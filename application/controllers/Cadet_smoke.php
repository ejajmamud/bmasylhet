<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cadet_smoke extends CI_Controller
{
    public function index()
    {
        if (! is_cli()) {
            show_404();
        }
        $this->load->database();
        $this->load->library('session');
        $this->load->model('cadet_model');
        $this->cadet_model->ensureSchema();

        $department = $this->db->where('code', 'ENGINE')->get('departments')->row_array();
        $cadet = $this->cadet_model->findPublic($department['id'], 'BMAS-0037', '2002-08-02');
        if (! $cadet) {
            throw new RuntimeException('Published BMAS-0037 record was not found.');
        }
        $activeDocuments = array_filter($cadet['documents'], static function ($document) {
            return ! empty($document['id']) && $document['status'] === 'active';
        });
        if (count($activeDocuments) !== 4) {
            throw new RuntimeException('Expected four active certificate documents.');
        }
        if (! $cadet['photo_thumbnail_path']) {
            throw new RuntimeException('Expected a cadet photograph.');
        }
        $token = $this->cadet_model->qrTokenForCadet($cadet);
        if (! $this->cadet_model->findByQrToken($token)) {
            throw new RuntimeException('QR token lookup failed.');
        }

        echo "Cadet smoke test passed.\n";
        echo "Record: {$cadet['cadet_number']} - {$cadet['full_name']}\n";
        echo "Documents: " . count($activeDocuments) . "/4\n";
        echo "QR URL: " . site_url('verify/qr/' . $token) . "\n";
    }
}
