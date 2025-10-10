<?php

if(!function_exists('mold_add_bookable_option')) {
    /*add bookable checkbox on product - Admin*/
    function mold_add_bookable_option( $product_type_options ) {
        $product_type_options['booking_option'] = array(
            'id'            => 'is_mold_trip',
            'wrapper_class' => '',
            'label'         => esc_html__( 'Bookable', 'mold-tour' ),
            'description'   => esc_html__( 'Bookable products are Trip Pages that can be booked', 'mold-tour' )
            );

        return $product_type_options;
    }
    add_action( 'product_type_options', 'mold_add_bookable_option');
}

if(!function_exists('mold_update_is_mold_trip')) {
    /*On bookable checkbox toggle Update Fields*/
    function mold_update_is_mold_trip( $post_id ){
        $is_mold_trip = $_POST['is_mold_trip'] ;
        update_post_meta( $post_id, 'is_mold_trip', $is_mold_trip );
    }
    add_action( 'woocommerce_process_product_meta', 'mold_update_is_mold_trip' );
}


if(!function_exists('mold_remove_tab')) {
    /*On bookable checkbox toggle remove and hide extra menu - overview, itenary, cost-book*/
    function mold_remove_tab( $tabs) {
        global $post;
        $is_bookable = get_post_meta( $post->ID, 'is_mold_trip', true ); 
        if($is_bookable == 'on'){
            // Other default values for 'attribute' are; general, inventory, shipping, linked_product, variations, advanced
            $tabs['attribute']['class'][] = 'hide_if_simple';
            $tabs['shipping']['class'][] = 'hide_if_simple hide_if_variable';
        }

        return $tabs;
    }
    add_filter( 'woocommerce_product_data_tabs', 'mold_remove_tab' );
}

/****************************/

