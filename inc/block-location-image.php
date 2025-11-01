<?php
// render.php
// Block rendering function

function callback_block_location_image($attributes, $content)
{
    if (!is_tax('location')) {
        // Fallback for non-location pages - just render the inner blocks
        return '<div class="mold-location-featured-container mold-fallback">' . $content . '</div>';
    }

    $current_term = get_queried_object();
    if (!$current_term || is_wp_error($current_term)) {
        return '<div class="mold-location-featured-container mold-fallback">' . $content . '</div>';
    }

    $attributes = wp_parse_args($attributes, array(
        'imageSize'  => 'full',
        'minHeight'  => 400,
    ));
    $border_radius = isset($attributes['style']['border']['radius']) ? $attributes['style']['border']['radius'] : '0px';

    $featured_image_id = get_term_meta($current_term->term_id, 'location-image-id', true);
    // If no featured image, render without background but keep inner blocks and matched page content
    if (!$featured_image_id) {
        $wrapper_classes[] = 'mold-no-featured-image';
        // Render with featured image background
        $background_style = "";
    } else {
        $image_url = wp_get_attachment_image_url($featured_image_id, $attributes['imageSize']);
        $background_style = "background-image: url('" . esc_url($image_url) . "');";
    }

    $map_image_id = get_term_meta($current_term->term_id, 'map-image-id', true);
    if ($map_image_id) {
        $map_image_url = wp_get_attachment_image_url($map_image_id, $attributes['imageSize']);
    }



    $wrapper_classes = array('mold-location-featured-container');

    // Try to find a Page with the same slug as the taxonomy term
    $matched_page = get_page_by_path($current_term->slug, OBJECT, 'page');
    //echo $matched_page->post_content; die;
    $page_content = '';
    if ($matched_page) {
        $page_content = $matched_page->post_content;
    }


    ob_start();
?>
    <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
        <div class="mold-featured-background"
            style="
				<?php echo $background_style; ?>
				min-height: <?php echo absint($attributes['minHeight']); ?>px;
				border-radius: <?php echo esc_attr($border_radius); ?>;
			">
            <div class="mold-inner-content">
                <?php
                echo $content;
                ?>
            </div>
            <div class="map-image">
                <img src="<?php echo esc_url($map_image_url); ?>" alt="<?php echo esc_attr($current_term->name); ?>" />
            </div>
        </div>
    </div>
    <?php
    if ($page_content) {
    ?>
        <div class="page-content-match">
            <?php echo $page_content; ?>
        </div>
    <?php
    }
    ?>
<?php
    return ob_get_clean();
}
