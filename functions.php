<?php
/**
 * Industrial Welding Theme Functions
 *
 * @package Industrial_Welding
 * @version 2.2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the preferred public site host.
 *
 * @return string
 */
function industrial_welding_get_preferred_site_host() {
	return 'plasmargon.com';
}

/**
 * Get legacy hosts that should resolve to the preferred domain.
 *
 * @return string[]
 */
function industrial_welding_get_legacy_site_hosts() {
	return array(
		'plasmagron.com',
		'www.plasmagron.com',
		'www.plasmargon.com',
	);
}

/**
 * Normalize legacy site URLs to the preferred production domain.
 *
 * @param string $url URL to normalize.
 * @return string
 */
function industrial_welding_normalize_site_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return $url;
	}

	$normalized = preg_replace(
		array(
			'#^(https?:)?//(?:www\.)?plasmagron\.com(?=[/:?\#]|$)#i',
			'#^(https?:)?//www\.plasmargon\.com(?=[/:?\#]|$)#i',
		),
		array(
			'$1//plasmargon.com',
			'$1//plasmargon.com',
		),
		$url,
		1
	);

	return is_string( $normalized ) && '' !== $normalized ? $normalized : $url;
}

/**
 * Filter URLs so old-domain links still resolve after the domain migration.
 *
 * @param string $url URL to filter.
 * @return string
 */
function industrial_welding_filter_normalized_url( $url ) {
	return industrial_welding_normalize_site_url( $url );
}
add_filter( 'home_url', 'industrial_welding_filter_normalized_url' );
add_filter( 'site_url', 'industrial_welding_filter_normalized_url' );
add_filter( 'content_url', 'industrial_welding_filter_normalized_url' );
add_filter( 'plugins_url', 'industrial_welding_filter_normalized_url' );
add_filter( 'theme_file_uri', 'industrial_welding_filter_normalized_url' );
add_filter( 'stylesheet_directory_uri', 'industrial_welding_filter_normalized_url' );
add_filter( 'template_directory_uri', 'industrial_welding_filter_normalized_url' );
add_filter( 'script_loader_src', 'industrial_welding_filter_normalized_url' );
add_filter( 'style_loader_src', 'industrial_welding_filter_normalized_url' );
add_filter( 'rest_url', 'industrial_welding_filter_normalized_url' );

/**
 * Normalize home/site options when they still contain the legacy domain.
 *
 * @param mixed $value Option value.
 * @return mixed
 */
function industrial_welding_filter_normalized_site_option( $value ) {
	if ( ! is_string( $value ) || '' === $value ) {
		return $value;
	}

	return industrial_welding_normalize_site_url( $value );
}
add_filter( 'option_home', 'industrial_welding_filter_normalized_site_option' );
add_filter( 'option_siteurl', 'industrial_welding_filter_normalized_site_option' );

/**
 * Normalize upload URLs when WordPress still stores the old host.
 *
 * @param array<string, string> $uploads Upload directory config.
 * @return array<string, string>
 */
function industrial_welding_filter_upload_dir_urls( $uploads ) {
	if ( isset( $uploads['baseurl'] ) ) {
		$uploads['baseurl'] = industrial_welding_normalize_site_url( $uploads['baseurl'] );
	}

	if ( isset( $uploads['url'] ) ) {
		$uploads['url'] = industrial_welding_normalize_site_url( $uploads['url'] );
	}

	return $uploads;
}
add_filter( 'upload_dir', 'industrial_welding_filter_upload_dir_urls' );

/**
 * Normalize WordPress menu item links when menus still store the old domain.
 *
 * @param string $host Host to check.
 * @return bool
 */
function industrial_welding_is_internal_site_host( $host ) {
	$host = strtolower( (string) $host );

	return '' === $host
		|| industrial_welding_get_preferred_site_host() === $host
		|| in_array( $host, industrial_welding_get_legacy_site_hosts(), true );
}

/**
 * Get the canonical URL for common internal menu labels.
 *
 * @param string $label Menu label.
 * @return string
 */
function industrial_welding_get_internal_menu_url_by_label( $label ) {
	$normalized_label = strtolower( trim( wp_strip_all_tags( (string) $label ) ) );
	$normalized_label = preg_replace( '/\s+/', ' ', $normalized_label );
	$normalized_label = is_string( $normalized_label ) ? $normalized_label : '';

	switch ( $normalized_label ) {
		case 'home':
			return home_url( '/' );

		case 'machines':
		case 'browse machines':
		case 'catalog overview':
		case 'filterable category paths':
		case 'browse all machines':
			return industrial_welding_get_catalog_url();

		case 'finder':
		case 'open finder':
		case 'start finder':
		case 'welder finder':
			return industrial_welding_get_finder_page_url();

		case 'compare':
		case 'compare shortlist':
		case 'compare shortlists':
		case 'quick compare':
			return industrial_welding_get_compare_page_url();

		case 'contact':
		case 'request quote':
		case 'documentation or bulk quote':
		case 'privacy':
		case 'terms':
		case 'support':
			return industrial_welding_get_contact_page_url();

		case 'blog':
			return industrial_welding_get_blog_page_url();

		default:
			return '';
	}
}

/**
 * Resolve stale custom menu URLs to the current site routes.
 *
 * @param string   $url  Original URL.
 * @param WP_Post|null $item Menu item when available.
 * @return string
 */
