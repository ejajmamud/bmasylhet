<section class="py-5">
    <div class="container">
        <div class="bma-panel p-4 p-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <span class="bma-status <?php echo html_escape($certificate['status']); ?>">
                        <?php echo html_escape(str_replace('_', ' ', $certificate['status'])); ?>
                    </span>
                    <h1 class="h3 fw-bold mt-3 mb-1">Certificate verified</h1>
                    <p class="text-muted mb-0">Verified at <?php echo html_escape($verified_at); ?></p>
                </div>
                <div class="no-print d-flex gap-2">
                    <a class="btn bma-outline" href="<?php echo site_url('/'); ?>">New Search</a>
                    <a class="btn bma-btn" href="<?php echo site_url('verify/print/' . $certificate['uuid']); ?>" target="_blank">Print</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Certificate number</div>
                                <div class="fw-bold"><?php echo html_escape($certificate['certificate_number']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Student ID</div>
                                <div class="fw-bold"><?php echo html_escape($certificate['student_identifier_snapshot']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Student name</div>
                                <div class="fw-bold"><?php echo html_escape($certificate['student_name_snapshot']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Institution</div>
                                <div class="fw-bold"><?php echo html_escape($certificate['institution_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Program / Course</div>
                                <div class="fw-bold"><?php echo html_escape($certificate['program_name_snapshot'] ?: $certificate['program_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Certificate type</div>
                                <div class="fw-bold"><?php echo html_escape($certificate['certificate_type_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Issue date</div>
                                <div class="fw-bold"><?php echo html_escape(date('d M Y', strtotime($certificate['issue_date']))); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label">Expiry date</div>
                                <div class="fw-bold">
                                    <?php echo $certificate['expiry_date'] ? html_escape(date('d M Y', strtotime($certificate['expiry_date']))) : 'Not applicable'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bma-card p-4 h-100">
                        <h2 class="h6 fw-bold mb-3">Verification provenance</h2>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Record UUID</span>
                            <span class="fw-bold small"><?php echo html_escape(substr($certificate['uuid'], 0, 8)); ?>...</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Public status</span>
                            <span class="fw-bold"><?php echo html_escape(ucfirst($certificate['status'])); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Visibility</span>
                            <span class="fw-bold"><?php echo html_escape(ucfirst($certificate['public_visibility'])); ?></span>
                        </div>
                        <?php if (! empty($certificate['public_remarks'])): ?>
                            <div class="mt-3">
                                <div class="bma-label">Public remarks</div>
                                <p class="mb-0"><?php echo nl2br(html_escape($certificate['public_remarks'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
