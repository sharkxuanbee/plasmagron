<?php
/**
 * Template Name: Welder Finder
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$finder_url        = industrial_welding_get_finder_page_url();
$catalog_url       = industrial_welding_get_catalog_url();
$compare_url       = industrial_welding_get_compare_page_url();
$cart_url          = industrial_welding_get_cart_page_url();
$contact_url       = industrial_welding_get_contact_page_url();
$taxonomy_config   = industrial_welding_get_filterable_catalog_taxonomies();
$thickness_options = industrial_welding_get_finder_thickness_options();
$answers           = industrial_welding_get_finder_answers();
$coverage          = industrial_welding_get_catalog_data_coverage();
$recommendations   = industrial_welding_get_finder_recommendations( $answers, 3 );

$finder_steps = array(
	array(
		'key'     => 'usage_scene',
		'eyebrow' => $taxonomy_config['usage_scene']['short_label'],
		'title'   => $taxonomy_config['usage_scene']['question'],
		'hint'    => $taxonomy_config['usage_scene']['question_hint'],
		'type'    => 'term',
		'options' => industrial_welding_get_catalog_filter_terms( 'usage_scene' ),
		'empty'   => $taxonomy_config['usage_scene']['empty_message'],
	),
	array(
		'key'     => 'thickness',
		'eyebrow' => __( 'Material Thickness', 'industrial-welding' ),
		'title'   => __( 'Material thickness?', 'industrial-welding' ),
		'hint'    => __( 'Thickness is mapped against amperage ranges so the recommendation stays explainable.', 'industrial-welding' ),
		'type'    => 'static',
		'options' => $thickness_options,
		'empty'   => '',
	),
	array(
		'key'     => 'skill_level',
		'eyebrow' => $taxonomy_config['skill_level']['short_label'],
		'title'   => $taxonomy_config['skill_level']['question'],
		'hint'    => $taxonomy_config['skill_level']['question_hint'],
		'type'    => 'term',
		'options' => industrial_welding_get_catalog_filter_terms( 'skill_level' ),
		'empty'   => $taxonomy_config['skill_level']['empty_message'],
	),
	array(
		'key'     => 'budget_range',
		'eyebrow' => $taxonomy_config['budget_range']['short_label'],
		'title'   => $taxonomy_config['budget_range']['question'],
		'hint'    => $taxonomy_config['budget_range']['question_hint'],
		'type'    => 'term',
		'options' => industrial_welding_get_catalog_filter_terms( 'budget_range' ),
		'empty'   => $taxonomy_config['budget_range']['empty_message'],
	),
);
?>

<section class="relative overflow-hidden bg-slate-950">
	<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.24),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.16),_transparent_30%)]"></div>
	<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_minmax(320px,360px)] gap-8 items-start">
			<div class="max-w-4xl">
				<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4"><?php esc_html_e( 'Selection Guidance', 'industrial-welding' ); ?></p>
				<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none">
					<?php esc_html_e( 'Welder Finder', 'industrial-welding' ); ?>
				</h1>
				<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed">
					<?php esc_html_e( 'Answer four practical questions and the Finder will narrow the catalog to the machines that best fit the intended work, operator level, and budget.', 'industrial-welding' ); ?>
				</p>

				<div class="mt-8 flex flex-col sm:flex-row gap-3">
					<a href="#finder-form" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Start Finder', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>

			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/80 p-6 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
				<p class="text-xs uppercase tracking-[0.24em] text-slate-500 font-semibold"><?php esc_html_e( 'Coverage Check', 'industrial-welding' ); ?></p>
				<h2 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Selection data is now part of the catalog', 'industrial-welding' ); ?></h2>
				<p class="mt-3 text-sm text-slate-300 leading-relaxed"><?php esc_html_e( 'Finder and filters rely on usage scene, skill level, budget, category, and core spec coverage. These counters make the matching logic transparent.', 'industrial-welding' ); ?></p>

				<div class="mt-5 space-y-3">
					<?php foreach ( $coverage['rows'] as $row ) : ?>
						<div class="rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
							<div class="flex items-center justify-between gap-3">
								<p class="text-sm text-slate-300"><?php echo esc_html( $row['label'] ); ?></p>
								<span class="text-lg font-bold text-white font-rajdhani"><?php echo esc_html( $row['percent'] ); ?>%</span>
							</div>
							<div class="mt-3 h-2 rounded-full bg-slate-800">
								<div class="h-2 rounded-full bg-gradient-to-r from-amber-400 to-cyan-400" style="width: <?php echo esc_attr( $row['percent'] ); ?>%"></div>
							</div>
							<p class="mt-2 text-xs uppercase tracking-[0.16em] text-slate-500 font-semibold">
								<?php
								echo esc_html(
									sprintf(
										/* translators: 1: count, 2: total product count */
										__( '%1$s of %2$s machines', 'industrial-welding' ),
										number_format_i18n( $row['count'] ),
										number_format_i18n( $coverage['total'] )
									)
								);
								?>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="bg-slate-900 border-y border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="grid grid-cols-1 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)] gap-8">
			<div class="rounded-[1.9rem] border border-slate-800 bg-slate-950/80 p-7 shadow-[0_24px_55px_rgba(2,6,23,0.38)]">
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Finder Rules', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Why the recommendation appears', 'industrial-welding' ); ?></h2>
				<div class="mt-6 space-y-4">
					<div class="rounded-2xl border border-slate-800 bg-slate-900/75 p-5">
						<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php esc_html_e( 'Priority 1', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-slate-300 leading-relaxed"><?php esc_html_e( 'Usage scene carries the highest weight, because it is the strongest signal for whether the machine belongs in the shortlist at all.', 'industrial-welding' ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-900/75 p-5">
						<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php esc_html_e( 'Priority 2', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-slate-300 leading-relaxed"><?php esc_html_e( 'Thickness is mapped to amperage ranges so the Finder can explain why a machine is fit for lighter, medium, or heavier work.', 'industrial-welding' ); ?></p>
					</div>
					<div class="rounded-2xl border border-slate-800 bg-slate-900/75 p-5">
						<p class="text-xs uppercase tracking-[0.18em] text-amber-300 font-semibold"><?php esc_html_e( 'Priority 3', 'industrial-welding' ); ?></p>
						<p class="mt-2 text-slate-300 leading-relaxed"><?php esc_html_e( 'Skill level and budget refine the shortlist. If there is no exact match, Finder falls back to the closest scored machines instead of showing a dead end.', 'industrial-welding' ); ?></p>
					</div>
				</div>
			</div>

			<form id="finder-form" method="get" action="<?php echo esc_url( $finder_url ); ?>" class="rounded-[1.9rem] border border-slate-800 bg-slate-950/80 p-7 shadow-[0_24px_55px_rgba(2,6,23,0.38)]" data-finder-form>
				<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
					<div>
						<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Question Flow', 'industrial-welding' ); ?></p>
						<h2 class="text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Answer the four Finder questions', 'industrial-welding' ); ?></h2>
					</div>
					<div class="flex flex-col items-start sm:items-end gap-2">
						<div class="rounded-full border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm text-slate-300">
							<span data-finder-progress>1 / <?php echo esc_html( count( $finder_steps ) ); ?></span>
						</div>
						<p class="hidden text-sm text-amber-200" data-finder-status></p>
					</div>
				</div>

				<div class="mt-8 space-y-8">
					<?php foreach ( $finder_steps as $index => $step ) : ?>
						<section class="<?php echo 0 === $index ? '' : 'hidden'; ?>" data-finder-step data-step-index="<?php echo esc_attr( $index ); ?>">
							<div class="mb-5">
								<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php echo esc_html( sprintf( __( 'Step %d', 'industrial-welding' ), $index + 1 ) ); ?></p>
								<h3 class="mt-2 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $step['title'] ); ?></h3>
								<p class="mt-2 text-slate-400"><?php echo esc_html( $step['hint'] ); ?></p>
							</div>

							<?php if ( 'term' === $step['type'] && empty( $step['options'] ) ) : ?>
								<div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/70 p-5 text-slate-400">
									<?php echo esc_html( $step['empty'] ); ?>
								</div>
							<?php else : ?>
								<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
									<?php foreach ( $step['options'] as $option_key => $option ) : ?>
										<?php
										$is_term      = 'term' === $step['type'];
										$option_value = $is_term ? $option->slug : $option_key;
										$option_label = $is_term ? $option->name : $option['label'];
										$option_desc  = $is_term
											? ( $option->description ? wp_trim_words( wp_strip_all_tags( $option->description ), 16 ) : __( 'Use this option to guide the shortlist.', 'industrial-welding' ) )
											: $option['description'];
										?>
										<label class="finder-choice group cursor-pointer">
											<input
												type="radio"
												name="<?php echo esc_attr( $step['key'] ); ?>"
												value="<?php echo esc_attr( $option_value ); ?>"
												class="sr-only peer"
												<?php checked( isset( $answers[ $step['key'] ] ) ? $answers[ $step['key'] ] : '', $option_value ); ?>
											>
											<span class="block h-full rounded-[1.6rem] border border-slate-800 bg-slate-900/75 p-5 transition peer-checked:border-amber-300 peer-checked:bg-amber-300/10 group-hover:border-cyan-300/40">
												<span class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php echo esc_html( $step['eyebrow'] ); ?></span>
												<span class="mt-3 block text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $option_label ); ?></span>
												<span class="mt-3 block text-sm leading-relaxed text-slate-300"><?php echo esc_html( $option_desc ); ?></span>
											</span>
										</label>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<div class="mt-6 flex flex-col sm:flex-row gap-3">
								<?php if ( $index > 0 ) : ?>
									<button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani" data-finder-prev>
										<?php esc_html_e( 'Previous', 'industrial-welding' ); ?>
									</button>
								<?php endif; ?>

								<?php if ( $index < count( $finder_steps ) - 1 ) : ?>
									<button type="button" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani" data-finder-next>
										<?php esc_html_e( 'Next Question', 'industrial-welding' ); ?>
									</button>
								<?php else : ?>
									<button type="submit" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
										<?php esc_html_e( 'Show Recommendations', 'industrial-welding' ); ?>
									</button>
								<?php endif; ?>

								<a href="<?php echo esc_url( $finder_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
									<?php esc_html_e( 'Reset Finder', 'industrial-welding' ); ?>
								</a>
							</div>
						</section>
					<?php endforeach; ?>
				</div>
			</form>
		</div>
	</div>
</section>

<section class="bg-slate-950">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
			<div>
				<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-2"><?php esc_html_e( 'Finder Results', 'industrial-welding' ); ?></p>
				<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani">
					<?php echo ! empty( $answers ) ? esc_html__( 'Recommended machines based on your answers', 'industrial-welding' ) : esc_html__( 'Submit the Finder to see recommendations', 'industrial-welding' ); ?>
				</h2>
			</div>

			<?php if ( ! empty( $answers ) ) : ?>
				<div class="flex flex-wrap gap-2">
					<?php foreach ( $answers as $key => $value ) : ?>
						<?php
						if ( 'thickness' === $key ) {
							$label = isset( $thickness_options[ $value ] ) ? $thickness_options[ $value ]['label'] : $value;
						} elseif ( isset( $taxonomy_config[ $key ] ) ) {
							$term = get_term_by( 'slug', $value, $key );
							$label = $term && ! is_wp_error( $term ) ? $term->name : $value;
						} else {
							$label = $value;
						}
						?>
						<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/75 px-4 py-2 text-sm text-slate-300">
							<?php echo esc_html( $label ); ?>
						</span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $recommendations['items'] ) ) : ?>
			<div class="rounded-[1.8rem] border border-slate-800 bg-slate-900/75 p-6 md:p-7 shadow-[0_24px_55px_rgba(2,6,23,0.38)] mb-8">
				<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_auto] gap-5 items-center">
					<div>
						<h3 class="text-2xl font-bold text-white font-rajdhani">
							<?php
							echo esc_html(
								$recommendations['is_fallback']
									? __( 'No exact match was available, so these are the safest fallback options.', 'industrial-welding' )
									: ( $recommendations['is_partial']
										? __( 'These are the closest matches to your Finder answers.', 'industrial-welding' )
										: __( 'These machines match the Finder rules most closely.', 'industrial-welding' ) )
							);
							?>
						</h3>
						<p class="mt-3 text-slate-300 leading-relaxed"><?php esc_html_e( 'Use the reasons under each machine to understand why it was selected, then move to detail, cart, or compare with a clearer purchase path.', 'industrial-welding' ); ?></p>
					</div>
					<div class="flex flex-col sm:flex-row gap-3">
						<a href="<?php echo esc_url( $recommendations['catalog_url'] ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
							<?php esc_html_e( 'Browse Matching Catalog', 'industrial-welding' ); ?>
						</a>
						<?php if ( $recommendations['compare_url'] ) : ?>
							<a href="<?php echo esc_url( $recommendations['compare_url'] ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
								<?php esc_html_e( 'Compare Recommendations', 'industrial-welding' ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
				<?php foreach ( $recommendations['items'] as $item ) : ?>
					<?php $purchase_action = industrial_welding_get_product_purchase_action( $item['id'] ); ?>
					<article class="overflow-hidden rounded-[1.8rem] border border-slate-800 bg-slate-900/75 shadow-[0_20px_50px_rgba(2,6,23,0.38)]">
						<?php if ( $item['thumbnail'] ) : ?>
							<img src="<?php echo esc_url( $item['thumbnail'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="h-56 w-full object-cover object-center">
						<?php else : ?>
							<div class="flex h-56 items-center justify-center bg-slate-950 text-slate-600">
								<svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
								</svg>
							</div>
						<?php endif; ?>

						<div class="p-6">
							<p class="text-xs uppercase tracking-[0.2em] text-amber-300 font-semibold"><?php echo esc_html( $item['category'] ); ?></p>
							<h3 class="mt-3 text-2xl font-bold text-white font-rajdhani"><?php echo esc_html( $item['title'] ); ?></h3>
							<?php if ( $item['summary'] ) : ?>
								<p class="mt-3 text-slate-300 leading-relaxed line-clamp-3"><?php echo esc_html( $item['summary'] ); ?></p>
							<?php endif; ?>
							<?php if ( $item['price_html'] ) : ?>
								<div class="mt-4 text-2xl font-bold text-amber-300 font-rajdhani">
									<?php echo wp_kses_post( $item['price_html'] ); ?>
								</div>
							<?php endif; ?>

							<div class="mt-5 rounded-2xl border border-slate-800 bg-slate-950/70 p-4">
								<p class="text-xs uppercase tracking-[0.18em] text-slate-500 font-semibold"><?php esc_html_e( 'Why it matched', 'industrial-welding' ); ?></p>
								<ul class="mt-3 space-y-2 text-sm text-slate-300">
									<?php foreach ( $item['reasons'] as $reason ) : ?>
										<li class="flex gap-3">
											<span class="mt-1 inline-flex h-2.5 w-2.5 flex-shrink-0 rounded-full bg-amber-300"></span>
											<span><?php echo esc_html( $reason ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>

							<div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
								<a href="<?php echo esc_url( $item['permalink'] ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
									<?php esc_html_e( 'View Machine', 'industrial-welding' ); ?>
								</a>
								<a href="<?php echo esc_url( $purchase_action['url'] ? $purchase_action['url'] : $cart_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
									<?php echo esc_html( $purchase_action['label'] ? $purchase_action['label'] : __( 'Open Cart', 'industrial-welding' ) ); ?>
								</a>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="rounded-[2rem] border border-dashed border-slate-700 bg-slate-900/60 px-6 py-16 text-center">
				<div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-950 text-slate-500">
					<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
					</svg>
				</div>
				<h3 class="mt-6 text-3xl font-bold text-white font-rajdhani"><?php esc_html_e( 'The Finder is ready when you are', 'industrial-welding' ); ?></h3>
				<p class="mt-3 max-w-2xl mx-auto text-slate-400 leading-relaxed"><?php esc_html_e( 'Work through the questions above. Once the answers are submitted, the Finder will return one to three machines with the reasons they fit the shortlist.', 'industrial-welding' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>

<section class="bg-slate-900 border-t border-slate-800">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-16">
		<div class="rounded-[1.9rem] border border-slate-800 bg-slate-950/80 p-8 md:p-10 shadow-[0_24px_55px_rgba(2,6,23,0.38)]">
			<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_auto] gap-8 items-center">
				<div>
					<p class="text-xs uppercase tracking-[0.3em] text-amber-300 font-semibold mb-3"><?php esc_html_e( 'Need Another Path?', 'industrial-welding' ); ?></p>
					<h2 class="text-3xl md:text-4xl font-bold text-white font-rajdhani"><?php esc_html_e( 'Switch from Finder to catalog, compare, cart, or direct checkout prep', 'industrial-welding' ); ?></h2>
					<p class="mt-4 text-slate-300 leading-relaxed"><?php esc_html_e( 'Finder is only one entry point. If the shortlist is already clear, move into detail, compare, or cart. If the requirements are still fuzzy, ask the sales team directly.', 'industrial-welding' ); ?></p>
				</div>
				<div class="flex flex-col sm:flex-row gap-3">
					<a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-amber-300 hover:text-amber-200 font-rajdhani">
						<?php esc_html_e( 'Browse Machines', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
						<?php esc_html_e( 'Compare Machines', 'industrial-welding' ); ?>
					</a>
					<a href="<?php echo esc_url( $cart_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
						<?php esc_html_e( 'Open Cart', 'industrial-welding' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
