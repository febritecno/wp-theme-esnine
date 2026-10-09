<?php
/**
 * Footer bottom bar: copyright and social links.
 *
 * @package Esnine
 */

$esnine_socials = array(
	'facebook' => __( 'Facebook', 'esnine' ),
	'linkedin' => __( 'LinkedIn', 'esnine' ),
	'twitter'  => __( 'Twitter', 'esnine' ),
	'youtube'  => __( 'YouTube', 'esnine' ),
);
?>
<div class="footer__bottom">
	<div class="footer__legal">
		<p><?php echo esc_html( esnine_mod( 'copyright' ) ); ?></p>
		<?php if ( get_privacy_policy_url() ) : ?>
			<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Terms & Conditions', 'esnine' ); ?></a>
		<?php else : ?>
			<span><?php esc_html_e( 'Terms & Conditions', 'esnine' ); ?></span>
		<?php endif; ?>
	</div>
	<ul class="footer__socials">
		<?php foreach ( $esnine_socials as $esnine_network => $esnine_label ) : ?>
			<?php
			$esnine_url = esnine_mod( $esnine_network . '_url' );
			if ( ! $esnine_url ) {
				continue;
			}
			?>
			<li>
				<a href="<?php echo esc_url( $esnine_url ); ?>" aria-label="<?php echo esc_attr( $esnine_label ); ?>">
					<img src="<?php echo esc_url( esnine_asset( 'icon-' . $esnine_network . '.svg' ) ); ?>" alt="">
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
