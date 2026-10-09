<?php
/**
 * Call to action band above the footer.
 *
 * @package Esnine
 */

$esnine_cta_title = esnine_mod( 'cta_title' );

if ( ! $esnine_cta_title ) {
	return;
}
?>
<section class="cta">
	<img class="cta__pattern cta__pattern--right" src="<?php echo esc_url( esnine_asset( 'cta-pattern-right.svg' ) ); ?>" alt="">
	<img class="cta__pattern cta__pattern--left" src="<?php echo esc_url( esnine_asset( 'cta-pattern-left.svg' ) ); ?>" alt="">
	<div class="cta__text">
		<h2 class="section__title"><?php echo esc_html( $esnine_cta_title ); ?></h2>
		<p class="section__desc"><?php echo esc_html( esnine_mod( 'cta_text' ) ); ?></p>
	</div>
	<?php if ( esnine_mod( 'cta_button' ) ) : ?>
		<a class="btn btn--primary cta__button" href="<?php echo esc_url( esnine_mod_link( 'cta_url' ) ); ?>"><?php echo esc_html( esnine_mod( 'cta_button' ) ); ?></a>
	<?php endif; ?>
</section>
