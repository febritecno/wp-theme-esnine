<?php
/**
 * Search form.
 *
 * @package Esnine
 */

?>
<form class="subscribe search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input class="subscribe__input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'esnine' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'esnine' ); ?>">
	<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Search', 'esnine' ); ?></button>
</form>
