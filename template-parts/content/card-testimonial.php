<?php
/**
 * Testimonial card.
 *
 * @package Esnine
 *
 * @var array $args Testimonial data from esnine_testimonial_data().
 */

?>
<li class="card testimonial">
	<?php /* translators: %d: star rating. */ ?>
	<?php if ( $args['rating'] ) : ?>
	<div class="testimonial__stars" role="img" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'esnine' ), $args['rating'] ) ); ?>">
		<?php for ( $esnine_i = 0; $esnine_i < $args['rating']; $esnine_i++ ) : ?>
			<img src="<?php echo esc_url( esnine_asset( 'star.svg' ) ); ?>" alt="" width="24" height="24" loading="lazy">
		<?php endfor; ?>
	</div>
	<?php endif; ?>
	<div class="testimonial__text">
		<h3 class="card__title"><?php echo esc_html( $args['title'] ); ?></h3>
		<p><?php echo esc_html( $args['quote'] ); ?></p>
	</div>
	<div class="testimonial__author">
		<?php
		if ( $args['avatar_id'] ) {
			echo wp_get_attachment_image( $args['avatar_id'], 'thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) );
		}
		?>
		<div>
			<p class="testimonial__name"><?php echo esc_html( $args['name'] ); ?></p>
			<p class="testimonial__location"><?php echo esc_html( $args['location'] ); ?></p>
		</div>
	</div>
</li>
