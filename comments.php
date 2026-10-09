<?php
/**
 * Comments list and form.
 *
 * @package Esnine
 */

if ( post_password_required() ) {
	return;
}
?>
<section class="comments" id="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="card__title">
			<?php
			/* translators: %s: number of comments. */
			echo esc_html( sprintf( _n( '%s Comment', '%s Comments', get_comments_number(), 'esnine' ), number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 50,
				)
			);
			?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="faq__answer"><?php esc_html_e( 'Comments are closed.', 'esnine' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
