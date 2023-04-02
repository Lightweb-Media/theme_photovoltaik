<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */
/* 
PHOTO ==  www.photovoltaik.sh
*/
define( 'PHOTO_THEME_URL', get_stylesheet_directory_uri() );
define( 'PHOTO_THEME_PATH', get_stylesheet_directory() );
define( 'PHOTO_VERSION', '1.0.0' );

function photo_enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'photo-style', PHOTO_THEME_URL . '/build/main.css', ['parent-style'], filemtime( PHOTO_THEME_PATH . '/build/main.css' ) );
    wp_enqueue_script( 'photo-slider', PHOTO_THEME_URL . '/build/slider.js', [], filemtime( PHOTO_THEME_PATH . '/build/slider.js' ), true );
    wp_enqueue_script( 'photo-caluclator-toggle', PHOTO_THEME_URL . '/build/calculator-toggle.js', [], filemtime( PHOTO_THEME_PATH . '/build/calculator-toggle.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'photo_enqueue_child_theme_styles' );

function backend_assets() {
	wp_enqueue_script( 
        'photo-be-js', 
        PHOTO_THEME_URL . '/build/backend.js', 
        ['wp-block-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-api', 'wp-polyfill'], 
        filemtime( PHOTO_THEME_PATH . '/build/backend.js' ), 
        true 
    );
}
add_action('admin_enqueue_scripts', 'backend_assets');

add_image_size( 'widget-slider-770', 770, 450, true );
add_image_size( 'widget-slider-450', 450, 263, true );

function add_custom_sizes_to_gutenberg( $sizes ) {
  return array_merge( $sizes, [
    'widget-slider-770' => __('Slider 770', 'photo'),
    'widget-slider-450' => __('Slider 450', 'photo'),
  ] );
}
add_filter( 'image_size_names_choose', 'add_custom_sizes_to_gutenberg' );


// includes
require_once PHOTO_THEME_PATH . '/classes/CheckedBy.php';
add_action( 'init', function() {
    new \Threek\CheckedBy;
} );

require_once PHOTO_THEME_PATH . '/shortcodes.php';


// Change 404 Page Title
add_filter( 'generate_404_title','generate_custom_404_title' );
function generate_custom_404_title()
{
      return __('<center>Nichts gefunden</center>', 'photo');
}


// Change 404 Page Text
add_filter( 'generate_404_text','generate_custom_404_text' );
function generate_custom_404_text()
{
      return __('<center>Haben Sie sich verirrt? Nutzen Sie unsere Suche oder klicken Sie auf einen unserer neuesten Beiträge.</center>', 'photo');
}


// Change 404 Page Search Form
function wpdocs_my_search_form( $form ) {
	$form = '<form role="search" method="get" action="/" class="wp-block-search__button-inside wp-block-search__text-button wp-block-search"><label for="wp-block-search__input-1" class="wp-block-search__label screen-reader-text">Suchen</label><div class="wp-block-search__inside-wrapper " ><input type="search" id="wp-block-search__input-1" class="wp-block-search__input wp-block-search__input " name="s" value="" placeholder="Suchen..."  required /><button type="submit" class="wp-block-search__button wp-element-button">Suchen</button></div></form>';

	return $form;
}
add_filter( 'get_search_form', 'wpdocs_my_search_form' );


/* Author Box
function show_author_box(){ 

    global $post;  
    $author_id = get_post_field('post_author' , $post->ID);
    
    // Check if is not 404 Page
    if(!is_404()){
    ?>
        <div class="author-box">
                <div class="author-box-avatar">
                    <img alt=<?php _e("Autorenfoto", "photo"); ?> title=<?php _e("Autorenfoto", "photo"); ?> src=<?php echo get_avatar_url($author_id); ?>/>
                </div>
                <div class="author-box-meta">
                    <div class="author-box_name"><?php echo '<span>'. get_the_author() . '</span>'; ?></div>
                    <div class="author-box_bio">
                        <?php echo get_the_author_meta("description", $author_id); ?>
                    </div>
                </div>
        <?php 
    }
}
add_action('generate_after_content', 'show_author_box');*/


// 3 featured posts on home page
function show_featured_posts(){ 
    if ( is_front_page() && is_home() ) {

        $args = array(
            'cat'      => '224',
            'posts_per_page' => '3'
        );
        
        $featuredPosts = new WP_Query($args);

        ?> <section class="featured-posts"> <?php

        if($featuredPosts->have_posts()){
        while ($featuredPosts->have_posts()) : $featuredPosts->the_post();
        ?>
        <!-- Loop Content ############################################## -->
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_do_microdata( 'article' ); ?>>
	    <div class="inside-article">
		<?php

		if ( generate_show_entry_header() ) : ?>

		<div class="archive-single-featured-image">
        <?php
            do_action( 'generate_after_entry_header' ); ?>
        </div>
        <div class="archive-single-content">
			<header <?php generate_do_attr( 'entry-header' ); ?>>
            <?php
    
			do_shortcode('[categorys]');

				if ( generate_show_title() ) {
					$params = generate_get_the_title_parameters();

					the_title( $params['before'], $params['after'] );
				}
                
                ?>
                <div class="author-info">
                    <?php
                    global $post;  
                    $author_id = get_post_field('post_author' , $post->ID); 
					if(!is_archive()) {$linkToAuthor = '&nbsp;<a href="'.get_author_posts_url($author_id).'">';}
                    echo '<img alt="' . __("Autorenfoto", "photo") . '" title="' . __("Autorenfoto", "photo") . '" src="'.get_avatar_url($author_id).'"/> ' . __("Von ", "photo") . $linkToAuthor . get_author_name($author_id).'</a>';

                    ?>
                </div>
			</header>
			<?php
		endif;

		if ( generate_show_excerpt() ) :
			?>

			<div class="entry-summary">
				<?php the_excerpt() ?>
			</div>

		<?php else : ?>

			<div class="entry-content">
				<?php
				the_content();
				?>
			</div>
        </div>
			<?php
		endif;

		?>
        <div class="read-more"><a href="<?php the_permalink(); ?>"><?php _e('Weiterlesen >', 'photo'); ?></a></div>
	</div>
</article>

        <!-- Loop Content - End ############################################### -->
        <?php
        endwhile;
        }

        ?> </section> <?php
    }
    
}
add_action('generate_after_header', 'show_featured_posts');


add_action( 'generate_before_main_content', function() {
	if ( is_front_page() && is_home() ) {
	?>
	<div class="home-headline">
		<div class="wp-block-group__inner-container">
			<h2><?php _e('Aktuelle Beiträge', 'photo'); ?></h2>
		</div>
	</div>
	<?php
	}
} );


/**** SCHEMA ADDON ***/
add_action( 'after_setup_theme', function () {
    add_filter( 'wp_schema_pro_schema_meta_fields', function ($fields){
        /**
         * Add ArticleBody
         * @param  array $fields Mapping fields array.
         * @return array
         */
        $fields['bsf-aiosrs-article']['subkeys']['articleBody'] = array( // `bsf-aiosrs-book` used for Book, `bsf-aiosrs-event` will for Event like that.
            'label'    => esc_html__( 'articleBody', 'wp-schema-pro' ), // Label to display in Mapping fields
            'type'     => 'text', // text/date/image
            'default'  => 'none',
            'required' => true, // true/false.
        );
     
        return $fields;
    } );
    add_filter( 'wp_schema_pro_schema_article', function ($schema, $data, $post) {
            /**
             * Mapping extra field for schema markup.
             *
             * @param  array $schema Schema array.
             * @param  array $data   Mapping fields array.
             * @return array
             */
        if ( isset( $data['articleBody'] ) && ! empty( $data['articleBody'] ) ) {
            // For date/text type field
            $content = preg_replace( '/<!--(.|\s)*?-->/', '', $data['articleBody'] );
            $content = strip_tags( $content );
            $schema['articleBody'] = esc_html( $content );
            // $schema['articleBody'] = esc_html( $data['articleBody'] );
     
            // For image type field
            // $schema['workExample'] = BSF_AIOSRS_Pro_Schema_Template::get_image_schema( $data['work-example'] );
        }
        return $schema;
    }, 10, 3 );
});
 