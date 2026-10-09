<?php
/**
 * Static page.
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

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
	?>
</div>
<?php
get_footer();
