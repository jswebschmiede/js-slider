<?php

/**
 * The settings class for the JS Slider plugin.
 * @package JS_Slider
 */

if (!class_exists('JS_Slider_Settings')) {

    class JS_Slider_Settings
    {
        public static bool|array $options_slider;
        public static bool|array $options_additional;

        public function __construct()
        {
            self::$options_slider = get_option('js_slider_options_slider');
            self::$options_additional = get_option('js_slider_options_additional');
            add_action('admin_init', [$this, 'register_options']);

            var_dump(self::$options_slider);
            var_dump(self::$options_additional);
        }

        /**
         * Registers the settings for the JS Slider plugin.
         * @link https://developer.wordpress.org/reference/functions/register_setting/
         * @link https://developer.wordpress.org/reference/functions/add_settings_section/
         * @link https://developer.wordpress.org/reference/functions/add_settings_field/
         * @link https://developer.wordpress.org/reference/hooks/admin_init/
         * @return void
         */
        public function register_options(): void
        {
            // Register the settings.
            register_setting(
                'js_slider_group_1',
                'js_slider_options_slider',
                [$this, 'sanitize_options']
            );
            register_setting(
                'js_slider_group_2',
                'js_slider_options_additional',
                [$this, 'sanitize_options']
            );

            // Register the settings sections.
            add_settings_section(
                'js_slider_main_section',
                __('How does it work?', 'js-slider'),
                null,
                'js_slider_page1'
            );

            add_settings_section(
                'js_slider_second_section',
                __('Slider Options', 'js-slider'),
                null,
                'js_slider_page2'
            );

            add_settings_section(
                'js_slider_second_section',
                __('Plugin Options', 'js-slider'),
                null,
                'js_slider_page3'
            );

            /**
             * Main section fields, page 1.
             */
            add_settings_field(
                'js_slider_shortcode',
                __('Shortcode', 'js-slider'),
                [$this, 'js_slider_shortcode_cb'],
                'js_slider_page1',
                'js_slider_main_section'
            );

            /**
             * Second section fields, page 2.
             */
            add_settings_field(
                'js_slider_title',
                __('Slider Title', 'js-slider'),
                [$this, 'js_slider_title_cb'],
                'js_slider_page2',
                'js_slider_second_section',
                [
                    'label_for' => 'js_slider_title'
                ]
            );

            add_settings_field(
                'js_slider_bullets',
                __('Show Bullets Navigation', 'js-slider'),
                [$this, 'js_slider_bullets_cb'],
                'js_slider_page2',
                'js_slider_second_section',
                [
                    'label_for' => 'js_slider_bullets'
                ]
            );

            add_settings_field(
                'js_slider_arrows',
                __('Show Arrows Navigation', 'js-slider'),
                [$this, 'js_slider_arrows_cb'],
                'js_slider_page2',
                'js_slider_second_section',
                [
                    'label_for' => 'js_slider_arrows'
                ]
            );

            /**
             * Third section fields, page 3.
             */
            add_settings_field(
                'js_slider_styles',
                __('Slider Style', 'js-slider'),
                [$this, 'js_slider_styles_cb'],
                'js_slider_page3',
                'js_slider_second_section',
                [
                    'options' => [
                        'style-1' => __('Style 1', 'js-slider'),
                        'style-2' => __('Style 2', 'js-slider')
                    ],
                    'label_for' => 'js_slider_styles'
                ]
            );
        }

        /**
         * Renders the shortcode field.
         * @param array $args The arguments for the field.
         * @return void
         */
        public function js_slider_shortcode_cb($args): void
        {
            echo '<span>' . __('Use the shortcode <code>[js_slider]</code> to display the slider in any page, post or widget.', 'js-slider') . '</span>';
        }

        /**
         * Renders the title field.
         * @param array $args The arguments for the field.
         * @return void
         */
        public function js_slider_title_cb($args): void
        {
            $js_slider_title = self::$options_slider['js_slider_title'] ?? '';
            echo '<input type="text" name="js_slider_options_slider[js_slider_title]" id="js_slider_title" value="' . esc_attr($js_slider_title) . '" />';
        }

        /**
         * Renders the bullets field.
         * @param array $args The arguments for the field.
         * @return void
         */
        public function js_slider_bullets_cb($args): void
        {
            $js_slider_bullets = self::$options_slider['js_slider_bullets'] ?? 0;
            echo '<input type="checkbox" name="js_slider_options_slider[js_slider_bullets]" id="js_slider_bullets" value="1" ' . checked(1, $js_slider_bullets, false) . ' />';
        }

        /**
         * Renders the arrows field.
         * @param array $args The arguments for the field.
         * @return void
         */
        public function js_slider_arrows_cb($args): void
        {
            $js_slider_arrows = self::$options_slider['js_slider_arrows'] ?? 0;
            echo '<input type="checkbox" name="js_slider_options_slider[js_slider_arrows]" id="js_slider_arrows" value="1" ' . checked(1, $js_slider_arrows, false) . ' />';
        }

        /**
         * Renders the styles field.
         * @param array $args The arguments for the field.
         * @return void
         */
        public function js_slider_styles_cb($args): void
        {
            $js_slider_styles = self::$options_additional['js_slider_styles'] ?? 'style-1';

            echo '<select name="js_slider_options_additional[js_slider_styles]" id="js_slider_styles">';
            foreach ($args['options'] as $value => $label) {
                echo '<option value="' . esc_attr($value) . '" ' . selected($value, $js_slider_styles, false) . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }

        /**
         * Sanitizes the options.
         * @param array $input The options to sanitize.
         * @return array The sanitized options.
         */
        public function sanitize_options($input): array
        {
            $new_input = [];

            foreach ($input as $key => $value) {
                switch ($key) {
                    case 'js_slider_title':
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                    case 'js_slider_bullets':
                    case 'js_slider_arrows':
                        $new_input[$key] = absint($value);
                        break;
                    case 'js_slider_styles':
                        $new_input[$key] = sanitize_key($value);
                        break;
                    default:
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }
            }
            return $new_input;
        }
    }
}
