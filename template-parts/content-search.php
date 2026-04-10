<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_type        = get_post_type();
$post_type_object = get_post_type_object( $post_type );
$search_summary   = industrial_welding_get_post_summary( get_the_ID(), 24 );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(15,23,42,0.84))] shadow-[0_24px_55px_rgba(2,6,23,0.42)] transition duration-300 hover:-translate-y-1 hover:border-amber-300/40' ); ?>>
	<div class="relative overflow-hidden border-b border-slate-800">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="block">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'h-64 w-full object-cover object-center transition duration-500 group-hover:scale-[1.03]' ) ); ?>
			</a>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" class="flex h-64 items-center justify-center bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.18),_transparent_32%),linear-gradient(180deg,rgba(15,23,42,1),rgba(2,6,23,1))] text-slate-500">
				<span class="text-sm uppercase tracking-[0.22em] font-semibold font-rajdhani">
					<?php echo esc_html( $post_type_object ? $post_type_object->labels->singular_name : __( 'Result', 'industrial-welding' ) ); ?>
				</span>
			</a>
		<?php endif; ?>
		<div class="absolute inset-x-0 top-0 flex flex-wrap items-center justify-between gap-3 p-4">
			<span class="inline-flex items-center rounded-full border border-amber-400/30 bg-slate-950/75 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200 font-rajdhani">
				<?php echo esc_html( $post_type_object ? $post_type_object->labels->singular_name : __( 'Result', 'industrial-welding' ) ); ?>
			</span>
			<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/80 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-slate-300 font-rajdhani">
				<?php echo esc_html( get_the_date() ); ?>
			</span>
		</div>
	</div>

	<div class="flex flex-1 flex-col p-6">
		<div class="flex-1">
			<h2 class="text-2xl font-bold text-white font-rajdhani leading-tight">
				<a href="<?php the_permalink(); ?>" rel="bookmark" class="transition group-hover:text-amber-200"><?php the_title(); ?></a>
			</h2>
			<?php if ( $search_summary ) : ?>
				<p class="mt-4 text-slate-300 leading-relaxed line-clamp-3"><?php echo esc_html( $search_summary ); ?></p>
			<?php endif; ?>
		</div>

		<div class="mt-6">
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
				<?php esc_html_e( 'Open Result', 'industrial-welding' ); ?>
			</a>
		</div>
	</div>
</article>
