<?php
/**
 * The footer for the Industrial Welding theme.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_navigation_items = industrial_welding_get_navigation_items();
$contact_url             = industrial_welding_get_contact_page_url();
$catalog_url             = industrial_welding_get_catalog_url();
$finder_url              = industrial_welding_get_finder_page_url();
$compare_url             = industrial_welding_get_compare_page_url();
?>

	</main>

	<footer id="colophon" class="site-footer border-t border-slate-800 bg-slate-950">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.1fr)_repeat(3,minmax(0,0.63fr))] gap-10">
				<div class="max-w-md">
					<div class="flex items-center gap-3 mb-6">
						<span class="text-2xl font-bold text-amber-300 font-rajdhani tracking-[0.18em]">PLASMA</span>
						<span class="text-2xl font-bold text-white font-rajdhani tracking-[0.18em]">GRON</span>
					</div>
					<p class="text-slate-300 leading-relaxed mb-6">
						<?php esc_html_e( 'A conversion-focused industrial machine theme built around one clear route: use Finder or category landings, narrow the shortlist, compare the tradeoffs, then quote or buy with confidence.', 'industrial-welding' ); ?>
					</p>
					<div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
						<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
							<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $contact_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
						</a>
					</div>
				</div>

				<div>
					<h2 class="text-lg font-bold text-white font-rajdhani uppercase tracking-[0.14em] mb-5"><?php esc_html_e( 'Explore', 'industrial-welding' ); ?></h2>
					<ul class="space-y-3">
						<?php foreach ( $footer_navigation_items as $item ) : ?>
							<li>
								<a href="<?php echo esc_url( $item['url'] ); ?>" class="text-slate-400 transition hover:text-amber-200">
									<?php echo esc_html( $item['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div>
					<h2 class="text-lg font-bold text-white font-rajdhani uppercase tracking-[0.14em] mb-5"><?php esc_html_e( 'Decision Paths', 'industrial-welding' ); ?></h2>
					<ul class="space-y-3 text-slate-400">
						<li><a href="<?php echo esc_url( $finder_url ); ?>" class="transition hover:text-amber-200"><?php esc_html_e( 'Welder Finder', 'industrial-welding' ); ?></a></li>
						<li><a href="<?php echo esc_url( $catalog_url ); ?>" class="transition hover:text-amber-200"><?php esc_html_e( 'Catalog Overview', 'industrial-welding' ); ?></a></li>
						<li><a href="<?php echo esc_url( $compare_url ); ?>" class="transition hover:text-amber-200"><?php esc_html_e( 'Compare Shortlist', 'industrial-welding' ); ?></a></li>
						<li><a href="<?php echo esc_url( $catalog_url ); ?>" class="transition hover:text-amber-200"><?php esc_html_e( 'Filterable Category Paths', 'industrial-welding' ); ?></a></li>
						<li><a href="<?php echo esc_url( $contact_url ); ?>" class="transition hover:text-amber-200"><?php esc_html_e( 'Documentation Or Bulk Quote', 'industrial-welding' ); ?></a></li>
					</ul>
				</div>

				<div>
					<h2 class="text-lg font-bold text-white font-rajdhani uppercase tracking-[0.14em] mb-5"><?php esc_html_e( 'Contact', 'industrial-welding' ); ?></h2>
					<ul class="space-y-4 text-slate-400">
						<li>
							<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold mb-1"><?php esc_html_e( 'Sales Line', 'industrial-welding' ); ?></p>
							<a href="<?php echo esc_url( industrial_welding_get_contact_phone_href() ); ?>" class="transition hover:text-amber-200"><?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?></a>
						</li>
						<li>
							<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold mb-1"><?php esc_html_e( 'Email', 'industrial-welding' ); ?></p>
							<a href="mailto:<?php echo esc_attr( industrial_welding_get_contact_email() ); ?>" class="transition hover:text-amber-200"><?php echo esc_html( industrial_welding_get_contact_email() ); ?></a>
						</li>
						<li>
							<p class="text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold mb-1"><?php esc_html_e( 'Support Window', 'industrial-welding' ); ?></p>
							<span><?php esc_html_e( 'Quote, spec sheet, and after-sales questions handled through the same contact route.', 'industrial-welding' ); ?></span>
						</li>
					</ul>
				</div>
			</div>

			<div class="mt-12 border-t border-slate-800 pt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
				<p class="text-sm text-slate-500">
					&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'industrial-welding' ); ?>
				</p>
				<div class="flex flex-wrap gap-5 text-sm text-slate-500">
					<a href="<?php echo esc_url( $contact_url ); ?>" class="transition hover:text-slate-300"><?php esc_html_e( 'Privacy', 'industrial-welding' ); ?></a>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="transition hover:text-slate-300"><?php esc_html_e( 'Terms', 'industrial-welding' ); ?></a>
					<a href="<?php echo esc_url( $contact_url ); ?>" class="transition hover:text-slate-300"><?php esc_html_e( 'Support', 'industrial-welding' ); ?></a>
				</div>
			</div>
		</div>
	</footer>

</div>

<?php wp_footer(); ?>

</body>
</html>
