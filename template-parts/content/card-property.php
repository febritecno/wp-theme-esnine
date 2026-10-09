<?php
/**
 * Property card.
 *
 * @package Esnine
 *
 * @var array $args Property data from esnine_property_data().
 */

$esnine_property = $args;
// On the Properties page the card shows its tag instead of the detail chips.
$esnine_tagline  = ( ! empty( $esnine_property['show_tagline'] ) && ! empty( $esnine_property['tagline'] ) ) ? $esnine_property['tagline'] : '';
?>
<li class="card property">
	<a class="property__media" href="<?php echo esc_url( $esnine_property['url'] ); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( $esnine_property['image_id'] ) {
			echo wp_get_attachment_image( $esnine_property['image_id'], 'large', false, array( 'class' => 'property__image', 'alt' => $esnine_property['name'], 'loading' => 'lazy' ) );
		}
		?>
	</a>
	<?php if ( $esnine_tagline ) : ?>
		<p class="chip property__tag"><?php echo esc_html( $esnine_tagline ); ?></p>
	<?php endif; ?>
	<div class="property__text">
		<h3 class="card__title"><?php echo esc_html( $esnine_property['name'] ); ?></h3>
		<?php esnine_expandable( $esnine_property['summary'], $esnine_property['full'], 'property__more', 'property__summary' ); ?>
	</div>
	<?php
	if ( ! $esnine_tagline ) {
		esnine_property_chips( $esnine_property );
	}
	?>
	<div class="property__footer">
		<?php if ( $esnine_property['price'] ) : ?>
			<div>
				<p class="property__price-label"><?php esc_html_e( 'Price', 'esnine' ); ?></p>
				<p class="property__price"><?php echo esc_html( $esnine_property['price'] ); ?></p>
			</div>
		<?php endif; ?>
		<a class="btn btn--primary property__cta" href="<?php echo esc_url( $esnine_property['url'] ); ?>"><?php esc_html_e( 'View Property Details', 'esnine' ); ?></a>
	</div>
</li>
