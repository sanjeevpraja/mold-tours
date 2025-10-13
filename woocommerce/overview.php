<?php

/**
 * Overview Meta Box
 */

if (!function_exists('mold_add_overview_meta_box')) {
    /**
     * Add overview meta box to products
     */
    function mold_add_overview_meta_box() {
        add_meta_box(
            'mold_overview_meta_box',
            esc_html__('Trip Overview', 'mold-tour'),
            'mold_overview_meta_box_content',
            'product',
            'normal',
            'high'
        );
    }
    add_action('add_meta_boxes', 'mold_add_overview_meta_box');
}

if (!function_exists('mold_overview_meta_box_content')) {
    /**
     * Display overview meta box content
     */
    function mold_overview_meta_box_content($post) {
        // Add nonce for security
        wp_nonce_field('mold_overview_meta_box', 'mold_overview_nonce');
        ?>
        <div class="mold-overview-metabox">
        <div>
                        <h3><?php echo esc_html__('Overview Items', 'mold-tour'); ?></h3>

                        <?php
                        if (!function_exists('is_countable')) {
                            function is_countable($var) {
                                return is_array($var) || $var instanceof Countable;
                            }
                        }

                        $overview = get_post_meta($post->ID, 'mold_trip_overview', true);
                        if (is_countable($overview)) {
                            $overview_count = count($overview);
                        } else {
                            $overview_count = 0;
                        }
                        ?>

                        <div class="overview-form">
                            <input type="hidden" class="form-count" name="form-count" value="<?php echo $overview_count; ?>">

                            <?php if (is_array($overview)):
                                $i = 0;
                                foreach ($overview as $overviewvalue) {
                                    echo '<div class="form-field">';

                                    if (is_array($overviewvalue)) {
                                        foreach ($overviewvalue as $key => $value) {
                                            switch ($key) {
                                                case 'icon':
                                                    if ($value == '') {
                                                        echo '<div class="formicon" data-icon-id="' . $i . '"><i class="icon no-icon" id="formicon' . $i . '" title="' . esc_attr__('Select Icon', 'mold-tour') . '"></i></div>';
                                                    } else {
                                                        echo '<div class="formicon" data-icon-id="' . $i . '"><i class="icon ' . $value . '" id="formicon' . $i . '" title="' .  $value  . '"></i> </div>';
                                                    }
                                                    echo '<input type="hidden" name="formicon' . $i . '" id="formicon_hidden' . $i . '" value="' . esc_attr($value) . '" />';
                                                    break;
                                                case 'title':
                                                    echo '<input type="text" name="formtitle' . $i . '" value="' . esc_attr($value) . '" class="widefat" placeholder="' . esc_attr__('e.g., Duration', 'mold-tour') . '" />';
                                                    break;
                                                case 'value':
                                                    echo '<input type="text" name="formvalue' . $i . '" value="' . esc_attr($value) . '" class="widefat" placeholder="' . esc_attr__('e.g., 5 Days', 'mold-tour') . '" />';
                                                    break;
                                            }
                                        }
                                    }
                                    echo '<button type="button" class="btn-delete" title="' . esc_attr__('Remove Item', 'mold-tour') . '"></button>';
                                    echo '</div>';
                                    $i = $i + 1;
                                }
                            else: ?>
                                <div class="form-field">
                                    <div class="formicon" data-icon-id="0"><i class="no-icon icon" id="formicon0"></i> <?php esc_html_e('Select Icon', 'mold-tour'); ?></div>
                                    <input type="hidden" name="formicon0" id="formicon_hidden0" value="" />

                                    <p><strong><?php esc_html_e('Title', 'mold-tour'); ?>:</strong>
                                    <input type="text" name="formtitle0" value="" class="widefat" placeholder="<?php esc_attr_e('e.g., Duration', 'mold-tour'); ?>" />
                                    </p>

                                    <p><strong><?php esc_html_e('Value', 'mold-tour'); ?>:</strong>
                                    <input type="text" name="formvalue0" value="" class="widefat" placeholder="<?php esc_attr_e('e.g., 5 Days', 'mold-tour'); ?>" />
                                    </p>

                                    <button type="button" class=" btn-delete" title="<?php esc_attr_e('Remove Item', 'mold-tour'); ?>"></button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="mold-popup-overview" class="mold-modal hidden">
                            <div class="mold-modal-content">
                                <span class="close-modal">&times;</span>
                                <input type="hidden" id="icon-holder-overview" name="icon-holder-overview" value="">
                                <div class="icon-select">
                                    <input id="search-overview" class="search-input" name="search-1" placeholder="<?php esc_attr_e('Search icons...', 'mold-tour'); ?>" type="text" data-toggle="hideseek" data-list="#icon-list-overview" autocomplete="off">
                                    <div class="icon-list-wrap">
                                        <ul id="icon-list-overview" class="iconlist"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btn-wrap">
                            <button type="button" id="add-overview-info" class="button button-primary"><?php esc_attr_e('Add New Overview Item', 'mold-tour'); ?></button>
                        </div>

                    </div>
        </div>
        
        <?php
    }
}

