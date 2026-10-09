<?php
/**
 * Property inquiry form.
 *
 * @package Esnine
 */

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only status flag.
$esnine_status   = isset( $_GET['inquiry'] ) ? sanitize_key( $_GET['inquiry'] ) : '';
$esnine_location = (string) esnine_field( 'property_location' );
$esnine_selected = get_the_title() . ( $esnine_location ? ', ' . $esnine_location : '' );
?>
<section class="section inquiry" id="inquiry">
	<div class="section__heading inquiry__intro">
		<span class="sparkles" aria-hidden="true">
			<span class="sparkles__lg"><img src="<?php echo esc_url( esnine_asset( 'sparkle-lg.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__md"><img src="<?php echo esc_url( esnine_asset( 'sparkle-md.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__sm"><img src="<?php echo esc_url( esnine_asset( 'sparkle-sm.svg' ) ); ?>" alt=""></span>
		</span>
		<?php /* translators: %s: property name. */ ?>
		<h2 class="section__title"><?php echo esc_html( esnine_mod_for_property( 'inquiry_title' ) ); ?></h2>
		<p class="section__desc"><?php echo esc_html( esnine_mod( 'inquiry_text' ) ); ?></p>
	</div>

	<form class="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="esnine_inquiry">
		<input type="hidden" name="property_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
		<?php wp_nonce_field( 'esnine_inquiry', 'esnine_inquiry_nonce' ); ?>
		<p class="form__hp" aria-hidden="true">
			<label for="inquiry-company"><?php esc_html_e( 'Company', 'esnine' ); ?></label>
			<input type="text" id="inquiry-company" name="company" tabindex="-1" autocomplete="off">
		</p>

		<?php if ( 'ok' === $esnine_status ) : ?>
			<p class="form__notice form__notice--ok" role="status"><?php esc_html_e( 'Thank you. Your inquiry was sent and our team will get back to you soon.', 'esnine' ); ?></p>
		<?php elseif ( 'invalid' === $esnine_status ) : ?>
			<p class="form__notice form__notice--error" role="alert"><?php esc_html_e( 'Please fill in your name, a valid email and a message, and accept the terms.', 'esnine' ); ?></p>
		<?php elseif ( 'error' === $esnine_status ) : ?>
			<p class="form__notice form__notice--error" role="alert"><?php esc_html_e( 'Something went wrong while sending your inquiry. Please try again.', 'esnine' ); ?></p>
		<?php endif; ?>

		<div class="form__grid">
			<p class="form__field">
				<label for="inquiry-first-name"><?php esc_html_e( 'First Name', 'esnine' ); ?></label>
				<input type="text" id="inquiry-first-name" name="first_name" placeholder="<?php esc_attr_e( 'Enter First Name', 'esnine' ); ?>" autocomplete="given-name" required>
			</p>
			<p class="form__field">
				<label for="inquiry-last-name"><?php esc_html_e( 'Last Name', 'esnine' ); ?></label>
				<input type="text" id="inquiry-last-name" name="last_name" placeholder="<?php esc_attr_e( 'Enter Last Name', 'esnine' ); ?>" autocomplete="family-name">
			</p>
			<p class="form__field">
				<label for="inquiry-email"><?php esc_html_e( 'Email', 'esnine' ); ?></label>
				<input type="email" id="inquiry-email" name="email" placeholder="<?php esc_attr_e( 'Enter your Email', 'esnine' ); ?>" autocomplete="email" required>
			</p>
			<p class="form__field">
				<label for="inquiry-phone"><?php esc_html_e( 'Phone', 'esnine' ); ?></label>
				<input type="tel" id="inquiry-phone" name="phone" placeholder="<?php esc_attr_e( 'Enter Phone Number', 'esnine' ); ?>" autocomplete="tel">
			</p>
			<p class="form__field form__field--full">
				<label for="inquiry-property"><?php esc_html_e( 'Selected Property', 'esnine' ); ?></label>
				<span class="form__readonly">
					<input type="text" id="inquiry-property" value="<?php echo esc_attr( $esnine_selected ); ?>" readonly>
					<img src="<?php echo esc_url( esnine_asset( 'icon-location.svg' ) ); ?>" alt="" width="24" height="24">
				</span>
			</p>
			<p class="form__field form__field--full">
				<label for="inquiry-message"><?php esc_html_e( 'Message', 'esnine' ); ?></label>
				<textarea id="inquiry-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Enter your Message here..', 'esnine' ); ?>" required></textarea>
			</p>
		</div>

		<div class="form__footer">
			<label class="form__check">
				<input type="checkbox" name="terms" value="1" required>
				<span>
					<?php esnine_terms_text(); ?>
				</span>
			</label>
			<button class="btn btn--primary form__submit" type="submit"><?php esc_html_e( 'Send Your Message', 'esnine' ); ?></button>
		</div>
	</form>
</section>
