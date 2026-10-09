<?php
/**
 * Esnine theme functions.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ESNINE_VERSION', '1.0.0' );

require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/settings.php';
require get_template_directory() . '/inc/acf.php';
require get_template_directory() . '/inc/post-types.php';
require get_template_directory() . '/inc/inquiry.php';
require get_template_directory() . '/inc/property-search.php';
require get_template_directory() . '/inc/data.php';

/**
 * Theme supports and menu locations.
 */
function esnine_setup() {
	load_theme_textdomain( 'esnine', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/editor-style.css' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 160,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'esnine' ),
			'footer'  => __( 'Footer Menu (top level = column titles)', 'esnine' ),
		)
	);
}
add_action( 'after_setup_theme', 'esnine_setup' );

/**
 * Widget areas.
 */
function esnine_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'esnine' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Shown beside blog posts and archives.', 'esnine' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget__title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'esnine_widgets_init' );

/**
 * Block pattern category for the theme's patterns in /patterns.
 */
function esnine_pattern_categories() {
	register_block_pattern_category(
		'esnine',
		array( 'label' => __( 'Esnine', 'esnine' ) )
	);
}
add_action( 'init', 'esnine_pattern_categories' );

/**
 * Front-end assets.
 */
function esnine_enqueue_assets() {
	wp_enqueue_style(
		'esnine-fonts',
		'https://fonts.googleapis.com/css2?family=Urbanist:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'esnine-main',
		get_theme_file_uri( 'assets/css/main.css' ),
		array( 'esnine-fonts' ),
		filemtime( get_theme_file_path( 'assets/css/main.css' ) )
	);
	wp_enqueue_script(
		'esnine-main',
		get_theme_file_uri( 'assets/js/main.js' ),
		array(),
		filemtime( get_theme_file_path( 'assets/js/main.js' ) ),
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'esnine_enqueue_assets' );

/**
 * Flag that scripts run, before first paint, so scroll reveals never hide
 * content for visitors without JavaScript.
 */
function esnine_js_flag() {
	wp_print_inline_script_tag( "document.documentElement.classList.add('js');" );
}
add_action( 'wp_head', 'esnine_js_flag', 0 );

/**
 * Preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function esnine_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'esnine_resource_hints', 10, 2 );

/**
 * Add theme classes to primary menu links.
 *
 * @param array    $atts Link attributes.
 * @param WP_Post  $item Menu item.
 * @param stdClass $args Menu arguments.
 * @return array
 */
function esnine_nav_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		// Links to a section of a page (#services) are never the current page.
		$anchor = false !== strpos( (string) $item->url, '#' );
		$active = ( ! $anchor && ( $item->current || $item->current_item_ancestor ) )
			|| ( 'post_type_archive' === $item->type && ( is_singular( $item->object ) || is_post_type_archive( $item->object ) || ( 'property' === $item->object && is_tax( 'property_type' ) ) ) );
		$class  = 'navbar__link' . ( $active ? ' is-active' : '' );
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' ' . $class : $class;

		if ( $anchor ) {
			unset( $atts['aria-current'] );
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'esnine_nav_link_attributes', 10, 3 );

/**
 * Handle the footer subscribe form.
 *
 * The theme does not store addresses itself: hook `esnine_subscribe` to send
 * the email to a newsletter service.
 */
function esnine_handle_subscribe() {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$status   = 'invalid';

	if ( isset( $_POST['esnine_subscribe_nonce'], $_POST['email'] )
		&& wp_verify_nonce( sanitize_key( $_POST['esnine_subscribe_nonce'] ), 'esnine_subscribe' ) ) {
		$email = sanitize_email( wp_unslash( $_POST['email'] ) );
		if ( is_email( $email ) ) {
			do_action( 'esnine_subscribe', $email );
			$status = 'ok';
		}
	}

	wp_safe_redirect( add_query_arg( 'subscribed', $status, $redirect ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_esnine_subscribe', 'esnine_handle_subscribe' );
add_action( 'admin_post_esnine_subscribe', 'esnine_handle_subscribe' );

// Archive titles without the "Archives:" / "Category:" prefix.
add_filter( 'get_the_archive_title_prefix', '__return_empty_string' );
