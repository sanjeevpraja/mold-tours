<?php
// render.php
// Block rendering function
function callback_block_overview( $attributes, $content, $block ) {
    global $post;

    if ( ! $post ) {
        return '';
    }

    // Get the data
    $mold_trip_overview = get_post_meta( $post->ID, 'mold_trip_overview', true );
    $terms = get_the_terms( $post->ID, 'grade' );
    $grade_term = get_the_term_list( $post->ID, 'grade', '', ', ', '' );
    $grade = '';
    $icon_class = '';

    if ( $terms ) {
        foreach ( $terms as $term ) {
            $grade = $term->name;
            $icon_class = get_term_meta( $term->term_id, 'grade-icon-id', true );
        }
    }

    $category = wc_get_product_category_list( $post->ID, ', ' );
    $cat_string = strip_tags( $category );
    $location = get_the_term_list( $post->ID, 'location', '', ', ', '' );

    // Start output
    $wrapper_attributes = get_block_wrapper_attributes( array(
        'class' => 'trip-overview-block ' . esc_attr( $attributes['className'] ?? '' )
    ) );

    ob_start();
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <ul class="trip-overview">
            <?php
            // Category
            if ( $cat_string != 'Uncategorized' && ! empty( $cat_string ) ) {
                ?>
                <li>
                    <span class="icon-barcode"></span>
                    <div class="detail">
                        <div class="title"><?php _e( 'Category', 'mold-tour' ); ?></div>
                        <div class="desc"><?php echo $category; ?></div>
                    </div>
                </li>
                <?php
            }

            // Location
            if ( $location != '' ) {
                ?>
                <li>
                    <span class="icon-earth"></span>
                    <div class="detail">
                        <div class="title"><?php _e( 'Location', 'mold-tour' ); ?></div>
                        <div class="desc"><?php echo $location; ?></div>
                    </div>
                </li>
                <?php
            }

            // Grade
            if ( ! empty( $grade ) ) {
                ?>
                <li>
                    <span class="<?php echo esc_attr( $icon_class ); ?>"></span>
                    <div class="detail">
                        <div class="title"><?php _e( 'Grade', 'mold-tour' ); ?></div>
                        <div class="desc"><?php echo $grade_term; ?></div>
                    </div>
                </li>
                <?php
            }

            // Custom Overview Items
            if ( is_array( $mold_trip_overview ) ) {
                foreach ( $mold_trip_overview as $overview ) {
                    if ( is_array( $overview ) ) {
                        ?>
                        <li>
                            <?php
                            foreach ( $overview as $key => $value ) {
                                if ( $key == 'icon' ) {
                                    echo '<span class="' . esc_attr( $value ) . '"></span>';
                                } else if ( $key == 'title' ) {
                                    echo '<div class="detail">';
                                    echo '<div class="title">' . esc_html( $value ) . '</div>';
                                } else if ( $key == 'value' ) {
                                    echo '<div class="desc">' . esc_html( $value ) . '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
        }