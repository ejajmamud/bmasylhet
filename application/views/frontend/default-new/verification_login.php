<?php
    $is_bengali = ($language_code ?? 'en') === 'bn';
    $text = static function ($key) use ($language_code) {
        return portal_text($key, $language_code);
    };
    $portal_logo = get_portal_theme() === 'academy_default'
        ? portal_asset('portal_academy_logo', 'assets/global/logo/BMA.png')
        : portal_asset('portal_govt_logo', 'assets/global/logo/bangladesh_logo.png');
?>
<?php if (get_frontend_settings('recaptcha_status')): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>

<section class="bma-hero bma-login-hero" aria-labelledby="login-title">
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
                <h1 id="login-title"><?php echo html_escape($text('login_title')); ?></h1>
                <p><?php echo html_escape($text('login_subtitle')); ?></p>
            </div>

            <div class="bma-tool bma-login-tool">
                <div class="bma-tool-heading">
                    <h2><?php echo html_escape($text('login_form_title')); ?></h2>
                    <span><?php echo html_escape($text('authorized_only')); ?></span>
                </div>

                <form class="bma-login-form" action="<?php echo site_url('login/validate_login'); ?>" method="post" id="login-form">
                    <?php if ($this->session->flashdata('error_message')): ?>
                        <div class="alert alert-danger" role="alert"><?php echo html_escape($this->session->flashdata('error_message')); ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('flash_message')): ?>
                        <div class="alert alert-success" role="status"><?php echo html_escape($this->session->flashdata('flash_message')); ?></div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="bma-label" for="email"><?php echo html_escape($text('email_address')); ?></label>
                        <input class="form-control bma-input" id="email" type="email" name="email" autocomplete="username" placeholder="name@example.gov.bd" required autofocus>
                    </div>

                    <div class="mb-2">
                        <label class="bma-label" for="password"><?php echo html_escape($text('password')); ?></label>
                        <div class="bma-password-field">
                            <input class="form-control bma-input" id="password" type="password" name="password" autocomplete="current-password" required>
                            <button class="bma-password-toggle" type="button" aria-label="<?php echo html_escape($text('show_password')); ?>" aria-pressed="false">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <a class="bma-login-link" href="<?php echo site_url('login/forgot_password_request'); ?>"><?php echo html_escape($text('forgot_password')); ?></a>
                    </div>

                    <?php if (get_frontend_settings('recaptcha_status')): ?>
                        <div class="g-recaptcha mb-3" data-sitekey="<?php echo html_escape(get_frontend_settings('recaptcha_sitekey')); ?>"></div>
                    <?php endif; ?>

                    <?php if (get_frontend_settings('recaptcha_status_v3')): ?>
                        <button class="btn bma-btn w-100 g-recaptcha" data-sitekey="<?php echo html_escape(get_frontend_settings('recaptcha_sitekey_v3')); ?>" data-callback="onLoginSubmit" data-action="submit">
                            <?php echo html_escape($text('sign_in')); ?>
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn bma-btn w-100"><?php echo html_escape($text('sign_in')); ?></button>
                    <?php endif; ?>

                    <div class="bma-security-note">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <span><?php echo html_escape($text('login_security_note')); ?></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    .bma-login-hero { min-height: calc(100vh - 85px); }
    .bma-login-tool { max-width: 520px; }
    .bma-login-form { padding: 22px; }
    .bma-password-field { position: relative; }
    .bma-password-field .bma-input { padding-right: 46px; }
    .bma-password-toggle {
        position: absolute;
        top: 1px;
        right: 1px;
        width: 42px;
        height: 40px;
        color: #4d5b52;
        background: transparent;
        border: 0;
        border-left: 1px solid #aebbb3;
    }
    .bma-password-toggle:focus-visible {
        outline: 3px solid var(--bma-focus);
        outline-offset: 2px;
    }
    .bma-login-link {
        color: var(--bma-brand-dark);
        font-size: 12px;
        font-weight: 600;
    }
    @media (max-width: 767.98px) {
        .bma-login-hero { min-height: auto; }
        .bma-login-form { padding: 18px 16px; }
    }
</style>

<script>
document.querySelector('.bma-password-toggle').addEventListener('click', function () {
    var input = document.getElementById('password');
    var show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    this.setAttribute('aria-pressed', show ? 'true' : 'false');
    this.setAttribute('aria-label', show ? '<?php echo addslashes($text('hide_password')); ?>' : '<?php echo addslashes($text('show_password')); ?>');
    this.querySelector('i').classList.toggle('fa-eye', !show);
    this.querySelector('i').classList.toggle('fa-eye-slash', show);
});

function onLoginSubmit() {
    document.getElementById('login-form').submit();
}
</script>
