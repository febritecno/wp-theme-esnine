<?php
/**
 * Custom post types, taxonomy and their detail fields.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the property, testimonial and FAQ post types.
 */
function esnine_register_post_types() {
	$types = array(
		'property'    => array(
			'singular' => __( 'Property', 'esnine' ),
			'plural'   => __( 'Properties', 'esnine' ),
			'icon'     => 'dashicons-admin-home',
			'slug'     => 'properties',
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
			'public'   => true,
		),
		'testimonial' => array(
			'singular' => __( 'Testimonial', 'esnine' ),
			'plural'   => __( 'Testimonials', 'esnine' ),
			'icon'     => 'dashicons-format-quote',
			'slug'     => 'testimonials',
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'public'   => false,
			'archive'  => true,
		),
		'faq'         => array(
			'singular' => __( 'FAQ', 'esnine' ),
			'plural'   => __( 'FAQs', 'esnine' ),
			'icon'     => 'dashicons-editor-help',
			'slug'     => 'faqs',
			'supports' => array( 'title', 'editor', 'excerpt' ),
			'public'   => true,
		),
	);

	foreach ( $types as $type => $config ) {
		register_post_type(
			$type,
			array(
				'labels'       => array(
					'name'          => $config['plural'],
					'singular_name' => $config['singular'],
					/* translators: %s: post type singular name. */
					'add_new_item'  => sprintf( __( 'Add New %s', 'esnine' ), $config['singular'] ),
					/* translators: %s: post type singular name. */
					'edit_item'     => sprintf( __( 'Edit %s', 'esnine' ), $config['singular'] ),
				),
				'public'       => $config['public'],
				'show_ui'      => true,
				'show_in_rest' => true,
				'has_archive'  => $config['public'] || ! empty( $config['archive'] ),
				// Testimonials are not public content types, but are listed on an archive page.
				'publicly_queryable' => $config['public'] || ! empty( $config['archive'] ),
				'menu_icon'    => $config['icon'],
				'supports'     => $config['supports'],
				'rewrite'      => array( 'slug' => $config['slug'] ),
			)
		);
	}

	register_post_type(
		'inquiry',
		array(
			'labels'              => array(
				'name'          => __( 'Inquiries', 'esnine' ),
				'singular_name' => __( 'Inquiry', 'esnine' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-email-alt',
			'supports'            => array( 'title', 'editor', 'custom-fields' ),
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
		)
	);

	register_taxonomy(
		'property_type',
		'property',
		array(
			'labels'            => array(
				'name'          => __( 'Property Types', 'esnine' ),
				'singular_name' => __( 'Property Type', 'esnine' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'property-type' ),
		)
	);

	foreach ( esnine_meta_fields() as $type => $fields ) {
		foreach ( $fields as $key => $field ) {
			register_post_meta(
				$type,
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => 'esnine_meta_auth',
				)
			);
		}
	}
}
add_action( 'init', 'esnine_register_post_types' );

/**
 * Only editors of a post may change its detail fields.
 *
 * @param bool   $allowed  Whether the user can edit the meta.
 * @param string $meta_key Meta key.
 * @param int    $post_id  Post ID.
 * @return bool
 */
function esnine_meta_auth( $allowed, $meta_key, $post_id ) {
	return current_user_can( 'edit_post', $post_id );
}

/**
 * Flush rewrite rules once so the new archives resolve.
 */
function esnine_flush_rewrites() {
	esnine_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'esnine_flush_rewrites' );

/**
 * Core detail fields per post type: meta key => array( label, input type ).
 *
 * Registered as post meta for the REST API and used for the fallback meta
 * box when ACF is not active. The names match the ACF field names.
 *
 * @return array
 */
function esnine_meta_fields() {
	return array(
		'property'    => array(
			'property_location'  => array( __( 'Location (e.g. Malibu, California)', 'esnine' ), 'text' ),
			'property_price'     => array( __( 'Price (numbers only)', 'esnine' ), 'number' ),
			'property_bedrooms'  => array( __( 'Bedrooms', 'esnine' ), 'number' ),
			'property_bathrooms' => array( __( 'Bathrooms', 'esnine' ), 'number' ),
			'property_area'      => array( __( 'Area (e.g. 2,500 Square Feet)', 'esnine' ), 'text' ),
		),
		'testimonial' => array(
			'testimonial_author'   => array( __( 'Client name', 'esnine' ), 'text' ),
			'testimonial_location' => array( __( 'Location (e.g. USA, California)', 'esnine' ), 'text' ),
			'testimonial_rating'   => array( __( 'Rating (1-5)', 'esnine' ), 'number' ),
		),
	);
}

/**
 * Add the details meta box.
 */
function esnine_add_meta_boxes() {
	// ACF provides the full editing UI; this basic box is only a fallback.
	if ( esnine_has_acf() ) {
		return;
	}
	foreach ( array_keys( esnine_meta_fields() ) as $type ) {
		add_meta_box( 'esnine_details', __( 'Details', 'esnine' ), 'esnine_render_meta_box', $type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'esnine_add_meta_boxes' );

/**
 * Render the details meta box.
 *
 * @param WP_Post $post Current post.
 */
function esnine_render_meta_box( $post ) {
	$fields = esnine_meta_fields();
	$fields = isset( $fields[ $post->post_type ] ) ? $fields[ $post->post_type ] : array();

	wp_nonce_field( 'esnine_save_meta', 'esnine_meta_nonce' );

	foreach ( $fields as $key => $field ) {
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="regular-text" type="%3$s" id="%1$s" name="%1$s" value="%4$s"%5$s></p>',
			esc_attr( $key ),
			esc_html( $field[0] ),
			esc_attr( $field[1] ),
			esc_attr( get_post_meta( $post->ID, $key, true ) ),
			'number' === $field[1] ? ' min="0" step="0.5"' : ''
		);
	}
}

/**
 * Save the details meta box.
 *
 * @param int $post_id Post ID.
 */
function esnine_save_meta( $post_id ) {
	if ( ! isset( $_POST['esnine_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['esnine_meta_nonce'] ), 'esnine_save_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = esnine_meta_fields();
	$type   = get_post_type( $post_id );

	if ( ! isset( $fields[ $type ] ) ) {
		return;
	}

	foreach ( array_keys( $fields[ $type ] ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post', 'esnine_save_meta' );

/**
 * Property list table: photo, price, location and featured columns.
 *
 * @param string[] $columns Existing columns.
 * @return string[]
 */
function esnine_property_columns( $columns ) {
	$added = array(
		'esnine_photo'    => __( 'Photo', 'esnine' ),
		'title'           => $columns['title'],
		'esnine_price'    => __( 'Price', 'esnine' ),
		'esnine_location' => __( 'Location', 'esnine' ),
		'esnine_rooms'    => __( 'Beds / Baths', 'esnine' ),
		'esnine_featured' => __( 'Featured', 'esnine' ),
	);
	unset( $columns['title'] );

	return array_merge( array_slice( $columns, 0, 1 ), $added, array_slice( $columns, 1 ) );
}
add_filter( 'manage_property_posts_columns', 'esnine_property_columns' );

/**
 * Print a custom property column.
 *
 * @param string $column  Column name.
 * @param int    $post_id Property ID.
 */
function esnine_property_column( $column, $post_id ) {
	switch ( $column ) {
		case 'esnine_photo':
			echo get_the_post_thumbnail( $post_id, array( 60, 60 ) );
			break;
		case 'esnine_price':
			echo esc_html( esnine_format_price( get_post_meta( $post_id, 'property_price', true ) ) );
			break;
		case 'esnine_location':
			echo esc_html( get_post_meta( $post_id, 'property_location', true ) );
			break;
		case 'esnine_rooms':
			echo esc_html( (int) get_post_meta( $post_id, 'property_bedrooms', true ) . ' / ' . (float) get_post_meta( $post_id, 'property_bathrooms', true ) );
			break;
		case 'esnine_featured':
			echo '0' === (string) get_post_meta( $post_id, 'property_featured', true ) ? '&mdash;' : '<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>';
			break;
	}
}
add_action( 'manage_property_posts_custom_column', 'esnine_property_column', 10, 2 );

/**
 * Let the property list be sorted by price.
 *
 * @param string[] $columns Sortable columns.
 * @return string[]
 */
function esnine_property_sortable_columns( $columns ) {
	$columns['esnine_price'] = 'esnine_price';
	return $columns;
}
add_filter( 'manage_edit-property_sortable_columns', 'esnine_property_sortable_columns' );

/**
 * Apply the price sort in the admin list.
 *
 * @param WP_Query $query Query.
 */
function esnine_property_admin_sort( $query ) {
	if ( is_admin() && $query->is_main_query() && 'esnine_price' === $query->get( 'orderby' ) ) {
		$query->set( 'meta_key', 'property_price' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'esnine_property_admin_sort' );
