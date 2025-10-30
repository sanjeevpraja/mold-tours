<?php

/**
 * Create a taxonomy
 */


if (! class_exists('Mold_Accomodation')) {

	class Mold_Accomodation
	{

		public function __construct()
		{
			add_action('init',  array($this, 'mold_accomodation_taxonomies'));
			add_action('save_post_product',  array($this, 'mold_save_accomodation_meta_box'), 10, 1);

			add_action('accomodation_add_form_fields', array($this, 'mold_add_accomodation_image'));
			add_action('created_accomodation', array($this, 'mold_save_accomodation_image'), 10, 1);
			add_action('accomodation_edit_form_fields', array($this, 'mold_update_accomodation_image'), 10, 2);
			add_action('edited_accomodation', array($this, 'mold_edit_accomodation_image'), 10, 1);


			add_action('accomodation_add_form_fields', array($this, 'mold_add_map_image'));
			add_action('created_accomodation', array($this, 'mold_save_map_image'), 10, 1);
			add_action('accomodation_edit_form_fields', array($this, 'mold_update_map_image'), 10, 2);
			add_action('edited_accomodation', array($this, 'mold_edit_map_image'), 10, 1);

			// Enqueue media scripts on taxonomy pages
      		add_action( 'admin_enqueue_scripts', array ( $this, 'mold_enqueue_media' ) );
			add_action('admin_footer', array($this, 'mold_accomodation_add_script'));
		}


		/*register taxonomy*/
		public function mold_accomodation_taxonomies()
		{
			$labels = array(
				'name'					=> esc_html_x('Accomodations', 'Taxonomy Accomodations', 'mold-tour'),
				'singular_name'			=> esc_html_x('Accomodation', 'Taxonomy Accomodation', 'mold-tour'),
				'search_items'			=> esc_html__('Search Accomodations', 'mold-tour'),
				'popular_items'			=> esc_html__('Popular Accomodations', 'mold-tour'),
				'all_items'				=> esc_html__('All Accomodations', 'mold-tour'),
				'parent_item'			=> esc_html__('Parent Accomodation', 'mold-tour'),
				'parent_item_colon'		=> esc_html__('Parent Accomodation', 'mold-tour'),
				'edit_item'				=> esc_html__('Edit Accomodation', 'mold-tour'),
				'update_item'			=> esc_html__('Update Accomodation', 'mold-tour'),
				'add_new_item'			=> esc_html__('Add New Accomodation', 'mold-tour'),
				'new_item_name'			=> esc_html__('New Accomodation Name', 'mold-tour'),
				'add_or_remove_items'	=> esc_html__('Add or remove Accomodations', 'mold-tour'),
				'choose_from_most_used'	=> esc_html__('Choose from most used Accomodation', 'mold-tour'),
				'menu_name'				=> esc_html__('Accomodation', 'mold-tour'),
			);

			$args = array(
				'labels'            => $labels,
				'public'            => true,
				'show_in_nav_menus' => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'hierarchical'      => false, //keep true (acts like categories)
				'show_tagcloud'     => true,
				'query_var'         => true,
				'rewrite'           => array('slug' => 'accomodation'),

				//Required for Gutenberg and WooCommerce block-based product editor
				'show_in_rest'      => true,
				'rest_base'         => 'product-accomodations',
				'rest_controller_class' => 'WP_REST_Terms_Controller',

				//Clean capabilities placeholder
				'capabilities'      => array(),
			);

			register_taxonomy('accomodation', array('tour'), $args);
		}


		/*******************/

		/*
		  * Add image field in the new accomodation
		  * @since 1.0.0
		*/
		public function mold_add_accomodation_image($taxonomy)
		{ ?>
			<div class="form-field term-group">
				<label for="accomodation-image-id"><?php esc_html_e('Image', 'mold-tour'); ?></label>
				<input type="hidden" id="accomodation-image-id" name="accomodation-image-id" class="custom_media_url" value="">
				<div id="accomodation-image-wrapper"></div>
				<p>
					<input type="button" class="button button-secondary accomodation_image_add" id="accomodation_image_add" name="accomodation_image_add" value="<?php esc_attr_e('Add Image', 'mold-tour'); ?>" />
					<input type="button" class="button button-secondary accomodation_image_remove" id="accomodation_image_remove" name="accomodation_image_remove" value="<?php esc_attr_e('Remove Image', 'mold-tour'); ?>" />
				</p>
			</div>
		<?php
		}

		/*
		  * Save image field
		  * @since 1.0.0
		*/
		public function mold_save_accomodation_image($term_id)
		{
			if (isset($_POST['accomodation-image-id']) && '' !== $_POST['accomodation-image-id']) {
				$image = $_POST['accomodation-image-id'];
				add_term_meta($term_id, 'accomodation-image-id', $image, true);
			}
		}

		/*
		  * Edit image field
		  * @since 1.0.0
		*/
		public function mold_update_accomodation_image($term, $taxonomy)
		{ ?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="accomodation-image-id"><?php esc_html_e('Image', 'mold-tour'); ?></label>
				</th>
				<td>
					<?php $image_id = get_term_meta($term->term_id, 'accomodation-image-id', true); ?>
					<input type="hidden" id="accomodation-image-id" name="accomodation-image-id" value="<?php echo esc_attr($image_id); ?>">
					<div id="accomodation-image-wrapper">
						<?php if ($image_id) { ?>
							<?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
						<?php } ?>
					</div>
					<p>
						<input type="button" class="button button-secondary accomodation_image_add" id="accomodation_image_add" name="accomodation_image_add" value="<?php esc_attr_e('Add Image', 'mold-tour'); ?>" />
						<input type="button" class="button button-secondary accomodation_image_remove" id="accomodation_image_remove" name="accomodation_image_remove" value="<?php esc_attr_e('Remove Image', 'mold-tour'); ?>" />
					</p>
				</td>
			</tr>
		<?php
		}

		/*
		 * Edit image field value
		 * @since 1.0.0
		 */
		public function mold_edit_accomodation_image($term_id)
		{
			if (isset($_POST['accomodation-image-id']) && '' !== $_POST['accomodation-image-id']) {
				$image = $_POST['accomodation-image-id'];
				update_term_meta($term_id, 'accomodation-image-id', $image);
			} else {
				update_term_meta($term_id, 'accomodation-image-id', '');
			}
		}



		/**********************
		 **********************/


		/*
		  * Add Map image in the new accomodation
		  * @since 1.0.0
		*/
		public function mold_add_map_image($taxonomy)
		{ ?>
			<div class="form-field term-group">
				<label for="map-image-id"><?php esc_html_e('Map', 'mold-tour'); ?></label>
				<input type="hidden" id="map-image-id" name="map-image-id" class="custom_media_url" value="">
				<div id="map-image-wrapper"></div>
				<p>
					<input type="button" class="button button-secondary map_image_add" id="map_image_add" name="map_image_add" value="<?php esc_attr_e('Add Image', 'mold-tour'); ?>" />
					<input type="button" class="button button-secondary map_image_remove" id="map_image_remove" name="map_image_remove" value="<?php esc_attr_e('Remove Image', 'mold-tour'); ?>" />
				</p>
			</div>
		<?php
		}

		/*
		  * Save Map image
		  * @since 1.0.0
		*/
		public function mold_save_map_image($term_id)
		{
			if (isset($_POST['map-image-id']) && '' !== $_POST['map-image-id']) {
				$image = $_POST['map-image-id'];
				add_term_meta($term_id, 'map-image-id', $image, true);
			}
		}

		/*
		  * Edit Map image
		  * @since 1.0.0
		*/
		public function mold_update_map_image($term, $taxonomy)
		{ ?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="map-image-id"><?php esc_html_e('Map', 'mold-tour'); ?></label>
				</th>
				<td>
					<?php $image_id = get_term_meta($term->term_id, 'map-image-id', true); ?>
					<input type="hidden" id="map-image-id" name="map-image-id" value="<?php echo $image_id; ?>">
					<div id="map-image-wrapper">
						<?php if ($image_id) { ?>
							<?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
						<?php } ?>
					</div>
					<p>
						<input type="button" class="button button-secondary map_image_add" id="map_image_add" name="map_image_add" value="<?php esc_attr_e('Add Image', 'mold-tour'); ?>" />
						<input type="button" class="button button-secondary map_image_remove" id="map_image_remove" name="map_image_remove" value="<?php esc_attr_e('Remove Image', 'mold-tour'); ?>" />
					</p>
				</td>
			</tr>
		<?php
		}

		/*
		 * update Map image value
		 * @since 1.0.0
		 */
		public function mold_edit_map_image($term_id)
		{
			if (isset($_POST['map-image-id']) && '' !== $_POST['map-image-id']) {
				$image = $_POST['map-image-id'];
				update_term_meta($term_id, 'map-image-id', $image);
			} else {
				update_term_meta($term_id, 'map-image-id', '');
			}
		}


		  /**
         * Enqueue media scripts for taxonomy pages
         */
        public function mold_enqueue_media() {
            $screen = get_current_screen();
            if ( $screen && $screen->taxonomy === 'accomodation' ) {
                wp_enqueue_media();
            }
        }

				
		/*
		 * Add script image add/remove btn
		 * @since 1.0.0
		 */

		public function mold_accomodation_add_script()
		{ ?>
			<script>
				jQuery(document).ready(function($) {
					// Only run on accomodation taxonomy pages
					if (!$('body').hasClass('taxonomy-accomodation')) {
						return;
					}

					function accomodation_image_upload(button_class) {
						$('body').on('click', button_class, function(e) {
							e.preventDefault();

							var button_id = '#' + $(this).attr('id');
							var button = $(button_id);

							// Make sure wp.media is available
							if (typeof wp.media === 'undefined') {
								console.error('wp.media is not available');
								return false;
							}

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

							frame.on('select', function() {
								var attachment = frame.state().get('selection').first().toJSON();
								$('#accomodation-image-id').val(attachment.id);
								$('#accomodation-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
								$('#accomodation-image-wrapper .custom_media_image').attr('src', attachment.sizes.thumbnail.url).css('display', 'block');
							});

							frame.open();
							return false;
						});
					}

					accomodation_image_upload('.accomodation_image_add.button');

					$('body').on('click', '.accomodation_image_remove', function() {
						$('#accomodation-image-id').val('');
						$('#accomodation-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
					});

					/***********/

					function map_image_upload(button_class) {
						$('body').on('click', button_class, function(e) {
							e.preventDefault();

							var button_id = '#' + $(this).attr('id');

							// Make sure wp.media is available
							if (typeof wp.media === 'undefined') {
								console.error('wp.media is not available');
								return false;
							}

							var frame = wp.media({
								title: 'Select or Upload Map Image',
								library: {
									type: 'image'
								},
								button: {
									text: 'Use this image'
								},
								multiple: false
							});

							frame.on('select', function() {
								var attachment = frame.state().get('selection').first().toJSON();
								$('#map-image-id').val(attachment.id);
								$('#map-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
								$('#map-image-wrapper .custom_media_image').attr('src', attachment.sizes.thumbnail.url).css('display', 'block');
							});

							frame.open();
							return false;
						});
					}

					map_image_upload('.map_image_add.button');

					$('body').on('click', '.map_image_remove', function() {
						$('#map-image-id').val('');
						$('#map-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
					});
				});
			</script>
<?php
		}



		/**********************/
		/*Save Loaction*/
		public function mold_save_accomodation_meta_box($post_id)
		{
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}
			if (! isset($_POST['accomodation'])) {
				return;
			}
			$accomodation = sanitize_text_field($_POST['accomodation']);

			// A valid accomodation is required, so don't let this get published without one
			if (empty($accomodation)) {
				$postdata = array(
					'ID'          => $post_id,
					'post_status' => 'draft',
				);
				wp_update_post($postdata);
			} else {
				$term = get_term_by('name', $accomodation, 'accomodation');
				if (! empty($term) && ! is_wp_error($term)) {
					wp_set_object_terms($post_id, $term->term_id, 'accomodation', false);
				}
			}
		}
	}

	$mold_accomodation = new Mold_Accomodation();
}



/*adding icon column to term list*/
add_filter('manage_edit-accomodation_columns', 'mold_add_accomodation_icon_column');
function mold_add_accomodation_icon_column($columns)
{
	$columns['accomodation_image'] = esc_html__('Image', 'mold-tour');
	$columns['map_image'] = esc_html__('Map', 'mold-tour');
	return $columns;
}

add_filter('manage_accomodation_custom_column', 'mold_add_accomodation_icon_column_content', 10, 3);
function mold_add_accomodation_icon_column_content($content, $column_name, $term_id)
{
	$term_id = absint($term_id);
	$accomodation_image = get_term_meta($term_id, 'accomodation-image-id', true);
	$map_image = get_term_meta($term_id, 'map-image-id', true);

	switch ($column_name) {
		case 'accomodation_image':
			if ($accomodation_image) {
				$accomodation_img_url = wp_get_attachment_image_src($accomodation_image, 'thumbnail');
				echo '<img src="' . $accomodation_img_url[0] . '" style="width: 60px; height: 60px"/>';
			} else {
				echo '--';
			}
			break;
		case 'map_image':
			if ($map_image) {
				$map_img_url = wp_get_attachment_image_src($map_image, 'thumbnail');
				echo '<img src="' . $map_img_url[0] . '" style="width: 60px; height: 60px"/>';
			} else {
				echo '--';
			}
			break;
	}
}
