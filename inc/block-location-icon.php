<?php
// render.php
// Block rendering function

function callback_block_location_icon($attributes, $content)
{
    if (has_term('', 'location')) {
        $terms = get_the_terms(get_the_ID(), 'location');

        if ($terms && ! is_wp_error($terms)) {

            $term     = $terms[0];                       // first location term
            $location = $term->name;                     // location name
            $url      = get_term_link($term, 'location'); // taxonomy archive URL

            return '<a href="' . esc_url($url) . '" class="location-value">
                <span class="material-symbols-outlined location-icon">location_on</span>
                ' . esc_html($location) . '
            </a>';
        }

        return '';
    }
    return '';
}
