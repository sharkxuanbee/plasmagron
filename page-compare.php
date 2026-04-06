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

$compare_args = industrial_welding_get_featured_products_query_args( 3 );
$compare_query_key = industrial_welding_get_compare_query_key();
$requested_ids = array();

if ( isset( $_GET[ $compare_query_key ] ) ) {
	$requested_ids = array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_GET[ $compare_query_key ] ) ) ) );
} elseif ( isset( $_GET['machines'] ) ) {
	$requested_ids = array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_GET['machines'] ) ) ) ) );
}

if ( ! empty( $requested_ids ) ) {
	$compare_args['post__in'] = $requested_ids;
	$compare_args['orderby']  = 'post__in';
}

$compare_query = new WP_Query( $compare_args );
$products_data = array();

if ( $compare_query->have_posts() ) {
	while ( $compare_query->have_posts() ) {
		$compare_query->the_post();
		$product = industrial_welding_is_woocommerce_active() ? wc_get_product( get_the_ID() ) : null;

		$products_data[] = array(
			'id'            => get_the_ID(),
			'title'         => get_the_title(),
			'permalink'     => get_permalink(),
			'thumbnail'     => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
			'price_html'    => $product ? $product->get_price_html() : '',
			'amperage'      => industrial_welding_get_product_meta( get_the_ID(), 'amperage' ),
			'input_voltage' => industrial_welding_get_product_meta( get_the_ID(), 'input_voltage' ),
			'duty_cycle'    => industrial_welding_get_product_meta( get_the_ID(), 'duty_cycle' ),
			'weight'        => industrial_welding_get_product_meta( get_the_ID(), 'weight' ),
			'best_for'      => industrial_welding_get_product_meta( get_the_ID(), 'best_for' ),
			'excerpt'       => get_the_excerpt(),
		);
	}
	wp_reset_postdata();
}
?>

<section class="bg-gray-900 py-16 border-b border-gray-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center">
			<h1 class="text-4xl md:text-5xl font-bold text-white mb-4 font-rajdhani">
				<?php esc_html_e( 'Compare Machines', 'industrial-welding' ); ?>
			</h1>
			<p class="text-gray-400 max-w-2xl mx-auto">
				<?php esc_html_e( 'Compare specifications side-by-side to find the perfect machine for your industrial needs.', 'industrial-welding' ); ?>
			</p>
		</div>
	</div>
</section>

