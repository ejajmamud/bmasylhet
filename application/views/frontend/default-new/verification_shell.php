<?php
    $language_dir = 'ltr';
    $page_title = $page_title ?? 'Certificate Verification';
?>
<!DOCTYPE html>
<html lang="en" dir="<?php echo $language_dir; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5.0">
    <title><?php echo html_escape($page_title); ?> | <?php echo html_escape($official_name); ?></title>
    <link rel="icon" href="<?php echo base_url('uploads/system/' . get_frontend_settings('favicon')); ?>" type="image/x-icon">
    <?php include 'includes_top.php'; ?>
    <style>
        :root {
            --bma-brand: <?php echo html_escape($brand_color); ?>;
            --bma-ink: #102033;
            --bma-muted: #617083;
            --bma-line: #dbe4ee;
            --bma-soft: #f6faf8;
        }
        body {
            background: #f7f9fb;
            color: var(--bma-ink);
        }
        .bma-topbar {
            background: var(--bma-brand);
            color: #fff;
            font-size: 14px;
        }
        .bma-header {
            background: #fff;
            border-bottom: 1px solid var(--bma-line);
        }
        .bma-logo {
            width: 58px;
            height: 58px;
            flex: 0 0 58px;
            object-fit: contain;
        }
        .bma-hero {
            background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
            border-bottom: 1px solid var(--bma-line);
        }
        .bma-panel, .bma-card {
            background: #fff;
            border: 1px solid var(--bma-line);
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(16, 32, 51, .06);
        }
        .bma-tab .nav-link {
            color: var(--bma-muted);
            border-radius: 6px;
            font-weight: 700;
        }
        .bma-tab .nav-link.active {
            background: var(--bma-brand);
            color: #fff;
        }
        .bma-btn {
            background: var(--bma-brand);
            border-color: var(--bma-brand);
            color: #fff;
            font-weight: 700;
        }
        .bma-btn:hover, .bma-btn:focus {
            background: #008f35;
            border-color: #008f35;
            color: #fff;
        }
        .bma-outline {
            border-color: var(--bma-brand);
            color: var(--bma-brand);
            font-weight: 700;
        }
        .bma-outline:hover {
            background: var(--bma-brand);
            color: #fff;
        }
        .bma-label {
            color: var(--bma-muted);
            font-size: 13px;
            margin-bottom: 6px;
            font-weight: 700;
        }
        .bma-input {
            min-height: 46px;
            border-color: #cfd9e5;
            border-radius: 6px;
        }
        .bma-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 9px 14px;
            font-weight: 800;
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
            background: #101820;
            color: #d9e2ec;
        }
        .bma-footer a { color: #fff; }
        .bma-qr-box {
            min-height: 230px;
            border: 1px dashed #9fb2c7;
            border-radius: 8px;
            background: #fbfdff;
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
            <span>Official certificate verification portal</span>
            <span><?php echo html_escape($support_email); ?></span>
        </div>
    </div>

    <header class="bma-header py-3">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
            <a href="<?php echo site_url('/'); ?>" class="d-flex align-items-center gap-3 text-decoration-none text-dark">
                <img
                    class="bma-logo"
                    src="<?php echo base_url('assets/global/logo/bangladesh_logo.png'); ?>"
                    alt="Bangladesh Government logo"
                    width="58"
                    height="58"
                >
                <span>
                    <strong class="d-block"><?php echo html_escape($official_name); ?></strong>
                    <small class="text-muted">Certificate Verification System</small>
                </span>
            </a>
            <nav class="d-flex align-items-center gap-2">
                <a class="btn btn-sm bma-outline" href="<?php echo site_url('/'); ?>">Verify</a>
                <a class="btn btn-sm btn-light border" href="<?php echo site_url('login'); ?>">Staff Login</a>
            </nav>
        </div>
    </header>

    <main>
        <?php include $inner_view . '.php'; ?>
    </main>

    <footer class="bma-footer py-4 mt-5">
        <div class="container d-flex flex-wrap justify-content-between gap-3">
            <div>
                <strong><?php echo html_escape($official_name); ?></strong>
                <div class="small mt-1">Only records published by authorized institution users are publicly verifiable.</div>
            </div>
            <div class="small">
                Support: <a href="mailto:<?php echo html_escape($support_email); ?>"><?php echo html_escape($support_email); ?></a>
            </div>
        </div>
    </footer>

    <?php include 'includes_bottom.php'; ?>
</body>
</html>
