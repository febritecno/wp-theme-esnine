<?php
/**
 * Home: featured properties carousel.
 *
 * @package Esnine
 */

$esnine_items   = esnine_get_properties();
$esnine_archive = get_post_type_archive_link( 'property' );

if ( ! $esnine_items ) {
	return;
}
$esnine_section = array(
	'id'             => 'properties',
	'title'          => esnine_mod( 'properties_title' ),
	'description'    => esnine_mod( 'properties_text' ),
	'view_all_label' => esnine_mod( 'properties_button' ),
	'view_all_url'   => (string) $esnine_archive,
);

esnine_carousel_open( $esnine_section );
foreach ( $esnine_items as $esnine_item ) {
	get_template_part( 'template-parts/content/card', 'property', $esnine_item );
}
esnine_carousel_close( $esnine_section, count( $esnine_items ) );
