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

$current_term          = is_tax( 'product_cat' ) ? get_queried_object() : null;
$archive_title         = $current_term ? single_term_title( '', false ) : industrial_welding_get_machine_label( true );
$archive_description   = __( 'Browse compare-ready industrial machines, shortlist the most relevant models, and move to detail, quote, or purchase with fewer clicks.', 'industrial-welding' );
$catalog_url           = industrial_welding_get_catalog_url();
$compare_url           = industrial_welding_get_compare_page_url();
$contact_url           = industrial_welding_get_contact_page_url();
$selected_compare_ids  = industrial_welding_get_requested_compare_ids();
$selected_count        = count( $selected_compare_ids );
$product_counts        = wp_count_posts( 'product' );
$product_total         = $product_counts ? (int) $product_counts->publish : 0;
$compare_min_selection = 2;

if ( $current_term instanceof WP_Term && ! empty( $current_term->description ) ) {
	$archive_description = $current_term->description;
} elseif ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
	$shop_page = get_post( wc_get_page_id( 'shop' ) );

	if ( $shop_page && ! empty( $shop_page->post_excerpt ) ) {
		$archive_description = $shop_page->post_excerpt;
	}
}

$product_categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.22),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.16),_transparent_32%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_minmax(320px,360px)] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Machine Catalog', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php echo esc_html( $archive_title ); ?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php echo esc_html( wp_strip_all_tags( $archive_description ) ); ?>
				</p>
				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Browse All Machines', 'industrial-welding' ); ?>
					</a>
					<button
						id="compare-selected-button"
						type="button"
						data-label="<?php esc_attr_e( 'Compare Selected', 'industrial-welding' ); ?>"
						data-base-url="<?php echo esc_url( $compare_url ); ?>"
						class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani <?php echo $selected_count >= $compare_min_selection ? '' : 'opacity-50 cursor-not-allowed'; ?>"
						<?php disabled( $selected_count < $compare_min_selection ); ?>
					>
						<?php esc_html_e( 'Compare Selected', 'industrial-welding' ); ?>
					</button>
				</div>
			</div>

			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Catalog Rules', 'industrial-welding' ); ?></p>
				<div class="mt-5 grid grid-cols-1 gap-3">
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php esc_html_e( 'Step 1', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-sm text-slate-300"><?php esc_html_e( 'Open the machine detail page when you need specs, trust notes, downloads, and the purchase or quote action.', 'industrial-welding' ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php esc_html_e( 'Step 2', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-sm text-slate-300"><?php esc_html_e( 'Tick at least two machines to unlock the compare decision page and remove guesswork from the shortlist.', 'industrial-welding' ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
						<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php esc_html_e( 'Step 3', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-sm text-slate-300"><?php esc_html_e( 'Move from compare to quote or checkout only after the core fit is clear.', 'industrial-welding' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4">
			<div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
				<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php esc_html_e( 'Published Machines', 'industrial-welding' ); ?></p>
				<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( number_format_i18n( $product_total ) ); ?></p>
			</div>
			<div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
				<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php esc_html_e( 'Categories', 'industrial-welding' ); ?></p>
				<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( is_array( $product_categories ) ? count( $product_categories ) : 0 ); ?></p>
			</div>
			<div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
				<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php esc_html_e( 'Selected For Compare', 'industrial-welding' ); ?></p>
				<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $selected_count ); ?></p>
			</div>
			<div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5">
				<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php esc_html_e( 'Minimum To Compare', 'industrial-welding' ); ?></p>
				<p class="mt-2 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $compare_min_selection ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
		<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
			<div class="flex flex-wrap gap-3">
				<a
					href="<?php echo esc_url( $catalog_url ); ?>"
					class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo ! $current_term ? 'border-amber-300 bg-amber-300/10 text-amber-200' : 'border-slate-700 bg-slate-950/70 text-slate-300 hover:border-amber-300 hover:text-amber-200'; ?>"
				>
					<?php esc_html_e( 'All Machines', 'industrial-welding' ); ?>
				</a>
				<?php if ( $product_categories && ! is_wp_error( $product_categories ) ) : ?>
					<?php foreach ( $product_categories as $term ) : ?>
						<?php $is_current = $current_term instanceof WP_Term && (int) $current_term->term_id === (int) $term->term_id; ?>
						<a
							href="<?php echo esc_url( get_term_link( $term ) ); ?>"
							class="inline-flex items-center rounded-full border px-4 py-2 text-sm transition <?php echo $is_current ? 'border-amber-300 bg-amber-300/10 text-amber-200' : 'border-slate-700 bg-slate-950/70 text-slate-300 hover:border-amber-300 hover:text-amber-200'; ?>"
						>
							<?php echo esc_html( $term->name ); ?>
							<span class="ml-2 rounded-full bg-slate-800 px-2 py-0.5 text-xs text-slate-400"><?php echo esc_html( number_format_i18n( $term->count ) ); ?></span>
						</a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="rounded-full border border-slate-700 bg-slate-950/70 px-4 py-2 text-sm text-slate-300">
				<?php
				echo esc_html(
					$selected_count > 0
						? sprintf(
							/* translators: %d: selected compare count */
							_n( '%d machine selected for compare', '%d machines selected for compare', $selected_count, 'industrial-welding' ),
							$selected_count
						)
						: __( 'No machines selected yet. Tick cards to build a shortlist.', 'industrial-welding' )
				);
				?>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<?php if ( have_posts() ) : ?>
			<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Decision-Ready Cards', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Open details when you need certainty, compare when you need tradeoffs', 'industrial-welding' ); ?></h2>
				</div>
				<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
					<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
				</a>
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
				<h2 class="mt-6 text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'No Machines Found', 'industrial-welding' ); ?></h2>
				<p class="mt-3 max-w-2xl mx-auto text-slate-400 leading-relaxed"><?php esc_html_e( 'This catalog view does not have published machines yet. Return to the main catalog or request a quote so the team can recommend the right model directly.', 'industrial-welding' ); ?></p>
				<div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Return To Catalog', 'industrial-welding' ); ?>
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
