<?php

// Shortcode to display the Site Name
add_shortcode( 'site_name','site_name_shortcode' );
function site_name_shortcode()
{
    return get_bloginfo($show = 'name');
}



/* Get Categorys of Post
Output (string): "Cat1, Cat2"
*/
add_shortcode( 'categorys','show_all_categorys_of_post' );
function show_all_categorys_of_post(){
    $post_categories = wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) );
    $names = '';
	if( $post_categories ){
		foreach($post_categories as $key => $name){
            // Check if is not last loop
            if ($key !== array_key_last($post_categories)) {
                $space = ', ';
            }else{
                $space = '';
            }
        
			$names .= $name . $space;
		}
    } 

    echo '<span class="category-list">'. $names .'</span>';
}

// Related Posts (same Category)
add_shortcode( 'related_posts','show_related_posts' );
function show_related_posts(){

    $related = new WP_Query(
        array(
            'category__in'   => wp_get_post_categories( $post->ID ),
            'posts_per_page' => 3,
            'post__not_in'   => array( $post->ID )
        )
    );
    
    if( $related->have_posts() ) { 
        
        $related_posts = sprintf('  <div class="related-posts-title">%s</div>
                                    <div class="related-posts">',
                                    __("Ähnliche Beiträge", "photo")
                                );
    
        while( $related->have_posts() ) { 
            $related->the_post(); 

                $related_posts .= sprintf(' <div class="related-post">
                                            <a href="%s"</a>
                                            <img src="%s" />
                                            </a>
                                            </div>',
                                            get_permalink(get_the_ID()),
                                            get_the_post_thumbnail_url(get_the_ID())
                                        );
            }
    
        $related_posts .= '</div>';
    
        wp_reset_postdata();
    
        echo $related_posts;
    }

}


// Category Description
add_shortcode('cat_description', 'show_cat_description');
function show_cat_description($atts){

    $a = shortcode_atts( array(
        'id' => 0,
    ), $atts );

    return category_description($a['id']);

}

// Aktuelles Jahr
function current_year_shortcode() {
    $jahr = date('Y');
    return $jahr;
}
add_shortcode('aktuelles_jahr', 'current_year_shortcode');

