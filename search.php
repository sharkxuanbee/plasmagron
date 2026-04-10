<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url = industrial_welding_get_catalog_url();
$finder_url  = industrial_welding_get_finder_page_url();
$query_text  = get_search_query();
$result_count = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : 0;
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.2),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.16),_transparent_30%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Search', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php
					printf(
						/* translators: %s: search query. */
						esc_html__( 'Results for "%s"', 'industrial-welding' ),
						esc_html( $query_text )
					);
					?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %d: result count */
							_n( '%d result found across the current site content.', '%d results found across the current site content.', $result_count, 'industrial-welding' ),
							$result_count
						)
					);
					?>
				</p>
				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Fallback Coverage', 'industrial-welding' ); ?></p>
				<p class="mt-4 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'Search results now stay inside the same industrial listing system as the rest of the site, instead of dropping into the old starter-theme search template.', 'industrial-welding' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
				<?php while ( have_posts() ) : ?>
					<?php
					the_post();
					get_template_part( 'template-parts/content', 'search' );
					?>
				<?php endwhile; ?>
			</div>

			<?php
			$pagination = paginate_links(
				array(
					'total'     => (int) $GLOBALS['wp_query']->max_num_pages,
					'current'   => max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) ),
					'type'      => 'array',
					'prev_text' => __( 'Previous', 'industrial-welding' ),
					'next_text' => __( 'Next', 'industrial-welding' ),
				)
			);
			?>

			<?php if ( ! empty( $pagination ) ) : ?>
				<nav class="mt-10 flex flex-wrap gap-3" aria-label="<?php esc_attr_e( 'Pagination', 'industrial-welding' ); ?>">
					<?php foreach ( $pagination as $page_link ) : ?>
						<?php
						$is_current = false !== strpos( $page_link, 'current' );
						$class      = $is_current
							? 'border-amber-300 bg-amber-300/10 text-amber-200'
							: 'border-slate-700 bg-slate-950/70 text-slate-300 hover:border-amber-300 hover:text-amber-200';
						?>
						<span class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo esc_attr( $class ); ?>">
							<?php echo wp_kses_post( $page_link ); ?>
						</span>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
