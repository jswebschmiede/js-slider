<?php

/**
 * The template for the JS Slider metabox.
 *
 * @package JS_Slider
 */

$link_text = get_post_meta($post->ID, '_js_slider_link_text', true);
$link_url = get_post_meta($post->ID, '_js_slider_link_url', true);

?>

<table class="form-table js-slider-metabox">
    <tr>
        <th>
            <label for="js_slider_link_text">Link Text</label>
        </th>
        <td>
            <input type="text" name="js_slider_link_text" id="js_slider_link_text" class="regular-text link-text" value="<?php echo (isset($link_text)) ? esc_html($link_text) : ''; ?>" required>
        </td>
    </tr>
    <tr>
        <th>
            <label for="js_slider_link_url">Link URL</label>
        </th>
        <td>
            <input type="url" name="js_slider_link_url" id="js_slider_link_url" class="regular-text link-url" value="<?php echo (isset($link_url)) ? esc_url($link_url) : ''; ?>" required>
        </td>
    </tr>
    <input type="hidden" name="js_slider_nonce" value="<?php echo wp_create_nonce('js_slider_nonce'); ?>">
</table>