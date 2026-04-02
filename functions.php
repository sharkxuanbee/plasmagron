<?php
/**
 * Industrial Welding Theme Functions
 *
 * @package Industrial_Welding
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function industrial_welding_scripts() {
    wp_enqueue_style(
        'industrial-welding-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_script(
        'industrial-welding-scripts',
        get_template_directory_uri() . '/assets/js/main.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );

    wp_localize_script( 'industrial-welding-scripts', 'industrialWelding', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'industrial_welding_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'industrial_welding_scripts' );

/**
 * Theme setup.
 */
function industrial_welding_setup() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo' );

    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'industrial-welding' ),
        'footer'    => esc_html__( 'Footer Menu', 'industrial-welding' ),
        'footer-secondary' => esc_html__( 'Footer Secondary Menu', 'industrial-welding' ),
    ) );

    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
}
add_action( 'after_setup_theme', 'industrial_welding_setup' );

/**
 * Register custom post type: Machines.
 */
function industrial_welding_register_machines_cpt() {
    $labels = array(
        'name'                  => _x( 'Machines', 'Post Type General Name', 'industrial-welding' ),
        'singular_name'         => _x( 'Machine', 'Post Type Singular Name', 'industrial-welding' ),
        'menu_name'             => __( 'Machines', 'industrial-welding' ),
        'name_admin_bar'        => __( 'Machine', 'industrial-welding' ),
        'archives'              => __( 'Machine Archives', 'industrial-welding' ),
        'attributes'            => __( 'Machine Attributes', 'industrial-welding' ),
        'parent_item_colon'     => __( 'Parent Machine:', 'industrial-welding' ),
        'all_items'             => __( 'All Machines', 'industrial-welding' ),
        'add_new_item'          => __( 'Add New Machine', 'industrial-welding' ),
        'add_new'               => __( 'Add New', 'industrial-welding' ),
        'new_item'              => __( 'New Machine', 'industrial-welding' ),
        'edit_item'             => __( 'Edit Machine', 'industrial-welding' ),
        'update_item'          => __( 'Update Machine', 'industrial-welding' ),
        'view_item'             => __( 'View Machine', 'industrial-welding' ),
        'view_items'           => __( 'View Machines', 'industrial-welding' ),
        'search_items'          => __( 'Search Machine', 'industrial-welding' ),
        'not_found'             => __( 'Not found', 'industrial-welding' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'industrial-welding' ),
        'featured_image'        => __( 'Machine Image', 'industrial-welding' ),
        'set_featured_image'    => __( 'Set machine image', 'industrial-welding' ),
        'remove_featured_image' => __( 'Remove machine image', 'industrial-welding' ),
        'use_featured_image'    => __( 'Use as machine image', 'industrial-welding' ),
    );
    $args = array(
        'label'               => __( 'Machine', 'industrial-welding' ),
        'description'         => __( 'Industrial welding machines and plasma cutters', 'industrial-welding' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-admin-generic',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rewrite'             => array( 'slug' => 'machines' ),
    );
    register_post_type( 'machines', $args );
}
add_action( 'init', 'industrial_welding_register_machines_cpt' );

/**
 * Register Custom Taxonomy: Machine Type.
 */
function industrial_welding_register_machine_type_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Machine Types', 'Taxonomy General Name', 'industrial-welding' ),
        'singular_name'              => _x( 'Machine Type', 'Taxonomy Singular Name', 'industrial-welding' ),
        'menu_name'                  => __( 'Machine Types', 'industrial-welding' ),
        'all_items'                  => __( 'All Machine Types', 'industrial-welding' ),
        'new_item_name'              => __( 'New Machine Type', 'industrial-welding' ),
        'add_new_item'               => __( 'Add New Machine Type', 'industrial-welding' ),
        'edit_item'                  => __( 'Edit Machine Type', 'industrial-welding' ),
        'update_item'                => __( 'Update Machine Type', 'industrial-welding' ),
        'view_item'                  => __( 'View Machine Type', 'industrial-welding' ),
        'separate_items_with_commas' => __( 'Separate machine types with commas', 'industrial-welding' ),
        'add_or_remove_items'        => __( 'Add or remove machine types', 'industrial-welding' ),
        'search_items'               => __( 'Search Machine Types', 'industrial-welding' ),
        'no_terms'                   => __( 'No machine types', 'industrial-welding' ),
    );
    $args = array(
        'labels'             => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'     => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'machine-type' ),
    );
    register_taxonomy( 'machine_type', array( 'machines' ), $args );
}
add_action( 'init', 'industrial_welding_register_machine_type_taxonomy' );

/**
 * Register Custom Meta Fields for Machines CPT.
 */
function industrial_welding_register_machine_meta() {
    $meta_fields = array(
        'input_voltage'    => array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest'      => true,
        ),
        'amperage'         => array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest'      => true,
        ),
        'duty_cycle'       => array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest'      => true,
        ),
        'weight'           => array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'show_in_rest'      => true,
        ),
        'best_for'         => array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'show_in_rest'      => true,
        ),
        'featured_machine' => array(
            'type'              => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'show_in_rest'      => true,
            'default'           => false,
        ),
        'download_pdf'     => array(
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'show_in_rest'      => true,
        ),
    );

    foreach ( $meta_fields as $key => $args ) {
        register_post_meta( 'machines', $key, $args );
    }
}
add_action( 'init', 'industrial_welding_register_machine_meta' );

