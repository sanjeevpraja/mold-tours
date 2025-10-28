<?php
// render.php
// Block rendering function
function callback_block_tour_gallery($attributes, $content, $block)
{

    global $post;

    if ( ! $post ) {
        return '';
    }

    $mold_slider_bg = get_post_meta( $post->ID, 'mold_slider_bg', true );
    $mold_slider_effect = get_post_meta($post->ID, 'mold_slider_effect', true);
    $mold_slider_height = get_post_meta($post->ID, 'mold_slider_height', true) == 'full' ? '100vh' : '600px';
    $mold_slider_nav = get_post_meta($post->ID, 'mold_slider_nav', true);
    $mold_slider_indicator = get_post_meta($post->ID, 'mold_slider_indicator', true);
    $mold_slider_speed = get_post_meta($post->ID, 'mold_slider_speed', true);
    $defaults = [
        'speed'        => 1000,
        'pagination'   => $mold_slider_indicator == 'hide' ? false : true,
        'navigation'   => $mold_slider_nav == 'hide' ? false : true,
        'effect'       => $mold_slider_effect
    ];
    $attributes = wp_parse_args( $attributes, $defaults );

    // Now safe to render
    $data_attrs = sprintf(
        'data-attr-speed="%d"
         data-attr-pagination="%s"
         data-attr-navigation="%s"
         data-attr-effect="%s"',
        $mold_slider_speed,
        $mold_slider_indicator == 'hide' ? 'false' : 'true',
        $mold_slider_nav == 'hide' ? 'false' : 'true',
        $mold_slider_effect,
    );

    $post_id = get_the_ID();
    $gallery_ids = get_post_meta($post_id, '_tour_gallery', true);

    if (! $gallery_ids) {
        return '<div class="tour-gallery-placeholder">No tour gallery found.</div>';
    }

    $ids = array_filter(array_map('absint', explode(',', $gallery_ids)));
    if (empty($ids)) return '';

    $html = '<div class="mold-tour-gallery swiper align'. esc_attr( $attributes['align'] ?? '' ).'"' . $data_attrs .' style="height: ' . esc_attr($mold_slider_height) . ';">';
    $html .= '<div class="swiper-wrapper">';
    foreach ($ids as $id) {
        $src = wp_get_attachment_image_src($id, 'full');
        if ($src) {
            $html .= '<div class="swiper-slide"><img src="' . esc_url($src[0]) . '" alt="" /></div>';
        }
    }
    $html .= '</div>';
    if($mold_slider_nav != 'hide'){
        $html .= '<div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>';
    }
    if($mold_slider_indicator != 'hide'){
        $html .= '<div class="swiper-pagination"></div>';
    }
    $html .= '</div>';

    return $html;
}
