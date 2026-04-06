<?php
/**
 * Industrial Welding Theme Functions
 *
 * @package Industrial_Welding
 * @version 2.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether WooCommerce is active.
 *
 * @return bool
 */
function industrial_welding_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Compare query key.
 *
 * @return string
 */
function industrial_welding_get_compare_query_key() {
	return 'products';
}

/**
 * Get the compare page URL.
 *
 * @return string
 */
function industrial_welding_get_compare_page_url() {
	$page = get_page_by_path( 'compare' );

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/compare/' );
}

/**
 * Get the catalog URL. The WooCommerce shop page is branded as /machines/.
 *
 * @return string
 */
function industrial_welding_get_catalog_url() {
	if ( industrial_welding_is_woocommerce_active() ) {
		$shop_url = wc_get_page_permalink( 'shop' );

		if ( $shop_url ) {
			return $shop_url;
		}
	}

	$page = get_page_by_path( 'machines' );

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/machines/' );
}

/**
 * Get the contact page URL.
 *
 * @return string
 */
function industrial_welding_get_contact_page_url() {
	$page = get_page_by_path( 'contact' );

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/contact/' );
}

/**
 * Get a page URL by slug when the page exists.
 *
 * @param string $slug Page slug.
 * @return string
 */
function industrial_welding_get_page_url_by_path( $slug ) {
	$page = get_page_by_path( $slug );

	if ( $page ) {
		return get_permalink( $page );
	}

	return '';
}

/**
 * Shared navigation items for fallback menus and initial setup.
 *
 * @return array<int, array<string, string>>
 */
function industrial_welding_get_navigation_items() {
	$items = array(
		array(
			'label' => __( 'Home', 'industrial-welding' ),
			'url'   => home_url( '/' ),
		),
		array(
			'label' => __( 'Machines', 'industrial-welding' ),
			'url'   => industrial_welding_get_catalog_url(),
		),
	);

	$optional_pages = array(
		'compare' => __( 'Compare', 'industrial-welding' ),
		'blog'    => __( 'Blog', 'industrial-welding' ),
		'contact' => __( 'Contact', 'industrial-welding' ),
	);

	foreach ( $optional_pages as $slug => $label ) {
		$url = industrial_welding_get_page_url_by_path( $slug );

		if ( ! $url ) {
			continue;
		}

		$items[] = array(
			'label' => $label,
			'url'   => $url,
		);
	}

	return $items;
}

/**
 * Shared product meta configuration.
 *
 * @return array<string, array<string, mixed>>
 */
