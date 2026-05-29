<?php
/**
 * Custom Post Type: Member
 */
 

// Register Custom Post Type
function create_member_post_type() {
    $labels = array(
        'name'                  => _x('Members', 'Post Type General Name', 'mold-tour'),
        'singular_name'         => _x('Member', 'Post Type Singular Name', 'mold-tour'),
        'menu_name'             => __('Members', 'mold-tour'),
        'name_admin_bar'        => __('Member', 'mold-tour'),
        'archives'              => __('Member Archives', 'mold-tour'),
        'attributes'            => __('Member Attributes', 'mold-tour'),
        'parent_item_colon'     => __('Parent Member:', 'mold-tour'),
        'all_items'             => __('All Members', 'mold-tour'),
        'add_new_item'          => __('Add New Member', 'mold-tour'),
        'add_new'               => __('Add New', 'mold-tour'),
        'new_item'              => __('New Member', 'mold-tour'),
        'edit_item'             => __('Edit Member', 'mold-tour'),
        'update_item'           => __('Update Member', 'mold-tour'),
        'view_item'             => __('View Member', 'mold-tour'),
        'view_items'            => __('View Members', 'mold-tour'),
        'search_items'          => __('Search Member', 'mold-tour'),
        'not_found'             => __('Not found', 'mold-tour'),
        'not_found_in_trash'    => __('Not found in Trash', 'mold-tour'),
        'featured_image'        => __('Featured Image', 'mold-tour'),
        'set_featured_image'    => __('Set featured image', 'mold-tour'),
        'remove_featured_image' => __('Remove featured image', 'mold-tour'),
        'use_featured_image'    => __('Use as featured image', 'mold-tour'),
        'insert_into_item'      => __('Insert into member', 'mold-tour'),
        'uploaded_to_this_item' => __('Uploaded to this member', 'mold-tour'),
        'items_list'            => __('Members list', 'mold-tour'),
        'items_list_navigation' => __('Members list navigation', 'mold-tour'),
        'filter_items_list'     => __('Filter members list', 'mold-tour'),
    );

    $args = array(
        'label'                 => __('Member', 'mold-tour'),
        'description'           => __('Member information', 'mold-tour'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes'),
        'taxonomies'            => array(),
        'hierarchical'          => true,
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

    register_post_type('member', $args);
}
add_action('init', 'create_member_post_type', 0);

// Add Meta Boxes
function add_member_meta_boxes() {
    add_meta_box(
        'member_details',
        __('Member Details', 'mold-tour'),
        'member_details_callback',
        'member',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_member_meta_boxes');

// Meta Box Callback Function
function member_details_callback($post) {
    // Add nonce field for security
    wp_nonce_field('member_meta_box', 'member_meta_box_nonce');

    // Get current values
    $designation = get_post_meta($post->ID, '_member_designation', true);
    $gender = get_post_meta($post->ID, '_member_gender', true);
    $university = get_post_meta($post->ID, '_member_university', true);
    $campus_department = get_post_meta($post->ID, '_member_campus_department', true);
    $academic_degree = get_post_meta($post->ID, '_member_academic_degree', true);
    $completed_year = get_post_meta($post->ID, '_member_completed_year', true);
    $thesis_title = get_post_meta($post->ID, '_member_thesis_title', true);
    $supervisor = get_post_meta($post->ID, '_member_supervisor', true);
    $phone = get_post_meta($post->ID, '_member_phone', true);
    $email = get_post_meta($post->ID, '_member_email', true);
    $website = get_post_meta($post->ID, '_member_website', true);
    $linkedin = get_post_meta($post->ID, '_member_linkedin', true);
    $twitter = get_post_meta($post->ID, '_member_twitter', true);
    $facebook = get_post_meta($post->ID, '_member_facebook', true);

    ?>
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row"><label for="member_designation"><?php _e('Designation', 'mold-tour'); ?></label></th>
                <td><input type="text" id="member_designation" name="member_designation" value="<?php echo esc_attr($designation); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_gender"><?php _e('Gender', 'mold-tour'); ?></label></th>
                <td>
                    <select id="member_gender" name="member_gender">
                        <option value=""><?php _e('Select Gender', 'mold-tour'); ?></option>
                        <option value="male" <?php selected($gender, 'male'); ?>><?php _e('Male', 'mold-tour'); ?></option>
                        <option value="female" <?php selected($gender, 'female'); ?>><?php _e('Female', 'mold-tour'); ?></option>
                        <option value="other" <?php selected($gender, 'other'); ?>><?php _e('Other', 'mold-tour'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="member_university"><?php _e('University', 'mold-tour'); ?></label></th>
                <td><input type="text" id="member_university" name="member_university" value="<?php echo esc_attr($university); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_campus_department"><?php _e('Campus/Department', 'mold-tour'); ?></label></th>
                <td><input type="text" id="member_campus_department" name="member_campus_department" value="<?php echo esc_attr($campus_department); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_academic_degree"><?php _e('Academic Degree', 'mold-tour'); ?></label></th>
                <td><input type="text" id="member_academic_degree" name="member_academic_degree" value="<?php echo esc_attr($academic_degree); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_completed_year"><?php _e('Completed Year', 'mold-tour'); ?></label></th>
                <td><input type="number" id="member_completed_year" name="member_completed_year" value="<?php echo esc_attr($completed_year); ?>" min="1900" max="2100" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_thesis_title"><?php _e('Thesis Title', 'mold-tour'); ?></label></th>
                <td><textarea id="member_thesis_title" name="member_thesis_title" rows="3" cols="50" class="large-text"><?php echo esc_textarea($thesis_title); ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_supervisor"><?php _e('Supervisor', 'mold-tour'); ?></label></th>
                <td><input type="text" id="member_supervisor" name="member_supervisor" value="<?php echo esc_attr($supervisor); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_phone"><?php _e('Phone', 'mold-tour'); ?></label></th>
                <td><input type="tel" id="member_phone" name="member_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_email"><?php _e('Email', 'mold-tour'); ?></label></th>
                <td><input type="email" id="member_email" name="member_email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_website"><?php _e('Website', 'mold-tour'); ?></label></th>
                <td><input type="url" id="member_website" name="member_website" value="<?php echo esc_attr($website); ?>" class="regular-text" placeholder="https://" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_linkedin"><?php _e('LinkedIn', 'mold-tour'); ?></label></th>
                <td><input type="url" id="member_linkedin" name="member_linkedin" value="<?php echo esc_attr($linkedin); ?>" class="regular-text" placeholder="https://linkedin.com/in/username" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_twitter"><?php _e('Twitter', 'mold-tour'); ?></label></th>
                <td><input type="url" id="member_twitter" name="member_twitter" value="<?php echo esc_attr($twitter); ?>" class="regular-text" placeholder="https://twitter.com/username" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="member_facebook"><?php _e('Facebook', 'mold-tour'); ?></label></th>
                <td><input type="url" id="member_facebook" name="member_facebook" value="<?php echo esc_attr($facebook); ?>" class="regular-text" placeholder="https://facebook.com/username" /></td>
            </tr>
        </tbody>
    </table>
    <?php
}

// Save Meta Box Data
function save_member_meta_box_data($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['member_meta_box_nonce']) || !wp_verify_nonce($_POST['member_meta_box_nonce'], 'member_meta_box')) {
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
    if (get_post_type($post_id) !== 'member') {
        return;
    }

    // Save meta data
    $fields = array(
        'member_designation',
        'member_gender',
        'member_university',
        'member_campus_department',
        'member_academic_degree',
        'member_completed_year',
        'member_thesis_title',
        'member_supervisor',
        'member_phone',
        'member_email',
        'member_website',
        'member_linkedin',
        'member_twitter',
        'member_facebook'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            if ($field === 'member_thesis_title') {
                $value = sanitize_textarea_field($_POST[$field]);
            }
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'save_member_meta_box_data');

// Admin List view
function member_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['university'] = __('University', 'mold-tour');
    $new_columns['degree'] = __('Degree', 'mold-tour');
    $new_columns['email'] = __('Email', 'mold-tour');
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_member_posts_columns', 'member_admin_columns');

// Populate admin columns
function member_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'university':
            echo esc_html(get_post_meta($post_id, '_member_university', true));
            break;
        case 'degree':
            echo esc_html(get_post_meta($post_id, '_member_academic_degree', true));
            break;
        case 'email':
            $email = get_post_meta($post_id, '_member_email', true);
            if ($email) {
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            }
            break;
    }
}
add_action('manage_member_posts_custom_column', 'member_admin_columns_content', 10, 2);

// Make admin columns sortable
function member_sortable_columns($columns) {
    $columns['university'] = 'university';
    $columns['degree'] = 'degree';
    return $columns;
}
add_filter('manage_edit-member_sortable_columns', 'member_sortable_columns');

// Handle sorting
function member_admin_sort($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') !== 'member') {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'university':
            $query->set('meta_key', '_member_university');
            $query->set('orderby', 'meta_value');
            break;
        case 'degree':
            $query->set('meta_key', '_member_academic_degree');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'member_admin_sort');

// Helper function to get member meta data
function get_member_meta($post_id, $field) {
    return get_post_meta($post_id, '_member_' . $field, true);
}

// Helper function to display member information (for use in templates)
function display_member_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $fields = array(
        'designation' => __('Designation', 'mold-tour'),
        'gender' => __('Gender', 'mold-tour'),
        'university' => __('University', 'mold-tour'),
        'campus_department' => __('Campus/Department', 'mold-tour'),
        'academic_degree' => __('Academic Degree', 'mold-tour'),
        'completed_year' => __('Completed Year', 'mold-tour'),
        'thesis_title' => __('Thesis Title', 'mold-tour'),
        'supervisor' => __('Supervisor', 'mold-tour'),
        'phone' => __('Phone', 'mold-tour'),
        'email' => __('Email', 'mold-tour'),
        'website' => __('Website', 'mold-tour'),
        'linkedin' => __('LinkedIn', 'mold-tour'),
        'twitter' => __('Twitter', 'mold-tour'),
        'facebook' => __('Facebook', 'mold-tour')
    );
    
    echo '<div class="member-info">';
    foreach ($fields as $field => $label) {
        $value = get_member_meta($post_id, $field);
        if (!empty($value)) {
            echo '<div class="member-field">';
            echo '<strong>' . esc_html($label) . ':</strong> ';
            
            // Handle special fields
            if (in_array($field, array('email'))) {
                echo '<a href="mailto:' . esc_attr($value) . '">' . esc_html($value) . '</a>';
            } elseif (in_array($field, array('website', 'linkedin', 'twitter', 'facebook'))) {
                echo '<a href="' . esc_url($value) . '" target="_blank">' . esc_html($value) . '</a>';
            } elseif ($field === 'phone') {
                echo '<a href="tel:' . esc_attr($value) . '">' . esc_html($value) . '</a>';
            } else {
                echo esc_html($value);
            }
            echo '</div>';
        }
    }
    echo '</div>';
}

// Flush rewrite rules on activation (add this to your plugin activation hook or run once)
function member_flush_rewrite_rules() {
    create_member_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'member_flush_rewrite_rules');




function register_member_meta_fields() {
    $fields = array(
        'member_designation',
        'member_gender',
        'member_university',
        'member_campus_department',
        'member_academic_degree',
        'member_completed_year',
        'member_thesis_title',
        'member_supervisor',
        'member_phone',
        'member_email',
        'member_website',
        'member_linkedin',
        'member_twitter',
        'member_facebook'
    );

    foreach ( $fields as $field_name ) {
        // Register the meta field for Gutenberg/REST API.
        register_post_meta( 'member', '_' . $field_name, array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
        ) );

        //Add a shortcode for each field. eg. [member_designation]
        // add_shortcode( $field_name, function() use ($field_name) {
        //     $meta_value = get_post_meta( get_the_ID(), '_' . $field_name, true );
        //     return esc_html( $meta_value );
        // });

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
add_action( 'init', 'register_member_meta_fields' );



?>