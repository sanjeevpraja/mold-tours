<?php
/**
 * Custom Post Type: Member
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
        'insert_into_item'      => __('Insert into member', 'wp-mold'),
        'uploaded_to_this_item' => __('Uploaded to this member', 'wp-mold'),
        'items_list'            => __('Members list', 'wp-mold'),
        'items_list_navigation' => __('Members list navigation', 'wp-mold'),
        'filter_items_list'     => __('Filter members list', 'wp-mold'),
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

// Save Meta Box Data
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

    // Save meta data
    $fields = array(
        'days',
        'nights',
        'price',
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
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

// Flush rewrite rules on activation (add this to your plugin activation hook or run once)
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

    foreach ( $fields as $field_name ) {
        // Register the meta field for Gutenberg/REST API.
        register_post_meta( 'tour', '_' . $field_name, array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
        ) );


        // Add a shortcode for each field with post_id parameter
        add_shortcode( $field_name, function( $atts ) use ($field_name) {
            $atts = shortcode_atts( array(
                'post_id' => get_the_ID(),
            ), $atts );

            $meta_value = get_post_meta( $atts['post_id'], '_' . $field_name, true );
            return esc_html( $meta_value );
        });
    }
}
add_action( 'init', 'register_tour_meta_fields' );


// admin list view
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
?>