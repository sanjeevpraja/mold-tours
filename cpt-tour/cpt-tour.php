<?php
/**
 * Custom Post Type: Tour
 */

// Register Custom Post Type
function create_tour_post_type() {
    $labels = array(
        'name'                  => _x('Tours', 'Post Type General Name', 'mold-tour'),
        'singular_name'         => _x('Tour', 'Post Type Singular Name', 'mold-tour'),
        'menu_name'             => __('Tours', 'mold-tour'),
        'name_admin_bar'        => __('Tour', 'mold-tour'),
        'archives'              => __('Tour Archives', 'mold-tour'),
        'attributes'            => __('Tour Attributes', 'mold-tour'),
        'parent_item_colon'     => __('Parent Tour:', 'mold-tour'),
        'all_items'             => __('All Tours', 'mold-tour'),
        'add_new_item'          => __('Add New Tour', 'mold-tour'),
        'add_new'               => __('Add New', 'mold-tour'),
        'new_item'              => __('New Tour', 'mold-tour'),
        'edit_item'             => __('Edit Tour', 'mold-tour'),
        'update_item'           => __('Update Tour', 'mold-tour'),
        'view_item'             => __('View Tour', 'mold-tour'),
        'view_items'            => __('View Tours', 'mold-tour'),
        'search_items'          => __('Search Tour', 'mold-tour'),
        'not_found'             => __('Not found', 'mold-tour'),
        'not_found_in_trash'    => __('Not found in Trash', 'mold-tour'),
        'featured_image'        => __('Featured Image', 'mold-tour'),
        'set_featured_image'    => __('Set featured image', 'mold-tour'),
        'remove_featured_image' => __('Remove featured image', 'mold-tour'),
        'use_featured_image'    => __('Use as featured image', 'mold-tour'),
        'insert_into_item'      => __('Insert into tour', 'mold-tour'), // Fixed: was 'member'
        'uploaded_to_this_item' => __('Uploaded to this tour', 'mold-tour'), // Fixed: was 'member'
        'items_list'            => __('Tours list', 'mold-tour'), // Fixed: was 'Members list'
        'items_list_navigation' => __('Tours list navigation', 'mold-tour'), // Fixed: was 'Members list navigation'
        'filter_items_list'     => __('Filter tours list', 'mold-tour'), // Fixed: was 'Filter members list'
    );

    $args = array(
        'label'                 => __('Tour', 'mold-tour'),
        'description'           => __('Tour information', 'mold-tour'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes'),
        'taxonomies'            => array('grade', 'location', 'category', 'post_tag'),
        'hierarchical'          => true,
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
function mold_set_default_tour_content($content, $post) {
    if ($post->post_type !== 'tour') {
        return $content;
    }
    
    // Check if content is empty
    if (empty($content)) {
        $default_content = '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:post-title {"textAlign":"center"} /-->

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

<!-- wp:post-terms {"term":"grade","style":{"typography":{"fontStyle":"normal","fontWeight":"900"}},"fontSize":"medium"} /--></div>
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

<!-- wp:post-terms {"term":"location","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}},"fontSize":"medium"} /--></div>
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
<h4 class="wp-block-heading"> Local Guide</h4>
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

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:mold/tab {"activeTabColor":"#508c14","tabGap":10,"borderRadius":10,"headerStyles":{"fontFamily":"","fontSize":"","textColor":"#333333","padding":{"inline":"30px","block":"16px"},"fontWeight":"bold"}} -->
<div class="wp-block-mold-tab tab-container tab-style-default" data-active-tab="0" style="--tab-header-fontFamily:inherit;--tab-header-fontSize:inherit;--tab-header-textColor:#333333;--tab-header-background-color:inherit;--tab-header-gap:10px;--tab-header-padding:16px 30px;--tab-header-border-radius:10px;--tab-header-border-width:1px;--tab-header-border-color:transparent;--tab-header-active-tab-text-color:#FFFFFF;--tab-header-active-tab-background-color:#508c14;--tab-header-active-tab-border-width:2px;--tab-content-fontFamily:inherit;--tab-content-fontSize:inherit;--tab-content-textColor:#333333;--tab-content-background-color:#ffffff;--tab-content-padding:10px 15px;--tab-content-border-radius:10px;--tab-content-border-width:1px;--tab-content-border-color:transparent"><nav class="tab-nav" role="tablist" aria-label="Tabs Navigation"></nav><div class="tab-content-wrapper"><!-- wp:mold/tab-item {"title":"Overview"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Overview" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:paragraph {"placeholder":"Add tab content here..."} -->
<p class="">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nunc vel tincidunt facilisis, nulla lorem posuere erat, vitae tincidunt sapien magna non justo. Integer nec risus ac nulla dignissim tincidunt. Suspendisse potenti. Curabitur ac felis nec sapien malesuada tincidunt. Proin sit amet magna ut erat fermentum tincidunt. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec vel augue nec sapien malesuada facilisis. Vivamus euismod, justo nec varius tincidunt, magna lorem tincidunt magna, nec tincidunt lorem magna nec magna. Sed vel sapien nec sapien tincidunt tincidunt. Nullam nec sapien nec sapien tincidunt tincidunt. Sed nec sapien nec sapien tincidunt tincidunt. Curabitur nec sapien nec sapien tincidunt tincidunt. Proin nec sapien nec sapien tincidunt tincidunt. Integer nec sapien nec sapien tincidunt tincidunt. Suspendisse nec sapien nec sapien tincidunt tincidunt.<br>Aliquam erat volutpat. Fusce nec sapien nec sapien tincidunt tincidunt. Morbi nec sapien nec sapien tincidunt tincidunt. Etiam nec sapien nec sapien tincidunt tincidunt. Nam nec sapien nec sapien tincidunt tincidunt. Sed nec sapien nec sapien tincidunt tincidunt. Curabitur nec sapien nec sapien tincidunt tincidunt. Proin nec sapien nec sapien tincidunt tincidunt. Integer nec sapien nec sapien tincidunt tincidunt. Suspendisse nec sapien nec sapien tincidunt tincidunt.<br>Praesent nec sapien nec sapien tincidunt tincidunt. Vestibulum nec sapien nec sapien tincidunt tincidunt. Cras nec sapien nec sapien tincidunt tincidunt. Ut nec sapien nec sapien tincidunt tincidunt. Aenean nec sapien nec sapien tincidunt tincidunt. Pellentesque nec sapien nec sapien tincidunt tincidunt. Vivamus nec sapien nec sapien tincidunt tincidunt.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:mold/tab-item -->

<!-- wp:mold/tab-item {"title":"Itinineary"} -->
<div class="wp-block-mold-tab-item tab-content-item undefined" data-tab-title="Itinineary" data-tab-id="tab-undefined" aria-hidden="true" hidden><!-- wp:mold/step {"stepType":"icon","listItems":[{"text":"Day 2 (Sept 22nd 2026)","content":"\u003cp\u003e\u003cstrong\u003eArrive Cuiba\u003c/strong\u003e\u003cbr /\u003eOn arrival in Cuiaba we will transfer to our Hotel.\u003cbr /\u003eHotel - Grand Odara\u003c/p\u003e","imageUrl":"","indexColor":"#f2f2f2","iconBgColor":"#121212","icon":"airplanemode_active"},{"text":"Day 3 (Sept 23rd 2026)","content":"\u003cp\u003e\u003cstrong\u003eTranspantaneira\u0026nbsp;\u003c/strong\u003e\u003cbr /\u003eMeeting at Gran Odara Hotel in the morning at 8am and transfer to Pocon\u0026eacute; City (100km). Once there, we take the Transpantaneira Park Road, where we drive 60 km until Pantanal Mato Grosso Hotel, arriving for lunch. In the afternoon (around 3pm) we will go for a boat safari at Pixaim River returning at 5pm. The dinner is served at 7pm. After dinner we go for a night drive, returning at 10pm and overnight.\u003cbr /\u003eMato Gross Hotel\u003c/p\u003e","imageUrl":"","indexColor":"#ffffff","iconBgColor":"#121212","icon":"directions_bus"},{"text":"Day 4 (Sept 24th 2026)","content":"\u003cp\u003e\u003cstrong\u003eJaguar Safari\u003c/strong\u003e\u003c/p\u003e\n\u003cp\u003eAfter breakfast, served at 7am, we take the Transpantaneira Park Road, where we drive for 85\u003cbr /\u003ekm until Santa Rosa Hotel. Along the Transpantaneira begins our wildlife highlights, like Caymans, Capybaras, many birds, and so on along the lodge\u003cbr /\u003eroad. We arrive at the Santa Rosa Hotel for lunch. This afternoon, around 2:30pm, we go for the first jaguar safari on the Cuiab\u0026aacute; River, returning at sunset. Dinner is served at 7pm. After dinner overnight, Santa Rosa Hotel\u003c/p\u003e","imageUrl":"","indexColor":"#ffffff","iconBgColor":"#121212","icon":"forest"},{"text":"Day 5, 6, 7 (Sept 25th - 27th 2026)","content":"\u003cp\u003e\u003cstrong\u003eJaguar Safari\u003c/strong\u003e\u003c/p\u003e\n\u003cp\u003eOver these next 3 days we will mainly stay focused on finding jaguars but we will also most likely see giant others, howler monkeys, brown capuchin monkeys, more mammals and many birds! After breakfast, served from 5:30 am, we will go on a boat safari. Return around 11 am for lunch, served at 12pm and break. Restart the Boat Safari at 2:30 pm, returning at the sunset for dinner and overnight.\u003c/p\u003e\n\u003cp\u003eSanta Rosa Hotel\u003c/p\u003e","imageUrl":"","icon":"explore","indexColor":"#fff","iconBgColor":"#222"},{"text":"Day 8 (Sept 28th 2026)","content":"\u003cp\u003e\u003cstrong\u003eSanta Rosa to Piuval Lodge\u003c/strong\u003e\u003c/p\u003e\n\u003cp\u003eThis morning will be our final opportunity to look for Jaguar. We will then have breakfast and check out the lodge. We then travel to Piuval Lodge where we will arrive late afternoon\u003c/p\u003e","imageUrl":"","icon":"hiking","indexColor":"#fff","iconBgColor":"#222"},{"text":"Day 9, 10 (Sept 29th - 30th 2026)","content":"\u003cp\u003e\u003cstrong\u003ePiuval Lodge\u003cbr /\u003e\u003c/strong\u003e\u003c/p\u003e\n\u003cp\u003e\u003cem\u003eFull day of activities\u003c/em\u003e\u003c/p\u003e\n\u003cp\u003eDrive Safari (safari Truck) before breakfast to seek giant anteater (the greatest chance). After breakfast we move on a safari truck along the fields and roads of the property until the bird watching tower, along the way we have chances to see Great Rheas, Armadillo, Giant Anteater, many birds. At the Tower we have the opportunity to see an unbelievable number of Storks and Egrets nesting.\u003c/p\u003e\n\u003cp\u003ePiuval Lodge\u003c/p\u003e","imageUrl":"","icon":"directions_bus","indexColor":"#fff","iconBgColor":"#222"},{"text":"Day 11 (Oct 1st 2026)","content":"\u003cp\u003e\u003cstrong\u003eFlight to UK\u003c/strong\u003e\u003cbr /\u003eAfter breakfast transfer to the airport for our flight to the UK\u003c/p\u003e","imageUrl":"","icon":"airplanemode_active","indexColor":"#fff","iconBgColor":"#222"},{"text":"Day 12 (Oct 2nd 2026)","content":"\u003cp\u003e\u003cstrong\u003eArrive UK\u003c/strong\u003e\u003c/p\u003e","imageUrl":"","icon":"flag","indexColor":"#fff","iconBgColor":"#222"}]} -->
<div class="wp-block-mold-step" data-animation-interval="2500" style="--head-color:#333333;--body-color:#333333;--track-color:#dddddd;--active-color:;--step-size:60px;--track-width:2px;padding:10px"><ul class="wp-mold-step" data-animation="false"><li class="wp-mold-step-li active animation-false" data-attr="list-1"><div class="step-index list-1 active" style="background: #222; color: #fff"><span class="material-symbols-outlined">airplanemode_active</span></div><div class="text-area"><div><h3 class="head-text">Day 2 (Sept 22nd 2026)</h3><div class="body-text"><p><strong>Arrive Cuiba</strong><br />On arrival in Cuiaba we will transfer to our Hotel.<br />Hotel - Grand Odara</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-2"><div class="step-index list-2 " style="background: #222; color: #fff"><span class="material-symbols-outlined">directions_bus</span></div><div class="text-area"><div><h3 class="head-text">Day 3 (Sept 23rd 2026)</h3><div class="body-text"><p><strong>Transpantaneira&nbsp;</strong><br />Meeting at Gran Odara Hotel in the morning at 8am and transfer to Pocon&eacute; City (100km). Once there, we take the Transpantaneira Park Road, where we drive 60 km until Pantanal Mato Grosso Hotel, arriving for lunch. In the afternoon (around 3pm) we will go for a boat safari at Pixaim River returning at 5pm. The dinner is served at 7pm. After dinner we go for a night drive, returning at 10pm and overnight.<br />Mato Gross Hotel</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-3"><div class="step-index list-3 " style="background: #222; color: #fff"><span class="material-symbols-outlined">forest</span></div><div class="text-area"><div><h3 class="head-text">Day 4 (Sept 24th 2026)</h3><div class="body-text"><p><strong>Jaguar Safari</strong></p>
<p>After breakfast, served at 7am, we take the Transpantaneira Park Road, where we drive for 85<br />km until Santa Rosa Hotel. Along the Transpantaneira begins our wildlife highlights, like Caymans, Capybaras, many birds, and so on along the lodge<br />road. We arrive at the Santa Rosa Hotel for lunch. This afternoon, around 2:30pm, we go for the first jaguar safari on the Cuiab&aacute; River, returning at sunset. Dinner is served at 7pm. After dinner overnight, Santa Rosa Hotel</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-4"><div class="step-index list-4 " style="background: #222; color: #fff"><span class="material-symbols-outlined">explore</span></div><div class="text-area"><div><h3 class="head-text">Day 5, 6, 7 (Sept 25th - 27th 2026)</h3><div class="body-text"><p><strong>Jaguar Safari</strong></p>
<p>Over these next 3 days we will mainly stay focused on finding jaguars but we will also most likely see giant others, howler monkeys, brown capuchin monkeys, more mammals and many birds! After breakfast, served from 5:30 am, we will go on a boat safari. Return around 11 am for lunch, served at 12pm and break. Restart the Boat Safari at 2:30 pm, returning at the sunset for dinner and overnight.</p>
<p>Santa Rosa Hotel</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-5"><div class="step-index list-5 " style="background: #222; color: #fff"><span class="material-symbols-outlined">hiking</span></div><div class="text-area"><div><h3 class="head-text">Day 8 (Sept 28th 2026)</h3><div class="body-text"><p><strong>Santa Rosa to Piuval Lodge</strong></p>
<p>This morning will be our final opportunity to look for Jaguar. We will then have breakfast and check out the lodge. We then travel to Piuval Lodge where we will arrive late afternoon</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-6"><div class="step-index list-6 " style="background: #222; color: #fff"><span class="material-symbols-outlined">directions_bus</span></div><div class="text-area"><div><h3 class="head-text">Day 9, 10 (Sept 29th - 30th 2026)</h3><div class="body-text"><p><strong>Piuval Lodge<br /></strong></p>
<p><em>Full day of activities</em></p>
<p>Drive Safari (safari Truck) before breakfast to seek giant anteater (the greatest chance). After breakfast we move on a safari truck along the fields and roads of the property until the bird watching tower, along the way we have chances to see Great Rheas, Armadillo, Giant Anteater, many birds. At the Tower we have the opportunity to see an unbelievable number of Storks and Egrets nesting.</p>
<p>Piuval Lodge</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-7"><div class="step-index list-7 " style="background: #222; color: #fff"><span class="material-symbols-outlined">airplanemode_active</span></div><div class="text-area"><div><h3 class="head-text">Day 11 (Oct 1st 2026)</h3><div class="body-text"><p><strong>Flight to UK</strong><br />After breakfast transfer to the airport for our flight to the UK</p></div></div></div></li><li class="wp-mold-step-li  animation-false" data-attr="list-8"><div class="step-index list-8 " style="background: #222; color: #fff"><span class="material-symbols-outlined">flag</span></div><div class="text-area"><div><h3 class="head-text">Day 12 (Oct 2nd 2026)</h3><div class="body-text"><p><strong>Arrive UK</strong></p></div></div></div></li></ul></div>
<!-- /wp:mold/step -->

<!-- wp:paragraph {"placeholder":"Add tab content here..."} -->
<p class=""></p>
<!-- /wp:paragraph --></div>
<!-- /wp:mold/tab-item --></div></div>
<!-- /wp:mold/tab --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:shortcode -->
[contact-form-7 id="392f199" title="Booking Query"]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->';

        return $default_content;
    }

    return $content;
}
add_filter('default_content', 'mold_set_default_tour_content', 10, 2);



// Enqueue admin script
function mold_tour_admin_scripts($hook) {
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
add_action('admin_enqueue_scripts', 'mold_tour_admin_scripts');



// AJAX handler for toggling featured (using tag instead of meta)
function mold_tour_toggle_featured() {
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
add_action('wp_ajax_tour_toggle_featured', 'mold_tour_toggle_featured');



register_post_meta('tour', '_tour_gallery', [
    'show_in_rest' => true,
    'single'       => true,
    'type'         => 'string',
]);

?>