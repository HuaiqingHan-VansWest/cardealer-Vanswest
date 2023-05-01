<?php
/**
 * Handle CarDealer Templates.
 *
 * @package cardealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CarDealer_Template class.
 */
class CarDealer_Template {

	/**
	 * The single instance of the class.
	 *
	 * @var CarDealer_Template
	 */
	protected static $_instance = null;

	/**
	 * Template URL.
	 * @access public
	 * @var string
	 */
	public static $templates_url;

	/**
	 * Template directory.
	 * @access public
	 * @var string
	 */
	public static $templates_dir;

	/**
	 * @var array
	 */
	public $templates = array();

	/**
	 * Main CarDealer_Template Instance.
	 *
	 * Ensures only one instance of CarDealer_Template is loaded or can be loaded.
	 *
	 * @static
	 * @return CarDealer_Template - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		self::$templates_dir = trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-templates/templates';
		self::$templates_url = trailingslashit( CARDEALER_INC_URL ) . 'cardealer-templates/templates';

		$this->templates = $this->get_templates();
	}

	private function get_templates_dir() {
		return apply_filters( 'cardealer_templates_dir', self::$templates_dir );
	}

	private function get_templates_url() {
		return apply_filters( 'cardealer_templates_url', self::$templates_url );
	}

	/**
		 * Get templates.
		 * @param array $args Optional.
		 * @return array.
		 */
		public function get_templates() {
			$templates = array();

			// Read Local Directory
			$templates_directory_path = $this->get_templates_dir();
			$templates_directory_url  = $this->get_templates_url();
			$handler                  = opendir( $templates_directory_path );

			while ( $handler && false !== ( $directory_name = readdir( $handler ) ) ) {

				if ( in_array( $directory_name, ['.', '..', 'demo-element'] ) ) {
					continue;
				}

				$wpbakery_data  = array();
				$elementor_data = array();

				// Check if we have a val;id directory.
				$template_dir = trailingslashit( $templates_directory_path ) . $directory_name;
				$template_url = trailingslashit( $templates_directory_url ) . $directory_name;
				if ( ! is_dir( $template_dir ) ) {
					continue;
				}

				// Skip if config file not exists.
				if ( ! file_exists( $template_dir . DIRECTORY_SEPARATOR . 'config.php' ) ) {
					continue;
				}

				// Get config data.
				$config_data = require_once( $template_dir . DIRECTORY_SEPARATOR . 'config.php' );

				// Skip if necessary config data not exists.
				if (
					( ! isset( $config_data['title'] ) || empty( $config_data['title'] ) )
					|| ( ! isset( $config_data['template_type'] ) || empty( $config_data['template_type'] ) )
					|| ( ! isset( $config_data['thumbnail'] ) || empty( $config_data['thumbnail'] ) )
				) {
					continue;
				}

				// Skip if thumbnail not exists.
				if ( ! file_exists( $template_dir . DIRECTORY_SEPARATOR . $config_data['thumbnail'] ) ) {
					continue;
				}

				$config_data['thumbnail_url']  = $template_url . '/' . $config_data['thumbnail'];
				$config_data['thumbnail_path'] = $template_dir . DIRECTORY_SEPARATOR . $config_data['thumbnail'];

				$template_found = false;
				if ( file_exists( $template_dir . DIRECTORY_SEPARATOR . 'wpbakery.php' ) ) {
					$template_found = true;
					$wpbakery_data  = wp_parse_args(
						$config_data,
						array(
							'id'            => 'wpbakery-' . $directory_name,
							'builder_type'  => 'wpbakery',
							'template_slug' => $directory_name,
							'template_path' => $template_dir,
							'template_file' => $template_dir . DIRECTORY_SEPARATOR . 'wpbakery.php',
						)
					);
					$templates[ 'wpbakery-' . $directory_name ] = $wpbakery_data;
				}

				if ( file_exists( $template_dir . DIRECTORY_SEPARATOR . 'elementor.json' ) ) {
					$template_found = true;
					$elementor_data  = wp_parse_args(
						$config_data,
						array(
							'id'            => 'elementor-' . $directory_name,
							'builder_type'  => 'elementor',
							'template_slug' => $directory_name,
							'template_path' => $template_dir,
							'template_file' => $template_dir . DIRECTORY_SEPARATOR . 'elementor.json',
						)
					);
					$templates[ 'elementor-' . $directory_name ] = $elementor_data;
				}

				// Skip if no templates found.
				if ( ! $template_found ) {
					continue;
				}

				$template_dir_url = trailingslashit( $templates_directory_url ) . $directory_name;
				$template_id      = sanitize_title_with_dashes( $directory_name );


				$args = array(
					'template_id' => $template_id,
					'thumbnail'   => trailingslashit( $template_dir_url ) . 'thumbnail.jpg',
				);
			}

			return $templates;
		}

}
