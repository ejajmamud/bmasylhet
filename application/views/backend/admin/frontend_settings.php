<?php
$active_tab = $this->input->get('tab') ?: 'portal_content';
$text_defaults = portal_text_defaults();
$asset_definitions = [
    'portal_govt_logo' => [
        'label' => 'Govt Green Logo',
        'description' => 'Displayed on the Govt Green public portal and login screen.',
        'fallback' => 'assets/global/logo/bangladesh_logo.png',
    ],
    'portal_academy_logo' => [
        'label' => 'Marine Academy Logo',
        'description' => 'Displayed in Academy Default and throughout the dashboard.',
        'fallback' => 'assets/global/logo/BMA.png',
    ],
    'portal_govt_background' => [
        'label' => 'Govt Green Background',
        'description' => 'The maritime image beneath the green overlay.',
        'fallback' => 'assets/global/logo/login_left_bg.png',
    ],
    'portal_academy_background' => [
        'label' => 'Academy Default Background',
        'description' => 'The campus or academy image used by Academy Default.',
        'fallback' => 'assets/global/logo/academy-default-bg.jpeg',
    ],
    'favicon' => [
        'label' => 'Website Favicon',
        'description' => 'Browser tab and bookmark icon used across the public portal and dashboard.',
        'fallback' => 'uploads/system/favicon.png',
        'is_favicon' => true,
    ],
];
$long_fields = [
    'portal_subtitle', 'required_note', 'certificate_security_note', 'student_id_note',
    'student_name_note', 'qr_camera_note', 'scanner_error', 'login_subtitle',
    'authorized_only', 'login_security_note', 'footer_note', 'invalid_title',
    'invalid_help', 'multiple_matches',
];
?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body bma-page-heading">
                <div>
                    <h4 class="page-title mb-1"><i class="fas fa-desktop title_icon" aria-hidden="true"></i> Frontend Settings</h4>
                    <p class="text-muted mb-0">Every field below is connected to the live public portal, login screen, or dashboard.</p>
                </div>
                <div class="bma-settings-heading-actions">
                    <span class="badge badge-success mr-2">Active: <?php echo html_escape(get_portal_theme() === 'govt_green' ? 'Govt Green' : 'Academy Default'); ?></span>
                    <a href="<?php echo site_url('admin/theme_settings'); ?>" class="btn btn-outline-primary">Manage Themes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav bma-settings-tabs portal-settings-nav" aria-label="Frontend settings sections">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active_tab === 'portal_content' ? 'active' : ''; ?>" href="<?php echo site_url('admin/frontend_settings?tab=portal_content'); ?>">
                            <i class="fas fa-language" aria-hidden="true"></i>
                            <span>Portal Content</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active_tab === 'portal_assets' ? 'active' : ''; ?>" href="<?php echo site_url('admin/frontend_settings?tab=portal_assets'); ?>">
                            <i class="fas fa-images" aria-hidden="true"></i>
                            <span>Logos &amp; Media</span>
                        </a>
                    </li>
                </ul>

                <?php if ($active_tab === 'portal_assets'): ?>
                    <form class="bma-settings-panel" action="<?php echo site_url('admin/frontend_settings/portal_assets'); ?>" method="post" enctype="multipart/form-data">
                        <div class="alert alert-info">
                            Upload PNG, JPG, JPEG, WEBP, or ICO media. Existing theme styling remains unchanged; only selected files are replaced.
                        </div>
                        <div class="row portal-asset-grid">
                            <?php foreach ($asset_definitions as $key => $asset): ?>
                                <?php $is_background = strpos($key, 'background') !== false; ?>
                                <?php $is_favicon = ! empty($asset['is_favicon']); ?>
                                <?php
                                $preview_source = $is_favicon
                                    ? base_url('uploads/system/' . (get_frontend_settings('favicon') ?: 'favicon.png'))
                                    : portal_asset($key, $asset['fallback']);
                                ?>
                                <div class="<?php echo $is_favicon ? 'col-12' : 'col-xl-6'; ?>">
                                    <section class="portal-asset-item <?php echo $is_favicon ? 'is-favicon' : ''; ?>">
                                        <div class="portal-asset-copy">
                                            <h5 class="mb-1"><?php echo html_escape($asset['label']); ?></h5>
                                            <p class="text-muted mb-0"><?php echo html_escape($asset['description']); ?></p>
                                        </div>
                                        <img
                                            class="portal-asset-preview <?php echo $is_background ? 'is-background' : ''; ?> <?php echo $is_favicon ? 'is-favicon' : ''; ?>"
                                            id="<?php echo $key; ?>_preview"
                                            src="<?php echo html_escape($preview_source); ?>"
                                            alt="<?php echo html_escape($asset['label']); ?> preview"
                                        >
                                        <div class="portal-asset-control">
                                            <div class="custom-file mt-3">
                                                <input
                                                    type="file"
                                                    class="custom-file-input portal-asset-input"
                                                    id="<?php echo $key; ?>"
                                                    name="<?php echo $key; ?>"
                                                    data-preview="<?php echo $key; ?>_preview"
                                                    accept="<?php echo $is_favicon ? '.png,.ico,.jpg,.jpeg,.webp,image/png,image/x-icon,image/vnd.microsoft.icon,image/jpeg,image/webp' : '.png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp'; ?>"
                                                >
                                                <label class="custom-file-label" for="<?php echo $key; ?>">Choose image</label>
                                            </div>
                                            <small class="form-text text-muted"><?php echo $is_favicon ? 'PNG or ICO recommended - Max 2 MB' : 'PNG, JPG or WEBP - Max 8 MB'; ?></small>
                                        </div>
                                    </section>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="bma-settings-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cloud-upload-alt mr-1" aria-hidden="true"></i> Upload Selected Media
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <form class="bma-settings-panel" action="<?php echo site_url('admin/frontend_settings/portal_update'); ?>" method="post">
                        <section class="bma-typography-settings">
                            <div>
                                <h4 class="header-title mb-1">Navbar Site Name</h4>
                                <p class="text-muted mb-0">Control the institution name size in the public website header.</p>
                            </div>
                            <div class="bma-typography-fields">
                                <div class="form-group mb-0">
                                    <label for="portal_nav_site_name_font_size_desktop">Desktop Size</label>
                                    <div class="input-group">
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="portal_nav_site_name_font_size_desktop"
                                            name="portal_nav_site_name_font_size_desktop"
                                            min="12"
                                            max="28"
                                            step="1"
                                            value="<?php echo (int) get_portal_setting('portal_nav_site_name_font_size_desktop', 16); ?>"
                                        >
                                        <div class="input-group-append"><span class="input-group-text">px</span></div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="portal_nav_site_name_font_size_mobile">Mobile Size</label>
                                    <div class="input-group">
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="portal_nav_site_name_font_size_mobile"
                                            name="portal_nav_site_name_font_size_mobile"
                                            min="12"
                                            max="24"
                                            step="1"
                                            value="<?php echo (int) get_portal_setting('portal_nav_site_name_font_size_mobile', 16); ?>"
                                        >
                                        <div class="input-group-append"><span class="input-group-text">px</span></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="alert alert-light border">
                            English is the primary language. Bangla values are used only when visitors choose BN from the language switcher.
                        </div>
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#portal-english" role="tab">English</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#portal-bangla" role="tab">Bangla</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <?php foreach (['en' => 'portal-english', 'bn' => 'portal-bangla'] as $language => $tab_id): ?>
                                <div class="tab-pane fade <?php echo $language === 'en' ? 'show active' : ''; ?>" id="<?php echo $tab_id; ?>" role="tabpanel">
                                    <div class="row">
                                        <?php foreach ($text_defaults as $key => $translations): ?>
                                            <?php
                                            $field_name = 'portal_' . $key . '_' . $language;
                                            $value = portal_text($key, $language);
                                            $label = ucwords(str_replace('_', ' ', $key));
                                            $is_long = in_array($key, $long_fields, true);
                                            ?>
                                            <div class="col-lg-<?php echo $is_long ? '12' : '6'; ?>">
                                                <div class="form-group">
                                                    <label class="portal-field-label" for="<?php echo $field_name; ?>"><?php echo html_escape($label); ?></label>
                                                    <?php if ($is_long): ?>
                                                        <textarea class="form-control" rows="3" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>"><?php echo html_escape($value); ?></textarea>
                                                    <?php else: ?>
                                                        <input type="text" class="form-control" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" value="<?php echo html_escape($value); ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="text-right border-top pt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1" aria-hidden="true"></i> Save Portal Content
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('change', function (event) {
        if (!event.target.classList.contains('custom-file-input')) {
            return;
        }
        var label = event.target.nextElementSibling;
        if (label && event.target.files.length) {
            label.textContent = event.target.files[0].name;
        }

        var previewId = event.target.getAttribute('data-preview');
        var preview = previewId ? document.getElementById(previewId) : null;
        var file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
        if (preview && file && file.type.indexOf('image/') === 0) {
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
