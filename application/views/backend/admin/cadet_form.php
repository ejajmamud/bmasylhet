<?php
$editing = ! empty($cadet['id']);
$document_map = [];
foreach ($documents as $document) {
    $document_map[$document['type_code']] = $document;
}
$old = static function ($field, $default = '') use ($cadet) {
    return html_escape($cadet[$field] ?? $default);
};
$action = $editing ? site_url('admin/cadets/update/' . $cadet['id']) : site_url('admin/cadets/store');
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1"><i class="mdi mdi-account-card-details title_icon"></i> <?php echo $editing ? 'Edit Cadet' : 'Add Cadet'; ?></h4>
                    <p class="text-muted mb-0"><?php echo $editing ? html_escape($cadet['cadet_number'] . ' - ' . $cadet['full_name']) : 'Create the cadet profile and attach the four official certificates.'; ?></p>
                </div>
                <a href="<?php echo $editing ? site_url('admin/cadets/view/' . $cadet['id']) : site_url('admin/cadets'); ?>" class="btn btn-light"><i class="mdi mdi-arrow-left mr-1"></i> Back</a>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cadet-record-form">
    <input type="hidden" name="_cadet_token" value="<?php echo html_escape($form_token); ?>">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Cadet Identity</h4>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="department_id">Department <span class="text-danger">*</span></label>
                            <select class="form-control" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo (int) $department['id']; ?>" <?php echo (string) ($cadet['department_id'] ?? '') === (string) $department['id'] ? 'selected' : ''; ?>>
                                        <?php echo html_escape($department['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cadet_number">Cadet Number <span class="text-danger">*</span></label>
                            <input class="form-control text-uppercase" id="cadet_number" name="cadet_number" value="<?php echo $old('cadet_number'); ?>" maxlength="80" placeholder="BMAS-0037" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $old('date_of_birth'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="full_name">Cadet Full Name <span class="text-danger">*</span></label>
                            <input class="form-control text-uppercase" id="full_name" name="full_name" value="<?php echo $old('full_name'); ?>" maxlength="160" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="batch_number">Batch <span class="text-danger">*</span></label>
                            <select class="form-control" id="batch_number" name="batch_number" required>
                                <option value="">Select</option>
                                <?php for ($batch = 1; $batch <= 20; $batch++): ?>
                                    <?php $suffix = ($batch % 100 >= 11 && $batch % 100 <= 13) ? 'th' : (['th', 'st', 'nd', 'rd'][$batch % 10] ?? 'th'); ?>
                                    <option value="<?php echo $batch; ?>" <?php echo (int) ($cadet['batch_number'] ?? 0) === $batch ? 'selected' : ''; ?>><?php echo $batch . $suffix; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="session_start_year">Session From <span class="text-danger">*</span></label>
                            <select class="form-control" id="session_start_year" name="session_start_year" required>
                                <option value="">Year</option>
                                <?php for ($year = 2019; $year <= $session_max_year; $year++): ?>
                                    <option value="<?php echo $year; ?>" <?php echo (int) ($cadet['session_start_year'] ?? 0) === $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="session_end_year">Session To <span class="text-danger">*</span></label>
                            <select class="form-control" id="session_end_year" name="session_end_year" required>
                                <option value="">Year</option>
                                <?php for ($year = 2019; $year <= $session_max_year; $year++): ?>
                                    <option value="<?php echo $year; ?>" <?php echo (int) ($cadet['session_end_year'] ?? 0) === $year ? 'selected' : ''; ?>><?php echo $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="header-title mb-0">Certificate Documents</h4>
                        <span class="badge badge-light">PDF, JPG or PNG · Max <?php echo (int) $max_upload_mb; ?> MB each</span>
                    </div>
                    <div class="row">
                        <?php foreach ($document_types as $type): ?>
                            <?php $current = $document_map[$type['code']] ?? null; ?>
                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <label for="document_<?php echo html_escape($type['code']); ?>" class="font-weight-bold mb-0">
                                            <span <?php echo $type['code'] === 'pre_sea_course_certificate' ? 'id="pre-sea-document-label"' : ''; ?>>
                                                <?php echo html_escape(
                                                    $type['code'] === 'pre_sea_course_certificate' && ($cadet['department_code'] ?? '') === 'NAUTICAL'
                                                        ? 'Pre-Sea Marine Nautical Course Certificate'
                                                        : $type['name']
                                                ); ?>
                                            </span>
                                            <?php if (! $editing || ! $current['id']): ?><span class="text-danger">*</span><?php endif; ?>
                                        </label>
                                        <?php if ($current && $current['id']): ?>
                                            <span class="badge badge-success">Uploaded</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Missing</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($current && $current['id']): ?>
                                        <div class="small text-muted text-truncate mb-2"><?php echo html_escape($current['original_filename']); ?></div>
                                        <a href="<?php echo site_url('admin/cadets/document/' . $current['uuid']); ?>" target="_blank" class="btn btn-sm btn-light mb-2"><i class="mdi mdi-eye mr-1"></i> Preview</a>
                                    <?php endif; ?>
                                    <input
                                        class="form-control-file"
                                        type="file"
                                        id="document_<?php echo html_escape($type['code']); ?>"
                                        name="document_<?php echo html_escape($type['code']); ?>"
                                        accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                                        <?php echo (! $editing || ! $current['id']) ? 'required' : ''; ?>
                                    >
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Student Image</h4>
                    <?php if ($editing && ! empty($cadet['photo_thumbnail_path'])): ?>
                        <img src="<?php echo site_url('admin/cadets/photo/' . $cadet['uuid']); ?>" alt="<?php echo html_escape($cadet['full_name']); ?>" class="img-fluid rounded mb-3" style="width:100%;max-height:360px;object-fit:cover">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center rounded bg-light mb-3" style="height:280px">
                            <i class="mdi mdi-account-box-outline text-muted" style="font-size:72px"></i>
                        </div>
                    <?php endif; ?>
                    <input class="form-control-file" type="file" id="student_image" name="student_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" <?php echo ($editing && ! empty($cadet['photo_thumbnail_path'])) ? '' : 'required'; ?>>
                    <small class="form-text text-muted">JPG or PNG · Max <?php echo (int) $max_upload_mb; ?> MB</small>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="mdi mdi-content-save mr-1"></i> <?php echo $editing ? 'Save Changes' : 'Save Cadet Record'; ?>
                    </button>
                    <?php if ($editing): ?>
                        <a href="<?php echo site_url('admin/cadets/view/' . $cadet['id']); ?>" class="btn btn-light btn-block">Cancel</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
(function () {
    var start = document.getElementById('session_start_year');
    var end = document.getElementById('session_end_year');
    start.addEventListener('change', function () {
        var suggested = String(parseInt(start.value || '0', 10) + 1);
        if (suggested !== '1' && end.querySelector('option[value="' + suggested + '"]')) {
            end.value = suggested;
        }
    });
    document.getElementById('cadet_number').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('full_name').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    var department = document.getElementById('department_id');
    var preSeaLabel = document.getElementById('pre-sea-document-label');
    function updatePreSeaLabel() {
        if (!preSeaLabel) return;
        var selectedText = department.options[department.selectedIndex] ? department.options[department.selectedIndex].text : '';
        preSeaLabel.textContent = selectedText.indexOf('Nautical') !== -1
            ? 'Pre-Sea Marine Nautical Course Certificate'
            : 'Pre-Sea Marine Engineering Course Certificate';
    }
    department.addEventListener('change', updatePreSeaLabel);
    updatePreSeaLabel();
})();
</script>
