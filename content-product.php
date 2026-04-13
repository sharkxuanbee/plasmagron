<?php
/**
 * Product card template used on the catalog archive.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! ( $product instanceof WC_Product ) ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$product_id              = get_the_ID();
$primary_term            = industrial_welding_get_primary_product_term( $product_id );
$summary                 = industrial_welding_get_product_summary( $product, 22 );
$spec_entries            = industrial_welding_get_product_spec_entries( $product_id, array( 'amperage', 'input_voltage', 'duty_cycle' ) );
$best_for                = industrial_welding_get_product_meta( $product_id, 'best_for' );
$selected_compare_ids    = industrial_welding_get_requested_compare_ids();
$is_selected_for_compare = in_array( $product_id, $selected_compare_ids, true );
$compare_now_url         = industrial_welding_get_compare_url_for_ids( array( $product_id ) );
$shortlist_add_label     = __( 'Add To Shortlist', 'industrial-welding' );
$shortlist_selected_label = __( 'Shortlisted', 'industrial-welding' );
$add_to_cart_label       = $product->add_to_cart_text();
$can_add_to_cart         = $product->is_purchasable() && $product->is_in_stock();
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(15,23,42,0.84))] shadow-[0_24px_55px_rgba(2,6,23,0.42)] transition duration-300 hover:-translate-y-1 hover:border-amber-300/40', $product ); ?>>
	<div class="relative overflow-hidden border-b border-slate-800">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array( 'class' => 'h-64 w-full object-cover object-center transition duration-500 group-hover:scale-[1.03]' ) ); ?>
		<?php else : ?>
			<div class="flex h-64 items-center justify-center bg-slate-950/85 text-slate-600">
				<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
				</svg>
			</div>
		<?php endif; ?>

		<div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-4">
			<?php if ( $primary_term ) : ?>
				<span class="inline-flex items-center rounded-full border border-amber-400/30 bg-slate-950/75 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200 font-rajdhani">
					<?php echo esc_html( $primary_term->name ); ?>
				</span>
			<?php endif; ?>
			<?php if ( $product->get_price_html() ) : ?>
				<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/80 px-3 py-1.5 text-sm font-bold text-white font-rajdhani">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>

	<div class="flex flex-1 flex-col p-6">
		<div class="flex-1">
			<h2 class="text-2xl font-bold text-white font-rajdhani leading-tight">
				<a href="<?php the_permalink(); ?>" class="transition group-hover:text-amber-200">
					<?php the_title(); ?>
				</a>
			</h2>

			<?php if ( $summary ) : ?>
				<p class="mt-4 text-slate-300 leading-relaxed line-clamp-3">
					<?php echo esc_html( $summary ); ?>
				</p>
			<?php endif; ?>

			<div class="mt-5 flex flex-wrap gap-2">
				<?php foreach ( $spec_entries as $entry ) : ?>
					<div class="rounded-full border border-slate-700 bg-slate-950/70 px-3 py-2 text-xs text-slate-200">
						<span class="text-slate-500 uppercase tracking-[0.18em] mr-2"><?php echo esc_html( $entry['label'] ); ?></span>
						<span class="font-semibold text-white"><?php echo esc_html( $entry['value'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="mt-5 rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
				<p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold"><?php esc_html_e( 'Decision Note', 'industrial-welding' ); ?></p>
				<p class="mt-2 text-sm text-slate-300 leading-relaxed">
					<?php echo esc_html( $best_for ? wp_trim_words( wp_strip_all_tags( $best_for ), 16 ) : __( 'Open the detail page for the full conversion path, downloads, trust notes, and cart or purchase action.', 'industrial-welding' ) ); ?>
				</p>
			</div>
		</div>

		<div class="mt-6 space-y-4">
			<?php if ( $can_add_to_cart ) : ?>
				<a
					href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
					data-quantity="1"
					data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
					data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
					aria-label="<?php echo esc_attr( wp_strip_all_tags( $add_to_cart_label ) ); ?>"
					rel="nofollow"
					class="inline-flex w-full items-center justify-center rounded-xl bg-cyan-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-cyan-300 font-rajdhani add_to_cart_button product_type_<?php echo esc_attr( $product->get_type() ); ?> <?php echo $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''; ?>"
				>
					<?php echo esc_html( $add_to_cart_label ); ?>
				</a>
			<?php endif; ?>

			<label class="flex items-center justify-between gap-4 rounded-2xl border border-slate-800 bg-slate-950/75 px-4 py-3 text-sm text-slate-200 cursor-pointer">
				<span class="font-semibold"><?php esc_html_e( 'Add to compare shortlist', 'industrial-welding' ); ?></span>
				<input
					type="checkbox"
					class="compare-checkbox h-5 w-5 rounded border-slate-600 bg-slate-900 text-amber-400 focus:ring-amber-400"
					value="<?php echo esc_attr( $product_id ); ?>"
					<?php checked( $is_selected_for_compare ); ?>
				>
			</label>

			<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
					<?php esc_html_e( 'View Machine', 'industrial-welding' ); ?>
				</a>
				<a
					href="<?php echo esc_url( $compare_now_url ); ?>"
					class="compare-shortlist-toggle inline-flex items-center justify-center rounded-xl border px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] transition font-rajdhani <?php echo $is_selected_for_compare ? 'border-amber-300 text-amber-200 bg-amber-300/10' : 'border-slate-700 text-white hover:border-cyan-300 hover:text-cyan-200'; ?>"
					data-product-id="<?php echo esc_attr( $product_id ); ?>"
					data-fallback-href="<?php echo esc_url( $compare_now_url ); ?>"
					data-label-add="<?php echo esc_attr( $shortlist_add_label ); ?>"
					data-label-selected="<?php echo esc_attr( $shortlist_selected_label ); ?>"
				>
					<?php echo esc_html( $is_selected_for_compare ? $shortlist_selected_label : $shortlist_add_label ); ?>
				</a>
			</div>
		</div>
	</div>
</article>
