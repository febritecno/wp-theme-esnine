<?php
/**
 * Archives: categories, tags, dates, authors and custom post types.
 *
 * @package Esnine
 */

get_header();
?>
<div class="section section--page">
	<?php esnine_page_head( get_the_archive_title(), get_the_archive_description() ); ?>
	<div class="layout<?php echo ( is_active_sidebar( 'sidebar-1' ) && ! is_post_type_archive() && ! is_tax() ) ? ' layout--sidebar' : ''; ?>">
		<div class="layout__main">
			<?php if ( have_posts() ) : ?>
				<ul class="cards<?php echo is_post_type_archive( 'testimonial' ) ? '' : ' cards--list'; ?>">
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
		if ( ! is_post_type_archive() && ! is_tax() ) {
			get_sidebar();
		}
		?>
	</div>
</div>
<?php
get_footer();
