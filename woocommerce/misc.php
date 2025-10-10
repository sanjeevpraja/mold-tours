<?php

if(!function_exists('mold_change_add_cart_text')) {
	/*Change label on button add  to cart to Book Now - woocommerce */
	function mold_change_add_cart_text() {
		$mold_add_to_cart_label = get_post_meta( get_the_ID(), 'mold_add_to_cart_label', true);
					if($mold_add_to_cart_label != ''){
						return $mold_add_to_cart_label;
					}
					else{
					return esc_html__( 'Book Now', 'mold-tour' ); 
					}
		
	}
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'mold_change_add_cart_text' );
	add_filter( 'woocommerce_product_add_to_cart_text', 'mold_change_add_cart_text' ); 
}


if(!function_exists('mold_woocommerce_search_result_title')) {
	/*Filter for custom format for title to search result page - woocommercew*/
	function mold_woocommerce_search_result_title( $page_title )
	{
		if ( is_search() ) {
			if (! get_search_query()) {
				$page_title = '<span>' . esc_html__('Search Results:', 'mold-tour') . '</span>' . esc_html__( 'All Products', 'mold-tour' );
			} else {
				$page_title = '<span>' . esc_html__('Search Results:', 'mold-tour') . '</span>' . sprintf( esc_html__( '%s', 'mold-tour' ), get_search_query() );
			}
		}
		return $page_title;
	}
	add_filter( 'woocommerce_page_title', 'mold_woocommerce_search_result_title', 10, 1 );
}
