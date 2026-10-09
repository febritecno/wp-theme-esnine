<?php
/**
 * Title: Call to Action
 * Slug: esnine/cta
 * Categories: esnine, call-to-action
 * Description: Heading, text and a button on a dark panel.
 *
 * @package Esnine
 */

?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"50px","bottom":"50px","left":"50px","right":"50px"}},"border":{"radius":"12px","width":"1px","color":"#262626"}},"backgroundColor":"grey-10","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-grey-10-background-color has-background" style="border-color:#262626;border-width:1px;border-radius:12px;padding-top:50px;padding-right:50px;padding-bottom:50px;padding-left:50px">
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Start Your Real Estate Journey Today', 'esnine' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"grey-60"} -->
<p class="has-grey-60-color has-text-color"><?php esc_html_e( 'Your dream property is just a click away. Explore our available properties or get in touch with our team for personalized assistance.', 'esnine' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Explore Properties', 'esnine' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
