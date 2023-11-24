<?php

/**
 * The admin page for the JS Slider plugin.
 * @package JS_Slider
 */


$default_tab = 'main_options';
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <nav class="nav-tab-wrapper">
        <a href="<?php echo admin_url('?page=js-slider-admin&tab=main_options'); ?>" class="nav-tab <?php if ($tab === 'main_options') : ?>nav-tab-active<?php endif; ?>">
            <?php _e('Main Options', 'js-slider'); ?>
        </a>
        <a href="<?php echo admin_url('?page=js-slider-admin&tab=slider_options'); ?>" class="nav-tab <?php if ($tab === 'slider_options') : ?>nav-tab-active<?php endif; ?>">
            <?php _e('Slider Options', 'js-slider'); ?>
        </a>
        <a href="<?php echo admin_url('?page=js-slider-admin&tab=additional_options'); ?>" class="nav-tab <?php if ($tab === 'additional_options') : ?>nav-tab-active<?php endif; ?>">
            <?php _e('Additional Options', 'js-slider'); ?>
        </a>
    </nav>

    <form action="options.php" method="post">
        <?php

        switch ($tab) {
            case 'slider_options':
                settings_fields('js_slider_group_1');
                do_settings_sections('js_slider_page2');
                break;
            case 'additional_options':
                settings_fields('js_slider_group_2');
                do_settings_sections('js_slider_page3');
                break;
            default:
                settings_fields('js_slider_group_1');
                do_settings_sections('js_slider_page1');
                break;
        }

        submit_button();
        ?>
    </form>
</div>