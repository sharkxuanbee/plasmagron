<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content', get_post_type() );

	$previous_link = get_previous_post_link(
		'%link',
		'<span class="block text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold">' . esc_html__( 'Previous Entry', 'industrial-welding' ) . '</span><span class="mt-3 block text-xl font-bold text-white font-rajdhani">%title</span>'
	);
	$next_link     = get_next_post_link(
		'%link',
		'<span class="block text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold">' . esc_html__( 'Next Entry', 'industrial-welding' ) . '</span><span class="mt-3 block text-xl font-bold text-white font-rajdhani">%title</span>'
	);
	?>
	<section class="bg-slate-950">
		<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div class="rounded-[1.6rem] border border-slate-800 bg-slate-900/78 p-6 shadow-[0_20px_45px_rgba(2,6,23,0.32)]">
					<?php if ( $previous_link ) : ?>
						<?php echo wp_kses_post( $previous_link ); ?>
					<?php else : ?>
						<span class="block text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Previous Entry', 'industrial-welding' ); ?></span>
						<span class="mt-3 block text-lg text-slate-400"><?php esc_html_e( 'No earlier post is available in this sequence.', 'industrial-welding' ); ?></span>
					<?php endif; ?>
				</div>
				<div class="rounded-[1.6rem] border border-slate-800 bg-slate-900/78 p-6 shadow-[0_20px_45px_rgba(2,6,23,0.32)]">
					<?php if ( $next_link ) : ?>
						<?php echo wp_kses_post( $next_link ); ?>
					<?php else : ?>
						<span class="block text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Next Entry', 'industrial-welding' ); ?></span>
						<span class="mt-3 block text-lg text-slate-400"><?php esc_html_e( 'No newer post is available in this sequence.', 'industrial-welding' ); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<section class="bg-slate-900 border-t border-slate-800">
			<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
				<?php comments_template(); ?>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

<?php
get_footer();
