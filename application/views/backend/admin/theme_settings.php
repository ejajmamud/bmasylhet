<?php
    $active_theme = get_portal_theme();
    $themes = [
        'govt_green' => [
            'name' => 'Govt_Green',
            'description' => 'Government-inspired maritime portal with green overlay, bilingual EN/BN support, and matching login and dashboard surfaces.',
            'preview' => base_url('assets/global/theme-previews/govt-green.png'),
        ],
        'academy_default' => [
            'name' => 'Academy_Default',
            'description' => 'Professional Marine Academy identity with white navigation, navy maritime surfaces, the official academy crest, and coordinated portal, login, and dashboard views.',
            'preview' => base_url('assets/global/theme-previews/academy-default.png'),
        ],
    ];
?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"><i class="mdi mdi-palette title_icon"></i> System Themes</h4>
                <p class="text-muted mb-0 mt-2">A system theme controls the public portal, staff login, and authenticated dashboards together.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($themes as $theme_key => $theme): ?>
        <div class="col-xl-6 col-lg-6">
            <div class="card h-100 bma-theme-card<?php echo $active_theme === $theme_key ? ' bma-theme-active' : ''; ?>">
                <img class="card-img-top bma-theme-preview" src="<?php echo html_escape($theme['preview']); ?>" alt="<?php echo html_escape($theme['name']); ?> preview">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0"><?php echo html_escape($theme['name']); ?></h5>
                        <?php if ($active_theme === $theme_key): ?>
                            <span class="badge badge-success">Active</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted flex-grow-1"><?php echo html_escape($theme['description']); ?></p>
                    <?php if ($active_theme === $theme_key): ?>
                        <button class="btn btn-success btn-block" type="button" disabled>
                            <i class="mdi mdi-check-circle"></i> Active Theme
                        </button>
                    <?php else: ?>
                        <button class="btn btn-primary btn-block" type="button" onclick="activatePortalTheme('<?php echo html_escape($theme_key); ?>', this)">
                            <i class="mdi mdi-palette"></i> Activate <?php echo html_escape($theme['name']); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .bma-theme-card {
        overflow: hidden;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .bma-theme-active {
        border: 2px solid #00a63e !important;
    }
    .bma-theme-preview {
        width: 100%;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        object-position: top;
        background: #eef3ef;
        border-bottom: 1px solid #d4ddd7;
    }
</style>

<script>
function activatePortalTheme(theme, button) {
    var original = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Activating';

    $.ajax({
        url: '<?php echo site_url('admin/portal_theme/activate'); ?>',
        type: 'POST',
        data: { theme: theme },
        success: function (response) {
            if (response) {
                success_notify('Theme successfully activated');
                window.setTimeout(function () {
                    window.location.reload();
                }, 700);
            } else {
                button.disabled = false;
                button.innerHTML = original;
                error_notify('Theme could not be activated');
            }
        },
        error: function () {
            button.disabled = false;
            button.innerHTML = original;
            error_notify('Theme could not be activated');
        }
    });
}
</script>