function industrial_welding_get_product_meta_config() {
	return array(
		'input_voltage'    => array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'field_type'        => 'text',
			'group'             => 'general',
			'label'             => __( 'Input Voltage', 'industrial-welding' ),
			'description'       => __( 'Example: 220V / 380V 3-Phase', 'industrial-welding' ),
		),
		'amperage'         => array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'field_type'        => 'text',
			'group'             => 'general',
			'label'             => __( 'Amperage', 'industrial-welding' ),
			'description'       => __( 'Example: 200A', 'industrial-welding' ),
		),
		'duty_cycle'       => array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'field_type'        => 'text',
			'group'             => 'general',
			'label'             => __( 'Duty Cycle', 'industrial-welding' ),
			'description'       => __( 'Example: 60% @ 200A', 'industrial-welding' ),
		),
		'weight'           => array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
			'field_type'        => 'text',
			'group'             => 'general',
			'label'             => __( 'Weight', 'industrial-welding' ),
			'description'       => __( 'Example: 42 lbs', 'industrial-welding' ),
		),
		'featured_machine' => array(
			'type'              => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'show_in_rest'      => true,
			'default'           => false,
			'field_type'        => 'checkbox',
			'group'             => 'general',
			'label'             => __( 'Homepage Featured Product', 'industrial-welding' ),
			'description'       => __( 'Show this product in homepage featured sections.', 'industrial-welding' ),
		),
		'rating'           => array(
			'type'              => 'number',
			'sanitize_callback' => 'floatval',
			'show_in_rest'      => true,
			'field_type'        => 'number',
			'group'             => 'general',
			'label'             => __( 'Displayed Rating', 'industrial-welding' ),
			'description'       => __( 'Optional manual rating override, for example 4.8.', 'industrial-welding' ),
			'custom_attributes' => array(
				'step' => '0.1',
				'min'  => '0',
				'max'  => '5',
			),
		),
		'review_count'     => array(
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'field_type'        => 'number',
			'group'             => 'general',
			'label'             => __( 'Displayed Review Count', 'industrial-welding' ),
			'description'       => __( 'Optional manual review count override.', 'industrial-welding' ),
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '0',
			),
		),
		'best_for'         => array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
			'show_in_rest'      => true,
			'field_type'        => 'textarea',
			'group'             => 'advanced',
			'label'             => __( 'Best For', 'industrial-welding' ),
			'description'       => __( 'Short guidance explaining who this product is best for.', 'industrial-welding' ),
		),
		'download_pdf'     => array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
			'field_type'        => 'url',
			'group'             => 'advanced',
			'label'             => __( 'Specification PDF URL', 'industrial-welding' ),
			'description'       => __( 'Direct URL to a PDF specification sheet.', 'industrial-welding' ),
		),
		'video_url'        => array(
			'type'              => 'string',
			'sanitize_callback' => 'esc_url_raw',
			'show_in_rest'      => true,
			'field_type'        => 'url',
			'group'             => 'advanced',
			'label'             => __( 'Demo Video URL', 'industrial-welding' ),
			'description'       => __( 'YouTube, Vimeo, or hosted video URL.', 'industrial-welding' ),
		),
		'warranty_text'    => array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_textarea_field',
			'show_in_rest'      => true,
			'field_type'        => 'textarea',
			'group'             => 'advanced',
			'label'             => __( 'Warranty Details', 'industrial-welding' ),
			'description'       => __( 'Short warranty summary shown on the product page.', 'industrial-welding' ),
		),
	);
}

/**
 * Get product spec labels used by the theme.
 *
 * @return array<string, string>
 */
