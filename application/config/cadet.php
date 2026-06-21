<?php
defined('BASEPATH') or exit('No direct script access allowed');

$config['cadet_private_storage'] = getenv('CADET_PRIVATE_STORAGE')
    ?: dirname(APPPATH) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'cadets';
$config['cadet_upload_max_mb'] = max(1, (int) (getenv('CADET_UPLOAD_MAX_MB') ?: 12));
$config['cadet_session_start_year'] = 2019;
$config['cadet_session_future_years'] = 10;
$config['cadet_public_document_viewing'] = getenv('CADET_PUBLIC_DOCUMENT_VIEWING') !== 'false';
$config['cadet_verification_rate_limit'] = max(5, (int) (getenv('CADET_VERIFICATION_RATE_LIMIT') ?: 30));
