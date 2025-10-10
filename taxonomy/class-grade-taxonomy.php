<?php
if ( ! class_exists( 'Mold_Grade' ) ) {
	class Mold_Grade {
		public function __construct() {
			register_activation_hook( MOLD_TOUR_MAIN_FILE_URL ,array($this,'mold_activate'));
			add_action( 'init', array ( $this, 'mold_grade_taxonomies') );
			add_action( 'save_post_product', array ( $this,'mold_save_grade_meta_box') );

			add_action( 'grade_add_form_fields', array ( $this, 'mold_add_grade_icon' ));
			add_action( 'created_grade', array ( $this, 'mold_save_grade_icon' ), 10, 1 );
			add_action( 'grade_edit_form_fields', array ( $this, 'mold_update_grade_icon' ), 10, 2);
			add_action( 'edited_grade', array ( $this, 'mold_edit_grade_icon' ), 10, 1);

			add_action( 'grade_add_form_fields', array ( $this, 'mold_add_grade_image' ), 10, 1);
			add_action( 'created_grade', array ( $this, 'mold_save_grade_image' ) , 10, 1);
			add_action( 'grade_edit_form_fields', array ( $this, 'mold_update_grade_image' ), 10, 2);
			add_action( 'edited_grade', array ( $this, 'mold_edit_grade_image' ), 10, 1);
			add_action( 'admin_footer', array ( $this, 'mold_grade_add_script' ));

			// Unfortunately, 'admin_notices' puts this too high on the edit screen
			// add_action( 'edit_form_top', array ( $this,'mold_grade_required_field_error_msg') );
		}

		public function mold_activate() {
			$this->mold_grade_taxonomies();
			if(wp_count_terms('grade') == 0){
				if( !term_exists( 'Easy', 'grade' ) ) {
					$term = wp_insert_term(
						'Easy',
						'grade', 
						array(
							'description'  => '',
							'slug'          => 'easy'
						)
					);
					add_term_meta($term['term_id'], 'grade-icon-id', 'icon-level-1');
				}
				if( !term_exists( 'Moderate', 'grade' ) ) {
					$term = wp_insert_term(
						'Moderate',
						'grade', 
						array(
							'description'  => '',
							'slug'          => 'moderate'
						)
					);
					add_term_meta($term['term_id'], 'grade-icon-id', 'icon-level-3');
				}
				if( !term_exists( 'Difficult', 'grade' ) ) {
					$term = wp_insert_term(
						'Difficult',
						'grade', 
						array(
							'description'  => '',
							'slug'          => 'difficult'
						)
					);
					add_term_meta($term['term_id'], 'grade-icon-id', 'icon-level-5');
				}
				if( !term_exists( 'Adventurous', 'grade' ) ) {
					$term = wp_insert_term(
						'Adventurous',
						'grade', 
						array(
							'description'  => '',
							'slug'          => 'adventurous'
						)
					);
					add_term_meta($term['term_id'], 'grade-icon-id', 'icon-level-8');
				}
				if( !term_exists( 'Challenging', 'grade' ) ) {
					$term = wp_insert_term(
						'Challenging',
						'grade', 
						array(
							'description'  => '',
							'slug'          => 'Challenging'
						)
					);
					add_term_meta($term['term_id'], 'grade-icon-id', 'icon-level-10');
				}
			}
		}
		/**
		 * Create a taxonomy
		 */
		public function mold_grade_taxonomies() {
			$labels = array(
				'name'					=> esc_html_x( 'Grades', 'Taxonomy Grades', 'mold-tour' ),
				'singular_name'			=> esc_html_x( 'Grade', 'Taxonomy Grade', 'mold-tour' ),
				'search_items'			=> esc_html__( 'Search Grades', 'mold-tour' ),
				'popular_items'			=> esc_html__( 'Popular Grades', 'mold-tour' ),
				'all_items'				=> esc_html__( 'All Grades', 'mold-tour' ),
				'parent_item'			=> esc_html__( 'Parent Grade', 'mold-tour' ),
				'parent_item_colon'		=> esc_html__( 'Parent Grade', 'mold-tour' ),
				'edit_item'				=> esc_html__( 'Edit Grade', 'mold-tour' ),
				'update_item'			=> esc_html__( 'Update Grade', 'mold-tour' ),
				'add_new_item'			=> esc_html__( 'Add New Grade', 'mold-tour' ),
				'new_item_name'			=> esc_html__( 'New Grade Name', 'mold-tour' ),
				'add_or_remove_items'	=> esc_html__( 'Add or remove Grades', 'mold-tour' ),
				'choose_from_most_used'	=> esc_html__( 'Choose from most used Grade', 'mold-tour' ),
				'menu_name'				=> esc_html__( 'Grade', 'mold-tour' ),
			);

			$args = array(
				'labels'            => $labels,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'hierarchical'      => true,
				'show_tagcloud'     => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'grade' ),
			
				//Add REST support for Gutenberg
				'show_in_rest'      => true,
				'rest_base'         => 'product-grades',
				'rest_controller_class' => 'WP_REST_Terms_Controller',
			
				//Keep meta_box_cb for classic editor only
				'meta_box_cb'       => array( $this, 'grade_meta_box' ),
			
				//Clean and ready
				'capabilities'      => array(),
			);
			
			register_taxonomy( 'grade', array( 'product' ), $args );
		}



		/***********************/

		 /*
		  * Add a icon field in the new Grade
		  * @since 1.0.0
		 */
		 public function mold_add_grade_icon ( $taxonomy ) { ?>
		 <div class="form-field term-group">
		 	<label for="grade-icon-id"><?php esc_html_e('Icon', 'mold-tour'); ?></label>
		 	<select id="grade-icon-id" name="grade-icon-id">
		 		<option value="" selected>--</option>
		 		<option value="icon-level-1">icon-level-1</option>
		 		<option value="icon-level-2">icon-level-2</option>
		 		<option value="icon-level-3">icon-level-3</option>
		 		<option value="icon-level-4">icon-level-4</option>
		 		<option value="icon-level-5">icon-level-5</option>
		 		<option value="icon-level-6">icon-level-6</option>
		 		<option value="icon-level-7">icon-level-7</option>
		 		<option value="icon-level-8">icon-level-8</option>
		 		<option value="icon-level-9">icon-level-9</option>
		 		<option value="icon-level-10">icon-level-10</option>
		 	</select>
		 </div>
		 <?php
		}


		/*
		  * Save icon field
		  * @since 1.0.0
		 */
		public function mold_save_grade_icon ( $term_id) {
			if( isset( $_POST['grade-icon-id'] ) && '' !== $_POST['grade-icon-id'] ){
				$grade_icon = $_POST['grade-icon-id'];
				add_term_meta( $term_id, 'grade-icon-id', $grade_icon, true );
			}
		}

		 /*
		  * Edit icon field
		  * @since 1.0.0
		 */
		 public function mold_update_grade_icon ( $term, $taxonomy ) { ?>
		 <tr class="form-field term-group-wrap">
		 	<th scope="row">
		 		<label for="grade-icon-id"><?php esc_html_e( 'Icon', 'mold-tour' ); ?></label>
		 	</th>
		 	<td>
		 		<?php $icon_id = get_term_meta ( $term -> term_id, 'grade-icon-id', true ); ?>
		 		<select id="grade-icon-id" name="grade-icon-id">
		 			<option value="" <?php echo (($icon_id == "") ? 'selected': '');?>>--</option>
		 			<option value="icon-level-1" <?php echo (($icon_id == "icon-level-1") ? 'selected': '');?>>icon-level-1</option>
		 			<option value="icon-level-2" <?php echo (($icon_id == "icon-level-2") ? 'selected': '');?>>icon-level-2</option>
		 			<option value="icon-level-3" <?php echo (($icon_id == "icon-level-3") ? 'selected': '');?>>icon-level-3</option>
		 			<option value="icon-level-4" <?php echo (($icon_id == "icon-level-4") ? 'selected': '');?>>icon-level-4</option>
		 			<option value="icon-level-5" <?php echo (($icon_id == "icon-level-5") ? 'selected': '');?>>icon-level-5</option>
		 			<option value="icon-level-6" <?php echo (($icon_id == "icon-level-6") ? 'selected': '');?>>icon-level-6</option>
		 			<option value="icon-level-7" <?php echo (($icon_id == "icon-level-7") ? 'selected': '');?>>icon-level-7</option>
		 			<option value="icon-level-8" <?php echo (($icon_id == "icon-level-8") ? 'selected': '');?>>icon-level-8</option>
		 			<option value="icon-level-9" <?php echo (($icon_id == "icon-level-9") ? 'selected': '');?>>icon-level-9</option>
		 			<option value="icon-level-10" <?php echo (($icon_id == "icon-level-10") ? 'selected': '');?>>icon-level-10</option>
		 		</select>
		 	</td>
		 </tr>
		 <?php
		}

		/*
		 * Edit icon field value
		 * @since 1.0.0
		 */
		public function mold_edit_grade_icon ( $term_id) {
			if( isset( $_POST['grade-icon-id'] ) && '' !== $_POST['grade-icon-id'] ){
				$grade_icon = $_POST['grade-icon-id'];
				update_term_meta ( $term_id, 'grade-icon-id', $grade_icon );
			} else {
				update_term_meta ( $term_id, 'grade-icon-id', '' );
			}
		}



		/***********************/


		 /*
		  * Add image field in the new grade
		  * @since 1.0.0
		 */
		 public function mold_add_grade_image ( $taxonomy ) { ?>
		 <div class="form-field term-group">
		 	<label for="grade-image-id"><?php esc_html_e('Image', 'mold-tour'); ?></label>
		 	<input type="hidden" id="grade-image-id" name="grade-image-id" class="custom_media_url" value="">
		 	<div id="grade-image-wrapper"></div>
		 	<p>
		 		<input type="button" class="button button-secondary grade_image_add" id="grade_image_add" name="grade_image_add" value="<?php esc_attr_e( 'Add Image', 'mold-tour' ); ?>" />
		 		<input type="button" class="button button-secondary grade_imgae_remove" id="grade_imgae_remove" name="grade_imgae_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
		 	</p>
		 </div>
		 <?php
		}

		 /*
		  * Save image field
		  * @since 1.0.0
		 */
		 public function mold_save_grade_image ( $term_id) {
		 	if( isset( $_POST['grade-image-id'] ) && '' !== $_POST['grade-image-id'] ){
		 		$image = $_POST['grade-image-id'];
		 		add_term_meta( $term_id, 'grade-image-id', $image, true );
		 	}
		 }
		 
		 /*
		  * Edit image field
		  * @since 1.0.0
		 */
		 public function mold_update_grade_image ( $term, $taxonomy ) { ?>
		 <tr class="form-field term-group-wrap">
		 	<th scope="row">
		 		<label for="grade-image-id"><?php esc_html_e( 'Image', 'mold-tour' ); ?></label>
		 	</th>
		 	<td>
		 		<?php $image_id = get_term_meta ( $term -> term_id, 'grade-image-id', true ); ?>
		 		<input type="hidden" id="grade-image-id" name="grade-image-id" value="<?php echo esc_attr($image_id); ?>">
		 		<div id="grade-image-wrapper">
		 			<?php if ( $image_id ) { ?>
		 			<?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
		 			<?php } ?>
		 		</div>
		 		<p>
		 			<input type="button" class="button button-secondary grade_image_add" id="grade_image_add" name="grade_image_add" value="<?php esc_attr_e( 'Add Image', 'mold-tour' ); ?>" />
		 			<input type="button" class="button button-secondary grade_imgae_remove" id="grade_imgae_remove" name="grade_imgae_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
		 		</p>
		 	</td>
		 </tr>
		 <?php
		}

		/*
		 * edit image field value
		 * @since 1.0.0
		 */
		public function mold_edit_grade_image ( $term_id) {
			if( isset( $_POST['grade-image-id'] ) && '' !== $_POST['grade-image-id'] ){
				$image = $_POST['grade-image-id'];
				update_term_meta ( $term_id, 'grade-image-id', $image );
			} else {
				update_term_meta ( $term_id, 'grade-image-id', '' );
			}
		}

		/*
		 * Add script for image
		 * @since 1.0.0
		 */
		public function mold_grade_add_script() { ?>
		<script>
			jQuery(document).ready( function($) {
				function ct_media_upload(button_class) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('body').on('click', button_class, function(e) {
						var button_id = '#'+$(this).attr('id');
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(button_id);
						wp.media.editor.send.attachment = function(props, attachment){
							$('#grade-image-id').val(attachment.id);
							$('#grade-image-wrapper').html('<img class="custom_media_image" src="" style="max-height:100px;" />');
							$('#grade-image-wrapper .custom_media_image').attr('src',attachment.sizes.thumbnail.url).css('display','block');
						}
						wp.media.editor.open();
						return false;
					});
				}
				ct_media_upload('.grade_image_add.button'); 
				$('body').on('click','.grade_imgae_remove',function(){
					$('#grade-image-id').val('');
					$('#grade-image-wrapper').html('<img class="custom_media_image" src="" />');
				});
			});
		</script>
		<?php }



		/***********************/


		/*save grade*/
		public function mold_save_grade_meta_box( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( ! isset( $_POST['grade'] ) ) {
				return;
			}
			$grade = sanitize_text_field( $_POST['grade'] );
			
			// A valid grade is required, so don't let this get published without one
			if ( empty( $grade ) ) {
				$postdata = array(
					'ID'          => $post_id,
					'post_status' => 'draft',
				);
				wp_update_post( $postdata );
			} else {
				$term = get_term_by( 'name', $grade, 'grade' );
				if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
					wp_set_object_terms( $post_id, $term->term_id, 'grade', false );
				}
			}
		}

		/*making sure grade is selected*/
		/*
		public function mold_grade_required_field_error_msg( $post ) {
			if ( 'product' === get_post_type( $post ) && 'auto-draft' !== get_post_status( $post ) ) {
				$grade = wp_get_object_terms( $post->ID, 'grade', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
				if ( is_wp_error( $grade ) || empty( $grade ) ) {
					printf(
						'<div class="error below-h2"><p>%s</p></div>',
						esc_html__( 'Grade is mandatory', 'mold-tour' )
					);
				}
			}
		}*/

		/*On edit product*/
		/*grade to radio*/
		public function grade_meta_box( $post ) {
			$terms = get_terms( 'grade', array( 'hide_empty' => false, 'orderby' => 'term_id', 'order' => 'ASC') );

			$post  = get_post();
			$grade = wp_get_object_terms( $post->ID, 'grade', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
			$name  = '';
			if ( ! is_wp_error( $grade ) ) {
				if ( isset( $grade[0] ) && isset( $grade[0]->name ) ) {
					$name = $grade[0]->name;
				}
			}

			foreach ( $terms as $term ) {
				?>
				<label title='<?php $term->name; ?>'>
					<input type="radio" name="grade" value="<?php echo esc_attr($term->name); ?>" <?php checked( $term->name, $name ); ?>>
					<span><?php echo esc_html($term->name); ?></span>
				</label><br>
				<?php
			}
		}


	}
	$mold_grade= new Mold_Grade();

}