function industrial_welding_resolve_menu_item_url( $url, $item = null ) {
	$normalized_url = industrial_welding_normalize_site_url( $url );
	$parsed_url     = wp_parse_url( $normalized_url );
	$host           = is_array( $parsed_url ) && isset( $parsed_url['host'] ) ? strtolower( $parsed_url['host'] ) : '';

	if ( ! industrial_welding_is_internal_site_host( $host ) ) {
		return $normalized_url;
	}

	$fallback_url = '';

	if ( $item && isset( $item->title ) ) {
		$fallback_url = industrial_welding_get_internal_menu_url_by_label( $item->title );
	}

	if ( $item && isset( $item->type ) && 'custom' === $item->type && $fallback_url ) {
		return $fallback_url;
	}

	if ( is_array( $parsed_url ) && ! empty( $parsed_url['query'] ) ) {
		parse_str( $parsed_url['query'], $query_args );

		if ( ! empty( $query_args['page_id'] ) ) {
			$page_url = get_permalink( absint( $query_args['page_id'] ) );

			if ( $page_url ) {
				return industrial_welding_normalize_site_url( $page_url );
			}

			if ( $fallback_url ) {
				return $fallback_url;
			}
		}
	}

	return $fallback_url ? $fallback_url : $normalized_url;
}

/**
 * Normalize WordPress menu item links when menus still store the old domain.
 *
 * @param array<string, string> $atts Menu item attributes.
 * @param WP_Post               $item Menu item object.
 * @return array<string, string>
 */
function industrial_welding_filter_menu_link_attributes( $atts, $item ) {
	if ( isset( $atts['href'] ) ) {
		$atts['href'] = industrial_welding_resolve_menu_item_url( $atts['href'], $item );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'industrial_welding_filter_menu_link_attributes', 10, 2 );

/**
 * Allow redirects to both the preferred and legacy hosts during migration.
 *
 * @param string[] $hosts Allowed hosts.
 * @return string[]
 */
function industrial_welding_filter_allowed_redirect_hosts( $hosts ) {
	return array_values(
		array_unique(
			array_merge(
				$hosts,
				industrial_welding_get_legacy_site_hosts(),
				array( industrial_welding_get_preferred_site_host() )
			)
		)
	);
}
add_filter( 'allowed_redirect_hosts', 'industrial_welding_filter_allowed_redirect_hosts' );

/**
 * Redirect legacy-domain requests to the preferred domain.
 */
function industrial_welding_redirect_legacy_domain() {
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}

	$request_host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) ) : '';

	if ( '' === $request_host || ! in_array( $request_host, industrial_welding_get_legacy_site_hosts(), true ) ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$target_url  = industrial_welding_normalize_site_url( 'https://' . $request_host . $request_uri );

	if ( $target_url ) {
		wp_safe_redirect( $target_url, 301, 'Industrial Welding' );
		exit;
	}
}
add_action( 'template_redirect', 'industrial_welding_redirect_legacy_domain', 0 );

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
 * Minimum product count required for a full compare view.
 *
 * @return int
 */
function industrial_welding_get_compare_min_selection() {
	return 2;
}

/**
 * Browser storage key for compare shortlist persistence.
 *
 * @return string
 */
