<?php
class CarDealer_Assets {

	public $pgs_assets = '';
	public $google_fonts_url = '';

	public function __construct() {
		$this->google_fonts_url             = $this->google_fonts_url();
		$this->featured_vehicles_list_style = cardealer_get_featured_vehicles_list_style();

		add_filter( 'pgs_assets_get_scripts', array( $this, 'pgs_assets_get_scripts' ) );
		add_filter( 'pgs_assets_get_styles', array( $this, 'pgs_assets_get_styles' ) );

		$this->pgs_assets = new PGS_Assets( 'cardealer' );

		$this->hooks();
	}

	function pgs_assets_get_scripts( $scripts ) {
		$scripts = array_merge( $scripts, $this->get_scripts() );
		return $scripts;
	}

	function pgs_assets_get_styles( $styles ) {
		$styles = array_merge( $styles, $this->get_styles() );
		return $styles;
	}

	function hooks() {
		add_filter( 'cardealer_assets_script_data', array( $this, 'vehicle_detail_page_scripts' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'admin_third_party_testing_script' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'custom_js' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'preloader_js' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'google_captcha_js' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'google_maps_js' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'inventory_scripts' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'blog_scripts' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'page_header_scripts' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'additional_scripts' ), 10, 2 );
		add_filter( 'cardealer_assets_script_data', array( $this, 'admin_scripts' ), 10, 2 );

		add_filter( 'cardealer_assets_style_data', array( $this, 'blog_styles' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'vehicle_detail_page_styles' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'additional_styles' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'inventory_styles' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'admin_third_party_testing_style' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'google_fonts_url_tweak' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'preloader_css' ), 10, 2 );
		add_action( 'cardealer_assets_before_style_register', array( $this, 'font_awesome_fix' ), 10, 2 );
		add_filter( 'cardealer_assets_style_data', array( $this, 'admin_styles' ), 10, 2 );

		add_action( 'cardealer_assets_before_style_enqueue', array( $this, 'custom_css' ), 999999, 2 );
		add_action( 'cardealer_assets_after_scripts', array( $this, 'comment_reply_script' ) );
	}

	public function enqueue_script( $handle ) {
		if ( empty( $handle ) ) {
			return;
		}
		$this->pgs_assets->enqueue_script( $handle );
	}

