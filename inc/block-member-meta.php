<?php
// render.php
// Block rendering function
function callback_block_member_meta($attributes, $content, $block)
{
    $html_tag = !empty($attributes['htmlTag']) ? $attributes['htmlTag'] : 'p';
    $fallback = !empty($attributes['fallback']) ? $attributes['fallback'] : '--';
    $meta_key = !empty($attributes['metaKey']) ? $attributes['metaKey'] : 'member_designation';

    // Get the current post ID
    $post_id = get_the_ID();

    if (!$post_id) {
        return '<div class="wp-mold-member-meta-error">' . __('No post found', 'mold-tour') . '</div>';
    }

    // Get the meta value - add underscore prefix to match your meta key pattern
    $meta_value = get_post_meta($post_id, '_' . $meta_key, true);

    // Use fallback if meta value is empty
    $display_content = !empty($meta_value) ? $meta_value : $fallback;

    // Sanitize the HTML tag
    $allowed_tags = array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span');
    $html_tag = in_array($html_tag, $allowed_tags) ? $html_tag : 'p';

    $block_props = array(
        'class' => 'wp-mold-member-meta',
    );

    $wrapper_attributes = get_block_wrapper_attributes($block_props);

    // Handle different meta key types
    switch ($meta_key) {
        case 'member_facebook':
            if (empty($meta_value)) return '';
            return sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" %s class="wp-mold-member-meta-icon">
                    <span class="dashicons dashicons-facebook-alt"></span>
                </a>',
                esc_url($meta_value),
                $wrapper_attributes
            );

        case 'member_twitter':
            if (empty($meta_value)) return '';
            return sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" %s class="wp-mold-member-meta-icon">
                    <span class="dashicons dashicons-twitter"></span>
                </a>',
                esc_url($meta_value),
                $wrapper_attributes
            );

        case 'member_linkedin':
            if (empty($meta_value)) return '';
            return sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" %s class="wp-mold-member-meta-icon">
                    <span class="dashicons dashicons-linkedin"></span>
                </a>',
                esc_url($meta_value),
                $wrapper_attributes
            );

        case 'member_website':
            if (empty($meta_value)) return '';
            return sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" %s class="wp-mold-member-meta-icon">
                    <span class="dashicons dashicons-admin-site"></span>
                </a>',
                esc_url($meta_value),
                $wrapper_attributes
            );

        default:
            // For regular text fields
            $output = sprintf(
                '<%1$s class="wp-mold-member-meta-info">%2$s</%1$s>',
                $html_tag,
                esc_html($display_content)
            );

            // Add debug info in admin or when WP_DEBUG is true
            if (is_admin() || (defined('WP_DEBUG') && WP_DEBUG)) {
                $output .= sprintf(
                    '<div><small>%s <code>_%s</code></small></div>',
                    __('Meta key:', 'mold-tour'),
                    esc_html($meta_key)
                );
            }

            return sprintf(
                '<div %s>%s</div>',
                $wrapper_attributes,
                $output
            );
    }
}
