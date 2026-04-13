<?php
/**
 * Cart page template.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url  = industrial_welding_get_catalog_url();
$checkout_url = industrial_welding_get_checkout_page_url();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<section class="relative overflow-hidden bg-slate-950">
		<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.2),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.14),_transparent_30%)]"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
			<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
				<div class="max-w-4xl">
					<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Cart', 'industrial-welding' ); ?></p>
					<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none"><?php esc_html_e( 'Review the machine order before checkout', 'industrial-welding' ); ?></h1>
					<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed"><?php esc_html_e( 'Adjust quantities, remove weak-fit items, then move into checkout with a cleaner purchase decision.', 'industrial-welding' ); ?></p>
					<div class="mt-8 flex flex-col sm:flex-row gap-3">
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Continue Shopping', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $checkout_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php esc_html_e( 'Proceed To Checkout', 'industrial-welding' ); ?>
						</a>
					</div>
				</div>

				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Purchase Flow', 'industrial-welding' ); ?></p>
					<p class="mt-4 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'This page is now the handoff between product selection and payment, rather than a generic WordPress page shell.', 'industrial-welding' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-slate-900 border-y border-slate-800">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
			<div class="industrial-woocommerce-shell industrial-woocommerce-shell--cart overflow-hidden rounded-[1.8rem] border border-slate-800 bg-slate-950/78 p-6 md:p-8 xl:p-10 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<?php the_content(); ?>
			</div>
		</div>
	</section>
<?php endwhile; ?>

<?php
get_footer();
