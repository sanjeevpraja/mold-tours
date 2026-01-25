<?php
// render.php
// Block rendering function
function callback_block_tour_meta($attributes, $content, $block)
{
    $html_tag = !empty($attributes['htmlTag']) ? $attributes['htmlTag'] : 'p';
    $fallback = !empty($attributes['fallback']) ? $attributes['fallback'] : '';
    $meta_key = !empty($attributes['metaKey']) ? $attributes['metaKey'] : 'tour_days';

    $currency   = get_option('tour_currency', 'USD');
    $thousand   = get_option('tour_thousand_separator', ',');
    $decimal    = get_option('tour_decimal_separator', '.');
    $decimals   = (int) get_option('tour_number_of_decimals', 2);

    // Get the current post ID
    $post_id = get_the_ID();

    if (!$post_id) {
        return '<div class="wp-mold-tour-meta-error">' . __('No post found', 'mold-tour') . '</div>';
    }

    // Check if we're dealing with a tour post type
    if (get_post_type($post_id) !== 'tour') {
        return '<div class="wp-mold-tour-meta-error">' . __('Not a tour post', 'mold-tour') . '</div>';
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
            if (is_numeric($display_content) && $display_content != '' && $display_content != 'N/A') {
                $display_content = $currency . ' ' . number_format($display_content, $decimals, $decimal, $thousand);
            } else {
                $display_content = $fallback;
            }
            break;
        case 'tour_original_price':
            if (is_numeric($display_content) && $display_content != '' && $display_content != 'N/A') {
                $display_content =  number_format($display_content, $decimals, $decimal, $thousand);
            } else {
                $display_content = $fallback;
            }
            break;


        case 'tour_date':
            // Format date if it's a valid date string
            if (!empty($display_content) && strtotime($display_content)) {
                $timestamp = strtotime($display_content);
                $display_content = date_i18n(get_option('date_format'), $timestamp);
            }
            break;

        case 'tour_grade_img':
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

    if ($meta_key == 'tour_grade_img') {
        $output = sprintf(
             '<img src="%1$s" alt="%2$s" class="%3$s">',
            esc_url($display_content),     // image URL
            esc_attr($meta_key),           // alt text
            'tour-grade-img'               // class
        );
    }
    elseif ($meta_key == 'tour_original_price') {
        $output = sprintf(
            '<%1$s class="wp-mold-tour-meta-info meta-original-price">%2$s</%1$s>',
            $html_tag,
            $display_content
        );
    }
    elseif ($meta_key == 'group_tour' || $meta_key == 'tailor_tour') {
        $tour_type = $meta_key === 'group_tour' ? 'group' : 'tailor';
        if($display_content) {
            $output = sprintf(
            '<%1$s class="tour-type tour-type--%2$s">%3$s</%1$s>',
            $html_tag,
            $tour_type,
            $display_content
        );
        }
        else{
            $output = '';
        }
    }
    else {
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
    //         __('Meta key:', 'mold-tour'),
    //         esc_html($meta_key)
    //     );
    // }

    return sprintf(
        '<div %s>%s</div>',
        $wrapper_attributes,
        $output
    );
}
