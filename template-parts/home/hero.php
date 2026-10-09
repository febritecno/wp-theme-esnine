<?php
/**
 * Home hero with stats and feature cards.
 *
 * @package Esnine
 */

$esnine_image    = (int) esnine_mod( 'hero_image' );
$esnine_badge    = (string) esnine_mod( 'hero_badge_text' );
$esnine_features = esnine_get_features();
$esnine_buttons  = array_filter(
	array(
		'btn--outline' => array( esnine_mod( 'hero_secondary_label' ), esnine_mod( 'hero_secondary_url' ) ),
		'btn--primary' => array( esnine_mod( 'hero_primary_label' ), esnine_mod_link( 'hero_primary_url' ) ),
	),
	static function ( $button ) {
		return $button[0] && $button[1];
	}
);
?>
<section class="hero">
	<div class="hero__main">
		<div class="hero__content">
			<div class="hero__text">
				<h1 class="hero__title"><?php echo nl2br( esc_html( trim( (string) esnine_mod( 'hero_title' ) ) ) ); ?></h1>
				<p class="hero__lead"><?php echo esc_html( esnine_mod( 'hero_lead' ) ); ?></p>
				<?php if ( $esnine_badge ) : ?>
					<a class="hero__badge" href="#properties" aria-label="<?php echo esc_attr( $esnine_badge ); ?>">
						<span class="hero__badge-shape"></span>
						<img class="hero__badge-icon" src="<?php echo esc_url( esnine_asset( 'icon-arrow-badge.svg' ) ); ?>" alt="">
						<svg class="hero__badge-ring" viewBox="0 0 175 175" aria-hidden="true">
							<defs>
								<path id="hero-badge-path" d="M87.5,144.5 a57,57 0 1,1 0.01,0" />
							</defs>
							<text>
								<textPath href="#hero-badge-path" textLength="352" lengthAdjust="spacing"><?php echo esc_html( $esnine_badge ); ?></textPath>
							</text>
						</svg>
					</a>
				<?php endif; ?>
			</div>
			<?php if ( $esnine_buttons ) : ?>
				<div class="hero__actions">
					<?php foreach ( $esnine_buttons as $esnine_class => $esnine_button ) : ?>
						<a class="btn <?php echo esc_attr( $esnine_class ); ?>" href="<?php echo esc_url( $esnine_button[1] ); ?>"><?php echo esc_html( $esnine_button[0] ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<ul class="hero__stats">
				<?php for ( $esnine_i = 1; $esnine_i <= 3; $esnine_i++ ) : ?>
					<?php if ( esnine_mod( "stat_{$esnine_i}_value" ) ) : ?>
						<li class="stat">
							<p class="stat__value" data-count><?php echo esc_html( esnine_mod( "stat_{$esnine_i}_value" ) ); ?></p>
							<p class="stat__label"><?php echo esc_html( esnine_mod( "stat_{$esnine_i}_label" ) ); ?></p>
						</li>
					<?php endif; ?>
				<?php endfor; ?>
			</ul>
		</div>
		<div class="hero__visual">
			<img class="hero__pattern" src="<?php echo esc_url( esnine_asset( 'hero-pattern.svg' ) ); ?>" alt="">
			<?php
			if ( $esnine_image ) {
				echo wp_get_attachment_image( $esnine_image, 'full', false, array( 'class' => 'hero__image', 'fetchpriority' => 'high', 'loading' => 'eager', 'sizes' => '(max-width: 1100px) 100vw, 50vw' ) );
			}
			?>
		</div>
	</div>
	<?php if ( $esnine_features ) : ?>
		<ul class="features" id="services">
			<?php foreach ( $esnine_features as $esnine_feature ) : ?>
				<li>
					<a class="feature" href="<?php echo esc_url( $esnine_feature['url'] ); ?>">
						<span class="feature__ring">
							<span class="feature__ring-inner">
								<?php if ( $esnine_feature['icon'] ) : ?>
									<img src="<?php echo esc_url( esnine_asset( $esnine_feature['icon'] ) ); ?>" alt="">
								<?php endif; ?>
							</span>
						</span>
						<span class="feature__label"><?php echo esc_html( $esnine_feature['label'] ); ?></span>
						<img class="feature__arrow" src="<?php echo esc_url( esnine_asset( 'icon-arrow-corner.svg' ) ); ?>" alt="">
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
