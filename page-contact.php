<?php
/**
 * Template Name: Contact Landing
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$catalog_url = industrial_welding_get_catalog_url();
$finder_url  = industrial_welding_get_finder_page_url();
$compare_url = industrial_welding_get_compare_page_url();
$page_title  = get_the_title() ? get_the_title() : __( 'Contact Us', 'industrial-welding' );
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.24),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(34,197,94,0.14),_transparent_30%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Sales & Support', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php echo esc_html( $page_title ); ?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php esc_html_e( 'Use this page for pricing questions, specification requests, bulk-buying support, payment follow-up, warranty follow-up, and pre-purchase guidance on the right machine for the job.', 'industrial-welding' ); ?>
				</p>
				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( industrial_welding_get_contact_phone_href() ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?>
					</a>
					<a href="mailto:<?php echo esc_attr( industrial_welding_get_contact_email() ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold tracking-[0.02em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php echo esc_html( industrial_welding_get_contact_email() ); ?>
					</a>
				</div>
			</div>

			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Fastest Path', 'industrial-welding' ); ?></p>
				<ul class="mt-5 space-y-4 text-sm text-slate-300">
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3"><?php esc_html_e( 'Need a machine suggestion first: open Finder.', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3"><?php esc_html_e( 'Already have candidates: use Compare before reaching out.', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-4 py-3"><?php esc_html_e( 'Need pricing, specs, or support: call or email directly from this page.', 'industrial-welding' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-14">
		<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
			<div class="rounded-[1.5rem] border border-slate-800 bg-slate-950/78 p-6 shadow-[0_20px_40px_rgba(2,6,23,0.3)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Sales Line', 'industrial-welding' ); ?></p>
				<p class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?></p>
				<p class="mt-3 text-sm text-slate-300"><?php esc_html_e( 'Best for urgent pricing questions, product matching, and stock questions.', 'industrial-welding' ); ?></p>
			</div>

			<div class="rounded-[1.5rem] border border-slate-800 bg-slate-950/78 p-6 shadow-[0_20px_40px_rgba(2,6,23,0.3)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Email', 'industrial-welding' ); ?></p>
				<p class="mt-3 text-2xl font-bold text-white font-rajdhani break-all"><?php echo esc_html( industrial_welding_get_contact_email() ); ?></p>
				<p class="mt-3 text-sm text-slate-300"><?php esc_html_e( 'Best for spec sheet requests, purchase lists, warranty records, and attachments.', 'industrial-welding' ); ?></p>
			</div>

			<div class="rounded-[1.5rem] border border-slate-800 bg-slate-950/78 p-6 shadow-[0_20px_40px_rgba(2,6,23,0.3)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Support Scope', 'industrial-welding' ); ?></p>
				<p class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Pre & Post Sale', 'industrial-welding' ); ?></p>
				<p class="mt-3 text-sm text-slate-300"><?php esc_html_e( 'Coverage includes machine selection, documentation, setup questions, and after-sales guidance.', 'industrial-welding' ); ?></p>
			</div>

			<div class="rounded-[1.5rem] border border-slate-800 bg-slate-950/78 p-6 shadow-[0_20px_40px_rgba(2,6,23,0.3)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Recommended Prep', 'industrial-welding' ); ?></p>
				<p class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Share Job Details', 'industrial-welding' ); ?></p>
				<p class="mt-3 text-sm text-slate-300"><?php esc_html_e( 'Include material type, thickness, voltage, process needs, and target quantity for faster advice.', 'industrial-welding' ); ?></p>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_minmax(320px,0.9fr)] gap-8">
			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-7 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Before You Reach Out', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Useful details to send with your inquiry', 'industrial-welding' ); ?></h2>
				<ul class="mt-6 space-y-4 text-slate-300">
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-5 py-4"><?php esc_html_e( 'Which machine or product family you are considering.', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-5 py-4"><?php esc_html_e( 'Material type and thickness for cutting or welding.', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-5 py-4"><?php esc_html_e( 'Available input power, expected usage frequency, and quantity needed.', 'industrial-welding' ); ?></li>
					<li class="rounded-2xl border border-slate-800 bg-slate-950/70 px-5 py-4"><?php esc_html_e( 'Whether you need pricing only, technical matching, or after-sales support.', 'industrial-welding' ); ?></li>
				</ul>
			</div>

			<div class="space-y-6">
				<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/78 p-7 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Self-Serve First', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Use the catalog tools before sending a request', 'industrial-welding' ); ?></h2>
					<p class="mt-4 text-slate-300 leading-relaxed"><?php esc_html_e( 'If you are still narrowing options, use Finder or Compare first. That makes the conversation faster and gives support more context.', 'industrial-welding' ); ?></p>
					<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
						<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php esc_html_e( 'Open Finder', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php esc_html_e( 'Compare Machines', 'industrial-welding' ); ?>
						</a>
						<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani sm:col-span-2">
							<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
						</a>
					</div>
				</div>

				<div class="rounded-[1.8rem] border border-amber-400/15 bg-slate-900/92 p-7 shadow-[0_25px_70px_rgba(15,23,42,0.62)]">
					<p class="text-xs uppercase tracking-[0.24em] text-amber-300 font-semibold"><?php esc_html_e( 'Direct Contact', 'industrial-welding' ); ?></p>
					<h2 class="mt-3 text-3xl font-bold text-white font-rajdhani"><?php echo esc_html( industrial_welding_get_request_quote_label() ); ?></h2>
					<p class="mt-4 text-slate-300 leading-relaxed"><?php esc_html_e( 'Reach out directly if you need formal pricing, documentation, or support for an existing machine order.', 'industrial-welding' ); ?></p>
					<div class="mt-6 flex flex-col gap-3">
						<a href="<?php echo esc_url( industrial_welding_get_contact_phone_href() ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
							<?php echo esc_html( industrial_welding_get_contact_phone_label() ); ?>
						</a>
						<a href="mailto:<?php echo esc_attr( industrial_welding_get_contact_email() ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold tracking-[0.02em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php echo esc_html( industrial_welding_get_contact_email() ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
