<?php
$batch = (int) $cadet['batch_number'];
$suffix = ($batch % 100 >= 11 && $batch % 100 <= 13) ? 'th' : (['th', 'st', 'nd', 'rd'][$batch % 10] ?? 'th');
$activeDocuments = array_filter($cadet['documents'], static function ($document) {
    return ! empty($document['id']) && $document['status'] === 'active';
});
$complete = count($activeDocuments) === 4 && ! empty($cadet['photo_thumbnail_path']);
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <h4 class="page-title mb-0 mr-2"><?php echo html_escape($cadet['full_name']); ?></h4>
                        <span class="badge badge-<?php echo $cadet['status'] === 'published' ? 'success' : ($cadet['status'] === 'suspended' ? 'danger' : ($cadet['status'] === 'archived' ? 'secondary' : 'warning')); ?>">
                            <?php echo html_escape(ucfirst($cadet['status'])); ?>
                        </span>
                    </div>
                    <p class="text-muted mb-0"><?php echo html_escape($cadet['cadet_number']); ?> · <?php echo html_escape($cadet['department_name']); ?></p>
                </div>
                <div class="d-flex flex-wrap">
                    <a href="<?php echo site_url('admin/cadets'); ?>" class="btn btn-light mr-2"><i class="mdi mdi-arrow-left mr-1"></i> Records</a>
                    <?php if (has_permission('cadet_edit')): ?>
                        <a href="<?php echo site_url('admin/cadets/edit/' . $cadet['id']); ?>" class="btn btn-primary"><i class="mdi mdi-pencil mr-1"></i> Edit</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body text-center">
                <?php if ($cadet['photo_thumbnail_path']): ?>
                    <img src="<?php echo site_url('admin/cadets/photo/' . $cadet['uuid']); ?>" alt="<?php echo html_escape($cadet['full_name']); ?>" class="img-fluid rounded mb-3" style="max-height:390px;object-fit:cover">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center rounded bg-light mb-3" style="height:300px"><i class="mdi mdi-account text-muted" style="font-size:72px"></i></div>
                <?php endif; ?>
                <h4><?php echo html_escape($cadet['full_name']); ?></h4>
                <div class="text-muted"><?php echo html_escape($cadet['cadet_number']); ?></div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Record Status</h4>
                <div class="d-flex justify-content-between border-bottom py-2"><span>Documents</span><strong><?php echo count($activeDocuments); ?>/4</strong></div>
                <div class="d-flex justify-content-between border-bottom py-2"><span>Photo</span><strong><?php echo $cadet['photo_thumbnail_path'] ? 'Ready' : 'Missing'; ?></strong></div>
                <div class="d-flex justify-content-between py-2"><span>Publication Ready</span><strong class="<?php echo $complete ? 'text-success' : 'text-warning'; ?>"><?php echo $complete ? 'Yes' : 'No'; ?></strong></div>
                <?php if ($cadet['status'] !== 'published' && $cadet['status'] !== 'archived' && has_permission('cadet_publish')): ?>
                    <form method="post" action="<?php echo site_url('admin/cadets/publish/' . $cadet['id']); ?>" class="mt-3">
                        <input type="hidden" name="_cadet_token" value="<?php echo html_escape($form_token); ?>">
                        <button class="btn btn-success btn-block" type="submit" <?php echo $complete ? '' : 'disabled'; ?>><i class="mdi mdi-check-decagram mr-1"></i> Publish Record</button>
                    </form>
                <?php endif; ?>
                <?php if ($cadet['status'] === 'published' && has_permission('cadet_suspend')): ?>
                    <form method="post" action="<?php echo site_url('admin/cadets/status/' . $cadet['id'] . '/suspended'); ?>" class="mt-3">
                        <input type="hidden" name="_cadet_token" value="<?php echo html_escape($form_token); ?>">
                        <label for="suspension-reason">Suspension Reason</label>
                        <textarea class="form-control mb-2" id="suspension-reason" name="reason" rows="2" required></textarea>
                        <button class="btn btn-danger btn-block" type="submit"><i class="mdi mdi-cancel mr-1"></i> Suspend Record</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Cadet Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3"><div class="text-muted small">Department</div><strong><?php echo html_escape($cadet['department_name']); ?></strong></div>
                    <div class="col-md-6 mb-3"><div class="text-muted small">Date of Birth</div><strong><?php echo html_escape(date('d F Y', strtotime($cadet['date_of_birth']))); ?></strong></div>
                    <div class="col-md-6 mb-3"><div class="text-muted small">Batch</div><strong><?php echo $batch . $suffix; ?> Batch</strong></div>
                    <div class="col-md-6 mb-3"><div class="text-muted small">Session</div><strong><?php echo (int) $cadet['session_start_year']; ?> - <?php echo (int) $cadet['session_end_year']; ?></strong></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Certificate Documents</h4>
                <?php foreach ($cadet['documents'] as $document): ?>
                    <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom py-3">
                        <div>
                            <strong><?php echo html_escape(
                                $document['type_code'] === 'pre_sea_course_certificate' && $cadet['department_code'] === 'NAUTICAL'
                                    ? 'Pre-Sea Marine Nautical Course Certificate'
                                    : $document['type_name']
                            ); ?></strong>
                            <?php if (! empty($document['id'])): ?>
                                <div class="text-muted small"><?php echo html_escape($document['original_filename']); ?> · Version <?php echo (int) $document['version']; ?></div>
                            <?php else: ?>
                                <div class="text-warning small">Document missing</div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if (! empty($document['id'])): ?>
                                <span class="badge badge-success mr-2">Active</span>
                                <a href="<?php echo site_url('admin/cadets/document/' . $document['uuid']); ?>" target="_blank" class="btn btn-sm btn-light"><i class="mdi mdi-eye mr-1"></i> View</a>
                            <?php else: ?>
                                <a href="<?php echo site_url('admin/cadets/edit/' . $cadet['id']); ?>" class="btn btn-sm btn-warning"><i class="mdi mdi-upload mr-1"></i> Upload</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($cadet['qr_token']): ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Public Verification</h4>
                    <div class="input-group">
                        <input class="form-control" readonly value="<?php echo html_escape(site_url('verify/qr/' . $cadet['qr_token'])); ?>" id="public-verification-url">
                        <div class="input-group-append">
                            <a class="btn btn-primary" href="<?php echo site_url('verify/qr/' . $cadet['qr_token']); ?>" target="_blank"><i class="mdi mdi-open-in-new"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Recent Audit Activity</h4>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Action</th><th>Reason</th><th>Date</th></tr></thead>
                        <tbody>
                            <?php if (! $audit_logs): ?><tr><td colspan="3" class="text-muted">No audit activity.</td></tr><?php endif; ?>
                            <?php foreach ($audit_logs as $log): ?>
                                <tr>
                                    <td><?php echo html_escape(str_replace('.', ' ', ucfirst($log['action']))); ?></td>
                                    <td><?php echo html_escape($log['reason'] ?: '-'); ?></td>
                                    <td><?php echo html_escape(date('d M Y H:i', strtotime($log['created_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
