<?php
/**
 * Home: testimonials carousel.
 *
 * @package Esnine
 */

$esnine_items = esnine_get_testimonials();

if ( ! $esnine_items ) {
	return;
}

$esnine_section = array(
	'id'             => 'testimonials',
	'title'          => esnine_mod( 'testimonials_title' ),
	'description'    => esnine_mod( 'testimonials_text' ),
	'view_all_label' => esnine_mod( 'testimonials_button' ),
	'autoplay'       => (int) apply_filters( 'esnine_carousel_autoplay', 5000, 'testimonials' ),
	'view_all_url'   => (string) get_post_type_archive_link( 'testimonial' ),
);

esnine_carousel_open( $esnine_section );
foreach ( $esnine_items as $esnine_item ) {
	get_template_part( 'template-parts/content/card', 'testimonial', $esnine_item );
}
esnine_carousel_close( $esnine_section, count( $esnine_items ) );
