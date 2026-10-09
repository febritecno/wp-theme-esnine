<?php
/**
 * Fallback template: blog index and any view without a more specific template.
 *
 * @package Esnine
 */

get_header();
?>
<div class="section section--page">
	<?php
	if ( is_home() && ! is_front_page() ) {
		esnine_page_head( single_post_title( '', false ) );
	} else {
		esnine_page_head( __( 'Latest Posts', 'esnine' ) );
	}
	?>
	<div class="layout<?php echo is_active_sidebar( 'sidebar-1' ) ? ' layout--sidebar' : ''; ?>">
		<div class="layout__main">
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
		<?php get_sidebar(); ?>
	</div>
</div>
<?php
get_footer();
