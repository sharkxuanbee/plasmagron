<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_type        = get_post_type();
$post_type_object = get_post_type_object( $post_type );
$post_summary     = industrial_welding_get_post_summary( get_the_ID(), is_singular() ? 40 : 24 );
$archive_url      = 'post' === $post_type ? industrial_welding_get_blog_page_url() : get_post_type_archive_link( $post_type );
$archive_label    = 'post' === $post_type
	? __( 'Back To Blog', 'industrial-welding' )
	: __( 'Back To Archive', 'industrial-welding' );

?>

<?php if ( is_singular() ) : ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<section class="relative overflow-hidden bg-slate-950">
			<div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.2),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.16),_transparent_30%)]"></div>
			<div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
				<div class="max-w-4xl">
					<p class="text-xs uppercase tracking-[0.34em] text-amber-300 font-semibold mb-4">
						<?php
						if ( 'post' === $post_type ) {
							$categories = get_the_category();
							echo esc_html( ! empty( $categories ) ? $categories[0]->name : __( 'Insight Article', 'industrial-welding' ) );
						} else {
							echo esc_html( $post_type_object ? $post_type_object->labels->singular_name : __( 'Entry', 'industrial-welding' ) );
						}
						?>
					</p>
					<h1 class="text-4xl md:text-5xl xl:text-6xl font-bold text-white font-rajdhani leading-none"><?php the_title(); ?></h1>
					<?php if ( $post_summary ) : ?>
						<p class="mt-5 max-w-3xl text-lg md:text-xl text-slate-300 leading-relaxed"><?php echo esc_html( $post_summary ); ?></p>
					<?php endif; ?>
					<div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-300">
						<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2"><?php echo esc_html( get_the_date() ); ?></span>
						<?php if ( 'post' === $post_type ) : ?>
							<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-900/80 px-4 py-2">
								<?php
								printf(
									/* translators: %s: author name. */
									esc_html__( 'By %s', 'industrial-welding' ),
									esc_html( get_the_author() )
								);
								?>
							</span>
						<?php endif; ?>
					</div>
					<div class="mt-8 flex flex-col sm:flex-row gap-3">
						<?php if ( $archive_url ) : ?>
							<a href="<?php echo esc_url( $archive_url ); ?>" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
								<?php echo esc_html( $archive_label ); ?>
							</a>
						<?php endif; ?>
						<a href="<?php echo esc_url( industrial_welding_get_contact_page_url() ); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-4 text-sm font-bold uppercase tracking-[0.08em] text-white transition hover:border-cyan-300 hover:text-cyan-200 font-rajdhani">
							<?php echo esc_html( industrial_welding_get_request_quote_label() ); ?>
						</a>
					</div>
				</div>
			</div>
		</section>

		<section class="bg-slate-900 border-y border-slate-800">
			<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
				<div class="overflow-hidden rounded-[1.8rem] border border-slate-800 bg-slate-950/78 shadow-[0_24px_55px_rgba(2,6,23,0.42)]">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="border-b border-slate-800">
							<a href="<?php the_permalink(); ?>" rel="bookmark">
								<?php the_post_thumbnail( 'large', array( 'class' => 'h-auto w-full object-cover object-center max-h-[520px]' ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="p-6 md:p-8 xl:p-10">
						<div class="entry-content text-slate-200 leading-relaxed">
							<?php
							the_content(
								sprintf(
									wp_kses(
										/* translators: %s: post title for screen readers. */
										__( 'Continue reading <span class="screen-reader-text">%s</span>', 'industrial-welding' ),
										array(
											'span' => array(
												'class' => array(),
											),
										)
									),
									wp_kses_post( get_the_title() )
								)
							);

							wp_link_pages(
								array(
									'before' => '<div class="mt-8 flex flex-wrap gap-3 items-center text-sm text-slate-300"><span class="font-semibold uppercase tracking-[0.18em] text-slate-500">' . esc_html__( 'Pages', 'industrial-welding' ) . '</span>',
									'after'  => '</div>',
								)
							);
							?>
						</div>

						<?php if ( 'post' === $post_type ) : ?>
							<?php
							$tag_list = get_the_tag_list(
								'<div class="mt-8 flex flex-wrap gap-3">',
								'',
								'</div>'
							);
							?>
							<?php if ( $tag_list ) : ?>
								<div class="mt-8 border-t border-slate-800 pt-6">
									<p class="text-xs uppercase tracking-[0.2em] text-slate-500 font-semibold mb-3"><?php esc_html_e( 'Tagged', 'industrial-welding' ); ?></p>
									<?php echo wp_kses_post( $tag_list ); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( get_edit_post_link() ) : ?>
							<div class="mt-8 border-t border-slate-800 pt-6 text-sm text-slate-400">
								<?php
								edit_post_link(
									sprintf(
										wp_kses(
											/* translators: %s: post title for screen readers. */
											__( 'Edit <span class="screen-reader-text">%s</span>', 'industrial-welding' ),
											array(
												'span' => array(
													'class' => array(),
												),
											)
										),
										wp_kses_post( get_the_title() )
									),
									'<span class="inline-flex items-center rounded-full border border-slate-700 px-4 py-2 hover:border-amber-300 hover:text-amber-200 transition">',
									'</span>'
								);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	</article>
<?php else : ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-800 bg-[linear-gradient(180deg,rgba(15,23,42,0.98),rgba(15,23,42,0.84))] shadow-[0_24px_55px_rgba(2,6,23,0.42)] transition duration-300 hover:-translate-y-1 hover:border-amber-300/40' ); ?>>
		<div class="relative overflow-hidden border-b border-slate-800">
			<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>" class="block">
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'h-64 w-full object-cover object-center transition duration-500 group-hover:scale-[1.03]' ) ); ?>
				</a>
			<?php else : ?>
				<a href="<?php the_permalink(); ?>" class="flex h-64 items-center justify-center bg-[radial-gradient(circle_at_top_left,_rgba(245,158,11,0.18),_transparent_32%),linear-gradient(180deg,rgba(15,23,42,1),rgba(2,6,23,1))] text-slate-500">
					<span class="text-sm uppercase tracking-[0.22em] font-semibold font-rajdhani">
						<?php echo esc_html( $post_type_object ? $post_type_object->labels->singular_name : __( 'Entry', 'industrial-welding' ) ); ?>
					</span>
				</a>
			<?php endif; ?>
			<div class="absolute inset-x-0 top-0 flex flex-wrap items-center justify-between gap-3 p-4">
				<span class="inline-flex items-center rounded-full border border-amber-400/30 bg-slate-950/75 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-200 font-rajdhani">
					<?php
					if ( 'post' === $post_type ) {
						$categories = get_the_category();
						echo esc_html( ! empty( $categories ) ? $categories[0]->name : __( 'Insight Article', 'industrial-welding' ) );
					} else {
						echo esc_html( $post_type_object ? $post_type_object->labels->singular_name : __( 'Entry', 'industrial-welding' ) );
					}
					?>
				</span>
				<span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/80 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-slate-300 font-rajdhani">
					<?php echo esc_html( get_the_date() ); ?>
				</span>
			</div>
		</div>

		<div class="flex flex-1 flex-col p-6">
			<div class="flex-1">
				<h2 class="text-2xl font-bold text-white font-rajdhani leading-tight">
					<a href="<?php the_permalink(); ?>" rel="bookmark" class="transition group-hover:text-amber-200"><?php the_title(); ?></a>
				</h2>
				<?php if ( $post_summary ) : ?>
					<p class="mt-4 text-slate-300 leading-relaxed line-clamp-3"><?php echo esc_html( $post_summary ); ?></p>
				<?php endif; ?>
			</div>

			<div class="mt-6">
				<a href="<?php the_permalink(); ?>" rel="bookmark" class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold uppercase tracking-[0.08em] text-slate-950 transition hover:bg-amber-300 font-rajdhani">
					<?php esc_html_e( 'Read Article', 'industrial-welding' ); ?>
				</a>
			</div>
		</div>
	</article>
<?php endif; ?>
