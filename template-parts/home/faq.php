<?php
/**
 * Home: FAQ carousel.
 *
 * @package Esnine
 */

$esnine_items   = esnine_get_faqs();
$esnine_archive = get_post_type_archive_link( 'faq' );

if ( ! $esnine_items ) {
	return;
}
$esnine_section = array(
	'id'             => 'faq',
	'title'          => esnine_mod( 'faq_title' ),
	'description'    => esnine_mod( 'faq_text' ),
	'view_all_label' => esnine_mod( 'faq_button' ),
	'autoplay'       => (int) apply_filters( 'esnine_carousel_autoplay', 5000, 'faq' ),
	'view_all_url'   => (string) $esnine_archive,
);

esnine_carousel_open( $esnine_section );
foreach ( $esnine_items as $esnine_item ) :
	?>
	<li class="card faq">
		<h3 class="card__title faq__question"><?php echo esc_html( $esnine_item['question'] ); ?></h3>
		<p class="faq__answer"><?php echo esc_html( $esnine_item['answer'] ); ?></p>
		<a class="btn btn--muted faq__more" href="<?php echo esc_url( $esnine_item['url'] ); ?>"><?php esc_html_e( 'Read More', 'esnine' ); ?></a>
	</li>
	<?php
endforeach;
esnine_carousel_close( $esnine_section, count( $esnine_items ) );
