<?php
/**
 * Template Name: Compare Machines
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$compare_query_key      = industrial_welding_get_compare_query_key();
$compare_min_selection  = industrial_welding_get_compare_min_selection();
$requested_ids          = industrial_welding_get_requested_compare_ids();
$catalog_url            = industrial_welding_get_catalog_url();
$finder_url             = industrial_welding_get_finder_page_url();
$contact_url            = industrial_welding_get_contact_page_url();
$products_data          = array();
$selected_product_ids   = array();
$selected_count         = 0;
$remaining_to_compare   = $compare_min_selection;
$has_full_compare_view  = false;

if ( ! empty( $requested_ids ) ) {
	$compare_query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => count( $requested_ids ),
			'post__in'       => $requested_ids,
			'orderby'        => 'post__in',
		)
	);

	if ( $compare_query->have_posts() ) {
		while ( $compare_query->have_posts() ) {
			$compare_query->the_post();

			$product = industrial_welding_is_woocommerce_active() ? wc_get_product( get_the_ID() ) : null;
			$summary = $product ? industrial_welding_get_product_summary( $product, 22 ) : get_the_excerpt();
			$term    = industrial_welding_get_primary_product_term( get_the_ID() );

			$products_data[] = array(
				'id'            => get_the_ID(),
				'title'         => get_the_title(),
				'category'      => $term ? $term->name : industrial_welding_get_machine_label(),
				'permalink'     => get_permalink(),
				'thumbnail'     => get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ),
				'price_html'    => $product ? $product->get_price_html() : '',
				'amperage'      => industrial_welding_get_product_meta( get_the_ID(), 'amperage' ),
				'input_voltage' => industrial_welding_get_product_meta( get_the_ID(), 'input_voltage' ),
				'duty_cycle'    => industrial_welding_get_product_meta( get_the_ID(), 'duty_cycle' ),
				'weight'        => industrial_welding_get_product_meta( get_the_ID(), 'weight' ),
				'best_for'      => industrial_welding_get_product_meta( get_the_ID(), 'best_for' ),
				'summary'       => $summary,
			);
		}

		wp_reset_postdata();
	}
}

$selected_product_ids  = array_map( 'absint', wp_list_pluck( $products_data, 'id' ) );
$selected_count        = count( $selected_product_ids );
$remaining_to_compare  = max( 0, $compare_min_selection - $selected_count );
$has_full_compare_view = $selected_count >= $compare_min_selection;
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.22),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(34,197,94,0.14),_transparent_34%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_minmax(320px,360px)] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Compare Decision Page', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php esc_html_e( 'Compare Machines', 'industrial-welding' ); ?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php esc_html_e( 'Turn a shortlist into a decision. Review the core differences side by side, then return to detail, quote, or checkout with less uncertainty.', 'industrial-welding' ); ?>
				</p>
				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Return To Catalog', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Guided Reset', 'industrial-welding' ); ?></p>
				<h2 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Not sure these are the right machines?', 'industrial-welding' ); ?></h2>
				<p class="mt-3 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'Compare works best when the shortlist is already plausible. If the current picks still feel uncertain, switch to Finder and rebuild the selection from application fit, thickness, skill, and budget.', 'industrial-welding' ); ?></p>
				<div class="mt-5">
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-cyan-400/35 bg-cyan-400/10 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-cyan-100 transition hover:border-cyan-300 hover:text-white font-rajdhani">
						<?php esc_html_e( 'Refine With Finder', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if ( ! empty( $products_data ) ) : ?>
	<section class="bg-slate-900 border-y border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
			<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Selected State', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Shortlisted machines', 'industrial-welding' ); ?></h2>
				</div>
				<div class="rounded-full border border-slate-700 bg-slate-950/70 px-4 py-2 text-sm text-slate-300">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %d: compared machine count */
							_n( '%d machine selected', '%d machines selected', $selected_count, 'industrial-welding' ),
							$selected_count
						)
					);
					?>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
				<?php foreach ( $products_data as $machine ) : ?>
					<?php
					$remaining_ids = array_values(
						array_filter(
							$selected_product_ids,
							static function( $id ) use ( $machine ) {
								return (int) $id !== (int) $machine['id'];
							}
						)
					);
					$remove_url    = industrial_welding_get_compare_url_for_ids( $remaining_ids );
					?>
					<article class="overflow-hidden rounded-[1.6rem] border border-slate-800 bg-slate-950/75 shadow-[0_22px_55px_rgba(2,6,23,0.38)]">
						<?php if ( $machine['thumbnail'] ) : ?>
							<img src="<?php echo esc_url( $machine['thumbnail'] ); ?>" alt="<?php echo esc_attr( $machine['title'] ); ?>" class="h-52 w-full object-cover object-center">
						<?php else : ?>
							<div class="flex h-52 items-center justify-center bg-slate-900 text-slate-600">
								<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
								</svg>
							</div>
						<?php endif; ?>
						<div class="p-5">
							<div class="flex items-start justify-between gap-4">
								<div>
									<p class="text-xs uppercase tracking-[0.2em] text-amber-300 font-semibold"><?php echo esc_html( $machine['category'] ); ?></p>
									<h3 class="mt-2 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $machine['title'] ); ?></h3>
								</div>
								<a href="<?php echo esc_url( $remove_url ); ?>" class="compare-remove-button inline-flex items-center rounded-full border border-slate-700 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.14em] text-slate-300 transition hover:border-rose-300 hover:text-rose-200" data-product-id="<?php echo esc_attr( $machine['id'] ); ?>">
									<?php esc_html_e( 'Remove', 'industrial-welding' ); ?>
								</a>
							</div>

							<?php if ( $machine['summary'] ) : ?>
								<p class="mt-4 text-sm leading-relaxed text-slate-300 line-clamp-3"><?php echo esc_html( $machine['summary'] ); ?></p>
							<?php endif; ?>

							<?php if ( $machine['price_html'] ) : ?>
								<div class="mt-4 text-2xl font-bold text-amber-300 font-rajdhani">
									<?php echo wp_kses_post( $machine['price_html'] ); ?>
								</div>
							<?php endif; ?>

							<div class="mt-5 flex flex-col sm:flex-row gap-3">
								<a href="<?php echo esc_url( $machine['permalink'] ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
									<?php esc_html_e( 'View Machine', 'industrial-welding' ); ?>
								</a>
								<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
									<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php if ( $has_full_compare_view ) : ?>
	<section class="bg-slate-950">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
			<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Side-By-Side View', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Compare the buying-critical specs', 'industrial-welding' ); ?></h2>
				</div>
				<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
					<?php esc_html_e( 'Add More Machines', 'industrial-welding' ); ?>
				</a>
			</div>

			<div class="industrial-compare-table-wrap overflow-x-auto pb-4">
				<table class="industrial-compare-table w-full min-w-[860px] overflow-hidden rounded-[1.8rem] border border-slate-800 bg-slate-900/85">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Compare Point', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<th>
									<div class="flex flex-col items-start gap-3">
										<span class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php echo esc_html( $machine['category'] ); ?></span>
										<span class="text-xl font-bold text-white font-rajdhani leading-tight"><?php echo esc_html( $machine['title'] ); ?></span>
									</div>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?php esc_html_e( 'Summary', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo esc_html( $machine['summary'] ? $machine['summary'] : __( 'Overview pending.', 'industrial-welding' ) ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Amperage', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo esc_html( $machine['amperage'] ? $machine['amperage'] : '—' ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Input Voltage', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo esc_html( $machine['input_voltage'] ? $machine['input_voltage'] : '—' ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Duty Cycle', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo esc_html( $machine['duty_cycle'] ? $machine['duty_cycle'] : '—' ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Weight', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo esc_html( $machine['weight'] ? $machine['weight'] : '—' ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Best For', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo esc_html( $machine['best_for'] ? wp_trim_words( wp_strip_all_tags( $machine['best_for'] ), 18 ) : __( 'Ask for a recommendation based on your application.', 'industrial-welding' ) ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Price', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td><?php echo $machine['price_html'] ? wp_kses_post( $machine['price_html'] ) : esc_html__( 'Quote on request', 'industrial-welding' ); ?></td>
							<?php endforeach; ?>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Next Step', 'industrial-welding' ); ?></th>
							<?php foreach ( $products_data as $machine ) : ?>
								<td>
									<div class="flex flex-col gap-3">
										<a href="<?php echo esc_url( $machine['permalink'] ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
											<?php esc_html_e( 'View Machine', 'industrial-welding' ); ?>
										</a>
										<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-4 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
											<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
										</a>
									</div>
								</td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="mt-10 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(260px,320px)] gap-5">
				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-7">
					<p class="text-xs uppercase tracking-[0.24em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Decision CTA', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Use the shortlist to trigger the real next action', 'industrial-welding' ); ?></h2>
					<p class="mt-4 text-slate-300 leading-relaxed"><?php esc_html_e( 'Once the tradeoffs are clear, either return to a machine detail page for the final purchase path or send a quote request with the shortlist already narrowed down.', 'industrial-welding' ); ?></p>
					<div class="mt-6 flex flex-col sm:flex-row gap-3">
						<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php esc_html_e( 'Quote This Shortlist', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Return To Catalog', 'industrial-welding' ); ?>
						</a>
					</div>
				</div>

				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/72 p-7">
					<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Finder Return Path', 'industrial-welding' ); ?></p>
					<h2 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Use Finder when compare exposes a mismatch', 'industrial-welding' ); ?></h2>
					<p class="mt-3 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'If these machines are too different, too advanced, or too expensive, go back through Finder and let the recommendation logic rebuild the shortlist from the actual requirements.', 'industrial-welding' ); ?></p>
					<div class="mt-5 flex flex-col gap-3">
						<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
							<?php esc_html_e( 'Back To Catalog Filters', 'industrial-welding' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php else : ?>
	<section class="bg-slate-950">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_320px] gap-6">
				<div class="rounded-[1.8rem] border border-dashed border-slate-700 bg-slate-900/72 p-8 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Shortlist Pending', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %d: remaining machine count needed for compare. */
								_n( 'Add %d more machine to unlock the side-by-side compare table', 'Add %d more machines to unlock the side-by-side compare table', $remaining_to_compare, 'industrial-welding' ),
								$remaining_to_compare
							)
						);
						?>
					</h2>
					<p class="mt-4 max-w-3xl text-slate-300 leading-relaxed"><?php esc_html_e( 'Single-machine entries now stay in shortlist mode. Keep this machine, return to the catalog, and add at least one more candidate before using the full compare view.', 'industrial-welding' ); ?></p>
					<div class="mt-6 flex flex-col sm:flex-row gap-3">
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php esc_html_e( 'Add More Machines', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Use Finder', 'industrial-welding' ); ?>
						</a>
					</div>
				</div>

				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Current State', 'industrial-welding' ); ?></p>
					<div class="mt-5 grid grid-cols-2 gap-4">
						<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
							<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold"><?php esc_html_e( 'Selected Now', 'industrial-welding' ); ?></p>
							<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $selected_count ); ?></p>
						</div>
						<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
							<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold"><?php esc_html_e( 'Needed', 'industrial-welding' ); ?></p>
							<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $compare_min_selection ); ?></p>
						</div>
					</div>
					<p class="mt-5 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'The shortlist now persists across the catalog and product detail pages, so you can keep adding machines without losing the current pick.', 'industrial-welding' ); ?></p>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>
<?php else : ?>
	<section class="bg-slate-900 border-y border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
			<div class="rounded-[2rem] border border-dashed border-slate-700 bg-slate-950/75 px-6 py-16 text-center">
				<div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-900 text-slate-500">
					<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
					</svg>
				</div>
				<p class="mt-6 text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold"><?php esc_html_e( 'Empty State', 'industrial-welding' ); ?></p>
				<h2 class="mt-3 text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'No machines selected for compare yet', 'industrial-welding' ); ?></h2>
				<p class="mt-4 max-w-2xl mx-auto text-slate-400 leading-relaxed"><?php esc_html_e( 'Return to the catalog, open the machines that look promising, and tick at least two products to unlock the full side-by-side decision view.', 'industrial-welding' ); ?></p>
				<div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
					</a>
				</div>
				<div class="mt-8 inline-flex rounded-full border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm text-slate-400">
					<?php esc_html_e( 'Finder is the faster route when you are starting from application requirements instead of model names.', 'industrial-welding' ); ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
