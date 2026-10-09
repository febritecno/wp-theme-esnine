<?php
/**
 * Primary navigation bar. On small screens the menu opens as an overlay panel.
 *
 * @package Esnine
 */

$esnine_contact = sprintf(
	'<li class="navbar__links-contact"><a class="navbar__link" href="%1$s">%2$s</a></li>',
	esc_url( home_url( '/#contact' ) ),
	esc_html__( 'Contact Us', 'esnine' )
);
?>
<nav class="navbar" aria-label="<?php esc_attr_e( 'Main', 'esnine' ); ?>" data-nav>
	<?php esnine_logo(); ?>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_id'        => 'main-menu',
			'menu_class'     => 'navbar__links',
			'depth'          => 1,
			'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s' . $esnine_contact . '</ul>',
			'fallback_cb'    => false,
		)
	);
	?>
	<a class="btn btn--dark navbar__contact" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact Us', 'esnine' ); ?></a>
	<button class="navbar__toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'esnine' ); ?>" aria-expanded="false" aria-controls="main-menu" data-nav-toggle>
		<img src="<?php echo esc_url( esnine_asset( 'icon-menu.svg' ) ); ?>" alt="">
	</button>
</nav>
