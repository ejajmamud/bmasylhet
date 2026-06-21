<section class="py-5">
    <div class="container">
        <div class="bma-panel p-4 p-lg-5 text-center">
            <span class="bma-status revoked"><?php echo html_escape(portal_text('not_verified', $language_code)); ?></span>
            <h1 class="h3 fw-bold mt-3"><?php echo html_escape(portal_text('invalid_title', $language_code)); ?></h1>
            <p class="text-muted mx-auto" style="max-width: 620px;">
                <?php echo html_escape($message ?: portal_text('invalid_help', $language_code)); ?>
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                <a class="btn bma-btn" href="<?php echo site_url('/'); ?>"><?php echo html_escape(portal_text('search_again', $language_code)); ?></a>
                <a class="btn bma-outline" href="mailto:<?php echo html_escape($support_email); ?>"><?php echo html_escape(portal_text('report_issue', $language_code)); ?></a>
            </div>
        </div>
    </div>
</section>
