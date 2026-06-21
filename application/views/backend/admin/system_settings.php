<?php
$system_title = get_settings('system_title') ?: 'Certificate Verification System';
$timezone = get_settings('timezone') ?: 'Asia/Dhaka';
?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1"><i class="mdi mdi-cog-outline title_icon"></i> System Settings</h4>
                    <p class="text-muted mb-0">Manage the identity, contact details, metadata, and access defaults used across the portal.</p>
                </div>
                <a href="<?php echo site_url('/'); ?>" class="btn btn-outline-primary mt-2 mt-md-0" target="_blank">
                    <i class="mdi mdi-open-in-new mr-1"></i> View Public Portal
                </a>
            </div>
        </div>
    </div>
</div>

<form class="required-form" action="<?php echo site_url('admin/system_settings/system_update'); ?>" method="post">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">System Identity</h4>
                    <div class="form-group">
                        <label for="system_name">Institution Name <span class="text-danger">*</span></label>
                        <input type="text" name="system_name" id="system_name" class="form-control" value="<?php echo html_escape(get_settings('system_name')); ?>" required>
                        <small class="form-text text-muted">Used in page metadata, emails, and administrative areas.</small>
                    </div>
                    <div class="form-group">
                        <label for="system_title">Website Title <span class="text-danger">*</span></label>
                        <input type="text" name="system_title" id="system_title" class="form-control" value="<?php echo html_escape($system_title); ?>" required>
                        <small class="form-text text-muted">The official product title. Recommended: Certificate Verification System.</small>
                    </div>
                    <div class="form-group">
                        <label for="slogan">Slogan</label>
                        <input type="text" name="slogan" id="slogan" class="form-control" value="<?php echo html_escape(get_settings('slogan')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="author">Content Owner</label>
                        <input type="text" name="author" id="author" class="form-control" value="<?php echo html_escape(get_settings('author')); ?>">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Contact Details</h4>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="system_email">System Email <span class="text-danger">*</span></label>
                            <input type="email" name="system_email" id="system_email" class="form-control" value="<?php echo html_escape(get_settings('system_email')); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Support Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?php echo html_escape(get_settings('phone')); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Office Address</label>
                        <textarea name="address" id="address" class="form-control" rows="3"><?php echo html_escape(get_settings('address')); ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label for="footer_text">Footer Copyright Text</label>
                            <input type="text" name="footer_text" id="footer_text" class="form-control" value="<?php echo html_escape(get_settings('footer_text')); ?>">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="footer_link">Footer Link</label>
                            <input type="text" name="footer_link" id="footer_link" class="form-control" value="<?php echo html_escape(get_settings('footer_link')); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Search Metadata</h4>
                    <div class="form-group">
                        <label for="website_description">Website Description</label>
                        <textarea name="website_description" id="website_description" class="form-control" rows="4"><?php echo html_escape(get_settings('website_description')); ?></textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label for="website_keywords">Website Keywords</label>
                        <input type="text" name="website_keywords" id="website_keywords" class="form-control" value="<?php echo html_escape(get_settings('website_keywords')); ?>">
                        <small class="form-text text-muted">Use comma-separated phrases.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Locale & Access</h4>
                    <div class="form-group">
                        <label for="language">Default Language</label>
                        <select name="language" id="language" class="form-control">
                            <?php foreach ($languages as $language): ?>
                                <option value="<?php echo html_escape($language); ?>" <?php echo get_settings('language') === $language ? 'selected' : ''; ?>>
                                    <?php echo html_escape(ucfirst($language)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="timezone">Timezone</label>
                        <select name="timezone" id="timezone" class="form-control">
                            <?php foreach (DateTimeZone::listIdentifiers() as $identifier): ?>
                                <option value="<?php echo html_escape($identifier); ?>" <?php echo $timezone === $identifier ? 'selected' : ''; ?>>
                                    <?php echo html_escape($identifier); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="public_signup">Public Account Registration</label>
                        <select name="public_signup" id="public_signup" class="form-control">
                            <option value="0" <?php echo get_settings('public_signup') != '1' ? 'selected' : ''; ?>>Disabled</option>
                            <option value="1" <?php echo get_settings('public_signup') == '1' ? 'selected' : ''; ?>>Enabled</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label for="allowed_device_number_of_loging">Allowed Login Devices</label>
                        <input type="number" min="1" max="20" name="allowed_device_number_of_loging" id="allowed_device_number_of_loging" class="form-control" value="<?php echo max(1, (int) get_settings('allowed_device_number_of_loging')); ?>">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-2">Portal Content</h4>
                    <p class="text-muted">Homepage, login, dashboard wording, logos, and theme backgrounds are managed separately.</p>
                    <a href="<?php echo site_url('admin/frontend_settings'); ?>" class="btn btn-outline-primary btn-block">
                        <i class="mdi mdi-monitor-edit mr-1"></i> Open Frontend Settings
                    </a>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
                <i class="mdi mdi-content-save mr-1"></i> Save System Settings
            </button>
        </div>
    </div>
</form>
