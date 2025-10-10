<?php
/**
 * Create a taxonomy
 */


if ( ! class_exists( 'Mold_Location' ) ) {

	class Mold_Location {

		public function __construct() {
			add_action( 'init',  array ( $this, 'mold_location_taxonomies'));
			add_action( 'save_post_product',  array ( $this,'mold_save_location_meta_box'), 10, 1);

			add_action( 'location_add_form_fields', array ( $this, 'mold_add_location_image' ));
			add_action( 'created_location', array ( $this, 'mold_save_location_image' ), 10, 1);
			add_action( 'location_edit_form_fields', array ( $this, 'mold_update_location_image' ), 10, 2);
			add_action( 'edited_location', array ( $this, 'mold_edit_location_image' ), 10, 1);
			

			add_action( 'location_add_form_fields', array ( $this, 'mold_add_map_image' ));
			add_action( 'created_location', array ( $this, 'mold_save_map_image' ), 10, 1 );
			add_action( 'location_edit_form_fields', array ( $this, 'mold_update_map_image' ), 10, 2);
			add_action( 'edited_location', array ( $this, 'mold_edit_map_image' ), 10, 1);

			add_action( 'admin_footer', array ( $this, 'mold_location_add_script' ));

		}


		/*register taxonomy*/
		public function mold_location_taxonomies() {
			$labels = array(
				'name'					=> esc_html_x( 'Locations', 'Taxonomy Locations', 'mold-tour' ),
				'singular_name'			=> esc_html_x( 'Location', 'Taxonomy Location', 'mold-tour' ),
				'search_items'			=> esc_html__( 'Search Locations', 'mold-tour' ),
				'popular_items'			=> esc_html__( 'Popular Locations', 'mold-tour' ),
				'all_items'				=> esc_html__( 'All Locations', 'mold-tour' ),
				'parent_item'			=> esc_html__( 'Parent Location', 'mold-tour' ),
				'parent_item_colon'		=> esc_html__( 'Parent Location', 'mold-tour' ),
				'edit_item'				=> esc_html__( 'Edit Location', 'mold-tour' ),
				'update_item'			=> esc_html__( 'Update Location', 'mold-tour' ),
				'add_new_item'			=> esc_html__( 'Add New Location', 'mold-tour' ),
				'new_item_name'			=> esc_html__( 'New Location Name', 'mold-tour' ),
				'add_or_remove_items'	=> esc_html__( 'Add or remove Locations', 'mold-tour' ),
				'choose_from_most_used'	=> esc_html__( 'Choose from most used Location', 'mold-tour' ),
				'menu_name'				=> esc_html__( 'Location', 'mold-tour' ),
				);

				$args = array(
					'labels'            => $labels,
					'public'            => true,
					'show_in_nav_menus' => true,
					'show_ui'           => true,
					'show_admin_column' => true,
					'hierarchical'      => true, //keep true (acts like categories)
					'show_tagcloud'     => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => 'location' ),
				
					//Required for Gutenberg and WooCommerce block-based product editor
					'show_in_rest'      => true,
					'rest_base'         => 'product-locations',
					'rest_controller_class' => 'WP_REST_Terms_Controller',
				
					//Clean capabilities placeholder
					'capabilities'      => array(),
				);
				
				register_taxonomy( 'location', array( 'product' ), $args );
		}


		/*******************/
			
		/*
		  * Add image field in the new location 
		  * @since 1.0.0
		*/
		 public function mold_add_location_image ( $taxonomy ) { ?>
		 <div class="form-field term-group">
		 	<label for="location-image-id"><?php esc_html_e('Image', 'mold-tour'); ?></label>
		 	<input type="hidden" id="location-image-id" name="location-image-id" class="custom_media_url" value="">
		 	<div id="location-image-wrapper"></div>
		 	<p>
		 		<input type="button" class="button button-secondary location_image_add" id="location_image_add" name="location_image_add" value="<?php esc_attr_e( 'Add Image', 'mold-tour' ); ?>" />
		 		<input type="button" class="button button-secondary location_image_remove" id="location_image_remove" name="location_image_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
		 	</p>
		 </div>
		 <?php
		}

		/*
		  * Save image field
		  * @since 1.0.0
		*/
		 public function mold_save_location_image ( $term_id ) {
		 	if( isset( $_POST['location-image-id'] ) && '' !== $_POST['location-image-id'] ){
		 		$image = $_POST['location-image-id'];
		 		add_term_meta( $term_id, 'location-image-id', $image, true );
		 	}
		 }
	 
		/*
		  * Edit image field
		  * @since 1.0.0
		*/
		 public function mold_update_location_image ( $term, $taxonomy ) { ?>
		 <tr class="form-field term-group-wrap">
		 	<th scope="row">
		 		<label for="location-image-id"><?php esc_html_e( 'Image', 'mold-tour' ); ?></label>
		 	</th>
		 	<td>
		 		<?php $image_id = get_term_meta ( $term -> term_id, 'location-image-id', true ); ?>
		 		<input type="hidden" id="location-image-id" name="location-image-id" value="<?php echo esc_attr($image_id); ?>">
		 		<div id="location-image-wrapper">
		 			<?php if ( $image_id ) { ?>
		 			<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
		 			<?php } ?>
		 		</div>
		 		<p>
		 			<input type="button" class="button button-secondary location_image_add" id="location_image_add" name="location_image_add" value="<?php esc_attr_e( 'Add Image', 'mold-tour' ); ?>" />
		 			<input type="button" class="button button-secondary location_image_remove" id="location_image_remove" name="location_image_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
		 		</p>
		 	</td>
		 </tr>
		 <?php
		}

		/*
		 * Edit image field value
		 * @since 1.0.0
		 */
		public function mold_edit_location_image ( $term_id ) {
			if( isset( $_POST['location-image-id'] ) && '' !== $_POST['location-image-id'] ){
				$image = $_POST['location-image-id'];
				update_term_meta ( $term_id, 'location-image-id', $image );
			} else {
				update_term_meta ( $term_id, 'location-image-id', '' );
			}
		}



		/**********************
		**********************/


		/*
		  * Add Map image in the new location 
		  * @since 1.0.0
		*/
		public function mold_add_map_image ( $taxonomy ) { ?>
		<div class="form-field term-group">
		 	<label for="map-image-id"><?php esc_html_e('Map', 'mold-tour'); ?></label>
		 	<input type="hidden" id="map-image-id" name="map-image-id" class="custom_media_url" value="">
		 	<div id="map-image-wrapper"></div>
		 	<p>
		 		<input type="button" class="button button-secondary map_image_add" id="map_image_add" name="map_image_add" value="<?php esc_attr_e( 'Add Image', 'mold-tour' ); ?>" />
		 		<input type="button" class="button button-secondary map_image_remove" id="map_image_remove" name="map_image_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
		 	</p>
		</div>
		<?php
		}

		/*
		  * Save Map image
		  * @since 1.0.0
		*/
		public function mold_save_map_image ( $term_id ) {
		 	if( isset( $_POST['map-image-id'] ) && '' !== $_POST['map-image-id'] ){
		 		$image = $_POST['map-image-id'];
		 		add_term_meta( $term_id, 'map-image-id', $image, true );
		 	}
		}
	 
		/*
		  * Edit Map image
		  * @since 1.0.0
		*/
		public function mold_update_map_image ( $term, $taxonomy ) { ?>
		<tr class="form-field term-group-wrap">
		 	<th scope="row">
		 		<label for="map-image-id"><?php esc_html_e( 'Map', 'mold-tour' ); ?></label>
		 	</th>
		 	<td>
		 		<?php $image_id = get_term_meta ( $term -> term_id, 'map-image-id', true ); ?>
		 		<input type="hidden" id="map-image-id" name="map-image-id" value="<?php echo $image_id; ?>">
		 		<div id="map-image-wrapper">
		 			<?php if ( $image_id ) { ?>
		 			<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
		 			<?php } ?>
		 		</div>
		 		<p>
		 			<input type="button" class="button button-secondary map_image_add" id="map_image_add" name="map_image_add" value="<?php esc_attr_e( 'Add Image', 'mold-tour' ); ?>" />
		 			<input type="button" class="button button-secondary map_image_remove" id="map_image_remove" name="map_image_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
		 		</p>
		 	</td>
		</tr>
		<?php
		}

		/*
		 * update Map image value
		 * @since 1.0.0
		 */
		public function mold_edit_map_image ( $term_id ) {
			if( isset( $_POST['map-image-id'] ) && '' !== $_POST['map-image-id'] ){
				$image = $_POST['map-image-id'];
				update_term_meta ( $term_id, 'map-image-id', $image );
			} else {
				update_term_meta ( $term_id, 'map-image-id', '' );
			}
		}

		/*
		 * Add script image add/remove btn
		 * @since 1.0.0
		 */
		public function mold_location_add_script() { ?>
		<script>
			jQuery(document).ready( function($) {
				function location_image_upload(button_class) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('body').on('click', button_class, function(e) {
						var button_id = '#'+$(this).attr('id');
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(button_id);
						_custom_media = true;
						wp.media.editor.send.attachment = function(props, attachment){
								$('#location-image-id').val(attachment.id);
								$('#location-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
								$('#location-image-wrapper .custom_media_image').attr('src',attachment.sizes.thumbnail.url).css('display','block');
						}
						wp.media.editor.open();
						return false;
					});
				}
				location_image_upload('.location_image_add.button'); 
				$('body').on('click','.location_image_remove',function(){
					$('#location-image-id').val('');
					$('#location-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
				});


			    /***********/

			    function map_image_upload(button_class) {
			     	var _custom_media = true,
			     	_orig_send_attachment = wp.media.editor.send.attachment;
			     	$('body').on('click', button_class, function(e) {
			     		var button_id = '#'+$(this).attr('id');
			     		var send_attachment_bkp = wp.media.editor.send.attachment;
			     		var button = $(button_id);
			     		wp.media.editor.send.attachment = function(props, attachment){
			     				$('#map-image-id').val(attachment.id);
			     				$('#map-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
			     				$('#map-image-wrapper .custom_media_image').attr('src',attachment.sizes.thumbnail.url).css('display','block');
			     		}
			     		wp.media.editor.open();
			     		return false;
			     	});
			     }
			     map_image_upload('.map_image_add.button'); 
			     $('body').on('click','.map_image_remove',function(){
			     	$('#map-image-id').val('');
			     	$('#map-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
			     });
		 	});
		</script>
		<?php 
		}



		/**********************/
		/*Save Loaction*/
		public function mold_save_location_meta_box( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( ! isset( $_POST['location'] ) ) {
				return;
			}
			$location = sanitize_text_field( $_POST['location'] );

			// A valid location is required, so don't let this get published without one
			if ( empty( $location ) ) {
				$postdata = array(
					'ID'          => $post_id,
					'post_status' => 'draft',
					);
				wp_update_post( $postdata );
			} else {
				$term = get_term_by( 'name', $location, 'location' );
				if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
					wp_set_object_terms( $post_id, $term->term_id, 'location', false );
				}
			}
		}

	}

$mold_location = new Mold_Location();

}



/*adding icon column to term list*/
add_filter('manage_edit-location_columns', 'mold_add_location_icon_column' );
function mold_add_location_icon_column( $columns ){
	$columns['location_image'] = esc_html__( 'Image', 'mold-tour' );
	$columns['map_image'] = esc_html__( 'Map', 'mold-tour' );
	return $columns;
}

add_filter('manage_location_custom_column', 'mold_add_location_icon_column_content', 10, 3 );
function mold_add_location_icon_column_content( $content, $column_name, $term_id ){
	$term_id = absint( $term_id );
	$location_image = get_term_meta( $term_id, 'location-image-id', true );
	$map_image = get_term_meta( $term_id, 'map-image-id', true );

	switch( $column_name ){
		case 'location_image' :
		if ( $location_image ) {
			$location_img_url = wp_get_attachment_image_src ( $location_image, 'thumbnail' );
			echo '<img src="'.$location_img_url[0].'" style="width: 60px; height: 60px"/>';
		}
		else{
			echo '--';
		}
		break;
		case 'map_image' :
		if ( $map_image ) {
			$map_img_url = wp_get_attachment_image_src ( $map_image, 'thumbnail' );
			echo '<img src="'.$map_img_url[0].'" style="width: 60px; height: 60px"/>';
		}
		else{
			echo '--';
		}
		break;
	}	

}