function industrial_welding_get_compare_storage_key() {
	return 'industrial_welding_compare_shortlist';
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
 * Build a compare URL from a shortlist of product IDs.
 *
 * @param int[] $product_ids Product IDs.
 * @return string
 */
function industrial_welding_get_compare_url_for_ids( $product_ids ) {
	$product_ids = array_values(
		array_filter(
			array_unique(
				array_map( 'absint', (array) $product_ids )
			)
		)
	);

	if ( empty( $product_ids ) ) {
		return industrial_welding_get_compare_page_url();
	}

	return add_query_arg(
		industrial_welding_get_compare_query_key(),
		implode( ',', $product_ids ),
		industrial_welding_get_compare_page_url()
	);
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
 * Get the blog landing URL, preferring the configured posts page.
 *
 * @return string
 */
function industrial_welding_get_blog_page_url() {
	$posts_page_id = absint( get_option( 'page_for_posts' ) );

	if ( $posts_page_id > 0 ) {
		$posts_page_url = get_permalink( $posts_page_id );

		if ( $posts_page_url ) {
			return $posts_page_url;
		}
	}

	$page = get_page_by_path( 'blog' );

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/blog/' );
}

/**
 * Get the Finder page URL.
 *
 * @return string
 */
function industrial_welding_get_finder_page_url() {
	$page = get_page_by_path( 'finder' );

	if ( $page ) {
		return get_permalink( $page );
	}

	return home_url( '/finder/' );
}

/**
 * Get the shared singular or plural machine label.
 *
 * @param bool $plural Whether to return the plural form.
 * @return string
 */
function industrial_welding_get_machine_label( $plural = false ) {
	return $plural
		? __( 'Machines', 'industrial-welding' )
		: __( 'Machine', 'industrial-welding' );
}

/**
 * Get the public-facing brand name.
 *
 * @return string
 */
function industrial_welding_get_brand_name() {
	return 'Plasmargon';
}

/**
 * Get the shared short brand introduction.
 *
 * @return string
 */
function industrial_welding_get_brand_intro() {
	return 'Plasmargon is a professional welding and plasma cutting equipment brand focused on delivering reliable, high-performance solutions for metalworking applications worldwide. Built around modern inverter technology and precision control systems, our equipment supports professionals and advanced DIY users with stable performance, multi-process flexibility, and practical usability.';
}

/**
 * Get the shared request quote label.
 *
 * @return string
 */
function industrial_welding_get_request_quote_label() {
	return __( 'Request Quote', 'industrial-welding' );
}

/**
 * Get the sales phone label.
 *
 * @return string
 */
function industrial_welding_get_contact_phone_label() {
	return '+1 (312) 330-0886';
}

/**
 * Get the sales phone href.
 *
 * @return string
 */
function industrial_welding_get_contact_phone_href() {
	return 'tel:+13123300886';
}

/**
 * Get the sales email address.
 *
 * @return string
 */
function industrial_welding_get_contact_email() {
	return 'tonsen1682025@163.com';
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
			'label' => industrial_welding_get_machine_label( true ),
			'url'   => industrial_welding_get_catalog_url(),
		),
	);

	$optional_pages = array(
		'finder'  => __( 'Finder', 'industrial-welding' ),
		'compare' => __( 'Compare', 'industrial-welding' ),
		'blog'    => __( 'Blog', 'industrial-welding' ),
		'contact' => __( 'Contact', 'industrial-welding' ),
	);

	foreach ( $optional_pages as $slug => $label ) {
		if ( 'blog' === $slug ) {
			$url = industrial_welding_get_blog_page_url();
		} else {
			$url = industrial_welding_get_page_url_by_path( $slug );
		}

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
 * Get a clean product summary for cards and supporting sections.
 *
 * @param WC_Product|int $product Product object or ID.
 * @param int            $length  Maximum words to keep.
 * @return string
 */
function industrial_welding_get_product_summary( $product, $length = 24 ) {
	$product_object = null;

	if ( class_exists( 'WC_Product' ) && $product instanceof WC_Product ) {
		$product_object = $product;
	} elseif ( function_exists( 'wc_get_product' ) ) {
		$product_object = wc_get_product( $product );
	}

	if ( ! $product_object ) {
		return '';
	}

	$summary = wp_strip_all_tags( $product_object->get_short_description() );

	if ( ! $summary ) {
		$post = get_post( $product_object->get_id() );
		$summary = $post ? wp_strip_all_tags( $post->post_excerpt ) : '';
	}

	if ( ! $summary ) {
		$post = get_post( $product_object->get_id() );
		$summary = $post ? wp_strip_all_tags( $post->post_content ) : '';
	}

	return $summary ? wp_trim_words( $summary, $length ) : '';
}

/**
 * Get a safe post summary for archive-style listings without rendering blocks.
 *
 * @param WP_Post|int $post   Post object or ID.
 * @param int         $length Maximum words to keep.
 * @return string
 */
function industrial_welding_get_post_summary( $post, $length = 24 ) {
	$post_object = $post instanceof WP_Post ? $post : get_post( $post );

	if ( ! $post_object instanceof WP_Post ) {
		return '';
	}

	$summary = trim( wp_strip_all_tags( (string) $post_object->post_excerpt ) );

	if ( '' === $summary ) {
		$summary = trim( wp_strip_all_tags( strip_shortcodes( (string) $post_object->post_content ) ) );
	}

	return '' !== $summary ? wp_trim_words( $summary, $length ) : '';
}

/**
 * Get labeled product specs and filter empty values.
 *
 * @param int          $product_id Product ID.
 * @param string[]|nil $keys       Optional subset of spec keys.
 * @return array<int, array<string, string>>
 */
function industrial_welding_get_product_spec_entries( $product_id, $keys = null ) {
	$labels = industrial_welding_get_product_spec_labels();

	if ( is_array( $keys ) ) {
		$labels = array_intersect_key( $labels, array_flip( $keys ) );
	}

	$entries = array();

	foreach ( $labels as $key => $label ) {
		$value = industrial_welding_get_product_meta( $product_id, $key );

		if ( '' === (string) $value ) {
			continue;
		}

		$entries[] = array(
			'key'   => $key,
			'label' => $label,
			'value' => (string) $value,
		);
	}

	return $entries;
}

/**
 * Parse selected compare product IDs from the request.
 *
 * @return int[]
 */
function industrial_welding_get_requested_compare_ids() {
	$raw_value = '';

	if ( isset( $_GET[ industrial_welding_get_compare_query_key() ] ) ) {
		$raw_value = sanitize_text_field( wp_unslash( $_GET[ industrial_welding_get_compare_query_key() ] ) );
	} elseif ( isset( $_GET['machines'] ) ) {
		$raw_value = sanitize_text_field( wp_unslash( $_GET['machines'] ) );
	}

	if ( ! $raw_value ) {
		return array();
	}

	$requested_ids = array_map( 'absint', explode( ',', $raw_value ) );

	return array_values( array_filter( array_unique( $requested_ids ) ) );
}

/**
 * Get the taxonomies used by the catalog filters and Finder.
 *
 * @return array<string, array<string, string>>
 */
function industrial_welding_get_filterable_catalog_taxonomies() {
	return array(
		'usage_scene'  => array(
			'label'          => __( 'Usage Scene', 'industrial-welding' ),
			'short_label'    => __( 'Usage', 'industrial-welding' ),
			'question'       => __( 'What will you weld?', 'industrial-welding' ),
			'question_hint'  => __( 'Choose the application or shop context closest to your work.', 'industrial-welding' ),
			'empty_message'  => __( 'Usage scene terms have not been added yet.', 'industrial-welding' ),
			'archive_intro'  => __( 'Use this view to narrow the catalog by application before comparing models.', 'industrial-welding' ),
		),
		'skill_level' => array(
			'label'          => __( 'Skill Level', 'industrial-welding' ),
			'short_label'    => __( 'Skill', 'industrial-welding' ),
			'question'       => __( 'Experience level?', 'industrial-welding' ),
			'question_hint'  => __( 'Select the operator experience level the machine should suit.', 'industrial-welding' ),
			'empty_message'  => __( 'Skill level terms have not been added yet.', 'industrial-welding' ),
			'archive_intro'  => __( 'Use this view when the operator skill level matters as much as the raw specifications.', 'industrial-welding' ),
		),
		'budget_range' => array(
			'label'          => __( 'Budget Range', 'industrial-welding' ),
			'short_label'    => __( 'Budget', 'industrial-welding' ),
			'question'       => __( 'Budget?', 'industrial-welding' ),
			'question_hint'  => __( 'Keep the shortlist grounded in a realistic spending range.', 'industrial-welding' ),
			'empty_message'  => __( 'Budget range terms have not been added yet.', 'industrial-welding' ),
			'archive_intro'  => __( 'Use this view to keep the catalog aligned with the target spend before moving to detail or compare.', 'industrial-welding' ),
		),
	);
}

/**
 * Get taxonomies supported by the catalog landing templates.
 *
 * @return string[]
 */
function industrial_welding_get_supported_catalog_taxonomies() {
	return array_merge(
		array( 'product_cat' ),
		array_keys( industrial_welding_get_filterable_catalog_taxonomies() )
	);
}

/**
 * Get requested catalog filters from the URL.
 *
 * @return array<string, string>
 */
function industrial_welding_get_requested_catalog_filters() {
	$filters = array();

	foreach ( industrial_welding_get_filterable_catalog_taxonomies() as $taxonomy => $config ) {
		if ( ! isset( $_GET[ $taxonomy ] ) ) {
			continue;
		}

		$value = sanitize_title( wp_unslash( $_GET[ $taxonomy ] ) );

		if ( '' === $value ) {
			continue;
		}

		$filters[ $taxonomy ] = $value;
	}

	return $filters;
}

/**
 * Get active catalog filters, including the current taxonomy archive when relevant.
 *
 * @param WP_Term|null $current_term Current queried term.
 * @return array<string, string>
 */
function industrial_welding_get_active_catalog_filters( $current_term = null ) {
	$filters = industrial_welding_get_requested_catalog_filters();

	if ( $current_term instanceof WP_Term && isset( industrial_welding_get_filterable_catalog_taxonomies()[ $current_term->taxonomy ] ) ) {
		$filters[ $current_term->taxonomy ] = $current_term->slug;
	}

	return $filters;
}

/**
 * Get the current queried catalog term when the archive is a supported taxonomy.
 *
 * @return WP_Term|null
 */
function industrial_welding_get_catalog_current_term() {
	$queried_object = get_queried_object();

	if ( ! ( $queried_object instanceof WP_Term ) ) {
		return null;
	}

	if ( ! in_array( $queried_object->taxonomy, industrial_welding_get_supported_catalog_taxonomies(), true ) ) {
		return null;
	}

	return $queried_object;
}

/**
 * Get a catalog URL with the requested filters preserved and optionally overridden.
 *
 * @param array<string, string|null> $overrides Filter overrides.
 * @param string                     $base_url  Base URL to apply filters to.
 * @return string
 */
function industrial_welding_get_catalog_url_with_filters( $overrides = array(), $base_url = '' ) {
	$filters = industrial_welding_get_requested_catalog_filters();

	foreach ( $overrides as $taxonomy => $value ) {
		if ( null === $value || '' === $value ) {
			unset( $filters[ $taxonomy ] );
			continue;
		}

		$filters[ $taxonomy ] = sanitize_title( $value );
	}

	$target_url = $base_url ? $base_url : industrial_welding_get_catalog_url();
	$target_url = remove_query_arg( 'paged', $target_url );

	if ( empty( $filters ) ) {
		return $target_url;
	}

	return add_query_arg( $filters, $target_url );
}

/**
 * Get a catalog term from the current query.
 *
 * @param WP_Query $query Query object.
 * @return array<string, string>|null
 */
function industrial_welding_get_catalog_term_from_query( $query ) {
	foreach ( industrial_welding_get_supported_catalog_taxonomies() as $taxonomy ) {
		if ( ! $query->is_tax( $taxonomy ) ) {
			continue;
		}

		$term_slug = $query->get( $taxonomy );

		if ( is_array( $term_slug ) ) {
			$term_slug = reset( $term_slug );
		}

		if ( ! $term_slug ) {
			continue;
		}

		return array(
			'taxonomy' => $taxonomy,
			'slug'     => sanitize_title( (string) $term_slug ),
		);
	}

	return null;
}

/**
 * Build the catalog tax query using the current archive plus active filters.
 *
 * @param WP_Query $query Query object.
 * @return array<int|string, mixed>
 */
function industrial_welding_get_catalog_tax_query_for_query( $query ) {
	$tax_query    = array( 'relation' => 'AND' );
	$current_term = industrial_welding_get_catalog_term_from_query( $query );

	if ( $current_term ) {
		$tax_query[] = array(
			'taxonomy' => $current_term['taxonomy'],
			'field'    => 'slug',
			'terms'    => $current_term['slug'],
		);
	}

	foreach ( industrial_welding_get_requested_catalog_filters() as $taxonomy => $slug ) {
		if ( $current_term && $current_term['taxonomy'] === $taxonomy ) {
			continue;
		}

		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $slug,
		);
	}

	return count( $tax_query ) > 1 ? $tax_query : array();
}

/**
 * Apply the catalog filters to product archive queries.
 *
 * @param WP_Query $query Query object.
 */
function industrial_welding_apply_catalog_filters_to_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$is_catalog_query = $query->is_post_type_archive( 'product' ) || $query->is_tax( industrial_welding_get_supported_catalog_taxonomies() );

	if ( ! $is_catalog_query ) {
		return;
	}

	$tax_query = industrial_welding_get_catalog_tax_query_for_query( $query );

	if ( ! empty( $tax_query ) ) {
		$query->set( 'tax_query', $tax_query );
	}

	$query->set( 'post_type', 'product' );
	$query->set( 'post_status', 'publish' );
}
add_action( 'pre_get_posts', 'industrial_welding_apply_catalog_filters_to_query' );

