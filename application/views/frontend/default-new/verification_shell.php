<?php
    $language_dir = 'ltr';
    $page_title = $page_title ?? 'Certificate Verification';
    $language_code = $language_code ?? 'en';
    $is_bengali = $language_code === 'bn';
    $tr = static function ($english, $bengali) use ($is_bengali) {
        return $is_bengali ? $bengali : $english;
    };
?>
<!DOCTYPE html>
<html lang="<?php echo html_escape($language_code); ?>" dir="<?php echo $language_dir; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5.0">
    <title><?php echo html_escape($page_title); ?> | <?php echo html_escape($official_name); ?></title>
    <link rel="icon" href="<?php echo base_url('uploads/system/' . get_frontend_settings('favicon')); ?>" type="image/x-icon">
    <?php include 'includes_top.php'; ?>
    <style>
        @font-face {
            font-family: 'Noto Sans';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans/NotoSans-Regular.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans/NotoSans-Medium.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 500;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans/NotoSans-SemiBold.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 600;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans/NotoSans-Bold.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 700;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans-bengali/NotoSansBengali-Regular.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans-bengali/NotoSansBengali-Medium.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 500;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans-bengali/NotoSansBengali-SemiBold.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 600;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('<?php echo base_url('assets/global/fonts/noto-sans-bengali/NotoSansBengali-Bold.ttf'); ?>') format('truetype');
            font-style: normal;
            font-weight: 700;
            font-display: swap;
        }
        :root {
            --bma-brand: <?php echo html_escape($brand_color); ?>;
            --bma-brand-dark: #00752c;
            --bma-brand-deep: #005b24;
            --bma-ink: #202020;
            --bma-muted: #5d625f;
            --bma-line: #cfd8d2;
            --bma-soft: #f4f7f5;
            --bma-white: #ffffff;
            --bma-danger: #b42318;
            --bma-focus: #ffd24d;
        }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            background: var(--bma-soft);
            color: var(--bma-ink);
            font-family: <?php echo $is_bengali ? "'Noto Sans Bengali'" : "'Noto Sans'"; ?>, Arial, sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.55;
        }
        .bma-topbar {
            min-height: 34px;
            background: var(--bma-brand-deep);
            color: #fff;
            font-size: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .16);
        }
        .bma-header {
            background: var(--bma-brand-dark);
            color: var(--bma-white);
            border-bottom: 1px solid rgba(255, 255, 255, .18);
        }
        .bma-logo {
            width: 64px;
            height: 64px;
            flex: 0 0 64px;
            object-fit: contain;
            background: #fff;
            border: 2px solid rgba(255, 255, 255, .9);
            border-radius: 50%;
        }
        .bma-hero {
            position: relative;
            min-height: calc(100vh - 132px);
            isolation: isolate;
            display: flex;
            align-items: center;
            background-image: url('<?php echo base_url('assets/global/logo/login_left_bg.png'); ?>');
            background-position: center;
            background-size: cover;
            border-bottom: 4px solid var(--bma-focus);
        }
        .bma-hero::before {
            position: absolute;
            inset: 0;
            z-index: -1;
            content: '';
            background: rgba(0, 117, 44, .32);
        }
        .bma-hero::after {
            position: absolute;
            inset: 0;
            z-index: -1;
            content: '';
            background: rgba(0, 55, 20, .08);
        }
        .bma-hero-content {
            width: 100%;
            padding: 44px 0 52px;
        }
        .bma-intro {
            max-width: 760px;
            margin: 0 auto 24px;
            color: #fff;
            text-align: center;
        }
        .bma-intro-logo {
            width: 94px;
            height: 94px;
            object-fit: contain;
            margin-bottom: 14px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid rgba(255, 255, 255, .9);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .22);
        }
        .bma-kicker {
            display: block;
            margin-bottom: 4px;
            font-size: 16px;
            font-weight: 600;
        }
        .bma-intro h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(27px, 4vw, 42px);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0;
        }
        .bma-intro p {
            margin: 8px auto 0;
            color: rgba(255, 255, 255, .92);
            font-size: 15px;
        }
        .bma-tool {
            max-width: 920px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid rgba(255, 255, 255, .65);
            border-radius: 6px;
            box-shadow: 0 8px 24px rgba(0, 45, 17, .26);
            overflow: hidden;
        }
        .bma-tool-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            background: #f1f5f2;
            border-bottom: 1px solid var(--bma-line);
        }
        .bma-tool-heading h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }
        .bma-tool-heading span {
            color: var(--bma-muted);
            font-size: 12px;
        }
        .bma-panel, .bma-card {
            background: #fff;
            border: 1px solid var(--bma-line);
            border-radius: 6px;
            box-shadow: 0 2px 7px rgba(0, 0, 0, .08);
        }
        .bma-tab {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            margin: 0;
            padding: 0;
            background: #fff;
            border-bottom: 1px solid var(--bma-line);
        }
        .bma-tab .nav-link {
            width: 100%;
            min-height: 48px;
            padding: 9px 10px;
            color: #3e4741;
            background: #fff;
            border: 0;
            border-right: 1px solid var(--bma-line);
            border-radius: 0;
            font-size: 13px;
            font-weight: 600;
        }
        .bma-tab .nav-item:last-child .nav-link {
            border-right: 0;
        }
        .bma-tab .nav-link.active {
            position: relative;
            color: var(--bma-brand-deep);
            background: #eef8f1;
        }
        .bma-tab .nav-link.active::after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 3px;
            content: '';
            background: var(--bma-brand);
        }
        .bma-tab-content {
            padding: 20px;
        }
        .bma-form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 180px 150px;
            gap: 12px;
            align-items: end;
        }
        .bma-form-grid.two {
            grid-template-columns: minmax(0, 1fr) minmax(220px, .7fr) 150px;
        }
        .bma-form-grid.name {
            grid-template-columns: 1.3fr 1fr 110px 150px;
        }
        .bma-captcha {
            width: 100%;
            height: 42px;
            object-fit: contain;
            background: #f7faf8;
            border: 1px solid var(--bma-line);
            border-radius: 4px;
        }
        .bma-btn {
            min-height: 42px;
            padding: 8px 16px;
            background: var(--bma-brand);
            border-color: var(--bma-brand);
            border-radius: 4px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .bma-btn:hover, .bma-btn:focus {
            background: var(--bma-brand-dark);
            border-color: var(--bma-brand-dark);
            color: #fff;
        }
        .bma-outline {
            border-color: var(--bma-brand);
            border-radius: 4px;
            color: var(--bma-brand);
            font-weight: 600;
        }
        .bma-outline:hover {
            background: var(--bma-brand);
            color: #fff;
        }
        .bma-header .bma-outline {
            color: #fff;
            border-color: rgba(255, 255, 255, .75);
        }
        .bma-header .bma-outline:hover {
            color: var(--bma-brand-deep);
            background: #fff;
            border-color: #fff;
        }
        .bma-header .btn-light {
            color: var(--bma-brand-deep);
            border-color: #fff !important;
            border-radius: 4px;
        }
        .bma-language {
            display: inline-flex;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: 4px;
        }
        .bma-language a {
            min-width: 38px;
            padding: 5px 8px;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
        }
        .bma-language a + a {
            border-left: 1px solid rgba(255, 255, 255, .72);
        }
        .bma-language a.active {
            color: var(--bma-brand-deep);
            background: #fff;
        }
        .bma-label {
            display: block;
            color: #303833;
            font-size: 12px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .bma-input {
            min-height: 42px;
            color: var(--bma-ink);
            border-color: #aebbb3;
            border-radius: 4px;
            font-size: 13px;
        }
        .bma-input:hover {
            border-color: #79877e;
        }
        .bma-input:focus,
        .bma-btn:focus-visible,
        .bma-outline:focus-visible,
        .bma-tab .nav-link:focus-visible,
        a:focus-visible {
            outline: 3px solid var(--bma-focus);
            outline-offset: 2px;
            border-color: var(--bma-brand-dark);
            box-shadow: none;
        }
        .bma-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 4px;
            padding: 7px 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0;
        }
        .bma-status.valid, .bma-status.published {
            background: #e7f8ee;
            color: #087d35;
        }
        .bma-status.expired {
            background: #fff7df;
            color: #9a6500;
        }
        .bma-status.revoked, .bma-status.suspended {
            background: #ffe8e8;
            color: #b32323;
        }
        .bma-footer {
            border-top: 1px solid var(--bma-line);
            background: #1d2620;
            color: #e4e9e5;
        }
        .bma-footer a { color: #fff; }
        .bma-qr-box {
            min-height: 180px;
            border: 1px dashed #8da096;
            border-radius: 4px;
            background: #f7faf8;
        }
        .bma-security-note {
            display: flex;
            gap: 9px;
            align-items: flex-start;
            margin-top: 14px;
            padding-top: 12px;
            color: var(--bma-muted);
            border-top: 1px solid #e2e8e4;
            font-size: 12px;
        }
        .bma-security-note i {
            color: var(--bma-brand-dark);
            font-size: 15px;
            margin-top: 2px;
        }
        @media (max-width: 991.98px) {
            .bma-form-grid,
            .bma-form-grid.two,
            .bma-form-grid.name {
                grid-template-columns: 1fr 1fr;
            }
            .bma-form-action { grid-column: 1 / -1; }
            .bma-hero { min-height: auto; }
        }
        @media (max-width: 767.98px) {
            .bma-topbar .container { justify-content: center !important; text-align: center; }
            .bma-topbar .bma-topbar-email { display: none; }
            .bma-header .container { justify-content: center !important; text-align: center; }
            .bma-header nav { width: 100%; justify-content: center; }
            .bma-logo { width: 54px; height: 54px; flex-basis: 54px; }
            .bma-hero-content { padding: 28px 0 34px; }
            .bma-intro-logo { width: 76px; height: 76px; }
            .bma-intro h1 { font-size: 27px; }
            .bma-intro p { font-size: 13px; }
            .bma-tab { grid-template-columns: 1fr 1fr; }
            .bma-tab .nav-link { border-bottom: 1px solid var(--bma-line); }
            .bma-form-grid,
            .bma-form-grid.two,
            .bma-form-grid.name {
                grid-template-columns: 1fr;
            }
            .bma-form-action { grid-column: auto; }
            .bma-tab-content { padding: 16px; }
            .bma-tool-heading { align-items: flex-start; flex-direction: column; }
        }
        @media print {
            .bma-topbar, .bma-header, .bma-footer, .no-print { display: none !important; }
            body { background: #fff; }
            .bma-panel, .bma-card { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="bma-topbar py-2">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span><?php echo html_escape($tr("Government of the People's Republic of Bangladesh", 'গণপ্রজাতন্ত্রী বাংলাদেশ সরকার')); ?></span>
            <span class="bma-topbar-email"><?php echo html_escape($support_email); ?></span>
        </div>
    </div>

    <header class="bma-header py-2">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
            <a href="<?php echo site_url('/'); ?>" class="d-flex align-items-center gap-3 text-decoration-none text-white">
                <img
                    class="bma-logo"
                    src="<?php echo base_url('assets/global/logo/bangladesh_logo.png'); ?>"
                    alt="Bangladesh Government logo"
                    width="58"
                    height="58"
                >
                <span>
                    <strong class="d-block fs-6"><?php echo html_escape($tr('Bangladesh Marine Academy Sylhet', 'বাংলাদেশ মেরিন একাডেমি, সিলেট')); ?></strong>
                    <small class="text-white"><?php echo html_escape($tr('Certificate Verification System', 'সনদপত্র যাচাইকরণ ব্যবস্থা')); ?></small>
                </span>
            </a>
            <nav class="d-flex align-items-center gap-2">
                <span class="bma-language" aria-label="Language">
                    <a class="<?php echo $language_code === 'en' ? 'active' : ''; ?>" href="<?php echo site_url('/?lang=en'); ?>" lang="en" hreflang="en">EN</a>
                    <a class="<?php echo $language_code === 'bn' ? 'active' : ''; ?>" href="<?php echo site_url('/?lang=bn'); ?>" lang="bn" hreflang="bn">BN</a>
                </span>
                <a class="btn btn-sm bma-outline" href="<?php echo site_url('/'); ?>"><?php echo html_escape($tr('Verify', 'সনদ যাচাই')); ?></a>
                <a class="btn btn-sm btn-light border" href="<?php echo site_url('login'); ?>"><?php echo html_escape($tr('Staff Login', 'কর্মকর্তা লগইন')); ?></a>
            </nav>
        </div>
    </header>

    <main>
        <?php include $inner_view . '.php'; ?>
    </main>

    <footer class="bma-footer py-4">
        <div class="container d-flex flex-wrap justify-content-between gap-3">
            <div>
                <strong><?php echo html_escape($tr('Bangladesh Marine Academy Sylhet', 'বাংলাদেশ মেরিন একাডেমি, সিলেট')); ?></strong>
                <div class="small mt-1"><?php echo html_escape($tr('Only certificates published by an authorized institution can be verified on this portal.', 'অনুমোদিত কর্তৃপক্ষ কর্তৃক প্রকাশিত সনদসমূহ এই পোর্টালে যাচাই করা যাবে।')); ?></div>
            </div>
            <div class="small">
                <?php echo html_escape($tr('Support', 'সহায়তা')); ?>: <a href="mailto:<?php echo html_escape($support_email); ?>"><?php echo html_escape($support_email); ?></a>
            </div>
        </div>
    </footer>

    <?php include 'includes_bottom.php'; ?>
</body>
</html>
