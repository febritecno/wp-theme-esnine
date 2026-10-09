<?php
/**
 * Property inquiry form handling.
 *
 * Submissions are saved as private "Inquiry" posts (so nothing is lost when
 * mail delivery fails) and emailed to the site administrator.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Process an inquiry form submission.
 */
function esnine_handle_inquiry() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$fail     = static function ( $code ) use ( $redirect ) {
		wp_safe_redirect( add_query_arg( 'inquiry', $code, $redirect ) . '#inquiry' );
		exit;
	};

	if ( ! isset( $_POST['esnine_inquiry_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['esnine_inquiry_nonce'] ), 'esnine_inquiry' ) ) {
		$fail( 'invalid' );
	}

	// Honeypot: real visitors never fill this hidden field.
	if ( ! empty( $_POST['company'] ) ) {
		$fail( 'ok' );
	}

	$data = array(
		'first_name' => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
		'last_name'  => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
		'email'      => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
		'phone'      => isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '',
		'message'    => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
	);
	$property_id = isset( $_POST['property_id'] ) ? absint( $_POST['property_id'] ) : 0;
	$property    = ( $property_id && 'property' === get_post_type( $property_id ) ) ? get_the_title( $property_id ) : '';

	if ( '' === $data['first_name'] || '' === $data['message'] || ! is_email( $data['email'] ) || empty( $_POST['terms'] ) ) {
		$fail( 'invalid' );
	}

	// Optional preferences sent by the Properties page form.
	$extras = array(
		'preferred_location' => __( 'Preferred location', 'esnine' ),
		'property_type'      => __( 'Property type', 'esnine' ),
		'bedrooms'           => __( 'Bedrooms', 'esnine' ),
		'bathrooms'          => __( 'Bathrooms', 'esnine' ),
		'budget'             => __( 'Budget', 'esnine' ),
		'contact_method'     => __( 'Preferred contact method', 'esnine' ),
		'contact_phone'      => __( 'Contact number', 'esnine' ),
		'contact_email'      => __( 'Contact email', 'esnine' ),
	);
	$details = '';
	foreach ( $extras as $key => $label ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' !== $value ) {
			$details .= $label . ': ' . $value . "\n";
		}
	}

	$name    = trim( $data['first_name'] . ' ' . $data['last_name'] );
	$body    = sprintf(
		"Name: %s\nEmail: %s\nPhone: %s\nProperty: %s\n%s\n%s",
		$name,
		$data['email'],
		$data['phone'],
		$property,
		$details,
		$data['message']
	);
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'inquiry',
			'post_status'  => 'private',
			/* translators: 1: sender name, 2: property name. */
			'post_title'   => sprintf( __( '%1$s about %2$s', 'esnine' ), $name, $property ? $property : __( 'a property', 'esnine' ) ),
			'post_content' => $body,
			'meta_input'   => array(
				'inquiry_email'    => $data['email'],
				'inquiry_phone'    => $data['phone'],
				'inquiry_property' => $property_id,
			),
		)
	);

	wp_mail(
		get_option( 'admin_email' ),
		/* translators: %s: property name. */
		sprintf( __( 'New inquiry: %s', 'esnine' ), $property ? $property : get_bloginfo( 'name' ) ),
		$body,
		array( 'Reply-To: ' . $name . ' <' . $data['email'] . '>' )
	);

	$fail( ( $post_id && ! is_wp_error( $post_id ) ) ? 'ok' : 'error' );
}
add_action( 'admin_post_nopriv_esnine_inquiry', 'esnine_handle_inquiry' );
add_action( 'admin_post_esnine_inquiry', 'esnine_handle_inquiry' );
