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
});

?>