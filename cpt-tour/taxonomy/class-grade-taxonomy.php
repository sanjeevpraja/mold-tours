<?php
if ( ! class_exists( 'Mold_Grade' ) ) {
	class Mold_Grade {
		public function __construct() {
			register_activation_hook( MOLD_TOUR_MAIN_FILE_URL ,array($this,'mold_activate'));
			add_action( 'init', array ( $this, 'mold_grade_taxonomies') );
			
			// Add meta box for block editor compatibility
			add_action( 'add_meta_boxes', array( $this, 'mold_add_grade_meta_box' ) );
			add_action( 'save_post_tour', array ( $this,'mold_save_grade_meta_box') );

			add_action( 'grade_add_form_fields', array ( $this, 'mold_add_grade_icon' ));
			add_action( 'created_grade', array ( $this, 'mold_save_grade_icon' ), 10, 1 );
			add_action( 'grade_edit_form_fields', array ( $this, 'mold_update_grade_icon' ), 10, 2);
			add_action( 'edited_grade', array ( $this, 'mold_edit_grade_icon' ), 10, 1);

			add_action( 'grade_add_form_fields', array ( $this, 'mold_add_grade_image' ), 10, 1);
			add_action( 'created_grade', array ( $this, 'mold_save_grade_image' ) , 10, 1);
			add_action( 'grade_edit_form_fields', array ( $this, 'mold_update_grade_image' ), 10, 2);
			add_action( 'edited_grade', array ( $this, 'mold_edit_grade_image' ), 10, 1);
			add_action( 'admin_footer', array ( $this, 'mold_grade_add_script' ));

			// Enqueue admin styles
			add_action( 'admin_enqueue_scripts', array( $this, 'mold_enqueue_admin_styles' ) );
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
		 * Create a taxonomy - Updated for CPT 'tour'
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

				// FSE/Block Editor support
				'show_in_rest'      => false,
				'rest_base'         => 'grades',
				'rest_controller_class' => 'WP_REST_Terms_Controller',

				// Remove default meta box since we're using custom one
				'meta_box_cb'       => false,

				'capabilities'      => array(),
			);

			register_taxonomy( 'grade', array( 'tour' ), $args ); // Only for 'tour' CPT
		}

		/**
		 * Add custom meta box for grade selection (Block Editor Compatible)
		 */
		public function mold_add_grade_meta_box() {
			add_meta_box(
				'mold-grade-selector',
				esc_html__( 'Tour Grade', 'mold-tour' ),
				array( $this, 'mold_grade_meta_box_callback' ),
				'tour',
				'side',
				'high'
			);
		}

		/**
		 * Custom meta box callback with radio buttons
		 */
		public function mold_grade_meta_box_callback( $post ) {
			// Add nonce for security
			wp_nonce_field( 'mold_save_grade_meta', 'mold_grade_nonce' );
			
			$terms = get_terms( array(
				'taxonomy' => 'grade',
				'hide_empty' => false,
				'orderby' => 'term_id',
				'order' => 'ASC'
			) );

			$current_terms = wp_get_object_terms( $post->ID, 'grade', array( 'fields' => 'ids' ) );
			$current_term_id = !empty( $current_terms ) ? $current_terms[0] : '';
			
			echo '<div class="mold-grade-radio-container">';
			
			if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$icon = get_term_meta( $term->term_id, 'grade-icon-id', true );
					$icon_html = $icon ? '<span class="' . esc_attr($icon) . '" style="margin-right: 8px;"></span>' : '';
					
					printf(
						'<label class="mold-grade-option">
							<input type="radio" name="mold_selected_grade" value="%s" %s>
							%s<span class="grade-name">%s</span>
						</label>',
						esc_attr( $term->term_id ),
						checked( $current_term_id, $term->term_id, false ),
						$icon_html,
						esc_html( $term->name )
					);
				}
			} else {
				echo '<p>' . esc_html__( 'No grades found. Please add some grades first.', 'mold-tour' ) . '</p>';
			}
			
			echo '</div>';
			echo '<p class="description">' . esc_html__( 'Select the difficulty grade for this tour', 'mold-tour' ) . '</p>';
		}

		/**
		 * Save grade meta box data
		 */
		public function mold_save_grade_meta_box( $post_id ) {
			// Check nonce
			if ( ! isset( $_POST['mold_grade_nonce'] ) || ! wp_verify_nonce( $_POST['mold_grade_nonce'], 'mold_save_grade_meta' ) ) {
				return;
			}
			
			// Check autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			
			// Check permissions
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
			
			// Save selected grade
			if ( isset( $_POST['mold_selected_grade'] ) && ! empty( $_POST['mold_selected_grade'] ) ) {
				$grade_term_id = intval( $_POST['mold_selected_grade'] );
				wp_set_object_terms( $post_id, $grade_term_id, 'grade', false );
			} else {
				// If no grade selected, remove any existing grade
				wp_delete_object_term_relationships( $post_id, 'grade' );
				
				// Optional: Set post to draft if grade is required
				/*
				$postdata = array(
					'ID' => $post_id,
					'post_status' => 'draft',
				);
				wp_update_post( $postdata );
				*/
			}
		}

		/**
		 * Enqueue admin styles
		 */
		public function mold_enqueue_admin_styles( $hook ) {
			if ( ! in_array( $hook, array( 'post.php', 'post-new.php', 'edit-tags.php', 'term.php' ) ) ) {
				return;
			}
			
			$screen = get_current_screen();
			if ( $screen && ( $screen->post_type === 'tour' || $screen->taxonomy === 'grade' ) ) {
				wp_add_inline_style( 'wp-admin', $this->mold_get_admin_css() );
			}
		}

		/**
		 * Admin CSS for better styling
		 */
		private function mold_get_admin_css() {
			return '
			.mold-grade-radio-container {
				margin: 10px 0;
			}
			
			.mold-grade-option {
				display: flex;
				align-items: center;
				padding: 10px 12px;
				margin-bottom: 8px;
				border: 1px solid #c3c4c7;
				border-radius: 4px;
				background: #fff;
				cursor: pointer;
				transition: all 0.2s ease;
			}
			
			.mold-grade-option:hover {
				background-color: #f0f0f1;
				border-color: #007cba;
			}
			
			.mold-grade-option input[type="radio"]:checked + * {
				font-weight: 600;
			}
			
			.mold-grade-option input[type="radio"]:checked ~ .grade-name {
				font-weight: 600;
				color: #007cba;
			}
			
			.mold-grade-option:has(input[type="radio"]:checked) {
				background-color: #f0f6fc;
				border-color: #007cba;
				box-shadow: 0 0 0 1px #007cba;
			}
			
			.grade-name {
				margin-left: 5px;
			}
			
			/* Block editor specific styles */
			.block-editor-page .mold-grade-option {
				border-color: #8c8f94;
			}
			
			.block-editor-page .mold-grade-option:hover {
				background-color: #f0f0f0;
				border-color: #007cba;
			}
			
			/* Term list table styles */
			.wp-list-table .column-grade_icon,
			.wp-list-table .column-grade_image {
				width: 100px;
				text-align: center;
			}
			';
		}

		/***********************/
		/* ICON FIELD METHODS */
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
				$grade_icon = sanitize_text_field( $_POST['grade-icon-id'] );
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
		 			<option value="" <?php selected( $icon_id, "" ); ?>>--</option>
		 			<option value="icon-level-1" <?php selected( $icon_id, "icon-level-1" ); ?>>icon-level-1</option>
		 			<option value="icon-level-2" <?php selected( $icon_id, "icon-level-2" ); ?>>icon-level-2</option>
		 			<option value="icon-level-3" <?php selected( $icon_id, "icon-level-3" ); ?>>icon-level-3</option>
		 			<option value="icon-level-4" <?php selected( $icon_id, "icon-level-4" ); ?>>icon-level-4</option>
		 			<option value="icon-level-5" <?php selected( $icon_id, "icon-level-5" ); ?>>icon-level-5</option>
		 			<option value="icon-level-6" <?php selected( $icon_id, "icon-level-6" ); ?>>icon-level-6</option>
		 			<option value="icon-level-7" <?php selected( $icon_id, "icon-level-7" ); ?>>icon-level-7</option>
		 			<option value="icon-level-8" <?php selected( $icon_id, "icon-level-8" ); ?>>icon-level-8</option>
		 			<option value="icon-level-9" <?php selected( $icon_id, "icon-level-9" ); ?>>icon-level-9</option>
		 			<option value="icon-level-10" <?php selected( $icon_id, "icon-level-10" ); ?>>icon-level-10</option>
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
				$grade_icon = sanitize_text_field( $_POST['grade-icon-id'] );
				update_term_meta ( $term_id, 'grade-icon-id', $grade_icon );
			} else {
				update_term_meta ( $term_id, 'grade-icon-id', '' );
			}
		}

		/***********************/
		/* IMAGE FIELD METHODS */
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
		 		<input type="button" class="button button-secondary grade_image_remove" id="grade_image_remove" name="grade_image_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
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
		 		$image = absint( $_POST['grade-image-id'] );
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
		 			<input type="button" class="button button-secondary grade_image_remove" id="grade_image_remove" name="grade_image_remove" value="<?php esc_attr_e( 'Remove Image', 'mold-tour' ); ?>" />
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
				$image = absint( $_POST['grade-image-id'] );
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
				$(document).on('click', button_class, function(e) {
					e.preventDefault();

					var button = $(this);
					var wrapper = button.closest('.form-field').find('#grade-image-wrapper');
					var input = button.closest('.form-field').find('#grade-image-id');

					// Check if wp.media is available
					if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
						console.error('WordPress media library is not available');
						return false;
					}

					// Create media frame
					var frame = wp.media({
						title: 'Select or Upload Image',
						library: {
							type: 'image'
						},
						button: {
							text: 'Use this image'
						},
						multiple: false
					});

					// Handle image selection
					frame.on('select', function() {
						var attachment = frame.state().get('selection').first().toJSON();
						input.val(attachment.id);
						wrapper.html('<img class="custom_media_image" src="' + attachment.sizes.thumbnail.url + '" style="max-height:100px;" />');
					});

					// Open media frame
					frame.open();
					return false;
				});
			}

			// Initialize media upload for grade images
			ct_media_upload('.grade_image_add'); 

			// Handle image removal
			$(document).on('click', '.grade_image_remove', function(e) {
				e.preventDefault();
				var wrapper = $(this).closest('.form-field').find('#grade-image-wrapper');
				var input = $(this).closest('.form-field').find('#grade-image-id');
				input.val('');
				wrapper.html('');
			});
		});
		</script>
		<?php }

	}
	$mold_grade = new Mold_Grade();
}

/***********************/
/* ADMIN COLUMN METHODS */
/***********************/

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
				echo '<img src="' . esc_url($grade_img_url[0]) . '" style="width: 60px; height: 60px; border-radius: 4px;"/>';
			}
			else{
				echo '--';
			}
			break;
	}	
}