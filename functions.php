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
	wp_enqueue_style( 'photo-fonts', PHOTO_THEME_URL . '/build/fonts.scss.css', ['parent-style'], filemtime( PHOTO_THEME_PATH . '/build/fonts.scss.css' ) );
    wp_enqueue_script( 'photo-pv-form-auto-next', PHOTO_THEME_URL . '/build/pv-form-auto-next.js', [], filemtime( PHOTO_THEME_PATH . '/build/pv-form-auto-next.js' ), true );
    wp_enqueue_script( 'photo-calculator-toggle', PHOTO_THEME_URL . '/build/calculator-toggle.js', [], filemtime( PHOTO_THEME_PATH . '/build/calculator-toggle.js' ), true );
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
 * @author Mike Hemberger
 * @link http://thestizmedia.com/acf-pro-simple-local-avatars/
 * @uses ACF Pro image field (tested return value set as Array )
 */
add_filter('get_avatar', 'lwm_profile_avatar', 10, 5);

function lwm_profile_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

    $user = '';

    // Get user by id or email
    if ( is_numeric( $id_or_email ) ) {

        $id   = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id   = (int) $id_or_email->user_id;
            $user = get_user_by( 'id' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email );
    }

    if ( ! $user ) {
        return $avatar;
    }

    // Get the user id
    $user_id = $user->ID;

    // Get the file id
    $image_id = get_user_meta($user_id, 'lwm_local_avatar', true); // CHANGE TO YOUR FIELD NAME

    // Bail if we don't have a local avatar
    if ( ! $image_id ) {
        return $avatar;
    }

    // Get the file size
    $image_url  = wp_get_attachment_image_src( $image_id, 'thumbnail' ); // Set image size by name

    // Get the file url
    $avatar_url = $image_url[0];
    // Get the img markup
    $avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '"/>';
    // Return our new avatar
    return $avatar;
}

