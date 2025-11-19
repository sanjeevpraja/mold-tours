<?php
// Add Meta Boxes
function add_tour_meta_boxes()
{
    add_meta_box(
        'tour_details',
        __('Tour Details', 'mold-tour'),
        'tour_details_callback',
        'tour',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_tour_meta_boxes');

// Meta Box Callback Function
function tour_details_callback($post)
{
    // Add nonce field for security
    wp_nonce_field('tour_meta_box', 'tour_meta_box_nonce');

    // Get current values
    $group_tour = get_post_meta($post->ID, '_group_tour', true);
    $tailor_tour = get_post_meta($post->ID, '_tailor_tour', true);
    $price = get_post_meta($post->ID, '_tour_price', true);
    $original_price = get_post_meta($post->ID, '_tour_original_price', true);


?>
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="group_tour"><?php _e('Group Tour', 'mold-tour'); ?></label></th>
                <td><input type="checkbox" id="group_tour" name="group_tour" <?php checked($group_tour, 'yes'); ?> /></td>
            </tr>
            <tr>
                <th scope="row"><label for="tailor_tour"><?php _e('Tailor-Made Tour', 'mold-tour'); ?></label></th>
                <td><input type="checkbox" id="tailor_tour" name="tailor_tour" <?php checked($tailor_tour, 'yes'); ?> /></td>
            </tr>
            <tr>
                <th scope="row"><label for="tour_price"><?php _e('Price', 'mold-tour'); ?></label></th>
                <td><input type="number" id="tour_price" name="tour_price" value="<?php echo esc_attr($price); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="tour_original_price" style="text-decoration: line-through;"><?php _e('Original Price', 'mold-tour'); ?></label></th>
                <td><input type="number" id="tour_original_price" name="tour_original_price" value="<?php echo esc_attr($original_price); ?>" class="regular-text" /></td>
            </tr>
        </tbody>
    </table>
<?php
}

// Save Meta Box Data - FIXED VERSION
function save_tour_meta_box_data($post_id)
{
    if (!isset($_POST['tour_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['tour_meta_box_nonce'], 'tour_meta_box')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'tour') return;

    // Checkbox fields: save "yes" or "no"
    $checkboxes = [
        'group_tour' => '_group_tour',
        'tailor_tour' => '_tailor_tour',
    ];

    foreach ($checkboxes as $field => $meta_key) {
        $value = isset($_POST[$field]) ? 'yes' : 'no';
        update_post_meta($post_id, $meta_key, $value);
    }

    // Number fields
    $numbers = [
        'tour_price' => '_tour_price',
        'tour_original_price' => '_tour_original_price'
    ];

    foreach ($numbers as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
}

add_action('save_post', 'save_tour_meta_box_data');

function get_tour_meta($post_id, $field)
{
    return get_post_meta($post_id, '_tour_' . $field, true);
}

function display_tour_info($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $fields = array(
        'group_tour' => __('Group Tour', 'mold-tour'),
        'tailor_tour' => __('Tailor-Made Tour', 'mold-tour'),
        'price' => __('Price', 'mold-tour'),
        'original_price' => __('Original Price', 'mold-tour'),
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
function tour_flush_rewrite_rules()
{
    create_tour_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'tour_flush_rewrite_rules');

function register_tour_meta_fields()
{
    $fields = array(
        'group_tour',
        'tailor_tour',
        'price',
        'original_price',
    );

    foreach ($fields as $field_name) {
        // Register the meta field for Gutenberg/REST API
        register_post_meta('tour', '_tour_' . $field_name, array(
            'show_in_rest' => true,
            'single' => true,
            'type' => 'string',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ));

        // Add a shortcode for each field with post_id parameter
        add_shortcode('tour_' . $field_name, function ($atts) use ($field_name) {
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
function tour_admin_columns($columns)
{
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['featured'] = '<span class="dashicons dashicons-star-filled" title="Featured"></span>';
    $new_columns['title'] = $columns['title'];
    $new_columns['group_tour'] = __('Group Tour', 'mold-tour');
    $new_columns['tailor_tour'] = __('Tailor-Made Tour', 'mold-tour');
    $new_columns['price'] = __('Price', 'mold-tour');
    //$new_columns['original_price'] = __('Original Price', 'mold-tour');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter('manage_tour_posts_columns', 'tour_admin_columns');

// Display custom columns in admin
function tour_custom_columns($column, $post_id)
{
    switch ($column) {
        case 'featured':
            $tags = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'names'));
            $is_featured = in_array('featured', $tags, true);
            $icon = $is_featured ? 'dashicons-star-filled' : 'dashicons-star-empty';
            $title = $is_featured ? __('Unmark as featured', 'mold-tour') : __('Mark as featured', 'mold-tour');
            echo '<a href="#" class="tour-featured-toggle" data-post-id="' . esc_attr($post_id) . '" title="' . esc_attr($title) . '">
            <span class="dashicons ' . esc_attr($icon) . '"></span>
          </a>';
            break;
        case 'group_tour':
            $value = get_post_meta($post_id, '_group_tour', true);
            echo $value === 'yes' ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
            break;
        case 'tailor_tour':
            $value = get_post_meta($post_id, '_tailor_tour', true);
            echo $value === 'yes' ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
            break;
        case 'price':
            echo esc_html(get_post_meta($post_id, '_tour_price', true));
            break;
        case 'original_price':
            echo esc_html(get_post_meta($post_id, '_tour_original_price', true));
            break;
    }
}
add_action('manage_tour_posts_custom_column', 'tour_custom_columns', 10, 2);

// Make columns sortable
function tour_sortable_columns($columns)
{
    $columns['group_tour'] = 'group_tour';
    $columns['tailor_tour'] = 'tailor_tour';
    $columns['price'] = 'price';
    return $columns;
}
add_filter('manage_edit-tour_sortable_columns', 'tour_sortable_columns');

?>