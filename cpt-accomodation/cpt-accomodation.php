<?php
/**
 * Custom Post Type: Accomodation
 * Add this code to your theme's functions.php file or create a plugin
 */
 

// Register Custom Post Type
function create_accomodation_post_type() {
    $labels = array(
        'name'                  => _x('Accomodations', 'Post Type General Name', 'mold-tour'),
        'singular_name'         => _x('Accomodation', 'Post Type Singular Name', 'mold-tour'),
        'menu_name'             => __('Accomodations', 'mold-tour'),
        'name_admin_bar'        => __('Accomodation', 'mold-tour'),
        'archives'              => __('Accomodation Archives', 'mold-tour'),
        'attributes'            => __('Accomodation Attributes', 'mold-tour'),
        'parent_item_colon'     => __('Parent Accomodation:', 'mold-tour'),
        'all_items'             => __('All Accomodations', 'mold-tour'),
        'add_new_item'          => __('Add New Accomodation', 'mold-tour'),
        'add_new'               => __('Add New', 'mold-tour'),
        'new_item'              => __('New Accomodation', 'mold-tour'),
        'edit_item'             => __('Edit Accomodation', 'mold-tour'),
        'update_item'           => __('Update Accomodation', 'mold-tour'),
        'view_item'             => __('View Accomodation', 'mold-tour'),
        'view_items'            => __('View Accomodations', 'mold-tour'),
        'search_items'          => __('Search Accomodation', 'mold-tour'),
        'not_found'             => __('Not found', 'mold-tour'),
        'not_found_in_trash'    => __('Not found in Trash', 'mold-tour'),
        'featured_image'        => __('Featured Image', 'mold-tour'),
        'set_featured_image'    => __('Set featured image', 'mold-tour'),
        'remove_featured_image' => __('Remove featured image', 'mold-tour'),
        'use_featured_image'    => __('Use as featured image', 'mold-tour'),
        'insert_into_item'      => __('Insert into accomodation', 'mold-tour'),
        'uploaded_to_this_item' => __('Uploaded to this accomodation', 'mold-tour'),
        'items_list'            => __('Accomodations list', 'mold-tour'),
        'items_list_navigation' => __('Accomodations list navigation', 'mold-tour'),
        'filter_items_list'     => __('Filter accomodations list', 'mold-tour'),
    );

    $args = array(
        'label'                 => __('Accomodation', 'mold-tour'),
        'description'           => __('Accomodation information', 'mold-tour'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes'),
        'taxonomies'            =>  array('location', 'category', 'post_tag'),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-flag',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('accomodation', $args);
}
add_action('init', 'create_accomodation_post_type', 0);



// Flush rewrite rules on activation (add this to your plugin activation hook or run once)
function accomodation_flush_rewrite_rules() {
    create_accomodation_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'accomodation_flush_rewrite_rules');



/****************/
// Add default content when Accomodation is created
function mold_set_default_accomodation_content($post_id, $post, $update) {
    // Only for new tour posts
    if ($update || $post->post_type !== 'accomodation') {
        return;
    }

    // Check if content is empty
    if (empty($post->post_content)) {
        $default_content = '<!-- wp:group {"metadata":{"name":"Main Wrap"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"background","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-color has-background-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:post-title {"textAlign":"center"} /-->

<!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"metadata":{"name":"Overview"},"layout":{"type":"grid"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Overview Location"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
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

<!-- wp:group {"metadata":{"categories":["mold"],"patternName":"core/block/659","name":"Overview Transportation"},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"star_rate","iconSize":40,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:40px;--line-height:40px;--icon-width:40px;--padding:10px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">star_rate</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} -->
<p class="has-grey-500-color has-text-color has-link-color">Rating</p>
<!-- /wp:paragraph -->

<!-- wp:group {"metadata":{"name":"Rating"},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:mold/dashicon {"dashicon":"dashicons-star-filled","iconSize":20,"padding":2,"iconColor":"#f7ab55"} -->
<div class="wp-block-mold-dashicon" style="--icon-size:20px;--line-height:20px;--icon-color:#f7ab55;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2;--padding:2px;--border-radius:0px"><span class="dashicons dashicons-star-filled"></span></div>
<!-- /wp:mold/dashicon -->

<!-- wp:mold/dashicon {"dashicon":"dashicons-star-filled","iconSize":20,"padding":2,"iconColor":"#f7ab55"} -->
<div class="wp-block-mold-dashicon" style="--icon-size:20px;--line-height:20px;--icon-color:#f7ab55;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2;--padding:2px;--border-radius:0px"><span class="dashicons dashicons-star-filled"></span></div>
<!-- /wp:mold/dashicon -->

<!-- wp:mold/dashicon {"dashicon":"dashicons-star-filled","iconSize":20,"padding":2,"iconColor":"#f7ab55"} -->
<div class="wp-block-mold-dashicon" style="--icon-size:20px;--line-height:20px;--icon-color:#f7ab55;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2;--padding:2px;--border-radius:0px"><span class="dashicons dashicons-star-filled"></span></div>
<!-- /wp:mold/dashicon -->

<!-- wp:mold/dashicon {"dashicon":"dashicons-star-filled","iconSize":20,"padding":2,"iconColor":"#f7ab55"} -->
<div class="wp-block-mold-dashicon" style="--icon-size:20px;--line-height:20px;--icon-color:#f7ab55;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2;--padding:2px;--border-radius:0px"><span class="dashicons dashicons-star-filled"></span></div>
<!-- /wp:mold/dashicon -->

<!-- wp:mold/dashicon {"dashicon":"dashicons-star-filled","iconSize":20,"padding":2,"iconColor":"#f7ab55"} -->
<div class="wp-block-mold-dashicon" style="--icon-size:20px;--line-height:20px;--icon-color:#f7ab55;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2;--padding:2px;--border-radius:0px"><span class="dashicons dashicons-star-filled"></span></div>
<!-- /wp:mold/dashicon --></div>
<!-- /wp:group --></div>
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
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"content"},"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"background","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-color has-background-background-color has-text-color has-background has-link-color"><!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|80"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph -->
<p class="">Here is a short example paragraph like dummy text for an accommodation description including details on availability and features:

"Our comfortable accommodations are available year-round and feature spacious rooms with modern amenities such as complimentary Wi-Fi, flat-screen TVs, and air conditioning. Guests can enjoy cozy furnishings, 24-hour front desk service, and convenient access to local attractions. Whether you are traveling for business or leisure, our accommodations provide a relaxing and welcoming atmosphere for your stay. Book now to secure your preferred dates and experience exceptional comfort."</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:image {"id":279,"width":"731px","height":"auto","aspectRatio":"1.7777777777777777","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"10px"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="http://natouravoyage.moldthemes.com/wp-content/uploads/2025/10/470227970_999497558878985_3787976228678281517_n.jpg" alt="" class="wp-image-279" style="border-radius:10px;aspect-ratio:1.7777777777777777;object-fit:cover;width:731px;height:auto"/></figure>
<!-- /wp:image -->

<!-- wp:spacer {"height":"30px"} -->
<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:mold/list {"listIcon":"check_circle_outline","iconSize":30,"iconColor":"#aaf300","listItems":[{"text":"List Header","content":"Comfortable accommodation at the confluence of rivers chongwe and zambezi, just outside the lower zambezi n.p.","imageUrl":""},{"text":"List Header","content":"Just nine guest tents, each with an outdoor seating area, as well as two suites with plunge pool","imageUrl":""},{"text":"List Header","content":"Pleasant mess tent with lounge and dining areas, fire-pit, pool","imageUrl":""},{"text":"List Header","content":"Expertly guided game drives, night drives, walking safaris, canoeing safaris, boating safaris, tigerfishing, sleep-outs","imageUrl":""}]} -->
<div class="wp-mold-list-block" style="--padding:10px;--icon-size:30px;--icon-color:#aaf300;--text-header-color:#333333;--text-description-color:#666666;--list-gap:10px" class="wp-block-mold-list"><ul class="wp-mold-list"><li class="wp-mold-list-li active" data-attr="list-1"><span class="list-icon material-symbols-outlined">check_circle_outline</span><div class="list-wrap"><div class="list-wrap">Comfortable accommodation at the confluence of rivers chongwe and zambezi, just outside the lower zambezi n.p.</div></div></li><li class="wp-mold-list-li " data-attr="list-2"><span class="list-icon material-symbols-outlined">check_circle_outline</span><div class="list-wrap"><div class="list-wrap">Just nine guest tents, each with an outdoor seating area, as well as two suites with plunge pool</div></div></li><li class="wp-mold-list-li " data-attr="list-3"><span class="list-icon material-symbols-outlined">check_circle_outline</span><div class="list-wrap"><div class="list-wrap">Pleasant mess tent with lounge and dining areas, fire-pit, pool</div></div></li><li class="wp-mold-list-li " data-attr="list-4"><span class="list-icon material-symbols-outlined">check_circle_outline</span><div class="list-wrap"><div class="list-wrap">Expertly guided game drives, night drives, walking safaris, canoeing safaris, boating safaris, tigerfishing, sleep-outs</div></div></li></ul></div>
<!-- /wp:mold/list -->

<!-- wp:spacer {"height":"20px"} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:html -->
[contact-form-7 id="392f199" title="Booking Query"]
<!-- /wp:html --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:group -->';

        // Update the post
        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $default_content
        ));
    }
}
add_action('wp_insert_post', 'mold_set_default_accomodation_content', 10, 3);




?>