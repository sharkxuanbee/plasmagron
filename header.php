<?php
/**
 * The header for the Industrial Welding theme.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$navigation_items = industrial_welding_get_navigation_items();
$finder_url       = industrial_welding_get_finder_page_url();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-slate-950 text-slate-100 font-roboto industrial-site' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
	<a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-amber-400 focus:text-slate-950 focus:px-4 focus:py-2" href="#primary">
		<?php esc_html_e( 'Skip to content', 'industrial-welding' ); ?>
	</a>

	<header id="masthead" class="site-header sticky top-0 z-50 border-b border-slate-800/90 bg-slate-950/90 backdrop-blur-xl">
		<div class="hidden xl:block border-b border-slate-800/80 bg-slate-950/90">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
				<div class="flex items-center justify-between gap-4 text-xs uppercase tracking-[0.18em] text-slate-400 font-semibold">
					<div class="flex items-center gap-4">
						<span><?php esc_html_e( 'Finder', 'industrial-welding' ); ?></span>
						<span class="text-slate-700">/</span>
						<span><?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?></span>
						<span class="text-slate-700">/</span>
						<span><?php esc_html_e( 'Compare Shortlists', 'industrial-welding' ); ?></span>
						<span class="text-slate-700">/</span>
						<span><?php esc_html_e( 'Quote Or Checkout', 'industrial-welding' ); ?></span>
					</div>
					<a href="<?php echo esc_url( industrial_welding_get_contact_phone_href() ); ?>" class="transition hover:text-amber-200">
						<?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?>
					</a>
				</div>
			</div>
		</div>

		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex items-center justify-between gap-4 py-4 lg:py-5">
				<div class="flex items-center">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center text-3xl font-bold font-rajdhani uppercase tracking-[0.18em] leading-none" aria-label="<?php echo esc_attr( industrial_welding_get_brand_name() ); ?>">
							<span class="text-amber-300">PLASMA</span><span class="text-white">RGON</span>
						</a>
					<?php endif; ?>
				</div>

				<nav id="site-navigation" class="hidden lg:flex items-center gap-8">
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'container'      => false,
								'fallback_cb'    => false,
								'items_wrap'     => '<ul class="flex items-center gap-8">%3$s</ul>',
								'link_before'    => '<span class="text-sm uppercase tracking-[0.12em] text-slate-300 transition hover:text-amber-200 font-semibold">',
								'link_after'     => '</span>',
							)
						);
						?>
					<?php else : ?>
						<ul class="flex items-center gap-8">
							<?php foreach ( $navigation_items as $item ) : ?>
								<li>
									<a href="<?php echo esc_url( $item['url'] ); ?>" class="text-sm uppercase tracking-[0.12em] text-slate-300 transition hover:text-amber-200 font-semibold">
										<?php echo esc_html( $item['label'] ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</nav>

				<div class="hidden lg:flex items-center gap-3">
					<a href="<?php echo esc_url( industrial_welding_get_catalog_url() ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_machine_label( true ) ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>

				<button id="mobile-menu-toggle" class="inline-flex h-12 w-12 items-center justify-center rounded-xl border border-slate-800 bg-slate-900 text-slate-200 transition hover:border-amber-300 hover:text-amber-200 lg:hidden" aria-controls="mobile-menu" aria-expanded="false">
					<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'industrial-welding' ); ?></span>
					<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
					</svg>
				</button>
			</div>
		</div>

		<div id="mobile-menu" class="hidden border-t border-slate-800 bg-slate-950/95 lg:hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 space-y-5">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'mobile-primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
							'items_wrap'     => '<ul class="space-y-3">%3$s</ul>',
							'link_before'    => '<span class="block rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-sm uppercase tracking-[0.12em] text-slate-200 transition hover:border-amber-300 hover:text-amber-200 font-semibold">',
							'link_after'     => '</span>',
						)
					);
					?>
				<?php else : ?>
					<ul class="space-y-3">
						<?php foreach ( $navigation_items as $item ) : ?>
							<li>
								<a href="<?php echo esc_url( $item['url'] ); ?>" class="block rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-sm uppercase tracking-[0.12em] text-slate-200 transition hover:border-amber-300 hover:text-amber-200 font-semibold">
									<?php echo esc_html( $item['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
					<a href="<?php echo esc_url( industrial_welding_get_catalog_url() ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_machine_label( true ) ); ?>
					</a>
					<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-3 text-xs font-bold uppercase tracking-[0.14em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
					</a>
				</div>

				<div class="rounded-2xl border border-slate-800 bg-slate-900 p-4 text-sm text-slate-300">
					<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold mb-2"><?php esc_html_e( 'Sales Contact', 'industrial-welding' ); ?></p>
					<a href="<?php echo esc_url( industrial_welding_get_contact_phone_href() ); ?>" class="text-amber-300 font-semibold transition hover:text-amber-200">
						<?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?>
					</a>
				</div>
			</div>
		</div>
	</header>

	<main id="primary" class="site-main flex-grow">
