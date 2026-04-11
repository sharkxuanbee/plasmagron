<?php
/**
 * The front page template.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url       = industrial_welding_get_catalog_url();
$finder_url        = industrial_welding_get_finder_page_url();
$compare_url       = industrial_welding_get_compare_page_url();
$contact_url       = industrial_welding_get_contact_page_url();
$featured_query    = new WP_Query( industrial_welding_get_featured_products_query_args( 4 ) );
$featured_machines = array();

if ( $featured_query->have_posts() ) {
	while ( $featured_query->have_posts() ) {
		$featured_query->the_post();
		$product = industrial_welding_is_woocommerce_active() ? wc_get_product( get_the_ID() ) : null;
		$term    = industrial_welding_get_primary_product_term( get_the_ID() );

		$featured_machines[] = array(
			'id'         => get_the_ID(),
			'title'      => get_the_title(),
			'permalink'  => get_permalink(),
			'thumbnail'  => get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ),
			'price_html' => $product ? $product->get_price_html() : '',
			'summary'    => $product ? industrial_welding_get_product_summary( $product, 20 ) : get_the_excerpt(),
			'category'   => $term ? $term->name : industrial_welding_get_machine_label(),
			'specs'      => industrial_welding_get_product_spec_entries( get_the_ID(), array( 'amperage', 'input_voltage', 'duty_cycle' ) ),
		);
	}

	wp_reset_postdata();
}

$product_categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 3,
	)
);

$usage_scene_terms = taxonomy_exists( 'usage_scene' )
	? get_terms(
		array(
			'taxonomy'   => 'usage_scene',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 3,
		)
	)
	: array();

$category_entry_url         = $catalog_url;
$category_entry_title       = __( 'Open machine-type landings', 'industrial-welding' );
$category_entry_description = __( 'Use category pages when the buyer already knows the machine family and only needs a cleaner starting point than the full catalog.', 'industrial-welding' );
$category_entry_label       = __( 'Browse Categories', 'industrial-welding' );

if ( $product_categories && ! is_wp_error( $product_categories ) ) {
	$primary_category_link = get_term_link( $product_categories[0] );

	if ( ! is_wp_error( $primary_category_link ) ) {
		$category_entry_url         = $primary_category_link;
		$category_entry_title       = sprintf(
			/* translators: %s: product category name */
			__( 'Start with %s', 'industrial-welding' ),
			$product_categories[0]->name
		);
		$category_entry_description = __( 'Send buyers straight into a machine-type landing page, then let filters and compare refine the shortlist from there.', 'industrial-welding' );
		$category_entry_label       = sprintf(
			/* translators: %s: product category name */
			__( 'Open %s', 'industrial-welding' ),
			$product_categories[0]->name
		);
	}
}

$quick_entries = array(
	array(
		'eyebrow'     => __( 'Finder', 'industrial-welding' ),
		'title'       => __( 'Use guided selection first', 'industrial-welding' ),
		'description' => __( 'Start with Finder when the buyer knows the job requirements but does not know which machine should make the shortlist yet.', 'industrial-welding' ),
		'url'         => $finder_url,
		'label'       => __( 'Start Finder', 'industrial-welding' ),
	),
	array(
		'eyebrow'     => __( 'Category', 'industrial-welding' ),
		'title'       => $category_entry_title,
		'description' => $category_entry_description,
		'url'         => $category_entry_url,
		'label'       => $category_entry_label,
	),
	array(
		'eyebrow'     => __( 'Catalog', 'industrial-welding' ),
		'title'       => __( 'Browse all machines', 'industrial-welding' ),
		'description' => __( 'Open the full product list when the buyer needs a wide view of the lineup before applying filters or compare.', 'industrial-welding' ),
		'url'         => $catalog_url,
		'label'       => __( 'Open Catalog', 'industrial-welding' ),
	),
	array(
		'eyebrow'     => __( 'Compare', 'industrial-welding' ),
		'title'       => __( 'Build a shortlist', 'industrial-welding' ),
		'description' => __( 'Use compare when the buyer already has a few candidate machines and wants the differences laid out clearly.', 'industrial-welding' ),
		'url'         => $compare_url,
		'label'       => __( 'Go To Compare', 'industrial-welding' ),
	),
);

