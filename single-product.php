<?php
/**
 * WooCommerce single product template.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$product = wc_get_product( get_the_ID() );

	if ( ! $product ) {
		continue;
	}

	$product_id   = get_the_ID();
	$catalog_url  = industrial_welding_get_catalog_url();
	$contact_url  = industrial_welding_get_contact_page_url();
	$compare_url  = add_query_arg(
		industrial_welding_get_compare_query_key(),
		(string) $product_id,
		industrial_welding_get_compare_page_url()
	);
	$product_meta = array(
		'input_voltage' => industrial_welding_get_product_meta( $product_id, 'input_voltage' ),
		'amperage'      => industrial_welding_get_product_meta( $product_id, 'amperage' ),
		'duty_cycle'    => industrial_welding_get_product_meta( $product_id, 'duty_cycle' ),
		'weight'        => industrial_welding_get_product_meta( $product_id, 'weight' ),
		'best_for'      => industrial_welding_get_product_meta( $product_id, 'best_for' ),
		'download_pdf'  => industrial_welding_get_product_meta( $product_id, 'download_pdf' ),
		'video_url'     => industrial_welding_get_product_meta( $product_id, 'video_url' ),
		'warranty_text' => industrial_welding_get_product_meta( $product_id, 'warranty_text' ),
		'rating'        => industrial_welding_get_product_meta( $product_id, 'rating' ),
		'review_count'  => industrial_welding_get_product_meta( $product_id, 'review_count' ),
	);

	$primary_term        = industrial_welding_get_primary_product_term( $product_id );
	$all_terms           = get_the_terms( $product_id, 'product_cat' );
	$gallery_ids         = $product->get_gallery_image_ids();
	$display_rating      = $product_meta['rating'] ? $product_meta['rating'] : $product->get_average_rating();
	$review_count        = '' !== $product_meta['review_count'] ? (int) $product_meta['review_count'] : (int) $product->get_review_count();
	$summary             = industrial_welding_get_product_summary( $product, 36 );
	$spec_entries        = industrial_welding_get_product_spec_entries( $product_id );
	$decision_specs      = industrial_welding_get_product_spec_entries( $product_id, array( 'amperage', 'input_voltage', 'duty_cycle' ) );
	$video_embed         = $product_meta['video_url'] ? wp_oembed_get( $product_meta['video_url'] ) : '';
	$warranty_text       = $product_meta['warranty_text'] ? $product_meta['warranty_text'] : __( '3-year coverage with technical support for installation, setup, and after-sales troubleshooting.', 'industrial-welding' );
	$sticky_primary_text = $product->is_purchasable() ? __( 'Add to Cart', 'industrial-welding' ) : industrial_welding_get_request_quote_label();
	$overview_title      = $summary ? $summary : __( 'Configured for industrial buyers who need reliable output, straightforward setup, and fast quoting support.', 'industrial-welding' );
	$use_cases           = array();

	if ( $product_meta['best_for'] ) {
		$raw_use_cases = preg_split( '/[\r\n,]+/', wp_strip_all_tags( (string) $product_meta['best_for'] ) );
		$use_cases     = array_values(
			array_filter(
				array_map( 'trim', is_array( $raw_use_cases ) ? $raw_use_cases : array() )
			)
		);
	}

	if ( empty( $use_cases ) ) {
		$use_cases = array(
			sprintf(
				/* translators: %s: machine category */
				__( 'Shortlist this %s for daily production environments that need stable output and repeatable results.', 'industrial-welding' ),
				$primary_term ? $primary_term->name : strtolower( industrial_welding_get_machine_label() )
			),
			__( 'Use it when buyers need a clear spec sheet, simple comparison data, and a direct quote or checkout path.', 'industrial-welding' ),
			__( 'Fit it into procurement workflows where serviceability, training support, and warranty coverage matter.', 'industrial-welding' ),
		);
	}

	$use_cases = array_slice( $use_cases, 0, 3 );

	$quick_value_cards = array(
		array(
			'eyebrow'     => __( 'Output', 'industrial-welding' ),
			'title'       => $product_meta['amperage'] ? $product_meta['amperage'] : __( 'Industrial-ready power', 'industrial-welding' ),
			'description' => $product_meta['duty_cycle'] ? sprintf( __( 'Configured for %s sustained workloads.', 'industrial-welding' ), $product_meta['duty_cycle'] ) : __( 'Balanced for production runs where uptime matters more than brochure specs.', 'industrial-welding' ),
		),
		array(
			'eyebrow'     => __( 'Setup', 'industrial-welding' ),
			'title'       => $product_meta['input_voltage'] ? $product_meta['input_voltage'] : __( 'Factory-configured input', 'industrial-welding' ),
			'description' => __( 'Review the power requirement before purchase to keep installation friction low.', 'industrial-welding' ),
		),
		array(
			'eyebrow'     => __( 'Best Fit', 'industrial-welding' ),
			'title'       => $primary_term ? $primary_term->name : __( 'Industrial applications', 'industrial-welding' ),
			'description' => $product_meta['best_for'] ? wp_trim_words( wp_strip_all_tags( $product_meta['best_for'] ), 18 ) : __( 'A strong fit when buyers need fast comparison, easy quoting, and dependable support coverage.', 'industrial-welding' ),
		),
	);

	$trust_points = array(
		array(
			'title'       => __( 'Coverage', 'industrial-welding' ),
			'detail'      => $warranty_text,
			'kicker'      => __( 'Warranty', 'industrial-welding' ),
		),
		array(
			'title'       => __( 'Decision Support', 'industrial-welding' ),
			'detail'      => __( 'Compare this machine with alternatives, request a tailored quote, or speak with a specialist before placing an order.', 'industrial-welding' ),
			'kicker'      => __( 'Conversion Path', 'industrial-welding' ),
		),
		array(
			'title'       => __( 'Documentation', 'industrial-welding' ),
			'detail'      => $product_meta['download_pdf'] ? __( 'Specification sheet available for procurement review and internal approval.', 'industrial-welding' ) : __( 'Specification sheet can be requested directly from the sales team.', 'industrial-welding' ),
			'kicker'      => __( 'Procurement', 'industrial-welding' ),
		),
	);

	$faq_items = array(
		array(
			'question' => __( 'Can I compare this machine before deciding?', 'industrial-welding' ),
			'answer'   => __( 'Yes. Use the compare action to place this machine next to other shortlisted models and review the key differences before sending an inquiry or checking out.', 'industrial-welding' ),
		),
		array(
			'question' => __( 'How fast can I get specs or documentation?', 'industrial-welding' ),
			'answer'   => $product_meta['download_pdf'] ? __( 'The spec sheet is already available from the downloads section, so your team can review it immediately.', 'industrial-welding' ) : __( 'If the spec sheet is not published yet, request it from the sales team and they can send the documentation directly.', 'industrial-welding' ),
		),
		array(
			'question' => __( 'What support is included after purchase?', 'industrial-welding' ),
			'answer'   => $warranty_text,
		),
	);
	?>

	<section class="relative overflow-hidden bg-slate-950">
		<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.22),_transparent_38%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.16),_transparent_32%)]"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
			<nav class="flex flex-wrap items-center gap-2 text-sm text-slate-400 mb-8" aria-label="<?php esc_attr_e( 'Breadcrumb', 'industrial-welding' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-amber-300 transition-colors"><?php esc_html_e( 'Home', 'industrial-welding' ); ?></a>
				<span>/</span>
				<a href="<?php echo esc_url( $catalog_url ); ?>" class="hover:text-amber-300 transition-colors"><?php echo esc_html( industrial_welding_get_machine_label( true ) ); ?></a>
				<?php if ( $primary_term ) : ?>
					<span>/</span>
					<a href="<?php echo esc_url( get_term_link( $primary_term ) ); ?>" class="hover:text-amber-300 transition-colors"><?php echo esc_html( $primary_term->name ); ?></a>
				<?php endif; ?>
				<span>/</span>
				<span class="text-slate-200"><?php the_title(); ?></span>
			</nav>

			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)] gap-8 xl:gap-10 items-start">
				<div class="space-y-6">
					<div class="flex flex-wrap gap-3">
						<span class="inline-flex items-center rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.26em] text-amber-200 font-rajdhani">
							<?php echo esc_html( $primary_term ? $primary_term->name : industrial_welding_get_machine_label() ); ?>
						</span>
						<?php if ( $display_rating ) : ?>
							<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/70 px-4 py-1.5 text-sm text-slate-200">
								<span class="text-amber-300 font-semibold mr-2"><?php echo esc_html( $display_rating ); ?>/5</span>
								<?php
								echo esc_html(
									$review_count > 0
										? sprintf(
											/* translators: %s: review count */
											_n( '%s review', '%s reviews', $review_count, 'industrial-welding' ),
											number_format_i18n( $review_count )
										)
										: __( 'Verified buyer feedback', 'industrial-welding' )
								);
								?>
							</span>
						<?php else : ?>
							<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/70 px-4 py-1.5 text-sm text-slate-200">
								<?php esc_html_e( 'Factory-tested for B2B buyers', 'industrial-welding' ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="space-y-4">
						<h1 class="max-w-4xl text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
							<?php the_title(); ?>
						</h1>
						<p class="max-w-3xl text-lg md:text-xl leading-relaxed text-slate-300">
							<?php echo esc_html( $overview_title ); ?>
						</p>
					</div>

					<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
						<?php foreach ( $decision_specs as $entry ) : ?>
							<div class="rounded-2xl border border-slate-800 bg-slate-900/80 p-4 shadow-[0_18px_45px_rgba(2,6,23,0.38)]">
								<p class="text-xs uppercase tracking-[0.22em] text-slate-500 font-semibold"><?php echo esc_html( $entry['label'] ); ?></p>
								<p class="mt-2 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $entry['value'] ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="sm:col-span-2 overflow-hidden rounded-[1.8rem] border border-slate-800 bg-slate-900/85 shadow-[0_24px_70px_rgba(2,6,23,0.55)]">
								<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full max-h-[640px] object-cover object-center' ) ); ?>
							</div>
						<?php else : ?>
							<div class="sm:col-span-2 flex min-h-[380px] items-center justify-center rounded-[1.8rem] border border-dashed border-slate-700 bg-slate-900/75">
								<div class="text-center px-6">
									<div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-800 text-amber-300">
										<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
										</svg>
									</div>
									<p class="text-lg text-white font-semibold font-rajdhani"><?php esc_html_e( 'Machine image will be added here', 'industrial-welding' ); ?></p>
									<p class="mt-2 text-sm text-slate-400"><?php esc_html_e( 'The detail template stays stable even when the media library is still being populated.', 'industrial-welding' ); ?></p>
								</div>
							</div>
						<?php endif; ?>

						<?php foreach ( array_slice( $gallery_ids, 0, 3 ) as $image_id ) : ?>
							<div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/80">
								<?php echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'class' => 'w-full h-48 object-cover object-center' ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="lg:sticky lg:top-24 space-y-5">
					<div id="product-buy-panel" class="rounded-[1.8rem] border border-amber-400/15 bg-slate-900/92 p-6 md:p-7 shadow-[0_25px_70px_rgba(15,23,42,0.62)]">
						<div class="flex items-start justify-between gap-4">
							<div>
								<p class="text-xs uppercase tracking-[0.22em] text-amber-300 font-semibold"><?php esc_html_e( 'Primary Conversion', 'industrial-welding' ); ?></p>
								<h2 class="mt-2 text-2xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Ready to move this machine forward?', 'industrial-welding' ); ?></h2>
							</div>
							<?php if ( $product->get_price_html() ) : ?>
								<div class="text-right">
									<p class="text-xs uppercase tracking-[0.18em] text-slate-500"><?php esc_html_e( 'Pricing', 'industrial-welding' ); ?></p>
									<div class="mt-2 text-3xl font-bold text-amber-300 font-rajdhani">
										<?php echo wp_kses_post( $product->get_price_html() ); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<div class="mt-6 space-y-4">
							<?php if ( $summary ) : ?>
								<p class="text-slate-300 leading-relaxed"><?php echo esc_html( $summary ); ?></p>
							<?php endif; ?>

							<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
								<?php foreach ( array_slice( $spec_entries, 0, 4 ) as $entry ) : ?>
									<div class="rounded-2xl border border-slate-800 bg-slate-950/80 p-4">
										<p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold"><?php echo esc_html( $entry['label'] ); ?></p>
										<p class="mt-2 text-lg font-bold text-white font-rajdhani"><?php echo esc_html( $entry['value'] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="space-y-3">
								<div class="industrial-product-purchase">
									<?php if ( $product->is_purchasable() ) : ?>
										<?php woocommerce_template_single_add_to_cart(); ?>
									<?php else : ?>
										<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex w-full items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-lg font-bold tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani uppercase">
											<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
										</a>
									<?php endif; ?>
								</div>
								<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
									<a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 bg-slate-950/70 px-5 py-3 font-semibold text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani uppercase tracking-[0.08em]">
										<?php esc_html_e( 'Compare This Machine', 'industrial-welding' ); ?>
									</a>
									<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 font-semibold text-slate-200 transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani uppercase tracking-[0.08em]">
										<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
									</a>
								</div>
							</div>

							<div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-800 bg-slate-950/75 p-4 text-sm text-slate-300">
								<div class="flex items-center justify-between gap-4">
									<span><?php esc_html_e( 'Sales Line', 'industrial-welding' ); ?></span>
									<a href="<?php echo esc_url( industrial_welding_get_contact_phone_href() ); ?>" class="font-semibold text-amber-300 hover:text-amber-200"><?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?></a>
								</div>
								<div class="flex items-center justify-between gap-4">
									<span><?php esc_html_e( 'Documentation', 'industrial-welding' ); ?></span>
									<span class="font-semibold text-slate-100"><?php echo esc_html( $product_meta['download_pdf'] ? __( 'Available now', 'industrial-welding' ) : __( 'Send on request', 'industrial-welding' ) ); ?></span>
								</div>
								<div class="flex items-center justify-between gap-4">
									<span><?php esc_html_e( 'After-sales support', 'industrial-welding' ); ?></span>
									<span class="font-semibold text-slate-100"><?php esc_html_e( 'Included', 'industrial-welding' ); ?></span>
								</div>
							</div>
						</div>
					</div>

					<div class="rounded-[1.5rem] border border-slate-800 bg-slate-900/80 p-6">
						<p class="text-xs uppercase tracking-[0.22em] text-slate-500 font-semibold"><?php esc_html_e( 'Decision Notes', 'industrial-welding' ); ?></p>
						<ul class="mt-4 space-y-4 text-sm text-slate-300">
							<?php foreach ( $trust_points as $point ) : ?>
								<li class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
									<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php echo esc_html( $point['kicker'] ); ?></p>
									<p class="mt-2 text-lg text-white font-bold font-rajdhani"><?php echo esc_html( $point['title'] ); ?></p>
									<p class="mt-2 leading-relaxed"><?php echo esc_html( $point['detail'] ); ?></p>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-slate-900 border-y border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
				<?php foreach ( $trust_points as $point ) : ?>
					<div class="rounded-2xl border border-slate-800 bg-slate-950/65 p-5">
						<p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold"><?php echo esc_html( $point['kicker'] ); ?></p>
						<h2 class="mt-2 text-xl font-bold text-white font-rajdhani"><?php echo esc_html( $point['title'] ); ?></h2>
						<p class="mt-2 text-sm text-slate-300 leading-relaxed"><?php echo esc_html( $point['detail'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="bg-slate-950">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
			<div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10">
				<div class="max-w-3xl">
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Quick Value', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'What this machine helps your team decide fast', 'industrial-welding' ); ?></h2>
				</div>
				<a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center rounded-full border border-slate-700 px-5 py-3 text-sm font-semibold tracking-[0.12em] uppercase text-slate-200 transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
					<?php esc_html_e( 'Send It To Compare', 'industrial-welding' ); ?>
				</a>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
				<?php foreach ( $quick_value_cards as $card ) : ?>
					<div class="rounded-[1.6rem] border border-slate-800 bg-slate-900/75 p-6 shadow-[0_22px_55px_rgba(2,6,23,0.4)]">
						<p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold"><?php echo esc_html( $card['eyebrow'] ); ?></p>
						<h3 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $card['title'] ); ?></h3>
						<p class="mt-3 text-slate-300 leading-relaxed"><?php echo esc_html( $card['description'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="bg-slate-900 border-y border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] gap-8 items-start">
				<div class="rounded-[1.7rem] border border-slate-800 bg-slate-950/70 p-7">
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Overview', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Operational context and buying notes', 'industrial-welding' ); ?></h2>
					<div class="mt-6 prose prose-invert max-w-none text-slate-300">
						<?php the_content(); ?>
					</div>
				</div>

				<div class="rounded-[1.7rem] border border-slate-800 bg-slate-950/70 p-7">
					<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
						<div>
							<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Specs', 'industrial-welding' ); ?></p>
							<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Specification snapshot', 'industrial-welding' ); ?></h2>
						</div>
						<?php if ( $product_meta['download_pdf'] ) : ?>
							<a href="<?php echo esc_url( $product_meta['download_pdf'] ); ?>" class="inline-flex items-center justify-center rounded-full border border-slate-700 px-5 py-3 text-sm font-semibold tracking-[0.12em] uppercase text-slate-200 transition hover:border-amber-300 hover:text-amber-200 font-rajdhani" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Download Specs', 'industrial-welding' ); ?>
							</a>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $spec_entries ) ) : ?>
						<div class="overflow-hidden rounded-3xl border border-slate-800">
							<table class="industrial-spec-table mb-0">
								<tbody>
									<?php foreach ( $spec_entries as $entry ) : ?>
										<tr>
											<th><?php echo esc_html( $entry['label'] ); ?></th>
											<td><?php echo esc_html( $entry['value'] ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else : ?>
						<div class="rounded-3xl border border-dashed border-slate-700 bg-slate-950/65 p-8 text-center">
							<p class="text-lg font-bold text-white font-rajdhani"><?php esc_html_e( 'Detailed specs are being finalized', 'industrial-welding' ); ?></p>
							<p class="mt-2 text-slate-400"><?php esc_html_e( 'Use the quote path if your buyer needs the full technical sheet before placing an order.', 'industrial-welding' ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $all_terms && ! is_wp_error( $all_terms ) ) : ?>
						<div class="mt-6 flex flex-wrap gap-2">
							<?php foreach ( $all_terms as $term ) : ?>
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900 px-4 py-2 text-sm text-slate-300 transition hover:border-amber-300 hover:text-amber-200">
									<?php echo esc_html( $term->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-slate-950">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] gap-8">
				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/75 p-7">
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Video Demo', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Show the team how this machine looks in action', 'industrial-welding' ); ?></h2>

					<?php if ( $video_embed ) : ?>
						<div class="mt-6 overflow-hidden rounded-[1.5rem] border border-slate-800 bg-slate-950/80 industrial-video-embed">
							<?php echo $video_embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php else : ?>
						<div class="mt-6 rounded-[1.5rem] border border-dashed border-slate-700 bg-slate-950/75 p-8 md:p-10">
							<p class="text-xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Demo video coming soon', 'industrial-welding' ); ?></p>
							<p class="mt-3 text-slate-300 leading-relaxed"><?php esc_html_e( 'The conversion path still stays intact: use the spec sheet, compare workflow, and quote action while the media asset is being prepared.', 'industrial-welding' ); ?></p>
							<div class="mt-6 flex flex-col sm:flex-row gap-3">
								<?php if ( $product_meta['download_pdf'] ) : ?>
									<a href="<?php echo esc_url( $product_meta['download_pdf'] ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani" target="_blank" rel="noopener noreferrer">
										<?php esc_html_e( 'Review Spec Sheet', 'industrial-welding' ); ?>
									</a>
								<?php endif; ?>
								<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
									<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="space-y-8">
					<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/75 p-7">
						<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Use Cases', 'industrial-welding' ); ?></p>
						<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'When this machine is usually shortlisted', 'industrial-welding' ); ?></h2>
						<div class="mt-6 space-y-4">
							<?php foreach ( $use_cases as $index => $use_case ) : ?>
								<div class="flex gap-4 rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
									<div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-amber-400/15 text-amber-300 font-bold font-rajdhani">
										<?php echo esc_html( (string) ( $index + 1 ) ); ?>
									</div>
									<p class="text-slate-300 leading-relaxed"><?php echo esc_html( $use_case ); ?></p>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/75 p-7">
						<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'FAQ', 'industrial-welding' ); ?></p>
						<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Questions buyers usually ask before converting', 'industrial-welding' ); ?></h2>
						<div class="mt-6 space-y-4">
							<?php foreach ( $faq_items as $faq_item ) : ?>
								<details class="group rounded-2xl border border-slate-800 bg-slate-950/70 p-5">
									<summary class="list-none cursor-pointer text-lg font-bold text-white font-rajdhani flex items-center justify-between gap-4">
										<span><?php echo esc_html( $faq_item['question'] ); ?></span>
										<span class="text-amber-300 transition group-open:rotate-45">+</span>
									</summary>
									<p class="mt-4 text-slate-300 leading-relaxed"><?php echo esc_html( $faq_item['answer'] ); ?></p>
								</details>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-slate-900 border-t border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
			<div class="rounded-[2rem] border border-amber-400/15 bg-[linear-gradient(135deg,rgba(15,23,42,0.95),rgba(30,41,59,0.88))] p-8 md:p-10 shadow-[0_28px_80px_rgba(2,6,23,0.55)]">
				<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_auto] gap-8 items-center">
					<div>
						<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Compare CTA', 'industrial-welding' ); ?></p>
						<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Still deciding between models?', 'industrial-welding' ); ?></h2>
						<p class="mt-4 max-w-3xl text-slate-300 leading-relaxed">
							<?php esc_html_e( 'Push this machine into the compare workflow, review alternatives side by side, then return with a shorter and more confident shortlist.', 'industrial-welding' ); ?>
						</p>
					</div>
					<div class="flex flex-col sm:flex-row gap-3">
						<a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php esc_html_e( 'Compare This Machine', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Browse More Machines', 'industrial-welding' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="industrial-sticky-cta">
		<div class="industrial-sticky-cta__inner">
			<div class="industrial-sticky-cta__copy">
				<p class="industrial-sticky-cta__eyebrow"><?php esc_html_e( 'Ready To Convert', 'industrial-welding' ); ?></p>
				<p class="industrial-sticky-cta__title"><?php the_title(); ?></p>
			</div>
			<div class="industrial-sticky-cta__actions">
				<?php if ( $product->is_purchasable() ) : ?>
					<a href="#product-buy-panel" class="industrial-sticky-cta__primary"><?php echo esc_html( $sticky_primary_text ); ?></a>
				<?php else : ?>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="industrial-sticky-cta__primary"><?php echo esc_html( $sticky_primary_text ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $compare_url ); ?>" class="industrial-sticky-cta__secondary"><?php esc_html_e( 'Compare', 'industrial-welding' ); ?></a>
			</div>
		</div>
	</div>

<?php endwhile; ?>

<?php
get_footer();
