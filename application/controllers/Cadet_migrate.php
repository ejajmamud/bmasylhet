<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cadet_migrate extends CI_Controller
{
    public function index()
    {
        if (! is_cli()) {
            show_404();
        }

        $this->load->database();
        $this->load->model('cadet_model');
        $this->cadet_model->ensureSchema();
        echo "Cadet verification schema is ready.\n";
    }
}