	function blog_scripts( $script_data, $script_key ) {
		global $car_dealer_options;

		if ( ( is_author() || is_category() || is_home() || is_single() || is_tag() || is_date() ) && 'post' === get_post_type() ) {
			if ( 'cardealer-blog' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( is_single() && 'cardealer-owl-carousel' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( ( 'cardealer-masonry' === $script_key || 'shuffle' === $script_key ) && is_home() ) {
				$cardealer_blog_layout = cardealer_get_blog_layout();
				if ( 'masonry' === $cardealer_blog_layout ) {
					$script_data['action'] = 'enqueue';
				}
			}
		}

		return $script_data;
	}

	function vehicle_detail_page_scripts( $script_data, $script_key ) {

		if ( is_singular( 'cars' ) || is_singular( 'cardealer_template' ) ) {

			if ( 'magnific-popup' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'cardealer-owl-carousel' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'slick-js' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'cardealer-vehicle-detail' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'cardealer-vehicle-detail-mobile' === $script_key && wp_is_mobile() ) {
				$script_data['action'] = 'enqueue';
			}

			if ( isset( $car_dealer_options['cars-related-vehicle'] ) && $car_dealer_options['cars-related-vehicle'] && 'cardealer-owl-carousel' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}
		}

		return $script_data;
	}

	function vehicle_detail_page_styles( $script_data, $script_key ) {
		global $car_dealer_options;

		if ( is_singular( 'cars' ) || is_singular( 'cardealer_template' ) ) {
			/*
			if ( 'owl-carousel' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}
			*/
		}

		return $script_data;
	}

	function admin_third_party_testing_script( $script_data, $script_key ) {

		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( 'cardealer_admin_js' === $script_key ) {
				if ( 'car-dealer_page_cardealer-third-party-testing' === $screen->id ) {
					$script_data['deps'][] = 'select2';
				}
			}

			if ( 'cardealer_seo_admin_js' === $script_key && 'cars' === $screen->id && class_exists( 'WPSEO_Admin_Init' ) ) {
				$script_data['action'] = 'enqueue';
			}
		}

		return $script_data;
	}

	function custom_js( $script_data, $script_key ) {
		global $car_dealer_options;
		$custom_js = isset( $car_dealer_options['custom_js'] ) ? $car_dealer_options['custom_js'] : '';

		if ( ! is_admin() && 'cardealer_js' === $script_key && $custom_js ) {
			$custom_js = wp_strip_all_tags( $custom_js );
			if ( ! empty( $custom_js ) ) {
				if ( isset( $script_data['inline_script'] ) && ! empty( $script_data['inline_script'] ) ) {
					$script_data['inline_script'] = ( is_array( $script_data['inline_script'] ) ) ? array_merge( $script_data['inline_script'], array( $custom_js ) ) : array( $script_data['inline_script'], $custom_js );
				} else {
					$script_data['inline_script'] = $custom_js;
				}
			}
		}

		return $script_data;
	}

	function preloader_js( $script_data, $script_key ) {
		global $car_dealer_options;

		if (
			! is_admin()
			&& 'cardealer_js' === $script_key
			&& ( isset( $car_dealer_options['preloader'] ) && filter_var( $car_dealer_options['preloader'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) )
			&& ( $car_dealer_options['preloader_img'] && 'code' === $car_dealer_options['preloader_img'] )
			&& ( isset( $car_dealer_options['preloader_js'] ) && ! empty( $car_dealer_options['preloader_js'] ) )
		) {
			$preloader_js = wp_strip_all_tags( $car_dealer_options['preloader_js'] );
			if ( ! empty( $preloader_js ) ) {
				if ( isset( $script_data['inline_script'] ) && ! empty( $script_data['inline_script'] ) ) {
					$script_data['inline_script'] = ( is_array( $script_data['inline_script'] ) ) ? array_merge( $script_data['inline_script'], array( $preloader_js ) ) : array( $script_data['inline_script'], $preloader_js );
				} else {
					$script_data['inline_script'] = $preloader_js;
				}
			}
		}

		return $script_data;
	}

	function google_captcha_js( $script_data, $script_key ) {
		$captcha_sitekey    = cardealer_get_goole_api_keys( 'site_key' );
		$captcha_secret_key = cardealer_get_goole_api_keys( 'secret_key' );

		if (
			! is_admin()
			&& 'cardealer_js' === $script_key
			&& ! empty( $captcha_secret_key )
			&& ! empty( $captcha_sitekey )
		) {
			$captcha_sitekey = wp_strip_all_tags( $captcha_sitekey );
			if ( ! empty( $captcha_sitekey ) ) {
				$script_data['localize']['goole_captcha_api_obj'] = array(
					'google_captcha_site_key' => $captcha_sitekey,
				);
			}
		}

		return $script_data;
	}

	function google_maps_js( $script_data, $script_key ) {
		global $car_dealer_options;

		if ( 'cardealer-map' === $script_key && is_single() && 'cars' === get_post_type() ) {
			$script_data['action'] = 'enqueue';
			$zoom = 10;
			if ( isset( $car_dealer_options['default_value_zoom'] ) && ! empty( $car_dealer_options['default_value_zoom'] ) ) {
				$zoom = (int) $car_dealer_options['default_value_zoom'];
			}
			$script_data['localize']['cardealer_map_obj'] = array(
				'zoom' => $zoom,
			);
		}

		return $script_data;
	}

	function inventory_scripts( $script_data, $script_key ) {
		global $car_dealer_options;

		if ( is_post_type_archive( 'cars' ) || is_page_template( 'templates/sold-cars.php' ) ) {

			if ( 'magnific-popup' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			$featured_vehicles_list_style = $this->featured_vehicles_list_style;

			if ( 'cardealer-vehicle-filter' === $script_key || 'cardealer-vehicle-inventory-js' === $script_key || ( 'cardealer-owl-carousel' === $script_key && 'carousel' === $featured_vehicles_list_style ) ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'shuffle' === $script_key || 'cardealer-masonry' === $script_key ) {
				if ( ( isset( $car_dealer_options[ 'vehicle-listing-layout' ] ) && 'lazyload' === $car_dealer_options[ 'vehicle-listing-layout' ] ) || ( function_exists( 'cardealer_get_cars_list_layout_style' ) && 'view-masonry' === cardealer_get_cars_list_layout_style() ) ) {
					$script_data['action'] = 'enqueue';
				}
			}
		}

		return $script_data;
	}

	function page_header_scripts( $script_data, $script_key ) {
		global $post;

		$banner_type = cardealer_get_banner_type();
		$video_link  = cardealer_get_video_link();

		if ( ( 'video-background' === $script_key && 'video' === $banner_type && '' !== $video_link ) ) {
			$script_data['action'] = 'enqueue';
			if ( class_exists( 'WPBakeryVisualComposerAbstract' ) && wp_script_is( 'vc_youtube_iframe_api_js', 'registered' ) ) {
				$script_data['deps'][] = 'vc_youtube_iframe_api_js';
			} else {
				$script_data['deps'][] = 'youtube_iframe_api_js';
			}
		}

		return $script_data;
	}

	function additional_scripts( $script_data, $script_key ) {
		global $post, $car_dealer_options;

		$actions = [
			'elementor',
			'elementor_get_templates',
			'elementor_save_template',
			'elementor_get_template',
			'elementor_delete_template',
			'elementor_import_template',
			'elementor_library_direct_actions',
		];

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $actions ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$script_data['action'] = 'enqueue';
		} else {

			if ( isset( $car_dealer_options[ 'enable_lazyload' ] ) && $car_dealer_options[ 'enable_lazyload' ] && 'lazyload' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'cardealer-faq' === $script_key && 'templates/faq.php' === get_page_template_slug() ) {
				$script_data['action'] = 'enqueue';
			}

			if ( class_exists( 'WooCommerce' ) ) {
				if ( 'cardealer-wc' === $script_key )  {
					$script_data['action'] = 'enqueue';
				}

				if ( 'cardealer-owl-carousel' === $script_key  && is_product() )  {
					$script_data['action'] = 'enqueue';
				}
			}

			if ( 'cardealer-coming-soon' === $script_key && isset( $car_dealer_options['enable_maintenance'] ) && $car_dealer_options['enable_maintenance'] )  {
				$script_data['action'] = 'enqueue';
			}
		}

		return $script_data;
	}

	function admin_scripts( $script_data, $script_key ) {
		if ( is_admin() ) {
			$screen = get_current_screen();

			if ( 'cardealer_templates' === $script_key && in_array( $screen->id, array( 'edit-cardealer_template', 'cardealer_template' ), true ) ) {
				$script_data['action'] = 'enqueue';
			}
		}

		return $script_data;
	}

	function blog_styles( $script_data, $script_key ) {
		global $car_dealer_options;

		if ( is_search() || ( ( is_author() || is_category() || is_home() || is_single() || is_tag() || is_date() ) && 'post' === get_post_type() ) ) {
			if ( 'cardealer-blog' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			if ( 'shuffle' === $script_key && is_home() ) {
				$cardealer_blog_layout = cardealer_get_blog_layout();
				if ( 'masonry' === $cardealer_blog_layout ) {
					$script_data['action'] = 'enqueue';
				}
			}
		}

		return $script_data;
	}

	function additional_styles( $script_data, $script_key ) {
		global $car_dealer_options, $post;

		$actions = [
			'elementor',
			'elementor_get_templates',
			'elementor_save_template',
			'elementor_get_template',
			'elementor_delete_template',
			'elementor_import_template',
			'elementor_library_direct_actions',
		];

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $actions ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$script_data['action'] = 'enqueue';
		} else {
			$current_pt = get_page_template_slug();
			if ( 'cardealer-tabs' === $script_key && 'templates/faq.php' === $current_pt ) {
				$script_data['action'] = 'enqueue';
			}
		}

		return $script_data;
	}

	function inventory_styles( $script_data, $script_key ) {

		if ( function_exists( 'cdfs_is_user_account_page' ) && cdfs_is_user_account_page() ) {
			if ( 'cardealer-inventory' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}
		}

		if ( is_post_type_archive( 'cars' ) || is_singular( 'cars' ) || is_singular( 'cardealer_template' ) || is_page_template( 'templates/sold-cars.php' ) ) {
			if ( 'cardealer-inventory' === $script_key || 'cardealer-tabs' === $script_key ) {
				$script_data['action'] = 'enqueue';
			}

			$featured_vehicles_list_style = $this->featured_vehicles_list_style;

			if ( is_post_type_archive( 'cars' ) && 'owl-carousel' === $script_key && 'grid' === $featured_vehicles_list_style ) {
				$script_data['action'] = 'register';
			}

			if ( is_singular( 'cars' ) || is_singular( 'cardealer_template' ) ) {
				if ( 'slick-slider' === $script_key || 'slick-slider-theme' === $script_key ) {
					$script_data['action'] = 'enqueue';
				}
			}
		}

		return $script_data;
	}

	function admin_third_party_testing_style( $script_data, $script_key ) {

		if ( 'cardealer-admin-style' === $script_key ) {
			$screen = get_current_screen();

			if ( 'car-dealer_page_cardealer-third-party-testing' === $screen->id ) {
				$script_data['deps'][] = 'select2';
			}
		}

		return $script_data;
	}

	function preloader_css( $script_data, $script_key ) {
		global $car_dealer_options;

		if (
			! is_admin()
			&& 'cardealer-main' === $script_key
			&& ( isset( $car_dealer_options['preloader'] ) && filter_var( $car_dealer_options['preloader'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) )
			&& ( $car_dealer_options['preloader_img'] && 'code' === $car_dealer_options['preloader_img'] )
			&& ( isset( $car_dealer_options['preloader_css'] ) && ! empty( $car_dealer_options['preloader_css'] ) )
		) {
			$preloader_css = wp_strip_all_tags( $car_dealer_options['preloader_css'] );
			if ( ! empty( $preloader_css ) ) {
				if ( isset( $script_data['inline_style'] ) && ! empty( $script_data['inline_style'] ) ) {
					$script_data['inline_style'] = ( is_array( $script_data['inline_style'] ) ) ? array_merge( $script_data['inline_style'], array( $preloader_css ) ) : array( $script_data['inline_style'], $preloader_css );
				} else {
					$script_data['inline_style'] = $preloader_css;
				}
			}
		}

		return $script_data;
	}

	function font_awesome_fix( $handle, $style_data ) {
		if ( 'font-awesome-shims' === $handle ) {
			wp_deregister_style( 'font-awesome-shims' );
			wp_deregister_style( 'font-awesome' );
		}
	}

	function admin_styles( $style_data, $handle ) {
		if ( is_admin() ) {
			$screen = get_current_screen();

			if ( 'cardealer_templates' === $handle && in_array( $screen->id, array( 'edit-cardealer_template', 'cardealer_template' ), true ) ) {
				$style_data['action'] = 'enqueue';
			}
		}

		return $style_data;

	}

	function google_fonts_url_tweak( $script_data, $script_key ) {
		$google_fonts_url = $this->google_fonts_url;

		if (  'cardealer-google-fonts' === $script_key && ! $google_fonts_url ) {
			$script_data = false;
		}

		return $script_data;
	}

	function custom_css( $handle, $style_data ) {
		global $car_dealer_options;

		$custom_css = isset( $car_dealer_options['custom_css'] ) ? $car_dealer_options['custom_css'] : '';
		if ( 'cardealer-main-responsive' === $handle && $custom_css ) {
			$custom_css = wp_strip_all_tags( $custom_css );
			if ( ! empty( $custom_css ) ) {
				wp_add_inline_style( $handle, $custom_css );
			}
		}
	}

	function comment_reply_script( $context ) {
		if ( 'front' === $context && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	function cardealer_enqueue_assets_cars_url() {
		global $car_dealer_options, $wp, $wp_query;


		$car_url = '';

		if ( is_admin() ) {
			return $car_url;
		}

		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
			$car_url = get_permalink( $car_dealer_options['cars_inventory_page'] );
			if ( function_exists( 'icl_object_id' ) ) {
				$lang    = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : null;
				$car_url = apply_filters( 'wpml_permalink', $car_url, $lang );
			}
		} else {
			$car_url = get_post_type_archive_link( 'cars' );
		}

		if (
			is_tax( 'vehicle_cat' )
			|| ( $wp->request && '' !== get_option( 'permalink_structure' ) && $this->vehicle_cat_slug() && false !== strpos( $wp->request, $this->vehicle_cat_slug() ) )
			|| ( ! is_tax( 'vehicle_cat' ) && '' === get_option( 'permalink_structure' ) && array_key_exists( 'vehicle_cat', $wp_query->query_vars ) )
		) {
			$car_url = $wp->request ? home_url( $wp->request ) : add_query_arg( 'vehicle_cat', $wp_query->query_vars['vehicle_cat'], home_url( '/' ) );
		}
		return $car_url;
	}

	function is_vehicle_cat() {
		global $wp, $wp_query;

		$is_vehicle_cat = false;

		if (
			is_tax( 'vehicle_cat' )
			|| ( $wp->request && '' !== get_option( 'permalink_structure' ) && $this->vehicle_cat_slug() && false !== strpos( $wp->request, $this->vehicle_cat_slug() ) )
			|| ( ! is_tax( 'vehicle_cat' ) && '' === get_option( 'permalink_structure' ) && array_key_exists( 'vehicle_cat', $wp_query->query_vars ) )
		) {
			$is_vehicle_cat = true;
		}

		return $is_vehicle_cat;
	}

	function vehicle_cat() {
		global $wp, $wp_query;

		$vehicle_cat = '';
		if (
			is_tax( 'vehicle_cat' )
			|| ( $wp->request && '' !== get_option( 'permalink_structure' ) && $this->vehicle_cat_slug() && false !== strpos( $wp->request, $this->vehicle_cat_slug() ) )
			|| ( ! is_tax( 'vehicle_cat' ) && '' === get_option( 'permalink_structure' ) && array_key_exists( 'vehicle_cat', $wp_query->query_vars ) )
		) {
			if ( isset( $wp_query->query_vars['vehicle_cat'] ) ) {
				$vehicle_cat = $wp_query->query_vars['vehicle_cat'];
			}
		}
		return $vehicle_cat;
	}

	function vehicle_cat_slug() {

		$vehicle_cat_obj  = get_taxonomy( 'vehicle_cat' );
		$vehicle_cat_slug = $vehicle_cat_obj ? $vehicle_cat_obj->rewrite['slug'] : '';

		return $vehicle_cat_slug;
	}

	private function get_scripts() {
		global $car_dealer_options;

		if ( empty( $car_dealer_options ) ) {
			$car_dealer_options = get_option( 'car_dealer_options' );
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$library_scripts = array(
			/*
			'js_handle' => array(
				'handle'    => 'js_handle',
				'src'       => 'js_src',
				'deps'      => array( 'jquery' ),
				'ver'       => '1.0.0',
				'in_footer' => true,
				'action'    => 'register',   // The default action. Accepts enqueue, dequeue, register and deregister.
				'context'   => array(        // Where to enqueue. Options: front, admin, login, block_editor, block.
					'front',
					'admin',
				),
			),
			*/
			'bootsrap' => array(
				'handle'    => 'bootsrap',
				'src'       => CARDEALER_URL . '/js/library/bootstrap/bootstrap' . $suffix . '.js',
				'ver'       => '3.3.7',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'cardealer-mega-menu' => array(
				'handle'    => 'cardealer-mega-menu',
				'src'       => CARDEALER_URL . '/js/library/cardealer-mega-menu/mega-menu' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'downcount' => array(
				'handle'    => 'downcount',
				'src'       => CARDEALER_URL . '/js/library/downcount/jquery.downCount' . $suffix . '.js',
				'ver'       => '1.0.0',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'jaaulde-cookies' => array(
				'handle'    => 'jaaulde-cookies',
				'src'       => CARDEALER_URL . '/js/library/jaaulde-cookies/cookies' . $suffix . '.js',
				'ver'       => '3.0.6',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
					'admin',
				),
			),
			'lazyload' => array(
				'handle'    => 'lazyload',
				'src'       => CARDEALER_URL . '/js/library/lazyload/lazyload' . $suffix . '.js',
				'ver'       => '2.0.0',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'magnific-popup' => array(
				'handle'    => 'magnific-popup',
				'src'       => CARDEALER_URL . '/js/library/magnific-popup/jquery.magnific-popup' . $suffix . '.js',
				'ver'       => '1.1.0',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'nice-select' => array(
				'handle'    => 'nice-select',
				'src'       => CARDEALER_URL . '/js/library/nice-select/jquery.nice-select' . $suffix . '.js',
				'ver'       => '1.1.0',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'owl-carousel' => array(
				'handle'    => 'owl-carousel',
				'src'       => CARDEALER_URL . '/js/library/owl-carousel/owl.carousel' . $suffix . '.js',
				'ver'       => '2.0.0',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'photoswipe' => array(
				'handle'    => 'photoswipe',
				'src'       => CARDEALER_URL . '/js/library/photoswipe/photoswipe' . $suffix . '.js',
				'ver'       => '4.1.2',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'photoswipe-ui-default' => array(
				'handle'    => 'photoswipe-ui-default',
				'src'       => CARDEALER_URL . '/js/library/photoswipe/photoswipe-ui-default' . $suffix . '.js',
				'ver'       => '4.1.2',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'shuffle' => array(
				'handle'    => 'shuffle',
				'src'       => CARDEALER_URL . '/js/library/shuffle/shuffle' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'slick-js' => array(
				'handle'    => 'slick-js',
				'src'       => CARDEALER_URL . '/js/library/slick/slick' . $suffix . '.js',
				'ver'       => '1.6.0',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'timepicker' => array(
				'handle'    => 'timepicker',
				'src'       => CARDEALER_URL . '/js/library/timepicker/jquery.timepicker' . $suffix . '.js',
				'ver'       => '1.11.9',
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'select2' => array(
				'handle'    => 'select2',
				'src'       => CARDEALER_URL . '/js/library/select2/select2.full' . $suffix . '.js',
				'ver'       => '4.0.13',
				'deps'      => array(
					'jquery',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'admin',
				),
			),
			'youtube_iframe_api_js' => array(
				'handle'    => 'youtube_iframe_api_js',
				'src'       => '//www.youtube.com/iframe_api',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'jquery',
				),
				'in_footer' => false,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'bootstrap-5-js' => array(
				'handle'    => 'bootstrap-5-js',
				'src'       => CARDEALER_URL . '/js/library/bootstrap-5/bootstrap.bundle' . $suffix . '.js',
				'ver'       => '4.9.0',
				'deps'      => array(
					'jquery',
					'jquery-ui-core',
				),
				'in_footer' => false,
				'action'    => 'register',
				'context'   => array(
					'admin',
				),
			),
			'jquery-confirm' => array(
				'handle'    => 'jquery-confirm',
				'src'       => CARDEALER_URL . '/js/library/jquery-confirm/jquery-confirm' . $suffix . '.js',
				'ver'       => '3.3.4',
				'deps'      => array(
					'jquery',
				),
				'in_footer' => false,
				'action'    => 'register',
				'context'   => array(
					'front',
					'admin',
				),
			),
		);

		$library_scripts = apply_filters( 'cardealer_get_library_scripts', $library_scripts );
		$google_maps_api = cardealer_get_google_maps_api_key();

		$core_scripts =  array(
			'cardealer-blog' => array(
				'handle'    => 'cardealer-blog',
				'src'       => CARDEALER_URL . '/js/frontend/blog' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'wp-mediaelement',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer_js' => array(
				'handle'    => 'cardealer_js',
				'src'       => CARDEALER_URL . '/js/frontend/cardealer' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'nice-select',
				),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'cardealer_obj' => array(
						'site_url'         => site_url(),
						'cars_url'         => $this->cardealer_enqueue_assets_cars_url(),
						'lay_style'        => cardealer_get_cars_list_layout_style(),
						'cars_grid'        => cardealer_get_cars_catlog_style(),
						'default_sort_by'  => cardealer_get_default_sort_by(),
						'default_order_by' => cardealer_get_default_sort_by_order(),
						'is_vehicle_cat'   => $this->is_vehicle_cat(),
						'vehicle_cat'      => $this->vehicle_cat(),
						'vehicle_cat_slug' => $this->vehicle_cat_slug(),

						// Add vehicle location param for ajax call in next page etc.
						'vehicle_location' => ( isset( $_GET['vehicle_location'] ) && ! empty( $_GET['vehicle_location'] ) ) ? sanitize_text_field( wp_unslash( $_GET['vehicle_location'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
					),
					'cardealer_js' => array(
						'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
						'pgs_auto_complate_search_nonce' => wp_create_nonce( 'cardealer_auto_complate_search_nonce' ),
						'compare_load_error_msg'         => esc_html__( 'Unable to load compare.', 'cardealer' ),
						'sticky_topbar'                  => ( isset( $car_dealer_options['sticky_topbar'] ) && 'on' === $car_dealer_options['sticky_topbar'] && isset( $car_dealer_options['top_bar'] ) && true === (bool) $car_dealer_options['top_bar'] ) ? true : false,
						'sticky_header_mobile'           => ( isset( $car_dealer_options['sticky_header'] ) && ( true === (bool) $car_dealer_options['sticky_header'] ) && isset( $car_dealer_options['sticky_header_mobile'] ) && ( true === (bool) $car_dealer_options['sticky_header_mobile'] ) ) ? true : false,
						'sticky_header_desktop'          => ( isset( $car_dealer_options['sticky_header'] ) && ( true === (bool) $car_dealer_options['sticky_header'] ) ) ? true : false,
					),
				),
			),
			'cardealer-coming-soon' => array(
				'handle'    => 'cardealer-coming-soon',
				'src'       => CARDEALER_URL . '/js/frontend/coming-soon' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'deps'      => array( 'downcount' ),
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-compare' => array(
				'handle'    => 'cardealer-compare',
				'src'       => CARDEALER_URL . '/js/frontend/compare' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array( 'jquery-ui-sortable' ),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'cardealer-elementor' => array(
				'handle'    => 'cardealer-elementor',
				'src'       => CARDEALER_URL . '/js/frontend/elementor' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-faq' => array(
				'handle'    => 'cardealer-faq',
				'src'       => CARDEALER_URL . '/js/frontend/faq' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-header' => array(
				'handle'    => 'cardealer-header',
				'src'       => CARDEALER_URL . '/js/frontend/header' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'cardealer-mega-menu',
					'jquery-ui-autocomplete',
				),
				'in_footer' => true,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'cardealer-mailchimp' => array(
				'handle'    => 'cardealer-mailchimp',
				'src'       => CARDEALER_URL . '/js/frontend/mailchimp' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-google-maps-apis' => array(
				'handle'    => 'cardealer-google-maps-apis',
				'src'       => 'https://maps.googleapis.com/maps/api/js?key=' . $google_maps_api . '&libraries=places',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
					'admin',
				),
			),
			'cardealer-map' => array(
				'handle'    => 'cardealer-map',
				'src'       => CARDEALER_URL . '/js/frontend/map' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'cardealer-google-maps-apis',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-masonry' => array(
				'handle'    => 'cardealer-masonry',
				'src'       => CARDEALER_URL . '/js/frontend/masonry' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-owl-carousel' => array(
				'handle'    => 'cardealer-owl-carousel',
				'src'       => CARDEALER_URL . '/js/frontend/owl-carousel' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'owl-carousel',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'video-background' => array(
				'handle'    => 'video-background',
				'src'       => CARDEALER_URL . '/js/frontend/video-background' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-google-recaptcha-apis' => array(
				'handle'    => 'cardealer-google-recaptcha-apis',
				'src'       => 'https://www.google.com/recaptcha/api.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-recaptcha' => array(
				'handle'    => 'cardealer-recaptcha',
				'src'       => CARDEALER_URL . '/js/frontend/recaptcha' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'cardealer-google-recaptcha-apis',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-shortcodes-js' => array(
				'handle'    => 'cardealer-shortcodes-js',
				'src'       => CARDEALER_URL . '/js/frontend/shortcodes' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'magnific-popup',
					'jquery-touch-punch',
					'cardealer-map',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'shortcode_js_object' => array(
						'cars_form_url'                     => $this->cardealer_enqueue_assets_cars_url(), // Add for vehicle-inventory.js, shortcodes.js
						'cardealer_cars_filter_query_nonce' => wp_create_nonce( 'cardealer_cars_filter_query_nonce' ),
						'is_year_range_active'              => cardealer_is_year_range_active(),
						'is_rtl'                            => is_rtl(),
						'error_msg'                         => esc_html__( 'Something went wrong!', 'cardealer' ),
						'min_year'                          => isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'max_year'                          => isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'min_price'                         => isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'max_price'                         => isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'currency_symbol'                   => cardealer_get_cars_currency_symbol(),
						'currency_pos'                      => cardealer_get_cars_currency_placement(),
						'decimal_places'                    => ( ! empty( $car_dealer_options['cars-number-decimals'] ) && is_numeric( $car_dealer_options['cars-number-decimals'] ) ) ? $car_dealer_options['cars-number-decimals'] : 0,
						'decimal_separator_symbol'         	=> ( isset( $car_dealer_options['cars-decimal-separator'] ) && ! empty( $car_dealer_options['cars-decimal-separator'] ) ) ? $car_dealer_options['cars-decimal-separator'] : '.',
						'thousand_seperator_symbol'         => ( isset( $car_dealer_options['cars-thousand-separator'] ) && ! empty( $car_dealer_options['cars-thousand-separator'] ) ) ? $car_dealer_options['cars-thousand-separator'] : '',
					),
				),
			),
			'cardealer-vehicle-detail' => array(
				'handle'    => 'cardealer-vehicle-detail',
				'src'       => CARDEALER_URL . '/js/frontend/vehicle-detail' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'jquery-ui-datepicker',
					'timepicker',
					'slick-js',
					'cardealer-recaptcha',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'vehicle_detail_js' => array(
						'ajaxurl' => admin_url( 'admin-ajax.php' ),
						'is_rtl'  => is_rtl(),
					),
				),
			),
			'cardealer-vehicle-detail-mobile' => array(
				'handle'    => 'cardealer-vehicle-detail-mobile',
				'src'       => CARDEALER_URL . '/js/frontend/vehicle-detail-mobile' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'jquery-touch-punch' => array(
				'handle'    => 'jquery-touch-punch',
				'src'       => CARDEALER_URL . '/js/library/jquery.ui.touch-punch' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-vehicle-filter' => array(
				'handle'    => 'cardealer-vehicle-filter',
				'src'       => CARDEALER_URL . '/js/frontend/vehicle-filter' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'jquery-ui-slider',
					'jquery-touch-punch',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'vehicle_filter_js_object' => array(
						'currency_symbol'                          => cardealer_get_cars_currency_symbol(),
						'currency_pos'                             => cardealer_get_cars_currency_placement(),
						'decimal_places'                           => ( ! empty( $car_dealer_options['cars-number-decimals'] ) && is_numeric( $car_dealer_options['cars-number-decimals'] ) ) ? $car_dealer_options['cars-number-decimals'] : 0,
						'decimal_separator_symbol'                 => ( isset( $car_dealer_options['cars-decimal-separator'] ) && ! empty( $car_dealer_options['cars-decimal-separator'] ) ) ? $car_dealer_options['cars-decimal-separator'] : '.',
						'thousand_seperator_symbol'                => ( isset( $car_dealer_options['cars-thousand-separator'] ) && ! empty( $car_dealer_options['cars-thousand-separator'] ) ) ? $car_dealer_options['cars-thousand-separator'] : '',
						'min_price'                                => isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'max_price'                                => isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'cars_form_url'                            => $this->cardealer_enqueue_assets_cars_url(),
						'is_vehicle_cat'                           => $this->is_vehicle_cat(),
						'vehicle_cat_slug'                         => $this->vehicle_cat_slug(),
						'pgs_cars_list_search_auto_compalte_nonce' => wp_create_nonce( 'pgs_cars_list_search_auto_compalte_nonce' ),
						'cardealer_cars_filter_query_nonce'        => wp_create_nonce( 'cardealer_cars_filter_query_nonce' ),
						'is_year_range_active'                     => cardealer_is_year_range_active(),
						'min_year'                                 => isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'max_year'                                 => isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : '', // phpcs:ignore WordPress.Security.NonceVerification
						'cars_filter_with'                         => cardealer_cars_filter_methods(),
						'error_msg'                                => esc_html__( 'Something went wrong!', 'cardealer' ),
						'default_sort_by'                          => cardealer_get_default_sort_by(),
						'lay_style'                                => cardealer_get_cars_list_layout_style(),
						'vehicle_cat'                              => $this->vehicle_cat(),
					)
				),
			),
			'cardealer-vehicle-inventory-js' => array(
				'handle'    => 'cardealer-vehicle-inventory',
				'src'       => CARDEALER_URL . '/js/frontend/vehicle-inventory' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'vehicle_inventory_js_object' => array(
						'cars_form_url'            => $this->cardealer_enqueue_assets_cars_url(),
						'load_more_vehicles_nonce' => wp_create_nonce( 'load_more_vehicles_nonce' ),
						'error_msg'                => esc_html__( 'Something went wrong!', 'cardealer' ),
					),
				),
			),
			'cardealer-wc' => array(
				'handle'    => 'cardealer-wc',
				'src'       => CARDEALER_URL . '/js/frontend/wc' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'vehicle_wc_js_object' => array(
						'cd_sell_car_online_ajax' => wp_create_nonce( 'cd_sell_car_online_ajax' ),
						'error_msg'               => esc_html__( 'Something went wrong!', 'cardealer' ),
					),
				),
			),
			'cardealer-widgets' => array(
				'handle'    => 'cardealer-widgets',
				'src'       => CARDEALER_URL . '/js/frontend/widgets' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-financing-calculator' => array(
				'handle'    => 'cardealer-financing-calculator',
				'src'       => CARDEALER_URL . '/js/frontend/vehicle-financing-calculator' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
				'localize' => array(
					'vehicle_financing_calculator_js_object' => array(
						'currency_symbol'     => cardealer_get_cars_currency_symbol(),
						'currency_placement'  => cardealer_get_cars_currency_placement(),
						'error_loan_amount'   => esc_html__( 'Please enter a valid number for the Loan Amount (P).', 'cardealer' ),
						'error_down_payment'  => esc_html__( 'Please enter a valid number for the Down Payment (P).', 'cardealer' ),
						'error_interest_rate' => esc_html__( 'Please enter an Interest Rate (R).', 'cardealer' ),
						'error_payment_count' => esc_html__( 'Please enter the Total Number of Payments (N).', 'cardealer' ),
						'period'              => esc_html__( '&#47;mo', 'cardealer' ),
					)
				),
			),
			'cardealer-wpbakery' => array(
				'handle'    => 'cardealer-wpbakery',
				'src'       => CARDEALER_URL . '/js/frontend/wpbakery' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer_admin_js' => array(
				'handle'    => 'cardealer_admin_js',
				'src'       => CARDEALER_URL . '/js/admin/admin' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'jquery',
				),
				'in_footer' => false,
				'action'    => 'enqueue',
				'context'   => array(
					'admin',
				),
				'localize' => array(
					'cardealer_admin_js' => array(
						'ajaxurl'                          => admin_url( 'admin-ajax.php' ),
						'cardealer_debug_nonce'            => wp_create_nonce( 'cardealer_debug_nonce' ),
						'pgs_mail_debug_nonce'             => wp_create_nonce( 'pgs_mail_debug_nonce' ),
						'pgs_vinquery_debug_nonce'         => wp_create_nonce( 'pgs_vinquery_debug_nonce' ),
						'pgs_mailchimp_debug_nonce'        => wp_create_nonce( 'pgs_mailchimp_debug_nonce' ),
						'search_vehicle'                   => esc_html__( 'Search Vehicle', 'cardealer' ),
					),
				),
			),
			'cardealer_templates' => array(
				'handle'    => 'cardealer_templates',
				'src'       => CARDEALER_URL . '/js/admin/cardealer-templates' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'jquery',
					'wp-util',
					'bootstrap-5-js',
				),
				'in_footer' => true,
				'action'    => 'register',
				'context'   => array(
					'admin',
				),
				'localize' => array(
					'cardealer_templates' => array(
					),
				),
			),
			'cardealer_seo_admin_js' => array(
				'handle'    => 'cardealer_seo_admin_js',
				'src'       => CARDEALER_URL . '/js/admin/cardealer-seo-admin' . $suffix . '.js',
				'ver'       => CARDEALER_VERSION,
				'deps'      => array(
					'jquery',
				),
				'in_footer' => false,
				'action'    => 'register',
				'context'   => array(
					'admin',
				),
			),
		);

		$core_scripts = apply_filters( 'cardealer_get_core_scripts', $core_scripts );

		$scripts = apply_filters( 'cardealer_get_scripts', array_merge( $library_scripts, $core_scripts ) );

		return $scripts;
	}

	private function get_styles() {
		global $car_dealer_options, $cardealer_theme_data;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$google_fonts_url = $this->google_fonts_url;

		$library_styles = array(
			/*
			'css_handle' => array(
				'handle'    => 'css_handle',
				'src'       => 'css_src',
				'deps'      => array( 'jquery' ),
				'ver'       => '1.0.0',
				'media'     => 'all',        // Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
				'context'   => array(        // Where to enqueue. Options: front, admin, login, block_editor, block.
					'front',
					'admin',
				),
				'has_rtl'   => true / 'replace',
			),
			*/
			'cardealer-google-fonts' => array(
				'handle'    => 'cardealer-google-fonts',
				'src'       => $google_fonts_url,
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'font-awesome-shims' => array(
				'handle'    => 'font-awesome-shims',
				'src'       => CARDEALER_URL . '/fonts/font-awesome/css/v4-shims' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '6.2.1',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
					'admin',
				),
			),
			'font-awesome' => array(
				'handle'    => 'font-awesome',
				'src'       => CARDEALER_URL . '/fonts/font-awesome/css/all' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '6.2.1',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
					'admin',
				),
			),
			'bootstrap' => array(
				'handle'    => 'bootstrap',
				'src'       => CARDEALER_URL . '/css/library/bootstrap/' . ( ( ! is_rtl() ) ? 'bootstrap' : 'bootstrap-rtl' ) . $suffix . '.css',
				'deps'      => array(),
				'ver'       => ( ! is_rtl() ) ? '3.3.5' : '3.3.7',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'jquery-ui' => array(
				'handle'    => 'jquery-ui',
				'src'       => CARDEALER_URL . '/css/library/jquery-ui/jquery-ui' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '1.12.1',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'magnific-popup' => array(
				'handle'    => 'magnific-popup',
				'src'       => CARDEALER_URL . '/css/library/magnific-popup/magnific-popup' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '1.1.0',
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'cardealer-mega-menu' => array(
				'handle'    => 'cardealer-mega-menu',
				'src'       => CARDEALER_URL . '/css/library/mega-menu/mega-menu' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'cardealer-nice-select' => array(
				'handle'    => 'cardealer-nice-select',
				'src'       => CARDEALER_URL . '/css/library/nice-select/nice-select' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'owl-carousel' => array(
				'handle'    => 'owl-carousel',
				'src'       => CARDEALER_URL . '/css/library/owl-carousel/owl-carousel' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '2.3.4',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'photoswipe' => array(
				'handle'    => 'photoswipe',
				'src'       => CARDEALER_URL . '/css/library/photoswipe/photoswipe' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '4.1.3',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'photoswipe-default-skin' => array(
				'handle'    => 'photoswipe-default-skin',
				'src'       => CARDEALER_URL . '/css/library/photoswipe/default-skin/default-skin' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '4.1.3',
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'slick-slider' => array(
				'handle'    => 'slick-slider',
				'src'       => CARDEALER_URL . '/css/library/slick/slick' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'slick-slider-theme' => array(
				'handle'    => 'slick-slider-theme',
				'src'       => CARDEALER_URL . '/css/library/slick/slick-theme' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'register',
				'context'   => array(
					'front',
				),
			),
			'timepicker' => array(
				'handle'    => 'timepicker',
				'src'       => CARDEALER_URL . '/css/library/timepicker/jquery.timepicker' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'select2' => array(
				'handle'    => 'select2',
				'src'       => CARDEALER_URL . '/css/library/select2/select2' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '4.0.13',
				'action'    => 'register',
				'context'   => array(
					'admin',
				),
			),
			'cardealer-jqueryui' => array(
				'handle'    => 'cardealer-jqueryui',
				'src'       => CARDEALER_URL . '/css/library/jquery-ui/jquery-ui' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '1.12.1',
				'action'    => 'register',
				'context'   => array(
					'admin',
				),
			),
			'jquery-confirm-bootstrap' => array(
				'handle'    => 'jquery-confirm-bootstrap',
				'src'       => CARDEALER_URL . '/css/library/jquery-confirm/jquery-confirm-bootstrap' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '3.3.7',
				'action'    => 'register',
				'context'   => array(
					'front',
					'admin',
				),
			),
			'jquery-confirm' => array(
				'handle'    => 'jquery-confirm',
				'src'       => CARDEALER_URL . '/css/library/jquery-confirm/jquery-confirm' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => '3.3.4',
				'action'    => 'register',
				'context'   => array(
					'front',
					'admin',
				),
			),
		);

		$library_styles = apply_filters( 'cardealer_get_library_styles', $library_styles );

		$core_styles = array();

		$core_styles = array(
			'cardealer-flaticon' => array(
				'handle'    => 'cardealer-flaticon',
				'src'       => CARDEALER_URL . '/css/frontend/flaticon' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
					'admin',
				),
			),
			'cardealer-header' => array(
				'handle'    => 'cardealer-header',
				'src'       => CARDEALER_URL . '/css/frontend/header' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
			'cardealer-footer' => array(
				'handle'    => 'cardealer-footer',
				'src'       => CARDEALER_URL . '/css/frontend/footer' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			),
		);

		// Elementor Specific CSS.
		if ( did_action( 'elementor/loaded' ) ) {
			$core_styles['cardealer-elementor'] = array(
				'handle'    => 'cardealer-elementor',
				'src'       => CARDEALER_URL . '/css/frontend/elementor' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => $cardealer_theme_data->get( 'Version' ),
				'action'    => 'enqueue',
				'deps'      => array(
					'magnific-popup',
				),
				'context'   => array(
					'front',
				),
			);
		}

		// WPBakery Specific CSS.
		if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
			$core_styles['cardealer-shortcodes'] = array(
				'handle'    => 'cardealer-shortcodes',
				'src'       => CARDEALER_URL . '/css/frontend/shortcodes' . $suffix . '.css',
				'deps'      => array(
					'magnific-popup',
				),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			);
		}

		$core_styles = array_merge(
			$core_styles,
			array(
				'cardealer-blog' => array(
					'handle'    => 'cardealer-blog',
					'src'       => CARDEALER_URL . '/css/frontend/blog' . $suffix . '.css',
					'deps'      => array(
						'wp-mediaelement',
					),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'register',
					'context'   => array(
						'front',
					),
				),
				'cardealer-sidebar' => array(
					'handle'    => 'cardealer-sidebar',
					'src'       => CARDEALER_URL . '/css/frontend/sidebar.css',
					'deps'      => array(),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'enqueue',
					'context'   => array(
						'front',
					),
				),
				'cardealer-inventory' => array(
					'handle'    => 'cardealer-inventory',
					'src'       => CARDEALER_URL . '/css/frontend/inventory' . $suffix . '.css',
					'deps'      => array(),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'register',
					'context'   => array(
						'front',
					),
				),
				'cardealer-tabs' => array(
					'handle'    => 'cardealer-tabs',
					'src'       => CARDEALER_URL . '/css/frontend/tabs' . $suffix . '.css',
					'deps'      => array(),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'register',
					'context'   => array(
						'front',
					),
				),
				'cardealer-contact-form' => array(
					'handle'    => 'cardealer-contact-form',
					'src'       => CARDEALER_URL . '/css/frontend/contact-form' . $suffix . '.css',
					'deps'      => array(),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'enqueue',
					'context'   => array(
						'front',
					),
				),
				'cardealer-main' => array(
					'handle'    => 'cardealer-main',
					'src'       => CARDEALER_URL . '/css/frontend/style' . $suffix . '.css',
					'deps'      => array(),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'enqueue',
					'context'   => array(
						'front',
					),
				),
				'cardealer-woocommerce' => array(
					'handle'    => 'cardealer-woocommerce',
					'src'       => CARDEALER_URL . '/css/frontend/woocommerce' . $suffix . '.css',
					'deps'      => array(
						'woocommerce-general',
					),
					'ver'       => CARDEALER_VERSION,
					'action'    => 'enqueue',
					'context'   => array(
						'front',
					),
				),
			)
		);

		// Elementor Specific CSS.
		if ( did_action( 'elementor/loaded' ) ) {
			$core_styles['cardealer-elementor-responsive'] = array(
				'handle'    => 'cardealer-elementor-responsive',
				'src'       => CARDEALER_URL . '/css/frontend/elementor-responsive' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => $cardealer_theme_data->get( 'Version' ),
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			);
		}

		// WPBakery Specific CSS.
		if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
			$core_styles['cardealer-shortcodes-responsive'] = array(
				'handle'    => 'cardealer-shortcodes-responsive',
				'src'       => CARDEALER_URL . '/css/frontend/shortcodes-responsive' . $suffix . '.css',
				'deps'      => array(),
				'ver'       => CARDEALER_VERSION,
				'action'    => 'enqueue',
				'context'   => array(
					'front',
				),
			);
		}

		$core_styles['cardealer-main-responsive'] = array(
			'handle'    => 'cardealer-main-responsive',
			'src'       => CARDEALER_URL . '/css/frontend/responsive' . $suffix . '.css',
			'deps'      => array(),
			'ver'       => CARDEALER_VERSION,
			'action'    => 'enqueue',
			'context'   => array(
				'front',
			),
		);

		$core_styles['cardealer-admin-style'] = array(
			'handle'    => 'cardealer-admin-style',
			'src'       => CARDEALER_URL . '/css/admin/admin_style' . $suffix . '.css',
			'deps'      => array(
				'cardealer-jqueryui',
				'font-awesome-shims',
				'font-awesome',
				'cardealer-flaticon',
			),
			'ver'       => CARDEALER_VERSION,
			'action'    => 'enqueue',
			'context'   => array(
				'admin',
			),
		);

		$core_styles['cardealer-bootstrap'] = array(
			'handle'    => 'cardealer-bootstrap',
			'src'       => CARDEALER_URL . '/css/admin/cardealer-bootstrap' . $suffix . '.css',
			'deps'      => array(),
			'ver'       => '1.0.0',
			'action'    => 'register',
			'context'   => array(
				'admin',
			),
		);
		$core_styles['cardealer_templates'] = array(
			'handle'    => 'cardealer_templates',
			'src'       => CARDEALER_URL . '/css/admin/cardealer-templates' . $suffix . '.css',
			'deps'      => array(
				'cardealer-bootstrap',
			),
			'ver'       => CARDEALER_VERSION,
			'action'    => 'register',
			'context'   => array(
				'admin',
			),
		);

		$core_styles = apply_filters( 'cardealer_get_core_styles', $core_styles );

		$styles = apply_filters( 'cardealer_get_styles', array_merge( $library_styles, $core_styles ) );

		return $styles;
	}

	/**
	 * Get Google font URL.
	 */
	private function google_fonts_url() {
		global $car_dealer_options;
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		$font_family = array();
		if ( class_exists( 'Redux' ) ) {
			// body fonts.
			if ( ! empty( $car_dealer_options['opt-typography-body']['font-family'] ) ) {
				$font_family[] = $car_dealer_options['opt-typography-body']['font-family'];
			}

			// heading fonts.
			for ( $h_tag = 1; $h_tag <= 6; $h_tag++ ) {
				if ( isset( $car_dealer_options[ 'opt-typography-h' . $h_tag ] ) && ! empty( $car_dealer_options[ 'opt-typography-h' . $h_tag ] ) ) {
					array_push( $font_family, $car_dealer_options[ 'opt-typography-h' . $h_tag ]['font-family'] );
				}
			}
			$font_family = array_unique( $font_family ); // remove duplicate fonts.
		}

		if ( empty( $font_family ) ) {
			/* translators: If there are characters in your language that are not supported by Open+Sans, translate this to 'off'. Do not translate into your own language. */
			if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'cardealer' ) ) {
				$fonts[] = 'Open Sans:400,300,400italic,600,600italic,700,700italic,800,800italic,300italic';
			}
			/* translators: If there are characters in your language that are not supported by Raleway, translate this to 'off'. Do not translate into your own language. */
			if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'cardealer' ) ) {
				$fonts[] = 'Roboto:100,300,400,500,700,900,100italic,300italic,400italic,700italic,900italic';
			}
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg(
				array(
					'family' => rawurlencode( implode( '|', $fonts ) ),
					'subset' => rawurlencode( $subsets ),
				),
				'https://fonts.googleapis.com/css'
			);
		}
		return $fonts_url;
	}
}

/**
 * Returns the main instance of CarDealer_Assets.
 *
 * @return CarDealer_Assets
 */
function cardealer_assets() {
	return new CarDealer_Assets();
}

function cardealer_assets_enqueue_script( $handle ) {
	cardealer_assets()->enqueue_script( $handle );
}

// Global for backwards compatibility.
$GLOBALS['cardealer_assets'] = cardealer_assets();
