<?php
/**
 * Theme functions
 *
 * @package Hotel_Hamburg
 */

if ( ! function_exists( 'hotel_hamburg_is_abc_active' ) ) :

	/**
	 * Check if ABC is active.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_is_abc_active() {

		return function_exists( 'advanced_booking_calendar_install' ) ? true : false;

	}

endif;

if ( ! function_exists( 'hotel_hamburg_primary_menu_fallback' ) ) :

	/**
	 * Primary menu fallback.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_primary_menu_fallback() {

		echo '<ul>';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'hotel-hamburg' ) . '</a></li>';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 4,
		) );
		echo '</ul>';

	}

endif;

if ( ! function_exists( 'hotel_hamburg_get_the_excerpt' ) ) :

	/**
	 * Fetch excerpt from the post.
	 *
	 * @since 1.0.0
	 *
	 * @param int     $length      Excerpt length.
	 * @param WP_Post $post_object WP_Post instance.
	 * @return string Excerpt content.
	 */
	function hotel_hamburg_get_the_excerpt( $length, $post_object = null ) {

		global $post;

		if ( is_null( $post_object ) ) {
			$post_object = $post;
		}

		$length = absint( $length );

		if ( 0 === $length ) {
			return;
		}

		$source_content = $post_object->post_content;

		if ( ! empty( $post_object->post_excerpt ) ) {
			$source_content = $post_object->post_excerpt;
		}

		$source_content = strip_shortcodes( $source_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );

		return $trimmed_content;
	}

endif;

if ( ! function_exists( 'hotel_hamburg_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_posts_navigation() {

		$pagination_type = hotel_hamburg_get_option( 'pagination_type' );

		switch ( $pagination_type ) {
			case 'default':
				the_posts_navigation();
				break;

			case 'numeric':
				the_posts_pagination();
				break;

			default:
				break;
		}


	}

endif;

