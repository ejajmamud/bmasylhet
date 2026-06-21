# Cadet-Centric Certificate Verification Build Plan

## 1. Confirmed Product Model

The public verification unit is one cadet, not one individual certificate.

A verifier must provide all three values:

1. Department
2. Cadet Number
3. Date of Birth

An exact match must show:

- Verification status
- Cadet photograph
- Cadet full name
- Department
- Cadet number
- Date of birth, masked or formatted according to the public privacy setting
- Batch
- Session
- All four certificate documents

The four required document types are:

1. Academic Transcript
2. Nationality and Character Certificate
3. Certificate of Six Basic Courses
4. Pre-Sea Marine Engineering Course Certificate

For Nautical Department records, the fourth label must be configurable so the correct Nautical course certificate name can be used without changing the database structure.

## 2. Current-System Findings

The existing implementation is not yet suitable for this workflow.

- Public verification currently searches individual rows in `certificates`.
- A successful result currently returns only one certificate.
- No dedicated cadet master table exists.
- No secure four-document upload workflow exists in the Superadmin dashboard.
- No cadet photograph storage model exists.
- The admin dashboard still displays legacy LMS course, lesson, enrolment, and revenue widgets.
- Legacy LMS code also uses the `certificates` table and expects fields such as `course_id`, `student_id`, and `shareable_url`.
- The newer verification table uses a different structure, creating a table-name and behavior collision.
- CSRF protection is disabled globally.
- Current persistent uploads are stored under the public web root.
- No append-only audit log currently protects cadet and document changes.

The new module must use dedicated tables and must not build further functionality on the conflicting LMS `certificates` table.

## 3. Superadmin Dashboard Information Architecture

The primary Superadmin navigation must include:

- Dashboard
- Cadet Records
  - All Cadets
  - Add Cadet
  - Draft Records
  - Published Records
  - Archived Records
- Verification Logs
- Audit Logs
- Departments
- Website Settings
- Theme Settings
- System Settings

Legacy LMS menus should be hidden from the certificate-system Superadmin interface unless a legacy maintenance mode explicitly enables them.

### Dashboard Metrics

The dashboard must show:

- Total cadets
- Engine Department cadets
- Nautical Department cadets
- Fully published records
- Incomplete/draft records
- Records missing one or more documents
- Verifications today
- Successful verifications
- Failed verification attempts
- Recently added cadets
- Recently updated documents

## 4. Manual Data Entry Workflow

### Step 1: Identity

Required fields:

- Department
  - Engine Department
  - Nautical Department
- Cadet Number
- Date of Birth

Rules:

- Cadet number must be trimmed and converted to uppercase.
- Internal spaces must be removed.
- Hyphens may be normalized according to the configured format.
- Date of birth must be stored as a database `DATE`.
- The combination of department and normalized cadet number must be unique.
- Before showing the full form, the system must check for an existing cadet.
- If found, Superadmin must be offered `Open Existing Record`; duplicate creation must be blocked.

### Step 2: Cadet Profile

Required fields:

- Cadet Full Name
- Student Image
- Batch
- Session Start Year
- Session End Year

Department and identity values from Step 1 remain visible and editable only with explicit confirmation.

Batch behavior:

- Store batch as an integer.
- Display ordinal labels such as `1st`, `2nd`, `3rd`, and `4th`.
- Initial dropdown should provide at least batches 1 through 20.
- Labels must be generated programmatically rather than stored as text.

Session behavior:

- Use two year dropdowns.
- Start-year options begin at 2019.
- Maximum option is the current year plus 10.
- Selecting a start year should automatically suggest the following year as the end year.
- End year must be greater than or equal to start year.
- Store the two years separately as integers.

Student image behavior:

- Accept JPEG, JPG, or PNG.
- Validate MIME type and extension.
- Enforce a configurable size limit.
- Re-encode uploaded images to remove unsafe metadata.
- Generate a private original and a safe display thumbnail.
- The public page must use the thumbnail, never the raw source upload.

### Step 3: Four Certificate Uploads

The form must provide exactly four named upload controls:

- Academic Transcript
- Nationality and Character Certificate
- Certificate of Six Basic Courses
- Pre-Sea Marine Engineering Course Certificate

Rules:

- Accept PDF, JPEG, JPG, and PNG.
- Each slot maps to a fixed document type, not a user-entered label.
- Replacing a file must create a new document version and retain an audit trail.
- Original filenames are metadata only; storage filenames must be random.
- File MIME, extension, size, and SHA-256 hash must be validated.
- Duplicate file hashes must trigger a warning.
- Files must be stored outside the public web root.
- Draft records may be saved with missing documents.
- Publishing must be blocked until the photo, required identity fields, and all four documents are present and valid.

### Step 4: Review and Publish

Before publication, show a single review screen containing:

- Cadet identity summary
- Photograph preview
- Batch and session
- Four document slots with preview status
- Duplicate warnings
- Existing-record warnings
- Audit note field

