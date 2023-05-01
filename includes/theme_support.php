<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Add theme support and functions
 *
 * Function called in cd_init_theme() in base-functions.php.
 *
 * @package Cardealer
 */

if ( ! function_exists( 'cardealer_theme_support' ) ) {
	/**
	 * Theme Support Function
	 */
	function cardealer_theme_support() {
		// Make theme available for translation.
		load_theme_textdomain( 'cardealer', CARDEALER_PATH . '/languages' );
		// Support for thumbnails.
		add_theme_support( 'post-thumbnails' );
		// Support for RSS.
		add_theme_support( 'automatic-feed-links' );
		// HTML5.
		add_theme_support(
			'html5',
			array(
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
				'script',
				'style',
			)
		);
		// Title Tag.
		add_theme_support( 'title-tag' );
		// Support for post formats.
		add_theme_support(
			'post-formats',
			array(
				'aside',  // title less blurb.
				'gallery', // gallery of images.
				'link',   // quick link to other site.
				'image',  // an image.
				'quote',  // a quick quote.
				'status', // a Facebook like status update.
				'video',  // video.
				'audio',  // audio.
				'chat',   // chat transcript.
			)
		);
		// Register WP3+ menus.
		register_nav_menus(
			array(
				'primary-menu' => esc_html__( 'Primary menu', 'cardealer' ), // Primary nav in header.
				'footer-menu'  => esc_html__( 'Footer menu', 'cardealer' ), // Nav in footer.
				'topbar-menu'  => esc_html__( 'Topbar menu', 'cardealer' ), // TopBar Menu.
			)
		);
		add_theme_support( 'widgets' );
		/**
		 * Add Woocommerce theme support
		 */
		add_theme_support( 'woocommerce' );
		// Add styles for use in visual editor.
		add_editor_style( 'css/editor-styles.css' );

		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

	}
}

/**
 * Theme's custom image sizes.
 *
 * @return array
 */
function cardealer_image_sizes() {
	$image_sizes = array(
		'cardealer-blog-thumb'         => array(
			'name'      => 'cardealer-blog-thumb',
			'width'     => 1170,
			'height'    => 500,
			'crop'      => true,
			'size_name' => esc_html__( 'Cardealer Blog Thumb', 'cardealer' ),
		),
		'cardealer-homepage-thumb'     => array(
			'name'      => 'cardealer-homepage-thumb',
			'width'     => 700,
			'height'    => 700,
			'crop'      => true,
			'size_name' => esc_html__( 'Cardealer Homepage Thumb', 'cardealer' ),
		),
		'cardealer-team-thumb'         => array(
			'name'      => 'cardealer-team-thumb',
			'width'     => 430,
			'height'    => 450,
			'crop'      => true,
			'size_name' => esc_html__( 'Cardealer Team Thumb', 'cardealer' ),
		),
		'cardealer-testimonials-thumb' => array(
			'name'      => 'cardealer-testimonials-thumb',
			'width'     => 450,
			'height'    => 189,
			'crop'      => true,
			'size_name' => esc_html__( 'Cardealer Testimonials Thumb', 'cardealer' ),
		),
		'cardealer-50x50'              => array(
			'name'      => 'cardealer-50x50',
			'width'     => 50,
			'height'    => 50,
			'crop'      => true,
			'size_name' => esc_html__( 'Cardealer 50x50', 'cardealer' ),
		),
		'cardealer-post_nav'           => array(
			'name'      => 'cardealer-post_nav',
			'width'     => 124,
			'height'    => 74,
			'crop'      => true,
			'size_name' => esc_html__( 'Cardealer Post Nav', 'cardealer' ),
		),
		'car_thumbnail'                => array(
			'name'      => 'car_thumbnail',
			'width'     => 190,
			'height'    => 138,
			'crop'      => true,
			'size_name' => esc_html__( 'Car Thumbnail', 'cardealer' ),
		),
		'car_catalog_image'            => array(
			'name'      => 'car_catalog_image',
			'width'     => 265,
			'height'    => 190,
			'crop'      => true,
			'size_name' => esc_html__( 'Car Catalog Image', 'cardealer' ),
		),
		'car_single_slider'            => array(
			'name'      => 'car_single_slider',
			'width'     => 876,
			'height'    => 535,
			'crop'      => true,
			'size_name' => esc_html__( 'Car Single Slider', 'cardealer' ),
		),
		'car_tabs_image'               => array(
			'name'      => 'car_tabs_image',
			'width'     => 430,
			'height'    => 321,
			'crop'      => true,
			'size_name' => esc_html__( 'Car Tabs Image', 'cardealer' ),
		),
	);

	return apply_filters( 'cardealer_image_sizes', $image_sizes );
}

if ( ! function_exists( 'cardealer_add_image_sizes' ) ) {
	/**
	 * Add additional image sizes
	 *
	 * Function called in cd_init_theme().
	 *
	 * @since 1.0.0
	 * @since 3.2.0 Now getting build-in image sizes from "cardealer_image_sizes" function.
	 */
	function cardealer_add_image_sizes() {
		$image_sizes = cardealer_image_sizes();
		foreach ( $image_sizes as $image_size ) {
			add_image_size(
				$image_size['name'],
				$image_size['width'],
				$image_size['height'],
				$image_size['crop']
			);
		}
	}
}

if ( ! function_exists( 'cardealer_custom_image_size_names' ) ) {
	/**
	 * The names and labels of the custom image sizes.
	 *
	 * @param string[] $size_names  Array of image size labels keyed by their name.
	 */
	function cardealer_custom_image_size_names( $size_names ) {
		$new_size_names = array();
		$image_sizes    = cardealer_image_sizes();

		foreach ( $image_sizes as $image_size ) {
			$new_size_names[ $image_size['name'] ] = $image_size['size_name'];
		}
		return array_merge( $size_names, $new_size_names );
	}
}
add_filter( 'image_size_names_choose', 'cardealer_custom_image_size_names' );
