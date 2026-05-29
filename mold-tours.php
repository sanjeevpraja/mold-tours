<?php
/*
Plugin Name: Mold Tours
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
if (!function_exists('mold_load_tour_plugin_mold')) {
	function mold_load_tour_plugin_mold()
	{
		$domain = 'mold-tour';
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
		// wp-content/languages/plugin-name/plugin-name-de_DE.mo
		load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
		// wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
		load_plugin_textdomain($domain, FALSE, basename(dirname(__FILE__)) . '/languages/');
	}
	add_action('plugins_loaded', 'mold_load_tour_plugin_mold');
}

/**
 * Enqueue Admin styles and scripts
 */
if (!function_exists('mold_load_tour_admin_styles')) {
	function mold_load_tour_admin_styles($hook)
	{
		wp_enqueue_style('mold-tour-admin', MOLD_TOUR_BASE_URL . 'css/tour-admin.css', array(), MOLD_TOUR_VERSION);

		global $post;
		if (is_object($post) && ($post->post_type == 'tour')) {
			wp_enqueue_script('productadmin', MOLD_TOUR_BASE_URL . '/js/productadmin.js', ['wp-data', 'wp-edit-post', 'wp-dom-ready'], false, MOLD_TOUR_VERSION);
			wp_enqueue_script('hideseek', MOLD_TOUR_BASE_URL . 'js/hideseek.js', array('jquery'), array(), MOLD_TOUR_VERSION);
		}
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

		// Flatpickr
		wp_enqueue_style('flatpickr-css', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
		wp_enqueue_script('flatpickr-js', 'https://cdn.jsdelivr.net/npm/flatpickr', [], null, true);
	}
	add_action('wp_enqueue_scripts', 'mold_tour_enqueue_styles_scripts_plugin', 200);
}

/** localize assets*/
function mold_block_enqueue_assets() {
    $currency = get_option( 'tour_currency', 'USD' );

    wp_enqueue_script(
        'mold-block-js',
        plugin_dir_url(__FILE__) . "build/block-tour-meta/index.js",
        [ 'wp-blocks', 'wp-element', 'wp-editor' ],
        '1.0',
        true
    );

    wp_localize_script(
        'mold-block-js',
        'moldBlockData',
        [ 'currency' => $currency ]
    );
}
add_action( 'enqueue_block_editor_assets', 'mold_block_enqueue_assets' );




/*gutenberg block*/
function wp_mold_tour_register_block()
{
	$options = get_option('wp_mold_tour_blocks_settings');
	$blocks = [
		'block-member-meta',
		'block-tour-meta',
		'block-tour-gallery',
		'block-location-image',
		'block-grade-image',
		// 'block-grade-icon',
		// 'block-location-icon',
	];

	foreach ($blocks as $block) {
		$constant_name = 'MOLD_' . strtoupper(str_replace('-', '_', $block));
		$option_key = 'disable-' . $block;
		$is_disabled = isset($options[$option_key]) ? $options[$option_key] : '0';


		// Define the constant
		if (!defined($constant_name)) {
			define($constant_name, $is_disabled);
		}

		// Register the block if not disabled
		if ($is_disabled !== '1') {
			if (file_exists(plugin_dir_path(__FILE__) . 'inc/' . $block . '.php')) {
				$funtion_name = 'callback_' . str_replace('-', '_', $block);
				require_once plugin_dir_path(__FILE__) . 'inc/' . $block . '.php';
				register_block_type(__DIR__ . "/build/$block", [
					'render_callback' => $funtion_name,
                    'style' => 'mold-' . $block . '-style'
				]);
                wp_register_style(
                    'mold-' . $block . '-style',
                    plugin_dir_url(__FILE__) . "build/$block/style-index.css",
                    array(),
                    '1.0.0'
                );
			} else {
				register_block_type(__DIR__ . "/build/$block");
			}
		}
	}
}

add_action('init', 'wp_mold_tour_register_block');



/**
 * CPT Tour
 */
$tour_cpt_file = plugin_dir_path(__FILE__) . 'cpt-tour/cpt-tour.php';
if (file_exists($tour_cpt_file)) {
	require_once $tour_cpt_file;

	/**
	 * Tour Setting
	 */
	require_once 'cpt-tour/tour-setting.php';
	require_once 'cpt-tour/cpm-fields.php';
	require_once 'cpt-tour/cpt-gallery.php';

	/**
	 * Taxonomy
	 */
	require_once 'cpt-tour/taxonomy/class-grade-taxonomy.php';
	require_once 'cpt-tour/taxonomy/class-location-taxonomy.php';
	require_once 'cpt-tour/taxonomy/class-accomodation-taxonomy.php';

	/**
	 * Widget
	 */
	require_once 'cpt-tour/widget/grade-widget.php';
	require_once 'cpt-tour/widget/location-widget.php';
	require_once 'cpt-tour/widget/searc-tour-widget.php';
}



/**
 * CPT Member
 */
$member_cpt_file = plugin_dir_path(__FILE__) . 'cpt-member/cpt-member.php';
if (file_exists($member_cpt_file)) {
	require_once $member_cpt_file;
}

/**
 * CPT Accomodation
 */
$accomodation_cpt_file = plugin_dir_path(__FILE__) . 'cpt-accomodation/cpt-accomodation.php';
if (file_exists($accomodation_cpt_file)) {
	require_once $accomodation_cpt_file;
}

/**
 * CPT Region
 */
$region_cpt_file = plugin_dir_path(__FILE__) . 'cpt-region/cpt-region.php';
if (file_exists($region_cpt_file)) {
	require_once $region_cpt_file;
}


function mold_tour_plugin_activation() {
    //Call every CPT registration function you have
		create_tour_post_type();
    create_accomodation_post_type();
		create_region_post_type();
		create_member_post_type();
    
    //Flush the rewrite rules once for everything
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'mold_tour_plugin_activation');

/**contact form 7*/
add_filter('wpcf7_form_tag', function ($tag) {
	if (! is_array($tag) || empty($tag['name'])) {
		return $tag;
	}

	global $post;

	// Only proceed if there's a current post (like a tour)
	if ($post) {
		// Prefill page title
		if ('page_title' === $tag['name']) {
			$tag['values'] = [get_the_title($post->ID)];
		}

		// Prefill page URL
		if ('page_url' === $tag['name']) {
			$tag['values'] = [get_permalink($post->ID)];
		}
	}

	return $tag;
}, 10, 1);


