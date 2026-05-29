<?php
/**
 * Custom Post Type: Region
 * Add this code to your theme's functions.php file or create a plugin
 */
 

// Register Custom Post Type
function create_region_post_type() {
    $labels = array(
        'name'                  => _x('Regions', 'Post Type General Name', 'mold-tour'),
        'singular_name'         => _x('Region', 'Post Type Singular Name', 'mold-tour'),
        'menu_name'             => __('Regions', 'mold-tour'),
        'name_admin_bar'        => __('Region', 'mold-tour'),
        'archives'              => __('Region Archives', 'mold-tour'),
        'attributes'            => __('Region Attributes', 'mold-tour'),
        'parent_item_colon'     => __('Parent Region:', 'mold-tour'),
        'all_items'             => __('All Regions', 'mold-tour'),
        'add_new_item'          => __('Add New Region', 'mold-tour'),
        'add_new'               => __('Add New', 'mold-tour'),
        'new_item'              => __('New Region', 'mold-tour'),
        'edit_item'             => __('Edit Region', 'mold-tour'),
        'update_item'           => __('Update Region', 'mold-tour'),
        'view_item'             => __('View Region', 'mold-tour'),
        'view_items'            => __('View Regions', 'mold-tour'),
        'search_items'          => __('Search Region', 'mold-tour'),
        'not_found'             => __('Not found', 'mold-tour'),
        'not_found_in_trash'    => __('Not found in Trash', 'mold-tour'),
        'featured_image'        => __('Featured Image', 'mold-tour'),
        'set_featured_image'    => __('Set featured image', 'mold-tour'),
        'remove_featured_image' => __('Remove featured image', 'mold-tour'),
        'use_featured_image'    => __('Use as featured image', 'mold-tour'),
        'insert_into_item'      => __('Insert into region', 'mold-tour'),
        'uploaded_to_this_item' => __('Uploaded to this region', 'mold-tour'),
        'items_list'            => __('Regions list', 'mold-tour'),
        'items_list_navigation' => __('Regions list navigation', 'mold-tour'),
        'filter_items_list'     => __('Filter regions list', 'mold-tour'),
    );

    $args = array(
        'label'                 => __('Region', 'mold-tour'),
        'description'           => __('Region information', 'mold-tour'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes'),
        'taxonomies'            =>  array('location'),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-admin-site',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type('region', $args);
}
add_action('init', 'create_region_post_type', 0);




/****************/
// Add default content when Region is created
function mold_set_default_region_content($post_id, $post, $update) {
    // Only for new region posts
    if ($update || $post->post_type !== 'region') {
        return;
    }

    // Check if content is empty
    if (empty($post->post_content)) {
        $default_content = '<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"background","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:mold/tab {"activeTab":1,"tabStyle":"pill","activeTabColor":"#EB8122","tabGap":14,"borderSize":0,"borderRadius":8,"contentStyles":{"fontFamily":"","fontSize":"","textColor":"#FFFFFF","backgroundColor":"#12121200","padding":{"inline":"0px","block":"10px"}}} -->
<div class="wp-block-mold-tab tab-container tab-style-pill" data-active-tab="1" style="--tab-header-fontFamily:inherit;--tab-header-fontSize:inherit;--tab-header-textColor:#333333;--tab-header-background-color:#e3e7ec;--tab-header-gap:14px;--tab-header-padding:10px 15px;--tab-header-border-radius:8px;--tab-header-border-width:0px;--tab-header-border-color:transparent;--tab-header-active-tab-text-color:#000000;--tab-header-active-tab-background-color:#EB8122;--tab-header-active-tab-border-width:2px;--tab-content-fontFamily:inherit;--tab-content-fontSize:inherit;--tab-content-textColor:#FFFFFF;--tab-content-background-color:#12121200;--tab-content-padding:10px 0px;--tab-content-border-radius:8px;--tab-content-border-width:0px;--tab-content-border-color:transparent"><nav class="tab-nav" role="tablist" aria-label="Tabs Navigation"></nav><div class="tab-content-wrapper"><!-- wp:mold/tab-item {"title":"Information"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Information" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:paragraph {"placeholder":"Add tab content here..."} -->
<p class=""></p>
<!-- /wp:paragraph -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p class="">This is information section</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":"50px"} -->
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:mold/accordion {"items":[{"title":"Visa","content":"","isOpen":true},{"title":"vaccinations and Health Information for India","content":"","isOpen":true},{"title":"New Digital Arrival Card","content":"","isOpen":true},{"title":"UK Government Travel Advice","content":"","isOpen":true}],"allowMultiple":true,"iconColor":"#EB8122","borderColor":"#54545452","headerStyles":{"fontFamily":"","fontSize":"1rem","textColor":"#FFFFFF","backgroundColor":"#000000","padding":{"inline":"19px","block":"20px"}},"contentStyles":{"fontFamily":"","fontSize":"","textColor":"#FFFFFF","backgroundColor":"#000000","padding":{"inline":"15px","block":"10px"}}} -->
<div class="wp-block-mold-accordion"><div class="accordion-block" data-allow-multiple="true" data-open-first="true" data-icon-position="right" data-icon-type="plus-minus" style="--item-gap:10px;--icon-color:#EB8122;--icon-size:30px;--border-size:1px;--border-color:#54545452;--border-radius:4px;--header-font-family:inherit;--header-font-size:1rem;--header-text-color:#FFFFFF;--header-background-color:#000000;--header-padding-block:20px;--header-padding-inline:19px;--content-font-family:inherit;--content-font-size:inherit;--content-text-color:#FFFFFF;--content-background-color:#000000;--content-padding-block:10px;--content-padding-inline:15px"><div class="accordion-item is-plus-minus is-open"><div class="accordion-header" data-accordion-trigger="0"><h3>Visa</h3><span class="accordion-icon is-right is-open"></span></div><div class="accordion-content" data-accordion-content="0"><p></p></div></div><div class="accordion-item is-plus-minus is-open"><div class="accordion-header" data-accordion-trigger="1"><h3>vaccinations and Health Information for India</h3><span class="accordion-icon is-right is-open"></span></div><div class="accordion-content" data-accordion-content="1"><p></p></div></div><div class="accordion-item is-plus-minus is-open"><div class="accordion-header" data-accordion-trigger="2"><h3>New Digital Arrival Card</h3><span class="accordion-icon is-right is-open"></span></div><div class="accordion-content" data-accordion-content="2"><p></p></div></div><div class="accordion-item is-plus-minus is-open"><div class="accordion-header" data-accordion-trigger="3"><h3>UK Government Travel Advice</h3><span class="accordion-icon is-right is-open"></span></div><div class="accordion-content" data-accordion-content="3"><p></p></div></div></div></div>
<!-- /wp:mold/accordion -->

<!-- wp:spacer -->
<div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color"></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class=""></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"placeholder":"Add tab content here..."} -->
<p class=""></p>
<!-- /wp:paragraph --></div>
<!-- /wp:mold/tab-item -->

<!-- wp:mold/tab-item {"title":"Tour"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Tour" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:query {"queryId":0,"query":{"postType":"tour","perPage":10,"order":"desc","orderBy":"date","sticky":"","parents":[],"format":[],"inherit":false,"taxQuery":{"location":[12]}}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"10px","color":"#3f3f3f","width":"1px"},"dimensions":{"minHeight":"100%"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"black","textColor":"white"} -->
<div class="wp-block-group has-border-color has-white-color has-black-background-color has-text-color has-background has-link-color" style="border-color:#3f3f3f;border-width:1px;border-radius:10px;min-height:100%;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:mold/div {"widthType":"manual","heightType":"manual","height":"200","heightUnit":"px","position":"relative","className":"wp-block-mold-div mold-div-block has-grey-100-background-color has-background","backgroundColor":"grey-100"} -->
<div><div class="wp-block-mold-div mold-div-block  has-grey-100-background-color has-background" style="position:relative;width:100%;height:200px"><!-- wp:post-featured-image {"isLink":true,"width":"100%","height":"200px"} /--></div></div>
<!-- /wp:mold/div -->

<!-- wp:post-title {"level":5,"isLink":true,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"fontSize":"large"} /-->

<!-- wp:group {"style":{"dimensions":{"minHeight":"30px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="min-height:30px"><!-- wp:post-excerpt {"moreText":"...","showMoreOnNewLine":false,"excerptLength":21} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--30)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"speed","iconSize":30,"padding":0,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:30px;--line-height:30px;--icon-width:30px;--padding:0px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">speed</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:post-terms {"term":"grade"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"location_on","iconSize":30,"padding":0,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:30px;--line-height:30px;--icon-width:30px;--padding:0px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">location_on</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:post-terms {"term":"location"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:mold/tour-meta {"fallback":"Not Available","metaKey":"tour_price","fontSize":"medium","style":{"typography":{"fontWeight":"700","fontStyle":"normal"}}} /-->

<!-- wp:spacer {"height":"20px"} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Book Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p class="">Not available</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query --></div>
<!-- /wp:mold/tab-item -->

<!-- wp:mold/tab-item {"title":"Accomodation"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Accomodation" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:query {"queryId":0,"query":{"postType":"accomodation","perPage":10,"order":"desc","orderBy":"date","sticky":"","parents":[],"format":[],"inherit":false,"taxQuery":{"location":[12]}}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}},"border":{"radius":"10px","color":"#3f3f3f","width":"1px"},"dimensions":{"minHeight":"100%"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"backgroundColor":"black","textColor":"white"} -->
<div class="wp-block-group has-border-color has-white-color has-black-background-color has-text-color has-background has-link-color" style="border-color:#3f3f3f;border-width:1px;border-radius:10px;min-height:100%;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:mold/div {"widthType":"manual","heightType":"manual","height":"200","heightUnit":"px","position":"relative","className":"wp-block-mold-div mold-div-block has-grey-100-background-color has-background","backgroundColor":"grey-100"} -->
<div><div class="wp-block-mold-div mold-div-block  has-grey-100-background-color has-background" style="position:relative;width:100%;height:200px"><!-- wp:post-featured-image {"isLink":true,"width":"100%","height":"200px"} /--></div></div>
<!-- /wp:mold/div -->

<!-- wp:post-title {"level":5,"isLink":true,"style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"fontSize":"large"} /-->

<!-- wp:group {"style":{"dimensions":{"minHeight":"30px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="min-height:30px"><!-- wp:post-excerpt {"moreText":"...","showMoreOnNewLine":false,"excerptLength":21} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--30)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"speed","iconSize":30,"padding":0,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:30px;--line-height:30px;--icon-width:30px;--padding:0px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">speed</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:post-terms {"term":"grade"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:mold/materialicon {"materialicon":"location_on","iconSize":30,"padding":0,"iconColor":"#eb8122"} -->
<div class="wp-block-mold-materialicon" style="--icon-size:30px;--line-height:30px;--icon-width:30px;--padding:0px;--border-radius:0px;--icon-color:#eb8122;--bg-color:transparent;--hover-color:#000000;--hover-bg-color:transparent;--translate-value-x:0px;--translate-value-y:0px;--rotate-value:0deg;--scale-value:1.2"><span class="material-icon material-symbols-outlined">location_on</span></div>
<!-- /wp:mold/materialicon -->

<!-- wp:post-terms {"term":"location"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:mold/tour-meta {"fallback":"Not Available","metaKey":"tour_price","fontSize":"medium","style":{"typography":{"fontWeight":"700","fontStyle":"normal"}}} /-->

<!-- wp:spacer {"height":"20px"} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Book Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
<p class="">Not available</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->
<!-- /wp:post-template -->

<!-- wp:paragraph -->
<p class=""></p>
<!-- /wp:paragraph -->

<!-- wp:query-pagination -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query --></div>
<!-- /wp:mold/tab-item --></div></div>
<!-- /wp:mold/tab --></div>
<!-- /wp:group -->';

        // Update the post
        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $default_content
        ));
    }
}
add_action('wp_insert_post', 'mold_set_default_region_content', 10, 3);




?>