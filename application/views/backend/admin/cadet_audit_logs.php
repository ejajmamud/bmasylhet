<div class="row">
    <div class="col-12">
        <div class="card"><div class="card-body"><h4 class="page-title mb-0"><i class="mdi mdi-clipboard-text-clock title_icon"></i> Cadet Audit Logs</h4></div></div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-hover">
                        <thead><tr><th>Action</th><th>Entity</th><th>Administrator</th><th>Reason</th><th>IP Address</th><th>Date</th></tr></thead>
                        <tbody>
                            <?php if (! $logs): ?><tr><td colspan="6" class="text-center text-muted py-5">No audit activity.</td></tr><?php endif; ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><strong><?php echo html_escape(str_replace('.', ' ', ucfirst($log['action']))); ?></strong></td>
                                    <td><?php echo html_escape($log['entity_type'] . ' #' . $log['entity_id']); ?></td>
                                    <td><?php echo html_escape(trim(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?: 'System'); ?></td>
                                    <td><?php echo html_escape($log['reason'] ?: '-'); ?></td>
                                    <td><?php echo html_escape($log['ip_address']); ?></td>
                                    <td><?php echo html_escape(date('d M Y H:i:s', strtotime($log['created_at']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
