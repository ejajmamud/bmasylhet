<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cadet_admin extends CI_Controller
{
    private $storageRoot;
    private $maxBytes;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'security', 'file']);
        $this->load->model('cadet_model');
        $this->config->load('cadet');
        $this->user_model->check_session_data('admin');
        check_permission('cadet_view');

        $this->cadet_model->ensureSchema();
        $this->output
            ->set_header('X-Frame-Options: SAMEORIGIN')
            ->set_header('X-Content-Type-Options: nosniff')
            ->set_header('Referrer-Policy: strict-origin-when-cross-origin');
        $this->storageRoot = rtrim(config_item('cadet_private_storage'), '/\\');
        $this->maxBytes = (int) config_item('cadet_upload_max_mb') * 1024 * 1024;
        $this->ensureDirectory($this->storageRoot);
    }

    public function index()
    {
        $filters = [
            'q' => trim((string) $this->input->get('q')),
            'department_id' => $this->input->get('department_id'),
            'status' => $this->input->get('status'),
            'batch_number' => $this->input->get('batch_number'),
            'session_start_year' => $this->input->get('session_start_year'),
        ];
        $this->render('cadets', [
            'cadets' => $this->cadet_model->all($filters),
            'departments' => $this->cadet_model->departments(),
            'filters' => $filters,
        ], 'Cadet Records');
    }

    public function create()
    {
        check_permission('cadet_create');
        $this->renderForm();
    }

    public function store()
    {
        check_permission('cadet_create');
        $this->requirePostAndToken();
        $this->persist();
    }

    public function edit($id)
    {
        check_permission('cadet_edit');
        $cadet = $this->requireCadet($id);
        $this->renderForm($cadet);
    }

    public function update($id)
    {
        check_permission('cadet_edit');
        $this->requirePostAndToken();
        $this->persist($this->requireCadet($id));
    }

    public function view($id)
    {
        $cadet = $this->requireCadet($id);
        $cadet['qr_token'] = $cadet['status'] === Cadet_model::STATUS_PUBLISHED
            ? $this->cadet_model->qrTokenForCadet($cadet)
            : null;

        $auditLogs = $this->db
            ->where('entity_type', 'cadet')
            ->where('entity_id', $cadet['id'])
            ->order_by('created_at', 'DESC')
            ->limit(20)
            ->get('cadet_audit_logs')
            ->result_array();

        $this->render('cadet_view', [
            'cadet' => $cadet,
            'audit_logs' => $auditLogs,
        ], 'Cadet Record');
    }

    public function publish($id)
    {
        check_permission('cadet_publish');
        $this->requirePostAndToken();
        if ($this->cadet_model->publish((int) $id)) {
            $this->session->set_flashdata('flash_message', 'Cadet record published successfully.');
        } else {
            $this->session->set_flashdata('error_message', 'Publishing requires a student photo and all four certificate documents.');
        }
        redirect(site_url('admin/cadets/view/' . (int) $id));
    }

    public function status($id, $status)
    {
        check_permission($status === 'suspended' ? 'cadet_suspend' : 'cadet_archive');
        $this->requirePostAndToken();
        $reason = trim((string) $this->input->post('reason'));
        if ($status === 'suspended' && $reason === '') {
            $this->session->set_flashdata('error_message', 'A suspension reason is required.');
        } else {
            $this->cadet_model->setStatus((int) $id, $status, $reason);
            $this->session->set_flashdata('flash_message', 'Cadet status updated.');
        }
        redirect(site_url('admin/cadets/view/' . (int) $id));
    }

    public function verificationLogs()
    {
        check_permission('verification_log_view');
        $logs = $this->db
            ->select('cadet_verification_logs.*, cadets.cadet_number, cadets.full_name, departments.name AS department_name')
            ->from('cadet_verification_logs')
            ->join('cadets', 'cadets.id = cadet_verification_logs.cadet_id', 'left')
            ->join('departments', 'departments.id = cadet_verification_logs.department_id', 'left')
            ->order_by('cadet_verification_logs.verified_at', 'DESC')
            ->limit(500)
            ->get()
            ->result_array();
        $this->render('cadet_verification_logs', ['logs' => $logs], 'Verification Logs');
    }

    public function auditLogs()
    {
        check_permission('cadet_audit_view');
        $logs = $this->db
            ->select('cadet_audit_logs.*, users.first_name, users.last_name')
            ->from('cadet_audit_logs')
            ->join('users', 'users.id = cadet_audit_logs.actor_user_id', 'left')
            ->order_by('cadet_audit_logs.created_at', 'DESC')
            ->limit(500)
            ->get()
            ->result_array();
        $this->render('cadet_audit_logs', ['logs' => $logs], 'Audit Logs');
    }

    public function photo($uuid)
    {
        $cadet = $this->cadet_model->find($uuid);
        if (! $cadet || empty($cadet['photo_thumbnail_path'])) {
            show_404();
        }
        $this->streamPrivateFile($cadet['photo_thumbnail_path'], $cadet['photo_mime_type'] ?: 'image/jpeg', false);
    }

    public function document($uuid)
    {
        $document = $this->db->where('uuid', $uuid)->get('cadet_documents')->row_array();
        if (! $document) {
            show_404();
        }
        $this->streamPrivateFile($document['path'], $document['mime_type'], true, $document['original_filename']);
    }

    private function persist($existing = null)
    {
        $createdFiles = [];
        $data = [
            'department_id' => (int) $this->input->post('department_id'),
            'cadet_number' => strtoupper(trim((string) $this->input->post('cadet_number'))),
            'date_of_birth' => trim((string) $this->input->post('date_of_birth')),
            'full_name' => preg_replace('/\s+/', ' ', trim((string) $this->input->post('full_name'))),
            'batch_number' => (int) $this->input->post('batch_number'),
            'session_start_year' => (int) $this->input->post('session_start_year'),
            'session_end_year' => (int) $this->input->post('session_end_year'),
        ];
        $errors = $this->validateCadetData($data, $existing);
        if ($errors) {
            $this->session->set_flashdata('error_message', implode(' ', $errors));
            return $this->renderForm(array_merge($existing ?: [], $data));
        }

        $this->db->trans_begin();
        try {
            $cadetId = $this->cadet_model->saveCadet($data, $existing['id'] ?? null);
            if (! empty($_FILES['student_image']['name'])) {
                $photo = $this->storePhoto($_FILES['student_image'], $cadetId);
                $createdFiles[] = $photo['photo_path'];
                $createdFiles[] = $photo['photo_thumbnail_path'];
                $before = $this->cadet_model->find($cadetId);
                $this->db->where('id', $cadetId)->update('cadets', $photo + [
                    'updated_by' => (int) $this->session->userdata('user_id'),
                ]);
                $this->cadet_model->audit('cadet.photo_updated', 'cadet', $cadetId, $before, $this->cadet_model->find($cadetId));
            }

            foreach ($this->cadet_model->documentTypes() as $type) {
                $field = 'document_' . $type['code'];
                if (! empty($_FILES[$field]['name'])) {
                    $fileData = $this->storeDocumentFile($_FILES[$field], $cadetId, $type['code']);
                    $createdFiles[] = $fileData['path'];
                    $this->cadet_model->storeDocument($cadetId, $type['id'], $fileData);
                }
            }

            if ($this->db->trans_status() === false) {
                throw new RuntimeException('The database could not save the cadet record.');
            }
            $this->db->trans_commit();
            $this->session->set_flashdata('flash_message', 'Cadet record saved successfully.');
            redirect(site_url('admin/cadets/view/' . $cadetId));
        } catch (Throwable $exception) {
            $this->db->trans_rollback();
            foreach ($createdFiles as $relativePath) {
                try {
                    $absolutePath = $this->absolutePrivatePath($relativePath);
                    if (is_file($absolutePath)) {
                        unlink($absolutePath);
                    }
                } catch (Throwable $cleanupException) {
                    log_message('error', 'Cadet file rollback cleanup failed: ' . $cleanupException->getMessage());
                }
            }
            log_message('error', 'Cadet save failed: ' . $exception->getMessage());
            $this->session->set_flashdata('error_message', $exception->getMessage());
            $this->renderForm(array_merge($existing ?: [], $data));
        }
    }

    private function validateCadetData($data, $existing)
    {
        $errors = [];
        $departmentExists = $this->db->where(['id' => $data['department_id'], 'status' => 'active'])->get('departments')->num_rows();
        if (! $departmentExists) {
            $errors[] = 'Select a valid department.';
        }
        if (! preg_match('/^[A-Za-z0-9\-\/ ]{3,80}$/', $data['cadet_number'])) {
            $errors[] = 'Enter a valid cadet number.';
        }
        $date = DateTime::createFromFormat('Y-m-d', $data['date_of_birth']);
        if (! $date || $date->format('Y-m-d') !== $data['date_of_birth'] || $date > new DateTime('today')) {
            $errors[] = 'Enter a valid date of birth.';
        }
        if (mb_strlen($data['full_name']) < 3 || mb_strlen($data['full_name']) > 160) {
            $errors[] = 'Enter the cadet full name.';
        }
        if ($data['batch_number'] < 1 || $data['batch_number'] > 100) {
            $errors[] = 'Select a valid batch.';
        }
        $maxYear = (int) date('Y') + (int) config_item('cadet_session_future_years');
        if ($data['session_start_year'] < 2019 || $data['session_start_year'] > $maxYear) {
            $errors[] = 'Select a valid session start year.';
        }
        if ($data['session_end_year'] < $data['session_start_year'] || $data['session_end_year'] > $maxYear) {
            $errors[] = 'Session end year must be on or after the start year.';
        }

        $duplicate = $this->db
            ->where('department_id', $data['department_id'])
            ->where('cadet_number_normalized', $this->cadet_model->normalizeCadetNumber($data['cadet_number']));
        if ($existing) {
            $duplicate->where('id !=', $existing['id']);
        }
        if ($duplicate->get('cadets')->num_rows()) {
            $errors[] = 'This department and cadet number already belong to another record.';
        }
        return $errors;
    }

    private function storePhoto($file, $cadetId)
    {
        $checked = $this->validateUpload($file, ['image/jpeg' => 'jpg', 'image/png' => 'png']);
        $directory = 'cadets/' . $cadetId . '/photo';
        $absoluteDirectory = $this->storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $directory);
        $this->ensureDirectory($absoluteDirectory);

        $base = bin2hex(random_bytes(16));
        $originalRelative = $directory . '/' . $base . '.' . $checked['extension'];
        $thumbRelative = $directory . '/' . $base . '-thumb.jpg';
        $originalAbsolute = $this->absolutePrivatePath($originalRelative);
        $thumbAbsolute = $this->absolutePrivatePath($thumbRelative);

        if (! move_uploaded_file($file['tmp_name'], $originalAbsolute)) {
            throw new RuntimeException('The student image could not be stored.');
        }
        $this->createThumbnail($originalAbsolute, $checked['mime'], $thumbAbsolute);

        return [
            'photo_path' => $originalRelative,
            'photo_thumbnail_path' => $thumbRelative,
            'photo_mime_type' => 'image/jpeg',
            'photo_size_bytes' => filesize($originalAbsolute),
            'photo_sha256' => hash_file('sha256', $originalAbsolute),
        ];
    }

    private function storeDocumentFile($file, $cadetId, $typeCode)
    {
        $checked = $this->validateUpload($file, [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ]);
        $directory = 'cadets/' . $cadetId . '/documents/' . $typeCode;
        $absoluteDirectory = $this->storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $directory);
        $this->ensureDirectory($absoluteDirectory);
        $relative = $directory . '/' . bin2hex(random_bytes(20)) . '.' . $checked['extension'];
        $absolute = $this->absolutePrivatePath($relative);
        if (! move_uploaded_file($file['tmp_name'], $absolute)) {
            throw new RuntimeException('A certificate document could not be stored.');
        }

        return [
            'disk' => 'private',
            'path' => $relative,
            'original_filename' => $this->safeOriginalFilename($file['name']),
            'mime_type' => $checked['mime'],
            'extension' => $checked['extension'],
            'size_bytes' => filesize($absolute),
            'sha256_hash' => hash_file('sha256', $absolute),
        ];
    }

    private function validateUpload($file, array $allowed)
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->uploadErrorMessage($file['error'] ?? UPLOAD_ERR_NO_FILE));
        }
        if (($file['size'] ?? 0) < 1 || $file['size'] > $this->maxBytes) {
            throw new RuntimeException('The uploaded file exceeds the allowed size.');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (! isset($allowed[$mime])) {
            throw new RuntimeException('Only PDF, JPEG, and PNG files are accepted for certificate records.');
        }
        return ['mime' => $mime, 'extension' => $allowed[$mime]];
    }

    private function createThumbnail($source, $mime, $destination)
    {
        $image = $mime === 'image/png' ? imagecreatefrompng($source) : imagecreatefromjpeg($source);
        if (! $image) {
            throw new RuntimeException('The student image is not a valid image.');
        }
        $width = imagesx($image);
        $height = imagesy($image);
        $targetWidth = 480;
        $targetHeight = 600;
        $sourceRatio = $width / max($height, 1);
        $targetRatio = $targetWidth / $targetHeight;
        if ($sourceRatio > $targetRatio) {
            $cropHeight = $height;
            $cropWidth = (int) ($height * $targetRatio);
            $sourceX = (int) (($width - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $width;
            $cropHeight = (int) ($width / $targetRatio);
            $sourceX = 0;
            $sourceY = (int) (($height - $cropHeight) / 2);
        }
        $thumb = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($thumb, $image, 0, 0, $sourceX, $sourceY, $targetWidth, $targetHeight, $cropWidth, $cropHeight);
        imagejpeg($thumb, $destination, 88);
        imagedestroy($thumb);
        imagedestroy($image);
    }

    private function renderForm($cadet = null)
    {
        $documents = [];
        if (! empty($cadet['id'])) {
            $fresh = $this->cadet_model->find($cadet['id']);
            $documents = $fresh['documents'];
            $cadet = array_merge($fresh, $cadet);
        }
        $this->render('cadet_form', [
            'cadet' => $cadet,
            'documents' => $documents,
            'departments' => $this->cadet_model->departments(),
            'document_types' => $this->cadet_model->documentTypes(),
            'form_token' => $this->formToken(),
            'max_upload_mb' => config_item('cadet_upload_max_mb'),
            'session_max_year' => (int) date('Y') + (int) config_item('cadet_session_future_years'),
        ], $cadet ? 'Edit Cadet' : 'Add Cadet');
    }

    private function render($view, $data, $title)
    {
        $data['page_name'] = $view;
        $data['page_title'] = $title;
        $data['form_token'] = $data['form_token'] ?? $this->formToken();
        $this->load->view('backend/index', $data);
    }

    private function requireCadet($id)
    {
        $cadet = $this->cadet_model->find((int) $id);
        if (! $cadet) {
            show_404();
        }
        return $cadet;
    }

    private function formToken()
    {
        $token = $this->session->userdata('cadet_form_token');
        if (! $token) {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('cadet_form_token', $token);
        }
        return $token;
    }

    private function requirePostAndToken()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_error('Method not allowed', 405);
        }
        $expected = (string) $this->session->userdata('cadet_form_token');
        $actual = (string) $this->input->post('_cadet_token');
        if ($expected === '' || ! hash_equals($expected, $actual)) {
            show_error('Your form session expired. Refresh the page and try again.', 419);
        }
    }

    private function streamPrivateFile($relative, $mime, $download = false, $filename = null)
    {
        $absolute = $this->absolutePrivatePath($relative);
        if (! is_file($absolute)) {
            show_404();
        }
        $name = $filename ?: basename($absolute);
        $disposition = $download ? 'inline' : 'inline';
        $this->output
            ->set_header('X-Content-Type-Options: nosniff')
            ->set_header('Cache-Control: private, no-store, max-age=0')
            ->set_header('Content-Disposition: ' . $disposition . '; filename="' . str_replace('"', '', $name) . '"')
            ->set_header('Content-Type: ' . $mime)
            ->set_header('Content-Length: ' . filesize($absolute))
            ->set_output(file_get_contents($absolute));
    }

    private function absolutePrivatePath($relative)
    {
        $relative = str_replace(['..', '\\'], ['', '/'], ltrim((string) $relative, '/'));
        $absolute = $this->storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        $root = realpath($this->storageRoot);
        $parent = realpath(dirname($absolute));
        if (! $root || ! $parent || strpos($parent, $root) !== 0) {
            throw new RuntimeException('Invalid private storage path.');
        }
        return $absolute;
    }

    private function ensureDirectory($path)
    {
        if (! is_dir($path) && ! mkdir($path, 0770, true) && ! is_dir($path)) {
            throw new RuntimeException('Private storage directory could not be created.');
        }
    }

    private function safeOriginalFilename($name)
    {
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '_', basename((string) $name));
        return substr($name ?: 'document', 0, 190);
    }

    private function uploadErrorMessage($code)
    {
        if ($code === UPLOAD_ERR_INI_SIZE || $code === UPLOAD_ERR_FORM_SIZE) {
            return 'The uploaded file exceeds the server size limit.';
        }
        if ($code === UPLOAD_ERR_NO_FILE) {
            return 'Select a file to upload.';
        }
        return 'The file upload did not complete successfully.';
    }
}
