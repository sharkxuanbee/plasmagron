<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'overflow-hidden rounded-[1.8rem] border border-slate-800 bg-slate-950/78 shadow-[0_24px_55px_rgba(2,6,23,0.42)]' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="border-b border-slate-800">
			<?php the_post_thumbnail( 'large', array( 'class' => 'h-auto w-full object-cover object-center max-h-[520px]' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="p-6 md:p-8 xl:p-10">
		<div class="entry-content text-slate-200 leading-relaxed">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="mt-8 flex flex-wrap gap-3 items-center text-sm text-slate-300"><span class="font-semibold uppercase tracking-[0.18em] text-slate-500">' . esc_html__( 'Pages', 'industrial-welding' ) . '</span>',
					'after'  => '</div>',
				)
			);
			?>
		</div>

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer mt-8 border-t border-slate-800 pt-6 text-sm text-slate-400">
				<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: page title for screen readers. */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'industrial-welding' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					),
					'<span class="inline-flex items-center rounded-full border border-slate-700 px-4 py-2 hover:border-amber-300 hover:text-amber-200 transition">',
					'</span>'
				);
				?>
			</footer>
		<?php endif; ?>
	</div>
</article>
