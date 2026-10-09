<?php
/**
 * Template helpers.
 *
 * @package Esnine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL of a bundled theme image.
 *
 * @param string $file File name inside assets/images.
 * @return string
 */
function esnine_asset( $file ) {
	return get_theme_file_uri( 'assets/images/' . $file );
}

/**
 * Print the site logo.
 *
 * @param string $text_file Logo text image file name.
 */
function esnine_logo( $text_file = 'logo-text.svg' ) {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	?>
	<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<img class="logo__symbol" src="<?php echo esc_url( esnine_asset( 'logo-symbol.svg' ) ); ?>" alt="">
		<img class="logo__text" src="<?php echo esc_url( esnine_asset( $text_file ) ); ?>" alt="">
	</a>
	<?php
}

/**
 * Open a carousel section: heading, description and "view all" button.
 *
 * @param array $args {
 *     @type string $id             Section id.
 *     @type string $title          Heading.
 *     @type string $description    Intro text.
 *     @type string $view_all_label Button label.
 *     @type string $view_all_url   Button URL.
 *     @type int    $autoplay       Optional. Milliseconds between automatic slides.
 * }
 */
function esnine_carousel_open( $args ) {
	?>
	<section class="section" id="<?php echo esc_attr( $args['id'] ); ?>" data-carousel<?php echo empty( $args['autoplay'] ) ? '' : ' data-carousel-autoplay="' . esc_attr( (int) $args['autoplay'] ) . '"'; ?>>
		<div class="section__head">
			<div class="section__heading">
				<span class="sparkles" aria-hidden="true">
					<span class="sparkles__lg"><img src="<?php echo esc_url( esnine_asset( 'sparkle-lg.svg' ) ); ?>" alt=""></span>
					<span class="sparkles__md"><img src="<?php echo esc_url( esnine_asset( 'sparkle-md.svg' ) ); ?>" alt=""></span>
					<span class="sparkles__sm"><img src="<?php echo esc_url( esnine_asset( 'sparkle-sm.svg' ) ); ?>" alt=""></span>
				</span>
				<h2 class="section__title"><?php echo esc_html( $args['title'] ); ?></h2>
				<p class="section__desc"><?php echo esc_html( $args['description'] ); ?></p>
			</div>
			<?php if ( $args['view_all_label'] && $args['view_all_url'] ) : ?>
				<a class="btn btn--muted section__view-all" href="<?php echo esc_url( $args['view_all_url'] ); ?>"><?php echo esc_html( $args['view_all_label'] ); ?></a>
			<?php endif; ?>
		</div>
		<div class="section__body">
			<ul class="cards" data-carousel-items>
	<?php
}

/**
 * Close a carousel section: pager with counter and arrows.
 *
 * @param array $args  Same arguments as esnine_carousel_open().
 * @param int   $total Number of items in the carousel.
 */
