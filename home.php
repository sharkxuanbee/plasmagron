<?php
/**
 * Blog index / posts page template.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url  = industrial_welding_get_catalog_url();
$finder_url   = industrial_welding_get_finder_page_url();
$contact_url  = industrial_welding_get_contact_page_url();
$posts_page   = get_post( get_option( 'page_for_posts' ) );
$page_title   = $posts_page instanceof WP_Post ? get_the_title( $posts_page ) : __( 'Blog', 'industrial-welding' );
$page_summary = __( 'Use this page for product updates, welding tips, buying guidance, and practical content that supports machine selection and procurement decisions.', 'industrial-welding' );
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.22),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.16),_transparent_30%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Insights & Updates', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php echo esc_html( $page_title ); ?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php echo esc_html( $page_summary ); ?>
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
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Content Focus', 'industrial-welding' ); ?></p>
				<ul class="mt-5 space-y-4 text-sm text-slate-300">
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3"><?php esc_html_e( 'Machine comparisons and category guidance', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3"><?php esc_html_e( 'Welding and cutting tips for buyers and operators', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3"><?php esc_html_e( 'Documentation, maintenance, and purchase-readiness topics', 'industrial-welding' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
		<?php if ( have_posts() ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
				<?php while ( have_posts() ) : ?>
					<?php
					the_post();
					$raw_excerpt = industrial_welding_get_post_summary( get_the_ID(), 24 );
					$categories  = get_the_category();
					?>
					<article class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(15,23,42,0.84))] shadow-[0_24px_55px_rgba(2,6,23,0.42)] transition duration-300 hover:-translate-y-1 hover:border-amber-300/40">
						<div class="relative overflow-hidden border-b border-slate-800">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="block">
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'h-64 w-full object-cover object-center transition duration-500 group-hover:scale-[1.03]' ) ); ?>
								</a>
							<?php else : ?>
								<a href="<?php the_permalink(); ?>" class="flex h-64 items-center justify-center bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.18),_transparent_32%),linear-gradient(180deg,rgba(15,23,42,1),rgba(2,6,23,1))] text-slate-500">
									<span class="text-sm uppercase tracking-[0.22em] font-semibold font-rajdhani"><?php esc_html_e( 'Blog Update', 'industrial-welding' ); ?></span>
								</a>
							<?php endif; ?>
							<div class="absolute inset-x-0 top-0 flex flex-wrap items-center justify-between gap-3 p-4">
								<?php if ( ! empty( $categories ) ) : ?>
									<span class="inline-flex items-center rounded-full border border-amber-400/30 bg-slate-950/75 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200 font-rajdhani">
										<?php echo esc_html( $categories[0]->name ); ?>
									</span>
								<?php endif; ?>
								<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/80 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-slate-300 font-rajdhani">
									<?php echo esc_html( get_the_date() ); ?>
								</span>
							</div>
						</div>

						<div class="flex flex-1 flex-col p-6">
							<div class="flex-1">
								<h2 class="text-2xl font-bold text-white font-rajdhani leading-tight">
									<a href="<?php the_permalink(); ?>" class="transition group-hover:text-amber-200">
										<?php the_title(); ?>
									</a>
								</h2>
								<?php if ( $raw_excerpt ) : ?>
									<p class="mt-4 text-slate-300 leading-relaxed line-clamp-3">
										<?php echo esc_html( $raw_excerpt ); ?>
									</p>
								<?php endif; ?>
							</div>

							<div class="mt-6">
								<a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
									<?php esc_html_e( 'Read Article', 'industrial-welding' ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			$pagination = paginate_links(
				array(
					'total'     => (int) $GLOBALS['wp_query']->max_num_pages,
					'current'   => max( 1, absint( get_query_var( 'paged' ) ) ),
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
							: 'border-slate-700 bg-slate-900/75 text-slate-300 hover:border-amber-300 hover:text-amber-200';
						?>
						<span class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo esc_attr( $class ); ?>">
							<?php echo wp_kses_post( $page_link ); ?>
						</span>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-8 text-center shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'No Posts Yet', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'The blog is ready for your first article', 'industrial-welding' ); ?></h2>
				<p class="mt-4 max-w-2xl mx-auto text-slate-300 leading-relaxed"><?php esc_html_e( 'Publish your first post to turn this page into a useful resource hub for product guidance, welding knowledge, and buyer education.', 'industrial-welding' ); ?></p>
				<div class="mt-6 flex flex-col sm:flex-row justify-center gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
