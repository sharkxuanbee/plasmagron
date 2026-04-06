<?php
/**
 * The front page template
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

$featured_query = new WP_Query( industrial_welding_get_featured_products_query_args( 3 ) );
$catalog_url    = industrial_welding_get_catalog_url();
$compare_url    = industrial_welding_get_compare_page_url();
?>

<section class="relative bg-gray-900 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/95 to-transparent z-10"></div>
    <div class="absolute inset-0">
        <img 
            src="https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=1920&q=80" 
            alt="<?php esc_attr_e( 'Industrial welding machinery', 'industrial-welding' ); ?>" 
            class="w-full h-full object-cover opacity-30"
        >
    </div>
    
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-48">
        <div class="max-w-3xl">
            <span class="inline-block px-4 py-1 bg-yellow-500/20 text-yellow-500 text-sm font-semibold tracking-wider uppercase mb-6 font-rajdhani">
                <?php esc_html_e( 'Industrial Grade Equipment', 'industrial-welding' ); ?>
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 font-rajdhani leading-tight">
                <?php esc_html_e( 'Precision Welding Solutions for Heavy Industrial Applications', 'industrial-welding' ); ?>
            </h1>
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                <?php esc_html_e( 'Professional plasma cutters, MIG, and TIG welders engineered for maximum performance, durability, and efficiency in the most demanding B2B environments.', 'industrial-welding' ); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center justify-center px-8 py-4 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold text-lg rounded transition-colors font-rajdhani tracking-wide">
                    <?php esc_html_e( 'View All Machines', 'industrial-welding' ); ?>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="<?php echo esc_url( $compare_url ); ?>" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 hover:border-yellow-500 text-white font-bold text-lg rounded transition-colors font-rajdhani tracking-wide">
                    <?php esc_html_e( 'Compare Machines', 'industrial-welding' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="bg-gray-800 py-8 border-y border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-yellow-500 font-rajdhani">500+</span>
                <span class="text-sm text-gray-400 mt-1"><?php esc_html_e( 'Dealers Worldwide', 'industrial-welding' ); ?></span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-yellow-500 font-rajdhani">25+</span>
                <span class="text-sm text-gray-400 mt-1"><?php esc_html_e( 'Years Experience', 'industrial-welding' ); ?></span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-yellow-500 font-rajdhani">99%</span>
                <span class="text-sm text-gray-400 mt-1"><?php esc_html_e( 'Customer Satisfaction', 'industrial-welding' ); ?></span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-yellow-500 font-rajdhani">24/7</span>
                <span class="text-sm text-gray-400 mt-1"><?php esc_html_e( 'Technical Support', 'industrial-welding' ); ?></span>
            </div>
        </div>
    </div>
</section>

<section class="bg-gray-900 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1 bg-yellow-500/20 text-yellow-500 text-sm font-semibold tracking-wider uppercase mb-4 font-rajdhani">
                <?php esc_html_e( 'Featured Equipment', 'industrial-welding' ); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 font-rajdhani">
                <?php esc_html_e( 'Industry-Leading Machines', 'industrial-welding' ); ?>
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e( 'Discover our most popular professional-grade welding machines and plasma cutters, trusted by shops worldwide.', 'industrial-welding' ); ?>
            </p>
        </div>

        <?php if ( $featured_query->have_posts() ) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                while ( $featured_query->have_posts() ) :
                    $featured_query->the_post();
                    $product = industrial_welding_is_woocommerce_active() ? wc_get_product( get_the_ID() ) : null;
                    $machine_meta = array(
                        'amperage'      => industrial_welding_get_product_meta( get_the_ID(), 'amperage' ),
                        'input_voltage' => industrial_welding_get_product_meta( get_the_ID(), 'input_voltage' ),
                        'duty_cycle'    => industrial_welding_get_product_meta( get_the_ID(), 'duty_cycle' ),
                    );
                    $primary_term = industrial_welding_get_primary_product_term( get_the_ID() );
                    ?>
                    <article id="product-<?php the_ID(); ?>" <?php post_class( 'bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-yellow-500/50 transition-all duration-300 group' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="relative overflow-hidden">
                                <div class="aspect-w-16 aspect-h-9">
                                    <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
                                </div>
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-bold uppercase tracking-wider rounded font-rajdhani">
                                        <?php esc_html_e( 'Featured', 'industrial-welding' ); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <?php if ( $primary_term ) : ?>
                                <div class="mb-3">
                                    <span class="text-xs text-yellow-500 font-medium uppercase tracking-wider">
                                        <?php echo esc_html( $primary_term->name ); ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <h3 class="text-xl font-bold text-white mb-3 font-rajdhani group-hover:text-yellow-500 transition-colors">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <?php if ( has_excerpt() ) : ?>
                                <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                    <?php the_excerpt(); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ( $product && $product->get_price_html() ) : ?>
                                <p class="text-lg font-bold text-yellow-500 font-rajdhani mb-4">
                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                </p>
                            <?php endif; ?>

                            <div class="flex flex-wrap gap-4 mb-6 text-sm">
                                <?php if ( $machine_meta['amperage'] ) : ?>
                                    <div class="flex items-center text-gray-300">
                                        <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span><?php echo esc_html( $machine_meta['amperage'] ); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $machine_meta['input_voltage'] ) : ?>
                                    <div class="flex items-center text-gray-300">
                                        <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                        </svg>
                                        <span><?php echo esc_html( $machine_meta['input_voltage'] ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-700 hover:bg-yellow-500 hover:text-gray-900 text-white font-semibold rounded transition-colors font-rajdhani tracking-wide">
                                <?php esc_html_e( 'View Specs', 'industrial-welding' ); ?>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>
        <?php else : ?>
            <!-- Fallback: demo product cards when no machines have been added yet -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $demo_machines = array(
                    array(
                        'title'    => __( 'PRO-CUT X200 Plasma Cutter', 'industrial-welding' ),
                        'type'     => __( 'Plasma Cutter', 'industrial-welding' ),
                        'desc'     => __( 'High-definition plasma cutting system for precision metal fabrication. Ideal for industrial-grade steel, aluminum, and stainless steel cutting.', 'industrial-welding' ),
                        'amp'      => '200A',
                        'volt'     => '380V 3-Phase',
                        'img'      => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=600&q=80',
                    ),
                    array(
                        'title'    => __( 'WELD-MASTER MIG 350', 'industrial-welding' ),
                        'type'     => __( 'MIG Welder', 'industrial-welding' ),
                        'desc'     => __( 'Heavy-duty MIG welding system with synergic control. Engineered for high-volume production environments and structural fabrication.', 'industrial-welding' ),
                        'amp'      => '350A',
                        'volt'     => '220/380V',
                        'img'      => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=600&q=80',
                    ),
                    array(
                        'title'    => __( 'PRECISION TIG AC/DC 250', 'industrial-welding' ),
                        'type'     => __( 'TIG Welder', 'industrial-welding' ),
                        'desc'     => __( 'Advanced AC/DC TIG welder with pulse capability. Perfect for aerospace, automotive, and precision sheet metal applications.', 'industrial-welding' ),
                        'amp'      => '250A',
                        'volt'     => '220V 1-Phase',
                        'img'      => 'https://images.unsplash.com/photo-1615811361523-6bd03d7748e7?w=600&q=80',
                    ),
                );

                foreach ( $demo_machines as $machine ) :
                ?>
                <article class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-yellow-500/50 transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <img
                            src="<?php echo esc_url( $machine['img'] ); ?>"
                            alt="<?php echo esc_attr( $machine['title'] ); ?>"
                            class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-bold uppercase tracking-wider rounded font-rajdhani">
                                <?php esc_html_e( 'Featured', 'industrial-welding' ); ?>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="mb-3">
                            <span class="text-xs text-yellow-500 font-medium uppercase tracking-wider">
                                <?php echo esc_html( $machine['type'] ); ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 font-rajdhani group-hover:text-yellow-500 transition-colors">
                            <?php echo esc_html( $machine['title'] ); ?>
                        </h3>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                            <?php echo esc_html( $machine['desc'] ); ?>
                        </p>
                        <div class="flex flex-wrap gap-4 mb-6 text-sm">
                            <div class="flex items-center text-gray-300">
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span><?php echo esc_html( $machine['amp'] ); ?></span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                                <span><?php echo esc_html( $machine['volt'] ); ?></span>
                            </div>
                        </div>
                        <span class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-700 hover:bg-yellow-500 hover:text-gray-900 text-white font-semibold rounded transition-colors font-rajdhani tracking-wide cursor-pointer">
                            <?php esc_html_e( 'View Specs', 'industrial-welding' ); ?>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

        <div class="text-center mt-12">
            <a href="<?php echo esc_url( $catalog_url ); ?>" class="inline-flex items-center text-yellow-500 hover:text-yellow-400 font-semibold transition-colors">
                <?php esc_html_e( 'View All Machines', 'industrial-welding' ); ?>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 font-rajdhani">
                <?php esc_html_e( 'Why Choose Plasmagron?', 'industrial-welding' ); ?>
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e( 'Built for professionals who demand reliability, performance, and longevity in their equipment.', 'industrial-welding' ); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-900 p-8 rounded-lg border border-gray-800 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-500/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 font-rajdhani"><?php esc_html_e( '3-Year Warranty', 'industrial-welding' ); ?></h3>
                <p class="text-gray-400 text-sm">
                    <?php esc_html_e( 'Full coverage on all industrial machines. We stand behind every piece of equipment we manufacture with our industry-leading warranty.', 'industrial-welding' ); ?>
                </p>
            </div>

            <div class="bg-gray-900 p-8 rounded-lg border border-gray-800 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-500/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 font-rajdhani"><?php esc_html_e( 'B2B Bulk Orders', 'industrial-welding' ); ?></h3>
                <p class="text-gray-400 text-sm">
                    <?php esc_html_e( 'Specialized pricing programs for high-volume orders. Equip your entire shop with Plasmagron machinery and save significantly.', 'industrial-welding' ); ?>
                </p>
            </div>

            <div class="bg-gray-900 p-8 rounded-lg border border-gray-800 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-500/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 font-rajdhani"><?php esc_html_e( 'Lifetime Technical Support', 'industrial-welding' ); ?></h3>
                <p class="text-gray-400 text-sm">
                    <?php esc_html_e( 'Expert technical support available 24/7. Our certified engineers are standing by to help with any questions or troubleshooting.', 'industrial-welding' ); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-gray-900 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-1">
            <div class="bg-gray-900 rounded-xl p-8 md:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4 font-rajdhani">
                            <?php esc_html_e( 'Ready to Upgrade Your Shop?', 'industrial-welding' ); ?>
                        </h2>
                        <p class="text-gray-300 mb-8">
                            <?php esc_html_e( 'Get a custom quote for your facility. Our team will work with you to find the perfect equipment configuration for your specific applications.', 'industrial-welding' ); ?>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo esc_url( industrial_welding_get_contact_page_url() ); ?>" class="inline-flex items-center justify-center px-8 py-4 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-bold rounded transition-colors font-rajdhani tracking-wide">
                                <?php esc_html_e( 'Request a Quote', 'industrial-welding' ); ?>
                            </a>
                            <a href="tel:+18005551234" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-600 hover:border-white text-white font-bold rounded transition-colors font-rajdhani tracking-wide">
                                <?php esc_html_e( 'Call 1-800-555-1234', 'industrial-welding' ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="hidden lg:block">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-800 p-6 rounded-lg text-center border border-gray-700">
                                <span class="text-4xl font-bold text-yellow-500 font-rajdhani">3</span>
                                <p class="text-gray-400 text-sm mt-1"><?php esc_html_e( 'Year Warranty', 'industrial-welding' ); ?></p>
                            </div>
                            <div class="bg-gray-800 p-6 rounded-lg text-center border border-gray-700">
                                <span class="text-4xl font-bold text-yellow-500 font-rajdhani">24/7</span>
                                <p class="text-gray-400 text-sm mt-1"><?php esc_html_e( 'Support', 'industrial-welding' ); ?></p>
                            </div>
                            <div class="bg-gray-800 p-6 rounded-lg text-center border border-gray-700">
                                <span class="text-4xl font-bold text-yellow-500 font-rajdhani">50+</span>
                                <p class="text-gray-400 text-sm mt-1"><?php esc_html_e( 'Models Available', 'industrial-welding' ); ?></p>
                            </div>
                            <div class="bg-gray-800 p-6 rounded-lg text-center border border-gray-700">
                                <span class="text-4xl font-bold text-yellow-500 font-rajdhani">100%</span>
                                <p class="text-gray-400 text-sm mt-1"><?php esc_html_e( 'Factory Tested', 'industrial-welding' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