function industrial_welding_get_product_spec_labels() {
	return array(
		'input_voltage' => __( 'Input Voltage', 'industrial-welding' ),
		'amperage'      => __( 'Amperage', 'industrial-welding' ),
		'duty_cycle'    => __( 'Duty Cycle', 'industrial-welding' ),
		'weight'        => __( 'Weight', 'industrial-welding' ),
	);
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
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_localize_script(
		'industrial-welding-scripts',
		'industrialWelding',
		array(
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'industrial_welding_nonce' ),
			'compareUrl'        => industrial_welding_get_compare_page_url(),
			'compareQueryKey'   => industrial_welding_get_compare_query_key(),
			'compareMinSelect'  => 2,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'industrial_welding_scripts' );

/**
 * Add Tailwind CSS CDN script to head.
 */
function industrial_welding_tailwind_cdn() {
	?>
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
	tailwind.config = {
		theme: {
			extend: {
				fontFamily: {
					rajdhani: ['Rajdhani', 'sans-serif'],
					roboto: ['Roboto', 'sans-serif'],
				},
				colors: {
					gray: {
						50:  '#f9fafb',
						100: '#f3f4f6',
						200: '#e5e7eb',
						300: '#d1d5db',
						400: '#9ca3af',
						500: '#6b7280',
						600: '#4b5563',
						700: '#374151',
						800: '#1f2937',
						900: '#111827',
						950: '#030712',
					},
				},
			},
		},
	}
	</script>
	<?php
}
add_action( 'wp_head', 'industrial_welding_tailwind_cdn', 1 );

/**
 * Theme setup.
 */
function industrial_welding_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary'          => esc_html__( 'Primary Menu', 'industrial-welding' ),
			'footer'           => esc_html__( 'Footer Menu', 'industrial-welding' ),
			'footer-secondary' => esc_html__( 'Footer Secondary Menu', 'industrial-welding' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'industrial_welding_setup' );

/**
 * Register custom product taxonomies used by the catalog.
 */
function industrial_welding_register_product_taxonomies() {
	if ( ! industrial_welding_is_woocommerce_active() ) {
		return;
	}

	$taxonomies = array(
		'usage_scene'  => array(
			'plural'   => __( 'Usage Scenes', 'industrial-welding' ),
			'singular' => __( 'Usage Scene', 'industrial-welding' ),
			'slug'     => 'usage-scene',
		),
		'skill_level' => array(
			'plural'   => __( 'Skill Levels', 'industrial-welding' ),
			'singular' => __( 'Skill Level', 'industrial-welding' ),
			'slug'     => 'skill-level',
		),
		'budget_range' => array(
			'plural'   => __( 'Budget Ranges', 'industrial-welding' ),
			'singular' => __( 'Budget Range', 'industrial-welding' ),
			'slug'     => 'budget-range',
		),
	);

	foreach ( $taxonomies as $taxonomy => $config ) {
		$labels = array(
			'name'              => $config['plural'],
			'singular_name'     => $config['singular'],
			'search_items'      => sprintf( __( 'Search %s', 'industrial-welding' ), $config['plural'] ),
			'all_items'         => sprintf( __( 'All %s', 'industrial-welding' ), $config['plural'] ),
			'edit_item'         => sprintf( __( 'Edit %s', 'industrial-welding' ), $config['singular'] ),
			'update_item'       => sprintf( __( 'Update %s', 'industrial-welding' ), $config['singular'] ),
			'add_new_item'      => sprintf( __( 'Add New %s', 'industrial-welding' ), $config['singular'] ),
			'new_item_name'     => sprintf( __( 'New %s Name', 'industrial-welding' ), $config['singular'] ),
			'menu_name'         => $config['plural'],
		);

		register_taxonomy(
			$taxonomy,
			array( 'product' ),
			array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud'     => false,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => $config['slug'] ),
			)
		);
	}
}
add_action( 'init', 'industrial_welding_register_product_taxonomies' );

/**
 * Register custom product meta fields.
 */
function industrial_welding_register_product_meta() {
	foreach ( industrial_welding_get_product_meta_config() as $key => $config ) {
		register_post_meta(
			'product',
			$key,
			array(
				'type'              => $config['type'],
				'single'            => true,
				'default'           => isset( $config['default'] ) ? $config['default'] : '',
				'sanitize_callback' => $config['sanitize_callback'],
				'show_in_rest'      => $config['show_in_rest'],
			)
		);
	}
}
add_action( 'init', 'industrial_welding_register_product_meta' );

/**
 * Register widget areas.
 */
