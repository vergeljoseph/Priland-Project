<?php
/**
 * Theme widgets
 *
 * @package Hotel_Hamburg
 */

if ( ! function_exists( 'hotel_hamburg_register_widgets' ) ) :

	/**
	 * Register theme widgets.
	 *
	 * @since 1.0.0
	 */
	function hotel_hamburg_register_widgets() {

		// Testimonials widget.
		register_widget( 'Hotel_Hamburg_Testimonials_Widget' );

		// Location widget.
		register_widget( 'Hotel_Hamburg_Location_Widget' );

		if ( function_exists( 'advanced_booking_calendar_install' ) ) {

			// Rooms widget.
			register_widget( 'Hotel_Hamburg_Rooms_Widget' );
		}
	}

endif;

add_action( 'widgets_init', 'hotel_hamburg_register_widgets' );

if ( ! class_exists( 'Hotel_Hamburg_Rooms_Widget' ) ) :

	/**
	 * Rooms widget.
	 *
	 * @since 1.0.0
	 */
	class Hotel_Hamburg_Rooms_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'hotel_hamburg_widget_rooms',
				'description'                 => esc_html__( 'Rooms Widget', 'hotel-hamburg' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'hotel-hamburg-rooms', esc_html__( 'Rooms', 'hotel-hamburg' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title    = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
			$view_more_text = ! empty( $instance['view_more_text'] ) ? $instance['view_more_text'] : '';
			$view_more_url = ! empty( $instance['view_more_url'] ) ? $instance['view_more_url'] : '';

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}

			// Render widget subtitle.
			if ( $subtitle ) {
				echo '<p class="widget-subtitle">' . esc_html( $subtitle ) . '</p>';
			}

			$rooms = $this->get_rooms();
			?>
			<div class="rooms-widget rooms-col-3">
				<div class="container">
					<?php if ( ! empty( $rooms ) ) : ?>
						<div class="inner-wrapper">
							<?php foreach ( $rooms as $room ) : ?>
								<div class="rooms-item">
									<div class="rooms-thumb">
										<?php if ( ! empty( $room['image'] ) ) : ?>
											<img src="<?php echo esc_url( $room['image'][0] ); ?>" alt="" />
										<?php else : ?>
											<img src="<?php echo get_template_directory_uri() . '/images/no-image.png'; ?>" alt="" />
										<?php endif; ?>
										<div class="room-price"><?php esc_html_e( 'From', 'hotel-hamburg' ); ?>&nbsp;<?php echo esc_html( abc_booking_formatPrice( $room['pricePreset'] ) ); ?></div>
									</div><!-- .rooms-thumb -->
									<div class="rooms-text-content">
										<h3 class="rooms-title">
										<?php if ( isset( $room['infoPage'] ) && absint( $room['infoPage'] ) > 0 ) : ?>
											<a href="<?php echo esc_url( get_permalink( absint( $room['infoPage'] ) ) ); ?>"><?php echo esc_html( $room['title'] ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $room['title'] ); ?>
										<?php endif; ?>
										</h3>
									</div><!-- .rooms-text-content -->
								</div><!-- .rooms-item -->
							<?php endforeach; ?>
						</div><!-- .inner-wrapper -->
					<?php endif; ?>
					<?php if ( ! empty( $view_more_text ) && ! empty( $view_more_url ) ) : ?>
						<div class="more-button-wrapper">
							<a href="<?php echo esc_url( $view_more_url ); ?>" class="more-button"><?php echo esc_html( $view_more_text ); ?></a>
						</div> <!-- .more-button-wrapper -->
					<?php endif; ?>
				</div><!-- .container -->
			</div><!-- .rooms-widget -->

			<?php

			echo $args['after_widget'];

		}

		/**
		 * Get rooms.
		 *
		 * @since 1.0.0
		 *
		 * @return array Rooms details.
		 */
		function get_rooms() {
			global $wpdb;

			$output = array();
			$table = 'advanced_booking_calendar_calendars';
			if( get_option( 'abc_pluginversion' ) >= 148 ) {
				$table = 'abc_calendars';
			}
			$query_raw = 'SELECT * FROM `' . $wpdb->prefix . $table . '` LIMIT %d';
			$query = $wpdb->prepare( $query_raw, 3 );
			$result = $wpdb->get_results( $query, ARRAY_A );

			if ( ! empty( $result ) ) {
				foreach ( $result as $r ) {
					$item = array();
					$item['id']          = isset( $r['id'] ) ? $r['id'] : '';
					$item['title']       = isset( $r['name'] ) ? $r['name'] : '';
					$item['pricePreset'] = isset( $r['pricePreset'] ) ? $r['pricePreset'] : '';
					$item['infoPage']    = isset( $r['infoPage'] ) ? $r['infoPage'] : '';
					$item['image']    = array();
					if ( absint( $item['infoPage'] ) > 0 && has_post_thumbnail( absint( $item['infoPage'] ) ) ) {
						$item['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( absint( $item['infoPage'] ) ), 'large' );
					}

					$output[] = $item;
				}

			}

			return $output;
		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['subtitle']       = sanitize_text_field( $new_instance['subtitle'] );
			$instance['view_more_text'] = sanitize_text_field( $new_instance['view_more_text'] );
			$instance['view_more_url']  = esc_url_raw( $new_instance['view_more_url'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title'          => '',
				'subtitle'       => '',
				'view_more_text' => esc_html__( 'View All Rooms', 'hotel-hamburg' ),
				'view_more_url'  => '#'
				) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['subtitle'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_more_text' ) ); ?>"><?php esc_html_e( 'View More Text:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['view_more_text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_more_url' ) ); ?>"><?php esc_html_e( 'View More URL:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_more_url' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['view_more_url'] ); ?>" />
			</p>
			<?php
		}
	}

endif;

if ( ! class_exists( 'Hotel_Hamburg_Testimonials_Widget' ) ) :

	/**
	 * Testimonials widget.
	 *
	 * @since 1.0.0
	 */
	class Hotel_Hamburg_Testimonials_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'hotel_hamburg_widget_testimonials',
				'description'                 => esc_html__( 'Testimonials Widget', 'hotel-hamburg' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'hotel-hamburg-testimonials', esc_html__( 'Testimonials', 'hotel-hamburg' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title    = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$subtitle = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
			$post_cat = ! empty( $instance['post_cat'] ) ? $instance['post_cat'] : '';

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}

			// Render widget subtitle.
			if ( $subtitle ) {
				echo '<p class="widget-subtitle">' . esc_html( $subtitle ) . '</p>';
			}

			$query_args = array(
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => true,
			);

			if ( absint( $post_cat ) > 0 ) {
				$query_args['cat'] = absint( $post_cat );
			}

			$the_query = new WP_Query( $query_args );
			?>
			<?php if ( $the_query->have_posts() ) : ?>

				<div class="testimonials-widget">
					<div class="inner-wrapper">

						<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

							<div class="testimonials-item">
								<div class="testimonials-wrapper">
									<div class="testimonials-text-wrap">

										<div class="testimonials-summary">
											<?php
											$excerpt = hotel_hamburg_get_the_excerpt( 30 );
											echo wp_kses_post( wpautop( $excerpt ) );
											?>
										</div><!-- .testimonials-summary -->
										<h3 class="testimonials-title">
											<?php the_title(); ?>
										</h3>

									</div><!-- .testimonials-text-wrap -->
								</div><!-- .testimonials-wrapper -->
							</div><!-- .testimonials-item -->

						<?php endwhile; ?>

					</div><!-- .inner-wrapper -->
				</div><!-- .testimonials-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>
			<?php

			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']    = sanitize_text_field( $new_instance['title'] );
			$instance['subtitle'] = sanitize_text_field( $new_instance['subtitle'] );
			$instance['post_cat'] = absint( $new_instance['post_cat'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title'    => '',
				'subtitle' => '',
				'post_cat' => 0,
				) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['subtitle'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo  esc_attr( $this->get_field_id( 'post_cat' ) ); ?>"><?php esc_html_e( 'Select Category:', 'hotel-hamburg' ); ?></label>
				<?php
				$cat_args = array(
					'orderby'         => 'name',
					'hide_empty'      => true,
					'taxonomy'        => 'category',
					'name'            => $this->get_field_name( 'post_cat' ),
					'id'              => $this->get_field_id( 'post_cat' ),
					'selected'        => $instance['post_cat'],
					'show_option_all' => esc_html__( 'All Categories','hotel-hamburg' ),
				);
				wp_dropdown_categories( $cat_args );
				?>
			</p>
			<?php
		}
	}

endif;

if ( ! class_exists( 'Hotel_Hamburg_Location_Widget' ) ) :

	/**
	 * Location widget.
	 *
	 * @since 1.0.0
	 */
	class Hotel_Hamburg_Location_Widget extends WP_Widget {

		private $allowed_html;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'hotel_hamburg_widget_location',
				'description'                 => esc_html__( 'Location Widget', 'hotel-hamburg' ),
				'customize_selective_refresh' => true,
				);
			$this->allowed_html = array(
				'a' => array(
					'href'  => array(),
					'title' => array(),
					),
				'br'     => array(),
				'em'     => array(),
				'strong' => array(),
				'iframe' => array(
					'align'        => array(),
					'frameborder'  => array(),
					'height'       => array(),
					'longdesc'     => array(),
					'marginheight' => array(),
					'marginwidth'  => array(),
					'name'         => array(),
					'scrolling'    => array(),
					'src'          => array(),
					'srcdoc'       => array(),
					'width'        => array(),
					),
				);

			parent::__construct( 'hotel-hamburg-location', esc_html__( 'Location', 'hotel-hamburg' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$page_id        = ! empty( $instance['page_id'] ) ? $instance['page_id'] : '';
			$location_code  = ! empty( $instance['location_code'] ) ? $instance['location_code'] : '';
			$view_more_text = ! empty( $instance['view_more_text'] ) ? $instance['view_more_text'] : '';
			$view_more_url  = ! empty( $instance['view_more_url'] ) ? $instance['view_more_url'] : '';

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			}

			if ( absint( $page_id ) > 0 ) {

				$query_args = array(
					'p'         => absint( $page_id ),
					'post_type' => array( 'page' ),
				);

				$the_query = new WP_Query( $query_args );
				?>
				<?php if ( $the_query->have_posts() ) : ?>
					<div class="location-widget<?php echo ( empty( $location_code ) ) ? ' no-location-map' : '' ;?>">
						<div class="inner-wrapper">
							<?php if ( ! empty( $location_code ) ) : ?>
								<div class="location-map">
									<div class="location-map-inner">
										<?php echo wp_kses( $location_code, $this->allowed_html ); ?>
									</div><!-- .location-map-inner -->
								</div><!-- .location-map -->
							<?php endif; ?>
							<div class="location-content">
								<div class="location-content-inner">
									<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
										<h3 class="widget-title"><?php the_title(); ?></h3>
										<div class="entry-content">
											<?php the_content(); ?>
										</div><!-- .entry-content -->
									<?php endwhile; ?>
									<?php if ( ! empty( $view_more_text ) && ! empty( $view_more_url ) ) : ?>
										<div class="more-button-wrapper">
											<a href="<?php echo esc_url( $view_more_url ); ?>" class="more-button"><?php echo esc_html( $view_more_text ); ?></a>
										</div> <!-- .more-button-wrapper -->
									<?php endif; ?>
								</div><!-- .location-content-inner -->
							</div><!-- .location-content -->
							<?php wp_reset_postdata(); ?>
						</div><!-- .inner-wrapper -->
					</div><!-- .location-widget -->

				<?php endif; ?>

				<?php
			}

			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['page_id']        = absint( $new_instance['page_id'] );
			$instance['location_code']  = wp_kses( $new_instance[ 'location_code' ], $this->allowed_html );
			$instance['view_more_text'] = sanitize_text_field( $new_instance['view_more_text'] );
			$instance['view_more_url']  = esc_url_raw( $new_instance['view_more_url'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'page_id'        => 0,
				'location_code'  => '',
				'view_more_text' => esc_html__( 'Book Now', 'hotel-hamburg' ),
				'view_more_url'  => '#',
				) );
			?>
			<p>
				<label for="<?php echo  esc_attr( $this->get_field_id( 'page_id' ) ); ?>"><?php esc_html_e( 'Select Page:', 'hotel-hamburg' ); ?></label>
				<?php
				$cat_args = array(
					'name'            => $this->get_field_name( 'page_id' ),
					'id'              => $this->get_field_id( 'page_id' ),
					'selected'        => $instance['page_id'],
					'show_option_none' => esc_html__( '&mdash; Select &mdash;','hotel-hamburg' ),
				);
				wp_dropdown_pages( $cat_args );
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'location_code' ) ); ?>"><?php esc_html_e( 'Map Code:', 'hotel-hamburg' ); ?></label>
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'location_code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'location_code' ) ); ?>" rows="4"><?php echo esc_textarea( $instance['location_code'] ); ?></textarea>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_more_text' ) ); ?>"><?php esc_html_e( 'More Text:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['view_more_text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_more_url' ) ); ?>"><?php esc_html_e( 'More URL:', 'hotel-hamburg' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_more_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_more_url' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['view_more_url'] ); ?>" />
			</p>
			<?php
		}
	}

endif;
