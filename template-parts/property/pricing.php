<?php
/**
 * Property pricing breakdown.
 *
 * @package Esnine
 */

$esnine_price = esnine_format_price( esnine_field( 'property_price' ) );
$esnine_note  = (string) esnine_field( 'property_pricing_note' );
$esnine_note  = $esnine_note ? $esnine_note : (string) esnine_mod( 'pricing_note' );
$esnine_cards = array_filter(
	array(
		__( 'Additional Fees', 'esnine' )     => esnine_cost_rows( esnine_field( 'property_additional_fees' ) ),
		__( 'Monthly Costs', 'esnine' )       => esnine_cost_rows( esnine_field( 'property_monthly_costs' ) ),
		__( 'Total Initial Costs', 'esnine' ) => esnine_cost_rows( esnine_field( 'property_initial_costs' ) ),
		__( 'Monthly Expenses', 'esnine' )    => esnine_cost_rows( esnine_field( 'property_monthly_expenses' ) ),
	)
);

if ( ! $esnine_cards ) {
	return;
}
?>
<section class="section pricing" id="pricing">
	<div class="section__heading">
		<span class="sparkles" aria-hidden="true">
			<span class="sparkles__lg"><img src="<?php echo esc_url( esnine_asset( 'sparkle-lg.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__md"><img src="<?php echo esc_url( esnine_asset( 'sparkle-md.svg' ) ); ?>" alt=""></span>
			<span class="sparkles__sm"><img src="<?php echo esc_url( esnine_asset( 'sparkle-sm.svg' ) ); ?>" alt=""></span>
		</span>
		<h2 class="section__title"><?php echo esc_html( esnine_mod( 'pricing_title' ) ); ?></h2>
		<?php /* translators: %s: property name. */ ?>
		<p class="section__desc"><?php echo esc_html( esnine_mod_for_property( 'pricing_text' ) ); ?></p>
	</div>

	<div class="pricing__body">
		<?php if ( $esnine_note ) : ?>
			<p class="pricing__note">
				<strong><?php esc_html_e( 'Note', 'esnine' ); ?></strong>
				<span><?php echo esc_html( $esnine_note ); ?></span>
			</p>
		<?php endif; ?>

		<div class="pricing__layout">
			<?php if ( $esnine_price ) : ?>
				<div class="pricing__listing">
					<p class="pricing__listing-label"><?php esc_html_e( 'Listing Price', 'esnine' ); ?></p>
					<p class="pricing__listing-value"><?php echo esc_html( $esnine_price ); ?></p>
				</div>
			<?php endif; ?>

			<div class="pricing__cards">
				<?php foreach ( $esnine_cards as $esnine_title => $esnine_rows ) : ?>
					<section class="pricing__card">
						<header class="pricing__card-head">
							<h3 class="card__title"><?php echo esc_html( $esnine_title ); ?></h3>
							<a class="btn btn--muted" href="#inquiry"><?php esc_html_e( 'Learn More', 'esnine' ); ?></a>
						</header>
						<dl class="pricing__rows<?php echo count( $esnine_rows ) <= 2 ? ' pricing__rows--stack' : ''; ?>">
							<?php foreach ( $esnine_rows as $esnine_row ) : ?>
								<div class="pricing__row">
									<dt><?php echo esc_html( $esnine_row['label'] ); ?></dt>
									<dd>
										<span class="pricing__value"><?php echo esc_html( $esnine_row['value'] ); ?></span>
										<?php if ( $esnine_row['note'] ) : ?>
											<span class="pricing__pill"><?php echo esc_html( $esnine_row['note'] ); ?></span>
										<?php endif; ?>
									</dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