function industrial_welding_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Area One', 'industrial-welding' ),
			'id'            => 'footer-1',
			'description'   => __( 'Add widgets here.', 'industrial-welding' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="text-lg font-semibold text-yellow-500 mb-4">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Area Two', 'industrial-welding' ),
			'id'            => 'footer-2',
			'description'   => __( 'Add widgets here.', 'industrial-welding' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="text-lg font-semibold text-yellow-500 mb-4">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer Area Three', 'industrial-welding' ),
			'id'            => 'footer-3',
			'description'   => __( 'Add widgets here.', 'industrial-welding' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="text-lg font-semibold text-yellow-500 mb-4">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'industrial_welding_widgets_init' );

/**
 * Custom excerpt length.
 *
 * @param int $length Current length.
 * @return int
 */
function industrial_welding_excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', 'industrial_welding_excerpt_length' );

/**
 * Custom excerpt more.
 *
 * @param string $more Current string.
 * @return string
 */
function industrial_welding_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'industrial_welding_excerpt_more' );

/**
 * Product meta helper function.
 *
 * @param int    $post_id Product ID.
 * @param string $key     Meta key.
 * @param bool   $single  Whether to return a single value.
 * @return mixed
 */
function industrial_welding_get_product_meta( $post_id, $key, $single = true ) {
	return get_post_meta( $post_id, $key, $single );
}

/**
 * Get the primary product category.
 *
 * @param int $product_id Product ID.
 * @return WP_Term|null
 */
function industrial_welding_get_primary_product_term( $product_id ) {
	$terms = get_the_terms( $product_id, 'product_cat' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	return array_shift( $terms );
}

/**
 * Get featured product IDs for homepage sections.
 *
 * @return int[]
 */
function industrial_welding_get_featured_product_ids() {
	$featured_ids = array();

	if ( industrial_welding_is_woocommerce_active() ) {
		$featured_ids = array_map( 'absint', wc_get_featured_product_ids() );
	}

	if ( empty( $featured_ids ) ) {
		$fallback_query = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 6,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'   => 'featured_machine',
						'value' => '1',
					),
				),
			)
		);

		$featured_ids = array_map( 'absint', $fallback_query->posts );
	}

	return array_values( array_filter( array_unique( $featured_ids ) ) );
}

/**
 * Get featured product query args.
 *
 * @param int $limit Maximum products to fetch.
 * @return array<string, mixed>
 */
function industrial_welding_get_featured_products_query_args( $limit = 3 ) {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
	);

	$featured_ids = industrial_welding_get_featured_product_ids();

	if ( ! empty( $featured_ids ) ) {
		$args['post__in'] = array_slice( $featured_ids, 0, $limit );
		$args['orderby']  = 'post__in';
	} else {
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
	}

	return $args;
}

/**
 * Get the assigned menu ID for a menu location.
 *
 * @param string $location Menu location.
 * @return int
 */
function industrial_welding_get_menu_location_id( $location ) {
	$locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! is_array( $locations ) || empty( $locations[ $location ] ) ) {
		return 0;
	}

	return absint( $locations[ $location ] );
}

/**
 * Assign a menu to theme locations that are still empty.
 *
 * @param int $menu_id Menu ID.
 */
function industrial_welding_assign_menu_locations( $menu_id ) {
	if ( ! $menu_id ) {
		return;
	}

	$locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	if ( empty( $locations['primary'] ) ) {
		$locations['primary'] = $menu_id;
	}

	if ( empty( $locations['footer'] ) ) {
		$locations['footer'] = $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Ensure a page exists.
 *
 * @param string $slug     Page slug.
 * @param string $title    Page title.
 * @param string $template Optional page template filename.
 * @return int
 */
function industrial_welding_ensure_page( $slug, $title, $template = '' ) {
	$page = get_page_by_path( $slug );

	if ( ! $page ) {
		$page_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			)
		);
	} else {
		$page_id = $page->ID;
	}

	if ( ! is_wp_error( $page_id ) && $template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}

	return is_wp_error( $page_id ) ? 0 : absint( $page_id );
}

/**
 * Ensure the WooCommerce shop page exists and uses the /machines/ slug.
 *
 * @return int
 */
function industrial_welding_ensure_shop_page() {
	$shop_page_id = industrial_welding_ensure_page(
		'machines',
		__( 'Machines', 'industrial-welding' )
	);

	if ( industrial_welding_is_woocommerce_active() && $shop_page_id ) {
		if ( absint( get_option( 'woocommerce_shop_page_id' ) ) !== $shop_page_id ) {
			update_option( 'woocommerce_shop_page_id', $shop_page_id );
		}
	}

	return $shop_page_id;
}

/**
 * Ensure required core pages exist.
 */
