<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url = industrial_welding_get_catalog_url();
$contact_url = industrial_welding_get_contact_page_url();
?>

<?php
while ( have_posts() ) :
	the_post();
	$page_summary = industrial_welding_get_post_summary( get_the_ID(), 28 );
	?>
	<section class="relative overflow-hidden bg-slate-950">
		<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.2),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.14),_transparent_30%)]"></div>
		<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
			<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
				<div class="max-w-4xl">
					<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Site Page', 'industrial-welding' ); ?></p>
					<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none"><?php the_title(); ?></h1>
					<?php if ( $page_summary ) : ?>
						<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed"><?php echo esc_html( $page_summary ); ?></p>
					<?php endif; ?>
					<div class="mt-8 flex flex-col sm:flex-row gap-3">
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
						</a>
					</div>
				</div>

				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Fallback Route', 'industrial-welding' ); ?></p>
					<p class="mt-4 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'This default page template now stays inside the industrial theme system, so unassigned pages do not fall back to the old starter-theme skeleton.', 'industrial-welding' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="bg-slate-900 border-y border-slate-800">
		<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
			<?php get_template_part( 'template-parts/content', 'page' ); ?>
		</div>
	</section>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<section class="bg-slate-950">
			<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
				<?php comments_template(); ?>
			</div>
		</section>
	<?php endif; ?>
<?php endwhile; ?>

<?php
get_footer();
