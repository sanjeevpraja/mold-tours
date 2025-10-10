<?php
   echo '<ul class="trip-overview">';
   $terms = get_the_terms( $post->ID , 'grade' );
   $grade_term = get_the_term_list( $post->ID , 'grade', '', ', ', '');
   if($terms) {
    foreach( $terms as $term ) {
        $grade = $term->name;
        $icon_class = get_term_meta ( $term->term_id, 'grade-icon-id', true );
    }
}

$category = wc_get_product_category_list( $post->ID, ', ');
$cat_string = strip_tags($category);
if($cat_string != 'Uncategorized'){
    echo '<li>';
    echo '<span class="icon-barcode"></span>';
    echo '<div class="detail">';
    echo '<div class="title">'.__('Category', 'mold-tour').'</div>';
    echo '<div class="desc">'.$category .'</div></div></li>';
}

$location = get_the_term_list( $post->ID, 'location', '', ', ', '' );
if($location != ''){
    echo '<li>';
    echo '<span class="icon-earth"></span>';
    echo '<div class="detail">';
    echo '<div class="title">'.__('Location', 'mold-tour').'</div>';
    echo '<div class="desc">'.$location.'</div></div></li>';
}

if (!empty($grade)){
    echo '<li>';
    echo '<span class="'.$icon_class.'"></span>';
    echo '<div class="detail">';
    echo '<div class="title">'.__('Grade', 'mold-tour').'</div>';
    echo '<div class="desc">'.$grade_term.'</div></div></li>';
}

if(is_array($mold_trip_overview)){
    foreach($mold_trip_overview  as $overview){
        echo '<li>';
        if(is_array($overview)){
            foreach($overview as $key => $value){
                if($key == 'icon'){
                    echo '<span class="'.$value.'"></span>';
                }
                else if($key == 'title'){
                    echo '<div class="detail">';
                    echo '<div class="title">'.$value.'</div>';
                }
                else if($key == 'value'){
                    echo '<div class="desc">'.$value.'</div>';
                    echo '</div>';
                }
                else{
                }
            }
        }
        echo '</li>';
    }
}
echo '</ul>';

?>