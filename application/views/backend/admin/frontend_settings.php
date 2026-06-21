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
];
$long_fields = [
    'portal_subtitle', 'required_note', 'certificate_security_note', 'student_id_note',
    'student_name_note', 'qr_camera_note', 'scanner_error', 'login_subtitle',
    'authorized_only', 'login_security_note', 'footer_note', 'invalid_title',
    'invalid_help', 'multiple_matches',
];
?>

<style>
    .portal-settings-nav .nav-link { min-width: 150px; }
    .portal-field-label { text-transform: capitalize; }
    .portal-asset-preview {
        width: 100%;
        height: 190px;
        object-fit: contain;
        background: #f3f5f4;
        border: 1px solid #dce3df;
        border-radius: 6px;
        padding: 12px;
    }
    .portal-asset-preview.is-background { object-fit: cover; padding: 0; }
</style>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1"><i class="mdi mdi-monitor-edit title_icon"></i> Frontend Settings</h4>
                    <p class="text-muted mb-0">Every field below is connected to the live public portal, login screen, or dashboard.</p>
                </div>
                <div class="mt-2 mt-md-0">
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
                <ul class="nav nav-pills bg-nav-pills portal-settings-nav mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active_tab === 'portal_content' ? 'active' : ''; ?>" href="<?php echo site_url('admin/frontend_settings?tab=portal_content'); ?>">
                            <i class="mdi mdi-translate mr-1"></i> Portal Content
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active_tab === 'portal_assets' ? 'active' : ''; ?>" href="<?php echo site_url('admin/frontend_settings?tab=portal_assets'); ?>">
                            <i class="mdi mdi-image-multiple mr-1"></i> Logos & Backgrounds
                        </a>
                    </li>
                </ul>

                <?php if ($active_tab === 'portal_assets'): ?>
                    <form action="<?php echo site_url('admin/frontend_settings/portal_assets'); ?>" method="post" enctype="multipart/form-data">
                        <div class="alert alert-info">
                            Upload PNG, JPG, JPEG, or WEBP images up to 8 MB. Existing theme styling remains unchanged; only the selected visual asset is replaced.
                        </div>
                        <div class="row">
                            <?php foreach ($asset_definitions as $key => $asset): ?>
                                <?php $is_background = strpos($key, 'background') !== false; ?>
                                <div class="col-lg-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h5 class="mb-1"><?php echo html_escape($asset['label']); ?></h5>
                                            <p class="text-muted"><?php echo html_escape($asset['description']); ?></p>
                                            <img class="portal-asset-preview <?php echo $is_background ? 'is-background' : ''; ?>" src="<?php echo portal_asset($key, $asset['fallback']); ?>" alt="<?php echo html_escape($asset['label']); ?> preview">
                                            <div class="custom-file mt-3">
                                                <input type="file" class="custom-file-input" id="<?php echo $key; ?>" name="<?php echo $key; ?>" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp">
                                                <label class="custom-file-label" for="<?php echo $key; ?>">Choose image</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-cloud-upload mr-1"></i> Upload Selected Assets
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <form action="<?php echo site_url('admin/frontend_settings/portal_update'); ?>" method="post">
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
                                <i class="mdi mdi-content-save mr-1"></i> Save Portal Content
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
    });
</script>
