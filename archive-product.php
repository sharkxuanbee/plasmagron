<?php
/**
 * WooCommerce product archive template.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$current_term          = industrial_welding_get_catalog_current_term();
$current_taxonomy      = $current_term ? $current_term->taxonomy : 'product';
$catalog_url           = industrial_welding_get_catalog_url();
$finder_url            = industrial_welding_get_finder_page_url();
$compare_url           = industrial_welding_get_compare_page_url();
$contact_url           = industrial_welding_get_contact_page_url();
$filterable_taxonomies = industrial_welding_get_filterable_catalog_taxonomies();
$requested_filters     = industrial_welding_get_requested_catalog_filters();
$active_filters        = industrial_welding_get_active_catalog_filters( $current_term );
$active_filter_count   = count( $active_filters );
$product_counts        = wp_count_posts( 'product' );
$product_total         = $product_counts ? (int) $product_counts->publish : 0;
$found_products        = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : 0;
$coverage              = industrial_welding_get_catalog_data_coverage();
$product_categories    = industrial_welding_get_catalog_filter_terms( 'product_cat' );
$compare_min_selection = 2;
$selected_compare_ids  = industrial_welding_get_requested_compare_ids();
$selected_count        = count( $selected_compare_ids );

$current_base_url = $catalog_url;

if ( $current_term ) {
	$current_term_url = get_term_link( $current_term );

	if ( ! is_wp_error( $current_term_url ) ) {
		$current_base_url = $current_term_url;
	}
}

$archive_eyebrow     = __( 'Machine Catalog', 'industrial-welding' );
$archive_title       = industrial_welding_get_machine_label( true );
$archive_description = __( 'Use category pages, reusable filters, and Finder to turn a wide catalog into a smaller and more credible shortlist.', 'industrial-welding' );

if ( $current_term instanceof WP_Term ) {
	$archive_title = $current_term->name;

	if ( 'product_cat' === $current_term->taxonomy ) {
		$archive_eyebrow     = __( 'Category Landing', 'industrial-welding' );
		$archive_description = $current_term->description
			? wp_strip_all_tags( $current_term->description )
			: __( 'This category page is the stable landing spot for buyers who already know the machine type they want to explore.', 'industrial-welding' );
	} elseif ( isset( $filterable_taxonomies[ $current_term->taxonomy ] ) ) {
		$archive_eyebrow     = sprintf(
			/* translators: %s: filter taxonomy label */
			__( '%s Landing', 'industrial-welding' ),
			$filterable_taxonomies[ $current_term->taxonomy ]['short_label']
		);
		$archive_description = $current_term->description
			? wp_strip_all_tags( $current_term->description )
			: $filterable_taxonomies[ $current_term->taxonomy ]['archive_intro'];
	}
}

