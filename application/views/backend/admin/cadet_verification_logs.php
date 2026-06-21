<div class="row">
    <div class="col-12">
        <div class="card"><div class="card-body"><h4 class="page-title mb-0"><i class="mdi mdi-shield-search title_icon"></i> Verification Logs</h4></div></div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-hover">
                        <thead><tr><th>Result</th><th>Cadet</th><th>Department</th><th>IP Address</th><th>Method</th><th>Verified At</th></tr></thead>
                        <tbody>
                            <?php if (! $logs): ?><tr><td colspan="6" class="text-center text-muted py-5">No verification activity.</td></tr><?php endif; ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><span class="badge badge-<?php echo $log['result_status'] === 'valid' ? 'success' : ($log['result_status'] === 'suspended' ? 'warning' : 'danger'); ?>"><?php echo html_escape(strtoupper($log['result_status'])); ?></span></td>
                                    <td><?php echo $log['cadet_number'] ? html_escape($log['cadet_number'] . ' - ' . $log['full_name']) : '<span class="text-muted">No match</span>'; ?></td>
                                    <td><?php echo html_escape($log['department_name'] ?: '-'); ?></td>
                                    <td><?php echo html_escape($log['ip_address']); ?></td>
                                    <td><?php echo html_escape(ucfirst($log['verification_type'])); ?></td>
                                    <td><?php echo html_escape(date('d M Y H:i:s', strtotime($log['verified_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
