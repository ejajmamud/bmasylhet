<?php
$admin_details = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
$cadetPages = ['cadets', 'cadet_form', 'cadet_view'];
$logPages = ['cadet_verification_logs', 'cadet_audit_logs'];
$settingsPages = ['system_settings', 'frontend_settings', 'theme_settings', 'manage_profile'];
?>
<div class="left-side-menu left-side-menu-detached">
    <div class="leftbar-user">
        <a href="<?php echo site_url('admin/manage_profile'); ?>">
            <img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="" height="42" class="rounded-circle shadow-sm">
            <span class="leftbar-user-name"><?php echo html_escape(trim($admin_details['first_name'] . ' ' . $admin_details['last_name'])); ?></span>
            <small class="d-block mt-1">Super Administrator</small>
        </a>
    </div>

    <ul class="metismenu side-nav side-nav-light">
        <li class="side-nav-title side-nav-item">Certificate System</li>
        <li class="side-nav-item <?php echo $page_name === 'dashboard' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('admin/dashboard'); ?>" class="side-nav-link">
                <i class="fas fa-th-large" aria-hidden="true"></i><span>Dashboard</span>
            </a>
        </li>

        <?php if (has_permission('cadet_view')): ?>
            <li class="side-nav-item <?php echo in_array($page_name, $cadetPages, true) ? 'active' : ''; ?>">
                <a href="javascript:void(0)" class="side-nav-link">
                    <i class="fas fa-id-card" aria-hidden="true"></i>
                    <span>Cadet Records</span><span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li class="<?php echo $page_name === 'cadets' ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/cadets'); ?>">All Cadets</a></li>
                    <?php if (has_permission('cadet_create')): ?>
                        <li class="<?php echo $page_name === 'cadet_form' ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/cadets/create'); ?>">Add Cadet</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo site_url('admin/cadets?status=draft'); ?>">Draft Records</a></li>
                    <li><a href="<?php echo site_url('admin/cadets?status=published'); ?>">Published Records</a></li>
                    <li><a href="<?php echo site_url('admin/cadets?status=suspended'); ?>">Suspended Records</a></li>
                    <li><a href="<?php echo site_url('admin/cadets?status=archived'); ?>">Archived Records</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (has_permission('verification_log_view') || has_permission('cadet_audit_view')): ?>
            <li class="side-nav-item <?php echo in_array($page_name, $logPages, true) ? 'active' : ''; ?>">
                <a href="javascript:void(0)" class="side-nav-link">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i><span>Activity</span><span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <?php if (has_permission('verification_log_view')): ?><li><a href="<?php echo site_url('admin/verification-logs'); ?>">Verification Logs</a></li><?php endif; ?>
                    <?php if (has_permission('cadet_audit_view')): ?><li><a href="<?php echo site_url('admin/audit-logs'); ?>">Audit Logs</a></li><?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>

        <?php if (has_permission('admins')): ?>
            <li class="side-nav-item <?php echo in_array($page_name, ['admins', 'admin_add', 'admin_edit', 'admin_permission'], true) ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/admins'); ?>" class="side-nav-link">
                    <i class="fas fa-user-shield" aria-hidden="true"></i><span>Administrators</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="side-nav-title side-nav-item mt-3">Configuration</li>
        <?php if (has_permission('theme')): ?>
            <li class="side-nav-item <?php echo $page_name === 'theme_settings' ? 'active' : ''; ?>">
                <a href="<?php echo site_url('admin/theme_settings'); ?>" class="side-nav-link">
                    <i class="fas fa-palette" aria-hidden="true"></i><span>Theme Settings</span>
                </a>
            </li>
        <?php endif; ?>
        <?php if (has_permission('settings')): ?>
            <li class="side-nav-item <?php echo in_array($page_name, ['system_settings', 'frontend_settings'], true) ? 'active' : ''; ?>">
                <a href="javascript:void(0)" class="side-nav-link">
                    <i class="fas fa-cog" aria-hidden="true"></i><span>Settings</span><span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li><a href="<?php echo site_url('admin/system_settings'); ?>">System Settings</a></li>
                    <li><a href="<?php echo site_url('admin/frontend_settings'); ?>">Website Settings</a></li>
                </ul>
            </li>
        <?php endif; ?>

        <li class="side-nav-title side-nav-item mt-3">Account</li>
        <li class="side-nav-item <?php echo $page_name === 'manage_profile' ? 'active' : ''; ?>">
            <a href="<?php echo site_url('admin/manage_profile'); ?>" class="side-nav-link">
                <i class="fas fa-user-circle" aria-hidden="true"></i><span>My Profile</span>
            </a>
        </li>
        <li class="side-nav-item">
            <a href="<?php echo site_url('login/logout'); ?>" class="side-nav-link">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i><span>Sign Out</span>
            </a>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>
