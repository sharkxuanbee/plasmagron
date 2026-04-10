<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$catalog_url = industrial_welding_get_catalog_url();
$finder_url  = industrial_welding_get_finder_page_url();

?>

<section class="no-results not-found rounded-[1.8rem] border border-dashed border-slate-700 bg-slate-950/78 px-6 py-14 text-center shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
	<div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-900 text-slate-500">
		<svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
		</svg>
	</div>
	<p class="mt-6 text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold"><?php esc_html_e( 'Nothing Found', 'industrial-welding' ); ?></p>
	<h2 class="mt-3 text-3xl font-bold text-white font-rajdhani">
		<?php
		if ( is_search() ) {
			esc_html_e( 'No matching results were found for this search', 'industrial-welding' );
		} elseif ( is_home() && current_user_can( 'publish_posts' ) ) {
			esc_html_e( 'The post index is ready for the first article', 'industrial-welding' );
		} else {
			esc_html_e( 'There is no content for this route yet', 'industrial-welding' );
		}
		?>
	</h2>
	<div class="mt-4 max-w-2xl mx-auto text-slate-300 leading-relaxed">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: %s: admin new post URL. */
						__( 'Ready to publish the first post? <a href="%s">Create it from the dashboard</a>.', 'industrial-welding' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Try a different keyword, or switch to the machine catalog and Finder when the real goal is product selection rather than content lookup.', 'industrial-welding' ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'Try the search form below, or move to the catalog and Finder if you are looking for the right machine rather than an article or page.', 'industrial-welding' ); ?></p>
		<?php endif; ?>
	</div>
	<div class="mt-8 rounded-2xl border border-slate-800 bg-slate-900/80 p-5 text-left">
		<?php get_search_form(); ?>
	</div>
	<div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
		<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
			<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
		</a>
		<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
			<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
		</a>
	</div>
</section>
