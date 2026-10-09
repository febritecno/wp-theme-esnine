<?php
/**
 * "Let's Make it Happen" form on the Properties page.
 *
 * @package Esnine
 *
 * @var array $args Dropdown options: locations (string[]) and types (slug => name).
 */

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only status flag.
$esnine_status  = isset( $_GET['inquiry'] ) ? sanitize_key( $_GET['inquiry'] ) : '';
$esnine_counts  = array( '1', '2', '3', '4', '5+' );
$esnine_selects = array(
	'preferred_location' => array( __( 'Preferred Location', 'esnine' ), __( 'Select Location', 'esnine' ), $args['locations'] ),
	'property_type'      => array( __( 'Property Type', 'esnine' ), __( 'Select Property Type', 'esnine' ), array_values( $args['types'] ) ),
	'bathrooms'          => array( __( 'No. of Bathrooms', 'esnine' ), __( 'Select no. of Bathrooms', 'esnine' ), $esnine_counts ),
	'bedrooms'           => array( __( 'No. of Bedrooms', 'esnine' ), __( 'Select no. of Bedrooms', 'esnine' ), $esnine_counts ),
);
?>
<section class="section" id="inquiry">
	<div class="section__heading">
		<span class="sparkles" aria-hidden="true">
			<span class="sparkles__lg"><img src="<?php echo esc_url( esnine_asset( 'sparkle-lg.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__md"><img src="<?php echo esc_url( esnine_asset( 'sparkle-md.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__sm"><img src="<?php echo esc_url( esnine_asset( 'sparkle-sm.svg' ) ); ?>" alt=""></span>
		</span>
		<h2 class="section__title"><?php echo esc_html( esnine_mod( 'match_title' ) ); ?></h2>
		<p class="section__desc"><?php echo esc_html( esnine_mod( 'match_text' ) ); ?></p>
	</div>

	<form class="form form--wide" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="esnine_inquiry">
		<?php wp_nonce_field( 'esnine_inquiry', 'esnine_inquiry_nonce' ); ?>
		<p class="form__hp" aria-hidden="true">
			<label for="match-company"><?php esc_html_e( 'Company', 'esnine' ); ?></label>
			<input type="text" id="match-company" name="company" tabindex="-1" autocomplete="off">
		</p>

		<?php if ( 'ok' === $esnine_status ) : ?>
			<p class="form__notice form__notice--ok" role="status"><?php esc_html_e( 'Thank you. Your request was sent and our team will get back to you soon.', 'esnine' ); ?></p>
		<?php elseif ( 'invalid' === $esnine_status ) : ?>
			<p class="form__notice form__notice--error" role="alert"><?php esc_html_e( 'Please fill in your name, a valid email and a message, and accept the terms.', 'esnine' ); ?></p>
		<?php elseif ( 'error' === $esnine_status ) : ?>
			<p class="form__notice form__notice--error" role="alert"><?php esc_html_e( 'Something went wrong while sending your request. Please try again.', 'esnine' ); ?></p>
		<?php endif; ?>

		<div class="form__grid form__grid--4">
			<p class="form__field">
				<label for="match-first-name"><?php esc_html_e( 'First Name', 'esnine' ); ?></label>
				<input type="text" id="match-first-name" name="first_name" placeholder="<?php esc_attr_e( 'Enter First Name', 'esnine' ); ?>" autocomplete="given-name" required>
			</p>
			<p class="form__field">
				<label for="match-last-name"><?php esc_html_e( 'Last Name', 'esnine' ); ?></label>
				<input type="text" id="match-last-name" name="last_name" placeholder="<?php esc_attr_e( 'Enter Last Name', 'esnine' ); ?>" autocomplete="family-name">
			</p>
			<p class="form__field">
				<label for="match-email"><?php esc_html_e( 'Email', 'esnine' ); ?></label>
				<input type="email" id="match-email" name="email" placeholder="<?php esc_attr_e( 'Enter your Email', 'esnine' ); ?>" autocomplete="email" required>
			</p>
			<p class="form__field">
				<label for="match-phone"><?php esc_html_e( 'Phone', 'esnine' ); ?></label>
				<input type="tel" id="match-phone" name="phone" placeholder="<?php esc_attr_e( 'Enter Phone Number', 'esnine' ); ?>" autocomplete="tel">
			</p>
			<?php foreach ( $esnine_selects as $esnine_name => $esnine_select ) : ?>
				<p class="form__field">
					<label for="match-<?php echo esc_attr( $esnine_name ); ?>"><?php echo esc_html( $esnine_select[0] ); ?></label>
					<select id="match-<?php echo esc_attr( $esnine_name ); ?>" name="<?php echo esc_attr( $esnine_name ); ?>">
						<option value=""><?php echo esc_html( $esnine_select[1] ); ?></option>
						<?php foreach ( $esnine_select[2] as $esnine_option ) : ?>
							<option><?php echo esc_html( $esnine_option ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endforeach; ?>
			<p class="form__field form__field--half">
				<label for="match-budget"><?php esc_html_e( 'Budget', 'esnine' ); ?></label>
				<select id="match-budget" name="budget">
					<option value=""><?php esc_html_e( 'Select Budget', 'esnine' ); ?></option>
					<?php foreach ( esnine_price_ranges() as $esnine_range ) : ?>
						<option><?php echo esc_html( $esnine_range[0] ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<fieldset class="form__field form__field--half form__choices">
				<legend><?php esc_html_e( 'Preferred Contact Method', 'esnine' ); ?></legend>
				<span class="form__choice">
					<img src="<?php echo esc_url( esnine_asset( 'icon-phone.svg' ) ); ?>" alt="" width="24" height="24">
					<input type="tel" name="contact_phone" placeholder="<?php esc_attr_e( 'Enter Your Number', 'esnine' ); ?>" aria-label="<?php esc_attr_e( 'Contact number', 'esnine' ); ?>" autocomplete="tel">
					<input type="radio" name="contact_method" value="Phone" aria-label="<?php esc_attr_e( 'Contact me by phone', 'esnine' ); ?>" checked>
				</span>
				<span class="form__choice">
					<img src="<?php echo esc_url( esnine_asset( 'icon-mail-solid.svg' ) ); ?>" alt="" width="24" height="24">
					<input type="email" name="contact_email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'esnine' ); ?>" aria-label="<?php esc_attr_e( 'Contact email', 'esnine' ); ?>" autocomplete="email">
					<input type="radio" name="contact_method" value="Email" aria-label="<?php esc_attr_e( 'Contact me by email', 'esnine' ); ?>">
				</span>
			</fieldset>
			<p class="form__field form__field--full">
				<label for="match-message"><?php esc_html_e( 'Message', 'esnine' ); ?></label>
				<textarea id="match-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Enter your Message here..', 'esnine' ); ?>" required></textarea>
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
