<?php
/**
 * Single property: gallery, description, features, inquiry form and pricing.
 * All content comes from the "Property Details" ACF field group.
 *
 * @package Esnine
 */

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/property/overview' );
	get_template_part( 'template-parts/property/inquiry' );
	get_template_part( 'template-parts/property/pricing' );
}

get_template_part( 'template-parts/home/faq' );

get_footer();
