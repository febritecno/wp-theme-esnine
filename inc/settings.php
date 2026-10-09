<?php
/**
 * Editable site copy, stored as ACF fields on the static front page.
 *
 * The "Home Page" and "Site-wide Content" field groups (see acf-json) are
 * shown when editing the page set as the front page under Settings > Reading.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read one content field from the front page.
 *
 * @param string $key Field name, e.g. hero_title.
 * @return mixed Field value, or an empty string when nothing is set.
 */
function esnine_mod( $key ) {
	$front = (int) get_option( 'page_on_front' );

	if ( ! $front ) {
		return '';
	}
	$value = esnine_field( $key, $front );

	return ( null === $value || false === $value ) ? '' : $value;
}

/**
 * Read a content field and swap {property} for the current property name.
 *
 * @param string $key Field name.
 * @return string
 */
function esnine_mod_for_property( $key ) {
	return str_replace( '{property}', get_the_title(), (string) esnine_mod( $key ) );
}

/**
 * Link stored in a content field, or the Properties archive when empty.
 *
 * @param string $key Field name.
 * @return string
 */
function esnine_mod_link( $key ) {
	$url = (string) esnine_mod( $key );

	return $url ? $url : (string) get_post_type_archive_link( 'property' );
}

/**
 * Tell editors where the site copy lives when no front page is set.
 */
function esnine_front_page_notice() {
	if ( get_option( 'page_on_front' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'Esnine: choose a static home page under Settings > Reading. The hero, banner, footer and section texts are edited on that page.', 'esnine' )
	);
}
add_action( 'admin_notices', 'esnine_front_page_notice' );
