<?php
/**
 * Single post, property and FAQ.
 *
 * @package Esnine
 */

get_header();
?>
<div class="section section--page section--narrow">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content/content', 'single' );

		if ( 'post' === get_post_type() ) {
			the_post_navigation(
				array(
					'prev_text' => '&larr; %title',
					'next_text' => '%title &rarr;',
				)
			);
		}

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
	?>
</div>
<?php
get_footer();
