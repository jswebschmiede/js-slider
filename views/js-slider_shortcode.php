<?php

/**
 * The template for the JS Slider shortcode.
 *
 * @package JS_Slider
 */

$title = (!empty($content)) ? esc_html($content) : esc_html(JS_Slider_Settings::$options['js_slider_title']);
$show_bullets = isset(JS_Slider_Settings::$options['js_slider_bullets']) ? JS_Slider_Settings::$options['js_slider_bullets'] : 0;
$show_arrows = isset(JS_Slider_Settings::$options['js_slider_arrows']) ? JS_Slider_Settings::$options['js_slider_arrows'] : 0;

// WP_Query arguments
$args = [
    'post_type' => 'js_slider',
    'post_status' => 'publish',
    'post__in' => $id,
    'posts_per_page' => -1,
    'orderby' => $orderby
];

// The Query
$query = new WP_Query($args);
?>

<h3><?php echo $title; ?></h3>
<div class="swiper js-slider" data-show-bullets="true" data-show-arrows="true">
    <div class="swiper-wrapper">

        <?php while ($query->have_posts()) : ?>
            <?php $query->the_post(); ?>

            <div class="swiper-slide">
                <?php the_post_thumbnail('full', ['class' => 'img-fluid']) ?>

                <div class="js-slider__title">
                    <h2><?php echo the_title(); ?></h2>
                </div>

                <div class="js-slider__description">
                    <div class="js-slider__subtitle">
                        <?php echo the_content(); ?>
                    </div>
                    <a class="js-slider__link" href="<?php echo esc_attr(get_post_meta(get_the_ID(), '_js_slider_link_url', true)); ?>">
                        <?php echo esc_html(get_post_meta(get_the_ID(), '_js_slider_link_text', true)); ?>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>

        <?php wp_reset_postdata(); ?>
    </div>

    <?php if (boolval($show_arrows)) : ?>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    <?php endif ?>

    <?php if (boolval($show_bullets)) : ?>
        <div class="swiper-pagination"></div>
    <?php endif ?>

</div>