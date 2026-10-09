<?php
/**
 * Footer brand, subscribe form and link columns.
 *
 * @package Esnine
 */

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only status flag.
$esnine_subscribed = isset( $_GET['subscribed'] ) ? sanitize_key( $_GET['subscribed'] ) : '';
?>
<div class="footer__main">
	<div class="footer__brand">
		<?php esnine_logo( 'logo-text-footer.svg' ); ?>
		<form class="subscribe" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="esnine_subscribe">
			<?php wp_nonce_field( 'esnine_subscribe', 'esnine_subscribe_nonce' ); ?>
			<img class="subscribe__mail" src="<?php echo esc_url( esnine_asset( 'icon-mail.svg' ) ); ?>" alt="">
			<input class="subscribe__input" type="email" name="email" placeholder="<?php echo esc_attr( esnine_mod( 'subscribe_placeholder' ) ); ?>" aria-label="<?php esc_attr_e( 'Email address', 'esnine' ); ?>" required>
			<button class="subscribe__send" type="submit" aria-label="<?php esc_attr_e( 'Subscribe', 'esnine' ); ?>">
				<img src="<?php echo esc_url( esnine_asset( 'icon-send.svg' ) ); ?>" alt="">
			</button>
		</form>
		<?php if ( 'ok' === $esnine_subscribed ) : ?>
			<p class="subscribe__note" role="status"><?php esc_html_e( 'Thanks, your email was received.', 'esnine' ); ?></p>
		<?php elseif ( 'invalid' === $esnine_subscribed ) : ?>
			<p class="subscribe__note" role="alert"><?php esc_html_e( 'Please enter a valid email address.', 'esnine' ); ?></p>
		<?php endif; ?>
	</div>
	<nav class="footer__links" aria-label="<?php esc_attr_e( 'Footer', 'esnine' ); ?>">
		<?php foreach ( esnine_get_footer_columns() as $esnine_column ) : ?>
			<div class="footer__column">
				<p class="footer__column-title"><?php echo esc_html( $esnine_column['title'] ); ?></p>
				<ul>
					<?php foreach ( $esnine_column['links'] as $esnine_link ) : ?>
						<li><a href="<?php echo esc_url( $esnine_link['url'] ); ?>"><?php echo esc_html( $esnine_link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</nav>
</div>
