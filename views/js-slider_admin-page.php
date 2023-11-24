<?php

/**
 * The admin page for the JS Slider plugin.
 * @package JS_Slider
 */


$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'main_options';

$active_tab_main_options = $active_tab == 'main_options' ? 'nav-tab-active' : '';
$active_tab_additional_options = $active_tab == 'additional_options' ? 'nav-tab-active' : '';

?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url('?page=js-slider-admin&tab=main_options'); ?>" class="nav-tab <?php echo $active_tab_main_options; ?>">
            <?php _e('Main Options', 'js-slider'); ?>
        </a>
        <a href="<?php echo admin_url('?page=js-slider-admin&tab=additional_options'); ?>" class="nav-tab <?php echo $active_tab_additional_options; ?>">
            <?php _e('Additional Options', 'js-slider'); ?>
        </a>
    </h2>

    <form action="options.php" method="post">
        <?php

        if ($active_tab == 'main_options') {
            settings_fields('js_slider_group');
            do_settings_sections('js_slider_page1');
        } else {
            settings_fields('js_slider_group');
            do_settings_sections('js_slider_page2');
        }

        submit_button();
        ?>
    </form>
</div>