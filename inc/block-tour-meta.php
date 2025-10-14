<?php
// render.php
// Block rendering function
function callback_block_tour_meta($attributes, $content, $block)
{
    $html_tag = !empty($attributes['htmlTag']) ? $attributes['htmlTag'] : 'p';
    $fallback = !empty($attributes['fallback']) ? $attributes['fallback'] : '--';
    $meta_key = !empty($attributes['metaKey']) ? $attributes['metaKey'] : 'tour_days';

    // Get the current post ID
    $post_id = get_the_ID();

    if (!$post_id) {
        return '<div class="wp-mold-tour-meta-error">' . __('No post found', 'wp-mold') . '</div>';
    }

    // Check if we're dealing with a tour post type
    if (get_post_type($post_id) !== 'tour') {
        return '<div class="wp-mold-tour-meta-error">' . __('Not a tour post', 'wp-mold') . '</div>';
    }

    // Get the meta value - use underscore prefix to match your meta key pattern
    $meta_value = get_post_meta($post_id, '_' . $meta_key, true);
    $terms = get_the_terms($post_id, 'grade');

    // Use fallback if meta value is empty
    $display_content = !empty($meta_value) ? $meta_value : $fallback;

    // Sanitize the HTML tag
    $allowed_tags = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span');
    $html_tag = in_array($html_tag, $allowed_tags) ? $html_tag : 'p';

    $block_props = array(
        'class' => 'wp-mold-tour-meta',
    );

    $wrapper_attributes = get_block_wrapper_attributes($block_props);

    // Format specific meta values
    switch ($meta_key) {
        case 'tour_price':
            // Format price with currency symbol if it's a numeric value
            if (is_numeric($display_content)) {
                $display_content = '$' . number_format($display_content, 2);
            }
            break;

        case 'tour_days':
            // Add "Days" suffix if not already present
            if (is_numeric($display_content) && !str_contains($display_content, __('Day', 'wp-mold'))) {
                $display_content = $display_content . ' ' . _n('Day', 'Days', $display_content, 'wp-mold');
            }
            break;

        case 'tour_nights':
            // Add "Nights" suffix if not already present
            if (is_numeric($display_content) && !str_contains($display_content, __('Night', 'wp-mold'))) {
                $display_content = $display_content . ' ' . _n('Night', 'Nights', $display_content, 'wp-mold');
            }
            break;

        case 'tour_date':
            // Format date if it's a valid date string
            if (!empty($display_content) && strtotime($display_content)) {
                $timestamp = strtotime($display_content);
                $display_content = date_i18n(get_option('date_format'), $timestamp);
            }
            break;

        case 'tour_grade_icon':
            // Format date if it's a valid date string
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    $icon_id  = get_term_meta($term->term_id, 'grade-icon-id', true);
                    //$icon_url = wp_get_attachment_image_url($icon_id, 'thumbnail');
                    $display_content = $icon_id;
                }
            }
            break;
    }

    if ($meta_key == 'tour_grade_icon') {
        $output = sprintf(
        '<span class="%1$s"></span>',
        esc_attr($display_content)
    );
    }
    else{
// For all tour meta fields (they're all text fields)
    $output = sprintf(
        '<%1$s class="wp-mold-tour-meta-info">%2$s</%1$s>',
        $html_tag,
        esc_html($display_content)
    );
    }

    // Add debug info in admin or when WP_DEBUG is true
    // if (is_admin() || (defined('WP_DEBUG') && WP_DEBUG)) {
    //     $output .= sprintf(
    //         '<div><small>%s <code>_%s</code></small></div>',
    //         __('Meta key:', 'wp-mold'),
    //         esc_html($meta_key)
    //     );
    // }

    return sprintf(
        '<div %s>%s</div>',
        $wrapper_attributes,
        $output
    );
}