/**
 * Get filter terms for a catalog taxonomy.
 *
 * @param string $taxonomy Taxonomy name.
 * @return WP_Term[]
 */
function industrial_welding_get_catalog_filter_terms( $taxonomy ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return array();
	}

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	return $terms;
}

/**
 * Get the current cache version used for catalog-facing derived data.
 *
 * @return int
 */
function industrial_welding_get_catalog_cache_version() {
	$version = absint( get_option( 'industrial_welding_catalog_cache_version', 1 ) );

	return $version > 0 ? $version : 1;
}

/**
 * Build a cache key scoped to the current catalog cache version.
 *
 * @param string $suffix Cache suffix.
 * @return string
 */
function industrial_welding_get_catalog_cache_key( $suffix ) {
	return 'industrial_welding_' . sanitize_key( $suffix ) . '_v' . industrial_welding_get_catalog_cache_version();
}

/**
 * Bump the shared catalog cache version once per request.
 */
function industrial_welding_bump_catalog_cache_version() {
	static $did_bump = false;

	if ( $did_bump ) {
		return;
	}

	$did_bump = true;
	update_option( 'industrial_welding_catalog_cache_version', industrial_welding_get_catalog_cache_version() + 1 );
}

/**
 * Check whether a product meta key should invalidate catalog-facing caches.
 *
 * @param string $meta_key Meta key.
 * @return bool
 */
