<?php
/**
 * Custom Post Type: Tour
 * Add this code to your theme's functions.php file or create a plugin
 */

// Register Custom Post Type
function create_tour_post_type() {
    $labels = array(
        'name'                  => _x('Tours', 'Post Type General Name', 'wp-mold'),
        'singular_name'         => _x('Tour', 'Post Type Singular Name', 'wp-mold'),
        'menu_name'             => __('Tours', 'wp-mold'),
        'name_admin_bar'        => __('Tour', 'wp-mold'),
        'archives'              => __('Tour Archives', 'wp-mold'),
        'attributes'            => __('Tour Attributes', 'wp-mold'),
        'parent_item_colon'     => __('Parent Tour:', 'wp-mold'),
        'all_items'             => __('All Tours', 'wp-mold'),
        'add_new_item'          => __('Add New Tour', 'wp-mold'),
        'add_new'               => __('Add New', 'wp-mold'),
        'new_item'              => __('New Tour', 'wp-mold'),
        'edit_item'             => __('Edit Tour', 'wp-mold'),
        'update_item'           => __('Update Tour', 'wp-mold'),
        'view_item'             => __('View Tour', 'wp-mold'),
        'view_items'            => __('View Tours', 'wp-mold'),
        'search_items'          => __('Search Tour', 'wp-mold'),
        'not_found'             => __('Not found', 'wp-mold'),
        'not_found_in_trash'    => __('Not found in Trash', 'wp-mold'),
        'featured_image'        => __('Featured Image', 'wp-mold'),
        'set_featured_image'    => __('Set featured image', 'wp-mold'),
        'remove_featured_image' => __('Remove featured image', 'wp-mold'),
        'use_featured_image'    => __('Use as featured image', 'wp-mold'),
        'insert_into_item'      => __('Insert into tour', 'wp-mold'), // Fixed: was 'member'
        'uploaded_to_this_item' => __('Uploaded to this tour', 'wp-mold'), // Fixed: was 'member'
        'items_list'            => __('Tours list', 'wp-mold'), // Fixed: was 'Members list'
        'items_list_navigation' => __('Tours list navigation', 'wp-mold'), // Fixed: was 'Members list navigation'
        'filter_items_list'     => __('Filter tours list', 'wp-mold'), // Fixed: was 'Filter members list'
    );

    $args = array(
        'label'                 => __('Tour', 'wp-mold'),
        'description'           => __('Tour information', 'wp-mold'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-groups',
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

// Add Meta Boxes
function add_tour_meta_boxes() {
    add_meta_box(
        'tour_details',
        __('Tour Details', 'wp-mold'),
        'tour_details_callback',
        'tour',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_tour_meta_boxes');

// Meta Box Callback Function
function tour_details_callback($post) {
    // Add nonce field for security
    wp_nonce_field('tour_meta_box', 'tour_meta_box_nonce');

    // Get current values
    $days = get_post_meta($post->ID, '_tour_days', true);
    $nights = get_post_meta($post->ID, '_tour_nights', true);
    $price = get_post_meta($post->ID, '_tour_price', true);

    ?>
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="tour_days"><?php _e('Days', 'wp-mold'); ?></label></th>
                <td><input type="text" id="tour_days" name="tour_days" value="<?php echo esc_attr($days); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="tour_nights"><?php _e('Nights', 'wp-mold'); ?></label></th>
                <td><input type="text" id="tour_nights" name="tour_nights" value="<?php echo esc_attr($nights); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="tour_price"><?php _e('Price', 'wp-mold'); ?></label></th>
                <td><input type="text" id="tour_price" name="tour_price" value="<?php echo esc_attr($price); ?>" class="regular-text" /></td>
            </tr>
        </tbody>
    </table>
    <?php
}

// Save Meta Box Data - FIXED VERSION
function save_tour_meta_box_data($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['tour_meta_box_nonce']) || !wp_verify_nonce($_POST['tour_meta_box_nonce'], 'tour_meta_box')) {
        return;
    }

    // Check if user has permissions to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if this is the correct post type
    if (get_post_type($post_id) !== 'tour') {
        return;
    }

    // Save meta data - FIXED: using correct field names with 'tour_' prefix
    $fields = array(
        'tour_days' => '_tour_days',
        'tour_nights' => '_tour_nights',
        'tour_price' => '_tour_price',
    );

    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $meta_key, $value);
        }
    }
}
add_action('save_post', 'save_tour_meta_box_data');

