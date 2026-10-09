<?php
/**
 * Not found.
 *
 * @package Esnine
 */

get_header();
?>
<div class="section section--page section--narrow">
	<?php esnine_page_head( __( 'Page not found', 'esnine' ), __( 'The page you are looking for does not exist or has moved.', 'esnine' ) ); ?>
	<div class="card no-results">
		<?php get_search_form(); ?>
		<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'esnine' ); ?></a>
	</div>
</div>
<?php
get_footer();
