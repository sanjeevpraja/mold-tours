<?php

if(!function_exists('mold_load_search_widget')) {
	/*Search Tour widget*/
	function mold_load_search_widget() {
		register_widget( 'Mold_Search_Widget' );
	}
	add_action( 'widgets_init', 'mold_load_search_widget' );
}

// Creating the widget 
class Mold_Search_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'mold_search_widget', 
			// Widget name will appear in UI
			esc_html__('Mold Trip Search', 'mold-tour'), 
			// Widget description
			array( 'description' => __( 'Search Tour widget', 'mold-tour' ), ) 
		);
	}


	// Creating widget front-end
	public function widget( $args, $instance ) {
		if(isset($instance['title'])){
			$title = apply_filters( 'widget_title', $instance['title'] );
		}
		else{
			$title = '';
		}
		

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . esc_html($title) . $args['after_title'];

		$html = '<form method="get" action="'.esc_url( home_url() ).'">';
		$html .= '<input type="hidden" name="post_type" value="product" />
				<div class="form-group">
					<label>' . esc_html__('Keyword', 'mold-tour') . '</label>
					<input type="text" name="s" value="" maxlength="50" class="form-control"/>
				</div>
				<div class="form-group">
					<label>' . esc_html__('Location', 'mold-tour') . '</label>
					<select name="location" class="form-control">
						<option value="">' . esc_html__('All', 'mold-tour') . '</option>
						';
						$loc_args = array(
							'taxonomy'     => 'location',
							'orderby'      => 'name',  
							'show_count'   => 0,    
							'pad_counts'   => 0,    
							'hierarchical' => 1,      
							'title_li'     => '',  
							'hide_empty'   => 0,
							);
						$all_location = get_categories( $loc_args );
						foreach ($all_location as $location) {
							if($location->location_parent == 0) {
								$location_id = $location->term_id;       
								$html .= '<option value="'. esc_attr(strtolower($location->name)) .'">'. esc_html($location->name) .'</option>';
							}       
						}
			$html .='</select>
					</div>
					<div class="form-group">
						<label>' . esc_html__('Grade', 'mold-tour').'</label>
						<select name="grade" class="form-control">
							<option value="">All</option>
							';

							$grades = get_terms('grade');
							foreach ( $grades as $grade ) {
								$html .= '<option value="' . esc_attr($grade->slug) . '">' . esc_html($grade->name) . '</option>';
							}

							$html .='</select>
						</div>
						<div class="form-group price-range">
							<label>' . esc_html__('Price Range', 'mold-tour') . '</label>
							<div>
								<input type="hidden" id="min_price" name="min_price" value="0" class="form-control">
								<input type="hidden" id="max_price" name="max_price" value="5000" class="form-control">
								<div id="amount"></div>
								<div class="mold-price-slider"></div>
							</div>
						</div>
						<div class="">
							<button type="submit" class="btn btn-search btn-primary">' . esc_html__('Search', 'mold-tour') . '</button>
						</div>
					</form>';   

			$html .= '
			<script>
				(function($){
					var minprice = '.mold_minmax_price('min').';
					var maxprice = '.mold_minmax_price('max').';
					$(document).ready(function(){
						$( ".mold-price-slider" ).slider({
							range: true,
							min: minprice,
							max: maxprice,
							values: [ minprice, maxprice ],
							slide: function( event, ui ) {
								$( "#min_price" ).val(ui.values[ 0 ]);
								$( "#max_price" ).val(ui.values[ 1 ]);
								$( "#amount" ).html(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
							}
						});
						$( "#min_price" ).val($( ".mold-price-slider" ).slider( "values", 0 ));
						$( "#max_price" ).val($( ".mold-price-slider" ).slider( "values", 1 ));
						$( "#amount" ).html($( ".mold-price-slider" ).slider( "values", 0 ) +
						" - " + $( ".mold-price-slider" ).slider( "values", 1 ) );
					});
				})(jQuery);
			</script>
			';
			echo $html; 
			echo $args['after_widget'];
		}

		// Widget Backend 
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = esc_html__( 'New title', 'mold-tour' );
			}
			?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'mold-tour' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}

		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}

} // Class wpb_widget ends here
