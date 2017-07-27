<?php
/**
 * Sanitization helper functions.
 *
 * @package Hotel_Hamburg
 */

/**
 * Sanitize checkbox.
 *
 * @since 1.0.0
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function hotel_hamburg_sanitize_checkbox( $checked ) {

	return ( ( isset( $checked ) && true === $checked ) ? true : false );

}

/**
 * Sanitize select.
 *
 * @since 1.0.0
 *
 * @param mixed                $input The value to sanitize.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return mixed Sanitized value.
 */
function hotel_hamburg_sanitize_select( $input, $setting ) {

	// Cleanup the input.
	$input = sanitize_text_field( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

}

/**
 * Sanitize positive integer.
 *
 * @since 1.0.0
 *
 * @param int                  $input Number to sanitize.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return int Sanitized number; otherwise, the setting default.
 */
function hotel_hamburg_sanitize_positive_integer( $input, $setting ) {

	$input = absint( $input );

	// If the input is an absolute integer, return it.
	// otherwise, return the default.
	return ( $input ? $input : $setting->default );

}

/**
 * Sanitize dropdown pages.
 *
 * @since 1.0.0
 *
 * @param int                  $page_id Page ID.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return int|string Page ID if the page is published; otherwise, the setting default.
 */
function hotel_hamburg_sanitize_dropdown_pages( $page_id, $setting ) {

	// Ensure $input is an absolute integer.
	$page_id = absint( $page_id );

	// If $page_id is an ID of a published page, return it; otherwise, return the default.
	return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );

}

/**
 * Sanitize image.
 *
 * @since 1.0.0
 *
 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
 *
 * @param string               $image Image filename.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return string The image filename if the extension is allowed; otherwise, the setting default.
 */
function hotel_hamburg_sanitize_image( $image, $setting ) {

	/**
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types().
	*/
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',
	);

	// Return an array with file extension and mime_type.
	$file = wp_check_filetype( $image, $mimes );

	// If $image has a valid mime_type, return it; otherwise, return the default.
	return ( $file['ext'] ? esc_url_raw( $image ) : $setting->default );

}

/**
 * Sanitize textarea.
 *
 * @since 1.0.0
 *
 * @param string               $input Content to be sanitized.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return string Sanitized content.
 */
function hotel_hamburg_sanitize_textarea( $input, $setting ) {

	return wp_kses_post( $input );

}

/**
 * Sanitize comma separated ids.
 *
 * @since 1.0.0
 *
 * @param string               $input Content to be sanitized.
 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
 * @return string Sanitized content.
 */
function hotel_hamburg_sanitize_comma_separated_ids( $input, $setting ) {

	$output = '';

	$ids = explode( ',', $input );

	if ( ! empty( $ids ) && is_array( $ids ) ) {
		$new_ids = array();
		foreach ( $ids as $n ) {
			if ( absint( $n ) > 0 ) {
				$new_ids[] = absint( $n );
			}
		}
		if ( ! empty( $new_ids ) ) {
			$output = implode( ',', $new_ids );
		}
	}

	return $output;

}
