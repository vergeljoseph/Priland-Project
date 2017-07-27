<?php
/**
 * Core functions.
 *
 * @package Hotel_Hamburg
 */

if ( ! function_exists( 'hotel_hamburg_get_option' ) ) :

	/**
	 * Get theme option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function hotel_hamburg_get_option( $key ) {

		$default_options = hotel_hamburg_get_default_theme_options();

		if ( empty( $key ) ) {
			return;
		}

		$default = ( isset( $default_options[ $key ] ) ) ? $default_options[ $key ] : '';
		$theme_options = get_theme_mod( 'theme_options', $default_options );
		$theme_options = array_merge( $default_options, $theme_options );
		$value = '';

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;

	}

endif;

if ( ! function_exists( 'hotel_hamburg_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function hotel_hamburg_get_default_theme_options() {

		$defaults_options = array();

		// Slider.
		$defaults_options['slider_status'] = true;

		// Blog.
		$defaults_options['excerpt_length']   = 40;
		$defaults_options['more_text']        = esc_html__( 'Read more', 'hotel-hamburg' );
		$defaults_options['category_exclude'] = '';

		// Pagination.
		$defaults_options['pagination_type'] = 'default';

		// Footer.
		$defaults_options['copyright_message'] = esc_html__( 'Copyright &copy; All rights reserved.', 'hotel-hamburg' );

		return apply_filters( 'hotel_hamburg_default_theme_options', $defaults_options );
	}

endif;
