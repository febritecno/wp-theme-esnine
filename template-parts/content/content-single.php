<?php
/**
 * Full content of a single post, page, property or FAQ.
 *
 * @package Esnine
 */

$esnine_is_property = 'property' === get_post_type();
$esnine_property    = $esnine_is_property ? esnine_property_data( get_post() ) : array();
?>
<article <?php post_class( 'entry-single' ); ?>>
	<header class="page-head">
		<?php esnine_breadcrumbs(); ?>
		<?php the_title( '<h1 class="section__title">', '</h1>' ); ?>
		<?php if ( 'post' === get_post_type() ) : ?>
			<p class="section__desc">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				&middot; <?php the_author(); ?>
			</p>
		<?php endif; ?>
		<?php if ( $esnine_is_property ) : ?>
			<?php esnine_property_chips( $esnine_property ); ?>
			<?php if ( $esnine_property['price'] ) : ?>
				<p class="single__price"><span class="property__price-label"><?php esc_html_e( 'Price', 'esnine' ); ?></span> <span class="property__price"><?php echo esc_html( $esnine_property['price'] ); ?></span></p>
			<?php endif; ?>
		<?php endif; ?>
	</header>
	<?php if ( has_post_thumbnail() ) : ?>
		<?php the_post_thumbnail( 'full', array( 'class' => 'single__image' ) ); ?>
	<?php endif; ?>
	<div class="entry">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'esnine' ),
				'after'  => '</nav>',
			)
		);
		?>
	</div>
</article>
