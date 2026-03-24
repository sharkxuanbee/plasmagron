<?php
/**
 * The header for the Industrial Welding theme
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-gray-900 text-gray-100 font-roboto' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-yellow-500 focus:text-gray-900 focus:px-4 focus:py-2" href="#primary">
        <?php esc_html_e( 'Skip to content', 'industrial-welding' ); ?>
    </a>

    <header id="masthead" class="site-header bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center space-x-3">
                            <span class="text-3xl font-bold text-yellow-500 font-rajdhani tracking-wider">PLASMA</span>
                            <span class="text-3xl font-bold text-gray-100 font-rajdhani tracking-wider">GRON</span>
                        </a>
                    <?php endif; ?>
                </div>

                <nav id="site-navigation" class="hidden md:flex items-center space-x-8">
                    <?php
                    if ( has_nav_menu( 'primary' ) ) :
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'items_wrap'     => '<ul class="flex space-x-8">%3$s</ul>',
                            'link_before'    => '<span class="text-gray-300 hover:text-yellow-500 transition-colors font-medium">',
                            'link_after'     => '</span>',
                        ) );
                    else :
                    ?>
                        <ul class="flex space-x-8">
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-gray-300 hover:text-yellow-500 transition-colors font-medium"><?php esc_html_e( 'Home', 'industrial-welding' ); ?></a></li>
                            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'machines' ) ? get_post_type_archive_link( 'machines' ) : home_url( '/machines/' ) ); ?>" class="text-gray-300 hover:text-yellow-500 transition-colors font-medium"><?php esc_html_e( 'Machines', 'industrial-welding' ); ?></a></li>
                            <?php if ( get_page_by_path( 'compare' ) ) : ?>
                                <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'compare' ) ) ); ?>" class="text-gray-300 hover:text-yellow-500 transition-colors font-medium"><?php esc_html_e( 'Compare', 'industrial-welding' ); ?></a></li>
                            <?php endif; ?>
                            <?php if ( get_page_by_path( 'blog' ) ) : ?>
                                <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'blog' ) ) ); ?>" class="text-gray-300 hover:text-yellow-500 transition-colors font-medium"><?php esc_html_e( 'Blog', 'industrial-welding' ); ?></a></li>
                            <?php endif; ?>
                            <?php if ( get_page_by_path( 'contact' ) ) : ?>
                                <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="text-gray-300 hover:text-yellow-500 transition-colors font-medium"><?php esc_html_e( 'Contact', 'industrial-welding' ); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </nav>

                <div class="flex items-center space-x-4">
                    <div class="hidden lg:flex items-center">
                        <?php get_search_form( true ); ?>
                    </div>

                    <a href="tel:+18005551234" class="hidden xl:flex items-center text-gray-400 hover:text-yellow-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="font-medium">1-800-555-1234</span>
                    </a>

                    <button id="mobile-menu-toggle" class="md:hidden text-gray-300 hover:text-yellow-500 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-gray-800 border-t border-gray-700">
            <div class="px-4 py-4 space-y-3">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'items_wrap'     => '<ul class="space-y-3">%3$s</ul>',
                    'link_before'    => '<span class="block text-gray-300 hover:text-yellow-500 transition-colors font-medium">',
                    'link_after'     => '</span>',
                ) );
                ?>
                <div class="pt-4 border-t border-gray-700">
                    <?php get_search_form( true ); ?>
                </div>
            </div>
        </div>
    </header>

    <main id="primary" class="site-main flex-grow">