<section class="bg-gray-900 py-12">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<?php if ( ! empty( $products_data ) ) : ?>
			<div class="flex justify-end mb-8">
				<a href="<?php echo esc_url( industrial_welding_get_catalog_url() ); ?>" class="inline-flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white font-semibold rounded transition-colors border border-gray-700">
					<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
					<?php esc_html_e( 'Add More Machines', 'industrial-welding' ); ?>
				</a>
			</div>

			<div class="overflow-x-auto pb-4">
				<table class="w-full min-w-[800px] bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
					<thead>
						<tr class="bg-gray-900 border-b border-gray-700">
							<th class="text-left py-6 px-6 w-48">
								<span class="text-sm font-semibold text-gray-400 uppercase tracking-wider"><?php esc_html_e( 'Specification', 'industrial-welding' ); ?></span>
							</th>
							<?php foreach ( $products_data as $machine ) : ?>
								<th class="text-center py-6 px-4 min-w-[280px]">
									<div class="flex flex-col items-center">
										<?php if ( $machine['thumbnail'] ) : ?>
											<img src="<?php echo esc_url( $machine['thumbnail'] ); ?>"
												alt="<?php echo esc_attr( $machine['title'] ); ?>"
												class="w-full h-40 object-cover rounded-lg mb-4 border border-gray-700">
										<?php else : ?>
											<div class="w-full h-40 bg-gray-700 rounded-lg mb-4 flex items-center justify-center">
												<svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
												</svg>
											</div>
										<?php endif; ?>
										<h3 class="text-lg font-bold text-white font-rajdhani mb-2"><?php echo esc_html( $machine['title'] ); ?></h3>
										<a href="<?php echo esc_url( $machine['permalink'] ); ?>" class="text-sm text-yellow-500 hover:text-yellow-400 transition-colors">
											<?php esc_html_e( 'View Details', 'industrial-welding' ); ?>
										</a>
									</div>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-700">
						<tr class="hover:bg-gray-700/30 transition-colors">
							<td class="py-5 px-6">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Amperage', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-lg font-bold text-white font-rajdhani">
										<?php echo esc_html( $machine['amperage'] ) ?: '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="hover:bg-gray-700/30 transition-colors bg-gray-800/50">
							<td class="py-5 px-6">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Input Voltage', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-lg font-bold text-white font-rajdhani">
										<?php echo esc_html( $machine['input_voltage'] ) ?: '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="hover:bg-gray-700/30 transition-colors">
							<td class="py-5 px-6">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Duty Cycle', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-lg font-bold text-white font-rajdhani">
										<?php echo esc_html( $machine['duty_cycle'] ) ?: '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="hover:bg-gray-700/30 transition-colors bg-gray-800/50">
							<td class="py-5 px-6">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Weight', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-lg font-bold text-white font-rajdhani">
										<?php echo esc_html( $machine['weight'] ) ?: '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="hover:bg-gray-700/30 transition-colors">
							<td class="py-5 px-6">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Price', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-lg font-bold text-yellow-500 font-rajdhani">
										<?php echo $machine['price_html'] ? wp_kses_post( $machine['price_html'] ) : '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="hover:bg-gray-700/30 transition-colors bg-gray-800/50">
							<td class="py-5 px-6 align-top">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Best For', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-sm text-gray-300">
										<?php echo esc_html( $machine['best_for'] ) ?: '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="hover:bg-gray-700/30 transition-colors">
							<td class="py-5 px-6 align-top">
								<span class="text-sm font-semibold text-gray-300"><?php esc_html_e( 'Description', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-5 px-6 text-center">
									<span class="text-sm text-gray-300">
										<?php echo esc_html( $machine['excerpt'] ) ?: '<span class="text-gray-500">—</span>'; ?>
									</span>
								</td>
							<?php endforeach; ?>
						</tr>
						<tr class="border-t-2 border-yellow-500">
							<td class="py-6 px-6">
								<span class="text-sm font-bold text-yellow-500 uppercase tracking-wider"><?php esc_html_e( 'Action', 'industrial-welding' ); ?></span>
							</td>
							<?php foreach ( $products_data as $machine ) : ?>
								<td class="py-6 px-6 text-center">
									<a href="<?php echo esc_url( $machine['permalink'] ); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold rounded transition-colors font-rajdhani tracking-wide">
										<?php esc_html_e( 'View Product', 'industrial-welding' ); ?>
									</a>
								</td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="mt-12 bg-gray-800 rounded-lg p-8 border border-gray-700">
				<h2 class="text-2xl font-bold text-white mb-6 font-rajdhani text-center">
					<?php esc_html_e( 'Need Help Deciding?', 'industrial-welding' ); ?>
				</h2>
				<p class="text-gray-400 text-center mb-8 max-w-2xl mx-auto">
					<?php esc_html_e( 'Our industrial equipment specialists can help you choose the perfect machine for your specific applications. Contact us today for a personalized recommendation.', 'industrial-welding' ); ?>
				</p>
				<div class="flex flex-col sm:flex-row justify-center gap-4">
					<a href="<?php echo esc_url( industrial_welding_get_contact_page_url() ); ?>" class="inline-flex items-center justify-center px-8 py-4 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold rounded transition-colors font-rajdhani tracking-wide">
						<?php esc_html_e( 'Contact an Expert', 'industrial-welding' ); ?>
					</a>
					<a href="tel:+18005551234" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 hover:border-yellow-500 text-white font-bold rounded transition-colors font-rajdhani tracking-wide">
						<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
						</svg>
						<?php esc_html_e( '1-800-555-1234', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

		<?php else : ?>
			<div class="text-center py-16">
				<svg class="w-20 h-20 text-gray-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
				</svg>
				<h2 class="text-2xl font-bold text-white mb-4 font-rajdhani">
					<?php esc_html_e( 'No Machines to Compare', 'industrial-welding' ); ?>
				</h2>
				<p class="text-gray-400 mb-8 max-w-md mx-auto">
					<?php esc_html_e( 'Browse our product catalog and select machines to compare them side-by-side.', 'industrial-welding' ); ?>
				</p>
				<a href="<?php echo esc_url( industrial_welding_get_catalog_url() ); ?>" class="inline-flex items-center px-8 py-4 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold rounded transition-colors font-rajdhani tracking-wide">
					<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
					</svg>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
