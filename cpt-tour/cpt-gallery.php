<?php
/*adding tour gallery*/
add_action( 'add_meta_boxes', function() {
	if ( post_type_exists( 'tour' ) ) {
		add_meta_box(
			'tour_gallery',
			__( 'Tour Gallery', 'mold-tour' ),
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
						<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Remove image', 'mold-tour' ) . '">×</a></li>
					</ul>
				</li>';
			}
			?>
		</ul>
		<input type="hidden" id="tour_gallery_ids" name="tour_gallery_ids" value="<?php echo esc_attr( $image_ids ); ?>" />
	</div>

	<p class="add_tour_images hide-if-no-js">
		<a href="#" class="button"><?php esc_html_e( 'Add gallery images', 'mold-tour' ); ?></a>
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
				title: '<?php esc_html_e( 'Add Images to Tour Gallery', 'mold-tour' ); ?>',
				button: { text: '<?php esc_html_e( 'Add to gallery', 'mold-tour' ); ?>' },
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