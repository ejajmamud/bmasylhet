<section class="py-5">
    <div class="container">
        <div class="bma-panel p-4 p-lg-5 text-center">
            <span class="bma-status revoked">Not Verified</span>
            <h1 class="h3 fw-bold mt-3">No matching certificate could be verified.</h1>
            <p class="text-muted mx-auto" style="max-width: 620px;">
                <?php echo html_escape($message ?: 'Check the certificate number, student ID, institution, or QR code and try again.'); ?>
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                <a class="btn bma-btn" href="<?php echo site_url('/'); ?>">Search Again</a>
                <a class="btn bma-outline" href="mailto:<?php echo html_escape($support_email); ?>">Report Issue</a>
            </div>
        </div>
    </div>
</section>
