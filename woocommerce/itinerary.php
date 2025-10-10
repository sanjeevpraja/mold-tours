<?php
/* Custom Meta Box - Itinerary */

if (!function_exists('mold_add_itinerary_meta_box')) {
    /**
     * Add itinerary meta box to products
     */
    function mold_add_itinerary_meta_box() {
        add_meta_box(
            'mold_itinerary_meta_box',
            esc_html__('Trip Itinerary', 'mold-tour'),
            'mold_itinerary_meta_box_content',
            ['product', 'tour'],
            'normal',
            'high'
        );
    }
    add_action('add_meta_boxes', 'mold_add_itinerary_meta_box');
}

if (!function_exists('mold_itinerary_meta_box_content')) {
    /**
     * Display itinerary meta box content
     */
    function mold_itinerary_meta_box_content($post) {
        // Add nonce for security
        wp_nonce_field('mold_itinerary_meta_box', 'mold_itinerary_nonce');

        $mold_itinerary_options = array(
            'title' => get_post_meta($post->ID, 'mold_itinerary_title', true),
            'content' => get_post_meta($post->ID, 'mold_itinerary_content', true),
        );

        $itinerary_title = get_post_meta($post->ID, 'mold_trip_itinerary_title', true);

        if ($itinerary_title == "") {
            $itinerary_title = esc_html__('Itinerary', 'mold-tour');
        }
        ?>
        <div class="mold-itinerary-metabox">
        <div class="form-field">
                            <label><strong><?php echo esc_html__('Tab Title', 'mold-tour'); ?></strong></label>
                            <input type="text" name="mold_trip_itinerary_title" value="<?php if (isset($itinerary_title)) echo esc_attr($itinerary_title); ?>" class="widefat" />
                        </div>

            <hr style="margin: 20px 0;" />

            <div>
                        <?php
                        if (!function_exists('is_countable')) {
                            function is_countable($var) {
                                return is_array($var) || $var instanceof Countable;
                            }
                        }

                        $itinerary = get_post_meta($post->ID, 'mold_itinerary_field', true);
                        if (is_countable($itinerary)) {
                            $itinerary_count = count($itinerary);
                        } else {
                            $itinerary_count = 0;
                        }
                        ?>

                        <div class="itinerary-form">
                            <input type="hidden" id="form-count" name="itinerary-form-count" value="<?php echo $itinerary_count; ?>">

                            <?php if (is_array($itinerary)):
                                $i = 0;
                                foreach ($itinerary as $itineraryvalue) {
                                    echo '<div class="form-field">';
                                    if (is_array($itineraryvalue)) {
                                        foreach ($itineraryvalue as $key => $value) {
                                            switch ($key) {
                                                case 'icon':
                                                    if ($value == '') {
                                                        echo '<div class="formicon" data-icon-id="' . $i . '"><i class="icon no-icon" id="formicon' . $i . '"></i></div>';
                                                    } else {
                                                        echo '<div class="formicon" data-icon-id="' . $i . '"><i class="icon ' . $value . '" id="formicon' . $i . '"></i></div>';
                                                    }
                                                    echo '<input type="hidden" name="itineraryformicon' . $i . '" id="formicon_hidden' . $i . '" value="' . $value . '" />';
                                                    break;
                                                case 'day':
                                                    echo '<input type="text" class="input-day widefat" name="itineraryformday' . $i . '" value="' . esc_attr($value) . '" placeholder="' . esc_attr__('e.g., Day 1', 'mold-tour') . '" />';
                                                    break;
                                                case 'title':
                                                    echo '<textarea class="input-title widefat" name="itineraryformtitle' . $i . '" placeholder="' . esc_attr__('Itinerary title', 'mold-tour') . '">' . esc_textarea($value) . '</textarea>';
                                                    break;
                                                case 'value':
                                                    $editor_content = $value;
                                                    $editor_id = 'itineraryformvalue' . $i;
                                                    $setting = array(
                                                        'media_buttons' => false,
                                                        'quicktags'     => true,
                                                        'teeny'     => true,
                                                        'textarea_rows' => 4,
                                                        'tinymce' => array(
                                                            'toolbar1' => 'bullist,bold,italic,link,unlink'
                                                        ),
                                                    );
                                                    wp_editor($editor_content, $editor_id, $setting);
                                                    break;
                                            }
                                        }
                                    }
                                    echo '<button type="button" class="btn btn-delete" title="' . esc_attr__('Remove', 'mold-tour') . '"></button>';
                                    echo '</div>';
                                    $i = $i + 1;
                                }
                            else: ?>
                                <div class="form-field">
                                    <div class="formicon" data-icon-id="0"><i class="no-icon icon" id="formicon0"></i></div>
                                    <input type="hidden" name="itineraryformicon0" id="formicon_hidden0" value="" />
                                    <input type="text" class="input-day widefat" name="itineraryformday0" value="" placeholder="<?php esc_attr_e('e.g., Day 1', 'mold-tour'); ?>" />

                                    <textarea class="input-title widefat" name="itineraryformtitle0" placeholder="<?php esc_attr_e('Itinerary title', 'mold-tour'); ?>" style="height: 60px;"></textarea>

                                    <?php
                                    $editor_id = 'itineraryformvalue0';
                                    $setting = array(
                                        'media_buttons' => false,
                                        'quicktags'     => true,
                                        'teeny'     => true,
                                        'textarea_rows' => 3,
                                        'tinymce' => array(
                                            'toolbar1' => 'bullist,bold,italic,link,unlink'
                                        ),
                                    );
                                    wp_editor('', $editor_id, $setting);
                                    ?>

                                    <button type="button" class="btn-delete" title="<?php esc_attr_e('Remove', 'mold-tour'); ?>"></button>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div id="mold-popup-itinerary" class="mold-modal hidden">
                            <div class="mold-modal-content">
                                <span class="close-modal">&times;</span>
                                <input type="hidden" id="icon-holder-itinerary" name="icon-holder-itinerary" value="">
                                <div class="icon-select">
                                    <input id="search-itinerary" class="search-input" name="search-1" placeholder="<?php esc_attr_e('Search icons...', 'mold-tour'); ?>" type="text" data-toggle="hideseek" data-list="#icon-list-itinerary" autocomplete="off">
                                    <div class="icon-list-wrap">
                                        <ul id="icon-list-itinerary" class="iconlist"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btn-wrap">
                            <button type="button" id="add-itinerary-info" class="button button-primary"><?php esc_attr_e('Add New Itinerary Item', 'mold-tour'); ?></button>
                        </div>

                    </div>
        </div>

        <?php
    }
}