/*adding icon column to term list*/
add_filter('manage_edit-grade_columns', 'mold_add_grade_icon_column' );
function mold_add_grade_icon_column( $columns ){
	$columns['grade_icon'] = esc_html__( 'Icon', 'mold-tour' );
	$columns['grade_image'] = esc_html__( 'Image', 'mold-tour' );
	return $columns;
}

add_filter('manage_grade_custom_column', 'mold_add_grade_icon_column_content', 10, 3 );
function mold_add_grade_icon_column_content( $content, $column_name, $term_id ){
	$term_id = absint( $term_id );
	$grade_icon = get_term_meta( $term_id, 'grade-icon-id', true );
	$grade_image = get_term_meta( $term_id, 'grade-image-id', true );

	switch( $column_name ){
		case 'grade_icon' :
		if($grade_icon != ''){
			echo '<span class="' . esc_attr($grade_icon) . '" style="font-size: 30px;"></span>';
		}
		else{
			echo '--';
		}
		break;
		case 'grade_image' :
		if ( $grade_image ) {
			$grade_img_url = wp_get_attachment_image_src ( $grade_image, 'thumbnail' );
			echo '<img src="' . esc_url($grade_img_url[0]) . '" style="width: 60px; height: 60px"/>';
		}
		else{
			echo '--';
		}
		break;
	}	
}
