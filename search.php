<?php
/**
 * Search results.
 *
 * @package Esnine
 */

get_header();
?>
<div class="section section--page">
	<?php
	/* translators: %s: search query. */
	esnine_page_head( sprintf( __( 'Search results for "%s"', 'esnine' ), esc_html( get_search_query() ) ) );
	?>
	<?php if ( have_posts() ) : ?>
		<ul class="cards cards--list">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content', get_post_type() );
			}
			?>
		</ul>
		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
