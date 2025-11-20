<?php
// render.php
// Block rendering function

function callback_block_grade_icon($attributes, $content)
{
    if (has_term('', 'grade')) {
        $terms = get_the_terms(get_the_ID(), 'grade');

        if ($terms && ! is_wp_error($terms)) {

            $term  = $terms[0];                     // first grade
            $grade = $term->name;                   // grade name
            $url   = get_term_link($term, 'grade'); // taxonomy archive URL

            return '<a href="' . esc_url($url) . '" class="grade-value">
                <span class="material-symbols-outlined grade-icon">speed</span>
                ' . esc_html($grade) . '
            </a>';
        }

        return '';
    }
    return '';
}
