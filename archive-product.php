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

$current_term = is_tax( 'product_cat' ) ? get_queried_object() : null;
$archive_title = $current_term ? single_term_title( '', false ) : __( 'Machines', 'industrial-welding' );
$archive_description = __( 'Professional-grade welding machines and plasma cutters for every industrial application. Explore our complete lineup below.', 'industrial-welding' );
$catalog_url = industrial_welding_get_catalog_url();
$compare_url = industrial_welding_get_compare_page_url();
$product_counts = wp_count_posts( 'product' );
$product_total = $product_counts ? (int) $product_counts->publish : 0;

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

<section class="bg-gray-900 py-16 border-b border-gray-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="text-center">
			<h1 class="text-4xl md:text-5xl font-bold text-white mb-4 font-rajdhani">
				<?php echo esc_html( $archive_title ); ?>
			</h1>
			<p class="text-gray-400 max-w-2xl mx-auto">
				<?php echo esc_html( wp_strip_all_tags( $archive_description ) ); ?>
			</p>
		</div>
	</div>
</section>

<section class="bg-gray-900 py-16">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<?php if ( have_posts() ) : ?>
			<div class="flex flex-col lg:flex-row gap-8 mb-12">
				<aside class="lg:w-64 flex-shrink-0">
					<div class="bg-gray-800 rounded-lg p-6 border border-gray-700 sticky top-24">
						<h3 class="text-lg font-semibold text-white mb-4 font-rajdhani">
							<?php esc_html_e( 'Filter by Category', 'industrial-welding' ); ?>
						</h3>
						<ul class="space-y-2">
							<li>
								<a href="<?php echo esc_url( $catalog_url ); ?>"
									class="flex items-center justify-between px-3 py-2 text-gray-300 hover:text-yellow-500 hover:bg-gray-700 rounded transition-colors <?php echo ! $current_term ? 'text-yellow-500 bg-gray-700' : ''; ?>">
									<span><?php esc_html_e( 'All Machines', 'industrial-welding' ); ?></span>
									<span class="text-xs bg-gray-600 px-2 py-1 rounded">
										<?php echo esc_html( $product_total ); ?>
									</span>
								</a>
							</li>
							<?php if ( $product_categories && ! is_wp_error( $product_categories ) ) : ?>
								<?php foreach ( $product_categories as $term ) : ?>
									<?php $is_current = $current_term instanceof WP_Term && (int) $current_term->term_id === (int) $term->term_id; ?>
									<li>
										<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
											class="flex items-center justify-between px-3 py-2 text-gray-300 hover:text-yellow-500 hover:bg-gray-700 rounded transition-colors <?php echo $is_current ? 'text-yellow-500 bg-gray-700' : ''; ?>">
											<span><?php echo esc_html( $term->name ); ?></span>
											<span class="text-xs bg-gray-600 px-2 py-1 rounded">
												<?php echo esc_html( $term->count ); ?>
											</span>
										</a>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
					</div>
				</aside>

				<div class="flex-grow">
					<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
						<p class="text-sm text-gray-400">
							<?php esc_html_e( 'Select two or more products to compare them side-by-side.', 'industrial-welding' ); ?>
						</p>
						<button
							id="compare-selected-button"
							type="button"
							data-label="<?php esc_attr_e( 'Compare Selected', 'industrial-welding' ); ?>"
							data-base-url="<?php echo esc_url( $compare_url ); ?>"
							class="inline-flex items-center justify-center px-6 py-3 bg-yellow-500 text-gray-900 font-bold rounded transition-colors font-rajdhani tracking-wide opacity-50 cursor-not-allowed"
							disabled
						>
							<?php esc_html_e( 'Compare Selected', 'industrial-welding' ); ?>
						</button>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
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
									'<span class="inline-flex items-center px-3 py-2 bg-gray-800 text-gray-300 hover:text-yellow-500 rounded transition-colors">%s</span>',
									esc_html__( 'Previous', 'industrial-welding' )
								),
								'next_text'          => sprintf(
									'<span class="inline-flex items-center px-3 py-2 bg-gray-800 text-gray-300 hover:text-yellow-500 rounded transition-colors">%s</span>',
									esc_html__( 'Next', 'industrial-welding' )
								),
								'screen_reader_text' => __( 'Pagination', 'industrial-welding' ),
							)
						);
						?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="text-center py-16">
				<svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
				</svg>
				<h2 class="text-2xl font-bold text-white mb-2 font-rajdhani">
					<?php esc_html_e( 'No Products Found', 'industrial-welding' ); ?>
				</h2>
				<p class="text-gray-400 mb-8">
					<?php esc_html_e( 'No products are currently available. Please check back soon.', 'industrial-welding' ); ?>
				</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold rounded transition-colors">
					<?php esc_html_e( 'Return to Homepage', 'industrial-welding' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