function industrial_welding_ensure_core_pages() {
	industrial_welding_ensure_shop_page();

	$pages = array(
		'compare' => array(
			'title'    => __( 'Compare Machines', 'industrial-welding' ),
			'template' => 'page-compare.php',
		),
		'blog'    => array(
			'title'    => __( 'Blog', 'industrial-welding' ),
			'template' => '',
		),
		'contact' => array(
			'title'    => __( 'Contact Us', 'industrial-welding' ),
			'template' => '',
		),
	);

	foreach ( $pages as $slug => $page_data ) {
		industrial_welding_ensure_page( $slug, $page_data['title'], $page_data['template'] );
	}
}

/**
 * Create a default navigation menu when the theme is first activated.
 *
 * @return int
 */
function industrial_welding_create_default_menu() {
	$primary_menu_id = industrial_welding_get_menu_location_id( 'primary' );

	if ( $primary_menu_id ) {
		industrial_welding_assign_menu_locations( $primary_menu_id );
		return $primary_menu_id;
	}

	$menu_name   = __( 'Primary Menu', 'industrial-welding' );
	$menu_exists = wp_get_nav_menu_object( $menu_name );

	if ( $menu_exists ) {
		$menu_id = absint( $menu_exists->term_id );
		industrial_welding_assign_menu_locations( $menu_id );
		return $menu_id;
	}

	$menu_id = wp_create_nav_menu( $menu_name );

	if ( is_wp_error( $menu_id ) ) {
		return 0;
	}

	foreach ( industrial_welding_get_navigation_items() as $item ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $item['label'],
				'menu-item-url'    => $item['url'],
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			)
		);
	}

	industrial_welding_assign_menu_locations( absint( $menu_id ) );

	return absint( $menu_id );
}

/**
 * Get the current theme version for one-time migrations.
 *
 * @return string
 */
function industrial_welding_get_theme_version() {
	return (string) wp_get_theme()->get( 'Version' );
}

/**
 * Initial theme activation hook.
 */
function industrial_welding_activate() {
	update_option( 'industrial_welding_setup_required', true );
	update_option( 'industrial_welding_flush_rules', true );
}
add_action( 'after_switch_theme', 'industrial_welding_activate' );

/**
 * Run one-time theme setup after activation.
 */
function industrial_welding_maybe_run_setup() {
	if ( ! get_option( 'industrial_welding_setup_required' ) ) {
		return;
	}

	industrial_welding_ensure_core_pages();
	industrial_welding_create_default_menu();
	update_option( 'industrial_welding_theme_version', industrial_welding_get_theme_version() );

	delete_option( 'industrial_welding_setup_required' );
}
add_action( 'init', 'industrial_welding_maybe_run_setup', 20 );

/**
 * Run one-time upgrade tasks when the theme version changes.
 */
function industrial_welding_maybe_run_upgrade() {
	$current_version = industrial_welding_get_theme_version();
	$stored_version  = (string) get_option( 'industrial_welding_theme_version', '0.0.0' );

	if ( ! $current_version || version_compare( $stored_version, $current_version, '>=' ) ) {
		return;
	}

	industrial_welding_ensure_core_pages();
	industrial_welding_create_default_menu();
	update_option( 'industrial_welding_flush_rules', true );
	update_option( 'industrial_welding_theme_version', $current_version );
}
add_action( 'init', 'industrial_welding_maybe_run_upgrade', 25 );

/**
 * Ensure the shop page remains wired to WooCommerce.
 */
function industrial_welding_maybe_sync_shop_page() {
	if ( ! industrial_welding_is_woocommerce_active() ) {
		return;
	}

	if ( wc_get_page_id( 'shop' ) > 0 ) {
		return;
	}

	industrial_welding_ensure_shop_page();
	update_option( 'industrial_welding_flush_rules', true );
}
add_action( 'admin_init', 'industrial_welding_maybe_sync_shop_page' );

/**
 * Flush rewrite rules on init if our flag is set.
 */
