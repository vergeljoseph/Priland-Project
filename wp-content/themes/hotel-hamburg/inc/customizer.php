<?php
/**
 * Hotel Hamburg Theme Customizer
 *
 * @package Hotel_Hamburg
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hotel_hamburg_customize_register( $wp_customize ) {

	// Load sanitization helpers.
	require_once get_template_directory() . '/inc/sanitize-helpers.php';

	// Load callback helpers.
	require_once get_template_directory() . '/inc/callback-helpers.php';

	// Load extra controls.
	require_once get_template_directory() . '/inc/extra-controls.php';

	// Register extra controls.
	$wp_customize->register_control_type( 'Hotel_Hamburg_Message_Control' );

	$default = hotel_hamburg_get_default_theme_options();

	// Panel theme options main.
	$wp_customize->add_panel( 'theme_panel_main',
		array(
			'title'    => esc_html__( 'Theme Options', 'hotel-hamburg' ),
			'priority' => 25,
		)
	);

	// Section slider.
	$wp_customize->add_section( 'theme_section_slider',
		array(
			'title' => esc_html__( 'Slider Options', 'hotel-hamburg' ),
			'panel' => 'theme_panel_main',
		)
	);

	// Setting slider_status.
	$wp_customize->add_setting( 'theme_options[slider_status]',
		array(
		'default'           => $default['slider_status'],
		'sanitize_callback' => 'hotel_hamburg_sanitize_checkbox',
		)
	);
	$wp_customize->add_control( 'theme_options[slider_status]',
		array(
		'label'    => esc_html__( 'Enable Slider', 'hotel-hamburg' ),
		'section'  => 'theme_section_slider',
		'type'     => 'checkbox',
		)
	);

	// Setting slider_message.
	$wp_customize->add_setting( 'theme_options[slider_message]',
		array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( new Hotel_Hamburg_Message_Control( $wp_customize, 'theme_options[featured_block_category]',
		array(
		'label'           => esc_html__( 'Note:', 'hotel-hamburg' ),
		'description'     => sprintf( esc_html__( 'Recommended image size for slider: %1$dpx x %2$dpx ', 'hotel-hamburg' ), 1600, 650 ),
		'section'         => 'theme_section_slider',
		'settings'        => 'theme_options[slider_message]',
		'active_callback' => 'hotel_hamburg_is_slider_active',
	) ) );

	for ( $i = 1; $i <= 5; $i++ ) {
		$wp_customize->add_setting( "theme_options[slider_page_$i]",
			array(
				'default'           => isset( $default[ 'slider_page_' . $i ] ) ? $default[ 'slider_page_' . $i ] : '',
				'sanitize_callback' => 'hotel_hamburg_sanitize_dropdown_pages',
			)
		);
		$wp_customize->add_control( "theme_options[slider_page_$i]",
			array(
				'label'           => esc_html__( 'Select Page', 'hotel-hamburg' ) . ' - ' . $i,
				'section'         => 'theme_section_slider',
				'type'            => 'dropdown-pages',
				'active_callback' => 'hotel_hamburg_is_slider_active',
			)
		);
	} // End for().

	// Section blog.
	$wp_customize->add_section( 'theme_section_blog',
		array(
			'title' => esc_html__( 'Blog Options', 'hotel-hamburg' ),
			'panel' => 'theme_panel_main',
		)
	);

	// Setting excerpt_length.
	$wp_customize->add_setting( 'theme_options[excerpt_length]',
		array(
			'default'           => $default['excerpt_length'],
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control( 'theme_options[excerpt_length]',
		array(
			'label'       => esc_html__( 'Excerpt Length', 'hotel-hamburg' ),
			'description' => esc_html__( 'Enter number of words.', 'hotel-hamburg' ),
			'section'     => 'theme_section_blog',
			'type'        => 'number',
		)
	);

	// Setting more_text.
	$wp_customize->add_setting( 'theme_options[more_text]',
		array(
			'default'           => $default['more_text'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'theme_options[more_text]',
		array(
			'label'   => esc_html__( 'More Text', 'hotel-hamburg' ),
			'section' => 'theme_section_blog',
			'type'    => 'text',
		)
	);

	// Setting category_exclude.
	$wp_customize->add_setting( 'theme_options[category_exclude]',
		array(
			'default'           => $default['category_exclude'],
			'sanitize_callback' => 'hotel_hamburg_sanitize_comma_separated_ids',
		)
	);
	$wp_customize->add_control( 'theme_options[category_exclude]',
		array(
			'label'       => esc_html__( 'Exclude Categories', 'hotel-hamburg' ),
			'description' => esc_html__( 'Enter category IDs to exclude in blog listing, separated by comma.', 'hotel-hamburg' ),
			'section'     => 'theme_section_blog',
			'type'        => 'text',
		)
	);

	// Section pagination.
	$wp_customize->add_section( 'theme_section_pagination',
		array(
			'title' => esc_html__( 'Pagination Options', 'hotel-hamburg' ),
			'panel' => 'theme_panel_main',
		)
	);

	// Setting pagination_type.
	$wp_customize->add_setting( 'theme_options[pagination_type]',
		array(
			'default'           => $default['pagination_type'],
			'sanitize_callback' => 'hotel_hamburg_sanitize_select',
		)
	);
	$wp_customize->add_control( 'theme_options[pagination_type]',
		array(
			'label'   => esc_html__( 'Select Type', 'hotel-hamburg' ),
			'section' => 'theme_section_pagination',
			'type'    => 'select',
			'choices' => array(
				'default' => __( 'Default', 'hotel-hamburg' ),
				'numeric' => __( 'Numeric', 'hotel-hamburg' ),
				),
		)
	);

	// Section footer.
	$wp_customize->add_section( 'theme_section_footer',
		array(
			'title' => esc_html__( 'Footer Options', 'hotel-hamburg' ),
			'panel' => 'theme_panel_main',
		)
	);

	// Setting copyright_message.
	$wp_customize->add_setting( 'theme_options[copyright_message]',
		array(
			'default'           => $default['copyright_message'],
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control( 'theme_options[copyright_message]',
		array(
			'label'   => esc_html__( 'Copyright Message', 'hotel-hamburg' ),
			'section' => 'theme_section_footer',
			'type'    => 'text',
		)
	);

}

add_action( 'customize_register', 'hotel_hamburg_customize_register' );

/**
 * Customizer partials.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hotel_hamburg_customizer_partials( WP_Customize_Manager $wp_customize ) {

	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'refresh';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'refresh';
	}

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Partial blogname.
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector'            => '.site-title a',
		'container_inclusive' => false,
		'render_callback'     => 'hotel_hamburg_customize_partial_blogname',
	) );

	// Partial blogdescription.
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector'            => '.site-description',
		'container_inclusive' => false,
		'render_callback'     => 'hotel_hamburg_customize_partial_blogdescription',
	) );

}

add_action( 'customize_register', 'hotel_hamburg_customizer_partials', 99 );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function hotel_hamburg_customize_partial_blogname() {

	bloginfo( 'name' );

}

/**
 * Render the site description for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function hotel_hamburg_customize_partial_blogdescription() {

	bloginfo( 'description' );

}
