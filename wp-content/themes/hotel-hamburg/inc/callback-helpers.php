<?php
/**
 * Callback helper functions.
 *
 * @package Hotel_Hamburg
 */

if ( ! function_exists( 'hotel_hamburg_is_slider_active' ) ) :

	/**
	 * Check if slider is active.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 *
	 * @return bool Whether the control is active to the current preview.
	 */
	function hotel_hamburg_is_slider_active( $control ) {

		if ( true === $control->manager->get_setting( 'theme_options[slider_status]' )->value() ) {
			return true;
		} else {
			return false;
		}

	}

endif;
