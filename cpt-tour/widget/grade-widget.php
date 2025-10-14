<?php

if(!function_exists('mold_load_grade_widget')) {
	/*Grade Icon widget*/
	function mold_load_grade_widget() {
		register_widget( 'Mold_Grade_Widget' );
	}
	add_action( 'widgets_init', 'mold_load_grade_widget' );
}

// Creating the widget 
class Mold_Grade_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(

			// Base ID of your widget
			'mold_grade_widget', 

			// Widget name will appear in UI
			esc_html__('Mold Trip Grade', 'mold-tour'), 

			// Widget description
			array( 'description' => esc_html__( 'Filter grade', 'mold-tour' ), ) 
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
			if ( ! empty( $title ) ){
				echo $args['before_title'] . esc_html($title) . $args['after_title'];
			}
			$terms_args = array(
				'orderby'  => 'term_id',
				'order'    => 'ASC'
				);
			$terms = get_terms('grade', $terms_args);
			foreach ( $terms as $term ) {
				$term_link = get_term_link( $term, 'grade' );
				$term_icon = get_term_meta ( $term->term_id, 'grade-icon-id', true );
				if($term_icon == ''){
					$term_icon = 'icon-level-1';
				}
				echo '<a href="' . esc_url($term_link) . '" data-toggle="tooltip" data-placement="bottom" title="' . esc_attr($term->name) . '">';
				echo '<span class="grade-select ' . esc_attr($term_icon).'"></span>';
				echo '</a>';
			}
		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = esc_html($instance[ 'title' ]);
		}
		else {
			$title = esc_html__( 'New title', 'mold-tour' );
		}
	// Widget admin form
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:' , 'mold-tour'); ?></label> 
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





