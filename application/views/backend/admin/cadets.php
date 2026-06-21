<?php
$status_labels = [
    'draft' => 'Draft',
    'published' => 'Published',
    'suspended' => 'Suspended',
    'archived' => 'Archived',
];
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1"><i class="mdi mdi-account-card-details title_icon"></i> Cadet Records</h4>
                    <p class="text-muted mb-0">Marine Academy certificate verification records</p>
                </div>
                <?php if (has_permission('cadet_create')): ?>
                    <a href="<?php echo site_url('admin/cadets/create'); ?>" class="btn btn-primary">
                        <i class="mdi mdi-account-plus mr-1"></i> Add Cadet
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" action="<?php echo site_url('admin/cadets'); ?>" class="row align-items-end">
                    <div class="form-group col-lg-4 col-md-6">
                        <label for="cadet-search">Search</label>
                        <input id="cadet-search" name="q" class="form-control" value="<?php echo html_escape($filters['q']); ?>" placeholder="Cadet number or full name">
                    </div>
                    <div class="form-group col-lg-2 col-md-6">
                        <label for="department-filter">Department</label>
                        <select id="department-filter" name="department_id" class="form-control">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo (int) $department['id']; ?>" <?php echo (string) $filters['department_id'] === (string) $department['id'] ? 'selected' : ''; ?>>
                                    <?php echo html_escape($department['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-4">
                        <label for="status-filter">Status</label>
                        <select id="status-filter" name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <?php foreach ($status_labels as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $filters['status'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-4">
                        <label for="batch-filter">Batch</label>
                        <select id="batch-filter" name="batch_number" class="form-control">
                            <option value="">All Batches</option>
                            <?php for ($batch = 1; $batch <= 20; $batch++): ?>
                                <?php $suffix = ($batch % 100 >= 11 && $batch % 100 <= 13) ? 'th' : (['th', 'st', 'nd', 'rd'][$batch % 10] ?? 'th'); ?>
                                <option value="<?php echo $batch; ?>" <?php echo (string) $filters['batch_number'] === (string) $batch ? 'selected' : ''; ?>><?php echo $batch . $suffix; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-4">
                        <button type="submit" class="btn btn-primary btn-block"><i class="mdi mdi-filter mr-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Cadet</th>
                                <th>Department</th>
                                <th>Batch / Session</th>
                                <th>Documents</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! $cadets): ?>
                                <tr><td colspan="7" class="text-center text-muted py-5">No cadet records found.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($cadets as $cadet): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($cadet['photo_thumbnail_path']): ?>
                                                <img src="<?php echo site_url('admin/cadets/photo/' . $cadet['uuid']); ?>" alt="" class="rounded mr-3" width="42" height="52" style="object-fit:cover">
                                            <?php else: ?>
                                                <span class="d-inline-flex align-items-center justify-content-center rounded bg-light mr-3" style="width:42px;height:52px"><i class="mdi mdi-account text-muted"></i></span>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo html_escape($cadet['full_name']); ?></strong>
                                                <div class="text-muted"><?php echo html_escape($cadet['cadet_number']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo html_escape($cadet['department_name']); ?></td>
                                    <td>
                                        <?php
                                        $batch = (int) $cadet['batch_number'];
                                        $suffix = ($batch % 100 >= 11 && $batch % 100 <= 13) ? 'th' : (['th', 'st', 'nd', 'rd'][$batch % 10] ?? 'th');
                                        ?>
                                        <strong><?php echo $batch . $suffix; ?> Batch</strong>
                                        <div class="text-muted"><?php echo (int) $cadet['session_start_year']; ?> - <?php echo (int) $cadet['session_end_year']; ?></div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo (int) $cadet['document_count'] === 4 ? 'badge-success' : 'badge-warning'; ?>">
                                            <?php echo (int) $cadet['document_count']; ?>/4
                                        </span>
                                    </td>
                                    <td><span class="badge badge-<?php echo $cadet['status'] === 'published' ? 'success' : ($cadet['status'] === 'suspended' ? 'danger' : ($cadet['status'] === 'archived' ? 'secondary' : 'warning')); ?>"><?php echo html_escape($status_labels[$cadet['status']] ?? ucfirst($cadet['status'])); ?></span></td>
                                    <td><?php echo html_escape(date('d M Y', strtotime($cadet['updated_at']))); ?></td>
                                    <td class="text-right text-nowrap">
                                        <a href="<?php echo site_url('admin/cadets/view/' . $cadet['id']); ?>" class="btn btn-sm btn-light" title="View"><i class="mdi mdi-eye"></i></a>
                                        <?php if (has_permission('cadet_edit')): ?>
                                            <a href="<?php echo site_url('admin/cadets/edit/' . $cadet['id']); ?>" class="btn btn-sm btn-light" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
