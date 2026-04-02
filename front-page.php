<?php
/**
 * The front page template.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$products_link = get_post_type_archive_link( 'machines' ) ? get_post_type_archive_link( 'machines' ) : home_url( '/machines/' );
$about_page    = get_page_by_path( 'about' );
$contact_page  = get_page_by_path( 'contact' );
$about_link    = $about_page ? get_permalink( $about_page ) : home_url( '/about/' );
$contact_link  = $contact_page ? get_permalink( $contact_page ) : home_url( '/contact/' );

$product_cards = array(
	array(
		'title'       => __( 'Plasma Cutters', 'industrial-welding' ),
		'description' => __( 'Reliable cutting performance for fabrication, maintenance, and production environments.', 'industrial-welding' ),
		'url'         => $products_link,
	),
	array(
		'title'       => __( 'MIG Welders', 'industrial-welding' ),
		'description' => __( 'High-efficiency MIG systems designed for stable arcs and continuous operation.', 'industrial-welding' ),
		'url'         => $products_link,
	),
	array(
		'title'       => __( 'TIG Welders', 'industrial-welding' ),
		'description' => __( 'Precision TIG solutions suitable for quality-focused metal processing lines.', 'industrial-welding' ),
		'url'         => $products_link,
	),
	array(
		'title'       => __( 'Welding Accessories', 'industrial-welding' ),
		'description' => __( 'Complete accessory options to improve safety, productivity, and output consistency.', 'industrial-welding' ),
		'url'         => $products_link,
	),
);

$advantages = array(
	array(
		'title'       => __( 'Stable Quality', 'industrial-welding' ),
		'description' => __( 'Every unit is built for long-term industrial use with strict quality control standards.', 'industrial-welding' ),
	),
	array(
		'title'       => __( 'OEM / ODM Support', 'industrial-welding' ),
		'description' => __( 'Flexible cooperation models help partners build products for different markets and channels.', 'industrial-welding' ),
	),
	array(
		'title'       => __( 'Fast Delivery', 'industrial-welding' ),
		'description' => __( 'Efficient production and logistics processes shorten lead times for B2B customers.', 'industrial-welding' ),
	),
	array(
		'title'       => __( 'Technical Service', 'industrial-welding' ),
		'description' => __( 'Pre-sales and after-sales support help your teams deploy and maintain equipment with confidence.', 'industrial-welding' ),
	),
);
?>

<section class="b2b-hero">
	<div class="b2b-container b2b-hero__inner">
		<p class="b2b-eyebrow"><?php esc_html_e( 'Industrial Welding Solutions', 'industrial-welding' ); ?></p>
		<h1><?php esc_html_e( 'Professional Equipment for Modern Manufacturing', 'industrial-welding' ); ?></h1>
		<p class="b2b-hero__description"><?php esc_html_e( 'We provide dependable plasma cutting and welding systems for distributors, workshops, and enterprise buyers.', 'industrial-welding' ); ?></p>
		<div class="b2b-hero__actions">
			<a class="b2b-button" href="<?php echo esc_url( $products_link ); ?>"><?php esc_html_e( 'View Products', 'industrial-welding' ); ?></a>
			<a class="b2b-button b2b-button--outline" href="<?php echo esc_url( $contact_link ); ?>"><?php esc_html_e( 'Request Quote', 'industrial-welding' ); ?></a>
		</div>
	</div>
</section>

<section class="b2b-section">
	<div class="b2b-container">
		<div class="b2b-section-heading">
			<h2><?php esc_html_e( 'Product Categories', 'industrial-welding' ); ?></h2>
			<p><?php esc_html_e( 'Quickly browse our core product lines and find the right equipment for your applications.', 'industrial-welding' ); ?></p>
		</div>
		<div class="b2b-card-grid">
			<?php foreach ( $product_cards as $product_card ) : ?>
				<article class="b2b-card">
					<h3><?php echo esc_html( $product_card['title'] ); ?></h3>
					<p><?php echo esc_html( $product_card['description'] ); ?></p>
					<a class="b2b-text-link" href="<?php echo esc_url( $product_card['url'] ); ?>"><?php esc_html_e( 'Explore', 'industrial-welding' ); ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="b2b-section b2b-section--muted">
	<div class="b2b-container">
		<div class="b2b-section-heading">
			<h2><?php esc_html_e( 'Why Work With Us', 'industrial-welding' ); ?></h2>
			<p><?php esc_html_e( 'A practical and dependable partner for long-term B2B collaboration.', 'industrial-welding' ); ?></p>
		</div>
		<div class="b2b-advantage-grid">
			<?php foreach ( $advantages as $advantage ) : ?>
				<div class="b2b-advantage-item">
					<h3><?php echo esc_html( $advantage['title'] ); ?></h3>
					<p><?php echo esc_html( $advantage['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="b2b-section">
	<div class="b2b-container b2b-about-snippet">
		<div>
			<h2><?php esc_html_e( 'About Our Company', 'industrial-welding' ); ?></h2>
			<p><?php esc_html_e( 'Plasmagron focuses on practical welding technology for global business customers, with clear communication, steady production, and long-term service support.', 'industrial-welding' ); ?></p>
		</div>
		<div>
			<a class="b2b-button" href="<?php echo esc_url( $about_link ); ?>"><?php esc_html_e( 'Learn More', 'industrial-welding' ); ?></a>
		</div>
	</div>
</section>

<section class="b2b-cta">
	<div class="b2b-container b2b-cta__inner">
		<h2><?php esc_html_e( 'Need Pricing or Product Advice?', 'industrial-welding' ); ?></h2>
		<p><?php esc_html_e( 'Tell us your requirements and our team will provide a tailored quotation quickly.', 'industrial-welding' ); ?></p>
		<a class="b2b-button b2b-button--light" href="<?php echo esc_url( $contact_link ); ?>"><?php esc_html_e( 'Send Inquiry', 'industrial-welding' ); ?></a>
	</div>
</section>

<?php
get_footer();
