<?php
/*custom tab  Cost & Book*/
if (!function_exists('mold_cost_book_options_tab')) {
	/*Cost and book option - Admin*/
	function mold_cost_book_options_tab($tabs)
	{
		global $post;
		$is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);
		if ($is_mold_trip == 'on') {
			$class = array("show_if_simple", "show_if_variable", "show_if_external");
		} else {
			$class = array("hide_if_simple", "hide_if_variable", "hide_if_external");
		};
		$tabs['mold_cost_book_data'] = array(
			'label'     => esc_html__('Cost & Book', 'mold-tour'),
			'target'    => 'mold_cost_book_data',
			'class'     => array_merge($class, array('mold-cost-book')),
		);
		return $tabs;
	}
	add_filter('woocommerce_product_data_tabs', 'mold_cost_book_options_tab', 103);
}

/*Admin */
if (!function_exists('mold_cost_book_options')) {
	function mold_cost_book_options()
	{
		global $post;
?>
		<div id="mold_cost_book_data" class="panel woocommerce_options_panel">
			<?php
			$cost_book_title = get_post_meta($post->ID, 'mold_trip_cost_book_title', true);
			$mold_trip_costbook_tab_hide = get_post_meta($post->ID, 'mold_trip_costbook_tab_hide', true);
			if ($cost_book_title == '') {
				$cost_book_title = esc_html__('Cost &amp; Book', 'mold-tour');
			}
			?>
			<table class="admin-table">
				<tr>
					<td>
						<p class="form-field">
							<label><?php echo esc_html__('Tab Title', 'mold-tour'); ?></label>
							<input type="text" name="mold_trip_cost_book_title" value="<?php if (isset($cost_book_title)) echo $cost_book_title; ?>" />
						</p>
					</td>
					<td style="width: 80px;">
						<p class="form-field">
							<label><?php echo esc_html__('Hide tab', 'mold-tour'); ?></label>
							<input type="checkbox" name="mold_trip_costbook_tab_hide" value="hide" <?php if ($mold_trip_costbook_tab_hide == 'hide') echo 'checked'; ?> />
						</p>
					</td>
				</tr>
			</table>
			<hr />
			<table class="admin-table">
				<tr>
					<td style="width: 50%">
						<p class="form-field">
							<?php $mold_disable_defailt_booking = get_post_meta($post->ID, 'mold_disable_default_booking', true); ?>
							<label for=""><?php esc_html_e('Disable default Booking', 'mold-tour'); ?></label>
							<?php if ($mold_disable_defailt_booking == 1): ?>
								<input type="checkbox" name="mold_disable_default_booking" value="1" checked />
							<?php else: ?>
								<input type="checkbox" name="mold_disable_default_booking" value="1" />
							<?php endif; ?>
						</p>

						<p class="form-field">
							<?php $mold_book_info = get_post_meta($post->ID, 'mold_book_info', true); ?>
							<label for=""><?php esc_html_e('Booking Information', 'mold-tour'); ?></label>
						<div style="padding-left: 12px; margin-top: -15px;">
							<?php
							$content = $mold_book_info;
							$editor_id = 'mold_book_info';
							$setting = array(
								'media_buttons' => false,
								'quicktags'     => false,
								'teeny' 	=> false,
								'tinymce' => array(
									'toolbar1' =>  'formatselect,bold,italic,underline,bullist,numlist,link,unlink, pre_code_button',
									'toolbar2' => ''
								),
							);
							wp_editor($content, $editor_id, $setting);
							?>
						</div>
						</p>
					</td>
					<td style="width: 50%">
						<p class="form-field">
							<?php $mold_book_side_pos = get_post_meta($post->ID, 'mold_book_side_pos', true); ?>
							<label><?php echo esc_html__('Side Content Position', 'mold-tour'); ?></label>
							<select name="mold_book_side_pos" id="mold_book_side_pos">
								<option value="left" <?php echo ($mold_book_side_pos != 'right') ? 'selected' : ''; ?>><?php echo esc_attr__('Left', 'mold-tour'); ?></option>
								<option value="right" <?php echo ($mold_book_side_pos == 'right') ? 'selected' : ''; ?>><?php echo esc_attr__('Right', 'mold-tour'); ?></option>
							</select>
						</p>
						<p class="form-field">
							<?php $mold_book_side_content = get_post_meta($post->ID, 'mold_book_side_content', true); ?>
							<label for=""><?php esc_html_e('Extra Information', 'mold-tour'); ?></label>
						<div style="padding-left: 12px; margin-top: -15px;">
							<?php
							$content = $mold_book_side_content;
							$editor_id = 'mold_book_side_content';
							$setting = array(
								'media_buttons' => false,
								'quicktags'     => false,
								'teeny' 	=> false,
								'tinymce' => array(
									'toolbar1' =>  'formatselect,bold,italic,underline,bullist,numlist,link,unlink, pre_code_button',
									'toolbar2' => ''
								),
							);
							wp_editor($content, $editor_id, $setting);
							?>
						</div>
						</p>
					</td>
				</tr>
			</table>

		</div>
		<?php
	}
	add_action('woocommerce_product_data_panels', 'mold_cost_book_options');
}