function get_tour_meta($post_id, $field) {
    return get_post_meta($post_id, '_tour_' . $field, true);
}

function display_tour_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $fields = array(
        'days' => __('Days', 'wp-mold'),
        'nights' => __('Nights', 'wp-mold'),
        'price' => __('Price', 'wp-mold'),
    );

    echo '<div class="cpt-info">';
    foreach ($fields as $field => $label) {
        $value = get_tour_meta($post_id, $field);
        if (!empty($value)) {
            echo '<div class="cpt-field">';
            echo '<strong>' . esc_html($label) . ':</strong> ';
            echo esc_html($value);
            echo '</div>';
        }
    }
    echo '</div>';
}

// Flush rewrite rules on activation
function tour_flush_rewrite_rules() {
    create_tour_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'tour_flush_rewrite_rules');

function register_tour_meta_fields() {
    $fields = array(
        'days',
        'nights',
        'price',
    );

    foreach ($fields as $field_name) {
        // Register the meta field for Gutenberg/REST API
        register_post_meta('tour', '_tour_' . $field_name, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            },
        ));

        // Add a shortcode for each field with post_id parameter
        add_shortcode('tour_' . $field_name, function($atts) use ($field_name) {
            $atts = shortcode_atts(array(
                'post_id' => get_the_ID(),
            ), $atts);

            $meta_value = get_post_meta($atts['post_id'], '_tour_' . $field_name, true);
            return esc_html($meta_value);
        });
    }
}
add_action('init', 'register_tour_meta_fields');

// Admin list view
function tour_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['days'] = __('Days', 'wp-mold');
    $new_columns['nights'] = __('Nights', 'wp-mold');
    $new_columns['price'] = __('Price', 'wp-mold');
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_tour_posts_columns', 'tour_admin_columns');

// Display custom columns in admin
function tour_custom_columns($column, $post_id) {
    switch ($column) {
        case 'days':
            echo esc_html(get_post_meta($post_id, '_tour_days', true));
            break;
        case 'nights':
            echo esc_html(get_post_meta($post_id, '_tour_nights', true));
            break;
        case 'price':
            echo esc_html(get_post_meta($post_id, '_tour_price', true));
            break;
    }
}
add_action('manage_tour_posts_custom_column', 'tour_custom_columns', 10, 2);

// Make columns sortable
function tour_sortable_columns($columns) {
    $columns['days'] = 'days';
    $columns['nights'] = 'nights';
    $columns['price'] = 'price';
    return $columns;
}
add_filter('manage_edit-tour_sortable_columns', 'tour_sortable_columns');

// The rest of your gallery code remains the same...
// [Your existing gallery code here]



/*adding tour gallery*/
add_action( 'add_meta_boxes', function() {
	if ( post_type_exists( 'tour' ) ) {
		add_meta_box(
			'tour_gallery',
			__( 'Tour Gallery', 'textdomain' ),
			'tour_gallery_metabox_callback',
			'tour',
			'side',
			'low'
		);
	}
});

