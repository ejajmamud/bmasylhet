<?php
    $language_dir = 'ltr';
    $page_title = $page_title ?? 'Certificate Verification';
    $language_code = $language_code ?? 'en';
    $is_bengali = $language_code === 'bn';
    $portal_theme = get_portal_theme();
    $is_academy_theme = $portal_theme === 'academy_default';
    $tr = static function ($key) use ($language_code) {
        return portal_text($key, $language_code);
    };
    $government_logo = portal_asset('portal_govt_logo', 'assets/global/logo/bangladesh_logo.png');
    $academy_logo = portal_asset('portal_academy_logo', 'assets/global/logo/BMA.png');
    $govt_background = portal_asset('portal_govt_background', 'assets/global/logo/login_left_bg.png');
    $academy_background = portal_asset('portal_academy_background', 'assets/global/logo/academy-default-bg.jpeg');
    $nav_site_name_size_desktop = max(12, min(28, (int) get_portal_setting('portal_nav_site_name_font_size_desktop', 16)));
    $nav_site_name_size_mobile = max(12, min(24, (int) get_portal_setting('portal_nav_site_name_font_size_mobile', 16)));
?>
<!DOCTYPE html>
<html lang="<?php echo html_escape($language_code); ?>" dir="<?php echo $language_dir; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5.0">
    <title><?php echo html_escape($page_title); ?><?php echo $page_title === get_settings('system_title') ? '' : ' | ' . html_escape($official_name); ?></title>
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
            --bma-nav-site-name-size: <?php echo $nav_site_name_size_desktop; ?>px;
            --bma-nav-site-name-size-mobile: <?php echo $nav_site_name_size_mobile; ?>px;
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
            display: none;
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
        .bma-site-name {
            font-size: var(--bma-nav-site-name-size) !important;
            line-height: 1.25;
        }
        .bma-hero {
            position: relative;
            min-height: calc(100vh - 132px);
            isolation: isolate;
            display: flex;
            align-items: center;
            background-image: url('<?php echo html_escape($govt_background); ?>');
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
        .bma-mobile-nav-toggle {
            display: none;
            width: 44px;
            height: 44px;
            padding: 0;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: 4px;
            font-size: 19px;
        }
        .bma-mobile-nav-toggle:hover,
        .bma-mobile-nav-toggle:focus {
            color: var(--bma-brand-deep);
            background: #fff;
        }
        .bma-mobile-nav {
            display: none;
        }
        .bma-mobile-nav-backdrop {
            display: none;
        }
        .bma-mobile-nav-head {
            display: none;
        }
        .bma-mobile-nav-link {
            display: flex;
            min-height: 44px;
            padding: 10px 12px;
            gap: 10px;
            align-items: center;
            color: var(--bma-ink);
            background: #fff;
            border: 1px solid var(--bma-line);
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
        }
        .bma-mobile-nav-link:hover,
        .bma-mobile-nav-link:focus {
            color: var(--bma-brand-deep);
            background: var(--bma-soft);
        }
        .bma-mobile-nav-link i {
            width: 18px;
            color: var(--bma-brand);
            text-align: center;
        }
        .bma-mobile-language {
            display: flex;
            min-height: 48px;
            padding: 8px 10px;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border: 1px solid var(--bma-line);
            border-radius: 4px;
        }
        .bma-mobile-language-label {
            display: inline-flex;
            gap: 9px;
            align-items: center;
            color: var(--bma-ink);
            font-size: 12px;
            font-weight: 600;
        }
        .bma-mobile-language-label i {
            color: var(--bma-brand);
        }
        .bma-mobile-language .bma-language {
            border-color: var(--bma-line);
        }
        .bma-mobile-language .bma-language a {
            color: var(--bma-brand-dark);
        }
        .bma-mobile-language .bma-language a + a {
            border-left-color: var(--bma-line);
        }
        .bma-mobile-language .bma-language a.active {
            color: #fff;
            background: var(--bma-brand-dark);
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
            .bma-header-inner {
                flex-wrap: nowrap !important;
                gap: 18px !important;
            }
            .bma-header-brand {
                min-width: 0;
                flex: 1 1 auto;
            }
            .bma-header-brand > span {
                min-width: 0;
            }
            .bma-site-name {
                overflow: hidden;
                max-width: 360px;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .bma-header-actions {
                flex: 0 0 auto;
            }
            .bma-header-actions .btn,
            .bma-header-actions .bma-language a {
                min-height: 36px;
            }
        }
        @media (max-width: 767.98px) {
            .bma-topbar .container { justify-content: center !important; text-align: center; }
            .bma-topbar .bma-topbar-email { display: none; }
            .bma-header {
                position: relative;
                padding-top: 6px !important;
                padding-bottom: 6px !important;
            }
            .bma-header-inner {
                position: relative;
                justify-content: space-between !important;
                text-align: left;
            }
            .bma-header-brand {
                justify-content: flex-start;
                gap: 10px !important;
            }
            .bma-header-brand small {
                display: none;
            }
            .bma-site-name {
                max-width: calc(100vw - 142px);
                white-space: normal;
                line-height: 1.2;
            }
            .bma-header-actions {
                display: none !important;
            }
            .bma-mobile-nav-toggle {
                display: inline-flex;
                flex: 0 0 44px;
            }
            .bma-mobile-nav {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                z-index: 1051;
                display: flex;
                width: min(86vw, 340px);
                padding: 0 16px 20px;
                gap: 10px;
                overflow-y: auto;
                flex-direction: column;
                background: #fff;
                border-left: 1px solid var(--bma-line);
                box-shadow: -12px 0 30px rgba(7, 34, 20, .24);
                transform: translateX(105%);
                visibility: hidden;
                transition: transform 220ms ease, visibility 220ms ease;
            }
            .bma-mobile-nav.is-open {
                transform: translateX(0);
                visibility: visible;
            }
            .bma-mobile-nav-backdrop {
                position: fixed;
                inset: 0;
                z-index: 1050;
                display: block;
                background: rgba(7, 20, 14, .58);
                opacity: 0;
                visibility: hidden;
                transition: opacity 220ms ease, visibility 220ms ease;
            }
            .bma-mobile-nav-backdrop.is-open {
                opacity: 1;
                visibility: visible;
            }
            .bma-mobile-nav-head {
                display: flex;
                min-height: 72px;
                margin: 0 -16px 6px;
                padding: 12px 16px;
                gap: 12px;
                align-items: center;
                justify-content: space-between;
                color: #fff;
                background: var(--bma-brand-dark);
                border-bottom: 3px solid var(--bma-brand);
            }
            .bma-mobile-nav-identity {
                display: flex;
                min-width: 0;
                gap: 10px;
                align-items: center;
            }
            .bma-mobile-nav-identity img {
                width: 44px;
                height: 44px;
                flex: 0 0 44px;
                padding: 2px;
                object-fit: contain;
                background: #fff;
                border-radius: 50%;
            }
            .bma-mobile-nav-identity strong,
            .bma-mobile-nav-identity small {
                display: block;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .bma-mobile-nav-identity strong {
                font-size: 12px;
            }
            .bma-mobile-nav-identity small {
                margin-top: 2px;
                color: rgba(255, 255, 255, .82);
                font-size: 10px;
            }
            .bma-mobile-nav-close {
                display: inline-flex;
                width: 40px;
                height: 40px;
                padding: 0;
                flex: 0 0 40px;
                align-items: center;
                justify-content: center;
                color: #fff;
                background: rgba(255, 255, 255, .1);
                border: 1px solid rgba(255, 255, 255, .45);
                border-radius: 4px;
                font-size: 18px;
            }
            .bma-mobile-nav-close:hover,
            .bma-mobile-nav-close:focus {
                color: var(--bma-brand-deep);
                background: #fff;
            }
            body.bma-drawer-open {
                overflow: hidden;
            }
            .bma-site-name { font-size: var(--bma-nav-site-name-size-mobile) !important; }
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

        <?php if ($is_academy_theme): ?>
        :root {
            --bma-brand: #315fa6;
            --bma-brand-dark: #173a68;
            --bma-brand-deep: #102d51;
            --bma-ink: #16263a;
            --bma-muted: #607086;
            --bma-line: #cad5e3;
            --bma-soft: #f3f6fa;
            --bma-focus: #f4be3f;
        }
        body {
            background: #f3f6fa;
            color: var(--bma-ink);
        }
        .bma-header {
            background: #fff;
            color: var(--bma-brand-deep);
            border-bottom: 1px solid #d6dfeb;
            box-shadow: 0 2px 8px rgba(16, 45, 81, .08);
        }
        .bma-header .text-white,
        .bma-header a.text-white {
            color: var(--bma-brand-deep) !important;
        }
        .bma-logo {
            width: 70px;
            height: 62px;
            flex-basis: 70px;
            padding: 0;
            background: #fff;
            border: 0;
            border-radius: 0;
        }
        .bma-header .bma-outline {
            color: var(--bma-brand-dark);
            border-color: #8ea7c7;
        }
        .bma-header .bma-outline:hover {
            color: #fff;
            background: var(--bma-brand-dark);
            border-color: var(--bma-brand-dark);
        }
        .bma-header .btn-light {
            color: #fff;
            background: var(--bma-brand-dark);
            border-color: var(--bma-brand-dark) !important;
        }
        .academy-default-theme .bma-mobile-nav-toggle {
            color: var(--bma-brand-deep);
            border-color: #8ea7c7;
        }
        .academy-default-theme .bma-mobile-nav-toggle:hover,
        .academy-default-theme .bma-mobile-nav-toggle:focus,
        .academy-default-theme .bma-mobile-nav-toggle[aria-expanded="true"] {
            color: #fff;
            background: var(--bma-brand-dark);
            border-color: var(--bma-brand-dark);
        }
        .bma-language {
            border-color: #91a7c3;
        }
        .bma-language a {
            color: var(--bma-brand-dark);
        }
        .bma-language a + a {
            border-left-color: #91a7c3;
        }
        .bma-language a.active {
            color: #fff;
            background: var(--bma-brand-dark);
        }
        .bma-hero {
            min-height: calc(100vh - 80px);
            background-image: url('<?php echo html_escape($academy_background); ?>');
            background-position: center;
            background-size: cover;
            border-bottom: 4px solid #4f79b9;
        }
        .bma-hero::before {
            background: linear-gradient(90deg, rgba(9, 32, 61, .78) 0%, rgba(16, 48, 86, .56) 50%, rgba(37, 82, 145, .28) 100%);
        }
        .bma-hero::after {
            background: rgba(8, 25, 48, .08);
        }
        .bma-intro-logo {
            width: 112px;
            height: 112px;
            padding: 5px;
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 50%;
            background: #fff;
        }
        .bma-tool {
            border-color: rgba(255, 255, 255, .72);
            box-shadow: 0 16px 38px rgba(5, 25, 49, .28);
        }
        .bma-tool-heading {
            background: #f0f4f9;
            border-bottom-color: #cad5e3;
        }
        .bma-tab .nav-link.active {
            color: var(--bma-brand-deep);
            background: #edf3fb;
        }
        .bma-tab .nav-link.active::after,
        .bma-btn {
            background: #315fa6;
        }
        .bma-btn {
            border-color: #315fa6;
        }
        .bma-btn:hover,
        .bma-btn:focus {
            background: #173a68;
            border-color: #173a68;
        }
        .bma-security-note i,
        .bma-login-link {
            color: #315fa6;
        }
        .bma-footer {
            background: #102d51;
            border-top-color: #315fa6;
        }
        @media (max-width: 767.98px) {
            .bma-logo { width: 62px; height: 54px; flex-basis: 62px; }
            .bma-intro-logo { width: 92px; height: 92px; }
        }
        <?php endif; ?>

        .bma-topbar { display: none !important; }
        .bma-cadet-form { padding: 22px 26px 26px; }
        .bma-cadet-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }
        .bma-captcha-row {
            display: grid;
            grid-template-columns: minmax(180px, 1fr) auto minmax(210px, .8fr);
            gap: 16px;
            align-items: end;
            margin-top: 18px;
        }
        .bma-captcha-wrap { display: flex; align-items: center; gap: 8px; min-height: 48px; }
        .bma-icon-button {
            width: 46px;
            height: 46px;
            padding: 0;
            border: 1px solid var(--bma-line);
            background: var(--bma-white);
            color: var(--bma-brand-dark);
        }
        .bma-verify-submit { min-height: 46px; }
        .bma-result-section { padding: 38px 0 52px; background: var(--bma-soft); min-height: 70vh; }
        .bma-verification-banner {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            align-items: center;
            padding: 24px 28px;
            background: var(--bma-white);
            border: 1px solid var(--bma-line);
            border-left: 5px solid var(--bma-brand);
            box-shadow: 0 4px 16px rgba(19, 50, 31, .08);
        }
        .bma-verification-banner.is-suspended { border-left-color: var(--bma-danger); }
        .bma-seal-icon {
            display: grid;
            place-items: center;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: var(--bma-brand);
            color: #fff;
            font-size: 25px;
        }
        .is-suspended .bma-seal-icon { background: var(--bma-danger); }
        .bma-result-eyebrow {
            display: block;
            color: var(--bma-brand-dark);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .bma-verification-banner h1 { font-size: 25px; margin: 0 0 3px; }
        .bma-verification-banner p { margin: 0; color: var(--bma-muted); }
        .bma-verification-time { text-align: right; border-left: 1px solid var(--bma-line); padding-left: 22px; }
        .bma-verification-time span { display: block; color: var(--bma-muted); font-size: 12px; }
        .bma-verification-time strong { display: block; margin-top: 4px; font-size: 13px; }
        .bma-result-layout {
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 22px;
            margin-top: 22px;
        }
        .bma-cadet-profile,
        .bma-document-panel {
            background: var(--bma-white);
            border: 1px solid var(--bma-line);
            box-shadow: 0 3px 14px rgba(19, 50, 31, .06);
        }
        .bma-cadet-profile { padding: 22px; }
        .bma-photo-frame {
            width: 100%;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: var(--bma-soft);
            border: 1px solid var(--bma-line);
        }
        .bma-photo-frame img { width: 100%; height: 100%; object-fit: cover; }
        .bma-profile-copy { padding: 18px 0; border-bottom: 1px solid var(--bma-line); }
        .bma-department-tag {
            display: inline-block;
            padding: 4px 7px;
            background: var(--bma-soft);
            color: var(--bma-brand-dark);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .bma-profile-copy h2 { font-size: 19px; margin: 11px 0 4px; }
        .bma-cadet-number { color: var(--bma-brand-dark); font-weight: 700; }
        .bma-profile-details { margin: 12px 0 0; }
        .bma-profile-details div { display: flex; justify-content: space-between; gap: 12px; padding: 9px 0; border-bottom: 1px solid var(--bma-line); }
        .bma-profile-details div:last-child { border-bottom: 0; }
        .bma-profile-details dt { color: var(--bma-muted); font-weight: 400; }
        .bma-profile-details dd { margin: 0; text-align: right; font-weight: 700; }
        .bma-document-panel { padding: 26px; }
        .bma-document-heading { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .bma-document-heading h2 { margin: 0; font-size: 22px; }
        .bma-document-count { color: var(--bma-brand-dark); font-size: 12px; font-weight: 700; }
        .bma-document-list { border-top: 1px solid var(--bma-line); }
        .bma-document-item {
            display: grid;
            grid-template-columns: 34px 42px minmax(0, 1fr) auto auto;
            gap: 14px;
            align-items: center;
            min-height: 86px;
            padding: 14px 0;
            border-bottom: 1px solid var(--bma-line);
        }
        .bma-document-index { color: var(--bma-muted); font-size: 12px; font-weight: 700; }
        .bma-document-icon {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            background: var(--bma-soft);
            color: var(--bma-brand-dark);
            font-size: 18px;
        }
        .bma-document-copy h3 { margin: 0 0 3px; font-size: 14px; }
        .bma-document-copy span { color: var(--bma-muted); font-size: 12px; }
        .bma-document-status { font-size: 12px; font-weight: 700; white-space: nowrap; }
        .bma-document-status.is-valid { color: var(--bma-brand-dark); }
        .bma-document-status.is-missing { color: var(--bma-danger); }
        .bma-document-action {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            border: 1px solid var(--bma-line);
            color: var(--bma-brand-dark);
            background: var(--bma-white);
        }
        .bma-document-action:hover { background: var(--bma-brand); border-color: var(--bma-brand); color: #fff; }
        .bma-result-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
        .bma-trust-note {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 18px;
            padding: 14px 18px;
            background: var(--bma-white);
            border: 1px solid var(--bma-line);
            color: var(--bma-brand-dark);
        }
        .bma-trust-note i { font-size: 22px; }
        .bma-trust-note strong, .bma-trust-note span { display: block; }
        .bma-trust-note span { color: var(--bma-muted); font-size: 11px; margin-top: 2px; }
        @media (max-width: 991.98px) {
            .bma-cadet-grid { grid-template-columns: 1fr 1fr; }
            .bma-captcha-row { grid-template-columns: 1fr auto; }
            .bma-verify-submit { grid-column: 1 / -1; }
            .bma-result-layout { grid-template-columns: 250px minmax(0, 1fr); }
            .bma-document-item { grid-template-columns: 28px 38px minmax(0, 1fr) auto; }
            .bma-document-status { display: none; }
        }
        @media (max-width: 767.98px) {
            .bma-cadet-form { padding: 18px; }
            .bma-cadet-grid, .bma-captcha-row { grid-template-columns: 1fr; }
            .bma-verify-submit { grid-column: auto; }
            .bma-verification-banner { grid-template-columns: auto 1fr; padding: 20px; }
            .bma-verification-time { grid-column: 1 / -1; text-align: left; border-left: 0; border-top: 1px solid var(--bma-line); padding: 14px 0 0; }
            .bma-result-layout { grid-template-columns: 1fr; }
            .bma-cadet-profile { display: grid; grid-template-columns: 120px 1fr; gap: 18px; }
            .bma-profile-copy { padding-top: 0; }
            .bma-profile-details { grid-column: 1 / -1; }
            .bma-document-panel { padding: 18px; }
            .bma-document-item { grid-template-columns: 28px 36px minmax(0, 1fr); }
            .bma-document-action { grid-column: 2 / -1; justify-self: start; }
            .bma-result-actions { flex-direction: column; }
        }
    </style>
</head>
<body class="<?php echo $is_academy_theme ? 'academy-default-theme' : 'govt-green-theme'; ?>">
    <div class="bma-topbar py-2">
        <div class="container d-flex flex-wrap justify-content-between gap-2">
            <span><?php echo html_escape($tr('government_name')); ?></span>
            <span class="bma-topbar-email"><?php echo html_escape($support_email); ?></span>
        </div>
    </div>

    <header class="bma-header py-2">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3 bma-header-inner">
            <a href="<?php echo site_url('/'); ?>" class="d-flex align-items-center gap-3 text-decoration-none text-white bma-header-brand">
                <img
                    class="bma-logo"
                    src="<?php echo html_escape($is_academy_theme ? $academy_logo : $government_logo); ?>"
                    alt="<?php echo html_escape($is_academy_theme ? 'Bangladesh Marine Academy Sylhet logo' : 'Bangladesh Government logo'); ?>"
                    width="<?php echo $is_academy_theme ? '70' : '58'; ?>"
                    height="<?php echo $is_academy_theme ? '62' : '58'; ?>"
                >
                <span>
                    <strong class="d-block bma-site-name"><?php echo html_escape($tr('institution_name')); ?></strong>
                    <small class="text-white"><?php echo html_escape($tr('system_title')); ?></small>
                </span>
            </a>
            <nav class="d-flex align-items-center gap-2 bma-header-actions <?php echo $inner_view === 'verification_home' ? 'is-home' : ''; ?>">
                <span class="bma-language" aria-label="Language">
                    <a class="<?php echo $language_code === 'en' ? 'active' : ''; ?>" href="<?php echo site_url('/?lang=en'); ?>" lang="en" hreflang="en">EN</a>
                    <a class="<?php echo $language_code === 'bn' ? 'active' : ''; ?>" href="<?php echo site_url('/?lang=bn'); ?>" lang="bn" hreflang="bn">BN</a>
                </span>
                <?php if ($inner_view !== 'verification_home'): ?>
                    <a class="btn btn-sm bma-outline" href="<?php echo site_url('/'); ?>"><?php echo html_escape($tr('verify_button')); ?></a>
                <?php endif; ?>
                <?php if ($this->uri->segment(1) !== 'login'): ?>
                    <a class="btn btn-sm btn-light border" href="<?php echo site_url('login'); ?>"><?php echo html_escape($tr('staff_login')); ?></a>
                <?php endif; ?>
            </nav>
            <button
                class="bma-mobile-nav-toggle"
                type="button"
                aria-controls="bma-mobile-navigation"
                aria-expanded="false"
                aria-label="Open navigation menu"
            >
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="bma-mobile-nav-backdrop" aria-hidden="true"></div>
            <nav class="bma-mobile-nav" id="bma-mobile-navigation" aria-label="Mobile navigation">
                <div class="bma-mobile-nav-head">
                    <div class="bma-mobile-nav-identity">
                        <img
                            src="<?php echo html_escape($is_academy_theme ? $academy_logo : $government_logo); ?>"
                            alt=""
                            width="44"
                            height="44"
                        >
                        <span>
                            <strong><?php echo html_escape($tr('institution_name')); ?></strong>
                            <small><?php echo html_escape($tr('system_title')); ?></small>
                        </span>
                    </div>
                    <button class="bma-mobile-nav-close" type="button" aria-label="Close navigation menu">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>
                <?php if ($inner_view !== 'verification_home'): ?>
                    <a class="bma-mobile-nav-link" href="<?php echo site_url('/'); ?>">
                        <i class="fa-solid fa-shield-check" aria-hidden="true"></i>
                        <span><?php echo html_escape($tr('verify_button')); ?> Certificate</span>
                    </a>
                <?php else: ?>
                    <a class="bma-mobile-nav-link" href="#verification-tool">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <span><?php echo html_escape($tr('verify_button')); ?> Certificate</span>
                    </a>
                <?php endif; ?>
                <?php if ($this->uri->segment(1) !== 'login'): ?>
                    <a class="bma-mobile-nav-link" href="<?php echo site_url('login'); ?>">
                        <i class="fa-solid fa-user-lock" aria-hidden="true"></i>
                        <span><?php echo html_escape($tr('staff_login')); ?></span>
                    </a>
                <?php endif; ?>
                <div class="bma-mobile-language">
                    <span class="bma-mobile-language-label">
                        <i class="fa-solid fa-language" aria-hidden="true"></i>
                        <span>Language</span>
                    </span>
                    <span class="bma-language" aria-label="Language">
                        <a class="<?php echo $language_code === 'en' ? 'active' : ''; ?>" href="<?php echo site_url('/?lang=en'); ?>" lang="en" hreflang="en">EN</a>
                        <a class="<?php echo $language_code === 'bn' ? 'active' : ''; ?>" href="<?php echo site_url('/?lang=bn'); ?>" lang="bn" hreflang="bn">BN</a>
                    </span>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <?php include $inner_view . '.php'; ?>
    </main>

    <footer class="bma-footer py-4">
        <div class="container d-flex flex-wrap justify-content-between gap-3">
            <div>
                <strong><?php echo html_escape($tr('institution_name')); ?></strong>
                <div class="small mt-1"><?php echo html_escape($tr('footer_note')); ?></div>
            </div>
            <div class="small">
                <?php echo html_escape($tr('support')); ?>: <a href="mailto:<?php echo html_escape($support_email); ?>"><?php echo html_escape($support_email); ?></a>
            </div>
        </div>
    </footer>

    <?php include 'includes_bottom.php'; ?>
    <script>
    (function () {
        var toggle = document.querySelector('.bma-mobile-nav-toggle');
        var menu = document.getElementById('bma-mobile-navigation');
        var backdrop = document.querySelector('.bma-mobile-nav-backdrop');
        var closeButton = document.querySelector('.bma-mobile-nav-close');
        if (!toggle || !menu || !backdrop || !closeButton) return;

        function closeMenu(returnFocus) {
            menu.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            document.body.classList.remove('bma-drawer-open');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Open navigation menu');
            if (returnFocus) toggle.focus();
        }

        toggle.addEventListener('click', function () {
            var opening = !menu.classList.contains('is-open');
            if (opening) {
                menu.classList.add('is-open');
                backdrop.classList.add('is-open');
                document.body.classList.add('bma-drawer-open');
                toggle.setAttribute('aria-expanded', 'true');
                toggle.setAttribute('aria-label', 'Close navigation menu');
                window.setTimeout(function () {
                    closeButton.focus();
                }, 230);
            } else {
                closeMenu(false);
            }
        });

        closeButton.addEventListener('click', function () {
            closeMenu(true);
        });

        backdrop.addEventListener('click', function () {
            closeMenu(true);
        });

        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                closeMenu(false);
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && menu.classList.contains('is-open')) {
                closeMenu(true);
            }
            if (event.key === 'Tab' && menu.classList.contains('is-open')) {
                var focusable = menu.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])');
                if (!focusable.length) return;
                var first = focusable[0];
                var last = focusable[focusable.length - 1];
                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) closeMenu(false);
        });
    })();
    </script>
</body>
</html>
