<?php

// Related Posts (same Category)
add_shortcode( 'related_posts','show_related_posts' );
function show_related_posts($atts){

    $atts = shortcode_atts( array(
        'posts_per_page' => 3,
    ), $atts );

    $related = new WP_Query(
        array(
            'category__in'   => wp_get_post_categories( $post->ID ),
            'posts_per_page' => $atts['posts_per_page'],
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
                                            <a href="%s" title="%s">
                                            <img src="%s" alt="%s" />
                                            </a>
                                            </div>',
                                            get_permalink(get_the_ID()),
                                            get_the_title(),
                                            get_the_post_thumbnail_url(get_the_ID()),
                                            get_the_title()
                                        );
            }
    
        $related_posts .= '</div>';
    
        wp_reset_postdata();
    
        return $related_posts;
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
    return date('Y');
}
add_shortcode('aktuelles_jahr', 'current_year_shortcode');

