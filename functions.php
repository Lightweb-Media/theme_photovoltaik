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
    //wp_enqueue_script( 'photo-slider', PHOTO_THEME_URL . '/build/slider.js', [], filemtime( PHOTO_THEME_PATH . '/build/slider.js' ), true );
    wp_enqueue_script( 'photo-calculator-toggle', PHOTO_THEME_URL . '/build/calculator-toggle.js', [], filemtime( PHOTO_THEME_PATH . '/build/calculator-toggle.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'photo_enqueue_child_theme_styles' );

/*function backend_assets() {
	wp_enqueue_script( 
        'photo-be-js', 
        PHOTO_THEME_URL . '/build/backend.js', 
        ['wp-block-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-api', 'wp-polyfill'], 
        filemtime( PHOTO_THEME_PATH . '/build/backend.js' ), 
        true 
    );
}
add_action('admin_enqueue_scripts', 'backend_assets');
*/

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

 