if (!function_exists('mold_save_itinerary_meta_box')) {
    /**
     * Save itinerary meta box data
     */
    function mold_save_itinerary_meta_box($post_id) {
        // Check nonce
        if (!isset($_POST['mold_itinerary_nonce']) || !wp_verify_nonce($_POST['mold_itinerary_nonce'], 'mold_itinerary_meta_box')) {
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
        // Save basic fields
        if (isset($_POST['mold_trip_itinerary_title'])) {
            update_post_meta($post_id, 'mold_trip_itinerary_title', sanitize_text_field($_POST['mold_trip_itinerary_title']));
        }


        // Save itinerary items
        if (isset($_POST['itinerary-form-count'])) {
            $form_count = intval($_POST['itinerary-form-count']);
            $itinerarytempArray = array();

            for ($i = 0; $i <= $form_count; $i++) {
                $form_icon = isset($_POST['itineraryformicon' . $i]) ? sanitize_text_field($_POST['itineraryformicon' . $i]) : '';
                $form_day = isset($_POST['itineraryformday' . $i]) ? sanitize_text_field($_POST['itineraryformday' . $i]) : '';
                $form_title = isset($_POST['itineraryformtitle' . $i]) ? sanitize_textarea_field($_POST['itineraryformtitle' . $i]) : '';
                $form_value = isset($_POST['itineraryformvalue' . $i]) ? wp_kses_post($_POST['itineraryformvalue' . $i]) : '';

                if (!empty($form_day) || !empty($form_title) || !empty($form_value)) {
                    $itinerarytempArray[$i] = array(
                        "icon" => $form_icon,
                        "day" => $form_day,
                        "title" => $form_title,
                        "value" => $form_value
                    );
                }
            }

            update_post_meta($post_id, 'mold_itinerary_field', $itinerarytempArray);
        }
    }
    add_action('save_post_product', 'mold_save_itinerary_meta_box');
}


/*
* Frontend display - Keep this part the same *
*/

if (!function_exists('mold_itinerary_woo_tab')) {
    /*Add itenary tab to product detail on frontend*/
    function mold_itinerary_woo_tab($tabs) {
        global $post;
        $is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);
        if ($is_mold_trip == 'on') {
            $itinerary_title = get_post_meta($post->ID, 'mold_trip_itinerary_title', true);

            if ($itinerary_title == "") {
                $itinerary_title = esc_html__('Itinerary', 'mold-tour');
            }
            $tabs['itinerary'] = array(
                'title'     => $itinerary_title,
                'priority'  => 20,
                'callback'  => 'mold_itinerary_woo_tab_content'
            );

            $mold_trip_itinerary_tab_hide = get_post_meta($post->ID, 'mold_trip_itinerary_tab_hide', true);
            if ($mold_trip_itinerary_tab_hide != 'hide') {
                return $tabs;
            }
        }
    }
    add_filter('woocommerce_product_tabs', 'mold_itinerary_woo_tab');

    function mold_itinerary_woo_tab_content() {
        global $post;
        $mold_itinerary_field = get_post_meta($post->ID, 'mold_itinerary_field', true);

        mold_itinerary_steps($mold_itinerary_field);
    }
}

// Helper function for frontend display (make sure this exists)
if (!function_exists('mold_itinerary_steps')) {
    function mold_itinerary_steps($itinerary_data) {
        // Your existing frontend display logic here
        if (is_array($itinerary_data)) {
            foreach ($itinerary_data as $item) {
                // Display each itinerary item
                echo '<div class="itinerary-steps">';
                if (!empty($item['icon'])) {
                    echo '<div class="step-index"><i class="' . esc_attr($item['icon']) . '"></i></div>';
                }
                echo'<div class="step-content">';
                if (!empty($item['day'])) {
                    echo '<div><span class="day-number">' . esc_html($item['day']) . '</span></div>';
                }
                if (!empty($item['title'])) {
                    echo '<h4 class="title">' . esc_html($item['title']) . '</h4>';
                }
                if (!empty($item['value'])) {
                    echo '<div class="detail">' . wp_kses_post($item['value']) . '</div>';
                }
                    echo '</div>';
                echo '</div>';
            }
        }
    }
}