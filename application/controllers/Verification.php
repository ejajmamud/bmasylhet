<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verification extends CI_Controller
{
    private $theme = 'default-new';
    private $storageRoot;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'security']);
        $this->load->model('cadet_model');
        $this->config->load('cadet');
        $this->cadet_model->ensureSchema();
        $this->storageRoot = rtrim(config_item('cadet_private_storage'), '/\\');
        $this->output
            ->set_header('X-Frame-Options: SAMEORIGIN')
            ->set_header('X-Content-Type-Options: nosniff')
            ->set_header('Referrer-Policy: strict-origin-when-cross-origin')
            ->set_header('Permissions-Policy: camera=(self), microphone=(), geolocation=()');

        $requestedLanguage = strtolower((string) $this->input->get('lang'));
        if (in_array($requestedLanguage, ['en', 'bn'], true)) {
            $this->session->set_userdata('verification_language', $requestedLanguage);
        }
        if (function_exists('get_frontend_settings') && get_frontend_settings('theme')) {
            $this->theme = get_frontend_settings('theme');
        }
    }

    public function index()
    {
        $this->render('verification_home', [
            'departments' => $this->cadet_model->departments(),
            'verification_form_token' => $this->verificationFormToken(),
        ]);
    }

    public function cadet()
    {
        $this->requirePost();
        $expectedToken = (string) $this->session->userdata('verification_form_token');
        $actualToken = (string) $this->input->post('_verification_token');
        if ($expectedToken === '' || ! hash_equals($expectedToken, $actualToken)) {
            return $this->invalid(null, null, null, 'invalid_form', 'Your verification form expired. Refresh the page and try again.');
        }
        if ($this->isRateLimited()) {
            return $this->invalid(null, null, null, 'rate_limited');
        }
        if (! $this->captchaIsValid($this->input->post('captcha'))) {
            return $this->invalid(null, null, null, 'invalid_captcha', 'The verification code did not match.');
        }

        $departmentId = (int) $this->input->post('department_id');
        $cadetNumber = trim((string) $this->input->post('cadet_number'));
        $dateOfBirth = trim((string) $this->input->post('date_of_birth'));
        $date = DateTime::createFromFormat('Y-m-d', $dateOfBirth);
        $validDepartment = $this->db->where(['id' => $departmentId, 'status' => 'active'])->get('departments')->num_rows();

        if (! $validDepartment || $this->cadet_model->normalizeCadetNumber($cadetNumber) === '' || ! $date || $date->format('Y-m-d') !== $dateOfBirth) {
            return $this->invalid($departmentId, $cadetNumber, $dateOfBirth);
        }

        $cadet = $this->cadet_model->findPublic($departmentId, $cadetNumber, $dateOfBirth);
        if (! $cadet) {
            return $this->invalid($departmentId, $cadetNumber, $dateOfBirth);
        }

        $status = $cadet['status'] === Cadet_model::STATUS_SUSPENDED ? 'suspended' : 'valid';
        $this->cadet_model->logVerification($cadet, $departmentId, $cadetNumber, $dateOfBirth, $status);
        $this->showResult($cadet);
    }

    public function qr($token = '')
    {
        $token = trim((string) $token);
        if ($token === '' || ! preg_match('/^[A-Za-z0-9_-]{32,100}$/', $token)) {
            return $this->invalid(null, '', '', 'invalid_token');
        }
        if ($this->isRateLimited()) {
            return $this->invalid(null, '', '', 'rate_limited');
        }

        $cadet = $this->cadet_model->findByQrToken($token);
        if (! $cadet) {
            $this->cadet_model->logVerification(null, null, $token, '', 'invalid_token', 'qr');
            return $this->render('verification_invalid');
        }

        $status = $cadet['status'] === Cadet_model::STATUS_SUSPENDED ? 'suspended' : 'valid';
        $this->cadet_model->logVerification($cadet, $cadet['department_id'], $cadet['cadet_number'], '', $status, 'qr');
        $this->showResult($cadet);
    }

    public function result($uuid = '')
    {
        $cadet = $this->cadet_model->find($uuid);
        if (! $cadet || ! in_array($cadet['status'], [Cadet_model::STATUS_PUBLISHED, Cadet_model::STATUS_SUSPENDED], true)) {
            return $this->render('verification_invalid');
        }
        $this->showResult($cadet);
    }

    public function print_receipt($uuid = '')
    {
        $cadet = $this->cadet_model->find($uuid);
        if (! $cadet || ! in_array($cadet['status'], [Cadet_model::STATUS_PUBLISHED, Cadet_model::STATUS_SUSPENDED], true)) {
            show_404();
        }
        $this->load->view('frontend/' . $this->theme . '/verification_print', $this->baseData([
            'cadet' => $cadet,
            'verified_at' => date('Y-m-d H:i:s'),
            'page_title' => 'Verification Receipt',
        ]));
    }

    public function photo($uuid = '')
    {
        $cadet = $this->cadet_model->find($uuid);
        if (! $cadet || empty($cadet['photo_thumbnail_path']) || ! in_array($cadet['status'], [Cadet_model::STATUS_PUBLISHED, Cadet_model::STATUS_SUSPENDED], true)) {
            show_404();
        }
        $this->streamPrivateFile($cadet['photo_thumbnail_path'], 'image/jpeg', 'cadet-photo.jpg');
    }

    public function document($uuid = '')
    {
        if (! config_item('cadet_public_document_viewing')) {
            show_404();
        }
        $document = $this->db
            ->select('cadet_documents.*, cadets.status AS cadet_status')
            ->from('cadet_documents')
            ->join('cadets', 'cadets.id = cadet_documents.cadet_id')
            ->where('cadet_documents.uuid', $uuid)
            ->where('cadet_documents.status', 'active')
            ->where('cadets.status', Cadet_model::STATUS_PUBLISHED)
            ->get()
            ->row_array();
        if (! $document) {
            show_404();
        }
        $this->streamPrivateFile($document['path'], $document['mime_type'], $document['original_filename']);
    }

    public function captcha()
    {
        $code = (string) random_int(10000, 99999);
        $this->session->set_userdata('cadet_verification_captcha', $code);

        $image = imagecreatetruecolor(150, 46);
        $background = imagecolorallocate($image, 247, 250, 252);
        $brand = imagecolorallocate($image, 0, 120, 50);
        $muted = imagecolorallocate($image, 125, 137, 130);
        imagefilledrectangle($image, 0, 0, 150, 46, $background);
        for ($i = 0; $i < 7; $i++) {
            imageline($image, random_int(0, 150), random_int(0, 46), random_int(0, 150), random_int(0, 46), $muted);
        }
        imagestring($image, 5, 47, 14, $code, $brand);
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        imagepng($image);
        imagedestroy($image);
    }

    // Legacy URLs now return to the single authoritative cadet verification form.
    public function certificate() { redirect('/'); }
    public function student_id() { redirect('/'); }
    public function student_name() { redirect('/'); }

    private function showResult($cadet)
    {
        $this->render('verification_result', [
            'cadet' => $cadet,
            'verified_at' => date('Y-m-d H:i:s'),
            'documents_complete' => count(array_filter($cadet['documents'], static function ($document) {
                return ! empty($document['id']) && $document['status'] === 'active';
            })) === 4,
        ]);
    }

    private function invalid($departmentId = null, $cadetNumber = '', $dateOfBirth = '', $status = 'not_found', $message = null)
    {
        $this->cadet_model->logVerification(null, $departmentId, (string) $cadetNumber, (string) $dateOfBirth, $status);
        $this->render('verification_invalid', ['message' => $message]);
    }

    private function isRateLimited()
    {
        $windowStart = date('Y-m-d H:i:s', time() - 600);
        $attempts = $this->db
            ->where('ip_address', $this->input->ip_address())
            ->where('verified_at >=', $windowStart)
            ->count_all_results('cadet_verification_logs');
        return $attempts >= (int) config_item('cadet_verification_rate_limit');
    }

    private function captchaIsValid($value)
    {
        $expected = (string) $this->session->userdata('cadet_verification_captcha');
        $this->session->unset_userdata('cadet_verification_captcha');
        return $expected !== '' && hash_equals($expected, trim((string) $value));
    }

    private function verificationFormToken()
    {
        $token = $this->session->userdata('verification_form_token');
        if (! $token) {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('verification_form_token', $token);
        }
        return $token;
    }

    private function requirePost()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            redirect('/');
        }
    }

    private function render($innerView, $data = [])
    {
        $this->load->view('frontend/' . $this->theme . '/verification_shell', $this->baseData($data + [
            'inner_view' => $innerView,
            'page_title' => get_settings('system_title') ?: 'Certificate Verification System',
        ]));
    }

    private function baseData($data = [])
    {
        $data['official_name'] = get_settings('system_name') ?: 'Bangladesh Marine Academy Sylhet';
        $data['brand_color'] = '#00A63E';
        $data['support_email'] = get_settings('system_email') ?: 'ejajjoy3@gmail.com';
        $data['language_code'] = in_array($this->session->userdata('verification_language'), ['en', 'bn'], true)
            ? $this->session->userdata('verification_language')
            : 'en';
        return $data;
    }

    private function streamPrivateFile($relative, $mime, $filename)
    {
        $relative = str_replace(['..', '\\'], ['', '/'], ltrim((string) $relative, '/'));
        $absolute = $this->storageRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        $root = realpath($this->storageRoot);
        $parent = realpath(dirname($absolute));
        if (! $root || ! $parent || strpos($parent, $root) !== 0 || ! is_file($absolute)) {
            show_404();
        }
        $this->output
            ->set_header('X-Content-Type-Options: nosniff')
            ->set_header('Cache-Control: private, no-store, max-age=0')
            ->set_header('Content-Disposition: inline; filename="' . str_replace('"', '', basename($filename)) . '"')
            ->set_header('Content-Type: ' . $mime)
            ->set_header('Content-Length: ' . filesize($absolute))
            ->set_output(file_get_contents($absolute));
    }
}
