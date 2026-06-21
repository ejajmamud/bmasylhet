<?php
$header_admin = $this->user_model->get_all_user($this->session->userdata('user_id'))->row_array();
$is_govt_theme = get_portal_theme() === 'govt_green';
$header_logo = $is_govt_theme
    ? portal_asset('portal_govt_logo', 'assets/global/logo/bangladesh_logo.png')
    : portal_asset('portal_academy_logo', 'assets/global/logo/BMA.png');
?>
<div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid">
        <a href="<?php echo site_url('admin/dashboard'); ?>" class="topnav-logo <?php echo $is_govt_theme ? 'bma-dashboard-brand' : 'bma-academy-brand'; ?>">
            <span class="topnav-logo-lg">
                <img src="<?php echo html_escape($header_logo); ?>" alt="<?php echo html_escape(portal_text('institution_name', 'en')); ?>" height="<?php echo $is_govt_theme ? '46' : '52'; ?>">
                <span class="<?php echo $is_govt_theme ? 'bma-dashboard-brand-copy' : 'bma-academy-brand-copy'; ?>">
                    <strong><?php echo html_escape(portal_text('institution_name', 'en')); ?></strong>
                    <small>Certificate Verification Administration</small>
                </span>
            </span>
            <span class="topnav-logo-sm"><img src="<?php echo html_escape($header_logo); ?>" alt="" height="42"></span>
        </a>

        <ul class="list-unstyled topbar-right-menu float-right mb-0">
            <li class="notification-list d-lg-none">
                <a class="nav-link side-nav-link" data-toggle="collapse" href="#topnav-menu-content" role="button"><i class="dripicons-menu noti-icon"></i></a>
            </li>
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="account-user-avatar"><img src="<?php echo $this->user_model->get_user_image_url($this->session->userdata('user_id')); ?>" alt="" class="rounded-circle"></span>
                    <span>
                        <span class="account-user-name"><?php echo html_escape(trim($header_admin['first_name'] . ' ' . $header_admin['last_name'])); ?></span>
                        <span class="account-position">Super Administrator</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                    <a href="<?php echo site_url('admin/manage_profile'); ?>" class="dropdown-item notify-item"><i class="mdi mdi-account-circle mr-1"></i><span>My Profile</span></a>
                    <a href="<?php echo site_url('/'); ?>" target="_blank" class="dropdown-item notify-item"><i class="mdi mdi-web mr-1"></i><span>Open Public Portal</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo site_url('login/logout'); ?>" class="dropdown-item notify-item"><i class="mdi mdi-logout mr-1"></i><span>Sign Out</span></a>
                </div>
            </li>
        </ul>
        <button class="button-menu-mobile open-left disable-btn"><i class="mdi mdi-menu"></i></button>
    </div>
</div>
