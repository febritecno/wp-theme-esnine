<?php
/**
 * Front page: hero, featured properties, testimonials and FAQ.
 *
 * @package Esnine
 */

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/properties' );
get_template_part( 'template-parts/home/testimonials' );
get_template_part( 'template-parts/home/faq' );

// Content of a static front page, if one is set and not empty.
if ( is_page() ) {
	while ( have_posts() ) {
		the_post();
		if ( trim( get_the_content() ) ) {
			echo '<section class="section"><div class="entry">';
			the_content();
			echo '</div></section>';
		}
	}
}

get_footer();
