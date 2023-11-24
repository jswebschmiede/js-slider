<?php

/**
 * The post type class for the JS Slider plugin.
 * @link https://developer.wordpress.org/reference/classes/wp_post_type/
 * @package JS_Slider
 */

if (!class_exists('JS_Slider_Post_Type')) {

    class JS_Slider_Post_Type
    {
        public function __construct()
        {
            add_action('init', [$this, 'create_post_type']);
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_meta_box'], 10, 2);
            add_filter('manage_js_slider_posts_columns', [$this, 'add_columns']);
            add_filter('manage_edit-js_slider_sortable_columns', [$this, 'sortable_columns']);
            add_action('manage_js_slider_posts_custom_column', [$this, 'render_columns'], 10, 2);
        }

        /**
         * Registers the post type.
         * @link https://codex.wordpress.org/Function_Reference/register_post_type
         * @return void
         */
        public function create_post_type(): void
        {
            register_post_type('js_slider', [
                'label' => __('Slider', 'js-slider'),
                'description' => __('Sliders', 'js-slider'),
                'labels' => [
                    'name' => __('Sliders', 'js-slider'),
                    'singular_name' => __('Slider', 'js-slider'),
                ],
                'public'            => true,
                'has_archive'       => false,
                'supports'          => ['title', 'editor', 'thumbnail'],
                'hierarchical'      => false,
                'show_ui'           => true,
                'show_in_menu'      => false,
                'show_in_admin_bar' => true,
                'menu_position'     => 20, // Below 'Pages
                'menu_icon'         => 'dashicons-images-alt2',
                'can_export'        => true,
                'show_in_rest'      => false,
            ]);
        }

        /**
         * Adds the columns to the post type.
         * @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
         * @param array $columns The columns.
         * @return array The new added columns.
         */
        public function add_columns(array $columns): array
        {
            $columns = [
                'cb' => $columns['cb'],
                'js_slider_image' => esc_html__('Image'),
                'title' => esc_html__('Title'),
                'js_slider_link_text' => esc_html__('Link Text', 'js-slider'),
                'js_slider_link_url' => esc_html__('Link URL', 'js-slider')
            ];

            return $columns;
        }

        /**
         * Makes the columns sortable.
         * @link https://developer.wordpress.org/reference/hooks/manage_this-screen-id_sortable_columns/
         * @param array $columns The columns.
         * @return array The sortable columns.
         */
        public function sortable_columns(array $columns): array
        {
            $columns['js_slider_link_text'] = 'js_slider_link_text';
            return $columns;
        }

        /**
         * Renders the columns for the post type.
         * @link https://developer.wordpress.org/reference/hooks/manage_posts_custom_column/
         * @param string $column The column.
         * @param int $post_id The post ID.
         * @return void
         */
        public function render_columns(string $column, int $post_id): void
        {
            switch ($column) {
                case 'js_slider_link_text':
                    echo esc_html(get_post_meta($post_id, '_js_slider_link_text', true));
                    break;
                case 'js_slider_link_url':
                    echo esc_url(get_post_meta($post_id, '_js_slider_link_url', true));
                    break;
                case 'js_slider_image':
                    echo get_the_post_thumbnail($post_id, [80, 80]);
                    break;
                default:
                    break;
            }
        }

        /**
         * Adds the meta boxes for the post type.
         * @link https://developer.wordpress.org/reference/hooks/add_meta_boxes/
         * @return void
         */
        public function add_meta_boxes(): void
        {
            /**
             * Add the meta box.
             * @link https://developer.wordpress.org/reference/functions/add_meta_box/
             */
            add_meta_box(
                'js_slider_meta_box',
                __('Link Options', 'js-slider'),
                [$this, 'render_meta_box'],
                'js_slider',
                'normal',
                'high'
            );
        }

        /**
         * Renders the meta box.
         *
         * @param WP_Post $post The post object.
         * @return void
         */
        public function render_meta_box(WP_Post $post): void
        {
            require_once JS_SLIDER_PLUGIN_PATH . 'views/js-slider_metabox.php';
        }

        /**
         * Saves the meta box.
         * @link https://developer.wordpress.org/reference/hooks/save_post/
         * @param int $post_id The post ID.
         * @return void
         */
        public function save_meta_box(int $post_id): void
        {
            /**
             * Verify the post action.
             */
            if (array_key_exists('action', $_POST) && $_POST['action'] === 'editpost') {

                /**
                 * Verify the nonce.
                 * @link https://developer.wordpress.org/reference/functions/wp_verify_nonce/
                 */
                if (!array_key_exists('js_slider_nonce', $_POST) || !wp_verify_nonce($_POST['js_slider_nonce'], 'js_slider_nonce')) {
                    return;
                }

                /**
                 * Prevent the data from being autosaved.
                 */
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }

                /**
                 * Verify the post type.
                 */
                if (!array_key_exists('post_type', $_POST) || $_POST['post_type'] !== 'js_slider') {
                    return;
                }

                /**
                 * Verify the user's permissions.
                 * @link https://developer.wordpress.org/reference/functions/current_user_can/
                 */
                if (!current_user_can('edit_post', $post_id) || !current_user_can('edit_page', $post_id)) {
                    return;
                }

                /**
                 * Get the old values.
                 * @link https://developer.wordpress.org/reference/functions/get_post_meta/
                 */
                $old_link_text = get_post_meta($post_id, '_js_slider_link_text', true);
                $old_link_url = get_post_meta($post_id, '_js_slider_link_url', true);

                /**
                 * Sanitize the data and save it to the database.
                 * @link https://developer.wordpress.org/reference/functions/update_post_meta/
                 */
                if (array_key_exists('js_slider_link_text', $_POST)) {
                    $new_link_text = !empty($_POST['js_slider_link_text']) ? sanitize_text_field($_POST['js_slider_link_text']) : 'Add Some Text';

                    update_post_meta(
                        $post_id,
                        '_js_slider_link_text',
                        $new_link_text,
                        $old_link_text
                    );
                }

                if (array_key_exists('js_slider_link_url', $_POST)) {
                    $new_link_url = !empty($_POST['js_slider_link_url']) ? esc_url_raw($_POST['js_slider_link_url']) : '#';

                    update_post_meta(
                        $post_id,
                        '_js_slider_link_url',
                        $new_link_url,
                        $old_link_url
                    );
                }
            }
        }
    }
}
