<?php
/**
 * Handle assets.
 *
 * @package cardealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PGS Assets class.
 */
class PGS_Assets {

	private $scripts = array();
	private $styles  = array();
	private $prefix  = 'pgs';
	private $args    = array();

	/**
	 * Set the initial state of the class
	 *
	 * @param array  $scripts The array of the scripts.
	 * @param array  $styles  The array of the styles.
	 * @param string $prefix  Prefix for the assets.
	 * @param array  $args    The array arguments.
	 */
	public function __construct( $prefix = 'pgs', $args = [] ) {
		$this->prefix  = $prefix;
		$this->args    = $args;

		$this->actions();
	}

	/**
	 * Hook in methods.
	 */
	private function actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_front_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_front_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_styles' ) );
	}

	public function get_scripts() {
		$scripts = $this->scripts;
		return apply_filters( 'pgs_assets_get_scripts', $scripts );
	}

	public function get_styles() {
		$styles = $this->styles;
		return apply_filters( 'pgs_assets_get_styles', $styles );
	}

	/**
	 * Register/queue frontend scripts.
	 */
	public function load_front_scripts() {
		$this->load_scripts( 'front' );
	}

	/**
	 * Register/queue frontend styles.
	 */
	public function load_front_styles() {
		$this->load_styles( 'front' );
	}

	/**
	 * Register/queue admin scripts.
	 */
	public function load_admin_scripts() {
		$this->load_scripts( 'admin' );
	}

	/**
	 * Register/queue admin styles.
	 */
	public function load_admin_styles() {
		$this->load_styles( 'admin' );
	}

	/**
	 * Register/queue scripts.
	 */
	public function load_scripts( $context = 'front' ) {
		global $post;

		$scripts = $this->get_scripts();

		do_action( $this->prefix . '_assets_before_scripts', $context );

		foreach( $scripts as $script_key => $script_data ) {

			// Skip assets if not in the context.
			if ( ! in_array( $context, $script_data['context'], true ) ) {
				continue;
			}

			$script_data = apply_filters( $this->prefix . '_assets_script_data', $script_data, $script_key );

			if ( ! $script_data || false === $script_data || empty( $script_data ) ) {
				continue;
			}

			do_action( $this->prefix . '_assets_before_script_action', $script_key, $script_data );

			if ( 'deregister' === $script_data['action'] ) {    // Deregister script.
				$this->deregister_script( $script_data['handle'], $script_data );
			}elseif ( 'register' === $script_data['action'] ) { // Register script.
				if ( isset( $script_data['src'] ) ) {

					$this->register_script( $script_data['handle'], $script_data['src'], $script_data['deps'], $script_data['ver'], $script_data['in_footer'], $script_data );
				}
			}elseif ( 'dequeue' === $script_data['action'] ) {  // Dequeue script.
				$this->dequeue_script( $script_data['handle'], $script_data );
			}elseif ( 'enqueue' === $script_data['action'] ) { // Enqueue script.

				$enqueue_status = apply_filters( $this->prefix . '_assets_enqueue_script_status', true, $context, $script_key, $script_data );

				if ( $enqueue_status ) {

					// Register deps.
					if ( is_array( $script_data['deps'] ) && ! empty( $script_data['deps'] ) ) {
						foreach ( $script_data['deps'] as $dep ) {
							if ( ! wp_script_is( $dep, 'registered' ) && array_key_exists( $dep, $scripts ) && isset( $scripts[ $dep ]['src'] ) ) {
								$this->register_script( $scripts[ $dep ]['handle'], $scripts[ $dep ]['src'], $scripts[ $dep ]['deps'], $scripts[ $dep ]['ver'], $scripts[ $dep ]['in_footer'], $scripts[ $dep ] );
							}
						}
					}

					// Enqueue script.
					$this->enqueue_script( $script_data['handle'], $script_data['src'], $script_data['deps'], $script_data['ver'], $script_data['in_footer'], $script_data );
				}
			}

			do_action( $this->prefix . '_assets_after_script_action', $script_key, $script_data );
		}

		do_action( $this->prefix . '_assets_after_scripts', $context );
	}

	/**
	 * Deregister a script handle.
	 *
	 * @uses  wp_deregister_script()
	 * @param string   $handle       Name of the script. Should be unique.
	 * @param array    $script_data  Array of script data.
	 */
	private function deregister_script( $handle, $script_data ) {
		do_action( $this->prefix . '_assets_before_script_deregister', $handle, $script_data );
		wp_deregister_script( $handle );
		do_action( $this->prefix . '_assets_after_script_deregister', $handle, $script_data );
	}

	/**
	 * Register a script for use.
	 *
	 * @uses  wp_register_script()
	 * @param array    $script_data  Array of script data.
	 * @param string   $handle       Name of the script. Should be unique.
	 * @param string   $path         Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param string[] $deps         An array of registered script handles this script depends on.
	 * @param string   $version      String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param boolean  $in_footer    Whether to enqueue the script before </body> instead of in the <head>. Default 'true'.
	 */
	private function register_script( $handle, $path, $deps = array( 'jquery' ), $version = CARDEALER_VERSION, $in_footer = true, $script_data = array() ) {
		do_action( $this->prefix . '_assets_before_script_register', $handle, $script_data );
		wp_register_script( $handle, $path, $deps, $version, $in_footer );

		// Localize JS
		do_action( $this->prefix . '_assets_before_script_localize', $handle, $script_data );

		if ( isset( $script_data['localize'] ) && is_array( $script_data['localize'] ) && ! empty( $script_data['localize'] ) ) {
			foreach ( $script_data['localize'] as $localize_name => $localize_data ) {
				if ( is_string( $localize_name ) && is_array( $localize_data ) ) {
					wp_localize_script( $script_data['handle'], $localize_name, $localize_data );
				}
			}
		}

		do_action( $this->prefix . '_assets_after_script_localize', $handle, $script_data );

		// Add Inline JS
		do_action( $this->prefix . '_assets_before_script_inline_script', $handle, $script_data );

		if ( isset( $script_data['inline_script'] ) && ! empty( $script_data['inline_script'] ) ) {
			$inline_scripts = (array) $script_data['inline_script'];
			foreach ( $inline_scripts as $inline_script ) {
				$inline_script = wp_strip_all_tags( $inline_script );
				wp_add_inline_script( $script_data['handle'], $inline_script );
			}
		}

		do_action( $this->prefix . '_assets_after_script_inline_script', $handle, $script_data );

		do_action( $this->prefix . '_assets_after_script_register', $handle, $script_data );

	}

	/**
	 * dequeue a script.
	 *
	 * @uses  wp_dequeue_script()
	 * @param string   $handle       Name of the script. Should be unique.
	 * @param array    $script_data  Array of script data.
	 */
	private function dequeue_script( $handle, $script_data ) {
		do_action( $this->prefix . '_assets_before_script_dequeue', $handle, $script_data );
		wp_dequeue_script( $handle );
		do_action( $this->prefix . '_assets_after_script_dequeue', $handle, $script_data );
	}

	/**
	 * Register and enqueue a script for use.
	 *
	 * @uses  wp_enqueue_script()
	 * @param string   $handle       Name of the script. Should be unique.
	 * @param string   $path         Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param string[] $deps         An array of registered script handles this script depends on.
	 * @param string   $version      String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param boolean  $in_footer    Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
	 * @param array    $script_data  Array of script data.
	 */
	public function enqueue_script( $handle, $path = '', $deps = array( 'jquery' ), $version = CARDEALER_VERSION, $in_footer = true, $script_data = array() ) {
		if ( ! wp_script_is( $handle, 'registered' ) && $path ) {
			$this->register_script( $handle, $path, $deps, $version, $in_footer, $script_data );
		}

		do_action( $this->prefix . '_assets_before_script_enqueue', $handle, $script_data );
		wp_enqueue_script( $handle );
		do_action( $this->prefix . '_assets_after_script_enqueue', $handle, $script_data );
	}

	/**
	 * Register/queue styles.
	 */
	public function load_styles( $context = 'front' ) {
		global $post;

		$styles = $this->get_styles();

		foreach( $styles as $style_key => $style_data ) {

			// Skip assets if not in the context.
			if ( ! in_array( $context, $style_data['context'], true ) ) {
				continue;
			}

			$style_data_defaults = array(
				'handle'  => '',
				'src'     => '',
				'deps'    => array(),
				'ver'     => '',
				'media'   => 'all',
				'action'  => 'enqueue',
				'context' => array(),
			);

			$style_data = apply_filters( $this->prefix . '_assets_style_data', $style_data, $style_key );

			if ( ! $style_data || false === $style_data || empty( $style_data ) ) {
				continue;
			}

			do_action( $this->prefix . '_assets_before_style_action', $style_key, $style_data );

			if ( 'deregister' === $style_data['action'] ) {    // Deregister style.
				$this->deregister_style( $style_data['handle'], $style_data );
			}elseif ( 'register' === $style_data['action'] ) { // Register style.
				if ( isset( $style_data['src'] ) ) {
					$style_data = wp_parse_args( $style_data, $style_data_defaults );
					$this->register_style( $style_data, $style_data['handle'], $style_data['src'], $style_data['deps'], $style_data['ver'], $style_data['media'] );
				}
			}elseif ( 'dequeue' === $style_data['action'] ) {  // Dequeue style.
				$this->dequeue_style( $style_data['handle'], $style_data );
			}elseif ( 'enqueue' === $style_data['action'] ) { // Enqueue style.

				$enqueue_status = apply_filters( $this->prefix . '_assets_enqueue_style_status', true, $style_key, $style_data );

				if ( $enqueue_status ) {

					// Register deps.
					if ( isset( $style_data['deps'] ) && is_array( $style_data['deps'] ) && ! empty( $style_data['deps'] ) ) {
						foreach ( $style_data['deps'] as $dep ) {
							if ( ! wp_style_is( $dep, 'registered' ) && array_key_exists( $dep, $styles ) && isset( $styles[ $dep ]['src'] ) ) {
								$styles[ $dep ] = wp_parse_args( $styles[ $dep ], $style_data_defaults );
								$this->register_style( $styles[ $dep ], $styles[ $dep ]['handle'], $styles[ $dep ]['src'], $styles[ $dep ]['deps'], $styles[ $dep ]['ver'], $styles[ $dep ]['media'] );
							}
						}
					}

					// Enqueue style.
					$style_data = wp_parse_args( $style_data, $style_data_defaults );
					$this->enqueue_style( $style_data, $style_data['handle'], $style_data['src'], $style_data['deps'], $style_data['ver'], $style_data['media'] );

					if ( isset( $style_data['inline_style'] ) && ! empty( $style_data['inline_style'] ) ) {
						$inline_styles = (array) $style_data['inline_style'];
						foreach ( $inline_styles as $inline_style ) {
							$inline_style = wp_strip_all_tags( $inline_style );
							wp_add_inline_style( $style_data['handle'], $inline_style );
						}
					}
				}
			}

			do_action( $this->prefix . '_assets_after_style_action', $style_key, $style_data );
		}
	}

	/**
	 * Deregister a script handle.
	 *
	 * @uses  wp_deregister_style()
	 * @param string   $handle      Name of the style. Should be unique.
	 * @param array    $style_data  Array of style data.
	 */
	private function deregister_style( $handle, $style_data ) {
		do_action( $this->prefix . '_assets_before_style_deregister', $handle, $style_data );
		wp_deregister_style( $handle );
		do_action( $this->prefix . '_assets_after_style_deregister', $handle, $style_data );
	}

	/**
	 * Register a style for use.
	 *
	 * @uses   wp_register_style()
	 * @param array    $style_data  Array of style data.
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 */
	private function register_style( $style_data, $handle, $path, $deps = array(), $version = CARDEALER_VERSION, $media = 'all' ) {
		do_action( $this->prefix . '_assets_before_style_register', $handle, $style_data );
		wp_register_style( $handle, $path, $deps, $version, $media );
		do_action( $this->prefix . '_assets_after_style_register', $handle, $style_data );

		$has_rtl = ( isset( $style_data['has_rtl'] ) && ! empty( $style_data['has_rtl'] ) ) ? $style_data['has_rtl'] : false;

		if ( $has_rtl ) {
			if ( is_string( $has_rtl ) ) {
				if ( 'replace' === $has_rtl ) {
					wp_style_add_data( $handle, 'rtl', 'replace' );
				}
			} elseif ( true === $has_rtl ) {
				wp_style_add_data( $handle, 'rtl', true );
			}
		}
	}

	/**
	 * dequeue a style.
	 *
	 * @uses  wp_dequeue_style()
	 * @param string   $handle       Name of the style. Should be unique.
	 * @param array    $style_data  Array of style data.
	 */
	private function dequeue_style( $handle, $style_data ) {
		do_action( $this->prefix . '_assets_before_style_dequeue', $handle, $style_data );
		wp_dequeue_style( $handle );
		do_action( $this->prefix . '_assets_after_style_dequeue', $handle, $style_data );
	}

	/**
	 * Register and enqueue a styles for use.
	 *
	 * @uses   wp_enqueue_style()
	 * @param  string   $handle  Name of the stylesheet. Should be unique.
	 * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
	 * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
	 * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
	 * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param array    $style_data  Array of style data.
	 */
	private function enqueue_style( $style_data, $handle, $path = '', $deps = array(), $version = CARDEALER_VERSION, $media = 'all' ) {
		if ( ! wp_style_is( $handle, 'registered' ) && $path ) {
			$this->register_style( $style_data, $handle, $path, $deps, $version, $media );
		}

		do_action( $this->prefix . '_assets_before_style_enqueue', $handle, $style_data );
		wp_enqueue_style( $handle );
		do_action( $this->prefix . '_assets_after_style_enqueue', $handle, $style_data );
	}
}