function industrial_welding_maybe_flush_rules() {
	if ( get_option( 'industrial_welding_flush_rules' ) ) {
		flush_rewrite_rules();
		delete_option( 'industrial_welding_flush_rules' );
	}
}
add_action( 'init', 'industrial_welding_maybe_flush_rules', 99 );

/**
 * Add custom WooCommerce product fields.
 */
function industrial_welding_add_product_fields() {
	if ( ! industrial_welding_is_woocommerce_active() ) {
		return;
	}

	echo '<div class="options_group">';

	foreach ( industrial_welding_get_product_meta_config() as $key => $config ) {
		if ( 'general' !== $config['group'] ) {
			continue;
		}

		industrial_welding_render_product_field( $key, $config );
	}

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'industrial_welding_add_product_fields' );

/**
 * Add advanced WooCommerce product fields.
 */
function industrial_welding_add_product_advanced_fields() {
	if ( ! industrial_welding_is_woocommerce_active() ) {
		return;
	}

	echo '<div class="options_group">';

	foreach ( industrial_welding_get_product_meta_config() as $key => $config ) {
		if ( 'advanced' !== $config['group'] ) {
			continue;
		}

		industrial_welding_render_product_field( $key, $config );
	}

	echo '</div>';
}
add_action( 'woocommerce_product_options_advanced', 'industrial_welding_add_product_advanced_fields' );

/**
 * Render a WooCommerce product field.
 *
 * @param string $key    Field key.
 * @param array  $config Field config.
 */
function industrial_welding_render_product_field( $key, $config ) {
	$args = array(
		'id'                => $key,
		'label'             => $config['label'],
		'description'       => $config['description'],
		'desc_tip'          => true,
		'custom_attributes' => isset( $config['custom_attributes'] ) ? $config['custom_attributes'] : array(),
		'type'              => $config['field_type'],
	);

	switch ( $config['field_type'] ) {
		case 'checkbox':
			woocommerce_wp_checkbox( $args );
			break;

		case 'textarea':
			woocommerce_wp_textarea_input( $args );
			break;

		default:
			woocommerce_wp_text_input( $args );
			break;
	}
}

/**
 * Save custom WooCommerce product fields.
 *
 * @param WC_Product $product Product object.
 */
function industrial_welding_save_product_fields( $product ) {
	foreach ( industrial_welding_get_product_meta_config() as $key => $config ) {
		if ( 'checkbox' === $config['field_type'] ) {
			$product->update_meta_data( $key, isset( $_POST[ $key ] ) ? '1' : '0' );
			continue;
		}

		$raw_value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';

		if ( '' === $raw_value ) {
			$product->delete_meta_data( $key );
			continue;
		}

		$sanitized = call_user_func( $config['sanitize_callback'], $raw_value );
		$product->update_meta_data( $key, $sanitized );
	}
}
add_action( 'woocommerce_admin_process_product_object', 'industrial_welding_save_product_fields' );

/**
 * Redirect legacy machine URLs to their WooCommerce equivalents.
 */
function industrial_welding_redirect_legacy_machine_urls() {
	if ( is_admin() || ! is_404() ) {
		return;
	}

	global $wp;

	if ( empty( $wp->request ) ) {
		return;
	}

	$segments = explode( '/', trim( $wp->request, '/' ) );

	if ( empty( $segments[0] ) ) {
		return;
	}

	if ( 'machine-type' === $segments[0] && ! empty( $segments[1] ) ) {
		$term = get_term_by( 'slug', $segments[1], 'product_cat' );

		if ( $term && ! is_wp_error( $term ) ) {
			wp_safe_redirect( get_term_link( $term ), 301 );
			exit;
		}
	}

	if ( 'machines' === $segments[0] && ! empty( $segments[1] ) ) {
		$product = get_page_by_path( $segments[1], OBJECT, 'product' );

		if ( $product ) {
			wp_safe_redirect( get_permalink( $product ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'industrial_welding_redirect_legacy_machine_urls' );
