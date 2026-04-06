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

	$product_meta = array(
		'input_voltage' => industrial_welding_get_product_meta( get_the_ID(), 'input_voltage' ),
		'amperage'      => industrial_welding_get_product_meta( get_the_ID(), 'amperage' ),
		'duty_cycle'    => industrial_welding_get_product_meta( get_the_ID(), 'duty_cycle' ),
		'weight'        => industrial_welding_get_product_meta( get_the_ID(), 'weight' ),
		'best_for'      => industrial_welding_get_product_meta( get_the_ID(), 'best_for' ),
		'download_pdf'  => industrial_welding_get_product_meta( get_the_ID(), 'download_pdf' ),
		'video_url'     => industrial_welding_get_product_meta( get_the_ID(), 'video_url' ),
		'warranty_text' => industrial_welding_get_product_meta( get_the_ID(), 'warranty_text' ),
		'rating'        => industrial_welding_get_product_meta( get_the_ID(), 'rating' ),
		'review_count'  => industrial_welding_get_product_meta( get_the_ID(), 'review_count' ),
	);
	$primary_term = industrial_welding_get_primary_product_term( get_the_ID() );
	$all_terms = get_the_terms( get_the_ID(), 'product_cat' );
	$gallery_ids = $product->get_gallery_image_ids();
	$compare_url = add_query_arg(
		industrial_welding_get_compare_query_key(),
		(string) get_the_ID(),
		industrial_welding_get_compare_page_url()
	);
	$display_rating = $product_meta['rating'] ? $product_meta['rating'] : $product->get_average_rating();
	$review_count = '' !== $product_meta['review_count'] ? $product_meta['review_count'] : $product->get_review_count();
	?>

	<section class="bg-gray-900 py-8 border-b border-gray-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<nav class="flex items-center text-sm text-gray-400" aria-label="<?php esc_attr_e( 'Breadcrumb', 'industrial-welding' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-yellow-500 transition-colors">
					<?php esc_html_e( 'Home', 'industrial-welding' ); ?>
				</a>
				<svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
				</svg>
				<a href="<?php echo esc_url( industrial_welding_get_catalog_url() ); ?>" class="hover:text-yellow-500 transition-colors">
					<?php esc_html_e( 'Machines', 'industrial-welding' ); ?>
				</a>
				<?php if ( $primary_term ) : ?>
					<svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
					</svg>
					<a href="<?php echo esc_url( get_term_link( $primary_term ) ); ?>" class="hover:text-yellow-500 transition-colors">
						<?php echo esc_html( $primary_term->name ); ?>
					</a>
				<?php endif; ?>
				<svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
				</svg>
				<span class="text-gray-300 truncate max-w-xs"><?php the_title(); ?></span>
			</nav>
		</div>
	</section>

	<section class="bg-gray-900 py-12">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
				<div class="relative">
					<div class="sticky top-24">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
								<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-contain max-h-[500px]' ) ); ?>
							</div>
						<?php else : ?>
							<div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 flex items-center justify-center aspect-square">
								<svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
								</svg>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $gallery_ids ) ) : ?>
							<div class="mt-4 grid grid-cols-4 gap-4">
								<?php foreach ( array_slice( $gallery_ids, 0, 4 ) as $image_id ) : ?>
									<a href="<?php echo esc_url( wp_get_attachment_url( $image_id ) ); ?>" class="bg-gray-800 rounded overflow-hidden border border-gray-700 hover:border-yellow-500 transition-colors">
										<?php echo wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'class' => 'w-full h-20 object-cover' ) ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div>
					<?php if ( $all_terms && ! is_wp_error( $all_terms ) ) : ?>
						<div class="mb-4">
							<?php foreach ( $all_terms as $term ) : ?>
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="inline-flex items-center px-3 py-1 bg-yellow-500/20 text-yellow-500 text-sm font-semibold uppercase tracking-wider rounded font-rajdhani hover:bg-yellow-500/30 transition-colors">
									<?php echo esc_html( $term->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<h1 class="text-3xl md:text-4xl font-bold text-white mb-4 font-rajdhani leading-tight">
						<?php the_title(); ?>
					</h1>

					<?php if ( $display_rating || $review_count ) : ?>
						<div class="flex items-center gap-3 mb-4 text-sm text-gray-300">
							<?php if ( $display_rating ) : ?>
								<span class="inline-flex items-center text-yellow-500 font-semibold">
									<?php echo esc_html( $display_rating ); ?>/5
								</span>
							<?php endif; ?>
							<?php if ( $review_count ) : ?>
								<span><?php echo esc_html( sprintf( _n( '%s review', '%s reviews', (int) $review_count, 'industrial-welding' ), number_format_i18n( (int) $review_count ) ) ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $product->get_price_html() ) : ?>
						<p class="text-2xl font-bold text-yellow-500 font-rajdhani mb-6">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $product->get_short_description() ) : ?>
						<div class="text-lg text-gray-300 mb-8 border-l-4 border-yellow-500 pl-4">
							<?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?>
						</div>
					<?php endif; ?>

					<div class="bg-gray-800 rounded-lg p-6 mb-8 border border-gray-700">
						<h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">
							<?php esc_html_e( 'Key Specifications', 'industrial-welding' ); ?>
						</h3>
						<div class="grid grid-cols-2 gap-4">
							<?php foreach ( industrial_welding_get_product_spec_labels() as $key => $label ) : ?>
								<?php if ( ! empty( $product_meta[ $key ] ) ) : ?>
									<div class="bg-gray-900 rounded p-4">
										<span class="text-xs text-gray-500 uppercase tracking-wider"><?php echo esc_html( $label ); ?></span>
										<p class="text-xl font-bold text-white font-rajdhani mt-1"><?php echo esc_html( $product_meta[ $key ] ); ?></p>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="flex flex-col sm:flex-row gap-4 mb-8">
						<div class="industrial-product-purchase flex-1">
							<?php if ( $product->is_purchasable() ) : ?>
								<?php woocommerce_template_single_add_to_cart(); ?>
							<?php else : ?>
								<a href="<?php echo esc_url( industrial_welding_get_contact_page_url() ); ?>" class="inline-flex items-center justify-center w-full px-8 py-4 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold text-lg rounded transition-colors font-rajdhani tracking-wide">
									<?php esc_html_e( 'Request a Quote', 'industrial-welding' ); ?>
								</a>
							<?php endif; ?>
						</div>
						<a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 hover:border-yellow-500 text-white font-bold text-lg rounded transition-colors font-rajdhani tracking-wide flex-1">
							<?php esc_html_e( 'Compare', 'industrial-welding' ); ?>
						</a>
					</div>

					<div class="flex items-center space-x-6 text-sm text-gray-400">
						<div class="flex items-center">
							<svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
							</svg>
							<?php echo esc_html( $product_meta['warranty_text'] ? $product_meta['warranty_text'] : __( '3-Year Warranty', 'industrial-welding' ) ); ?>
						</div>
						<div class="flex items-center">
							<svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
							</svg>
							<?php esc_html_e( 'Secure Checkout', 'industrial-welding' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-gray-800 py-12 border-t border-gray-700">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="machine-tabs" data-tabs>
				<div class="border-b border-gray-700 mb-8">
					<nav class="flex space-x-8" aria-label="<?php esc_attr_e( 'Product Details Tabs', 'industrial-welding' ); ?>">
						<button class="machine-tab active text-yellow-500 border-b-2 border-yellow-500 py-4 px-2 text-sm font-semibold transition-colors font-rajdhani tracking-wide" data-tab="description">
							<?php esc_html_e( 'Description', 'industrial-welding' ); ?>
						</button>
						<button class="machine-tab text-gray-400 hover:text-white border-b-2 border-transparent py-4 px-2 text-sm font-semibold transition-colors font-rajdhani tracking-wide" data-tab="specs">
							<?php esc_html_e( 'Full Specifications', 'industrial-welding' ); ?>
						</button>
						<button class="machine-tab text-gray-400 hover:text-white border-b-2 border-transparent py-4 px-2 text-sm font-semibold transition-colors font-rajdhani tracking-wide" data-tab="downloads">
							<?php esc_html_e( 'Downloads', 'industrial-welding' ); ?>
						</button>
					</nav>
				</div>

				<div class="tab-content" data-tab-content="description">
					<div class="prose prose-invert max-w-none text-gray-300">
						<?php the_content(); ?>
					</div>

					<?php if ( $product_meta['best_for'] ) : ?>
						<div class="mt-8 p-6 bg-gray-900 rounded-lg border border-gray-700">
							<h3 class="text-lg font-semibold text-white mb-3 font-rajdhani">
								<?php esc_html_e( 'Best For', 'industrial-welding' ); ?>
							</h3>
							<p class="text-gray-300"><?php echo wp_kses_post( nl2br( $product_meta['best_for'] ) ); ?></p>
						</div>
					<?php endif; ?>
				</div>

				<div class="tab-content hidden" data-tab-content="specs">
					<div class="bg-gray-900 rounded-lg overflow-hidden border border-gray-700">
						<table class="w-full">
							<thead>
								<tr class="bg-gray-800 border-b border-gray-700">
									<th class="text-left py-4 px-6 text-sm font-semibold text-yellow-500 uppercase tracking-wider"><?php esc_html_e( 'Specification', 'industrial-welding' ); ?></th>
									<th class="text-left py-4 px-6 text-sm font-semibold text-yellow-500 uppercase tracking-wider"><?php esc_html_e( 'Value', 'industrial-welding' ); ?></th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-700">
								<?php foreach ( industrial_welding_get_product_spec_labels() as $key => $label ) : ?>
									<?php if ( ! empty( $product_meta[ $key ] ) ) : ?>
										<tr class="hover:bg-gray-800/50 transition-colors">
											<td class="py-4 px-6 text-sm text-gray-400"><?php echo esc_html( $label ); ?></td>
											<td class="py-4 px-6 text-sm text-white font-medium"><?php echo esc_html( $product_meta[ $key ] ); ?></td>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<?php if ( $all_terms && ! is_wp_error( $all_terms ) ) : ?>
						<div class="mt-6 flex flex-wrap gap-2">
							<span class="text-sm text-gray-400"><?php esc_html_e( 'Categories:', 'industrial-welding' ); ?></span>
							<?php foreach ( $all_terms as $term ) : ?>
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="inline-flex items-center px-3 py-1 bg-gray-700 text-gray-300 text-sm rounded hover:bg-gray-600 transition-colors">
									<?php echo esc_html( $term->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="tab-content hidden" data-tab-content="downloads">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<?php if ( $product_meta['download_pdf'] ) : ?>
							<a href="<?php echo esc_url( $product_meta['download_pdf'] ); ?>" class="flex items-center p-6 bg-gray-900 rounded-lg border border-gray-700 hover:border-yellow-500 transition-colors group" target="_blank" rel="noopener noreferrer">
								<div class="flex-shrink-0 w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center mr-4 group-hover:bg-yellow-500/30 transition-colors">
									<svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
									</svg>
								</div>
								<div>
									<h4 class="text-white font-semibold group-hover:text-yellow-500 transition-colors"><?php esc_html_e( 'Product Specification Sheet', 'industrial-welding' ); ?></h4>
									<p class="text-sm text-gray-400"><?php esc_html_e( 'PDF Document', 'industrial-welding' ); ?></p>
								</div>
								<svg class="w-5 h-5 ml-auto text-gray-400 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
								</svg>
							</a>
						<?php else : ?>
							<div class="col-span-2 flex flex-col items-center justify-center p-12 bg-gray-900 rounded-lg border border-gray-700 border-dashed">
								<svg class="w-12 h-12 text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
								</svg>
								<p class="text-gray-400 text-center mb-4"><?php esc_html_e( 'Product documentation will be available soon.', 'industrial-welding' ); ?></p>
								<a href="<?php echo esc_url( industrial_welding_get_contact_page_url() ); ?>" class="text-yellow-500 hover:text-yellow-400 font-semibold transition-colors">
									<?php esc_html_e( 'Contact us for documentation', 'industrial-welding' ); ?>
								</a>
							</div>
						<?php endif; ?>

						<a href="<?php echo esc_url( industrial_welding_get_contact_page_url() ); ?>" class="flex items-center p-6 bg-gray-900 rounded-lg border border-gray-700 hover:border-yellow-500 transition-colors group">
							<div class="flex-shrink-0 w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center mr-4 group-hover:bg-yellow-500/30 transition-colors">
								<svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
								</svg>
							</div>
							<div>
								<h4 class="text-white font-semibold group-hover:text-yellow-500 transition-colors"><?php esc_html_e( 'Request Service Manual', 'industrial-welding' ); ?></h4>
								<p class="text-sm text-gray-400"><?php esc_html_e( 'Get the complete service documentation', 'industrial-welding' ); ?></p>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-gray-900 py-16">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-12">
				<h2 class="text-2xl md:text-3xl font-bold text-white font-rajdhani">
					<?php esc_html_e( 'Need Help Choosing?', 'industrial-welding' ); ?>
				</h2>
				<p class="text-gray-400 mt-2">
					<?php esc_html_e( 'Compare our machines side-by-side to find the perfect fit for your shop.', 'industrial-welding' ); ?>
				</p>
			</div>
			<div class="text-center">
				<a href="<?php echo esc_url( industrial_welding_get_compare_page_url() ); ?>" class="inline-flex items-center px-8 py-4 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded transition-colors font-rajdhani tracking-wide border border-gray-700">
					<?php esc_html_e( 'Compare All Machines', 'industrial-welding' ); ?>
					<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
					</svg>
				</a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
