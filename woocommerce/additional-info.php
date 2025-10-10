<?php 

if(!function_exists('mold_remove_product_tabs')) {
	/*If product is bookable trip it removes additional Information tab*/
	function mold_remove_product_tabs( $tabs ) {
		global $post;
		$product = wc_get_product( $post->ID );
		$is_mold_trip = get_post_meta( $post->ID, 'is_mold_trip', true ); 
		if($is_mold_trip == 'on'){
	    	unset( $tabs['additional_information'] );   // Remove the additional information tab      
		}
		return $tabs;
	}
	add_filter( 'woocommerce_product_tabs', 'mold_remove_product_tabs', 98 );
}