<?php
/**
 * The footer for the theme.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_page = get_page_by_path( 'contact' );
$about_page   = get_page_by_path( 'about' );
$blog_page_id = (int) get_option( 'page_for_posts' );

$footer_links = array(
	array(
		'label' => __( 'Home', 'industrial-welding' ),
		'url'   => home_url( '/' ),
	),
	array(
		'label' => __( 'Products', 'industrial-welding' ),
		'url'   => get_post_type_archive_link( 'machines' ) ? get_post_type_archive_link( 'machines' ) : home_url( '/machines/' ),
	),
	array(
		'label' => __( 'About', 'industrial-welding' ),
		'url'   => $about_page ? get_permalink( $about_page ) : home_url( '/about/' ),
	),
	array(
		'label' => __( 'Blog', 'industrial-welding' ),
		'url'   => $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/blog/' ),
	),
	array(
		'label' => __( 'Contact', 'industrial-welding' ),
		'url'   => $contact_page ? get_permalink( $contact_page ) : home_url( '/contact/' ),
	),
);
?>

	</main>

	<footer id="colophon" class="site-footer b2b-footer">
		<div class="b2b-container b2b-footer__grid">
			<div>
				<h2 class="b2b-footer__title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h2>
				<p><?php esc_html_e( 'Focused on dependable welding and cutting solutions for global B2B partners.', 'industrial-welding' ); ?></p>
			</div>

			<div>
				<h3><?php esc_html_e( 'Quick Navigation', 'industrial-welding' ); ?></h3>
				<ul class="b2b-footer__links">
					<?php foreach ( $footer_links as $footer_link ) : ?>
						<li><a href="<?php echo esc_url( $footer_link['url'] ); ?>"><?php echo esc_html( $footer_link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div>
				<h3><?php esc_html_e( 'Contact', 'industrial-welding' ); ?></h3>
				<ul class="b2b-footer__contact">
					<li><?php esc_html_e( 'Email: sales@example.com', 'industrial-welding' ); ?></li>
					<li><?php esc_html_e( 'Phone: +1 (800) 000-0000', 'industrial-welding' ); ?></li>
					<li><?php esc_html_e( 'Address: 123 Business Avenue, Industrial City', 'industrial-welding' ); ?></li>
				</ul>
			</div>
		</div>

		<div class="b2b-container b2b-footer__bottom">
			<p>
				<?php
				echo esc_html(
					sprintf(
						/* translators: %1$s: year, %2$s: site name. */
						__( '© %1$s %2$s. All rights reserved.', 'industrial-welding' ),
						gmdate( 'Y' ),
						get_bloginfo( 'name' )
					)
				);
				?>
			</p>
		</div>
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>
