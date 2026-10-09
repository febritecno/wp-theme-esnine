<?php
/**
 * Property archive search and filters.
 *
 * Filters are read from the query string and applied to the main query, so
 * results stay paginated and bookmarkable however many properties exist.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Price range options: key => array( label, min, max ).
 *
 * @return array
 */
function esnine_price_ranges() {
	return apply_filters(
		'esnine_price_ranges',
		array(
			'0-500000'        => array( __( 'Under $500,000', 'esnine' ), 0, 500000 ),
			'500000-1000000'  => array( __( '$500,000 – $1,000,000', 'esnine' ), 500000, 1000000 ),
			'1000000-2000000' => array( __( '$1,000,000 – $2,000,000', 'esnine' ), 1000000, 2000000 ),
			'2000000-0'       => array( __( 'Over $2,000,000', 'esnine' ), 2000000, 0 ),
		)
	);
}

/**
 * Size range options in square feet: key => array( label, min, max ).
 *
 * @return array
 */
function esnine_size_ranges() {
	return apply_filters(
		'esnine_size_ranges',
		array(
			'0-1500'    => array( __( 'Under 1,500 sq ft', 'esnine' ), 0, 1500 ),
			'1500-2500' => array( __( '1,500 – 2,500 sq ft', 'esnine' ), 1500, 2500 ),
			'2500-4000' => array( __( '2,500 – 4,000 sq ft', 'esnine' ), 2500, 4000 ),
			'4000-0'    => array( __( 'Over 4,000 sq ft', 'esnine' ), 4000, 0 ),
		)
	);
}

/**
 * Distinct values of a property field, for filter dropdowns. Cached until a
 * property is saved.
 *
 * @param string $meta_key Field name.
 * @return string[]
 */
function esnine_property_field_values( $meta_key ) {
	$cache_key = 'esnine_values_' . $meta_key;
	$values    = get_transient( $cache_key );

	if ( false === $values ) {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Result is cached in a transient.
		$values = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = %s AND pm.meta_value <> '' AND p.post_type = 'property' AND p.post_status = 'publish'
				ORDER BY pm.meta_value ASC",
				$meta_key
			)
		);
		set_transient( $cache_key, $values, DAY_IN_SECONDS );
	}
	return $values;
}

/**
 * Keep helper data in sync when a property is saved: a numeric copy of the
 * area for range filtering, and fresh dropdown values.
 *
 * @param int $post_id Property ID.
 */
function esnine_sync_property_search_data( $post_id ) {
	if ( 'property' !== get_post_type( $post_id ) ) {
		return;
	}
	$area = (string) get_post_meta( $post_id, 'property_area', true );
	update_post_meta( $post_id, '_property_area_num', (int) preg_replace( '/\D/', '', strtok( $area, '.' ) ) );

	delete_transient( 'esnine_values_property_location' );
	delete_transient( 'esnine_values_property_build_year' );
}
add_action( 'save_post_property', 'esnine_sync_property_search_data', 20 );
add_action( 'acf/save_post', 'esnine_sync_property_search_data', 20 );

/**
 * Current filter values from the query string.
 *
 * @return array
 */
function esnine_property_filters() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Public, read-only search form.
	$filters = array();
	foreach ( array( 'location', 'ptype', 'price', 'size', 'year' ) as $key ) {
		// Prefixed so they never collide with WordPress query vars such as "year".
		$filters[ $key ] = isset( $_GET[ 'f_' . $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'f_' . $key ] ) ) : '';
	}
	// phpcs:enable
	return $filters;
}

/**
 * Build a numeric range clause for a meta query.
 *
 * @param string $key    Meta key.
 * @param array  $ranges Range options.
 * @param string $choice Selected option key.
 * @return array|null
 */
function esnine_range_clause( $key, $ranges, $choice ) {
	if ( ! isset( $ranges[ $choice ] ) ) {
		return null;
	}
	list( , $min, $max ) = $ranges[ $choice ];

	return $max
		? array( 'key' => $key, 'value' => array( $min, $max ), 'type' => 'NUMERIC', 'compare' => 'BETWEEN' )
		: array( 'key' => $key, 'value' => $min, 'type' => 'NUMERIC', 'compare' => '>=' );
}

/**
 * Apply the filters to the property archive query.
 *
 * @param WP_Query $query Query.
 */
function esnine_filter_property_archive( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! ( $query->is_post_type_archive( 'property' ) || $query->is_tax( 'property_type' ) ) ) {
		return;
	}

	$filters = esnine_property_filters();
	$meta    = array();

	$query->set( 'posts_per_page', (int) apply_filters( 'esnine_properties_per_page', 6 ) );

	if ( $filters['location'] ) {
		$meta[] = array( 'key' => 'property_location', 'value' => $filters['location'] );
	}
	if ( $filters['year'] ) {
		$meta[] = array( 'key' => 'property_build_year', 'value' => absint( $filters['year'] ), 'type' => 'NUMERIC' );
	}
	foreach ( array(
		esnine_range_clause( 'property_price', esnine_price_ranges(), $filters['price'] ),
		esnine_range_clause( '_property_area_num', esnine_size_ranges(), $filters['size'] ),
	) as $clause ) {
		if ( $clause ) {
			$meta[] = $clause;
		}
	}
	if ( $meta ) {
		$query->set( 'meta_query', $meta );
	}
	if ( $filters['ptype'] ) {
		$query->set(
			'tax_query',
			array(
				array( 'taxonomy' => 'property_type', 'field' => 'slug', 'terms' => sanitize_title( $filters['ptype'] ) ),
			)
		);
	}
}
add_action( 'pre_get_posts', 'esnine_filter_property_archive' );

/**
 * Search on the property archive should stay on the property archive template.
 *
 * @param string $template Template path.
 * @return string
 */
function esnine_property_search_template( $template ) {
	if ( is_search() && 'property' === get_query_var( 'post_type' ) ) {
		$archive = locate_template( 'archive-property.php' );
		return $archive ? $archive : $template;
	}
	return $template;
}
add_filter( 'template_include', 'esnine_property_search_template' );

/**
 * Print a filter dropdown.
 *
 * @param string $name     Query string key.
 * @param string $label    Placeholder label.
 * @param array  $options  Options as value => label.
 * @param string $selected Selected value.
 * @param string $icon     Icon file name in assets/images.
 */
function esnine_filter_select( $name, $label, $options, $selected, $icon ) {
	printf(
		'<label class="filter"><img class="filter__icon" src="%1$s" alt="" width="24" height="24"><span class="screen-reader-text">%2$s</span><select name="%3$s">',
		esc_url( esnine_asset( $icon ) ),
		esc_html( $label ),
		esc_attr( 'f_' . $name )
	);
	printf( '<option value="">%s</option>', esc_html( $label ) );
	foreach ( $options as $value => $text ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $value ), selected( (string) $selected, (string) $value, false ), esc_html( $text ) );
	}
	printf( '</select><span class="filter__chevron"><img src="%s" alt="" width="24" height="24"></span></label>', esc_url( esnine_asset( 'icon-chevron.svg' ) ) );
}
