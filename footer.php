<?php
/**
 * The footer for the Industrial Welding theme
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

    </main>

    <footer id="colophon" class="site-footer bg-gray-950 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div class="lg:col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="text-2xl font-bold text-yellow-500 font-rajdhani tracking-wider">PLASMA</span>
                        <span class="text-2xl font-bold text-gray-100 font-rajdhani tracking-wider">GRON</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        <?php esc_html_e( 'Premium industrial welding machinery and plasma cutters for professional B2B applications. Built to perform, designed to last.', 'industrial-welding' ); ?>
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors" aria-label="<?php esc_attr_e( 'LinkedIn', 'industrial-welding' ); ?>">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors" aria-label="<?php esc_attr_e( 'YouTube', 'industrial-welding' ); ?>">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors" aria-label="<?php esc_attr_e( 'Twitter', 'industrial-welding' ); ?>">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    </div>
                <?php else : ?>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-500 mb-4 font-rajdhani"><?php esc_html_e( 'Products', 'industrial-welding' ); ?></h3>
                        <ul class="space-y-3">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'container'      => false,
                                'fallback_cb'    => false,
                                'items_wrap'     => '%3$s',
                                'link_before'    => '<li class="text-gray-400 hover:text-yellow-500 transition-colors text-sm"><span class="inline-flex items-center">',
                                'link_after'     => '</span></li>',
                            ) );
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <div>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    </div>
                <?php else : ?>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-500 mb-4 font-rajdhani"><?php esc_html_e( 'Support', 'industrial-welding' ); ?></h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm"><?php esc_html_e( 'Warranty Info', 'industrial-welding' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm"><?php esc_html_e( 'Shipping & Delivery', 'industrial-welding' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm"><?php esc_html_e( 'Technical Support', 'industrial-welding' ); ?></a></li>
                            <li><a href="#" class="text-gray-400 hover:text-yellow-500 transition-colors text-sm"><?php esc_html_e( 'Contact Us', 'industrial-welding' ); ?></a></li>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <div>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    </div>
                <?php else : ?>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-500 mb-4 font-rajdhani"><?php esc_html_e( 'Contact', 'industrial-welding' ); ?></h3>
                        <ul class="space-y-3 text-gray-400 text-sm">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span><?php esc_html_e( '1234 Industrial Blvd<br>Detroit, MI 48201', 'industrial-welding' ); ?></span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:+18005551234" class="hover:text-yellow-500 transition-colors">1-800-555-1234</a>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:sales@plasmagron.com" class="hover:text-yellow-500 transition-colors">sales@plasmagron.com</a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-gray-500 text-sm">
                        &copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'industrial-welding' ); ?>
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors"><?php esc_html_e( 'Privacy Policy', 'industrial-welding' ); ?></a>
                        <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors"><?php esc_html_e( 'Terms of Service', 'industrial-welding' ); ?></a>
                        <a href="#" class="text-gray-500 hover:text-gray-400 text-sm transition-colors"><?php esc_html_e( 'Cookie Policy', 'industrial-welding' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>

<?php wp_footer(); ?>

</body>
</html>
