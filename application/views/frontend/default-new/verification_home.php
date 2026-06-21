<?php
    $is_bengali = ($language_code ?? 'en') === 'bn';
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
                <img
                    class="bma-intro-logo"
                    src="<?php echo html_escape($portal_logo); ?>"
                    alt="<?php echo html_escape($text('institution_name')); ?>"
                    width="<?php echo get_portal_theme() === 'academy_default' ? '112' : '94'; ?>"
                    height="<?php echo get_portal_theme() === 'academy_default' ? '112' : '94'; ?>"
                >
                <span class="bma-kicker"><?php echo html_escape($text('institution_name')); ?></span>
                <h1 id="verification-title"><?php echo html_escape($text('portal_title')); ?></h1>
                <p><?php echo html_escape($text('portal_subtitle')); ?></p>
            </div>

            <div class="bma-tool">
                <div class="bma-tool-heading">
                    <h2><?php echo html_escape($text('verify_form_title')); ?></h2>
                    <span><?php echo html_escape($text('required_note')); ?></span>
                </div>

                <ul class="nav nav-pills bma-tab" id="verificationTabs" role="tablist" aria-label="<?php echo html_escape($text('verification_method')); ?>">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="certificate-tab" data-bs-toggle="pill" data-bs-target="#certificate-number" type="button" role="tab" aria-controls="certificate-number" aria-selected="true"><?php echo html_escape($text('certificate_number')); ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="student-id-tab" data-bs-toggle="pill" data-bs-target="#student-id" type="button" role="tab" aria-controls="student-id" aria-selected="false"><?php echo html_escape($text('student_id')); ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="student-name-tab" data-bs-toggle="pill" data-bs-target="#student-name" type="button" role="tab" aria-controls="student-name" aria-selected="false"><?php echo html_escape($text('student_name')); ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="scan-qr-tab" data-bs-toggle="pill" data-bs-target="#scan-qr" type="button" role="tab" aria-controls="scan-qr" aria-selected="false"><?php echo html_escape($text('scan_qr')); ?></button>
                    </li>
                </ul>

                <div class="tab-content bma-tab-content">
                    <div class="tab-pane fade show active" id="certificate-number" role="tabpanel" aria-labelledby="certificate-tab" tabindex="0">
                        <form method="post" action="<?php echo site_url('verify/certificate'); ?>">
                            <div class="bma-form-grid">
                                <div>
                                    <label class="bma-label" for="certificate_number"><?php echo html_escape($text('certificate_number')); ?> *</label>
                                    <input class="form-control bma-input text-uppercase" id="certificate_number" name="certificate_number" placeholder="BMA-SYL-2026-0001" autocomplete="off" required>
                                </div>
                                <div>
                                    <label class="bma-label" for="captcha_certificate"><?php echo html_escape($text('verification_code')); ?> *</label>
                                    <input class="form-control bma-input" id="captcha_certificate" name="captcha" inputmode="numeric" autocomplete="off" required>
                                </div>
                                <div class="bma-form-action">
                                    <button class="btn bma-btn w-100" type="submit"><?php echo html_escape($text('verify_button')); ?></button>
                                </div>
                            </div>
                            <div class="bma-form-grid mt-2" aria-label="<?php echo html_escape($text('captcha_image')); ?>">
                                <div></div>
                                <img class="bma-captcha" src="<?php echo site_url('verify/captcha'); ?>?scope=certificate&amp;t=<?php echo time(); ?>" alt="<?php echo html_escape($text('verification_code_image')); ?>">
                                <div></div>
                            </div>
                            <div class="bma-security-note">
                                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                                <span><?php echo html_escape($text('certificate_security_note')); ?></span>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="student-id" role="tabpanel" aria-labelledby="student-id-tab" tabindex="0">
                        <form method="post" action="<?php echo site_url('verify/student-id'); ?>">
                            <div class="bma-form-grid two">
                                <div>
                                    <label class="bma-label" for="student_id"><?php echo html_escape($text('student_id')); ?> *</label>
                                    <input class="form-control bma-input text-uppercase" id="student_id" name="student_id" placeholder="CADET-2026-001" autocomplete="off" required>
                                </div>
                                <div>
                                    <label class="bma-label" for="student_id_institution"><?php echo html_escape($text('institution')); ?></label>
                                    <select class="form-select bma-input" id="student_id_institution" name="institution_id">
                                        <option value=""><?php echo html_escape($text('any_institution')); ?></option>
                                        <?php foreach ($institutions as $institution): ?>
                                            <option value="<?php echo (int) $institution['id']; ?>"><?php echo html_escape($institution['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="bma-form-action">
                                    <button class="btn bma-btn w-100" type="submit"><?php echo html_escape($text('verify_button')); ?></button>
                                </div>
                            </div>
                            <div class="bma-security-note">
                                <i class="fas fa-user-shield" aria-hidden="true"></i>
                                <span><?php echo html_escape($text('student_id_note')); ?></span>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="student-name" role="tabpanel" aria-labelledby="student-name-tab" tabindex="0">
                        <form method="post" action="<?php echo site_url('verify/student-name'); ?>">
                            <div class="bma-form-grid name">
                                <div>
                                    <label class="bma-label" for="student_name"><?php echo html_escape($text('student_name')); ?> *</label>
                                    <input class="form-control bma-input" id="student_name" name="student_name" placeholder="<?php echo html_escape($text('full_name_placeholder')); ?>" autocomplete="off" required>
                                </div>
                                <div>
                                    <label class="bma-label" for="name_institution"><?php echo html_escape($text('institution')); ?></label>
                                    <select class="form-select bma-input" id="name_institution" name="institution_id">
                                        <option value=""><?php echo html_escape($text('select_institution')); ?></option>
                                        <?php foreach ($institutions as $institution): ?>
                                            <option value="<?php echo (int) $institution['id']; ?>"><?php echo html_escape($institution['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="bma-label" for="issue_year"><?php echo html_escape($text('issue_year')); ?></label>
                                    <input class="form-control bma-input" id="issue_year" name="issue_year" inputmode="numeric" placeholder="2026">
                                </div>
                                <div class="bma-form-action">
                                    <button class="btn bma-btn w-100" type="submit"><?php echo html_escape($text('search_button')); ?></button>
                                </div>
                            </div>
                            <div class="bma-form-grid mt-2">
                                <div>
                                    <label class="bma-label" for="certificate_type"><?php echo html_escape($text('certificate_type')); ?></label>
                                    <input class="form-control bma-input" id="certificate_type" name="certificate_type" placeholder="Course Completion">
                                </div>
                                <div>
                                    <label class="bma-label" for="captcha_name"><?php echo html_escape($text('verification_code')); ?> *</label>
                                    <input class="form-control bma-input" id="captcha_name" name="captcha" inputmode="numeric" autocomplete="off" required>
                                </div>
                                <img class="bma-captcha" src="<?php echo site_url('verify/captcha'); ?>?scope=name&amp;n=<?php echo time(); ?>" alt="<?php echo html_escape($text('verification_code_image')); ?>">
                            </div>
                            <div class="bma-security-note">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                <span><?php echo html_escape($text('student_name_note')); ?></span>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="scan-qr" role="tabpanel" aria-labelledby="scan-qr-tab" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <div id="qr-reader" class="bma-qr-box d-flex align-items-center justify-content-center text-muted">
                                    <span><?php echo html_escape($text('qr_camera_note')); ?></span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <button class="btn bma-btn w-100 mb-3" id="start-qr" type="button"><?php echo html_escape($text('start_camera')); ?></button>
                                <form id="qr-token-form" method="get">
                                    <label class="bma-label" for="qr_token"><?php echo html_escape($text('qr_link_token')); ?></label>
                                    <input class="form-control bma-input mb-2" id="qr_token" placeholder="https://.../verify/qr/...">
                                    <button class="btn bma-outline w-100" type="submit"><?php echo html_escape($text('open_link')); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
document.getElementById('qr-token-form').addEventListener('submit', function (event) {
    event.preventDefault();
    var value = document.getElementById('qr_token').value.trim();
    if (!value) return;
    var token = value.split('/verify/qr/').pop().trim();
    window.location.href = '<?php echo site_url('verify/qr'); ?>/' + encodeURIComponent(token);
});

document.getElementById('start-qr').addEventListener('click', function () {
    if (typeof Html5Qrcode === 'undefined') {
        document.getElementById('qr-reader').innerHTML = '<span><?php echo addslashes($text('scanner_error')); ?></span>';
        return;
    }
    var qr = new Html5Qrcode('qr-reader');
    qr.start({ facingMode: 'environment' }, { fps: 10, qrbox: 220 }, function (decodedText) {
        qr.stop();
        var token = decodedText.split('/verify/qr/').pop().trim();
        window.location.href = '<?php echo site_url('verify/qr'); ?>/' + encodeURIComponent(token);
    });
});
</script>
