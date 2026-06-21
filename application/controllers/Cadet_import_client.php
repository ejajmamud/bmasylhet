<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cadet_import_client extends CI_Controller
{
    public function index()
    {
        if (! is_cli()) {
            show_404();
        }

        $sourceDirectory = getenv('CADET_IMPORT_SOURCE') ?: dirname(APPPATH) . '/assets/global/client_files';
        $portraitPath = getenv('CADET_IMPORT_PORTRAIT') ?: '';

        $this->load->database();
        $this->load->library('session');
        $this->load->model('cadet_model');
        $this->config->load('cadet');
        $this->cadet_model->ensureSchema();

        $required = [
            'academic_transcript' => $sourceDirectory . '/certificate.jpeg',
            'character_certificate' => $sourceDirectory . '/37.pdf',
            'six_basic_courses' => $sourceDirectory . '/37_1.pdf',
            'pre_sea_course_certificate' => $sourceDirectory . '/Pre-Sea.jpeg',
        ];
        foreach ($required as $path) {
            if (! is_file($path)) {
                throw new RuntimeException('Missing client document: ' . $path);
            }
        }
        if (! is_file($portraitPath)) {
            throw new RuntimeException('Provide the extracted BMAS-0037 portrait as the second argument.');
        }

        $department = $this->db->where('code', 'ENGINE')->get('departments')->row_array();
        $existing = $this->db
            ->where('department_id', $department['id'])
            ->where('cadet_number_normalized', 'BMAS0037')
            ->get('cadets')
            ->row_array();

        $cadetId = $this->cadet_model->saveCadet([
            'department_id' => $department['id'],
            'cadet_number' => 'BMAS-0037',
            'full_name' => 'MD. AL AMIN',
            'date_of_birth' => '2002-08-02',
            'batch_number' => 2,
            'session_start_year' => 2022,
            'session_end_year' => 2023,
        ], $existing['id'] ?? null);

        $storageRoot = rtrim(config_item('cadet_private_storage'), '/\\');
        $photoDirectory = $storageRoot . '/cadets/' . $cadetId . '/photo';
        $this->ensureDirectory($photoDirectory);
        $photoOriginal = 'cadets/' . $cadetId . '/photo/client-portrait.jpg';
        $photoThumbnail = 'cadets/' . $cadetId . '/photo/client-portrait-thumb.jpg';
        copy($portraitPath, $storageRoot . '/' . $photoOriginal);
        $this->makePortrait($portraitPath, $storageRoot . '/' . $photoThumbnail);
        $this->makeWebReadable($storageRoot . '/' . $photoOriginal);
        $this->makeWebReadable($storageRoot . '/' . $photoThumbnail);
        $this->db->where('id', $cadetId)->update('cadets', [
            'photo_path' => $photoOriginal,
            'photo_thumbnail_path' => $photoThumbnail,
            'photo_mime_type' => 'image/jpeg',
            'photo_size_bytes' => filesize($storageRoot . '/' . $photoOriginal),
            'photo_sha256' => hash_file('sha256', $storageRoot . '/' . $photoOriginal),
        ]);

        $types = [];
        foreach ($this->cadet_model->documentTypes() as $type) {
            $types[$type['code']] = $type;
        }
        foreach ($required as $code => $source) {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($source);
            $extension = $mime === 'application/pdf' ? 'pdf' : 'jpg';
            $directory = 'cadets/' . $cadetId . '/documents/' . $code;
            $this->ensureDirectory($storageRoot . '/' . $directory);
            $relative = $directory . '/client-' . $code . '.' . $extension;
            copy($source, $storageRoot . '/' . $relative);
            $this->makeWebReadable($storageRoot . '/' . $relative);
            $hash = hash_file('sha256', $storageRoot . '/' . $relative);

            $current = $this->db->where([
                'cadet_id' => $cadetId,
                'document_type_id' => $types[$code]['id'],
                'status' => 'active',
                'sha256_hash' => $hash,
            ])->get('cadet_documents')->row_array();
            if (! $current) {
                $this->cadet_model->storeDocument($cadetId, $types[$code]['id'], [
                    'disk' => 'private',
                    'path' => $relative,
                    'original_filename' => basename($source),
                    'mime_type' => $mime,
                    'extension' => $extension,
                    'size_bytes' => filesize($storageRoot . '/' . $relative),
                    'sha256_hash' => $hash,
                ]);
            }
        }

        if (! $this->cadet_model->publish($cadetId)) {
            throw new RuntimeException('The imported record did not satisfy publication requirements.');
        }
        echo "Imported and published BMAS-0037 for MD. AL AMIN.\n";
    }

    private function makePortrait($source, $destination)
    {
        $image = imagecreatefromjpeg($source);
        if (! $image) {
            throw new RuntimeException('The extracted cadet portrait is invalid.');
        }
        $width = imagesx($image);
        $height = imagesy($image);
        $canvas = imagecreatetruecolor(480, 600);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, 480, 600, $width, $height);
        imagejpeg($canvas, $destination, 90);
        imagedestroy($canvas);
        imagedestroy($image);
    }

    private function ensureDirectory($path)
    {
        if (! is_dir($path) && ! mkdir($path, 0775, true) && ! is_dir($path)) {
            throw new RuntimeException('Unable to create import storage directory.');
        }
        chmod($path, 0775);
    }

    private function makeWebReadable($path)
    {
        chmod($path, 0644);
        if (function_exists('posix_getpwnam') && posix_getpwnam('www-data')) {
            @chown($path, 'www-data');
            @chgrp($path, 'www-data');
            $directory = dirname($path);
            while ($directory && strpos($directory, rtrim(config_item('cadet_private_storage'), '/\\')) === 0) {
                @chown($directory, 'www-data');
                @chgrp($directory, 'www-data');
                @chmod($directory, 0770);
                if ($directory === rtrim(config_item('cadet_private_storage'), '/\\')) {
                    break;
                }
                $directory = dirname($directory);
            }
        }
    }
}
