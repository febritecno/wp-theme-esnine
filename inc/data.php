<?php
/**
 * Content for the home page sections.
 *
 * Every getter reads what editors entered: published posts and their ACF
 * fields. A section with no content is simply not printed.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Query published posts of a type.
 *
 * @param string $type Post type.
 * @return WP_Post[]
 */
function esnine_query( $type ) {
	return get_posts(
		array(
			'post_type'      => $type,
			'post_status'    => 'publish',
			'posts_per_page' => (int) apply_filters( 'esnine_section_limit', 30, $type ),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
}

/**
 * Short plain-text summary of a post.
 *
 * @param WP_Post $post  Post.
 * @param int     $words Word limit.
 * @return string
 */
function esnine_summary( $post, $words = 12 ) {
	$text = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;

	return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $text ) ), $words, '...' );
}

/**
 * Full plain text of a post, shown when a "Read More" toggle is opened.
 *
 * @param WP_Post $post Post.
 * @return string
 */
function esnine_full_text( $post ) {
	$text = trim( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ) );

	return '' !== $text ? $text : trim( wp_strip_all_tags( $post->post_excerpt ) );
}

/**
 * Name of the first property type assigned to a property.
 *
 * @param WP_Post $post Property post.
 * @return string
 */
function esnine_property_type( $post ) {
	$terms = get_the_terms( $post, 'property_type' );

	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

/**
 * Card data for a single property post.
 *
 * @param WP_Post $post Property post.
 * @return array
 */
function esnine_property_data( $post ) {
	return array(
		'name'      => get_the_title( $post ),
		'image_id'  => (int) get_post_thumbnail_id( $post ),
		'summary'   => esnine_summary( $post ),
		'full'      => esnine_full_text( $post ),
		'tagline'   => (string) esnine_field( 'property_tagline', $post ),
		'location'  => (string) esnine_field( 'property_location', $post ),
		'bedrooms'  => esnine_field( 'property_bedrooms', $post ),
		'bathrooms' => esnine_field( 'property_bathrooms', $post ),
		'area'      => (string) esnine_field( 'property_area', $post ),
		'type'      => esnine_property_type( $post ),
		'price'     => esnine_format_price( esnine_field( 'property_price', $post ) ),
		'url'       => get_permalink( $post ),
	);
}

/**
 * Featured properties.
 *
 * @return array[]
 */
function esnine_get_properties() {
	$items = array();

	foreach ( esnine_query( 'property' ) as $post ) {
		// Unset means featured, so properties created before the field existed still show.
		if ( '0' === (string) get_post_meta( $post->ID, 'property_featured', true ) ) {
			continue;
		}
		$items[] = esnine_property_data( $post );
	}

	return apply_filters( 'esnine_properties', $items );
}

/**
 * Card data for a single testimonial post.
 *
 * @param WP_Post $post Testimonial post.
 * @return array
 */
function esnine_testimonial_data( $post ) {
	return array(
		'title'     => get_the_title( $post ),
		'quote'     => wp_strip_all_tags( strip_shortcodes( $post->post_content ) ),
		'name'      => (string) esnine_field( 'testimonial_author', $post ),
		'location'  => (string) esnine_field( 'testimonial_location', $post ),
		'avatar_id' => (int) get_post_thumbnail_id( $post ),
		'rating'    => min( 5, max( 0, (int) esnine_field( 'testimonial_rating', $post ) ) ),
	);
}

/**
 * Client testimonials.
 *
 * @return array[]
 */
function esnine_get_testimonials() {
	$items = array();

	foreach ( esnine_query( 'testimonial' ) as $post ) {
		$items[] = esnine_testimonial_data( $post );
	}

	return apply_filters( 'esnine_testimonials', $items );
}

/**
 * Frequently asked questions.
 *
 * @return array[]
 */
function esnine_get_faqs() {
	$items = array();

	foreach ( esnine_query( 'faq' ) as $post ) {
		$items[] = array(
			'question' => get_the_title( $post ),
			'answer'   => esnine_summary( $post, 20 ),
			'url'      => get_permalink( $post ),
		);
	}

	return apply_filters( 'esnine_faqs', $items );
}

/**
 * Service cards under the hero, from the Home Page fields.
 *
 * @return array[]
 */
function esnine_get_features() {
	$items = array();

	for ( $i = 1; $i <= 4; $i++ ) {
		$label = (string) esnine_mod( "feature_{$i}_label" );

		if ( '' === $label ) {
			continue;
		}
		$items[] = array(
			'icon'  => sanitize_file_name( (string) esnine_mod( "feature_{$i}_icon" ) ),
			'label' => $label,
			'url'   => (string) esnine_mod( "feature_{$i}_url" ),
		);
	}

	return apply_filters( 'esnine_features', $items );
}

/**
 * Footer link columns from the Footer menu: top-level items are the column
 * titles, their children are the links.
 *
 * @return array[] Columns with title and links ( label, url ) keys.
 */
function esnine_get_footer_columns() {
	$locations = get_nav_menu_locations();
	$items     = empty( $locations['footer'] ) ? array() : wp_get_nav_menu_items( $locations['footer'] );
	$columns   = array();

	foreach ( $items ? $items : array() as $item ) {
		if ( ! $item->menu_item_parent ) {
			$columns[ $item->ID ] = array(
				'title' => $item->title,
				'links' => array(),
			);
		} elseif ( isset( $columns[ $item->menu_item_parent ] ) ) {
			$columns[ $item->menu_item_parent ]['links'][] = array(
				'label' => $item->title,
				'url'   => $item->url,
			);
		}
	}

	return apply_filters( 'esnine_footer_columns', array_values( $columns ) );
}
