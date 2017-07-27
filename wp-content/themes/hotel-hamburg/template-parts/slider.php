<?php
/**
 * Slider template part.
 *
 * @package Hotel_Hamburg
 */

$page_ids = array();

for ( $i = 1; $i <= 5; $i++ ) {
	$page_ids[] = absint( hotel_hamburg_get_option( 'slider_page_' . $i ) );
}

// Remove item with zero value.
$page_ids = array_filter( $page_ids );

// Fetch value only.
$page_ids = array_values( $page_ids );

// Remove duplicate items.
$page_ids = array_unique( $page_ids );

if ( empty( $page_ids ) ) {
	return;
}

$query_args = array(
	'posts_per_page' => count( $page_ids ),
	'no_found_rows'  => true,
	'orderby'        => 'post__in',
	'post_type'      => 'page',
	'post__in'       => $page_ids,
	'meta_query'     => array(
		array( 'key' => '_thumbnail_id' ),
	),
);

$all_posts = get_posts( $query_args );
$slides = array();

if ( ! empty( $all_posts ) ) {
	$cnt = 0;
	foreach ( $all_posts as $key => $post ) {

		if ( has_post_thumbnail( $post->ID ) ) {
			$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$slides[ $cnt ]['images'] = $image_array;
			$slides[ $cnt ]['title']  = get_the_title( $post->ID );
			$slides[ $cnt ]['url']    = get_permalink( $post->ID );
			$cnt++;
		}
	}
}

if ( empty( $page_ids ) ) {
	return;
}
?>
<div id="featured-slider">
	<div class="cycle-slideshow" id="main-slider"
	data-cycle-speed="1000"
	data-cycle-fx="fadeout"
	data-cycle-slides="article"
	data-cycle-auto-height="container"
	data-cycle-swipe="true"
	>
		<div class="cycle-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>
		<div class="cycle-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>

		<?php $cnt = 1; ?>
		<?php foreach ( $slides as $key => $slide ) : ?>
			<?php $slide_class = ( 1 === $cnt ) ? 'first' : ''; ?>
			<article class="slide-item<?php echo ' ' . esc_attr( $slide_class ); ?>">
				<a href="<?php echo esc_url( $slide['url'] ); ?>">
					<img src="<?php echo esc_url( $slide['images'][0] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" />
				</a>
			</article>
			<?php $cnt++; ?>
		<?php endforeach; ?>
	</div><!-- #main-slider -->
	<?php if ( hotel_hamburg_is_abc_active() ) : ?>
		<div id="booking-form-area">
			<?php echo do_shortcode( '[abc-bookingwidget]' ); ?>
		</div><!-- #booking-form-area -->
	<?php endif; ?>
</div><!-- #featured-slider -->
