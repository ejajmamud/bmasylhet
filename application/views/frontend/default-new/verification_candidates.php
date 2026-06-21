<section class="py-5">
    <div class="container">
        <div class="bma-panel p-4 p-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">Select the matching certificate</h1>
                    <p class="text-muted mb-0">Multiple privacy-safe matches were found. Choose one to view the public result.</p>
                </div>
                <a class="btn bma-outline" href="<?php echo site_url('/'); ?>">Refine Search</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Institution</th>
                            <th>Program</th>
                            <th>Certificate</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matches as $certificate): ?>
                            <tr>
                                <td><?php echo html_escape($certificate['student_name_snapshot']); ?></td>
                                <td><?php echo html_escape($certificate['institution_name']); ?></td>
                                <td><?php echo html_escape($certificate['program_name_snapshot'] ?: $certificate['program_name']); ?></td>
                                <td><?php echo html_escape($this->mask($certificate['certificate_number'])); ?></td>
                                <td><span class="bma-status <?php echo html_escape($certificate['status']); ?>"><?php echo html_escape($certificate['status']); ?></span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm bma-btn" href="<?php echo site_url('verify/result/' . $certificate['uuid']); ?>">Open</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
