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

	wp_enqueue_style( 'photo-fonts', PHOTO_THEME_URL . '/fonts/fonts.css', ['parent-style'], filemtime( PHOTO_THEME_PATH . '/fonts/fonts.css' ) );

    wp_enqueue_style( 'photo-style', PHOTO_THEME_URL . '/dist/main.css', ['parent-style'], filemtime( PHOTO_THEME_PATH . '/dist/main.css' ) );
    
	wp_enqueue_script( 'photo-pv-form-auto-next', PHOTO_THEME_URL . '/dist/pv-form-auto-next.js', [], filemtime( PHOTO_THEME_PATH . '/dist/pv-form-auto-next.js' ), true );
    wp_enqueue_script( 'photo-calculator-toggle', PHOTO_THEME_URL . '/dist/calculator-toggle.js', [], filemtime( PHOTO_THEME_PATH . '/dist/calculator-toggle.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'photo_enqueue_child_theme_styles' );

require_once PHOTO_THEME_PATH . '/shortcodes.php';


/**** SCHEMA ADDON ***/
add_action( 'after_setup_theme', 'add_my_custom_meta_field' );
function add_my_custom_meta_field() {
	add_filter( 'wp_schema_pro_schema_meta_fields', 'my_extra_schema_field_language' );
	add_filter( 'wp_schema_pro_schema_meta_fields', 'my_extra_schema_field_article' );
	add_filter( 'wp_schema_pro_schema_article', 'my_extra_schema_field_mapping_language', 10, 3 );
	add_filter( 'wp_schema_pro_schema_article', 'my_extra_schema_field_mapping_articleBody', 10, 3 );
}
 
/**
 * Add inLanguage
 * @param  array $fields Mapping fields array.
 * @return array
 */
function my_extra_schema_field_language( $fields ) {
	$fields['bsf-aiosrs-article']['subkeys']['inLanguage'] = array( // `bsf-aiosrs-book` used for Book, `bsf-aiosrs-event` will for Event like that.
		'label'    => esc_html__( 'inLanguage', 'wp-schema-pro' ), // Label to display in Mapping fields
		'type'     => 'text', // text/date/image
		'default'  => 'de-DE',
		'required' => true, // true/false.
	);
 
	return $fields;
}
 
/**
 * Mapping extra field inLanguage for schema markup.
 *
 * @param  array $schema Schema array.
 * @param  array $data   Mapping fields array.
 * @return array
 */
function my_extra_schema_field_mapping_language( $schema, $data, $post ) {
 
	if ( isset( $data['inLanguage'] ) && ! empty( $data['inLanguage'] ) ) {
		// For date/text type field
		$schema['inLanguage'] = esc_html( $data['inLanguage'] );
 
		// For image type field
		// $schema['workExample'] = BSF_AIOSRS_Pro_Schema_Template::get_image_schema( $data['work-example'] );
	}
	return $schema;
}
 
 
/**
 * Add ArticleBody
 * @param  array $fields Mapping fields array.
 * @return array
 */
function my_extra_schema_field_article( $fields ) {
	$fields['bsf-aiosrs-article']['subkeys']['articleBody'] = array( // `bsf-aiosrs-book` used for Book, `bsf-aiosrs-event` will for Event like that.
		'label'    => esc_html__( 'articleBody', 'wp-schema-pro' ), // Label to display in Mapping fields
		'type'     => 'text', // text/date/image
		'default'  => 'none',
		'required' => true, // true/false.
	);
 
	return $fields;
}
 
/**
 * Mapping extra field for schema markup.
 *
 * @param  array $schema Schema array.
 * @param  array $data   Mapping fields array.
 * @return array
 */
function my_extra_schema_field_mapping_articleBody( $schema, $data, $post ) {
 
	if ( isset( $data['articleBody'] ) && ! empty( $data['articleBody'] ) ) {
		// For date/text type field
		$schema['articleBody'] = esc_html( $data['articleBody'] );
 
		// For image type field
		// $schema['workExample'] = BSF_AIOSRS_Pro_Schema_Template::get_image_schema( $data['work-example'] );
	}
	return $schema;
}





// Local Avatar
/**
 * Use ACF image field as avatar
 * @author Rene Kutter
 * @uses ACF Pro image field (tested return value set as Array )
 */
add_filter('get_avatar', 'lwm_profile_avatar', 10, 5);

function lwm_profile_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

    // Get the user id
    $user_id = get_current_user_id();

    // Get the file id from ACF Field
    $image_id = get_user_meta($user_id, 'lwm_local_avatar', true); // CHANGE TO YOUR FIELD NAME

    // Bail if we don't have a local avatar
    if ( ! $image_id ) {
        return $avatar;
    }

    // Get the file size
    $image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' ); // Set image size by name

    // Get the file url
    $avatar_url = $image_url[0];

    // Get the img markup
    $avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '"/>';

    // Return our new avatar
    return $avatar;
}


add_action( 'load-profile.php', function(){
   add_filter( 'option_show_avatars', '__return_false' );
} );


/**
 * get custom author avatar
 * 
 */
add_shortcode('custom_avatar', function() {
	$avatar = get_the_author_meta('lwm_local_avatar');

	return  '<div class="custom-avatar">' . wp_get_attachment_image( $avatar, 'thumbnail' ) . '</div>';
});

/**
 * dequeues
 * 
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

add_action( 'wp_head', function() {
    if( empty( get_current_user_id() ) ) {
        wp_dequeue_style('dashicons');
    }

}, 1 );

/**
 * gsc cls fix
 * 
 */
add_action( 'wp_head', function() {
?>
	<style>
		.pv-calculator {
			display: none;
		}
	</style>
<?php
} );