function esnine_carousel_close( $args, $total ) {
	$pages = max( 1, (int) ceil( $total / 3 ) );
	?>
			</ul>
			<div class="pager">
				<?php if ( $args['view_all_label'] && $args['view_all_url'] ) : ?>
					<a class="btn btn--muted pager__view-all" href="<?php echo esc_url( $args['view_all_url'] ); ?>"><?php echo esc_html( $args['view_all_label'] ); ?></a>
				<?php endif; ?>
				<p class="pager__count" aria-live="polite">
					<span data-carousel-current>01</span>
					<?php esc_html_e( 'of', 'esnine' ); ?>
					<span class="pager__total" data-carousel-total><?php echo esc_html( sprintf( '%02d', $pages ) ); ?></span>
				</p>
				<div class="pager__arrows">
					<button class="pager__btn pager__btn--prev" type="button" aria-label="<?php esc_attr_e( 'Previous', 'esnine' ); ?>" data-carousel-prev disabled>
						<img src="<?php echo esc_url( esnine_asset( 'icon-arrow-left.svg' ) ); ?>" alt="">
					</button>
					<button class="pager__btn pager__btn--next" type="button" aria-label="<?php esc_attr_e( 'Next', 'esnine' ); ?>" data-carousel-next<?php disabled( $pages, 1 ); ?>>
						<img src="<?php echo esc_url( esnine_asset( 'icon-arrow-right.svg' ) ); ?>" alt="">
					</button>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Print the bedroom / bathroom / type chips of a property.
 *
 * @param array $property Property data from esnine_get_properties().
 */
function esnine_property_chips( $property ) {
	$chips = array(
		'icon-bed.svg'      => $property['bedrooms'] ? sprintf( /* translators: %s: number of bedrooms. */ __( '%s-Bedroom', 'esnine' ), $property['bedrooms'] ) : '',
		'icon-bath.svg'     => $property['bathrooms'] ? sprintf( /* translators: %s: number of bathrooms. */ __( '%s-Bathroom', 'esnine' ), $property['bathrooms'] ) : '',
		'icon-building.svg' => $property['type'],
	);
	$chips = array_filter( $chips );

	if ( ! $chips ) {
		return;
	}

	echo '<ul class="chips">';
	foreach ( $chips as $icon => $label ) {
		printf(
			'<li class="chip"><span class="chip__icon"><img src="%1$s" alt=""></span>%2$s</li>',
			esc_url( esnine_asset( $icon ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Print a breadcrumb trail for inner pages.
 */
function esnine_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$crumbs = array( array( __( 'Home', 'esnine' ), home_url( '/' ) ) );

	if ( is_singular() ) {
		$type = get_post_type_object( get_post_type() );
		if ( $type && $type->has_archive ) {
			$crumbs[] = array( $type->labels->name, get_post_type_archive_link( $type->name ) );
		} elseif ( is_singular( 'post' ) && get_option( 'page_for_posts' ) ) {
			$crumbs[] = array( get_the_title( get_option( 'page_for_posts' ) ), get_permalink( get_option( 'page_for_posts' ) ) );
		}
		$crumbs[] = array( get_the_title(), '' );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$crumbs[] = array( sprintf( __( 'Search: %s', 'esnine' ), get_search_query() ), '' );
	} elseif ( is_404() ) {
		$crumbs[] = array( __( 'Not Found', 'esnine' ), '' );
	} elseif ( is_home() ) {
		$crumbs[] = array( __( 'Blog', 'esnine' ), '' );
	} elseif ( is_archive() ) {
		$crumbs[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	}

	$crumbs = apply_filters( 'esnine_breadcrumbs', $crumbs );

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'esnine' ) . '"><ol>';
	foreach ( $crumbs as $crumb ) {
		if ( $crumb[1] ) {
			printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $crumb[1] ), esc_html( $crumb[0] ) );
		} else {
			printf( '<li aria-current="page">%s</li>', esc_html( $crumb[0] ) );
		}
	}
	echo '</ol></nav>';
}

/**
 * Print the page heading block used by archive, search and 404 templates.
 *
 * @param string $title       Heading.
 * @param string $description Optional intro text (may contain basic HTML).
 */
function esnine_page_head( $title, $description = '' ) {
	?>
	<header class="page-head">
		<?php esnine_breadcrumbs(); ?>
		<h1 class="section__title"><?php echo wp_kses_post( $title ); ?></h1>
		<?php if ( $description ) : ?>
			<div class="section__desc"><?php echo wp_kses_post( $description ); ?></div>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Print the "I agree with Terms of Use and Privacy Policy" consent text.
 */
function esnine_terms_text() {
	$privacy = get_privacy_policy_url();
	$open    = $privacy ? '<a href="' . esc_url( $privacy ) . '">' : '<span class="form__link">';
	$close   = $privacy ? '</a>' : '</span>';

	printf(
		/* translators: 1, 3: opening link tags. 2, 4: closing link tags. */
		esc_html__( 'I agree with %1$sTerms of Use%2$s and %3$sPrivacy Policy%4$s', 'esnine' ),
		$open, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped parts above.
		$close, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$open, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$close // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}

/**
 * Print a text that expands in place: the short version plus a "Read More"
 * button revealing the full version. Without a longer version only the short
 * text is printed.
 *
 * @param string $short        Collapsed text.
 * @param string $full         Expanded text.
 * @param string $button_class Class of the toggle button.
 * @param string $text_class   Class of the text paragraph.
 */
function esnine_expandable( $short, $full, $button_class, $text_class ) {
	$short = trim( $short );
	$more  = '' !== trim( $full ) && rtrim( $short, '. ' ) !== rtrim( trim( $full ), '. ' );

	if ( $more && '...' !== substr( $short, -3 ) ) {
		$short = rtrim( $short, '. ' ) . '...';
	}
	?>
	<div class="more" data-more>
		<p class="<?php echo esc_attr( $text_class ); ?>">
			<span data-more-short><?php echo esc_html( $short ); ?></span>
			<?php if ( $more ) : ?>
				<span data-more-full hidden><?php echo esc_html( $full ); ?></span>
			<?php endif; ?>
		</p>
		<?php if ( $more ) : ?>
			<button class="<?php echo esc_attr( $button_class ); ?>" type="button" aria-expanded="false" data-more-toggle data-more-label="<?php esc_attr_e( 'Read More', 'esnine' ); ?>" data-less-label="<?php esc_attr_e( 'Read Less', 'esnine' ); ?>"><?php esc_html_e( 'Read More', 'esnine' ); ?></button>
		<?php endif; ?>
	</div>
	<?php
}
