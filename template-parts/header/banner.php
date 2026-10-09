<?php
/**
 * Dismissible announcement banner.
 *
 * @package Esnine
 */

$esnine_banner_text = esnine_mod( 'banner_text' );

if ( ! $esnine_banner_text ) {
	return;
}
?>
<div class="banner" data-banner>
	<img class="banner__pattern" src="<?php echo esc_url( esnine_asset( 'banner-pattern.svg' ) ); ?>" alt="">
	<p class="banner__text"><?php echo esc_html( $esnine_banner_text ); ?></p>
	<?php if ( esnine_mod( 'banner_link_text' ) ) : ?>
		<a class="banner__link" href="<?php echo esc_url( esnine_mod( 'banner_url' ) ); ?>"><?php echo esc_html( esnine_mod( 'banner_link_text' ) ); ?></a>
	<?php endif; ?>
	<button class="banner__close" type="button" aria-label="<?php esc_attr_e( 'Dismiss banner', 'esnine' ); ?>" data-banner-close>
		<img src="<?php echo esc_url( esnine_asset( 'icon-close.svg' ) ); ?>" alt="">
	</button>
</div>
