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

if ( ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$product_meta = array(
	'amperage'      => industrial_welding_get_product_meta( get_the_ID(), 'amperage' ),
	'input_voltage' => industrial_welding_get_product_meta( get_the_ID(), 'input_voltage' ),
	'duty_cycle'    => industrial_welding_get_product_meta( get_the_ID(), 'duty_cycle' ),
);
$primary_term = industrial_welding_get_primary_product_term( get_the_ID() );
$summary = $product->get_short_description() ? wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 22 ) : get_the_excerpt();
?>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-yellow-500/50 transition-all duration-300 group', $product ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="relative overflow-hidden">
			<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
			<?php if ( $primary_term ) : ?>
				<div class="absolute top-4 left-4">
					<span class="inline-flex items-center px-3 py-1 bg-gray-900/80 backdrop-blur text-yellow-500 text-xs font-bold uppercase tracking-wider rounded font-rajdhani">
						<?php echo esc_html( $primary_term->name ); ?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="p-6">
		<h2 class="text-xl font-bold text-white mb-3 font-rajdhani group-hover:text-yellow-500 transition-colors line-clamp-1">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h2>

		<?php if ( $summary ) : ?>
			<p class="text-gray-400 text-sm mb-4 line-clamp-3">
				<?php echo esc_html( $summary ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $product->get_price_html() ) : ?>
			<p class="text-lg font-bold text-yellow-500 font-rajdhani mb-4">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			</p>
		<?php endif; ?>

		<div class="space-y-2 mb-6">
			<?php if ( $product_meta['amperage'] ) : ?>
				<div class="flex items-center text-sm">
					<span class="text-gray-500 w-28"><?php esc_html_e( 'Amperage', 'industrial-welding' ); ?>:</span>
					<span class="text-gray-300 font-medium"><?php echo esc_html( $product_meta['amperage'] ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ( $product_meta['input_voltage'] ) : ?>
				<div class="flex items-center text-sm">
					<span class="text-gray-500 w-28"><?php esc_html_e( 'Voltage', 'industrial-welding' ); ?>:</span>
					<span class="text-gray-300 font-medium"><?php echo esc_html( $product_meta['input_voltage'] ); ?></span>
				</div>
			<?php endif; ?>
			<?php if ( $product_meta['duty_cycle'] ) : ?>
				<div class="flex items-center text-sm">
					<span class="text-gray-500 w-28"><?php esc_html_e( 'Duty Cycle', 'industrial-welding' ); ?>:</span>
					<span class="text-gray-300 font-medium"><?php echo esc_html( $product_meta['duty_cycle'] ); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<label class="inline-flex items-center mb-4 text-sm text-gray-300 cursor-pointer">
			<input type="checkbox" class="compare-checkbox mr-2 rounded border-gray-600 bg-gray-900 text-yellow-500 focus:ring-yellow-500" value="<?php echo esc_attr( get_the_ID() ); ?>">
			<span><?php esc_html_e( 'Compare', 'industrial-welding' ); ?></span>
		</label>

		<a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-700 hover:bg-yellow-500 hover:text-gray-900 text-white font-semibold rounded transition-colors font-rajdhani tracking-wide group">
			<span><?php esc_html_e( 'View Product', 'industrial-welding' ); ?></span>
			<svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
			</svg>
		</a>
	</div>
</article>
