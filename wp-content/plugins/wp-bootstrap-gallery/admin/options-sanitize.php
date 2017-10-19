<?php

/* Text */

add_filter( 'wpb_of_sanitize_text', 'sanitize_text_field' );

/* Textarea */

function wpb_of_sanitize_textarea($input) {
	global $allowedposttags;
	$output = wp_kses( $input, $allowedposttags);
	return $output;
}

add_filter( 'wpb_of_sanitize_textarea', 'wpb_of_sanitize_textarea' );

/* Info */

add_filter( 'wpb_of_sanitize_info', 'wpb_of_sanitize_allowedposttags' );

/* Select */

add_filter( 'wpb_of_sanitize_select', 'wpb_of_sanitize_enum', 10, 2);

/* Radio */

add_filter( 'wpb_of_sanitize_radio', 'wpb_of_sanitize_enum', 10, 2);

/* Images */

add_filter( 'wpb_of_sanitize_images', 'wpb_of_sanitize_enum', 10, 2);

/* Checkbox */

function wpb_of_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = "1";
	} else {
		$output = "0";
	}
	return $output;
}
add_filter( 'wpb_of_sanitize_checkbox', 'wpb_of_sanitize_checkbox' );

/* Multicheck */

function wpb_of_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1"; 
			}
		}
	}
	return $output;
}
add_filter( 'wpb_of_sanitize_multicheck', 'wpb_of_sanitize_multicheck', 10, 2 );

/* Color Picker */

add_filter( 'wpb_of_sanitize_color', 'wpb_of_sanitize_hex' );

/* Uploader */

function wpb_of_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype($input);
	if ( $filetype["ext"] ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'wpb_of_sanitize_upload', 'wpb_of_sanitize_upload' );

/* Allowed Tags */

function wpb_of_sanitize_allowedtags($input) {
	global $allowedtags;
	$output = wpautop(wp_kses( $input, $allowedtags));
	return $output;
}

add_filter( 'wpb_of_sanitize_info', 'wpb_of_sanitize_allowedtags' );

/* Allowed Post Tags */

function wpb_of_sanitize_allowedposttags($input) {
	global $allowedposttags;
	$output = wpautop(wp_kses( $input, $allowedposttags));
	return $output;
}

add_filter( 'wpb_of_sanitize_info', 'wpb_of_sanitize_allowedposttags' );


/* Check that the key value sent is valid */

function wpb_of_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}

/* Background */

function wpb_of_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );

	$output['color'] = apply_filters( 'wpb_of_sanitize_hex', $input['color'] );
	$output['image'] = apply_filters( 'wpb_of_sanitize_upload', $input['image'] );
	$output['repeat'] = apply_filters( 'wpb_of_background_repeat', $input['repeat'] );
	$output['position'] = apply_filters( 'wpb_of_background_position', $input['position'] );
	$output['attachment'] = apply_filters( 'wpb_of_background_attachment', $input['attachment'] );

	return $output;
}
add_filter( 'wpb_of_sanitize_background', 'wpb_of_sanitize_background' );

function wpb_of_sanitize_background_repeat( $value ) {
	$recognized = wpb_of_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wpb_of_default_background_repeat', current( $recognized ) );
}
add_filter( 'wpb_of_background_repeat', 'wpb_of_sanitize_background_repeat' );

function wpb_of_sanitize_background_position( $value ) {
	$recognized = wpb_of_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wpb_of_default_background_position', current( $recognized ) );
}
add_filter( 'wpb_of_background_position', 'wpb_of_sanitize_background_position' );

function wpb_of_sanitize_background_attachment( $value ) {
	$recognized = wpb_of_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wpb_of_default_background_attachment', current( $recognized ) );
}
add_filter( 'wpb_of_background_attachment', 'wpb_of_sanitize_background_attachment' );


/* Typography */

function wpb_of_sanitize_typography( $input ) {
	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'style' => '',
		'color' => ''
	) );

	$output['size']  = apply_filters( 'wpb_of_font_size', $output['size'] );
	$output['face']  = apply_filters( 'wpb_of_font_face', $output['face'] );
	$output['style'] = apply_filters( 'wpb_of_font_style', $output['style'] );
	$output['color'] = apply_filters( 'wpb_of_color', $output['color'] );

	return $output;
}
add_filter( 'wpb_of_sanitize_typography', 'wpb_of_sanitize_typography' );


function wpb_of_sanitize_font_size( $value ) {
	$recognized = wpb_of_recognized_font_sizes();
	$value = preg_replace('/px/','', $value);
	if ( in_array( (int) $value, $recognized ) ) {
		return (int) $value;
	}
	return (int) apply_filters( 'wpb_of_default_font_size', $recognized );
}
add_filter( 'wpb_of_font_face', 'wpb_of_sanitize_font_face' );


function wpb_of_sanitize_font_style( $value ) {
	$recognized = wpb_of_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wpb_of_default_font_style', current( $recognized ) );
}
add_filter( 'wpb_of_font_style', 'wpb_of_sanitize_font_style' );


function wpb_of_sanitize_font_face( $value ) {
	$recognized = wpb_of_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'wpb_of_default_font_face', current( $recognized ) );
}
add_filter( 'wpb_of_font_face', 'wpb_of_sanitize_font_face' );

/**
 * Get recognized background repeat settings
 *
 * @return   array
 *
 */
function wpb_of_recognized_background_repeat() {
	$default = array(
		'no-repeat' => 'No Repeat',
		'repeat-x'  => 'Repeat Horizontally',
		'repeat-y'  => 'Repeat Vertically',
		'repeat'    => 'Repeat All',
		);
	return apply_filters( 'wpb_of_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @return   array
 *
 */
function wpb_of_recognized_background_position() {
	$default = array(
		'top left'      => 'Top Left',
		'top center'    => 'Top Center',
		'top right'     => 'Top Right',
		'center left'   => 'Middle Left',
		'center center' => 'Middle Center',
		'center right'  => 'Middle Right',
		'bottom left'   => 'Bottom Left',
		'bottom center' => 'Bottom Center',
		'bottom right'  => 'Bottom Right'
		);
	return apply_filters( 'wpb_of_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @return   array
 *
 */
function wpb_of_recognized_background_attachment() {
	$default = array(
		'scroll' => 'Scroll Normally',
		'fixed'  => 'Fixed in Place'
		);
	return apply_filters( 'wpb_of_recognized_background_attachment', $default );
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */
 
function wpb_of_sanitize_hex( $hex, $default = '' ) {
	if ( wpb_of_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return   array
 */
 
function wpb_of_recognized_font_sizes() {
	$sizes = range( 9, 71 );
	$sizes = apply_filters( 'wpb_of_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function wpb_of_recognized_font_faces() {
	$default = array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva',
		'trebuchet' => 'Trebuchet',
		'georgia'   => 'Georgia',
		'times'     => 'Times New Roman',
		'tahoma'    => 'Tahoma, Geneva',
		'palatino'  => 'Palatino',
		'helvetica' => 'Helvetica*'
		);
	return apply_filters( 'wpb_of_recognized_font_faces', $default );
}

/**
 * Get recognized font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function wpb_of_recognized_font_styles() {
	$default = array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold italic' => 'Bold Italic'
		);
	return apply_filters( 'wpb_of_recognized_font_styles', $default );
}

/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */
 
function wpb_of_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}