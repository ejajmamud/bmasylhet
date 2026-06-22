<?php
$metricCards = [
    ['Total Cadets', $cadet_metrics['total'], 'fas fa-users', 'primary', site_url('admin/cadets')],
    ['Published Records', $cadet_metrics['published'], 'fas fa-certificate', 'success', site_url('admin/cadets?status=published')],
    ['Draft Records', $cadet_metrics['draft'], 'fas fa-file-alt', 'warning', site_url('admin/cadets?status=draft')],
    ['Verifications Today', $cadet_metrics['today_verifications'], 'fas fa-shield-alt', 'info', site_url('admin/verification-logs')],
];
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body bma-page-heading">
                <div>
                    <h4 class="page-title mb-1"><i class="fas fa-th-large title_icon" aria-hidden="true"></i> Certificate System Dashboard</h4>
                    <p class="text-muted mb-0">Bangladesh Marine Academy Sylhet</p>
                </div>
                <a href="<?php echo site_url('admin/cadets/create'); ?>" class="btn btn-primary"><i class="fas fa-user-plus mr-1" aria-hidden="true"></i> Add Cadet</a>
            </div>
        </div>
    </div>
</div>

<div class="row bma-metric-grid">
    <?php foreach ($metricCards as $card): ?>
        <div class="col-xl-3 col-md-6 bma-metric-column">
            <a href="<?php echo $card[4]; ?>" class="card text-reset bma-metric-card">
                <div class="card-body">
                    <div class="bma-metric-content">
                        <div>
                            <div class="bma-metric-label"><?php echo $card[0]; ?></div>
                            <div class="bma-metric-value"><?php echo number_format($card[1]); ?></div>
                        </div>
                        <span class="bma-metric-icon bma-metric-icon-<?php echo $card[3]; ?>">
                            <i class="<?php echo $card[2]; ?>" aria-hidden="true"></i>
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
                    <div><i class="fas fa-cogs text-primary mr-2" aria-hidden="true"></i> Engine Department</div>
                    <strong><?php echo number_format($cadet_metrics['engine'] ?? 0); ?></strong>
                </div>
                <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                    <div><i class="fas fa-ship text-primary mr-2" aria-hidden="true"></i> Nautical Department</div>
                    <strong><?php echo number_format($cadet_metrics['nautical'] ?? 0); ?></strong>
                </div>
                <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                    <div><i class="fas fa-ban text-danger mr-2" aria-hidden="true"></i> Suspended</div>
                    <strong><?php echo number_format($cadet_metrics['suspended']); ?></strong>
                </div>
                <div class="d-flex align-items-center justify-content-between py-3">
                    <div><i class="fas fa-times-circle text-danger mr-2" aria-hidden="true"></i> Failed Verifications</div>
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
                    <table class="table table-centered table-hover mb-0 bma-responsive-table bma-responsive-records">
                        <thead><tr><th>Cadet</th><th>Department</th><th>Documents</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            <?php if (! $recent_cadets): ?><tr><td colspan="5" class="text-center text-muted py-5">No cadet records yet.</td></tr><?php endif; ?>
                            <?php foreach ($recent_cadets as $cadet): ?>
                                <tr>
                                    <td data-label="Cadet"><strong><?php echo html_escape($cadet['full_name']); ?></strong><div class="text-muted"><?php echo html_escape($cadet['cadet_number']); ?></div></td>
                                    <td data-label="Department"><?php echo html_escape($cadet['department_name']); ?></td>
                                    <td data-label="Documents"><span class="badge <?php echo (int) $cadet['document_count'] === 4 ? 'badge-success' : 'badge-warning'; ?>"><?php echo (int) $cadet['document_count']; ?>/4</span></td>
                                    <td data-label="Status"><span class="badge badge-<?php echo $cadet['status'] === 'published' ? 'success' : ($cadet['status'] === 'suspended' ? 'danger' : 'warning'); ?>"><?php echo html_escape(ucfirst($cadet['status'])); ?></span></td>
                                    <td data-label="Open" class="text-right"><a href="<?php echo site_url('admin/cadets/view/' . $cadet['id']); ?>" class="btn btn-sm btn-light" aria-label="View <?php echo html_escape($cadet['full_name']); ?>"><i class="fas fa-arrow-right" aria-hidden="true"></i></a></td>
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
                    <table class="table table-centered mb-0 bma-responsive-table bma-responsive-activity">
                        <thead><tr><th>Result</th><th>Cadet</th><th>IP Address</th><th>Method</th><th>Time</th></tr></thead>
                        <tbody>
                            <?php if (! $recent_verifications): ?><tr><td colspan="5" class="text-center text-muted py-4">No verification activity yet.</td></tr><?php endif; ?>
                            <?php foreach ($recent_verifications as $verification): ?>
                                <tr>
                                    <td data-label="Result"><span class="badge badge-<?php echo $verification['result_status'] === 'valid' ? 'success' : 'danger'; ?>"><?php echo html_escape(strtoupper($verification['result_status'])); ?></span></td>
                                    <td data-label="Cadet"><?php echo $verification['cadet_number'] ? html_escape($verification['cadet_number'] . ' - ' . $verification['full_name']) : '<span class="text-muted">No match</span>'; ?></td>
                                    <td data-label="IP Address"><?php echo html_escape($verification['ip_address']); ?></td>
                                    <td data-label="Method"><?php echo html_escape(ucfirst($verification['verification_type'])); ?></td>
                                    <td data-label="Time"><?php echo html_escape(date('d M Y H:i', strtotime($verification['verified_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