$clear_filters_url = $current_term
	? $current_base_url
	: $catalog_url;
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.22),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.16),_transparent_32%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_minmax(320px,360px)] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php echo esc_html( $archive_eyebrow ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php echo esc_html( $archive_title ); ?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php echo esc_html( $archive_description ); ?>
				</p>
				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Browse All Machines', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Selection Status', 'industrial-welding' ); ?></p>
				<div class="mt-5 grid grid-cols-2 gap-4">
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold"><?php esc_html_e( 'Published', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( number_format_i18n( $product_total ) ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold"><?php esc_html_e( 'Visible Now', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( number_format_i18n( $found_products ) ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold"><?php esc_html_e( 'Active Filters', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $active_filter_count ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold"><?php esc_html_e( 'Ready Profiles', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $coverage['rows']['selection_complete']['percent'] ); ?>%</p>
					</div>
				</div>
				<p class="mt-5 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'Category pages, filters, and Finder all read the same usage, skill, budget, category, and core spec structure, so the selection logic stays consistent.', 'industrial-welding' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
		<div class="rounded-[1.8rem] border border-slate-800 bg-slate-950/70 p-6 md:p-7">
			<div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-6">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Filter System', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Narrow the catalog without losing the compare flow', 'industrial-welding' ); ?></h2>
					<p class="mt-3 max-w-3xl text-slate-300 leading-relaxed"><?php esc_html_e( 'Filters use shareable URLs and stay additive to category landings. If the shortlist still feels unclear, switch to Finder instead of guessing.', 'industrial-welding' ); ?></p>
				</div>
				<div class="flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $clear_filters_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
						<?php esc_html_e( 'Clear Extra Filters', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Use Finder Instead', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

			<div class="space-y-6">
				<div>
					<div class="flex items-center justify-between gap-4 mb-3">
						<h3 class="text-lg font-bold text-white font-rajdhani"><?php esc_html_e( 'Product Categories', 'industrial-welding' ); ?></h3>
						<span class="text-sm text-slate-500"><?php esc_html_e( 'Stable landing pages for machine types', 'industrial-welding' ); ?></span>
					</div>
					<div class="flex flex-wrap gap-3">
						<a href="<?php echo esc_url( industrial_welding_get_catalog_url_with_filters( array(), $catalog_url ) ); ?>" class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo 'product_cat' !== $current_taxonomy ? 'border-amber-300 bg-amber-300/10 text-amber-200' : 'border-slate-700 bg-slate-900/75 text-slate-300 hover:border-amber-300 hover:text-amber-200'; ?>">
							<?php esc_html_e( 'All Machines', 'industrial-welding' ); ?>
						</a>
						<?php foreach ( $product_categories as $term ) : ?>
							<?php
							$term_link = get_term_link( $term );

							if ( is_wp_error( $term_link ) ) {
								continue;
							}

							$term_url = industrial_welding_get_catalog_url_with_filters( array(), $term_link );
							$is_active = $current_term instanceof WP_Term && 'product_cat' === $current_taxonomy && (int) $current_term->term_id === (int) $term->term_id;
							?>
							<a href="<?php echo esc_url( $term_url ); ?>" class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo $is_active ? 'border-amber-300 bg-amber-300/10 text-amber-200' : 'border-slate-700 bg-slate-900/75 text-slate-300 hover:border-amber-300 hover:text-amber-200'; ?>">
								<?php echo esc_html( $term->name ); ?>
								<span class="ml-2 rounded-full bg-slate-800 px-2 py-0.5 text-xs text-slate-400"><?php echo esc_html( number_format_i18n( $term->count ) ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>

				<?php foreach ( $filterable_taxonomies as $taxonomy => $config ) : ?>
					<?php
					$terms       = industrial_welding_get_catalog_filter_terms( $taxonomy );
					$active_slug = isset( $active_filters[ $taxonomy ] ) ? $active_filters[ $taxonomy ] : '';
					$all_url     = $current_term instanceof WP_Term && $current_term->taxonomy === $taxonomy
						? industrial_welding_get_catalog_url_with_filters( array( $taxonomy => null ), $catalog_url )
						: industrial_welding_get_catalog_url_with_filters( array( $taxonomy => null ), $current_base_url );
					?>
					<div>
						<div class="flex items-center justify-between gap-4 mb-3">
							<h3 class="text-lg font-bold text-white font-rajdhani"><?php echo esc_html( $config['label'] ); ?></h3>
							<span class="text-sm text-slate-500"><?php echo esc_html( $config['question_hint'] ); ?></span>
						</div>
						<div class="flex flex-wrap gap-3">
							<a href="<?php echo esc_url( $all_url ); ?>" class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo '' === $active_slug ? 'border-amber-300 bg-amber-300/10 text-amber-200' : 'border-slate-700 bg-slate-900/75 text-slate-300 hover:border-amber-300 hover:text-amber-200'; ?>">
								<?php echo esc_html( sprintf( __( 'All %s', 'industrial-welding' ), $config['short_label'] ) ); ?>
							</a>
							<?php foreach ( $terms as $term ) : ?>
								<?php
								if ( $current_term instanceof WP_Term && $current_term->taxonomy === $taxonomy ) {
									$term_link = get_term_link( $term );

									if ( is_wp_error( $term_link ) ) {
										continue;
									}

									$term_url = industrial_welding_get_catalog_url_with_filters( array( $taxonomy => null ), $term_link );
								} else {
									$term_url = industrial_welding_get_catalog_url_with_filters( array( $taxonomy => $term->slug ), $current_base_url );
								}

								$is_active = $active_slug === $term->slug;
								?>
								<a href="<?php echo esc_url( $term_url ); ?>" class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo $is_active ? 'border-amber-300 bg-amber-300/10 text-amber-200' : 'border-slate-700 bg-slate-900/75 text-slate-300 hover:border-amber-300 hover:text-amber-200'; ?>">
									<?php echo esc_html( $term->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<?php if ( $active_filter_count > 0 ) : ?>
	<section class="bg-slate-950 border-b border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
			<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
				<div class="flex flex-wrap gap-3">
					<?php foreach ( $active_filters as $taxonomy => $slug ) : ?>
						<?php
						if ( ! isset( $filterable_taxonomies[ $taxonomy ] ) ) {
							continue;
						}

						$term = get_term_by( 'slug', $slug, $taxonomy );

						if ( ! $term || is_wp_error( $term ) ) {
							continue;
						}

						$remove_url = $current_term instanceof WP_Term && $current_term->taxonomy === $taxonomy
							? industrial_welding_get_catalog_url_with_filters( array( $taxonomy => null ), $catalog_url )
							: industrial_welding_get_catalog_url_with_filters( array( $taxonomy => null ), $current_base_url );
						?>
						<a href="<?php echo esc_url( $remove_url ); ?>" class="inline-flex items-center rounded-full border border-amber-300/40 bg-amber-300/10 px-4 py-2 text-sm text-amber-100 transition hover:border-amber-200 hover:text-white">
							<?php echo esc_html( $filterable_taxonomies[ $taxonomy ]['short_label'] . ': ' . $term->name ); ?>
							<span class="ml-3 text-amber-300">×</span>
						</a>
					<?php endforeach; ?>
				</div>

				<div class="rounded-full border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm text-slate-300">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %d: number of visible products */
							_n( '%d machine matches the current selection', '%d machines match the current selection', $found_products, 'industrial-welding' ),
							$found_products
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<?php if ( have_posts() ) : ?>
			<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Decision-Ready Cards', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Keep narrowing until the shortlist is ready for detail or compare', 'industrial-welding' ); ?></h2>
				</div>
				<div class="flex flex-col sm:flex-row gap-3">
					<button
						id="compare-selected-button"
						type="button"
						data-label="<?php esc_attr_e( 'Compare Selected', 'industrial-welding' ); ?>"
						data-base-url="<?php echo esc_url( $compare_url ); ?>"
						class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani <?php echo $selected_count >= $compare_min_selection ? '' : 'opacity-50 cursor-not-allowed'; ?>"
						<?php disabled( $selected_count < $compare_min_selection ); ?>
					>
						<?php esc_html_e( 'Compare Selected', 'industrial-welding' ); ?>
					</button>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 xl:gap-7">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; ?>
			</div>

			<div class="mt-12">
				<?php
				the_posts_pagination(
					array(
						'mid_size'           => 2,
						'prev_text'          => sprintf(
							'<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-slate-300 transition hover:border-amber-300 hover:text-amber-200">%s</span>',
							esc_html__( 'Previous', 'industrial-welding' )
						),
						'next_text'          => sprintf(
							'<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-slate-300 transition hover:border-amber-300 hover:text-amber-200">%s</span>',
							esc_html__( 'Next', 'industrial-welding' )
						),
						'screen_reader_text' => __( 'Pagination', 'industrial-welding' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="rounded-[2rem] border border-dashed border-slate-700 bg-slate-900/70 px-6 py-16 text-center">
				<div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-950 text-slate-500">
					<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
					</svg>
				</div>
				<h2 class="mt-6 text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'No machines match the current path', 'industrial-welding' ); ?></h2>
				<p class="mt-3 max-w-2xl mx-auto text-slate-400 leading-relaxed"><?php esc_html_e( 'The current category and filter combination is too narrow. Clear the extra filters or use Finder so the system can recommend the closest machines instead of leaving you in a dead end.', 'industrial-welding' ); ?></p>
				<div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
					<a href="<?php echo esc_url( $clear_filters_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
						<?php esc_html_e( 'Clear Extra Filters', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Use Finder', 'industrial-welding' ); ?>
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
