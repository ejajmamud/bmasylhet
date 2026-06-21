<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verification extends CI_Controller
{
    private $theme = 'default-new';
    private $publicStatuses = ['published', 'valid', 'expired', 'revoked', 'suspended'];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'security']);

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
            'institutions' => $this->db->order_by('name', 'ASC')->get('institutions')->result_array(),
        ]);
    }

    public function certificate()
    {
        $this->requirePost();
        if (! $this->captchaIsValid($this->input->post('captcha'), 'certificate')) {
            return $this->invalid('certificate_number', $this->input->post('certificate_number'), 'The verification code did not match.');
        }

        $number = $this->normalizeCertificateNumber($this->input->post('certificate_number'));
        if ($number === '') {
            return $this->invalid('certificate_number', '', 'Enter a certificate number to verify.');
        }

        $certificate = $this->certificateQuery()
            ->where('UPPER(REPLACE(REPLACE(REPLACE(certificates.certificate_number, " ", ""), "-", ""), "/", "")) =', $number)
            ->get()
            ->row_array();

        return $this->showCertificateOrInvalid($certificate, 'certificate_number', $number);
    }

    public function student_id()
    {
        $this->requirePost();

        $studentId = trim((string) $this->input->post('student_id'));
        if ($studentId === '') {
            return $this->invalid('student_id', '', 'Enter a student ID to verify.');
        }

        $query = $this->certificateQuery()->where('UPPER(certificates.student_identifier_snapshot)', strtoupper($studentId));
        if ($this->input->post('institution_id')) {
            $query->where('certificates.institution_id', (int) $this->input->post('institution_id'));
        }

        $matches = $query->limit(26)->get()->result_array();
        if (count($matches) === 1) {
            return $this->showCertificateOrInvalid($matches[0], 'student_id', $studentId);
        }

        return $this->showCandidatesOrInvalid($matches, 'student_id', $studentId);
    }

    public function student_name()
    {
        $this->requirePost();
        if (! $this->captchaIsValid($this->input->post('captcha'), 'name')) {
            return $this->invalid('student_name', $this->input->post('student_name'), 'The verification code did not match.');
        }

        $name = preg_replace('/\s+/', ' ', trim((string) $this->input->post('student_name')));
        $institutionId = (int) $this->input->post('institution_id');
        $issueYear = trim((string) $this->input->post('issue_year'));
        $certificateType = trim((string) $this->input->post('certificate_type'));

        if (strlen($name) < 3) {
            return $this->invalid('student_name', $name, 'Enter at least 3 characters of the student name.');
        }

        if (! $institutionId && $issueYear === '' && $certificateType === '') {
            return $this->invalid('student_name', $name, 'Use an institution, issue year, or certificate type with name search.');
        }

        $query = $this->certificateQuery()
            ->group_start()
            ->where('UPPER(certificates.student_name_snapshot)', strtoupper($name))
            ->or_like('UPPER(certificates.student_name_snapshot)', strtoupper($name), 'after')
            ->group_end();

        if ($institutionId) {
            $query->where('certificates.institution_id', $institutionId);
        }
        if ($issueYear !== '') {
            $query->where('YEAR(certificates.issue_date)', (int) $issueYear);
        }
        if ($certificateType !== '') {
            $query->like('certificate_types.name', $certificateType);
        }

        $matches = $query->limit(26)->get()->result_array();
        if (count($matches) === 1) {
            return $this->showCertificateOrInvalid($matches[0], 'student_name', $name);
        }

        return $this->showCandidatesOrInvalid($matches, 'student_name', $name);
    }

    public function qr($token = '')
    {
        $token = trim((string) $token);
        if ($token === '' || ! preg_match('/^[A-Za-z0-9_\-\.]{16,200}$/', $token)) {
            return $this->invalid('qr', $token, null, 'invalid_token');
        }

        $tokenHash = hash('sha256', $token);
        $certificate = $this->certificateQuery()
            ->join('certificate_qr_tokens', 'certificate_qr_tokens.certificate_id = certificates.id')
            ->where('certificate_qr_tokens.token_hash', $tokenHash)
            ->where('certificate_qr_tokens.status', 'active')
            ->get()
            ->row_array();

        return $this->showCertificateOrInvalid($certificate, 'qr', $tokenHash, null, 'invalid_token');
    }

    public function result($uuid = '')
    {
        $certificate = $this->certificateQuery()->where('certificates.uuid', $uuid)->get()->row_array();
        return $this->showCertificateOrInvalid($certificate, 'manual_url', $uuid);
    }

    public function print_receipt($uuid = '')
    {
        $certificate = $this->certificateQuery()->where('certificates.uuid', $uuid)->get()->row_array();
        if (! $certificate || ! in_array($certificate['status'], $this->publicStatuses, true)) {
            show_404();
        }

        $data = $this->baseData(['certificate' => $certificate, 'page_title' => 'Verification Receipt']);
        $this->load->view('frontend/' . $this->theme . '/verification_print', $data);
    }

    public function captcha()
    {
        $scope = strtolower((string) $this->input->get('scope'));
        if (! in_array($scope, ['certificate', 'name'], true)) {
            $scope = 'certificate';
        }

        $code = (string) random_int(10000, 99999);
        $this->session->set_userdata('verification_captcha_' . $scope, $code);

        $image = imagecreatetruecolor(150, 46);
        $background = imagecolorallocate($image, 247, 250, 252);
        $brand = imagecolorallocate($image, 0, 166, 62);
        $muted = imagecolorallocate($image, 113, 128, 150);
        imagefilledrectangle($image, 0, 0, 150, 46, $background);
        for ($i = 0; $i < 6; $i++) {
            imageline($image, random_int(0, 150), random_int(0, 46), random_int(0, 150), random_int(0, 46), $muted);
        }
        imagestring($image, 5, 47, 14, $code, $brand);

        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        imagepng($image);
        imagedestroy($image);
    }

    private function certificateQuery()
    {
        return $this->db
            ->select('certificates.*, institutions.name as institution_name, institutions.short_code as institution_code, certificate_types.name as certificate_type_name, programs.name as program_name')
            ->from('certificates')
            ->join('institutions', 'institutions.id = certificates.institution_id')
            ->join('certificate_types', 'certificate_types.id = certificates.certificate_type_id')
            ->join('programs', 'programs.id = certificates.program_id', 'left')
            ->where_in('certificates.status', $this->publicStatuses)
            ->where('certificates.public_visibility !=', 'hidden');
    }

    private function showCertificateOrInvalid($certificate, $type, $input, $message = null, $invalidStatus = 'not_found')
    {
        if (! $certificate) {
            return $this->invalid($type, $input, $message, $invalidStatus);
        }

        $this->logVerification($certificate['id'], $certificate['institution_id'], $type, $input, $certificate['status']);

        $this->render('verification_result', [
            'certificate' => $certificate,
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function showCandidatesOrInvalid($matches, $type, $input)
    {
        if (! $matches) {
            return $this->invalid($type, $input);
        }

        if (count($matches) > 25) {
            return $this->invalid($type, $input, 'Too many possible matches. Add more specific details and try again.');
        }

        $this->logVerification(null, null, $type, $input, 'candidate_list');
        $this->render('verification_candidates', ['matches' => $matches]);
    }

    private function invalid($type, $input, $message = null, $resultStatus = 'not_found')
    {
        $this->logVerification(null, null, $type, (string) $input, $resultStatus);
        $this->render('verification_invalid', ['message' => $message]);
    }

    private function logVerification($certificateId, $institutionId, $type, $input, $status)
    {
        if (! $this->db->table_exists('verification_logs')) {
            return;
        }

        $this->db->insert('verification_logs', [
            'certificate_id' => $certificateId,
            'institution_id' => $institutionId,
            'verification_type' => $type,
            'input_hash' => $input === '' ? null : hash('sha256', strtoupper((string) $input)),
            'result_status' => $status,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr((string) $this->input->user_agent(), 0, 500),
            'referer' => substr((string) $this->input->server('HTTP_REFERER'), 0, 255),
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function captchaIsValid($value, $scope)
    {
        $sessionKey = 'verification_captcha_' . $scope;
        $expected = (string) $this->session->userdata($sessionKey);
        $this->session->unset_userdata($sessionKey);
        return $expected !== '' && hash_equals($expected, trim((string) $value));
    }

    private function normalizeCertificateNumber($value)
    {
        return strtoupper(str_replace([' ', '-', '/'], '', trim((string) $value)));
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

    public function mask($value)
    {
        $value = (string) $value;
        if (strlen($value) <= 6) {
            return str_repeat('*', max(strlen($value) - 2, 1)) . substr($value, -2);
        }
        return substr($value, 0, 4) . '-****-' . substr($value, -4);
    }
}
