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
        $default_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}},"color":{"background":"#212121"}},"textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-color has-text-color has-background has-link-color" style="background-color:#212121;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:post-title {"textAlign":"center"} /-->

<!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:mold/tour-gallery /-->

<!-- wp:group {"metadata":{"name":"Price Row"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"small","layout":{"type":"flex","flexWrap":"nowrap"}} -->
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

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}},"color":{"background":"#f2f2f20f"}}} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60);background-color:#f2f2f20f;color:#f2f2f20f"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"Overview"},"layout":{"type":"grid"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Overview Difficulty"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"speed","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">speed</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Difficulty</p>
<!-- /wp:paragraph -->

<!-- wp:post-terms {"term":"grade"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Overview Location"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"map","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">map</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Location</p>
<!-- /wp:paragraph -->

<!-- wp:post-terms {"term":"location"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Overview Accomodation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"accessibility","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">accessibility</span></div>
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
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"directions_bus","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">directions_bus</span></div>
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
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"person_pin","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">person_pin</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Tour Guide</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4 class="wp-block-heading">Tour guide</h4>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"categories":["mold"],"patternName":"core/block/659","name":"Overview Transportation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"groups","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">groups</span></div>
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

<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}},"color":{"background":"#f2f2f20f"}}} -->
<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--60);background-color:#f2f2f20f;color:#f2f2f20f"/>
<!-- /wp:separator -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:mold/tab {"activeTab":1,"activeTabColor":"#eb8122","tabGap":10,"borderRadius":10,"headerStyles":{"fontFamily":"","fontSize":"","textColor":"#ffffff","padding":{"inline":"30px","block":"16px"},"fontWeight":"bold","backgroundColor":"#12121200"},"contentStyles":{"fontFamily":"","fontSize":"","textColor":"#ffffff","backgroundColor":"#12121200","padding":{"inline":"15px","block":"10px"}}} -->
<div class="wp-block-mold-tab tab-container tab-style-default" data-active-tab="1" style="--tab-header-fontFamily:inherit;--tab-header-fontSize:inherit;--tab-header-textColor:#ffffff;--tab-header-background-color:#12121200;--tab-header-gap:10px;--tab-header-padding:16px 30px;--tab-header-border-radius:10px;--tab-header-border-width:1px;--tab-header-border-color:transparent;--tab-header-active-tab-text-color:#000000;--tab-header-active-tab-background-color:#eb8122;--tab-header-active-tab-border-width:2px;--tab-content-fontFamily:inherit;--tab-content-fontSize:inherit;--tab-content-textColor:#ffffff;--tab-content-background-color:#12121200;--tab-content-padding:10px 15px;--tab-content-border-radius:10px;--tab-content-border-width:1px;--tab-content-border-color:transparent"><nav class="tab-nav" role="tablist" aria-label="Tabs Navigation"></nav><div class="tab-content-wrapper"><!-- wp:mold/tab-item {"title":"Overview"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Overview" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:paragraph {"placeholder":"Add tab content here..."} -->
<p class="">Add Overview Decription</p>
<!-- /wp:paragraph --></div>
<!-- /wp:mold/tab-item -->

<!-- wp:mold/tab-item {"title":"Itinineary"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Itinineary" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:mold/step {"stepType":"icon","stepColor":"#121212","stepBgColor":"#eb8122","headColor":"#eb8122","bodyColor":"#ffffff","trackColor":"#555555","listItems":[{"text":"Day 1","content":"<p>Description</p>","imageUrl":"","icon":"airplanemode_active","indexColor":"#ffffff","iconBgColor":"#121212"},{"text":"Day 2","content":"<p>Description</p>","imageUrl":"","indexColor":"#ffffff","iconBgColor":"#121212","icon":"directions_bus"}]} -->
<div class="wp-block-mold-step" data-animation-interval="2500" style="--head-color:#eb8122;--body-color:#ffffff;--track-color:#555555;--active-color:;--step-size:60px;--track-width:2px;padding:10px"><ul class="wp-mold-step" data-animation="false"><li class="wp-mold-step-li active animation-false" data-attr="list-1"><div class="step-index list-1 active" style="background: #121212; color: #ffffff"><span class="material-symbols-outlined">airplanemode_active</span></div><div class="text-area"><div><h3 class="head-text">Day 1</h3><div class="body-text"><p>Description</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-2"><div class="step-index list-2 " style="background: #121212; color: #ffffff"><span class="material-symbols-outlined">directions_bus</span></div><div class="text-area"><div><h3 class="head-text">Day 2</h3><div class="body-text"><p>Description</p></div></div></div></li></ul></div>
<!-- /wp:mold/step --></div>
<!-- /wp:mold/tab-item --></div></div>
<!-- /wp:mold/tab --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:group {"metadata":{"name":"Booking Query Form"},"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-100"}}}},"textColor":"grey-100","fontSize":"extra-small","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-grey-100-color has-text-color has-link-color has-extra-small-font-size"><!-- wp:shortcode -->
[contact-form-7 id="57abdff" title="Booking Query"]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
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