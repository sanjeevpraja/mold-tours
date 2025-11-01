<?php
// render.php
// Block rendering function

function callback_block_grade_image($attributes, $content) {
    if (!is_tax('grade')) {
        // Fallback for non-grade pages - just render the inner blocks
        return '<div class="mold-grade-featured-container mold-fallback">' . $content . '</div>';
    }

    $current_term = get_queried_object();
    if (!$current_term || is_wp_error($current_term)) {
        return '<div class="mold-grade-featured-container mold-fallback">' . $content . '</div>';
    }

    $featured_image_id = get_term_meta($current_term->term_id, 'grade-image-id', true);

    $attributes = wp_parse_args($attributes, array(
        'imageSize' => 'full',
        'minHeight' => 400,
    ));
    $border_radius = isset($attributes['style']['border']['radius']) ? $attributes['style']['border']['radius'] : '0px';

    $wrapper_classes = array(
        'mold-grade-featured-container'
    );


    // If no featured image, render without background but keep the inner blocks
    if (!$featured_image_id) {
        $wrapper_classes[] = 'mold-no-featured-image';
        ob_start();
        ?>
        <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
            <div class="mold-featured-content">
                <div class="mold-inner-content">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    $image_url = wp_get_attachment_image_url($featured_image_id, $attributes['imageSize']);
    ob_start();
    ?>
    <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
        <div
            class="mold-featured-background"
            style="background-image: url('<?php echo esc_url($image_url); ?>');
                min-height: <?php echo absint($attributes['minHeight']); ?>px;
                border-radius: <?php echo esc_attr($border_radius); ?>;"
                "
        >
            <div class="mold-inner-content">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}