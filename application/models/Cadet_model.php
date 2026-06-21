<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cadet_model extends CI_Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_ARCHIVED = 'archived';

    private $documentTypes = [
        ['academic_transcript', 'Academic Transcript', 1],
        ['character_certificate', 'Nationality and Character Certificate', 2],
        ['six_basic_courses', 'Certificate of Six Basic Courses', 3],
        ['pre_sea_course_certificate', 'Pre-Sea Marine Engineering Course Certificate', 4],
    ];

    public function ensureSchema()
    {
        $queries = [
            "CREATE TABLE IF NOT EXISTS `departments` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(120) NOT NULL,
                `code` varchar(30) NOT NULL,
                `status` varchar(30) NOT NULL DEFAULT 'active',
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `departments_code_unique` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `cadets` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `uuid` char(36) NOT NULL,
                `department_id` bigint unsigned NOT NULL,
                `cadet_number` varchar(80) NOT NULL,
                `cadet_number_normalized` varchar(80) NOT NULL,
                `full_name` varchar(160) NOT NULL,
                `date_of_birth` date NOT NULL,
                `batch_number` smallint unsigned NOT NULL,
                `session_start_year` smallint unsigned NOT NULL,
                `session_end_year` smallint unsigned NOT NULL,
                `photo_path` varchar(255) DEFAULT NULL,
                `photo_thumbnail_path` varchar(255) DEFAULT NULL,
                `photo_mime_type` varchar(100) DEFAULT NULL,
                `photo_size_bytes` bigint unsigned DEFAULT NULL,
                `photo_sha256` char(64) DEFAULT NULL,
                `status` varchar(30) NOT NULL DEFAULT 'draft',
                `public_visibility` varchar(30) NOT NULL DEFAULT 'standard',
                `published_at` datetime DEFAULT NULL,
                `suspended_at` datetime DEFAULT NULL,
                `suspension_reason` text DEFAULT NULL,
                `archived_at` datetime DEFAULT NULL,
                `created_by` int unsigned DEFAULT NULL,
                `updated_by` int unsigned DEFAULT NULL,
                `approved_by` int unsigned DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `cadets_uuid_unique` (`uuid`),
                UNIQUE KEY `cadets_department_number_unique` (`department_id`,`cadet_number_normalized`),
                KEY `cadets_department_status_index` (`department_id`,`status`),
                KEY `cadets_batch_session_index` (`batch_number`,`session_start_year`,`session_end_year`),
                KEY `cadets_full_name_index` (`full_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `document_types` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `code` varchar(80) NOT NULL,
                `name` varchar(160) NOT NULL,
                `department_id` bigint unsigned DEFAULT NULL,
                `display_order` smallint unsigned NOT NULL DEFAULT 1,
                `is_required` tinyint(1) NOT NULL DEFAULT 1,
                `status` varchar(30) NOT NULL DEFAULT 'active',
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `document_types_code_unique` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `cadet_documents` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `uuid` char(36) NOT NULL,
                `cadet_id` bigint unsigned NOT NULL,
                `document_type_id` bigint unsigned NOT NULL,
                `version` smallint unsigned NOT NULL DEFAULT 1,
                `disk` varchar(40) NOT NULL DEFAULT 'private',
                `path` varchar(255) NOT NULL,
                `original_filename` varchar(190) NOT NULL,
                `mime_type` varchar(100) NOT NULL,
                `extension` varchar(10) NOT NULL,
                `size_bytes` bigint unsigned NOT NULL,
                `sha256_hash` char(64) NOT NULL,
                `status` varchar(30) NOT NULL DEFAULT 'active',
                `uploaded_by` int unsigned DEFAULT NULL,
                `approved_by` int unsigned DEFAULT NULL,
                `approved_at` datetime DEFAULT NULL,
                `replaced_by_document_id` bigint unsigned DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `cadet_documents_uuid_unique` (`uuid`),
                KEY `cadet_documents_cadet_status_index` (`cadet_id`,`status`),
                KEY `cadet_documents_type_index` (`document_type_id`),
                KEY `cadet_documents_hash_index` (`sha256_hash`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `cadet_qr_tokens` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `cadet_id` bigint unsigned NOT NULL,
                `token_hash` char(64) NOT NULL,
                `token_version` smallint unsigned NOT NULL DEFAULT 1,
                `status` varchar(30) NOT NULL DEFAULT 'active',
                `issued_at` datetime NOT NULL,
                `rotated_at` datetime DEFAULT NULL,
                `disabled_at` datetime DEFAULT NULL,
                `created_by` int unsigned DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `cadet_qr_tokens_hash_unique` (`token_hash`),
                KEY `cadet_qr_tokens_cadet_status_index` (`cadet_id`,`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `cadet_verification_logs` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `cadet_id` bigint unsigned DEFAULT NULL,
                `department_id` bigint unsigned DEFAULT NULL,
                `verification_type` varchar(40) NOT NULL,
                `cadet_number_hash` char(64) DEFAULT NULL,
                `date_of_birth_hash` char(64) DEFAULT NULL,
                `result_status` varchar(40) NOT NULL,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` varchar(500) DEFAULT NULL,
                `referer` varchar(255) DEFAULT NULL,
                `verified_at` datetime NOT NULL,
                `metadata_json` longtext DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `cadet_verification_cadet_index` (`cadet_id`,`verified_at`),
                KEY `cadet_verification_ip_index` (`ip_address`,`verified_at`),
                KEY `cadet_verification_number_index` (`cadet_number_hash`,`verified_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `cadet_audit_logs` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `actor_user_id` int unsigned DEFAULT NULL,
                `action` varchar(120) NOT NULL,
                `entity_type` varchar(80) NOT NULL,
                `entity_id` bigint unsigned DEFAULT NULL,
                `before_json` longtext DEFAULT NULL,
                `after_json` longtext DEFAULT NULL,
                `reason` text DEFAULT NULL,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` varchar(500) DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `cadet_audit_entity_index` (`entity_type`,`entity_id`),
                KEY `cadet_audit_actor_index` (`actor_user_id`,`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        ];

        foreach ($queries as $query) {
            $this->db->query($query);
        }

        $this->seedReferenceData();
    }

    public function seedReferenceData()
    {
        foreach ([
            ['name' => 'Engine Department', 'code' => 'ENGINE'],
            ['name' => 'Nautical Department', 'code' => 'NAUTICAL'],
        ] as $department) {
            if (! $this->db->where('code', $department['code'])->get('departments')->num_rows()) {
                $this->db->insert('departments', $department);
            }
        }

        foreach ($this->documentTypes as $type) {
            if (! $this->db->where('code', $type[0])->get('document_types')->num_rows()) {
                $this->db->insert('document_types', [
                    'code' => $type[0],
                    'name' => $type[1],
                    'display_order' => $type[2],
                    'is_required' => 1,
                ]);
            }
        }
    }

    public function departments()
    {
        return $this->db->where('status', 'active')->order_by('name')->get('departments')->result_array();
    }

    public function documentTypes()
    {
        return $this->db->where('status', 'active')->order_by('display_order')->get('document_types')->result_array();
    }

    public function normalizeCadetNumber($value)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', trim((string) $value)));
    }

    public function uuid()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function all($filters = [])
    {
        $this->db
            ->select('cadets.*, departments.name AS department_name, departments.code AS department_code,
                (SELECT COUNT(*) FROM cadet_documents WHERE cadet_documents.cadet_id = cadets.id AND cadet_documents.status = "active") AS document_count')
            ->from('cadets')
            ->join('departments', 'departments.id = cadets.department_id');

        if (! empty($filters['q'])) {
            $this->db->group_start()
                ->like('cadets.cadet_number', $filters['q'])
                ->or_like('cadets.full_name', $filters['q'])
                ->group_end();
        }
        foreach (['department_id', 'status', 'batch_number', 'session_start_year'] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $this->db->where('cadets.' . $field, $filters[$field]);
            }
        }

        return $this->db->order_by('cadets.updated_at', 'DESC')->get()->result_array();
    }

    public function find($idOrUuid)
    {
        $field = is_numeric($idOrUuid) ? 'cadets.id' : 'cadets.uuid';
        $cadet = $this->db
            ->select('cadets.*, departments.name AS department_name, departments.code AS department_code')
            ->from('cadets')
            ->join('departments', 'departments.id = cadets.department_id')
            ->where($field, $idOrUuid)
            ->get()
            ->row_array();

        if ($cadet) {
            $cadet['documents'] = $this->documentsForCadet($cadet['id']);
        }
        return $cadet;
    }

    public function findPublic($departmentId, $cadetNumber, $dateOfBirth)
    {
        $cadet = $this->db
            ->select('cadets.*, departments.name AS department_name, departments.code AS department_code')
            ->from('cadets')
            ->join('departments', 'departments.id = cadets.department_id')
            ->where('cadets.department_id', (int) $departmentId)
            ->where('cadets.cadet_number_normalized', $this->normalizeCadetNumber($cadetNumber))
            ->where('cadets.date_of_birth', $dateOfBirth)
            ->where_in('cadets.status', [self::STATUS_PUBLISHED, self::STATUS_SUSPENDED])
            ->where('cadets.public_visibility !=', 'hidden')
            ->get()
            ->row_array();

        if ($cadet) {
            $cadet['documents'] = $this->documentsForCadet($cadet['id'], true);
        }
        return $cadet;
    }

    public function findByQrToken($token)
    {
        $cadet = $this->db
            ->select('cadets.*, departments.name AS department_name, departments.code AS department_code')
            ->from('cadet_qr_tokens')
            ->join('cadets', 'cadets.id = cadet_qr_tokens.cadet_id')
            ->join('departments', 'departments.id = cadets.department_id')
            ->where('cadet_qr_tokens.token_hash', hash('sha256', $token))
            ->where('cadet_qr_tokens.status', 'active')
            ->where_in('cadets.status', [self::STATUS_PUBLISHED, self::STATUS_SUSPENDED])
            ->get()
            ->row_array();

        if ($cadet) {
            $cadet['documents'] = $this->documentsForCadet($cadet['id'], true);
        }
        return $cadet;
    }

    public function documentsForCadet($cadetId, $activeOnly = false)
    {
        $this->db
            ->select('cadet_documents.*, document_types.code AS type_code, document_types.name AS type_name, document_types.display_order, document_types.is_required')
            ->from('document_types')
            ->join(
                'cadet_documents',
                'cadet_documents.document_type_id = document_types.id AND cadet_documents.cadet_id = ' . (int) $cadetId .
                ($activeOnly ? ' AND cadet_documents.status = "active"' : ' AND cadet_documents.status != "replaced"'),
                'left'
            )
            ->where('document_types.status', 'active')
            ->order_by('document_types.display_order');

        return $this->db->get()->result_array();
    }

    public function saveCadet($data, $id = null)
    {
        $before = $id ? $this->find($id) : null;
        $data['cadet_number_normalized'] = $this->normalizeCadetNumber($data['cadet_number']);
        $data['updated_by'] = (int) $this->session->userdata('user_id');

        if ($id) {
            $this->db->where('id', (int) $id)->update('cadets', $data);
            $cadetId = (int) $id;
            $action = 'cadet.updated';
        } else {
            $data['uuid'] = $this->uuid();
            $data['created_by'] = (int) $this->session->userdata('user_id');
            $this->db->insert('cadets', $data);
            $cadetId = (int) $this->db->insert_id();
            $action = 'cadet.created';
        }

        $this->audit($action, 'cadet', $cadetId, $before, $this->find($cadetId));
        return $cadetId;
    }

    public function activeDocumentCount($cadetId)
    {
        return $this->db->where(['cadet_id' => $cadetId, 'status' => 'active'])->get('cadet_documents')->num_rows();
    }

    public function storeDocument($cadetId, $documentTypeId, array $fileData)
    {
        $current = $this->db->where([
            'cadet_id' => $cadetId,
            'document_type_id' => $documentTypeId,
            'status' => 'active',
        ])->order_by('version', 'DESC')->get('cadet_documents')->row_array();

        $version = $current ? ((int) $current['version'] + 1) : 1;
        $record = $fileData + [
            'uuid' => $this->uuid(),
            'cadet_id' => $cadetId,
            'document_type_id' => $documentTypeId,
            'version' => $version,
            'status' => 'active',
            'uploaded_by' => (int) $this->session->userdata('user_id'),
            'approved_by' => (int) $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('cadet_documents', $record);
        $newId = (int) $this->db->insert_id();

        if ($current) {
            $this->db->where('id', $current['id'])->update('cadet_documents', [
                'status' => 'replaced',
                'replaced_by_document_id' => $newId,
            ]);
        }

        $this->audit('cadet.document_uploaded', 'cadet_document', $newId, $current, $record);
        return $newId;
    }

    public function publish($cadetId)
    {
        $cadet = $this->find($cadetId);
        if (! $cadet || empty($cadet['photo_thumbnail_path']) || $this->activeDocumentCount($cadetId) < 4) {
            return false;
        }

        $this->db->where('id', $cadetId)->update('cadets', [
            'status' => self::STATUS_PUBLISHED,
            'published_at' => date('Y-m-d H:i:s'),
            'approved_by' => (int) $this->session->userdata('user_id'),
            'updated_by' => (int) $this->session->userdata('user_id'),
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);
        $this->ensureQrToken($cadetId);
        $this->audit('cadet.published', 'cadet', $cadetId, $cadet, $this->find($cadetId));
        return true;
    }

    public function setStatus($cadetId, $status, $reason = null)
    {
        if (! in_array($status, [self::STATUS_DRAFT, self::STATUS_SUSPENDED, self::STATUS_ARCHIVED], true)) {
            return false;
        }
        $before = $this->find($cadetId);
        $data = ['status' => $status, 'updated_by' => (int) $this->session->userdata('user_id')];
        if ($status === self::STATUS_SUSPENDED) {
            $data['suspended_at'] = date('Y-m-d H:i:s');
            $data['suspension_reason'] = $reason;
        }
        if ($status === self::STATUS_ARCHIVED) {
            $data['archived_at'] = date('Y-m-d H:i:s');
        }
        $this->db->where('id', $cadetId)->update('cadets', $data);
        $this->audit('cadet.' . $status, 'cadet', $cadetId, $before, $this->find($cadetId), $reason);
        return true;
    }

    public function ensureQrToken($cadetId)
    {
        $active = $this->db->where(['cadet_id' => $cadetId, 'status' => 'active'])->get('cadet_qr_tokens')->row_array();
        if ($active) {
            return $active;
        }

        $cadet = $this->find($cadetId);
        $version = 1;
        $token = $this->publicQrToken($cadet['uuid'], $version);
        $record = [
            'cadet_id' => $cadetId,
            'token_hash' => hash('sha256', $token),
            'token_version' => $version,
            'status' => 'active',
            'issued_at' => date('Y-m-d H:i:s'),
            'created_by' => (int) $this->session->userdata('user_id'),
        ];
        $this->db->insert('cadet_qr_tokens', $record);
        return $record;
    }

    public function publicQrToken($uuid, $version)
    {
        $secret = config_item('encryption_key') ?: 'bma-cadet-verification';
        $payload = $uuid . ':' . (int) $version;
        return rtrim(strtr(base64_encode(hash_hmac('sha256', $payload, $secret, true)), '+/', '-_'), '=');
    }

    public function qrTokenForCadet(array $cadet)
    {
        $active = $this->db->where(['cadet_id' => $cadet['id'], 'status' => 'active'])->get('cadet_qr_tokens')->row_array();
        if (! $active) {
            $active = $this->ensureQrToken($cadet['id']);
        }
        return $this->publicQrToken($cadet['uuid'], $active['token_version']);
    }

    public function metrics()
    {
        $metrics = [
            'total' => $this->db->count_all('cadets'),
            'published' => $this->db->where('status', self::STATUS_PUBLISHED)->count_all_results('cadets'),
            'draft' => $this->db->where('status', self::STATUS_DRAFT)->count_all_results('cadets'),
            'suspended' => $this->db->where('status', self::STATUS_SUSPENDED)->count_all_results('cadets'),
            'today_verifications' => $this->db->where('verified_at >=', date('Y-m-d 00:00:00'))->count_all_results('cadet_verification_logs'),
            'successful_verifications' => $this->db->where('result_status', 'valid')->count_all_results('cadet_verification_logs'),
            'failed_verifications' => $this->db->where('result_status', 'not_found')->count_all_results('cadet_verification_logs'),
        ];
        foreach ($this->departments() as $department) {
            $metrics[strtolower($department['code'])] = $this->db->where('department_id', $department['id'])->count_all_results('cadets');
        }
        return $metrics;
    }

    public function logVerification($cadet, $departmentId, $number, $dateOfBirth, $status, $type = 'identity')
    {
        $this->db->insert('cadet_verification_logs', [
            'cadet_id' => $cadet ? $cadet['id'] : null,
            'department_id' => $departmentId ?: null,
            'verification_type' => $type,
            'cadet_number_hash' => $number === '' ? null : hash('sha256', $this->normalizeCadetNumber($number)),
            'date_of_birth_hash' => $dateOfBirth === '' ? null : hash('sha256', $dateOfBirth),
            'result_status' => $status,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr((string) $this->input->user_agent(), 0, 500),
            'referer' => substr((string) $this->input->server('HTTP_REFERER'), 0, 255),
            'verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function audit($action, $entityType, $entityId, $before = null, $after = null, $reason = null)
    {
        $ipAddress = is_cli() ? null : $this->input->ip_address();
        $userAgent = is_cli() ? 'CodeIgniter CLI' : substr((string) $this->input->user_agent(), 0, 500);
        $this->db->insert('cadet_audit_logs', [
            'actor_user_id' => $this->session->userdata('user_id') ?: null,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before_json' => $before === null ? null : json_encode($before, JSON_UNESCAPED_SLASHES),
            'after_json' => $after === null ? null : json_encode($after, JSON_UNESCAPED_SLASHES),
            'reason' => $reason,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
