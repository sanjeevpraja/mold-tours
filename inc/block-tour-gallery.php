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
            'speed'         => 1000,
            'autoDelay'     => 3000,
            'effect'        => 'slide',
            'navigation'    => true,
            'pagination'    => true,
            'slidesPerView' => 1,
            'sliderGap'     => 0,
            'sliderHeight'  => '600',
            'equalHeight'   => false,
            'align'         => '',
            'borderRadius'  => 0,
            'displayType'   => 'slider',
            'columns'       => 3,
            'columnsMobile' => 1,
            'bgColor'       => '#f0f0f0',
            'enableLightbox'=> true,
        ]
    );

    $mold_slider_speed       = absint( $attributes['speed'] );
    $mold_slider_autoDelay   = absint( $attributes['autoDelay'] );
    $mold_slider_effect      = esc_attr( $attributes['effect'] );
    $mold_slider_nav         = (bool) $attributes['navigation'];
    $mold_slider_pagination  = (bool) $attributes['pagination'];
    $mold_slider_height      = absint( $attributes['sliderHeight'] );
    $mold_slider_equalHeight = (bool) $attributes['equalHeight'];
    $mold_slider_slidesPerView = absint( $attributes['slidesPerView'] );
    $mold_slider_sliderGap   = absint( $attributes['sliderGap'] );
    $mold_border_radius      = absint( $attributes['borderRadius'] );
    $mold_display_type       = in_array( $attributes['displayType'], ['slider', 'grid'], true ) ? $attributes['displayType'] : 'slider';
    $mold_columns            = absint( $attributes['columns'] ) ?: 3;
    $mold_columns_mobile     = absint( $attributes['columnsMobile'] ) ?: 1;
    $mold_bg_color           = esc_attr( $attributes['bgColor'] );
    $mold_enable_lightbox    = (bool) $attributes['enableLightbox'];

    $post_id    = get_the_ID();
    $gallery_ids = get_post_meta($post_id, '_tour_gallery', true);

    if (! $gallery_ids) {
        return '<div class="tour-gallery-placeholder">No tour gallery found.</div>';
    }

    $ids = array_filter(array_map('absint', explode(',', $gallery_ids)));
    if (empty($ids)) return '';

    $lightbox_class = $mold_enable_lightbox ? ' has-lightbox' : '';
    $lightbox_attr  = $mold_enable_lightbox ? ' data-lightbox="true"' : '';

    // ── GRID MODE ─────────────────────────────────────────────────────────────
    if ( $mold_display_type === 'grid' ) {

        $css_vars = implode(';', [
            '--mold-border-radius:'   . $mold_border_radius . 'px',
            '--mold-columns:'         . $mold_columns,
            '--mold-columns-mobile:'  . $mold_columns_mobile,
            '--mold-gap:'             . $mold_slider_sliderGap . 'px',
            '--mold-skeleton-bg:'     . $mold_bg_color,
        ]);

        $align_class = esc_attr( $attributes['align'] ?? '' );

        $html  = '<div class="mold-tour-gallery-grid align' . $align_class . $lightbox_class . '"'
               . $lightbox_attr
               . ' style="' . esc_attr( $css_vars ) . '">';

        foreach ($ids as $id) {
            $src  = wp_get_attachment_image_src($id, 'large');
            $full = wp_get_attachment_image_src($id, 'full');
            $alt  = get_post_meta($id, '_wp_attachment_image_alt', true);
            if ($src) {
                $full_url = $full ? esc_url($full[0]) : esc_url($src[0]);
                $html .= '<div class="mold-gallery-grid-item">';
                $html .= '<img src="' . esc_url($src[0]) . '"'
                       . ' data-full="' . $full_url . '"'
                       . ' alt="' . esc_attr($alt) . '" loading="lazy" />';
                $html .= '</div>';
            }
        }

        $html .= '</div>';
        return $html;
    }

    // ── SLIDER MODE ───────────────────────────────────────────────────────────
    $css_vars = implode(';', [
        '--mold-border-radius:' . $mold_border_radius . 'px',
        '--mold-height:'        . $mold_slider_height . 'px',
        '--mold-skeleton-bg:'   . $mold_bg_color,
    ]);

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

    $align_class = esc_attr( $attributes['align'] ?? '' );

    $html  = '<div class="mold-tour-gallery swiper align' . $align_class . $lightbox_class . '"'
           . ' ' . $data_attrs
           . $lightbox_attr
           . ' style="' . esc_attr( $css_vars ) . '">';

    $html .= '<div class="swiper-wrapper">';
    foreach ($ids as $id) {
        $src  = wp_get_attachment_image_src($id, 'full');
        $alt  = get_post_meta($id, '_wp_attachment_image_alt', true);
        if ($src) {
            $html .= '<div class="swiper-slide">'
                   . '<img src="' . esc_url($src[0]) . '"'
                   . ' data-full="' . esc_url($src[0]) . '"'
                   . ' alt="' . esc_attr($alt) . '"'
                   . ' loading="lazy" /></div>';
        }
    }
    $html .= '</div>';

    if ($mold_slider_nav) {
        $html .= '<div class="swiper-button-prev"></div>';
        $html .= '<div class="swiper-button-next"></div>';
    }
    if ($mold_slider_pagination) {
        $html .= '<div class="swiper-pagination"></div>';
    }

    $html .= '</div>';

    return $html;
}
