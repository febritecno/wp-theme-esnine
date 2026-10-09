<?php
/**
 * Post card used in the blog, archives and search results.
 *
 * @package Esnine
 */

if ( 'testimonial' === get_post_type() ) {
	get_template_part( 'template-parts/content/card', 'testimonial', esnine_testimonial_data( get_post() ) );
	return;
}

if ( 'property' === get_post_type() ) {
	get_template_part( 'template-parts/content/card', 'property', esnine_property_data( get_post() ) );
	return;
}
?>
<li <?php post_class( 'card post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'large', array( 'class' => 'property__image' ) ); ?>
		</a>
	<?php endif; ?>
	<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php if ( 'post' === get_post_type() ) : ?>
		<p class="post-card__meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
	<?php endif; ?>
	<p class="faq__answer"><?php echo esc_html( esnine_summary( get_post(), 24 ) ); ?></p>
	<a class="btn btn--muted faq__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'esnine' ); ?></a>
</li>
