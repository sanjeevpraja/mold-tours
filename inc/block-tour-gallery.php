<?php
// render.php
// Block rendering function
function callback_block_tour_gallery($attributes, $content, $block)
{

    global $post;

    if ( ! $post ) {
        return '';
    }

    // Defaults (important for old content / safety)
    $attributes = wp_parse_args(
        $attributes,
        [
            'speed'        => 1000,
            'autoDelay'    => 3000,
            'effect'       => 'slide',
            'navigation'   => true,
            'pagination'   => true,
            'slidesPerView'=> 1,
            'sliderGap'    => 0,
            'sliderHeight' => '600',
            'equalHeight'  => false,
            'align'        => '',
            'borderRadius' => 0,
        ]
    );

    $mold_slider_speed      = absint( $attributes['speed'] );
    $mold_slider_autoDelay  = absint( $attributes['autoDelay'] );
    $mold_slider_effect     = esc_attr( $attributes['effect'] );
    $mold_slider_nav        = (bool) $attributes['navigation'];
    $mold_slider_pagination= (bool) $attributes['pagination'];
    $mold_slider_height    = esc_attr( $attributes['sliderHeight'] );
    $mold_slider_equalHeight = (bool) $attributes['equalHeight'];
    $mold_slider_slidesPerView = absint( $attributes['slidesPerView'] );
    $mold_slider_sliderGap = absint( $attributes['sliderGap'] );
    $mold_slider_borderRadius = esc_attr( $attributes['borderRadius'] );

    $defaults = [
        'speed'        => 1000,
        'pagination'   => $mold_slider_pagination ? true : false,
        'navigation'   => $mold_slider_nav ? true : false,
        'effect'       => $mold_slider_effect,
        'slidesPerView' => $mold_slider_slidesPerView,
        'sliderGap' => $mold_slider_sliderGap
    ];
    $attributes = wp_parse_args( $attributes, $defaults );

    // Now safe to render
    $data_attrs = sprintf(
        'data-attr-speed="%d"
         data-attr-auto-delay="%d"
         data-attr-pagination="%s"
         data-attr-navigation="%s"
         data-attr-effect="%s"
         data-attr-equal-height="%s"
         data-attr-slides-per-view="%d"
         data-attr-slider-gap="%d"
         data-attr-slider-height="%s"',
        $mold_slider_speed,
        $mold_slider_autoDelay,
        $mold_slider_pagination ? 'true' : 'false',
        $mold_slider_nav ? 'true' : 'false',
        $mold_slider_effect,
        $mold_slider_equalHeight ? 'true' : 'false',
        $mold_slider_slidesPerView,
        $mold_slider_sliderGap,
        $mold_slider_height
    );
    $style_attrs = [
        '--mold-border-radius' => esc_attr($mold_slider_borderRadius)."px",
        '--mold-height' => esc_attr($mold_slider_height)."px"
    ];

    $post_id = get_the_ID();
    $gallery_ids = get_post_meta($post_id, '_tour_gallery', true);

    if (! $gallery_ids) {
        return '<div class="tour-gallery-placeholder">No tour gallery found.</div>';
    }

    $ids = array_filter(array_map('absint', explode(',', $gallery_ids)));
    if (empty($ids)) return '';

    $html = '<div class="mold-tour-gallery swiper align'. esc_attr( $attributes['align'] ?? '' ).'"' . $data_attrs .' style="'. esc_attr(implode(';', array_map(function($key, $value){ return $key.':'.$value; }, array_keys($style_attrs), $style_attrs))) .'">';
    $html .= '<div class="swiper-wrapper">';
    foreach ($ids as $id) {
        $src = wp_get_attachment_image_src($id, 'full');
        if ($src) {
            $html .= '<div class="swiper-slide"><img src="' . esc_url($src[0]) . '" alt="" /></div>';
        }
    }
    $html .= '</div>';
    if($mold_slider_nav){
        $html .= '<div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>';
    }
    if($mold_slider_pagination){
        $html .= '<div class="swiper-pagination"></div>';
    }
    $html .= '</div>';

    return $html;
}
