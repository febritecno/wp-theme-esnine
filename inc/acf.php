<?php
/**
 * Advanced Custom Fields integration.
 *
 * Field groups are stored as JSON in /acf-json so they travel with the theme
 * and can still be edited from the ACF admin screen. Every template reads
 * fields through esnine_field(), which falls back to plain post meta, so the
 * site keeps rendering if ACF is deactivated.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether ACF is active.
 *
 * @return bool
 */
function esnine_has_acf() {
	return function_exists( 'get_field' );
}

/**
 * Read a custom field value.
 *
 * @param string      $name Field name.
 * @param int|WP_Post $post Optional post. Defaults to the current post.
 * @return mixed
 */
function esnine_field( $name, $post = null ) {
	$post_id = $post ? ( is_object( $post ) ? $post->ID : (int) $post ) : get_the_ID();

	if ( esnine_has_acf() ) {
		return get_field( $name, $post_id );
	}
	return get_post_meta( $post_id, $name, true );
}

/**
 * Save and load ACF field groups from the theme.
 *
 * @return string
 */
function esnine_acf_json_save_point() {
	return get_template_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'esnine_acf_json_save_point' );

/**
 * Add the theme folder to the ACF JSON load points.
 *
 * @param string[] $paths Load points.
 * @return string[]
 */
function esnine_acf_json_load_point( $paths ) {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'esnine_acf_json_load_point' );

/**
 * Tell editors the theme needs ACF for the property fields.
 */
function esnine_acf_notice() {
	if ( esnine_has_acf() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html__( 'Esnine: activate the Advanced Custom Fields plugin to edit property details, galleries and pricing.', 'esnine' )
	);
}
add_action( 'admin_notices', 'esnine_acf_notice' );

/**
 * Format a numeric price as $1,250,000. Non-numeric values pass through.
 *
 * @param mixed $value Raw price.
 * @return string
 */
function esnine_format_price( $value ) {
	if ( '' === $value || null === $value || false === $value ) {
		return '';
	}
	return is_numeric( $value ) ? '$' . number_format_i18n( (float) $value ) : (string) $value;
}

/**
 * Split a textarea field into trimmed, non-empty lines.
 *
 * @param string $text Field value.
 * @return string[]
 */
function esnine_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) ) );
}

/**
 * Parse "Label | Value | Note" lines into rows.
 *
 * @param string $text Field value.
 * @return array[] Rows with label, value and note keys.
 */
function esnine_cost_rows( $text ) {
	$rows = array();

	foreach ( esnine_lines( $text ) as $line ) {
		$parts  = array_map( 'trim', explode( '|', $line ) );
		$rows[] = array(
			'label' => $parts[0],
			'value' => isset( $parts[1] ) ? $parts[1] : '',
			'note'  => isset( $parts[2] ) ? $parts[2] : '',
		);
	}
	return $rows;
}

/**
 * Attachment IDs of a property's gallery: featured image first, then the
 * photos of the ACF gallery field, without duplicates.
 *
 * @param int $post_id Property ID.
 * @return int[]
 */
function esnine_property_gallery( $post_id ) {
	$ids = array_merge(
		array( (int) get_post_thumbnail_id( $post_id ) ),
		array_map( 'intval', explode( ',', (string) get_post_meta( $post_id, 'property_gallery', true ) ) )
	);

	return array_values( array_unique( array_filter( $ids, 'wp_attachment_is_image' ) ) );
}