$how_to_choose_steps = array(
	array(
		'step'        => '01',
		'title'       => __( 'Choose Finder or a category landing', 'industrial-welding' ),
		'description' => __( 'Finder is for requirement-led buyers. Category landings are for buyers who already know the machine family and need a faster entry point.', 'industrial-welding' ),
	),
	array(
		'step'        => '02',
		'title'       => __( 'Use filters and compare to tighten the shortlist', 'industrial-welding' ),
		'description' => __( 'Usage, skill, budget, amperage, power input, and duty cycle should narrow the field before anyone asks for a quote.', 'industrial-welding' ),
	),
	array(
		'step'        => '03',
		'title'       => __( 'Move into detail, quote, or checkout only after confidence is high', 'industrial-welding' ),
		'description' => __( 'The conversion step should happen after the shortlist is credible, not while the buyer is still guessing which machine fits.', 'industrial-welding' ),
	),
);

$trust_blocks = array(
	array(
		'title'       => __( 'Industrial-first detail pages', 'industrial-welding' ),
		'description' => __( 'Every featured machine can route buyers into specs, compare, quote, or checkout without dead ends.', 'industrial-welding' ),
	),
	array(
		'title'       => __( 'Compare-ready catalog flow', 'industrial-welding' ),
		'description' => __( 'The listing experience is optimized for shortlist building first, not for hiding machines behind complex filters.', 'industrial-welding' ),
	),
	array(
		'title'       => __( 'Support-backed conversion path', 'industrial-welding' ),
		'description' => __( 'Warranty, technical support, and documentation stay visible throughout the decision chain.', 'industrial-welding' ),
	),
);

$use_case_entries = array();

if ( ! empty( $usage_scene_terms ) && ! is_wp_error( $usage_scene_terms ) ) {
	foreach ( $usage_scene_terms as $usage_term ) {
		$usage_term_link = get_term_link( $usage_term );

		if ( is_wp_error( $usage_term_link ) ) {
			continue;
		}

		$use_case_entries[] = array(
			'title'       => $usage_term->name,
			'description' => $usage_term->description ? wp_trim_words( wp_strip_all_tags( $usage_term->description ), 18 ) : __( 'Open the matching machine options for this usage scenario.', 'industrial-welding' ),
			'url'         => $usage_term_link,
			'label'       => __( 'Open Scenario', 'industrial-welding' ),
		);
	}
}