if (!function_exists('mold_save_overview_meta_box')) {
    /**
     * Save overview meta box data
     */
    function mold_save_overview_meta_box($post_id) {
        // Check nonce
        if (!isset($_POST['mold_overview_nonce']) || !wp_verify_nonce($_POST['mold_overview_nonce'], 'mold_overview_meta_box')) {
            return;
        }
        
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        

        
        // Save overview items
        if (isset($_POST['form-count'])) {
            $form_count = intval($_POST['form-count']);
            $tempArray = array();
            
            for ($i = 0; $i <= $form_count; $i++) {
                $form_icon = isset($_POST['formicon' . $i]) ? sanitize_text_field($_POST['formicon' . $i]) : '';
                $form_title = isset($_POST['formtitle' . $i]) ? sanitize_text_field($_POST['formtitle' . $i]) : '';
                $form_value = isset($_POST['formvalue' . $i]) ? sanitize_text_field($_POST['formvalue' . $i]) : '';
                
                if (!empty($form_title) || !empty($form_value)) {
                    $tempArray[$i] = array(
                        "icon" => $form_icon,
                        "title" => $form_title,
                        "value" => $form_value
                    );
                }
            }
            
            update_post_meta($post_id, 'mold_trip_overview', $tempArray);
        }
    
        
        // Save map
        if (isset($_POST['mold_trip_map'])) {
            update_post_meta($post_id, 'mold_trip_map', wp_kses_post($_POST['mold_trip_map']));
        }
    }
    add_action('save_post_product', 'mold_save_overview_meta_box');
}


/* Front End - Keep this part the same */

if (!function_exists('mold_rename_tab_overview')) {
    /* Save Overview tab to description if product not bookable */
    function mold_rename_tab_overview($tabs) {
        global $product, $post;
        $is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);
        if ($post->post_content) {
            if ($is_mold_trip ==  'on') {
                $overview_title = get_post_meta($post->ID, 'mold_trip_overview_title', true);
                if ($overview_title == '') {
                    $overview_title = esc_html__('Overview', 'mold-tour');
                }
                $tabs['description']['title'] = $overview_title;/* Rename the description tab*/
            } else {
                $tabs['description']['title'] = esc_html__('Description', 'mold-tour');/* Rename the description tab*/
            }

            $tabs['description']['callback'] = 'mold_custom_description_tab_content';
        }

        return $tabs;
    }
    add_filter('woocommerce_product_tabs', 'mold_rename_tab_overview', 98);

    function mold_custom_description_tab_content() {
        global $post;
        $mold_trip_overview = get_post_meta($post->ID, 'mold_trip_overview', true);
        $array_filter_overview = $mold_trip_overview;
        $is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);
        $grade = strip_tags(get_the_term_list($post->ID, 'grade', '', '', ''));
        $mold_day = get_post_meta($post->ID, 'mold_tour_day', true);
        $mold_night = get_post_meta($post->ID, 'mold_tour_night', true);
        $mold_overview_icons_hide = get_post_meta($post->ID, 'mold_overview_icons_hide', true);
        $mold_overview_side_pos = get_post_meta($post->ID, 'mold_overview_side_pos', true);
        $mold_overview_side_content = get_post_meta($post->ID, 'mold_overview_side_content', true);

        echo '<div class="row">';
        if (isset($is_mold_trip) && $is_mold_trip = 'on') {
            require 'overview-icons.php';
                        the_content();
        } else {
            the_content();
        }

        echo '</div>';
    }
}

// Make sure the overview-icons.php file exists and works correctly
if (!function_exists('mold_display_overview_icons')) {
    function mold_display_overview_icons() {
        global $post;
        $mold_trip_overview = get_post_meta($post->ID, 'mold_trip_overview', true);
        
        if (is_array($mold_trip_overview) && !empty($mold_trip_overview)) {
            echo '<div class="overview-icons">';
            foreach ($mold_trip_overview as $item) {
                if (!empty($item['title']) || !empty($item['value'])) {
                    echo '<div class="overview-item">';
                    if (!empty($item['icon'])) {
                        echo '<i class="' . esc_attr($item['icon']) . '"></i>';
                    }
                    if (!empty($item['title'])) {
                        echo '<span class="overview-title">' . esc_html($item['title']) . '</span>';
                    }
                    if (!empty($item['value'])) {
                        echo '<span class="overview-value">' . esc_html($item['value']) . '</span>';
                    }
                    echo '</div>';
                }
            }
            echo '</div>';
        }
    }
}