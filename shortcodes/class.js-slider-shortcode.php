<?php

class JS_Slider_Shortcode
{
    public function __construct()
    {
        add_shortcode('js_slider', [$this, 'render_shortcode']);
    }

    public function render_shortcode($atts = [], $content = null, $tag = ''): string
    {
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // set default attributes
        extract(shortcode_atts([
            'id' => '',
            'orderby' => 'date',
        ], $atts, $tag));

        // sanitize attributes
        if (!empty($id)) {
            $id = array_map('absint', explode(',', $id));
        }

        ob_start();
        require JS_SLIDER_PLUGIN_PATH . 'views/js-slider_shortcode.php';
        wp_enqueue_style('js-slider-swiper');
        wp_enqueue_script('js-slider-swiper-bundle');
        wp_enqueue_script('js-slider-swiper');
        return ob_get_clean();
    }
}
