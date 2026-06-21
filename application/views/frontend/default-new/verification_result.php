<?php
$isSuspended = $cadet['status'] === 'suspended';
$batch = (int) $cadet['batch_number'];
$suffix = ($batch % 100 >= 11 && $batch % 100 <= 13) ? 'th' : (['th', 'st', 'nd', 'rd'][$batch % 10] ?? 'th');
?>
<section class="bma-result-section">
    <div class="container">
        <div class="bma-verification-banner <?php echo $isSuspended ? 'is-suspended' : 'is-valid'; ?>">
            <div class="bma-seal-icon"><i class="fas <?php echo $isSuspended ? 'fa-exclamation-triangle' : 'fa-check'; ?>" aria-hidden="true"></i></div>
            <div>
                <span class="bma-result-eyebrow">Official Verification Result</span>
                <h1><?php echo $isSuspended ? 'Certificate Record Suspended' : 'Certificates Verified'; ?></h1>
                <p><?php echo $isSuspended ? 'This cadet record is authentic but currently suspended.' : 'The cadet identity and four official certificate records have been verified.'; ?></p>
            </div>
            <div class="bma-verification-time">
                <span>Verified At</span>
                <strong><?php echo html_escape(date('d M Y, h:i A', strtotime($verified_at))); ?></strong>
            </div>
        </div>

        <div class="bma-result-layout">
            <aside class="bma-cadet-profile">
                <div class="bma-photo-frame">
                    <img src="<?php echo site_url('verify/photo/' . $cadet['uuid']); ?>" alt="<?php echo html_escape($cadet['full_name']); ?>">
                </div>
                <div class="bma-profile-copy">
                    <span class="bma-department-tag"><?php echo html_escape($cadet['department_name']); ?></span>
                    <h2><?php echo html_escape($cadet['full_name']); ?></h2>
                    <div class="bma-cadet-number"><?php echo html_escape($cadet['cadet_number']); ?></div>
                </div>
                <dl class="bma-profile-details">
                    <div><dt>Batch</dt><dd><?php echo $batch . $suffix; ?></dd></div>
                    <div><dt>Session</dt><dd><?php echo (int) $cadet['session_start_year']; ?> - <?php echo (int) $cadet['session_end_year']; ?></dd></div>
                    <div><dt>Date of Birth</dt><dd><?php echo html_escape(date('d F Y', strtotime($cadet['date_of_birth']))); ?></dd></div>
                    <div><dt>Record Status</dt><dd><?php echo $isSuspended ? 'Suspended' : 'Valid'; ?></dd></div>
                </dl>
            </aside>

            <div class="bma-document-panel">
                <div class="bma-document-heading">
                    <div>
                        <span class="bma-result-eyebrow">Certificate Portfolio</span>
                        <h2>Official Documents</h2>
                    </div>
                    <span class="bma-document-count"><?php echo $documents_complete ? '4 of 4 verified' : 'Record incomplete'; ?></span>
                </div>

                <div class="bma-document-list">
                    <?php foreach ($cadet['documents'] as $index => $document): ?>
                        <article class="bma-document-item">
                            <div class="bma-document-index"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></div>
                            <div class="bma-document-icon"><i class="far <?php echo ($document['mime_type'] ?? '') === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-image'; ?>" aria-hidden="true"></i></div>
                            <div class="bma-document-copy">
                                <h3><?php echo html_escape(
                                    $document['type_code'] === 'pre_sea_course_certificate' && $cadet['department_code'] === 'NAUTICAL'
                                        ? 'Pre-Sea Marine Nautical Course Certificate'
                                        : $document['type_name']
                                ); ?></h3>
                                <span><?php echo ! empty($document['id']) ? 'Verified official record' : 'Document unavailable'; ?></span>
                            </div>
                            <div class="bma-document-status <?php echo ! empty($document['id']) ? 'is-valid' : 'is-missing'; ?>">
                                <i class="fas <?php echo ! empty($document['id']) ? 'fa-check-circle' : 'fa-minus-circle'; ?>" aria-hidden="true"></i>
                                <?php echo ! empty($document['id']) ? 'Valid' : 'Missing'; ?>
                            </div>
                            <?php if (! $isSuspended && ! empty($document['id'])): ?>
                                <a class="btn bma-document-action" href="<?php echo site_url('verify/document/' . $document['uuid']); ?>" target="_blank" rel="noopener">
                                    <i class="fas fa-eye" aria-hidden="true"></i><span>View</span>
                                </a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="bma-result-actions no-print">
                    <a class="btn bma-outline" href="<?php echo site_url('/'); ?>"><i class="fas fa-search mr-2"></i>New Verification</a>
                    <a class="btn bma-btn" href="<?php echo site_url('verify/print/' . $cadet['uuid']); ?>" target="_blank"><i class="fas fa-print mr-2"></i>Print Result</a>
                </div>
            </div>
        </div>

        <div class="bma-trust-note">
            <i class="fas fa-shield-alt" aria-hidden="true"></i>
            <div><strong>Secure academy record</strong><span>Reference <?php echo html_escape(strtoupper(substr($cadet['uuid'], 0, 12))); ?></span></div>
        </div>
    </div>
</section>
