<?php
$text = static function ($key) use ($language_code) {
    return portal_text($key, $language_code);
};
$portal_logo = get_portal_theme() === 'academy_default'
    ? portal_asset('portal_academy_logo', 'assets/global/logo/BMA.png')
    : portal_asset('portal_govt_logo', 'assets/global/logo/bangladesh_logo.png');
?>
<section class="bma-hero" aria-labelledby="verification-title">
    <div class="bma-hero-content">
        <div class="container">
            <div class="bma-intro">
                <img class="bma-intro-logo" src="<?php echo html_escape($portal_logo); ?>" alt="<?php echo html_escape($text('institution_name')); ?>" width="112" height="112">
                <span class="bma-kicker"><?php echo html_escape($text('institution_name')); ?></span>
                <h1 id="verification-title"><?php echo html_escape($text('portal_title')); ?></h1>
                <p><?php echo html_escape($text('portal_subtitle')); ?></p>
            </div>

            <div class="bma-tool">
                <div class="bma-tool-heading">
                    <h2>Verify Cadet Certificates</h2>
                    <span>All fields are required</span>
                </div>
                <form method="post" action="<?php echo site_url('verify/cadet'); ?>" class="bma-cadet-form">
                    <input type="hidden" name="_verification_token" value="<?php echo html_escape($verification_form_token); ?>">
                    <div class="bma-cadet-grid">
                        <div>
                            <label class="bma-label" for="department_id">Department *</label>
                            <select class="form-select bma-input" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo (int) $department['id']; ?>"><?php echo html_escape($department['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="bma-label" for="cadet_number">Cadet Number *</label>
                            <input class="form-control bma-input text-uppercase" id="cadet_number" name="cadet_number" placeholder="BMAS-0037" autocomplete="off" maxlength="80" required>
                        </div>
                        <div>
                            <label class="bma-label" for="date_of_birth">Date of Birth *</label>
                            <input class="form-control bma-input" type="date" id="date_of_birth" name="date_of_birth" max="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="bma-captcha-row">
                        <div>
                            <label class="bma-label" for="captcha">Verification Code *</label>
                            <input class="form-control bma-input" id="captcha" name="captcha" inputmode="numeric" autocomplete="off" maxlength="5" required>
                        </div>
                        <div class="bma-captcha-wrap">
                            <img id="cadet-captcha-image" class="bma-captcha" src="<?php echo site_url('verify/captcha'); ?>?t=<?php echo time(); ?>" alt="Verification code image">
                            <button class="btn bma-icon-button" type="button" id="refresh-captcha" title="Refresh verification code" aria-label="Refresh verification code">
                                <i class="fas fa-sync-alt" aria-hidden="true"></i>
                            </button>
                        </div>
                        <button class="btn bma-btn bma-verify-submit" type="submit">
                            <i class="fas fa-shield-alt mr-2" aria-hidden="true"></i> Verify Certificates
                        </button>
                    </div>
                    <div class="bma-security-note">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <span>Exact department, cadet number and date of birth are required for verification.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    document.getElementById('cadet_number').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('refresh-captcha').addEventListener('click', function () {
        document.getElementById('cadet-captcha-image').src = '<?php echo site_url('verify/captcha'); ?>?t=' + Date.now();
        document.getElementById('captcha').value = '';
        document.getElementById('captcha').focus();
    });
})();
</script>
