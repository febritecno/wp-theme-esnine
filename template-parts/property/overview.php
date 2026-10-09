<?php
/**
 * Property header, photo gallery, description and key features.
 *
 * @package Esnine
 */

$esnine_property = esnine_property_data( get_post() );
$esnine_gallery  = esnine_property_gallery( get_the_ID() );
$esnine_features = esnine_lines( esnine_field( 'property_features' ) );
$esnine_stats    = array_filter(
	array(
		'icon-bed.svg'  => array( __( 'Bedrooms', 'esnine' ), $esnine_property['bedrooms'] ? sprintf( '%02d', $esnine_property['bedrooms'] ) : '' ),
		'icon-bath.svg' => array( __( 'Bathrooms', 'esnine' ), $esnine_property['bathrooms'] ? sprintf( '%02d', $esnine_property['bathrooms'] ) : '' ),
		'icon-area.svg' => array( __( 'Area', 'esnine' ), $esnine_property['area'] ),
	),
	static function ( $stat ) {
		return '' !== $stat[1];
	}
);
?>
<article <?php post_class( 'section section--page pd' ); ?>>
	<div class="pd__top">
		<header class="pd__head">
			<div class="pd__title-wrap">
				<h1 class="pd__title"><?php the_title(); ?></h1>
				<?php if ( $esnine_property['location'] ) : ?>
					<p class="pd__location">
						<img src="<?php echo esc_url( esnine_asset( 'icon-location.svg' ) ); ?>" alt="" width="24" height="24">
						<?php echo esc_html( $esnine_property['location'] ); ?>
					</p>
				<?php endif; ?>
			</div>
			<?php if ( $esnine_property['price'] ) : ?>
				<div class="pd__price">
					<p class="property__price-label"><?php esc_html_e( 'Price', 'esnine' ); ?></p>
					<p class="property__price"><?php echo esc_html( $esnine_property['price'] ); ?></p>
				</div>
			<?php endif; ?>
		</header>

		<?php if ( $esnine_gallery ) : ?>
			<div class="gallery" data-gallery>
				<ul class="gallery__thumbs">
					<?php foreach ( $esnine_gallery as $esnine_index => $esnine_id ) : ?>
						<li>
							<?php /* translators: %d: photo number. */ ?>
							<button class="gallery__thumb" type="button" data-gallery-thumb aria-label="<?php echo esc_attr( sprintf( __( 'Show photo %d', 'esnine' ), $esnine_index + 1 ) ); ?>">
								<?php echo wp_get_attachment_image( $esnine_id, 'medium', false, array( 'alt' => '' ) ); ?>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>
				<ul class="gallery__stage">
					<?php foreach ( $esnine_gallery as $esnine_index => $esnine_id ) : ?>
						<li class="gallery__slide" data-gallery-slide>
							<?php
							$esnine_alt = get_post_meta( $esnine_id, '_wp_attachment_image_alt', true );
							echo wp_get_attachment_image(
								$esnine_id,
								'large',
								false,
								array(
									/* translators: 1: property name, 2: photo number. */
									'alt'           => $esnine_alt ? $esnine_alt : sprintf( __( '%1$s photo %2$d', 'esnine' ), get_the_title(), $esnine_index + 1 ),
									'loading'       => $esnine_index < 2 ? 'eager' : 'lazy',
									'fetchpriority' => 0 === $esnine_index ? 'high' : 'auto',
								)
							);
							?>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( count( $esnine_gallery ) > 1 ) : ?>
					<div class="gallery__nav">
						<button class="pager__btn pager__btn--prev" type="button" aria-label="<?php esc_attr_e( 'Previous photos', 'esnine' ); ?>" data-gallery-prev>
							<img src="<?php echo esc_url( esnine_asset( 'icon-arrow-left.svg' ) ); ?>" alt="">
						</button>
						<div class="gallery__dots" data-gallery-dots aria-hidden="true"></div>
						<button class="pager__btn pager__btn--next" type="button" aria-label="<?php esc_attr_e( 'Next photos', 'esnine' ); ?>" data-gallery-next>
							<img src="<?php echo esc_url( esnine_asset( 'icon-arrow-right.svg' ) ); ?>" alt="">
						</button>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="pd__info">
		<section class="pd__panel">
			<div class="pd__panel-text">
				<h2 class="card__title"><?php esc_html_e( 'Description', 'esnine' ); ?></h2>
				<div class="pd__description entry"><?php the_content(); ?></div>
			</div>
			<?php if ( $esnine_stats ) : ?>
				<dl class="pd__stats">
					<?php foreach ( $esnine_stats as $esnine_icon => $esnine_stat ) : ?>
						<div class="pd__stat">
							<dt>
								<span class="chip__icon"><img src="<?php echo esc_url( esnine_asset( $esnine_icon ) ); ?>" alt=""></span>
								<?php echo esc_html( $esnine_stat[0] ); ?>
							</dt>
							<dd><?php echo esc_html( $esnine_stat[1] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>
		</section>

		<?php if ( $esnine_features ) : ?>
			<section class="pd__panel">
				<h2 class="card__title"><?php esc_html_e( 'Key Features and Amenities', 'esnine' ); ?></h2>
				<ul class="pd__features">
					<?php foreach ( $esnine_features as $esnine_feature ) : ?>
						<li class="pd__feature">
							<img class="pd__feature-bg" src="<?php echo esc_url( esnine_asset( 'feature-bg.png' ) ); ?>" alt="">
							<img class="pd__feature-icon" src="<?php echo esc_url( esnine_asset( 'icon-bolt.svg' ) ); ?>" alt="" width="24" height="24">
							<span><?php echo esc_html( $esnine_feature ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>
	</div>
</article>
