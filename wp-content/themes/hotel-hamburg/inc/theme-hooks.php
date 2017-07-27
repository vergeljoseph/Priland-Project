<?php
/**
 * Theme hooks
 *
 * @package Hotel_Hamburg
 */

if ( ! function_exists( 'hotel_hamburg_add_featured_slider' ) ) :

	/**
	 * Add featured slider.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_add_featured_slider() {

		if ( ! is_page_template( 'tpl-home.php' ) ) {
			return;
		}

		$slider_status = hotel_hamburg_get_option( 'slider_status' );

		if ( ! $slider_status ) {
			return;
		}

		get_template_part( 'template-parts/slider' );

	}

endif;

add_action( 'hotel_hamburg_after_header', 'hotel_hamburg_add_featured_slider', 12 );

if ( ! function_exists( 'hotel_hamburg_add_home_widget_area' ) ) :

	/**
	 * Add home widget area.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_add_home_widget_area() {

		if ( ! is_page_template( 'tpl-home.php' ) ) {
			return;
		}

		if ( is_active_sidebar( 'sidebar-home-widget-area' ) ) {
			echo '<div id="home-widget-area" class="widget-area">';
			dynamic_sidebar( 'sidebar-home-widget-area' );
			echo '</div><!-- #home-widget-area -->';
		}

	}

endif;

add_action( 'hotel_hamburg_after_header', 'hotel_hamburg_add_home_widget_area', 15 );

if ( ! function_exists( 'hotel_hamburg_register_recommended_plugins' ) ) :

	/**
	 * Register recommended plugins.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_register_recommended_plugins() {

		// Plugins.
		$plugins = array(
			array(
				'name' => esc_html__( 'Advanced Booking Calendar', 'hotel-hamburg' ),
				'slug' => 'advanced-booking-calendar',
			),
		);

		// TGM configurations.
		$config = array();

		// Register now.
		tgmpa( $plugins, $config );

	}

endif;

add_action( 'tgmpa_register', 'hotel_hamburg_register_recommended_plugins' );

if ( ! function_exists( 'hotel_hamburg_custom_excerpt_length' ) ) :

	/**
	 * Custom excerpt length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length Excerpt length.
	 * @return int Custom excerpt length.
	 */
	function hotel_hamburg_custom_excerpt_length( $length ) {

		if ( is_admin() ) {
			return $length;
		}

		$excerpt_length = hotel_hamburg_get_option( 'excerpt_length' );

		if ( absint( $excerpt_length ) > 0 ) {
			$length = absint( $excerpt_length );
		}

		return $length;

	}

endif;

add_filter( 'excerpt_length', 'hotel_hamburg_custom_excerpt_length' );


if ( ! function_exists( 'hotel_hamburg_custom_read_more' ) ) :

	/**
	 * Add read more link.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more Read more string.
	 * @return string Custom more link.
	 */
	function hotel_hamburg_custom_read_more( $more ) {

		if ( is_admin() ) {
			return $more;
		}

		$more_text = hotel_hamburg_get_option( 'more_text' );

		if ( ! empty( $more_text ) ) {
			$more = ' <a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html( $more_text ) . '</a>';
		}

		return $more;
	}

endif;

add_filter( 'excerpt_more', 'hotel_hamburg_custom_read_more' );

if ( ! function_exists( 'hotel_hamburg_exclude_categories_from_listing' ) ) :

	/**
	 * Exclude categories from listing.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $query The WP_Query instance.
	 * @return WP_Query Modified query.
	 */
	function hotel_hamburg_exclude_categories_from_listing( $query ) {

		if ( $query->is_home() && $query->is_main_query() ) {
			$category_exclude = hotel_hamburg_get_option( 'category_exclude' );

			if ( ! empty( $category_exclude ) ) {
				$categories = explode( ',', $category_exclude );
				$categories = array_filter( $categories, 'absint' );

				if ( ! empty( $categories ) ) {
					$exclude_ids = '-' . implode( ',-', $categories );
					$query->set( 'cat', $exclude_ids );
				}

			}

		}

		return $query;

	}

endif;

add_filter( 'pre_get_posts', 'hotel_hamburg_exclude_categories_from_listing' );