function industrial_welding_is_catalog_cache_sensitive_meta_key( $meta_key ) {
	static $meta_keys = null;

	if ( null === $meta_keys ) {
		$meta_keys = array_keys( industrial_welding_get_product_meta_config() );
	}

	return in_array( $meta_key, $meta_keys, true );
}

/**
 * Get published product IDs for catalog-derived calculations.
 *
 * @param array<string, mixed> $args Optional query overrides.
 * @return int[]
 */
function industrial_welding_get_catalog_product_ids( $args = array() ) {
	$query_args = wp_parse_args(
		$args,
		array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return array_map( 'absint', get_posts( $query_args ) );
}

/**
 * Invalidate derived catalog caches when a product is saved.
 *
 * @param int $post_id Product ID.
 */
function industrial_welding_invalidate_catalog_caches_on_product_save( $post_id ) {
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	industrial_welding_bump_catalog_cache_version();
}
add_action( 'save_post_product', 'industrial_welding_invalidate_catalog_caches_on_product_save' );

/**
 * Invalidate derived catalog caches when product publish state changes.
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Old post status.
 * @param WP_Post $post       Post object.
 */
function industrial_welding_invalidate_catalog_caches_on_status_change( $new_status, $old_status, $post ) {
	if ( ! $post instanceof WP_Post || 'product' !== $post->post_type || $new_status === $old_status ) {
		return;
	}

	industrial_welding_bump_catalog_cache_version();
}
add_action( 'transition_post_status', 'industrial_welding_invalidate_catalog_caches_on_status_change', 10, 3 );

/**
 * Invalidate derived catalog caches when product terms change.
 *
 * @param int    $object_id  Object ID.
 * @param int[]  $terms      Term IDs.
 * @param int[]  $tt_ids     Term taxonomy IDs.
 * @param string $taxonomy   Taxonomy slug.
 * @param bool   $append     Whether terms are appended.
 * @param int[]  $old_tt_ids Previous term taxonomy IDs.
 */
function industrial_welding_invalidate_catalog_caches_on_term_change( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {
	unset( $terms, $tt_ids, $append, $old_tt_ids );

	if ( 'product' !== get_post_type( $object_id ) ) {
		return;
	}

	if ( 'product_cat' !== $taxonomy && ! in_array( $taxonomy, industrial_welding_get_supported_catalog_taxonomies(), true ) ) {
		return;
	}

	industrial_welding_bump_catalog_cache_version();
}
add_action( 'set_object_terms', 'industrial_welding_invalidate_catalog_caches_on_term_change', 10, 6 );

/**
 * Invalidate derived catalog caches when relevant product meta changes.
 *
 * @param int    $meta_id    Meta row ID.
 * @param int    $object_id  Post ID.
 * @param string $meta_key   Meta key.
 * @param mixed  $meta_value Meta value.
 */
function industrial_welding_invalidate_catalog_caches_on_meta_change( $meta_id, $object_id, $meta_key, $meta_value ) {
	unset( $meta_id, $meta_value );

	if ( 'product' !== get_post_type( $object_id ) ) {
		return;
	}

	if ( ! industrial_welding_is_catalog_cache_sensitive_meta_key( $meta_key ) ) {
		return;
	}

	industrial_welding_bump_catalog_cache_version();
}
add_action( 'added_post_meta', 'industrial_welding_invalidate_catalog_caches_on_meta_change', 10, 4 );
add_action( 'updated_post_meta', 'industrial_welding_invalidate_catalog_caches_on_meta_change', 10, 4 );
add_action( 'deleted_post_meta', 'industrial_welding_invalidate_catalog_caches_on_meta_change', 10, 4 );

/**
 * Get the data coverage counts used by the filter and Finder experience.
 *
 * @return array<string, mixed>
 */
function industrial_welding_get_catalog_data_coverage() {
	$cache_key = industrial_welding_get_catalog_cache_key( 'catalog_coverage' );
	$cached    = get_transient( $cache_key );

	if ( is_array( $cached ) && isset( $cached['rows'] ) ) {
		return $cached;
	}

	$product_ids = industrial_welding_get_catalog_product_ids();

	$total = count( $product_ids );
	$stats = array(
		'total'              => $total,
		'usage_scene'        => 0,
		'skill_level'        => 0,
		'budget_range'       => 0,
		'product_cat'        => 0,
		'core_specs'         => 0,
		'selection_complete' => 0,
	);

	foreach ( $product_ids as $product_id ) {
		$has_usage_scene  = has_term( '', 'usage_scene', $product_id );
		$has_skill_level  = has_term( '', 'skill_level', $product_id );
		$has_budget_range = has_term( '', 'budget_range', $product_id );
		$has_category     = has_term( '', 'product_cat', $product_id );
		$spec_count       = count( industrial_welding_get_product_spec_entries( $product_id, array( 'amperage', 'input_voltage', 'duty_cycle' ) ) );

		if ( $has_usage_scene ) {
			++$stats['usage_scene'];
		}

		if ( $has_skill_level ) {
			++$stats['skill_level'];
		}

		if ( $has_budget_range ) {
			++$stats['budget_range'];
		}

		if ( $has_category ) {
			++$stats['product_cat'];
		}

		if ( $spec_count >= 3 ) {
			++$stats['core_specs'];
		}

		if ( $has_usage_scene && $has_skill_level && $has_budget_range && $has_category && $spec_count >= 3 ) {
			++$stats['selection_complete'];
		}
	}

	$rows = array(
		'usage_scene'        => __( 'Usage scene coverage', 'industrial-welding' ),
		'skill_level'        => __( 'Skill level coverage', 'industrial-welding' ),
		'budget_range'       => __( 'Budget coverage', 'industrial-welding' ),
		'product_cat'        => __( 'Category coverage', 'industrial-welding' ),
		'core_specs'         => __( 'Core spec coverage', 'industrial-welding' ),
		'selection_complete' => __( 'Complete selection profile', 'industrial-welding' ),
	);

	foreach ( $rows as $key => $label ) {
		$stats['rows'][ $key ] = array(
			'label'   => $label,
			'count'   => isset( $stats[ $key ] ) ? (int) $stats[ $key ] : 0,
			'percent' => $total > 0 && isset( $stats[ $key ] ) ? (int) round( ( $stats[ $key ] / $total ) * 100 ) : 0,
		);
	}

	set_transient( $cache_key, $stats, 12 * HOUR_IN_SECONDS );

	return $stats;
}

/**
 * Get the Finder thickness options.
 *
 * @return array<string, array<string, mixed>>
 */
function industrial_welding_get_finder_thickness_options() {
	return array(
		'thin'   => array(
			'label'       => __( 'Thin Material', 'industrial-welding' ),
			'description' => __( 'Light-gauge work and lower amperage requirements.', 'industrial-welding' ),
			'min'         => 0,
			'max'         => 140,
		),
		'medium' => array(
			'label'       => __( 'Medium Thickness', 'industrial-welding' ),
			'description' => __( 'General fabrication jobs that need balanced output.', 'industrial-welding' ),
			'min'         => 141,
			'max'         => 220,
		),
		'heavy'  => array(
			'label'       => __( 'Heavy Section', 'industrial-welding' ),
			'description' => __( 'Higher-output work where sustained amperage matters.', 'industrial-welding' ),
			'min'         => 221,
			'max'         => PHP_INT_MAX,
		),
	);
}

/**
 * Get the current Finder answers from the request.
 *
 * @return array<string, string>
 */
function industrial_welding_get_finder_answers() {
	$answers = industrial_welding_get_requested_catalog_filters();

	if ( isset( $_GET['thickness'] ) ) {
		$thickness = sanitize_key( wp_unslash( $_GET['thickness'] ) );

		if ( isset( industrial_welding_get_finder_thickness_options()[ $thickness ] ) ) {
			$answers['thickness'] = $thickness;
		}
	}

	return $answers;
}

/**
 * Get the numeric amperage value from a product's amperage field.
 *
 * @param int $product_id Product ID.
 * @return float
 */
function industrial_welding_get_product_amperage_value( $product_id ) {
	$amperage = (string) industrial_welding_get_product_meta( $product_id, 'amperage' );

	if ( ! $amperage ) {
		return 0;
	}

	if ( preg_match( '/([\d.]+)/', $amperage, $matches ) ) {
		return (float) $matches[1];
	}

	return 0;
}

/**
 * Get the thickness band that best matches an amperage value.
 *
 * @param float $amperage Product amperage.
 * @return string
 */
function industrial_welding_get_thickness_band_for_amperage( $amperage ) {
	foreach ( industrial_welding_get_finder_thickness_options() as $slug => $option ) {
		if ( $amperage >= $option['min'] && $amperage <= $option['max'] ) {
			return $slug;
		}
	}

	return '';
}

/**
 * Get the primary term for a product under a specific taxonomy.
 *
 * @param int    $product_id Product ID.
 * @param string $taxonomy   Taxonomy name.
 * @return WP_Term|null
 */
function industrial_welding_get_primary_term_for_taxonomy( $product_id, $taxonomy ) {
	$terms = get_the_terms( $product_id, $taxonomy );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	return array_shift( $terms );
}

/**
 * Get Finder recommendations from the current answers.
 *
 * @param array<string, string> $answers Finder answers.
 * @param int                   $limit   Maximum number of products.
 * @return array<string, mixed>
 */
function industrial_welding_get_finder_recommendations( $answers, $limit = 3 ) {
	$result = array(
		'items'            => array(),
		'is_fallback'      => false,
		'is_partial'       => false,
		'matched_products' => 0,
		'compare_url'      => '',
		'catalog_url'      => industrial_welding_get_catalog_url_with_filters(
			array(
				'usage_scene'  => isset( $answers['usage_scene'] ) ? $answers['usage_scene'] : null,
				'skill_level'  => isset( $answers['skill_level'] ) ? $answers['skill_level'] : null,
				'budget_range' => isset( $answers['budget_range'] ) ? $answers['budget_range'] : null,
			)
		),
	);

	if ( empty( $answers ) ) {
		return $result;
	}

	$cache_answers = $answers;
	ksort( $cache_answers );

	$cache_key = industrial_welding_get_catalog_cache_key(
		'finder_' . md5(
			wp_json_encode(
				array(
					'answers' => $cache_answers,
					'limit'   => (int) $limit,
				)
			)
		)
	);
	$cached    = get_transient( $cache_key );

	if ( is_array( $cached ) && isset( $cached['items'] ) ) {
		return $cached;
	}

	$candidate_query = array();
	$tax_query       = array( 'relation' => 'AND' );

	foreach ( array( 'usage_scene', 'skill_level', 'budget_range' ) as $taxonomy ) {
		if ( empty( $answers[ $taxonomy ] ) ) {
			continue;
		}

		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'slug',
			'terms'    => $answers[ $taxonomy ],
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$candidate_query['tax_query'] = $tax_query;
	}

	$product_ids = industrial_welding_get_catalog_product_ids( $candidate_query );

	if ( empty( $product_ids ) ) {
		$product_ids = industrial_welding_get_catalog_product_ids();
	}

	$scored_products = array();

	foreach ( $product_ids as $product_id ) {
		$score          = 0;
		$matched_rules  = 0;
		$reason_details = array();

		if ( ! empty( $answers['usage_scene'] ) && has_term( $answers['usage_scene'], 'usage_scene', $product_id ) ) {
			$usage_term = industrial_welding_get_primary_term_for_taxonomy( $product_id, 'usage_scene' );
			$score     += 45;
			++$matched_rules;

			if ( $usage_term ) {
				$reason_details[] = sprintf(
					/* translators: %s: usage scene name */
					__( 'Matches the %s usage scene.', 'industrial-welding' ),
					$usage_term->name
				);
			}
		}

		if ( ! empty( $answers['thickness'] ) ) {
			$amperage       = industrial_welding_get_product_amperage_value( $product_id );
			$thickness_band = $amperage > 0 ? industrial_welding_get_thickness_band_for_amperage( $amperage ) : '';

			if ( $thickness_band && $answers['thickness'] === $thickness_band ) {
				$thickness_label = industrial_welding_get_finder_thickness_options()[ $answers['thickness'] ]['label'];
				$score          += 25;
				++$matched_rules;
				$reason_details[] = sprintf(
					/* translators: 1: thickness label, 2: amperage text */
					__( 'Amperage output fits the %1$s range (%2$s).', 'industrial-welding' ),
					$thickness_label,
					(string) industrial_welding_get_product_meta( $product_id, 'amperage' )
				);
			}
		}

		if ( ! empty( $answers['skill_level'] ) && has_term( $answers['skill_level'], 'skill_level', $product_id ) ) {
			$skill_term = industrial_welding_get_primary_term_for_taxonomy( $product_id, 'skill_level' );
			$score     += 18;
			++$matched_rules;

			if ( $skill_term ) {
				$reason_details[] = sprintf(
					/* translators: %s: skill level term name */
					__( 'Aligned with the %s operator level.', 'industrial-welding' ),
					$skill_term->name
				);
			}
		}

		if ( ! empty( $answers['budget_range'] ) && has_term( $answers['budget_range'], 'budget_range', $product_id ) ) {
			$budget_term = industrial_welding_get_primary_term_for_taxonomy( $product_id, 'budget_range' );
			$score      += 15;
			++$matched_rules;

			if ( $budget_term ) {
				$reason_details[] = sprintf(
					/* translators: %s: budget term name */
					__( 'Sits in the %s budget band.', 'industrial-welding' ),
					$budget_term->name
				);
			}
		}

		if ( count( industrial_welding_get_product_spec_entries( $product_id, array( 'amperage', 'input_voltage', 'duty_cycle' ) ) ) >= 3 ) {
			$score += 5;
		}

		if ( $score <= 0 ) {
			continue;
		}

		$scored_products[] = array(
			'id'            => $product_id,
			'score'         => $score,
			'matched_rules' => $matched_rules,
			'reasons'       => array_slice( array_values( array_unique( $reason_details ) ), 0, 3 ),
		);
	}

	usort(
		$scored_products,
		static function( $left, $right ) {
			if ( $left['score'] === $right['score'] ) {
				if ( $left['matched_rules'] === $right['matched_rules'] ) {
					return $left['id'] < $right['id'] ? -1 : 1;
				}

				return $right['matched_rules'] <=> $left['matched_rules'];
			}

			return $right['score'] <=> $left['score'];
		}
	);

	if ( empty( $scored_products ) ) {
		$fallback_query = new WP_Query( industrial_welding_get_featured_products_query_args( $limit ) );

		if ( $fallback_query->have_posts() ) {
			while ( $fallback_query->have_posts() ) {
				$fallback_query->the_post();
				$scored_products[] = array(
					'id'            => get_the_ID(),
					'score'         => 0,
					'matched_rules' => 0,
					'reasons'       => array(
						__( 'No exact Finder match was available, so this featured machine is shown as a safe fallback.', 'industrial-welding' ),
					),
				);
			}

			wp_reset_postdata();
		}

		$result['is_fallback'] = true;
	}

	$scored_products             = array_slice( $scored_products, 0, $limit );
	$result['matched_products']  = count( $scored_products );
	$recommended_product_ids     = array();
	$answered_rule_count         = count( $answers );

	foreach ( $scored_products as $item ) {
		$product = industrial_welding_is_woocommerce_active() ? wc_get_product( $item['id'] ) : null;

		if ( ! $product ) {
			continue;
		}

		$recommended_product_ids[] = $item['id'];
		$primary_term              = industrial_welding_get_primary_product_term( $item['id'] );

		$result['items'][] = array(
			'id'         => $item['id'],
			'title'      => get_the_title( $item['id'] ),
			'permalink'  => get_permalink( $item['id'] ),
			'thumbnail'  => get_the_post_thumbnail_url( $item['id'], 'medium_large' ),
			'summary'    => industrial_welding_get_product_summary( $product, 22 ),
			'price_html' => $product->get_price_html(),
			'category'   => $primary_term ? $primary_term->name : industrial_welding_get_machine_label(),
			'reasons'    => $item['reasons'],
			'score'      => $item['score'],
		);

		if ( $answered_rule_count > 0 && $item['matched_rules'] < $answered_rule_count ) {
			$result['is_partial'] = true;
		}
	}

	if ( count( $recommended_product_ids ) >= industrial_welding_get_compare_min_selection() ) {
		$result['compare_url'] = industrial_welding_get_compare_url_for_ids( $recommended_product_ids );
	}

	set_transient( $cache_key, $result, 6 * HOUR_IN_SECONDS );

	return $result;
}

/**
 * Get a cache-busting asset version from the theme file mtime.
 *
 * @param string $relative_path Theme-relative asset path beginning with a slash.
 * @return string
 */
function industrial_welding_get_asset_version( $relative_path ) {
	$asset_path = get_theme_file_path( $relative_path );

	if ( file_exists( $asset_path ) ) {
		return (string) filemtime( $asset_path );
	}

	return wp_get_theme()->get( 'Version' );
}

/**
 * Enqueue scripts and styles.
 */
function industrial_welding_scripts() {
	wp_enqueue_style(
		'industrial-welding-fonts',
		get_theme_file_uri( '/assets/css/fonts.css' ),
		array(),
		industrial_welding_get_asset_version( '/assets/css/fonts.css' )
	);

	wp_enqueue_style(
		'industrial-welding-tailwind',
		get_theme_file_uri( '/assets/css/tailwind.css' ),
		array( 'industrial-welding-fonts' ),
		industrial_welding_get_asset_version( '/assets/css/tailwind.css' )
	);

	wp_enqueue_style(
		'industrial-welding-style',
		get_stylesheet_uri(),
		array( 'industrial-welding-tailwind' ),
		industrial_welding_get_asset_version( '/style.css' )
	);

	wp_enqueue_script(
		'industrial-welding-scripts',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		industrial_welding_get_asset_version( '/assets/js/main.js' ),
		true
	);

	wp_localize_script(
		'industrial-welding-scripts',
		'industrialWelding',
		array(
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'industrial_welding_nonce' ),
			'finderUrl'         => industrial_welding_get_finder_page_url(),
			'compareUrl'        => industrial_welding_get_compare_page_url(),
			'compareQueryKey'   => industrial_welding_get_compare_query_key(),
			'compareMinSelect'  => industrial_welding_get_compare_min_selection(),
			'compareStorageKey' => industrial_welding_get_compare_storage_key(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'industrial_welding_scripts' );

/**
 * Theme setup.
 */
function industrial_welding_setup() {
	load_theme_textdomain( 'industrial-welding', get_template_directory() . '/languages' );

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
 * Assign a page template when the page is still using the default template.
 *
 * @param int    $page_id   Page ID.
 * @param string $template  Template filename.
 */
function industrial_welding_maybe_assign_page_template( $page_id, $template ) {
	if ( ! $page_id || ! $template ) {
		return;
	}

	if ( ! file_exists( get_theme_file_path( $template ) ) ) {
		return;
	}

	$current_template = (string) get_page_template_slug( $page_id );

	if ( $current_template === $template ) {
		return;
	}

	if ( '' === $current_template || 'default' === $current_template ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
		return;
	}

	if ( ! file_exists( get_theme_file_path( $current_template ) ) ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}
}

/**
 * Assign the posts page when WordPress does not already have a valid one.
 *
 * @param int $page_id Blog page ID.
 */
function industrial_welding_maybe_assign_posts_page( $page_id ) {
	$page_id = absint( $page_id );

	if ( ! $page_id ) {
		return;
	}

	$front_page_id = absint( get_option( 'page_on_front' ) );
	$posts_page_id = absint( get_option( 'page_for_posts' ) );

	if ( $posts_page_id === $page_id ) {
		return;
	}

	if ( $posts_page_id > 0 ) {
		$posts_page = get_post( $posts_page_id );

		if (
			$posts_page instanceof WP_Post
			&& 'page' === $posts_page->post_type
			&& ! in_array( $posts_page->post_status, array( 'trash', 'auto-draft' ), true )
			&& $posts_page_id !== $front_page_id
		) {
			return;
		}
	}

	if ( $page_id === $front_page_id ) {
		return;
	}

	update_option( 'page_for_posts', $page_id );
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
	$page = get_page_by_path( $slug, OBJECT, 'page' );

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

	if ( ! is_wp_error( $page_id ) ) {
		industrial_welding_maybe_assign_page_template( $page_id, $template );
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
		'finder'  => array(
			'title'    => __( 'Welder Finder', 'industrial-welding' ),
			'template' => 'page-finder.php',
		),
		'compare' => array(
			'title'    => __( 'Compare Machines', 'industrial-welding' ),
			'template' => 'page-compare.php',
		),
		'blog'    => array(
			'title'    => __( 'Blog', 'industrial-welding' ),
			'template' => 'page-blog.php',
		),
		'contact' => array(
			'title'    => __( 'Contact Us', 'industrial-welding' ),
			'template' => 'page-contact.php',
		),
	);

	foreach ( $pages as $slug => $page_data ) {
		$page_id = industrial_welding_ensure_page( $slug, $page_data['title'], $page_data['template'] );

		if ( 'blog' === $slug ) {
			industrial_welding_maybe_assign_posts_page( $page_id );
		}
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
