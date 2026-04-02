<?php
/**
 * The header for the theme.
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'industrial-welding' ); ?></a>

	<header id="masthead" class="site-header b2b-site-header">
		<div class="b2b-container b2b-header-inner">
			<div class="site-branding b2b-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a class="b2b-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation b2b-main-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'industrial-welding' ); ?>">
				<?php
				$about_page   = get_page_by_path( 'about' );
				$blog_page_id = (int) get_option( 'page_for_posts' );
				$contact_page = get_page_by_path( 'contact' );

				$fallback_links = array(
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

				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'container'      => false,
						)
					);
				} else {
					?>
					<ul id="primary-menu" class="menu">
						<?php foreach ( $fallback_links as $fallback_link ) : ?>
							<li><a href="<?php echo esc_url( $fallback_link['url'] ); ?>"><?php echo esc_html( $fallback_link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
					<?php
				}
				?>
			</nav>

			<div class="b2b-header-actions">
				<a class="b2b-button b2b-button--small" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<?php esc_html_e( 'Get Quote', 'industrial-welding' ); ?>
				</a>
				<button id="mobile-menu-toggle" class="b2b-mobile-toggle" aria-controls="mobile-menu" aria-expanded="false">
					<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'industrial-welding' ); ?></span>
					<svg class="b2b-mobile-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					</svg>
				</button>
			</div>
		</div>

		<div id="mobile-menu" class="b2b-mobile-menu hidden">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'mobile-primary-menu',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
			} else {
				?>
				<ul id="mobile-primary-menu" class="menu">
					<?php foreach ( $fallback_links as $fallback_link ) : ?>
						<li><a href="<?php echo esc_url( $fallback_link['url'] ); ?>"><?php echo esc_html( $fallback_link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<?php
			}
			?>
			<a class="b2b-button b2b-button--full" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<?php esc_html_e( 'Contact Us', 'industrial-welding' ); ?>
			</a>
		</div>
	</header>

	<main id="primary" class="site-main">