if ( empty( $use_case_entries ) ) {
	$use_case_entries = array(
		array(
			'title'       => __( 'Fabrication Shops', 'industrial-welding' ),
			'description' => __( 'Browse machines suited to repeatable shop throughput and easy comparison across output ranges.', 'industrial-welding' ),
			'url'         => $catalog_url,
			'label'       => __( 'Browse Machines', 'industrial-welding' ),
		),
		array(
			'title'       => __( 'Procurement Teams', 'industrial-welding' ),
			'description' => __( 'Jump into compare when internal approval depends on a clean side-by-side summary of the shortlist.', 'industrial-welding' ),
			'url'         => $compare_url,
			'label'       => __( 'Compare Options', 'industrial-welding' ),
		),
		array(
			'title'       => __( 'Bulk Buyers', 'industrial-welding' ),
			'description' => __( 'Go directly to the quote path when pricing, availability, or documentation needs a tailored response.', 'industrial-welding' ),
			'url'         => $contact_url,
			'label'       => industrial_welding_get_request_quote_label(),
		),
	);
}
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.24),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(34,197,94,0.14),_transparent_30%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 xl:py-24">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1.1fr)_minmax(340px,0.9fr)] gap-8 xl:gap-10 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-5"><?php esc_html_e( 'About Plasmargon', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-7xl font-bold text-white font-rajdhani leading-none">
					<?php esc_html_e( 'Reliable welding and plasma cutting equipment for real-world metalworking', 'industrial-welding' ); ?>
				</h1>
				<p class="mt-6 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php echo esc_html( industrial_welding_get_brand_intro() ); ?>
				</p>

				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Start Finder', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					</a>
				</div>

				<div class="mt-10 flex flex-wrap gap-3">
					<?php if ( $product_categories && ! is_wp_error( $product_categories ) ) : ?>
						<?php foreach ( $product_categories as $term ) : ?>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/75 px-4 py-2 text-sm text-slate-300 transition hover:border-amber-300 hover:text-amber-200">
								<?php echo esc_html( $term->name ); ?>
							</a>
						<?php endforeach; ?>
					<?php else : ?>
						<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/75 px-4 py-2 text-sm text-slate-300"><?php esc_html_e( 'Compare-ready catalog', 'industrial-welding' ); ?></span>
						<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/75 px-4 py-2 text-sm text-slate-300"><?php esc_html_e( 'Quote or checkout path', 'industrial-welding' ); ?></span>
						<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/75 px-4 py-2 text-sm text-slate-300"><?php esc_html_e( 'Support-first buying flow', 'industrial-welding' ); ?></span>
					<?php endif; ?>
				</div>
			</div>

			<div class="space-y-5">
				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Core Route', 'industrial-welding' ); ?></p>
					<div class="mt-5 grid grid-cols-1 gap-3">
						<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
							<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold">01</p>
							<p class="mt-2 text-lg font-bold text-white font-rajdhani"><?php esc_html_e( 'Choose Finder or a category', 'industrial-welding' ); ?></p>
							<p class="mt-2 text-sm text-slate-300"><?php esc_html_e( 'Open the guided route when the fit is unclear, or start from a machine-type landing when the family is already known.', 'industrial-welding' ); ?></p>
						</div>
						<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
							<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold">02</p>
							<p class="mt-2 text-lg font-bold text-white font-rajdhani"><?php esc_html_e( 'Filter and compare the shortlist', 'industrial-welding' ); ?></p>
							<p class="mt-2 text-sm text-slate-300"><?php esc_html_e( 'Move into compare as soon as there are at least two realistic candidates and use filters to remove weak fits.', 'industrial-welding' ); ?></p>
						</div>
						<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
							<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold">03</p>
							<p class="mt-2 text-lg font-bold text-white font-rajdhani"><?php esc_html_e( 'Convert from confidence', 'industrial-welding' ); ?></p>
							<p class="mt-2 text-sm text-slate-300"><?php esc_html_e( 'Finish on the detail page where the machine can move to quote or purchase cleanly after the selection logic is already clear.', 'industrial-welding' ); ?></p>
						</div>
					</div>
				</div>

				<?php if ( ! empty( $featured_machines ) ) : ?>
					<?php $hero_machine = $featured_machines[0]; ?>
					<div class="overflow-hidden rounded-[1.8rem] border border-amber-400/20 bg-slate-900/80 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
						<?php if ( $hero_machine['thumbnail'] ) : ?>
							<img src="<?php echo esc_url( $hero_machine['thumbnail'] ); ?>" alt="<?php echo esc_attr( $hero_machine['title'] ); ?>" class="h-56 w-full object-cover object-center">
						<?php endif; ?>
						<div class="p-6">
							<p class="text-xs uppercase tracking-[0.22em] text-amber-300 font-semibold"><?php esc_html_e( 'Featured Machine', 'industrial-welding' ); ?></p>
							<h2 class="mt-3 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( $hero_machine['title'] ); ?></h2>
							<p class="mt-3 text-slate-300 leading-relaxed"><?php echo esc_html( $hero_machine['summary'] ); ?></p>
							<a href="<?php echo esc_url( $hero_machine['permalink'] ); ?>" class="mt-5 inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
								<?php esc_html_e( 'View Machine', 'industrial-welding' ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
			<div>
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Quick Entry', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Choose the fastest entry point for the buyer stage', 'industrial-welding' ); ?></h2>
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
			<?php foreach ( $quick_entries as $entry ) : ?>
				<a href="<?php echo esc_url( $entry['url'] ); ?>" class="group rounded-[1.7rem] border border-slate-800 bg-slate-950/75 p-6 shadow-[0_20px_50px_rgba(2,6,23,0.35)] transition hover:-translate-y-1 hover:border-amber-300/40">
					<p class="text-xs uppercase tracking-[0.2em] text-amber-300 font-semibold"><?php echo esc_html( $entry['eyebrow'] ); ?></p>
					<h3 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $entry['title'] ); ?></h3>
					<p class="mt-3 text-slate-300 leading-relaxed"><?php echo esc_html( $entry['description'] ); ?></p>
					<span class="mt-6 inline-flex items-center text-sm font-bold uppercase tracking-[0.08em] text-white transition group-hover:text-amber-200 font-rajdhani">
						<?php echo esc_html( $entry['label'] ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
			<div>
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Best Sellers', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Machines already positioned for conversion', 'industrial-welding' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
				<?php esc_html_e( 'View Full Catalog', 'industrial-welding' ); ?>
			</a>
		</div>

		<?php if ( ! empty( $featured_machines ) ) : ?>
			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
				<?php foreach ( $featured_machines as $machine ) : ?>
					<article class="group flex h-full flex-col overflow-hidden rounded-[1.6rem] border border-slate-800 bg-slate-900/75 shadow-[0_20px_50px_rgba(2,6,23,0.35)] transition hover:-translate-y-1 hover:border-amber-300/40">
						<?php if ( $machine['thumbnail'] ) : ?>
							<img src="<?php echo esc_url( $machine['thumbnail'] ); ?>" alt="<?php echo esc_attr( $machine['title'] ); ?>" class="h-56 w-full object-cover object-center transition duration-500 group-hover:scale-[1.03]">
						<?php endif; ?>
						<div class="flex flex-1 flex-col p-5">
							<p class="text-xs uppercase tracking-[0.2em] text-amber-300 font-semibold"><?php echo esc_html( $machine['category'] ); ?></p>
							<h3 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $machine['title'] ); ?></h3>
							<p class="mt-3 text-sm text-slate-300 leading-relaxed line-clamp-3"><?php echo esc_html( $machine['summary'] ); ?></p>
							<div class="mt-4 flex flex-wrap gap-2">
								<?php foreach ( array_slice( $machine['specs'], 0, 2 ) as $entry ) : ?>
									<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/70 px-3 py-2 text-xs text-slate-200">
										<?php echo esc_html( $entry['value'] ); ?>
									</span>
								<?php endforeach; ?>
							</div>
							<?php if ( $machine['price_html'] ) : ?>
								<div class="mt-4 text-2xl font-bold text-amber-300 font-rajdhani">
									<?php echo wp_kses_post( $machine['price_html'] ); ?>
								</div>
							<?php endif; ?>
							<a href="<?php echo esc_url( $machine['permalink'] ); ?>" class="mt-5 inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
								<?php esc_html_e( 'View Machine', 'industrial-welding' ); ?>
							</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="rounded-[1.8rem] border border-dashed border-slate-700 bg-slate-900/60 p-10 text-center">
				<h3 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Featured machines will appear here', 'industrial-welding' ); ?></h3>
				<p class="mt-3 text-slate-400"><?php esc_html_e( 'Once products are marked as featured, this section becomes the homepage shortcut into the strongest conversion candidates.', 'industrial-welding' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
			<div>
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'How To Choose', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'A simple decision framework for industrial buyers', 'industrial-welding' ); ?></h2>
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
			<?php foreach ( $how_to_choose_steps as $step ) : ?>
				<div class="rounded-[1.7rem] border border-slate-800 bg-slate-950/75 p-6 shadow-[0_20px_50px_rgba(2,6,23,0.35)]">
					<p class="text-5xl font-bold text-slate-700 font-rajdhani"><?php echo esc_html( $step['step'] ); ?></p>
					<h3 class="mt-4 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="mt-3 text-slate-300 leading-relaxed"><?php echo esc_html( $step['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
			<div>
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Use Case Entry', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Route by buying context, not just by model name', 'industrial-welding' ); ?></h2>
			</div>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
			<?php foreach ( $use_case_entries as $entry ) : ?>
				<a href="<?php echo esc_url( $entry['url'] ); ?>" class="group rounded-[1.7rem] border border-slate-800 bg-slate-900/75 p-6 shadow-[0_20px_50px_rgba(2,6,23,0.35)] transition hover:-translate-y-1 hover:border-amber-300/40">
					<h3 class="text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $entry['title'] ); ?></h3>
					<p class="mt-3 text-slate-300 leading-relaxed"><?php echo esc_html( $entry['description'] ); ?></p>
					<span class="mt-6 inline-flex items-center text-sm font-bold uppercase tracking-[0.08em] text-white transition group-hover:text-amber-200 font-rajdhani">
						<?php echo esc_html( $entry['label'] ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="text-center mb-8">
			<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Trust Section', 'industrial-welding' ); ?></p>
			<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'The conversion chain is built to stay consistent page to page', 'industrial-welding' ); ?></h2>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
			<?php foreach ( $trust_blocks as $block ) : ?>
				<div class="rounded-[1.7rem] border border-slate-800 bg-slate-950/75 p-6 shadow-[0_20px_50px_rgba(2,6,23,0.35)]">
					<h3 class="text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $block['title'] ); ?></h3>
					<p class="mt-3 text-slate-300 leading-relaxed"><?php echo esc_html( $block['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="rounded-[2rem] border border-amber-400/18 bg-[linear-gradient(135deg,rgba(15,23,42,0.96),rgba(30,41,59,0.88))] p-8 md:p-10 shadow-[0_28px_80px_rgba(2,6,23,0.5)]">
			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_auto] gap-8 items-center">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Strong CTA', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Need a faster route to the right machine?', 'industrial-welding' ); ?></h2>
					<p class="mt-4 max-w-3xl text-slate-300 leading-relaxed"><?php esc_html_e( 'Send the sales team your application, power setup, and quantity target. They can point you to the right machines, send the documentation, and help finalize the shortlist.', 'industrial-welding' ); ?></p>
				</div>
				<div class="flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
