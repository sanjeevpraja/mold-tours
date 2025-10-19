<?php
// Add submenu under "Tours"
add_action('admin_menu', 'mold_add_tour_settings_submenu');
function mold_add_tour_settings_submenu()
{
    add_submenu_page(
        'edit.php?post_type=tour',     // Parent: your CPT
        __('Tour Settings', 'wp-mold'), // Page title
        __('Settings', 'wp-mold'),      // Menu title
        'manage_options',               // Capability
        'tour-settings',                // Slug
        'mold_render_tour_settings_page' // Callback
    );
}

// Render the Tour Settings page
function mold_render_tour_settings_page()
{
?>
    <div class="wrap">
        <h1><?php _e('Tour Settings', 'wp-mold'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tour_settings_group');
            do_settings_sections('tour-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
add_action('admin_init', 'mold_register_tour_settings');
function mold_register_tour_settings()
{
    // Register options
    register_setting('tour_settings_group', 'tour_currency');
    register_setting('tour_settings_group', 'tour_thousand_separator');
    register_setting('tour_settings_group', 'tour_decimal_separator');
    register_setting('tour_settings_group', 'tour_number_of_decimals');

    // Section
    add_settings_section(
        'tour_general_section',
        '',
        '',
        'tour-settings'
    );


    // ✅ Currency Field
    add_settings_field(
        'tour_currency',
        __('Currency', 'wp-mold'),
        function () {
            $selected = get_option('tour_currency', 'USD');
            $currencies = array(
                'USD' => 'US Dollar ($)',
                'EUR' => 'Euro (€)',
                'GBP' => 'British Pound (£)',
                'NPR' => 'Nepalese Rupee (Rs)',
                'INR' => 'Indian Rupee (₹)',
                'AUD' => 'Australian Dollar (A$)',
                'CAD' => 'Canadian Dollar (C$)',
                'JPY' => 'Japanese Yen (¥)',
                'CNY' => 'Chinese Yuan (¥)',
                'CHF' => 'Swiss Franc (CHF)',
                'SGD' => 'Singapore Dollar (S$)',
                'HKD' => 'Hong Kong Dollar (HK$)',
                'NZD' => 'New Zealand Dollar (NZ$)',
                'SEK' => 'Swedish Krona (kr)',
                'NOK' => 'Norwegian Krone (kr)',
                'DKK' => 'Danish Krone (kr)',
                'AED' => 'UAE Dirham (د.إ)',
                'SAR' => 'Saudi Riyal (﷼)',
                'QAR' => 'Qatari Riyal (ر.ق)',
                'OMR' => 'Omani Rial (﷼)',
                'KWD' => 'Kuwaiti Dinar (KD)',
                'BHD' => 'Bahraini Dinar (BD)',
                'PKR' => 'Pakistani Rupee (₨)',
                'BDT' => 'Bangladeshi Taka (৳)',
                'LKR' => 'Sri Lankan Rupee (Rs)',
                'MMK' => 'Myanmar Kyat (Ks)',
                'THB' => 'Thai Baht (฿)',
                'IDR' => 'Indonesian Rupiah (Rp)',
                'MYR' => 'Malaysian Ringgit (RM)',
                'PHP' => 'Philippine Peso (₱)',
                'VND' => 'Vietnamese Dong (₫)',
                'KRW' => 'South Korean Won (₩)',
                'TRY' => 'Turkish Lira (₺)',
                'RUB' => 'Russian Ruble (₽)',
                'PLN' => 'Polish Zloty (zł)',
                'CZK' => 'Czech Koruna (Kč)',
                'HUF' => 'Hungarian Forint (Ft)',
                'RON' => 'Romanian Leu (lei)',
                'ILS' => 'Israeli Shekel (₪)',
                'EGP' => 'Egyptian Pound (E£)',
                'MAD' => 'Moroccan Dirham (د.م.)',
                'ZAR' => 'South African Rand (R)',
                'KES' => 'Kenyan Shilling (KSh)',
                'NGN' => 'Nigerian Naira (₦)',
                'GHS' => 'Ghanaian Cedi (₵)',
                'TZS' => 'Tanzanian Shilling (TSh)',
                'UGX' => 'Ugandan Shilling (USh)',
                'RWF' => 'Rwandan Franc (FRw)',
                'XOF' => 'West African CFA Franc (CFA)',
                'XAF' => 'Central African CFA Franc (FCFA)',
                'MXN' => 'Mexican Peso (MX$)',
                'BRL' => 'Brazilian Real (R$)',
                'ARS' => 'Argentine Peso (AR$)',
                'CLP' => 'Chilean Peso (CLP$)',
                'COP' => 'Colombian Peso (COP$)',
                'PEN' => 'Peruvian Sol (S/)',
                'UYU' => 'Uruguayan Peso ($U)',
                'VEF' => 'Venezuelan Bolívar (Bs)',
                'BOB' => 'Bolivian Boliviano (Bs)',
                'PYG' => 'Paraguayan Guaraní (₲)',
                'BWP' => 'Botswana Pula (P)',
                'MZN' => 'Mozambican Metical (MT)',
                'ETB' => 'Ethiopian Birr (Br)',
                'DZD' => 'Algerian Dinar (د.ج)',
                'TND' => 'Tunisian Dinar (د.ت)',
                'LBP' => 'Lebanese Pound (ل.ل)',
                'JOD' => 'Jordanian Dinar (JD)',
                'IQD' => 'Iraqi Dinar (ع.د)',
                'IRR' => 'Iranian Rial (﷼)',
                'AFN' => 'Afghan Afghani (؋)',
                'MVR' => 'Maldivian Rufiyaa (Rf)',
                'SCR' => 'Seychellois Rupee (₨)',
                'MUR' => 'Mauritian Rupee (₨)',
                'BND' => 'Brunei Dollar (B$)',
                'LAK' => 'Lao Kip (₭)',
                'KHR' => 'Cambodian Riel (៛)',
                'FJD' => 'Fijian Dollar (FJ$)',
                'PGK' => 'Papua New Guinean Kina (K)',
                'SBD' => 'Solomon Islands Dollar (SI$)',
                'VUV' => 'Vanuatu Vatu (VT)',
                'TOP' => 'Tongan Paʻanga (T$)',
                'WST' => 'Samoan Tala (WS$)',
                'XPF' => 'CFP Franc (₣)',
                'MDL' => 'Moldovan Leu (L)',
                'UAH' => 'Ukrainian Hryvnia (₴)',
                'GEL' => 'Georgian Lari (₾)',
                'AZN' => 'Azerbaijani Manat (₼)',
                'KZT' => 'Kazakhstani Tenge (₸)',
                'UZS' => 'Uzbekistani Som (soʻm)',
                'TJS' => 'Tajikistani Somoni (ЅМ)',
                'AMD' => 'Armenian Dram (֏)',
                'BYN' => 'Belarusian Ruble (Br)',
                'BGN' => 'Bulgarian Lev (лв)',
                'HRK' => 'Croatian Kuna (kn)',
                'ISK' => 'Icelandic Króna (kr)',
            );
    ?>
        <input list="tour_currency_list" name="tour_currency" value="<?php echo esc_attr($selected); ?>" style="width:300px;">
        <datalist id="tour_currency_list">
            <?php foreach ($currencies as $code => $label) : ?>
                <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </datalist>


<?php
        },
        'tour-settings',
        'tour_general_section'
    );


    add_settings_field(
        'tour_thousand_separator',
        __('Thousand Separator', 'wp-mold'),
        function () {
            $value = get_option('tour_thousand_separator', ',');
            echo '<input type="text" name="tour_thousand_separator" value="' . esc_attr($value) . '" maxlength="1" style="width:60px; text-align:center;">';
            echo '<p class="description">' . __('Character used to separate thousands (e.g., 1,000 or 1.000).', 'wp-mold') . '</p>';
        },
        'tour-settings',
        'tour_general_section'
    );

    add_settings_field(
        'tour_decimal_separator',
        __('Decimal Separator', 'wp-mold'),
        function () {
            $value = get_option('tour_decimal_separator', '.');
            echo '<input type="text" name="tour_decimal_separator" value="' . esc_attr($value) . '" maxlength="1" style="width:60px; text-align:center;">';
            echo '<p class="description">' . __('Character used for decimals (e.g., 1.50 or 1,50).', 'wp-mold') . '</p>';
        },
        'tour-settings',
        'tour_general_section'
    );

    add_settings_field(
        'tour_number_of_decimals',
        __('Number of Decimals', 'wp-mold'),
        function () {
            $value = get_option('tour_number_of_decimals', 2);
            echo '<input type="number" name="tour_number_of_decimals" value="' . esc_attr($value) . '" min="0" max="6" step="1" style="width:80px;">';
            echo '<p class="description">' . __('Number of digits to display after the decimal point (e.g., 2 for 1.50).', 'wp-mold') . '</p>';
        },
        'tour-settings',
        'tour_general_section'
    );
}

?>