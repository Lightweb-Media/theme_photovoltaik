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

    $related_posts = '';
    
    if( $related->have_posts() ) { 
        
        ob_start();

        ?>

        <div class="related-posts-title"><?php _e("Ähnliche Beiträge", "photo"); ?></div>
        <div class="related-posts">
                  
        <?php
        while( $related->have_posts() ) { 
            $related->the_post(); 
            ?>

            <div class="relared-post">
                <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                    <?php echo get_the_post_thumbnail(); ?>
                </a>
            </div>

            <?php
                /* $related_posts .= sprintf(' <div class="related-post">
                                            <a href="%s" title="%s">
                                            <img src="%s" alt="%s" />
                                            </a>
                                            </div>',
                                            get_permalink(get_the_ID()),
                                            get_the_title(),
                                            get_the_post_thumbnail_url(get_the_ID()),
                                            get_the_title()
                                        ); */
            }
    
        echo '</div>';

        $related_posts = ob_get_contents();
        ob_end_clean();
    
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

