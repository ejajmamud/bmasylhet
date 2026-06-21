<?php
$metricCards = [
    ['Total Cadets', $cadet_metrics['total'], 'mdi-account-group-outline', 'primary', site_url('admin/cadets')],
    ['Published Records', $cadet_metrics['published'], 'mdi-check-decagram-outline', 'success', site_url('admin/cadets?status=published')],
    ['Draft Records', $cadet_metrics['draft'], 'mdi-file-document-edit-outline', 'warning', site_url('admin/cadets?status=draft')],
    ['Verifications Today', $cadet_metrics['today_verifications'], 'mdi-shield-search', 'info', site_url('admin/verification-logs')],
];
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1"><i class="mdi mdi-view-dashboard-outline title_icon"></i> Certificate System Dashboard</h4>
                    <p class="text-muted mb-0">Bangladesh Marine Academy Sylhet</p>
                </div>
                <a href="<?php echo site_url('admin/cadets/create'); ?>" class="btn btn-primary"><i class="mdi mdi-account-plus mr-1"></i> Add Cadet</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($metricCards as $card): ?>
        <div class="col-xl-3 col-md-6">
            <a href="<?php echo $card[4]; ?>" class="card text-reset">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted font-13 text-uppercase"><?php echo $card[0]; ?></div>
                            <h2 class="mb-0 mt-2"><?php echo number_format($card[1]); ?></h2>
                        </div>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-<?php echo $card[3]; ?>-lighten text-<?php echo $card[3]; ?>" style="width:52px;height:52px">
                            <i class="mdi <?php echo $card[2]; ?>" style="font-size:26px"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Department Records</h4>
                <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                    <div><i class="mdi mdi-engine-outline text-primary mr-2"></i> Engine Department</div>
                    <strong><?php echo number_format($cadet_metrics['engine'] ?? 0); ?></strong>
                </div>
                <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                    <div><i class="mdi mdi-ferry text-primary mr-2"></i> Nautical Department</div>
                    <strong><?php echo number_format($cadet_metrics['nautical'] ?? 0); ?></strong>
                </div>
                <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                    <div><i class="mdi mdi-alert-circle-outline text-danger mr-2"></i> Suspended</div>
                    <strong><?php echo number_format($cadet_metrics['suspended']); ?></strong>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3">
                    <div><i class="mdi mdi-close-circle-outline text-danger mr-2"></i> Failed Verifications</div>
                    <strong><?php echo number_format($cadet_metrics['failed_verifications']); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="header-title mb-0">Recent Cadet Records</h4>
                    <a href="<?php echo site_url('admin/cadets'); ?>">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-hover mb-0">
                        <thead><tr><th>Cadet</th><th>Department</th><th>Documents</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            <?php if (! $recent_cadets): ?><tr><td colspan="5" class="text-center text-muted py-5">No cadet records yet.</td></tr><?php endif; ?>
                            <?php foreach ($recent_cadets as $cadet): ?>
                                <tr>
                                    <td><strong><?php echo html_escape($cadet['full_name']); ?></strong><div class="text-muted"><?php echo html_escape($cadet['cadet_number']); ?></div></td>
                                    <td><?php echo html_escape($cadet['department_name']); ?></td>
                                    <td><span class="badge <?php echo (int) $cadet['document_count'] === 4 ? 'badge-success' : 'badge-warning'; ?>"><?php echo (int) $cadet['document_count']; ?>/4</span></td>
                                    <td><span class="badge badge-<?php echo $cadet['status'] === 'published' ? 'success' : ($cadet['status'] === 'suspended' ? 'danger' : 'warning'); ?>"><?php echo html_escape(ucfirst($cadet['status'])); ?></span></td>
                                    <td class="text-right"><a href="<?php echo site_url('admin/cadets/view/' . $cadet['id']); ?>" class="btn btn-sm btn-light"><i class="mdi mdi-arrow-right"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="header-title mb-0">Recent Verification Activity</h4>
                    <a href="<?php echo site_url('admin/verification-logs'); ?>">View Logs</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead><tr><th>Result</th><th>Cadet</th><th>IP Address</th><th>Method</th><th>Time</th></tr></thead>
                        <tbody>
                            <?php if (! $recent_verifications): ?><tr><td colspan="5" class="text-center text-muted py-4">No verification activity yet.</td></tr><?php endif; ?>
                            <?php foreach ($recent_verifications as $verification): ?>
                                <tr>
                                    <td><span class="badge badge-<?php echo $verification['result_status'] === 'valid' ? 'success' : 'danger'; ?>"><?php echo html_escape(strtoupper($verification['result_status'])); ?></span></td>
                                    <td><?php echo $verification['cadet_number'] ? html_escape($verification['cadet_number'] . ' - ' . $verification['full_name']) : '<span class="text-muted">No match</span>'; ?></td>
                                    <td><?php echo html_escape($verification['ip_address']); ?></td>
                                    <td><?php echo html_escape(ucfirst($verification['verification_type'])); ?></td>
                                    <td><?php echo html_escape(date('d M Y H:i', strtotime($verification['verified_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
