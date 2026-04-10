<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url = industrial_welding_get_catalog_url();
$finder_url  = industrial_welding_get_finder_page_url();
$contact_url = industrial_welding_get_contact_page_url();
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.2),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(239,68,68,0.12),_transparent_30%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
		<div class="max-w-4xl">
			<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( '404', 'industrial-welding' ); ?></p>
			<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none"><?php esc_html_e( 'That route does not exist in the current site structure', 'industrial-welding' ); ?></h1>
			<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed"><?php esc_html_e( 'Use the catalog, Finder, or the contact path below to get back into a working decision flow.', 'industrial-welding' ); ?></p>
			<div class="mt-8 flex flex-col sm:flex-row gap-3">
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
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
		<div class="rounded-[1.8rem] border border-slate-800 bg-slate-950/78 p-8 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
			<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Search Instead', 'industrial-welding' ); ?></p>
			<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Search the current site content', 'industrial-welding' ); ?></h2>
			<div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900/80 p-5">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
