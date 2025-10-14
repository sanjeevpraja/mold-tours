<?php
/*
Plugin Name: WP Mold Tours
Plugin URI: http://moldthemes.com
Description: Mold Tour Plugin 
Version: 3.0
Author: Mold Themes
Author URI: http://www.moldthemes.com
Text Domain: mold-tour
Domain Path:  /languages
*/

/*
* Define constant
*/
define('MOLD_TOUR_MAIN_FILE_URL', __FILE__);
define('MOLD_TOUR_VERSION', '1.7');
define('MOLD_TOUR_BASE_URL', plugin_dir_url(__FILE__));

function wp_mold_tour_is_mold_block_active()
{
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');
	return is_plugin_active('mold-blocks/mold-blocks.php');
}

/**
 * Localization
 */
if (!function_exists('mold_load_tour_plugin_textdomain')) {
	function mold_load_tour_plugin_textdomain()
	{
		$domain = 'mold-tour';
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
		// wp-content/languages/plugin-name/plugin-name-de_DE.mo
		load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
		// wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
		load_plugin_textdomain($domain, FALSE, basename(dirname(__FILE__)) . '/languages/');
	}
	add_action('plugins_loaded', 'mold_load_tour_plugin_textdomain');
}

/**
 * Enqueue Admin styles and scripts
 */
if (!function_exists('mold_load_tour_admin_styles')) {
	function mold_load_tour_admin_styles($hook)
	{
		wp_enqueue_style('mold-tour-admin', MOLD_TOUR_BASE_URL . 'css/tour-admin.css', array(), MOLD_TOUR_VERSION);

		global $post;
		if (is_object($post) && ($post->post_type == 'product' || $post->post_type == 'tour')) {
			$is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);
			wp_enqueue_script('productadmin', MOLD_TOUR_BASE_URL . 'js/productadmin.js', array('jquery'), array(), MOLD_TOUR_VERSION);
			$bookable = array(
				'bookable'      	=> $is_mold_trip, //used in admin.js
			);
			wp_localize_script('productadmin', 'trip', $bookable);

			wp_enqueue_script('hideseek', MOLD_TOUR_BASE_URL . 'js/hideseek.js', array('jquery'), array(), MOLD_TOUR_VERSION);
		}

		wp_enqueue_style('deasil-iconfont', MOLD_TOUR_BASE_URL . 'font/iconfont/iconstyle.css', array(), MOLD_TOUR_VERSION);
		wp_enqueue_style('deasil-icofont', MOLD_TOUR_BASE_URL . 'font/icofont/icofont.css', array(), MOLD_TOUR_VERSION);

		wp_enqueue_script('deasil-datajson', MOLD_TOUR_BASE_URL . 'font/iconfont/data.json', array(), MOLD_TOUR_VERSION);
		wp_localize_script('deasil-datajson', 'iconfont', array(
			'pluginsUrl' =>  MOLD_TOUR_BASE_URL
		));
	}
	add_action('admin_enqueue_scripts', 'mold_load_tour_admin_styles');
}

/**
 * Enqueue Frontend styles and scripts
 */
if (!function_exists('mold_tour_enqueue_styles_scripts_plugin')) {

	function mold_tour_enqueue_styles_scripts_plugin()
	{
		wp_enqueue_script('mold-tour', MOLD_TOUR_BASE_URL . 'js/tour.js', array('jquery'), array(), MOLD_TOUR_VERSION);
		wp_enqueue_style('mold-tour-core', MOLD_TOUR_BASE_URL . 'css/tour-core.css', array(), MOLD_TOUR_VERSION);

		wp_enqueue_style('deasil-iconfont-font', MOLD_TOUR_BASE_URL . 'font/iconfont/iconstyle.css', array(), MOLD_TOUR_VERSION);
		wp_enqueue_style('deasil-icofont-font', MOLD_TOUR_BASE_URL . 'font/icofont/icofont.css', array(), MOLD_TOUR_VERSION);

		// Flatpickr
		wp_enqueue_style('flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
		wp_enqueue_script('flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', [], null, true);
	}
	add_action('wp_enqueue_scripts', 'mold_tour_enqueue_styles_scripts_plugin', 200);
}



/*gutenberg block*/
function wp_mold_tour_register_block()
{
	$options = get_option('wp_mold_tour_blocks_settings');
	$blocks = [
		'block-member-meta',
		'block-tour-meta',
		'block-tour-overview',
		'block-tour-gallery'
	];

	foreach ($blocks as $block) {
		$constant_name = 'MOLD_' . strtoupper(str_replace('-', '_', $block));
		$option_key = 'disable-' . $block;
		$is_disabled = isset($options[$option_key]) ? $options[$option_key] : '0';


		// Define the constant
		if (!defined($constant_name)) {
			define($constant_name, $is_disabled);
		}

		// Skip mold-blocks-specific blocks if mold-blocks isn't active
		$requires_wc = in_array($block, [
			'block-tour-overview'
		]);

		if ($requires_wc && !wp_mold_tour_is_mold_block_active()) {
			continue;
		}


		// Register the block if not disabled
		if ($is_disabled !== '1') {
			if (file_exists(plugin_dir_path(__FILE__) . 'inc/' . $block . '.php')) {
				$funtion_name = 'callback_' . str_replace('-', '_', $block);
				require_once plugin_dir_path(__FILE__) . 'inc/' . $block . '.php';
				register_block_type(__DIR__ . "/build/$block", [
					'render_callback' => $funtion_name
				]);
			} else {
				register_block_type(__DIR__ . "/build/$block");
			}
		}
	}
}

add_action('init', 'wp_mold_tour_register_block');





	/**
	 * CPT Member
	 */
$member_cpt_file = plugin_dir_path(__FILE__) . 'cpt-member/cpt-member.php';
if (file_exists($member_cpt_file)) {
	require_once $member_cpt_file;
}



	/**
	 * CPT Tour
	 */
$tour_cpt_file = plugin_dir_path(__FILE__) . 'cpt-tour/cpt-tour.php';
if (file_exists($tour_cpt_file)) {
	require_once $tour_cpt_file;

	require 'cpt-tour/cpm-fields.php';
	require 'cpt-tour/cpt-gallery.php';
	require 'cpt-tour/cpm-overview.php';
	require 'cpt-tour/cpm-itinerary.php';



	/**
	 * Taxonomy
	 */
	require 'cpt-tour/taxonomy/class-grade-taxonomy.php';
	require 'cpt-tour/taxonomy/class-location-taxonomy.php';


	/**
	 * Widget
	 */
	require 'cpt-tour/widget/grade-widget.php';
	require 'cpt-tour/widget/location-widget.php';
	require 'cpt-tour/widget/searc-tour-widget.php';
}