if (!function_exists('mold_cost_book_fields_save')) {
	/*Function to save all custom field information from products*/
	function mold_cost_book_fields_save($post_id)
	{
		/*update*/
		$mold_trip_cost_book_title = $_POST['mold_trip_cost_book_title'];
		update_post_meta($post_id, 'mold_trip_cost_book_title', $mold_trip_cost_book_title);

		$mold_trip_costbook_tab_hide = $_POST['mold_trip_costbook_tab_hide'];
		update_post_meta($post_id, 'mold_trip_costbook_tab_hide', $mold_trip_costbook_tab_hide);

		$mold_disable_default_booking = $_POST['mold_disable_default_booking'];
		update_post_meta($post_id, 'mold_disable_default_booking', $mold_disable_default_booking);

		$mold_book_info = $_POST['mold_book_info'];
		update_post_meta($post_id, 'mold_book_info', $mold_book_info);

		$mold_book_side_pos = $_POST['mold_book_side_pos'];
		update_post_meta($post_id, 'mold_book_side_pos', $mold_book_side_pos);

		$mold_book_side_content = $_POST['mold_book_side_content'];
		update_post_meta($post_id, 'mold_book_side_content', $mold_book_side_content);
	}
	add_action('woocommerce_process_product_meta', 'mold_cost_book_fields_save');
}

