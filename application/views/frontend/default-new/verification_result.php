<section class="py-5">
    <div class="container">
        <div class="bma-panel p-4 p-lg-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <span class="bma-status <?php echo html_escape($certificate['status']); ?>">
                        <?php echo html_escape(str_replace('_', ' ', $certificate['status'])); ?>
                    </span>
                    <h1 class="h3 fw-bold mt-3 mb-1"><?php echo html_escape(portal_text('certificate_verified', $language_code)); ?></h1>
                    <p class="text-muted mb-0"><?php echo html_escape(portal_text('verified_at', $language_code)); ?> <?php echo html_escape($verified_at); ?></p>
                </div>
                <div class="no-print d-flex gap-2">
                    <a class="btn bma-outline" href="<?php echo site_url('/'); ?>"><?php echo html_escape(portal_text('new_search', $language_code)); ?></a>
                    <a class="btn bma-btn" href="<?php echo site_url('verify/print/' . $certificate['uuid']); ?>" target="_blank"><?php echo html_escape(portal_text('print', $language_code)); ?></a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('certificate_number', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape($certificate['certificate_number']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('student_id', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape($certificate['student_identifier_snapshot']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('student_name', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape($certificate['student_name_snapshot']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('institution', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape($certificate['institution_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('program_course', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape($certificate['program_name_snapshot'] ?: $certificate['program_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('certificate_type', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape($certificate['certificate_type_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('issue_date', $language_code)); ?></div>
                                <div class="fw-bold"><?php echo html_escape(date('d M Y', strtotime($certificate['issue_date']))); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bma-card p-3 h-100">
                                <div class="bma-label"><?php echo html_escape(portal_text('expiry_date', $language_code)); ?></div>
                                <div class="fw-bold">
                                    <?php echo $certificate['expiry_date'] ? html_escape(date('d M Y', strtotime($certificate['expiry_date']))) : html_escape(portal_text('not_applicable', $language_code)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bma-card p-4 h-100">
                        <h2 class="h6 fw-bold mb-3"><?php echo html_escape(portal_text('verification_provenance', $language_code)); ?></h2>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted"><?php echo html_escape(portal_text('record_reference', $language_code)); ?></span>
                            <span class="fw-bold small"><?php echo html_escape(substr($certificate['uuid'], 0, 8)); ?>...</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted"><?php echo html_escape(portal_text('public_status', $language_code)); ?></span>
                            <span class="fw-bold"><?php echo html_escape(ucfirst($certificate['status'])); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted"><?php echo html_escape(portal_text('visibility', $language_code)); ?></span>
                            <span class="fw-bold"><?php echo html_escape(ucfirst($certificate['public_visibility'])); ?></span>
                        </div>
                        <?php if (! empty($certificate['public_remarks'])): ?>
                            <div class="mt-3">
                                <div class="bma-label"><?php echo html_escape(portal_text('public_remarks', $language_code)); ?></div>
                                <p class="mb-0"><?php echo nl2br(html_escape($certificate['public_remarks'])); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
