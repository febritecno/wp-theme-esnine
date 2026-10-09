<?php
/**
 * Properties page: search, filters, results grid and the matching form.
 *
 * @package Esnine
 */

get_header();

global $wp_query;

$esnine_filters   = esnine_property_filters();
$esnine_archive   = get_post_type_archive_link( 'property' );
$esnine_types     = get_terms( array( 'taxonomy' => 'property_type', 'hide_empty' => true ) );
$esnine_types     = is_wp_error( $esnine_types ) ? array() : wp_list_pluck( $esnine_types, 'name', 'slug' );
$esnine_locations = esnine_property_field_values( 'property_location' );
$esnine_years     = esnine_property_field_values( 'property_build_year' );
$esnine_page      = max( 1, (int) get_query_var( 'paged' ) );
$esnine_pages     = max( 1, (int) $wp_query->max_num_pages );
$esnine_filtered  = (bool) array_filter( $esnine_filters ) || get_search_query();
?>
<section class="phero">
	<div class="phero__text">
		<h1 class="section__title"><?php echo esc_html( esnine_mod( 'archive_title' ) ); ?></h1>
		<p class="section__desc"><?php echo esc_html( esnine_mod( 'archive_text' ) ); ?></p>
	</div>
	<form class="psearch" method="get" action="<?php echo esc_url( $esnine_archive ); ?>" role="search">
		<div class="psearch__bar">
			<label class="screen-reader-text" for="property-search"><?php esc_html_e( 'Search for a property', 'esnine' ); ?></label>
			<input class="psearch__input" type="search" id="property-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search For A Property', 'esnine' ); ?>">
			<input type="hidden" name="post_type" value="property">
			<button class="btn btn--primary psearch__submit" type="submit">
				<img src="<?php echo esc_url( esnine_asset( 'icon-search.svg' ) ); ?>" alt="" width="24" height="24">
				<span><?php esc_html_e( 'Find Property', 'esnine' ); ?></span>
			</button>
		</div>
		<div class="psearch__filters">
			<?php
			esnine_filter_select( 'location', __( 'Location', 'esnine' ), array_combine( $esnine_locations, $esnine_locations ), $esnine_filters['location'], 'icon-filter-location.svg' );
			esnine_filter_select( 'ptype', __( 'Property Type', 'esnine' ), $esnine_types, $esnine_filters['ptype'], 'icon-filter-type.svg' );
			esnine_filter_select( 'price', __( 'Pricing Range', 'esnine' ), wp_list_pluck( esnine_price_ranges(), 0 ), $esnine_filters['price'], 'icon-filter-price.svg' );
			esnine_filter_select( 'size', __( 'Property Size', 'esnine' ), wp_list_pluck( esnine_size_ranges(), 0 ), $esnine_filters['size'], 'icon-filter-size.svg' );
			esnine_filter_select( 'year', __( 'Build Year', 'esnine' ), array_combine( $esnine_years, $esnine_years ), $esnine_filters['year'], 'icon-filter-year.svg' );
			?>
		</div>
	</form>
</section>

<section class="section" id="results">
	<div class="section__heading">
		<span class="sparkles" aria-hidden="true">
			<span class="sparkles__lg"><img src="<?php echo esc_url( esnine_asset( 'sparkle-lg.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__md"><img src="<?php echo esc_url( esnine_asset( 'sparkle-md.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__sm"><img src="<?php echo esc_url( esnine_asset( 'sparkle-sm.svg' ) ); ?>" alt=""></span>
		</span>
		<h2 class="section__title"><?php echo esc_html( esnine_mod( 'results_title' ) ); ?></h2>
		<p class="section__desc">
			<?php
			if ( $esnine_filtered ) {
				/* translators: %s: number of properties found. */
				echo esc_html( sprintf( _n( '%s property matches your search.', '%s properties match your search.', $wp_query->found_posts, 'esnine' ), number_format_i18n( $wp_query->found_posts ) ) );
				printf( ' <a class="property__more" href="%s">%s</a>', esc_url( $esnine_archive ), esc_html__( 'Clear filters', 'esnine' ) );
			} else {
				echo esc_html( esnine_mod( 'results_text' ) );
			}
			?>
		</p>
	</div>
	<div class="section__body">
		<?php if ( have_posts() ) : ?>
			<ul class="cards">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content/card', 'property', array_merge( esnine_property_data( get_post() ), array( 'show_tagline' => true ) ) );
				}
				?>
			</ul>
			<nav class="pager" aria-label="<?php esc_attr_e( 'Properties pages', 'esnine' ); ?>">
				<p class="pager__count"><span><?php echo esc_html( sprintf( '%02d', $esnine_page ) ); ?></span> <?php esc_html_e( 'of', 'esnine' ); ?> <?php echo esc_html( sprintf( '%02d', $esnine_pages ) ); ?></p>
				<div class="pager__arrows">
					<?php
					foreach ( array(
						'prev' => array( $esnine_page > 1, $esnine_page - 1, __( 'Previous page', 'esnine' ), 'icon-arrow-left.svg' ),
						'next' => array( $esnine_page < $esnine_pages, $esnine_page + 1, __( 'Next page', 'esnine' ), 'icon-arrow-right.svg' ),
					) as $esnine_dir => $esnine_link ) {
						if ( $esnine_link[0] ) {
							printf( '<a class="pager__btn pager__btn--%1$s" href="%2$s#results" aria-label="%3$s"><img src="%4$s" alt=""></a>', esc_attr( $esnine_dir ), esc_url( get_pagenum_link( $esnine_link[1] ) ), esc_attr( $esnine_link[2] ), esc_url( esnine_asset( $esnine_link[3] ) ) );
						} else {
							printf( '<span class="pager__btn pager__btn--%1$s is-disabled" aria-hidden="true"><img src="%2$s" alt=""></span>', esc_attr( $esnine_dir ), esc_url( esnine_asset( $esnine_link[3] ) ) );
						}
					}
					?>
				</div>
			</nav>
		<?php else : ?>
			<div class="card no-results">
				<h3 class="card__title"><?php esc_html_e( 'No properties found', 'esnine' ); ?></h3>
				<p class="faq__answer"><?php esc_html_e( 'Try removing a filter or searching for a different location.', 'esnine' ); ?></p>
				<a class="btn btn--primary" href="<?php echo esc_url( $esnine_archive ); ?>"><?php esc_html_e( 'View All Properties', 'esnine' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_template_part(
	'template-parts/property/match-form',
	null,
	array(
		'locations' => $esnine_locations,
		'types'     => $esnine_types,
	)
);

get_footer();