function tour_gallery_metabox_callback( $post ) {
	wp_nonce_field( 'tour_gallery_nonce', 'tour_gallery_nonce_field' );

	$image_ids = get_post_meta( $post->ID, '_tour_gallery', true );
	$attachments = array_filter( explode( ',', $image_ids ) );
	?>
	<div id="tour_images_container" class="product_images_container">
		<ul class="product_images">
			<?php
			foreach ( $attachments as $image_id ) {
				$image = wp_get_attachment_image( $image_id, 'thumbnail' );
				echo '<li class="image" data-attachment_id="' . esc_attr( $image_id ) . '">' . $image . '
					<ul class="actions">
						<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Remove image', 'textdomain' ) . '">×</a></li>
					</ul>
				</li>';
			}
			?>
		</ul>
		<input type="hidden" id="tour_gallery_ids" name="tour_gallery_ids" value="<?php echo esc_attr( $image_ids ); ?>" />
	</div>

	<p class="add_tour_images hide-if-no-js">
		<a href="#" class="button"><?php esc_html_e( 'Add gallery images', 'textdomain' ); ?></a>
	</p>


    <?php
        $menu_bg = get_post_meta($post->ID, 'mold_slider_bg', true);
        $slider_effect = get_post_meta($post->ID, 'mold_slider_effect', true);
        $slider_height = get_post_meta($post->ID, 'mold_slider_height', true);
        $slider_nav = get_post_meta($post->ID, 'mold_slider_nav', true);
        $slider_indicator = get_post_meta($post->ID, 'mold_slider_indicator', true);
        $slider_speed = get_post_meta($post->ID, 'mold_slider_speed', true);

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
            <label class="post-attributes-label" for="mold_slider_speed"><?php esc_html_e('Slider Speed', 'mold-tour')?></label>
        </p>
        <input type="range" name="mold_slider_speed" id="mold_slider_speed" min="0" max="10" value="<?php echo esc_attr( $slider_speed ); ?>">



	<script>
	jQuery(function($){
		var frame;
		var $image_gallery_ids = $('#tour_gallery_ids');
		var $product_images = $('#tour_images_container ul.product_images');

		$('.add_tour_images').on('click', 'a', function(e){
			e.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: '<?php esc_html_e( 'Add Images to Tour Gallery', 'textdomain' ); ?>',
				button: { text: '<?php esc_html_e( 'Add to gallery', 'textdomain' ); ?>' },
				multiple: true
			});

			frame.on('select', function(){
				var selection = frame.state().get('selection');
				var attachment_ids = $image_gallery_ids.val() ? $image_gallery_ids.val().split(',') : [];

				selection.map(function(attachment){
					attachment = attachment.toJSON();
					if (attachment.id) {
						attachment_ids.push(attachment.id);
						$product_images.append(
							'<li class="image" data-attachment_id="'+attachment.id+'">'+
								'<img src="'+attachment.sizes.thumbnail.url+'" />'+
								'<ul class="actions"><li><a href="#" class="delete">×</a></li></ul>'+
							'</li>'
						);
					}
				});
				$image_gallery_ids.val(attachment_ids.join(','));
			});

			frame.open();
		});

		// Delete image
		$('#tour_images_container').on('click', '.delete', function(e){
			e.preventDefault();
			var $li = $(this).closest('li.image');
			var attachment_id = $li.data('attachment_id');

			$li.remove();

			var attachment_ids = [];
			$('#tour_images_container ul li.image').each(function(){
				attachment_ids.push($(this).data('attachment_id'));
			});
			$image_gallery_ids.val(attachment_ids.join(','));
		});

		// Sortable
		$('#tour_images_container ul.product_images').sortable({
			items: 'li.image',
			cursor: 'move',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			forceHelperSize: false,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'sortable-placeholder',
			start: function(event, ui){
				ui.item.css('background-color','#f6f6f6');
			},
			stop: function(event, ui){
				ui.item.removeAttr('style');
				var attachment_ids = [];
				$('#tour_images_container ul li.image').each(function(){
					attachment_ids.push($(this).data('attachment_id'));
				});
				$image_gallery_ids.val(attachment_ids.join(','));
			}
		});
	});
	</script>
	<?php
}
add_action( 'save_post_tour', function( $post_id ) {
	if ( ! isset( $_POST['tour_gallery_nonce_field'] ) ||
	     ! wp_verify_nonce( $_POST['tour_gallery_nonce_field'], 'tour_gallery_nonce' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	if ( isset( $_POST['tour_gallery_ids'] ) ) {
		update_post_meta( $post_id, '_tour_gallery', sanitize_text_field( $_POST['tour_gallery_ids'] ) );
	}
    if ( isset( $_POST['mold_slider_bg'] ) ) {
		update_post_meta( $post_id, 'mold_slider_bg', sanitize_text_field( $_POST['mold_slider_bg'] ) );
	}
    if ( isset( $_POST['mold_slider_effect'] ) ) {
		update_post_meta( $post_id, 'mold_slider_effect', sanitize_text_field( $_POST['mold_slider_effect'] ) );
	}
    if ( isset( $_POST['mold_slider_height'] ) ) {
		update_post_meta( $post_id, 'mold_slider_height', sanitize_text_field( $_POST['mold_slider_height'] ) );
	}
    if ( isset( $_POST['mold_slider_nav'] ) ) {
		update_post_meta( $post_id, 'mold_slider_nav', sanitize_text_field( $_POST['mold_slider_nav'] ) );
	}
    if ( isset( $_POST['mold_slider_indicator'] ) ) {
		update_post_meta( $post_id, 'mold_slider_indicator', sanitize_text_field( $_POST['mold_slider_indicator'] ) );
	}
    if ( isset( $_POST['mold_slider_speed'] ) ) {
		update_post_meta( $post_id, 'mold_slider_speed', sanitize_text_field( $_POST['mold_slider_speed'] ) );
	}
});

?>