<?php
/**
 * The template for displaying the Machines archive page
 *
 * @package Industrial_Welding
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<section class="bg-gray-900 py-16 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 font-rajdhani">
                <?php post_type_archive_title(); ?>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e( 'Professional-grade welding machines and plasma cutters for every industrial application. Explore our complete lineup below.', 'industrial-welding' ); ?>
            </p>
        </div>
    </div>
</section>

<section class="bg-gray-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ( have_posts() ) : ?>
            <div class="flex flex-col lg:flex-row gap-8 mb-12">
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 sticky top-24">
                        <h3 class="text-lg font-semibold text-white mb-4 font-rajdhani">
                            <?php esc_html_e( 'Filter by Type', 'industrial-welding' ); ?>
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="<?php echo esc_url( get_post_type_archive_link( 'machines' ) ); ?>" 
                                   class="flex items-center justify-between px-3 py-2 text-gray-300 hover:text-yellow-500 hover:bg-gray-700 rounded transition-colors <?php echo ! is_tax( 'machine_type' ) ? 'text-yellow-500 bg-gray-700' : ''; ?>">
                                    <span><?php esc_html_e( 'All Machines', 'industrial-welding' ); ?></span>
                                    <span class="text-xs bg-gray-600 px-2 py-1 rounded">
                                        <?php echo wp_count_posts( 'machines' )->publish; ?>
                                    </span>
                                </a>
                            </li>
                            <?php
                            $terms = get_terms( array(
                                'taxonomy'   => 'machine_type',
                                'hide_empty' => true,
                            ) );
                            
                            if ( $terms && ! is_wp_error( $terms ) ) :
                                foreach ( $terms as $term ) :
                                    $term_link = get_term_link( $term );
                                    $is_current = is_tax( 'machine_type', $term->term_id );
                                    ?>
                                    <li>
                                        <a href="<?php echo esc_url( $term_link ); ?>" 
                                           class="flex items-center justify-between px-3 py-2 text-gray-300 hover:text-yellow-500 hover:bg-gray-700 rounded transition-colors <?php echo $is_current ? 'text-yellow-500 bg-gray-700' : ''; ?>">
                                            <span><?php echo esc_html( $term->name ); ?></span>
                                            <span class="text-xs bg-gray-600 px-2 py-1 rounded">
                                                <?php echo esc_html( $term->count ); ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                </aside>

                <div class="flex-grow">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            
                            $machine_meta = array(
                                'amperage'     => get_machine_meta( get_the_ID(), 'amperage' ),
                                'input_voltage' => get_machine_meta( get_the_ID(), 'input_voltage' ),
                                'duty_cycle'  => get_machine_meta( get_the_ID(), 'duty_cycle' ),
                                'weight'       => get_machine_meta( get_the_ID(), 'weight' ),
                            );
                            ?>
                            <article id="machine-<?php the_ID(); ?>" <?php post_class( 'bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-yellow-500/50 transition-all duration-300 group' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="relative overflow-hidden">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <?php 
                                            the_post_thumbnail( 'medium_large', array( 
                                                'class' => 'w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500'
                                            ) ); 
                                            ?>
                                        </div>
                                        <?php
                                        $terms = get_the_terms( get_the_ID(), 'machine_type' );
                                        if ( $terms && ! is_wp_error( $terms ) ) :
                                            ?>
                                            <div class="absolute top-4 left-4">
                                                <span class="inline-flex items-center px-3 py-1 bg-gray-900/80 backdrop-blur text-yellow-500 text-xs font-bold uppercase tracking-wider rounded font-rajdhani">
                                                    <?php echo esc_html( $terms[0]->name ); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="p-6">
                                    <h2 class="text-xl font-bold text-white mb-3 font-rajdhani group-hover:text-yellow-500 transition-colors line-clamp-1">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>

                                    <?php if ( has_excerpt() ) : ?>
                                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                            <?php the_excerpt(); ?>
                                        </p>
                                    <?php endif; ?>

                                    <div class="space-y-2 mb-6">
                                        <?php if ( $machine_meta['amperage'] ) : ?>
                                            <div class="flex items-center text-sm">
                                                <span class="text-gray-500 w-28"><?php esc_html_e( 'Amperage', 'industrial-welding' ); ?>:</span>
                                                <span class="text-gray-300 font-medium"><?php echo esc_html( $machine_meta['amperage'] ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ( $machine_meta['input_voltage'] ) : ?>
                                            <div class="flex items-center text-sm">
                                                <span class="text-gray-500 w-28"><?php esc_html_e( 'Voltage', 'industrial-welding' ); ?>:</span>
                                                <span class="text-gray-300 font-medium"><?php echo esc_html( $machine_meta['input_voltage'] ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ( $machine_meta['duty_cycle'] ) : ?>
                                            <div class="flex items-center text-sm">
                                                <span class="text-gray-500 w-28"><?php esc_html_e( 'Duty Cycle', 'industrial-welding' ); ?>:</span>
                                                <span class="text-gray-300 font-medium"><?php echo esc_html( $machine_meta['duty_cycle'] ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-700 hover:bg-yellow-500 hover:text-gray-900 text-white font-semibold rounded transition-colors font-rajdhani tracking-wide group">
                                        <span><?php esc_html_e( 'View Specs', 'industrial-welding' ); ?></span>
                                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                            <?php
                        endwhile;
                        ?>
                    </div>

                    <div class="mt-12">
                        <?php
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => sprintf(
                                '<span class="inline-flex items-center px-3 py-2 bg-gray-800 text-gray-300 hover:text-yellow-500 rounded transition-colors">%s</span>',
                                '&larr; Previous'
                            ),
                            'next_text' => sprintf(
                                '<span class="inline-flex items-center px-3 py-2 bg-gray-800 text-gray-300 hover:text-yellow-500 rounded transition-colors">%s</span>',
                                'Next &rarr;'
                            ),
                            'screen_reader_text' => __( 'Pagination', 'industrial-welding' ),
                        ) );
                        ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-white mb-2 font-rajdhani">
                    <?php esc_html_e( 'No Machines Found', 'industrial-welding' ); ?>
                </h2>
                <p class="text-gray-400 mb-8">
                    <?php esc_html_e( 'No machines are currently available. Please check back soon.', 'industrial-welding' ); ?>
                </p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-semibold rounded transition-colors">
                    <?php esc_html_e( 'Return to Homepage', 'industrial-welding' ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