if (!function_exists('mold_cost_book')) {
	/*Cost and Book tab - Frontend*/
	function mold_cost_book($tabs)
	{
		global $post;
		$is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);

		if ($is_mold_trip == 'on') {
			$cost_book_title = get_post_meta($post->ID, 'mold_trip_cost_book_title', true);
			if ($cost_book_title == '') {
				$cost_book_title = esc_html__('Cost &amp; Book', 'mold-tour');
			}

			$tabs['cost_book'] = array(
				'title' 	=> $cost_book_title,
				'priority' 	=> 30,
				'callback' 	=> 'mold_cost_book_content'
			);

			$mold_trip_costbook_tab_hide = get_post_meta($post->ID, 'mold_trip_costbook_tab_hide', true);
			if ($mold_trip_costbook_tab_hide != 'hide') {
				return $tabs;
			}
		}
	}
	add_filter('woocommerce_product_tabs', 'mold_cost_book');


	function mold_cost_book_content()
	{
		global $post;

		// Check if WooCommerce is active
		if (!function_exists('wc_get_product')) {
			return;
		}

		$product = wc_get_product($post->ID);
		$mold_book_side_pos = get_post_meta($post->ID, 'mold_book_side_pos', true);
		$mold_book_side_content = wpautop(get_post_meta($post->ID, 'mold_book_side_content', true));

		echo '<div class="row">';

		if (isset($mold_book_side_content) && ($mold_book_side_content != '') && ($mold_book_side_pos == 'left')) {
			echo '<div class="col-sm-4">';
			echo '<div class="border-' . $mold_book_side_pos . '">';
			echo do_shortcode($mold_book_side_content);
			echo '</div>';
			echo '</div>';
		}

		echo '<div class="col-sm-8 cost-book">';

		$mold_disable_default_booking = get_post_meta($post->ID, 'mold_disable_default_booking', true);

		if ($mold_disable_default_booking != 1) {
			if ($product->is_type('simple')) {
				echo '<div class="simple-booking">';

				echo do_blocks('<!-- wp:woocommerce/add-to-cart-form /-->');
				echo '</div>';
			} elseif ($product->is_type('variable')) {
				$variations = mold_find_valid_variations();
		?>

				<table class="price-table" cellspacing="0">
					<tbody>
						<?php
						foreach ($variations as $key => $value) {
							if (!$value['variation_is_visible']) continue;
						?>
							<tr>
								<td>
									<?php
									foreach ($value['attributes'] as $key => $val) {
										$val = str_replace(array('-', '_'), ' ', $val);
										printf('<span class="attr attr-%s">%s</span>', $key, ucwords($val));
									}
									?>
									<?php
									if (($value['display_price'] != $value['display_regular_price']) && ($value['display_price'] < $value['display_regular_price'])) {
										echo  '<span class="badge badge-success">' . esc_html__('Sale!', 'mold-tour') . '</span>';
									}
									?>
								</td>
								<td>
									<?php
									if ($value['display_regular_price'] == $value['display_price']) {
										echo get_woocommerce_currency() . '&nbsp;';
										echo $value['display_price'];
									} else {
										echo get_woocommerce_currency() . '&nbsp;';
										echo '<span style="text-decoration: line-through">' . $value['display_regular_price'], '</span> &nbsp;&nbsp;';
										echo $value['display_price'];
									}
									?>
								</td>
								<td>
									<?php
									if ($value['is_in_stock']  == 1) {
										if ($value['availability_html'] == '') {
											echo '<p class="stock in-stock">' . esc_html__('Available', 'mold-tour') . '</p>';
										} else {
											echo $value['availability_html'];
										}
									} else {
										echo $value['availability_html'];
									}
									?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>


				<div class="mold-variable-product-meta">
					<?php
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
					remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

					do_action('woocommerce_single_product_summary');
					?>
				</div>

		<?php
			} else {
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

				do_action('woocommerce_single_product_summary');
			}
		}

		$mold_book_info = wpautop(get_post_meta($post->ID, 'mold_book_info', true));
		if (isset($mold_book_info) && $mold_book_info != '') {
			echo do_shortcode($mold_book_info);
		}


		echo '</div>';

		if (isset($mold_book_side_content) && ($mold_book_side_content != '') && ($mold_book_side_pos == 'right')) {
			echo '<div class="col-sm-4">';
			echo '<div class="border-' . $mold_book_side_pos . '">';
			echo do_shortcode($mold_book_side_content);
			echo '</div>';
			echo '</div>';
		}


		echo '</div>';
	}

	function mold_find_valid_variations()
	{
		global $product;

		$variations = $product->get_available_variations();
		$attributes = $product->get_attributes();
		$new_variants = array();

		/*Loop through all variations*/
		foreach ($variations as $variation) {

			/*Peruse the attributes.
			// 1. If both are explicitly set, this is a valid variation
			// 2. If one is not set, that means any, and we must 'create' the rest.
			*/
			$valid = true; // so far
			foreach ($attributes as $slug => $args) {
				if (array_key_exists("attribute_$slug", $variation['attributes']) && !empty($variation['attributes']["attribute_$slug"])) {
					/*Exists*/
				} else {
					/*Not exists, create*/
					$valid = false; // it contains 'anys'
					/*loop through all options for the 'ANY' attribute, and add each*/
					foreach (explode('|', $attributes[$slug]['value']) as $attribute) {
						$attribute = trim($attribute);
						$new_variant = $variation;
						$new_variant['attributes']["attribute_$slug"] = $attribute;
						$new_variants[] = $new_variant;
					}
				}
			}
			/*This contains ALL set attributes, and is itself a 'valid' variation.*/
			if ($valid)
				$new_variants[] = $variation;
		}
		return $new_variants;
	}
}