Available actions:

- Save Draft
- Publish
- Cancel

Publishing must:

- Mark the cadet record as published.
- Mark all four document records as active.
- Generate or activate the cadet QR token.
- Record the actor, IP address, timestamp, and before/after data.

## 5. Cadet Management Screens

### All Cadets

The table must support:

- Search by cadet number or name
- Filter by department
- Filter by batch
- Filter by session
- Filter by status
- Filter by missing document
- Sort by recently created or updated

Columns:

- Photo
- Cadet number
- Full name
- Department
- Batch
- Session
- Document completion, for example `4/4`
- Public status
- Last updated
- Actions

Actions:

- View
- Edit
- Manage Documents
- Preview Public Result
- Publish/Unpublish
- Suspend
- Archive

Hard deletion must not be available through the normal UI.

### Cadet Detail

The detail screen must contain:

- Profile section
- Four-document section
- Verification URL and QR section
- Verification activity
- Audit timeline
- Status controls

## 6. Proposed Database Design

### `departments`

- `id`
- `name`
- `code`
- `status`
- timestamps

Seed values:

- `ENGINE`, Engine Department
- `NAUTICAL`, Nautical Department

### `cadets`

- `id`
- `uuid`
- `department_id`
- `cadet_number`
- `cadet_number_normalized`
- `full_name`
- `date_of_birth`
- `batch_number`
- `session_start_year`
- `session_end_year`
- `photo_path`
- `photo_thumbnail_path`
- `photo_mime_type`
- `photo_size_bytes`
- `photo_sha256`
- `status`
- `public_visibility`
- `published_at`
- `suspended_at`
- `suspension_reason`
- `archived_at`
- `created_by`
- `updated_by`
- `approved_by`
- timestamps

Required constraints:

- Unique `uuid`
- Unique `(department_id, cadet_number_normalized)`
- Index `(department_id, status)`
- Index `(batch_number, session_start_year, session_end_year)`
- Index `full_name`

Statuses:

- `draft`
- `published`
- `suspended`
- `archived`

### `document_types`

- `id`
- `code`
- `name`
- `department_id`, nullable when shared by both departments
- `display_order`
- `is_required`
- `status`
- timestamps

Seed codes:

- `academic_transcript`
- `character_certificate`
- `six_basic_courses`
- `pre_sea_course_certificate`

### `cadet_documents`

- `id`
- `uuid`
- `cadet_id`
- `document_type_id`
- `version`
- `disk`
- `path`
- `original_filename`
- `mime_type`
- `extension`
- `size_bytes`
- `sha256_hash`
- `status`
- `uploaded_by`
- `approved_by`
- `approved_at`
- `replaced_by_document_id`
- timestamps

Required constraints:

- Unique `uuid`
- Unique active document per `(cadet_id, document_type_id)`
- Index `(cadet_id, status)`
- Index `sha256_hash`

Statuses:

- `pending`
- `active`
- `replaced`
- `rejected`
- `archived`

### `cadet_qr_tokens`

- `id`
- `cadet_id`
- `token_hash`
- `token_version`
- `status`
- `issued_at`
- `rotated_at`
- `disabled_at`
- `created_by`
- timestamps

Only the SHA-256 token hash must be stored.

### `cadet_verification_logs`

- `id`
- `cadet_id`, nullable for failed attempts
- `department_id`, nullable
- `verification_type`
- `cadet_number_hash`
- `date_of_birth_hash`
- `result_status`
- `ip_address`
- `user_agent`
- `referer`
- `verified_at`
- `metadata_json`

Raw failed-search DOB and cadet numbers must not be stored.

### `cadet_audit_logs`

- `id`
- `actor_user_id`
- `action`
- `entity_type`
- `entity_id`
- `before_json`
- `after_json`
- `reason`
- `ip_address`
- `user_agent`
- `created_at`

The application must not provide update or delete operations for audit rows.

## 7. Public Verification Workflow

The home page verification form must become:

- Department dropdown
- Cadet Number
- Date of Birth
- Image captcha
- Verify button

The existing certificate-number, student-name, and generic student-ID tabs should be removed from the primary workflow unless retained behind an optional advanced-search setting.

Lookup algorithm:

1. Validate captcha.
2. Normalize cadet number.
3. Validate department.
4. Parse DOB using a strict date format.
5. Hash submitted values for logging.
6. Perform one exact lookup using department, normalized cadet number, and DOB.
7. Ensure the cadet is published and publicly visible.
8. Load all four active documents.
9. Calculate the overall verification status.
10. Record the attempt.

The invalid response must remain generic:

`No matching cadet record could be verified.`

It must not reveal which field was incorrect.

## 8. Verification Result

For a complete valid record, show:

- Large `VALID` or `VERIFIED` status
- Cadet photograph
- Cadet full name
- Department
- Cadet number
- Batch
- Session
- Verification timestamp
- Four certificate rows/cards