if(!function_exists('mold_meta_box_product_layout')) {
    /*add meta box Slider layout on Product detail -Admin*/
    function mold_meta_box_product_layout()
    {
        global $post;
        if(!empty($post))
        {
            add_meta_box( 
                'mold_slider_layout_box', 
                esc_html__('Mold Slider Layout', 'mold-tour'), 
                'mold_slider_layout_detail', 
                'product', 
                'side', 
                'low' ); 
        }
    }
    add_action( 'add_meta_boxes', 'mold_meta_box_product_layout' );

    function mold_slider_layout_detail($post){
        // $post is already set, and contains an object: the WordPress post
        global $post;
        $values = get_post_custom( $post->ID );
        
        $menu_bg = isset( $values['mold_slider_bg'] ) ? esc_attr( $values['mold_slider_bg'][0] ) : 'with-overlay';
        $slider_effect = isset( $values['mold_slider_effect'] ) ? esc_attr( $values['mold_slider_effect'][0] ) : 'carousel-fade';
        $slider_height = isset( $values['mold_slider_height'] ) ? esc_attr( $values['mold_slider_height'][0] ) : '';
        $slider_nav = isset( $values['mold_slider_nav'] ) ? esc_attr( $values['mold_slider_nav'][0] ) : '';
        $slider_indicator = isset( $values['mold_slider_indicator'] ) ? esc_attr( $values['mold_slider_indicator'][0] ) : '';
        $add_to_cart_label = (isset( $values['mold_add_to_cart_label'] ) && ($values['mold_add_to_cart_label'][0] != "")) ? esc_attr( $values['mold_add_to_cart_label'][0] ) : esc_html__('Book Now', 'mold-tour');


        // We'll use this nonce field later on when saving.
        wp_nonce_field( 'slide_meta_box_nonce', 'meta_box_nonce_slider' );
        ?>

        <p class="post-attributes-label-wrapper">
            <label class="post-attributes-label" for="mold_slider_bg"><?php esc_html_e('Slider Overlay', 'mold-tour')?></label>
        </p>
        <select name="mold_slider_bg" id="mold_slider_bg">
            <option value="with-overlay" <?php selected( $menu_bg, 'with-overlay' ); ?>><?php esc_html_e('Overlay', 'mold-tour')?></option>
            <option value="with-text-box" <?php selected( $menu_bg, 'with-text-box' ); ?>><?php esc_html_e('Boxed', 'mold-tour')?></option>
            <option value="" <?php selected( $menu_bg, '' ); ?>><?php esc_html_e('Transparent', 'mold-tour')?></option>
        </select>

        <p class="post-attributes-label-wrapper">
            <label class="post-attributes-label" for="mold_slider_effect"><?php esc_html_e('Slider Effect', 'mold-tour')?></label>
        </p>
        <select name="mold_slider_effect" id="mold_slider_effect">
            <option value="carousel-fade" <?php selected( $slider_effect, 'carousel-fade' ); ?>><?php esc_html_e('Fade', 'mold-tour')?></option>
            <option value="slide" <?php selected( $slider_effect, 'slide' ); ?>><?php esc_html_e('Slide', 'mold-tour')?></option>
        </select>

        <p class="post-attributes-label-wrapper">
            <label class="post-attributes-label" for="mold_slider_height"><?php esc_html_e('Slider height', 'mold-tour')?></label>
        </p>
        <select name="mold_slider_height" id="mold_slider_height">
            <option value="" <?php selected( $slider_height, '' ); ?>><?php esc_html_e('Default', 'mold-tour')?></option>
            <option value="full-height" <?php selected( $slider_height, 'full-height' ); ?>><?php esc_html_e('Full Height', 'mold-tour')?></option>
        </select>

        <p class="post-attributes-label-wrapper">
            <label class="post-attributes-label" for="mold_slider_nav"><?php esc_html_e('Slider Nav Control Position', 'mold-tour')?></label>
        </p>
        <select name="mold_slider_nav" id="mold_slider_nav">
            <option value="" <?php selected( $slider_nav, '' ); ?>><?php esc_html_e('Default', 'mold-tour')?></option>
            <option value="bottom" <?php selected( $slider_nav, 'bottom' ); ?>><?php esc_html_e('Bottom', 'mold-tour')?></option>
            <option value="bottom-right" <?php selected( $slider_nav, 'bottom-right' ); ?>><?php esc_html_e('Bottom Right', 'mold-tour')?></option>
            <option value="bottom-left" <?php selected( $slider_nav, 'bottom-left' ); ?>><?php esc_html_e('Bottom Left', 'mold-tour')?></option>
            <option value="hide" <?php selected( $slider_nav, 'hide' ); ?>><?php esc_html_e('Hide', 'mold-tour')?></option>
        </select>

        <p class="post-attributes-label-wrapper">
            <label class="post-attributes-label" for="mold_slider_indicator"><?php esc_html_e('Slider Indicator', 'mold-tour')?></label>
        </p>
        <select name="mold_slider_indicator" id="mold_slider_indicator">
            <option value="" <?php selected( $slider_indicator, '' ); ?>><?php esc_html_e('Default', 'mold-tour')?></option>
            <option value="square" <?php selected( $slider_indicator, 'square' ); ?>><?php esc_html_e('Square', 'mold-tour')?></option>
            <option value="dashed" <?php selected( $slider_indicator, 'dashed' ); ?>><?php esc_html_e('Dashed', 'mold-tour')?></option>
            <option value="hide" <?php selected( $slider_indicator, 'hide' ); ?>><?php esc_html_e('Hide', 'mold-tour')?></option>
        </select>

         <p class="post-attributes-label-wrapper">
            <label class="post-attributes-label" for="mold_add_to_cart_label"><?php esc_html_e('Add To Cart Button Label', 'mold-tour')?></label>
        </p>
        <input type="text" name="mold_add_to_cart_label" id="mold_add_to_cart_label" value="<?php echo esc_html($add_to_cart_label);?>">        
    
    <?php 
    }
}



if(!function_exists('mold_slider_layout_detail_save')) {
    /* Save Slider setting Product to database */
    function mold_slider_layout_detail_save( $post_id )
    {
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce_slider'] ) || !wp_verify_nonce( $_POST['meta_box_nonce_slider'], 'slide_meta_box_nonce' ) ) return;

        if( !current_user_can( 'edit_post', $_POST['post_ID'] ) )
            return;

        update_post_meta( $post_id, 'mold_slider_bg', esc_attr( $_POST['mold_slider_bg'] ) );
        update_post_meta( $post_id, 'mold_slider_effect', esc_attr( $_POST['mold_slider_effect'] ) );
        update_post_meta( $post_id, 'mold_slider_height', esc_attr( $_POST['mold_slider_height'] ) );
        update_post_meta( $post_id, 'mold_slider_nav', esc_attr( $_POST['mold_slider_nav'] ) );
        update_post_meta( $post_id, 'mold_slider_indicator', esc_attr( $_POST['mold_slider_indicator'] ) );
        update_post_meta( $post_id, 'mold_add_to_cart_label', esc_attr( $_POST['mold_add_to_cart_label'] ) );

    }
    add_action( 'save_post', 'mold_slider_layout_detail_save' );
}



