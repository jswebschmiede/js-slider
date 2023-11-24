<?php
/*
Plugin Name: JS Slider
Plugin URI: http://www.meine-webseite.de/mein-plugin
Description: Eine kurze Beschreibung meines Plugins.
Version: 1.0
Author: Jörg Schöneburg
Author URI: http://www.meine-webseite.de
License: GPL2 V2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: js-slider
Domain Path: /languages
Requires at least: 5.2
Requires PHP: 8.0
*/


defined('ABSPATH') or die('Thanks for visting');

if (!class_exists('JS_Slider')) {

    /**
     * The main class for the JS Slider plugin.
     */
    class JS_Slider
    {
        public function __construct()
        {
            $this->define_constants();

            add_action('admin_menu', [$this, 'add_admin_menu']);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 999);

            require_once JS_SLIDER_PLUGIN_PATH . 'post-types/class.js-slider-cpt.php';
            $js_slider_cpt = new JS_Slider_Post_Type();

            require_once JS_SLIDER_PLUGIN_PATH . 'class.js-slider-settings.php';
            $js_slider_settings = new JS_Slider_Settings();

            require_once JS_SLIDER_PLUGIN_PATH . 'shortcodes/class.js-slider-shortcode.php';
            $js_slider_shortcode = new JS_Slider_Shortcode();
        }

        /**
         * Defines the constants for the JS Slider plugin.
         * @return void
         */
        public function define_constants(): void
        {
            define('JS_SLIDER_VERSION', '1.0.0');
            define('JS_SLIDER_PLUGIN_PATH', plugin_dir_path(__FILE__));
            define('JS_SLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        /**
         * create the admin page menu for the JS Slider plugin.
         * @link https://developer.wordpress.org/reference/functions/add_menu_page/
         * @return void
         */
        public function add_admin_menu(): void
        {
            $adminmenu = add_menu_page(
                __('JS Slider', 'js-slider'),
                __('JS Slider', 'js-slider'),
                'manage_options',
                'js-slider-admin',
                [$this, 'render_admin_page'],
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'js-slider-admin',
                __('Manage Slides', 'js-slider'),
                __('Manage Slides', 'js-slider'),
                'manage_options',
                'edit.php?post_type=js_slider',
                null,
                null
            );

            add_submenu_page(
                'js-slider-admin',
                __('Add New Slide', 'js-slider'),
                __('Add New Slide', 'js-slider'),
                'manage_options',
                'post-new.php?post_type=js_slider',
                null,
                null
            );

            add_action('admin_print_styles-' . $adminmenu, [$this, 'enqueue_admin_styles']);
            add_action('admin_print_scripts-' . $adminmenu, [$this, 'enqueue_admin_scripts']);
        }

        /**
         * Renders the admin page for the JS Slider plugin.
         * @return void
         */
        public function render_admin_page(): void
        {
            if (!current_user_can('manage_options')) {
                return;
            }

            if (isset($_GET['settings-updated'])) {
                // add settings saved message with the class of "updated"
                add_settings_error('js_slider_options', 'js_slider_message', __('Settings Saved', 'js-slider'), 'success');
            }
            // show error/update messages
            settings_errors('js_slider_options');

            require_once JS_SLIDER_PLUGIN_PATH . 'views/js-slider_admin-page.php';
        }

        /**
         * Enqueues the admin styles for the JS Slider plugin.
         * @return void
         */
        public function enqueue_admin_styles(): void
        {
            wp_enqueue_style('js-slider-admin', JS_SLIDER_PLUGIN_URL . 'assets/css/js-slider-admin.css');
        }

        /**
         * Enqueues the admin scripts for the JS Slider plugin.
         * @return void
         */
        public function enqueue_admin_scripts(): void
        {
            wp_enqueue_script('js-slider-admin', JS_SLIDER_PLUGIN_URL . 'assets/js/js-slider-admin.js');
        }

        public function enqueue_scripts(): void
        {
            wp_register_style('js-slider-swiper', JS_SLIDER_PLUGIN_URL . 'assets/vendor/swiper/css/swiper-bundle.css', [], JS_SLIDER_VERSION);
            wp_register_script('js-slider-swiper-bundle', JS_SLIDER_PLUGIN_URL . 'assets/vendor/swiper/js/swiper-bundle.min.js', [], JS_SLIDER_VERSION, true);
            wp_register_script('js-slider-swiper', JS_SLIDER_PLUGIN_URL . 'assets/vendor/swiper/js/swiper.js', [], JS_SLIDER_VERSION, true);
        }

        public static function activate(): void
        {
            flush_rewrite_rules();
        }

        public static function deactivate(): void
        {
            flush_rewrite_rules();
            unregister_post_type('js_slider');
        }

        public static function uninstall(): void
        {
        }
    }
}

/**
 * Initializes the JS Slider plugin.
 */
if (class_exists('JS_Slider')) {
    register_activation_hook(__FILE__, ['JS_Slider', 'activate']);
    register_deactivation_hook(__FILE__, ['JS_Slider', 'deactivate']);
    register_uninstall_hook(__FILE__, ['JS_Slider', 'uninstall']);
    $js_slider = new JS_Slider();
}