/**
 * Register Widget Areas.
 */
function industrial_welding_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Footer Area One', 'industrial-welding' ),
        'id'            => 'footer-1',
        'description'   => __( 'Add widgets here.', 'industrial-welding' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="text-lg font-semibold text-yellow-500 mb-4">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Area Two', 'industrial-welding' ),
        'id'            => 'footer-2',
        'description'   => __( 'Add widgets here.', 'industrial-welding' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="text-lg font-semibold text-yellow-500 mb-4">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Area Three', 'industrial-welding' ),
        'id'            => 'footer-3',
        'description'   => __( 'Add widgets here.', 'industrial-welding' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="text-lg font-semibold text-yellow-500 mb-4">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'industrial_welding_widgets_init' );

/**
 * Custom excerpt length.
 */
function industrial_welding_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'industrial_welding_excerpt_length' );

/**
 * Custom excerpt more.
 */
function industrial_welding_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'industrial_welding_excerpt_more' );

/**
 * Get machine meta helper function.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param bool   $single  Whether to return a single value.
 * @return mixed
 */
function get_machine_meta( $post_id, $key, $single = true ) {
    return get_post_meta( $post_id, $key, $single );
}

/**
 * Theme activation: flush rewrite rules and create required pages.
 */
function industrial_welding_activate() {
    // Register CPT first so rewrite rules include it.
    industrial_welding_register_machines_cpt();
    industrial_welding_register_machine_type_taxonomy();

    // Flush so /machines/ permalink works immediately.
    flush_rewrite_rules();

    // Auto-create required pages if they don't exist.
    $pages = array(
        'compare' => array(
            'title'    => __( 'Compare Machines', 'industrial-welding' ),
            'template' => 'page-compare.php',
        ),
        'blog' => array(
            'title'    => __( 'Blog', 'industrial-welding' ),
            'template' => '',
        ),
        'contact' => array(
            'title'    => __( 'Contact Us', 'industrial-welding' ),
            'template' => '',
        ),
    );

    foreach ( $pages as $slug => $page_data ) {
        $existing = get_page_by_path( $slug );
        if ( ! $existing ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_data['title'],
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ) );

            if ( ! is_wp_error( $page_id ) && ! empty( $page_data['template'] ) ) {
                update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
            }
        }
    }

    // Set "Blog" page as the posts page if not already configured.
    $blog_page = get_page_by_path( 'blog' );
    if ( $blog_page && ! get_option( 'page_for_posts' ) ) {
        update_option( 'page_for_posts', $blog_page->ID );
        update_option( 'show_on_front', 'page' );

        // Also set front page to use the static front page.
        $front = get_page_by_path( 'front-page' );
        if ( ! $front ) {
            // Use the theme's front-page.php template automatically.
            // WordPress uses front-page.php when show_on_front = page.
            // We need a page assigned or WP falls back to index.
            // Setting page_on_front to 0 lets front-page.php template take over.
        }
    }

    // Create default nav menu with proper links.
    $menu_name   = __( 'Primary Menu', 'industrial-welding' );
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( ! $menu_exists ) {
        $menu_id = wp_create_nav_menu( $menu_name );

        if ( ! is_wp_error( $menu_id ) ) {
            // Home.
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'   => __( 'Home', 'industrial-welding' ),
                'menu-item-url'     => home_url( '/' ),
                'menu-item-status'  => 'publish',
                'menu-item-type'    => 'custom',
            ) );

            // Machines archive.
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'   => __( 'Machines', 'industrial-welding' ),
                'menu-item-url'     => home_url( '/machines/' ),
                'menu-item-status'  => 'publish',
                'menu-item-type'    => 'custom',
            ) );

            // Compare page.
            $compare_page = get_page_by_path( 'compare' );
            if ( $compare_page ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => __( 'Compare', 'industrial-welding' ),
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $compare_page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ) );
            }

            // Blog page.
            $blog_page = get_page_by_path( 'blog' );
            if ( $blog_page ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => __( 'Blog', 'industrial-welding' ),
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $blog_page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ) );
            }

            // Contact page.
            $contact_page = get_page_by_path( 'contact' );
            if ( $contact_page ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => __( 'Contact', 'industrial-welding' ),
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $contact_page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ) );
            }

            // Assign menu to primary location.
            $locations = get_theme_mod( 'nav_menu_locations' );
            if ( ! is_array( $locations ) ) {
                $locations = array();
            }
            $locations['primary'] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }
}
add_action( 'after_switch_theme', 'industrial_welding_activate' );

/**
 * Flush rewrite rules on init if our flag is set (backup method).
 */
function industrial_welding_maybe_flush_rules() {
    if ( get_option( 'industrial_welding_flush_rules' ) ) {
        flush_rewrite_rules();
        delete_option( 'industrial_welding_flush_rules' );
    }
}
add_action( 'init', 'industrial_welding_maybe_flush_rules', 99 );