if (!function_exists('mold_book_date_validation')) {
	/*validation for single product*/
	function mold_book_date_validation()
	{
		$product_id = $_REQUEST['add-to-cart'];
		$product = wc_get_product($product_id);
		$is_trip = get_post_meta($product_id, 'is_mold_trip', true);
		if ($product->is_type('simple') && ($is_trip == 'on')) {
			if (empty($_REQUEST['mold_book_date'])) {
				wc_add_notice(esc_html__('Please enter Date', 'mold-tour'), 'error');
				return false;
			}
		}
		return true;
	}
	add_action('woocommerce_add_to_cart_validation', 'mold_book_date_validation');
}


if (!function_exists('mold_save_book_date_field')) {
	/*Save data to database*/
	function mold_save_book_date_field($cart_item_data, $product_id)
	{
		if (isset($_REQUEST['mold_book_date'])) {
			$cart_item_data['mold_book_date'] = $_REQUEST['mold_book_date'];
			/* below statement make sure every add to cart action as unique line item */
			$cart_item_data['unique_key'] = md5(microtime() . rand());
		}
		return $cart_item_data;
	}
	add_action('woocommerce_add_cart_item_data', 'mold_save_book_date_field', 10, 2);
}


if (!function_exists('mold_book_date_meta_on_cart_and_checkout')) {
	/*Render on cart and checkout page*/
	function mold_book_date_meta_on_cart_and_checkout($cart_data, $cart_item = null)
	{
		$custom_items = array();
		/* Woo 2.4.2 updates */
		if (!empty($cart_data)) {
			$custom_items = $cart_data;
		}
		if (isset($cart_item['mold_book_date'])) {
			$custom_items[] = array("name" => 'Date', "value" => $cart_item['mold_book_date']);
		}
		return $custom_items;
	}
	add_filter('woocommerce_get_item_data', 'mold_book_date_meta_on_cart_and_checkout', 10, 2);
}


if (!function_exists('mold_book_date_order_meta_handler')) {
	/*This is a piece of code that will add your custom field with order meta - Order Page.*/
	function mold_book_date_order_meta_handler($item_id, $values, $cart_item_key)
	{
		if (isset($values['mold_book_date'])) {
			wc_add_order_item_meta($item_id, __("Book Date", 'mold-tour'), $values['mold_book_date']);
		}
	}
	add_action('woocommerce_add_order_item_meta', 'mold_book_date_order_meta_handler', 1, 3);
}



/*Plus / Minus button on quantity*/
add_action('woocommerce_after_add_to_cart_quantity', 'mold_display_quantity_minus');
function mold_display_quantity_minus()
{
	global $product;
	if ($product->is_type('simple') && !($product->is_sold_individually())) {
		?>
		<div class="quantity-control">
			<button type="button" class="plus">+</button>
			<button type="button" class="minus">-</button>
		</div>
<?php
	}
}

/*add calendar*/
add_action('woocommerce_before_add_to_cart_button', 'mold_add_booking_calendar');
function mold_add_booking_calendar()
{
	global $post;
	$product = wc_get_product($post->ID);
	$is_mold_trip = get_post_meta($post->ID, 'is_mold_trip', true);
	if ($is_mold_trip == 'on') {
		$product_price = $product->get_price();
		if ($product->is_in_stock() && !empty($product_price)) :
			echo '<div class="mold-book-date">';
			echo '<label>' . esc_html__('Date', 'mold-tour') . '</label>';
			echo '<input type="text" name="mold_book_date" value="" class="form-control datepicker" autocomplete="off">';
			echo '</div>';
		endif;
	}
}
