<?php
/**
 * Custom Post Type: Tour
 */

// Register Custom Post Type
function create_tour_post_type() {
    $labels = array(
        'name'                  => _x('Tours', 'Post Type General Name', 'wp-mold'),
        'singular_name'         => _x('Tour', 'Post Type Singular Name', 'wp-mold'),
        'menu_name'             => __('Tours', 'wp-mold'),
        'name_admin_bar'        => __('Tour', 'wp-mold'),
        'archives'              => __('Tour Archives', 'wp-mold'),
        'attributes'            => __('Tour Attributes', 'wp-mold'),
        'parent_item_colon'     => __('Parent Tour:', 'wp-mold'),
        'all_items'             => __('All Tours', 'wp-mold'),
        'add_new_item'          => __('Add New Tour', 'wp-mold'),
        'add_new'               => __('Add New', 'wp-mold'),
        'new_item'              => __('New Tour', 'wp-mold'),
        'edit_item'             => __('Edit Tour', 'wp-mold'),
        'update_item'           => __('Update Tour', 'wp-mold'),
        'view_item'             => __('View Tour', 'wp-mold'),
        'view_items'            => __('View Tours', 'wp-mold'),
        'search_items'          => __('Search Tour', 'wp-mold'),
        'not_found'             => __('Not found', 'wp-mold'),
        'not_found_in_trash'    => __('Not found in Trash', 'wp-mold'),
        'featured_image'        => __('Featured Image', 'wp-mold'),
        'set_featured_image'    => __('Set featured image', 'wp-mold'),
        'remove_featured_image' => __('Remove featured image', 'wp-mold'),
        'use_featured_image'    => __('Use as featured image', 'wp-mold'),
        'insert_into_item'      => __('Insert into tour', 'wp-mold'), // Fixed: was 'member'
        'uploaded_to_this_item' => __('Uploaded to this tour', 'wp-mold'), // Fixed: was 'member'
        'items_list'            => __('Tours list', 'wp-mold'), // Fixed: was 'Members list'
        'items_list_navigation' => __('Tours list navigation', 'wp-mold'), // Fixed: was 'Members list navigation'
        'filter_items_list'     => __('Filter tours list', 'wp-mold'), // Fixed: was 'Filter members list'
    );

    $args = array(
        'label'                 => __('Tour', 'wp-mold'),
        'description'           => __('Tour information', 'wp-mold'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields'),
        'taxonomies'            => array('grade', 'location', 'category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-location',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('tour', $args);
}
add_action('init', 'create_tour_post_type', 0);


// Add default content when tour is created
function mold_set_default_tour_content($post_id, $post, $update) {
    // Only for new tour posts
    if ($update || $post->post_type !== 'tour') {
        return;
    }
    
    // Check if content is empty
    if (empty($post->post_content)) {
        $default_content = '<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:mold/tour-gallery /-->

<!-- wp:group {"metadata":{"name":"Price Row"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p class="">USD</p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-small-font-size"><!-- wp:mold/tour-meta {"fallback":"N/A","metaKey":"tour_price","fontSize":"x-large"} /-->

<!-- wp:mold/tour-meta {"fallback":"","metaKey":"tour_original_price","fontSize":"large"} /--></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p class="">Price per person</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:post-terms {"term":"category"} /-->

<!-- wp:post-terms {"term":"post_tag"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"backgroundColor":"grey-100"} -->
<hr class="wp-block-separator has-text-color has-grey-100-color has-alpha-channel-opacity has-grey-100-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"Overview"},"layout":{"type":"grid"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Overview Difficulty"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"speed","iconSize":40} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#000000;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">speed</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Difficulty</p>
<!-- /wp:paragraph -->

<!-- wp:post-terms {"term":"grade"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Overview Location"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"map","iconSize":40} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#000000;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">map</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Location</p>
<!-- /wp:paragraph -->

<!-- wp:post-terms {"term":"location"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Overview Accomodation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"accessibility","iconSize":40} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#000000;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">accessibility</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Accomodation</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Teahouse</h4>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"categories":["mold"],"patternName":"core/block/659","name":"Overview Transportation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"directions_bus","iconSize":40} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#000000;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">directions_bus</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Transport</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Bus</h4>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"categories":["mold"],"patternName":"core/block/659","name":"Overview Transportation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"person_pin","iconSize":40} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#000000;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">person_pin</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Tour Guide</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Barry Macdonald &amp; Local Guide</h4>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"categories":["mold"],"patternName":"core/block/659","name":"Overview Transportation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"groups","iconSize":40} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#000000;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">groups</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Group Size</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">6</h4>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"backgroundColor":"grey-100"} -->
<hr class="wp-block-separator has-text-color has-grey-100-color has-alpha-channel-opacity has-grey-100-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60)"/>
<!-- /wp:separator -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:mold/tab {"activeTab":1,"activeTabColor":"#508c14","tabGap":10,"borderRadius":10,"headerStyles":{"fontFamily":"","fontSize":"","textColor":"#333333","padding":{"inline":"30px","block":"16px"},"fontWeight":"bold"}} -->
<div class="wp-block-mold-tab tab-container tab-style-default" data-active-tab="1" style="--tab-header-fontFamily:inherit;--tab-header-fontSize:inherit;--tab-header-textColor:#333333;--tab-header-background-color:inherit;--tab-header-gap:10px;--tab-header-padding:16px 30px;--tab-header-border-radius:10px;--tab-header-border-width:1px;--tab-header-border-color:transparent;--tab-header-active-tab-text-color:#FFFFFF;--tab-header-active-tab-background-color:#508c14;--tab-header-active-tab-border-width:2px;--tab-content-fontFamily:inherit;--tab-content-fontSize:inherit;--tab-content-textColor:#333333;--tab-content-background-color:#ffffff;--tab-content-padding:10px 15px;--tab-content-border-radius:10px;--tab-content-border-width:1px;--tab-content-border-color:transparent"><nav class="tab-nav" role="tablist" aria-label="Tabs Navigation"></nav><div class="tab-content-wrapper"><!-- wp:mold/tab-item {"title":"Overview"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Overview" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:paragraph {"placeholder":"Add tab content here..."} -->
<p class="">Explore the thrill of mountain climbing with our guided tour designed for all skill levels. This adventure takes you through stunning landscapes, challenging yet rewarding climbs, and breathtaking views. Our expert guides ensure safety and share tips to help you enjoy every step. Whether youa beginner or looking to improve, this tour offers the perfect mix of excitement and learning. Experience the beauty of nature, test your limits, and create unforgettable memories. Suitable gear and instructions are provided. Join us for a safe, fun, and inspiring mountain climbing journey!</p>
<!-- /wp:paragraph --></div>
<!-- /wp:mold/tab-item -->

<!-- wp:mold/tab-item {"title":"Itinineary"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Itinineary" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:mold/step {"stepType":"icon","listItems":[{"text":"Label","content":"<p>Description</p>","imageUrl":"","indexColor":"#f2f2f2","iconBgColor":"#121212","icon":"add_card"},{"text":"Label","content":"<p>Description</p>","imageUrl":"","indexColor":"#ffffff","iconBgColor":"#121212","icon":"add_task"}]} -->
<div class="wp-block-mold-step" data-animation-interval="2500" style="--head-color:#333333;--body-color:#333333;--track-color:#dddddd;--active-color:;--step-size:60px;--track-width:2px;padding:10px"><ul class="wp-mold-step" data-animation="false"><li class="wp-mold-step-li active animation-false" data-attr="list-1"><div class="step-index list-1 active" style="background: #121212; color: #f2f2f2"><span class="material-symbols-outlined">add_card</span></div><div class="text-area"><div><h3 class="head-text">Label</h3><div class="body-text"><p>Description</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-2"><div class="step-index list-2 " style="background: #121212; color: #ffffff"><span class="material-symbols-outlined">add_task</span></div><div class="text-area"><div><h3 class="head-text">Label</h3><div class="body-text"><p>Description</p></div></div></div></li></ul></div>
<!-- /wp:mold/step --></div>
<!-- /wp:mold/tab-item --></div></div>
<!-- /wp:mold/tab --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:shortcode -->
[contact-form-7 id="a289753" title="Untitled"]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->';

        // Update the post
        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $default_content
        ));
    }
}
add_action('wp_insert_post', 'mold_set_default_tour_content', 10, 3);



// Enqueue admin script
function tour_admin_scripts($hook) {
    if ($hook !== 'edit.php' || get_current_screen()->post_type !== 'tour') {
        return;
    }

    wp_enqueue_script(
        'tour-admin-featured',
        MOLD_TOUR_BASE_URL . '/js/tour-admin-featured.js', array('jquery'), '1.0', true);

    wp_localize_script('tour-admin-featured', 'tourFeatured', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('tour_featured_nonce'),
    ));
}
add_action('admin_enqueue_scripts', 'tour_admin_scripts');



// AJAX handler for toggling featured (using tag instead of meta)
function tour_toggle_featured() {
    check_ajax_referer('tour_featured_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    $tag_name = 'featured';

    // Get current tags for this post
    $tags = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'names'));

    if (in_array($tag_name, $tags, true)) {
        // Remove the "featured" tag
        wp_remove_object_terms($post_id, $tag_name, 'post_tag');
        $new_status = 0;
    } else {
        // Add the "featured" tag (create if it doesn't exist)
        wp_add_object_terms($post_id, $tag_name, 'post_tag');
        $new_status = 1;
    }

    wp_send_json_success(array(
        'new_status' => $new_status,
        'icon' => $new_status ? 'dashicons-star-filled' : 'dashicons-star-empty',
    ));
}
add_action('wp_ajax_tour_toggle_featured', 'tour_toggle_featured');




?>