Each certificate entry must show:

- Document name
- Status
- View document action, only when public document viewing is enabled
- Download action, only when enabled
- Document reference UUID

Overall status rules:

- `VALID`: cadet is published and all four required documents are active.
- `INCOMPLETE`: should never be publicly discoverable; only visible in admin preview.
- `SUSPENDED`: show authenticity record with a suspended warning; document access disabled by default.
- `ARCHIVED`: not publicly discoverable unless a future policy explicitly allows historical verification.

The result page must never expose:

- Storage paths
- Internal numeric IDs
- Raw audit data
- Staff details
- Original filenames
- Document hashes

## 9. File Access and Storage

Production certificate files must not remain in `/var/www/html/uploads`.

Recommended container path:

`/var/www/private/cadet-documents`

Coolify must mount a persistent volume to that path.

File access must go through controllers that:

- Resolve a document UUID.
- Check cadet and document status.
- Apply public visibility rules.
- Set safe content headers.
- Prevent path traversal.
- Log access where required.

Direct web-server access to original certificate files must be impossible.

## 10. Authorization

Initial release:

- Root Superadmin can create, edit, publish, suspend, archive, and replace documents.
- Restricted admins may be granted explicit permissions later.

New permission keys:

- `cadet_view`
- `cadet_create`
- `cadet_edit`
- `cadet_publish`
- `cadet_suspend`
- `cadet_archive`
- `cadet_document_upload`
- `cadet_document_replace`
- `verification_log_view`
- `cadet_audit_view`

Server-side permission checks must protect every action. Navigation visibility alone is not authorization.

## 11. Security Release Blockers

The following must be resolved before production data entry:

- Enable and test CodeIgniter CSRF protection for admin forms.
- Store certificates and source photographs outside the public web root.
- Validate MIME using server-side file inspection, not browser-provided MIME alone.
- Randomize all stored filenames.
- Add maximum upload size controls in PHP, the web server, and application validation.
- Add rate limiting to public verification.
- Require captcha for the public verification form.
- Prevent duplicate cadet identities.
- Add append-only audit logging.
- Escape all displayed metadata.
- Add controlled document streaming.
- Back up both database records and private document storage.

## 12. Legacy Migration Strategy

To avoid the existing LMS collision:

1. Create the new cadet-specific tables without modifying the legacy LMS certificate add-on.
2. Move the current sample verification record into the new cadet structure or remove it after confirmation.
3. Update public verification routes to query cadets.
4. Replace the public result view with the four-document result.
5. Hide legacy LMS certificate and course interfaces from the certificate-system dashboard.
6. Retire the current `certificate_qr_tokens` flow after the cadet QR flow is verified.
7. Do not drop the conflicting legacy tables until a backup and compatibility review are complete.

## 13. Implementation Phases

### Phase 1: Foundation

- Add schema migration runner suitable for this CodeIgniter deployment.
- Create departments, cadets, document types, documents, QR tokens, verification logs, and audit logs.
- Seed departments and four document types.
- Add private-storage configuration.
- Add models and validation services.

### Phase 2: Superadmin Cadet Module

- Add dashboard navigation.
- Add cadet list and filters.
- Add the stepped manual-entry form.
- Add four secure upload slots.
- Add photo processing.
- Add draft, review, publish, suspend, and archive behavior.
- Add detail and audit views.

### Phase 3: Public Verification

- Replace the current search form with Department + Cadet Number + DOB.
- Add strict exact-match lookup.
- Add cadet profile and four-document result.
- Add generic invalid result.
- Add rate limiting, captcha, and verification logging.
- Add cadet QR verification.

### Phase 4: Dashboard Conversion

- Replace LMS dashboard metrics with cadet and verification metrics.
- Hide obsolete LMS navigation.
- Apply the selected Marine Academy theme consistently.
- Add responsive tables and mobile result layouts.

### Phase 5: Security and QA

- Enable CSRF.
- Verify private storage and authorization.
- Test duplicate identity blocking.
- Test missing document publication blocking.
- Test file replacement/version history.
- Test suspended and archived behavior.
- Test Engine and Nautical records.
- Test desktop and mobile layouts.
- Test backup and restore.

## 14. Acceptance Criteria

- Superadmin can create an Engine or Nautical cadet.
- Cadet number and DOB are required.
- Duplicate department/cadet-number records are blocked.
- Full name, photo, batch, and two-year session are stored.
- All four named certificate upload fields are available.
- Draft records can be incomplete.
- Publishing is blocked until all required information and documents exist.
- Public verification requires Department + Cadet Number + DOB.
- A valid match shows the cadet photo, details, and all four certificates.
- An invalid match does not reveal which input failed.
- Certificate originals are not directly accessible by URL.
- All create, edit, upload, replace, publish, suspend, and archive actions are audited.
- Dashboard metrics reflect the cadet verification system rather than the legacy LMS.
