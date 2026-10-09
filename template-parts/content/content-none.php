<?php
/**
 * Shown when a query returns nothing.
 *
 * @package Esnine
 */

?>
<div class="card no-results">
	<h2 class="card__title"><?php esc_html_e( 'Nothing found', 'esnine' ); ?></h2>
	<p class="faq__answer"><?php esc_html_e( 'Try a different search, or head back to the home page.', 'esnine' ); ?></p>
	<?php get_search_form(); ?>
</div>
