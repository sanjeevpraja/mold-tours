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
            'tour',
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
                                    <div class="formicon" data-icon-id="0" title="<?php esc_attr_e('Select Icon', 'mold-tour'); ?>"><i class="no-icon icon" id="formicon0"></i></div>
                                    <input type="hidden" name="formicon0" id="formicon_hidden0" value="" />
                                    <input type="text" name="formtitle0" value="" class="widefat" placeholder="<?php esc_attr_e('e.g., Duration', 'mold-tour'); ?>" />
                                    <input type="text" name="formvalue0" value="" class="widefat" placeholder="<?php esc_attr_e('e.g., 5 Days', 'mold-tour'); ?>" />

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
