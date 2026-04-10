<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Industrial_Welding
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area rounded-[1.8rem] border border-slate-800 bg-slate-950/78 p-6 md:p-8 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">

	<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title text-3xl font-bold text-white font-rajdhani">
			<?php
			$comment_count = get_comments_number();
			if ( '1' === $comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One comment on "%1$s"', 'industrial-welding' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s comment on "%2$s"', '%1$s comments on "%2$s"', $comment_count, 'comments title', 'industrial-welding' ) ),
					number_format_i18n( $comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2>

		<div class="mt-6 text-sm text-slate-400">
			<?php the_comments_navigation(); ?>
		</div>

		<ol class="comment-list mt-8 space-y-6">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>

		<div class="mt-6 text-sm text-slate-400">
			<?php
			the_comments_navigation();
			?>
		</div>

		<?php
		if ( ! comments_open() ) :
			?>
			<p class="no-comments mt-6 text-sm text-slate-400"><?php esc_html_e( 'Comments are closed.', 'industrial-welding' ); ?></p>
			<?php
		endif;

	endif;

	echo '<div class="mt-8 border-t border-slate-800 pt-8">';
	comment_form();
	echo '</div>';
	?>

</div>
