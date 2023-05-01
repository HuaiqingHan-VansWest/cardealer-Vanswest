<?php
defined( 'ABSPATH' ) || die();

use Elementor\Controls_Stack;
use Elementor\Plugin;
use Elementor\Utils;

// use Elementor\Api;
// use Elementor\Plugin;
// use Elementor\TemplateLibrary\Source_Remote;
// use Elementor\TemplateLibrary\Source_Base;

if ( ! class_exists( 'PGS_Library' ) ) {

	/**
	 * PGS_Library class.
	 *
	 * @since 1.0.0
	 */
	class PGS_Library {

		/**
		 * PGS_Library instance.
		 * @access private
		 * @var PGS_Library
		 */
		private static $instance;

		/**
		 * PGS_Library version.
		 * @access private
		 * @var string
		 */
		private static $version = '1.0.0';

		private static $pgs_library_title       = 'PGS Library';
		private static $pgs_library_id          = 'pgs_library';
		private static $pgs_library_slug        = 'pgs-library';
		private static $external_url            = 'https://www.example.com';
		private static $pgs_library_popup_title = 'PGS Library';

		/**
		 * PGS_Library directory.
		 * @access private
		 * @var string
		 */
		private static $pgs_library_dir;

		/**
		 * PGS_Library URL.
		 * @access private
		 * @var string
		 */
		private static $pgs_library_url;

		/**
		 * PGS_Library templates directory.
		 * @access private
		 * @var string
		 */
		private static $pgs_library_templates_dir;

		/**
		 * PGS_Library templates URL.
		 * @access private
		 * @var string
		 */
		private static $pgs_library_templates_url;

		/**
		 * Utils instance.
		 * @var PGS_Library_Utils
		 */
		public $utils = null;

		/**
		 * Constants.
		 * @access public
		 * @var array
		 */
		public $constants = array();

		/**
		 * Arguments.
		 * @access private
		 * @var array
		 */
		private static $args = array();

		/**
		 * Constructor.
		 */
		public function __construct( $args ) {
			self::$args = $args;
			$this->define_constants();
			$this->init_hooks();
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_enqueue_styles' ], 0 );
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_enqueue_scripts' ], 0 );
		}

		/**
		 * Defines constants used by the plugin.
		 */
		private function define_constants() {
			$defaults = array(
				'pgs_library_title'         => esc_html__( 'PGS Library', 'cardealer' ),
				'pgs_library_id'            => 'pgs_library',
				'pgs_library_slug'          => 'pgs-library',
				'pgs_library_dir'           => wp_normalize_path( dirname( __FILE__ ) ),
				'pgs_library_url'           => str_replace( wp_normalize_path( untrailingslashit( get_template_directory() ) ), get_template_directory_uri(), wp_normalize_path( dirname( __FILE__ ) ) ),
				'pgs_library_templates_dir' => trailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) . 'templates',
				'pgs_library_templates_url' => str_replace( wp_normalize_path( untrailingslashit( get_template_directory() ) ), get_template_directory_uri(), trailingslashit( wp_normalize_path( dirname( __FILE__ ) ) ) . 'templates' ),
			);

			$this->constants = wp_parse_args( self::$args, $defaults );

			self::$pgs_library_title         = $this->constants['pgs_library_title'];
			self::$pgs_library_popup_title   = ( isset( $this->constants['pgs_library_popup_title'] ) && ! empty( $this->constants['pgs_library_popup_title'] ) ) ? $this->constants['pgs_library_popup_title'] : $this->constants['pgs_library_title'];
			self::$pgs_library_id            = $this->constants['pgs_library_id'];
			self::$pgs_library_slug          = $this->constants['pgs_library_slug'];
			self::$pgs_library_dir           = $this->constants['pgs_library_dir'];
			self::$pgs_library_url           = $this->constants['pgs_library_url'];
			self::$pgs_library_templates_dir = $this->constants['pgs_library_templates_dir'];
			self::$pgs_library_templates_url = $this->constants['pgs_library_templates_url'];

		}

		/**
		 * Initializes the plugin.
		 */
		private function init_hooks() {

			if ( ! class_exists( '\Elementor\Plugin' ) ) {
				return;
			}

			// Hook actions.
			$this->add_actions();

			/**
			 * Fires after all files have been loaded.
			 * @param PGS_Library
			 */
			do_action( 'pgs_pgs_library_init', $this );
		}

		/**
		 * Adds required action hooks.
		 * @access protected
		 */
		protected function add_actions() {

			// Editor Assets
			add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_enqueue_styles' ], 0 );
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_enqueue_scripts' ], 0 );

			// Front Assets
			add_action( 'elementor/preview/enqueue_styles', [ $this, 'preview_enqueue_styles' ] );

			add_action( 'wp_ajax_pgs_library_sync_library', [ $this, 'sync_library' ] );
			add_action( 'wp_ajax_pgs_library_get_template', [ $this, 'get_template' ] );
			add_action( 'elementor/editor/footer', [ $this, 'editor_templates' ] );

		}

		/**
		 * Returns the version number of the plugin.
		 * @return string
		 */
		public function version() {
			return self::$version;
		}

		/**
		 * Returns the plugin directory.
		 * @return string
		 */
		public static function get_dir() {
			return self::$pgs_library_dir;
		}

		/**
		 * Returns the plugin URL.
		 * @return string
		 */
		public function get_url() {
			return self::$pgs_library_url;
		}

		/**
		 * Enqueue styles.
		 * Enqueue all the editor styles.
		 * Fires after Elementor editor styles are enqueued.
		 * @access public
		 */
		public function editor_enqueue_styles() {
			$suffix = \Elementor\Utils::is_script_debug() ? '' : '.min';

			wp_enqueue_style(
				self::$pgs_library_slug . '-editor',
				trailingslashit( self::$pgs_library_url ) . 'assets/css/editor.css', // trailingslashit( self::$pgs_library_url ) . 'assets/css/editor' . $suffix . '.css',
				array(),
				self::$version
			);
		}

		/**
		 * Enqueue scripts.
		 * Enqueue all the editor scripts.
		 * Fires after Elementor editor scripts are enqueued.
		 * @access public
		 */
		public function editor_enqueue_scripts() {
			$suffix = \Elementor\Utils::is_script_debug() ? '' : '.min';

			wp_enqueue_script(
				self::$pgs_library_slug . '-editor',
				trailingslashit( self::$pgs_library_url ) . 'assets/js/editor.js', // trailingslashit( self::$pgs_library_url ) . 'assets/js/editor' . $suffix . '.js',
				array( 'jquery', 'wp-i18n' ),
				self::$version,
				true
			);

			wp_set_script_translations(
				self::$pgs_library_slug . '-editor',
				apply_filters( 'pgs_library_script_translations_domain', 'cardealer', self::$pgs_library_id ),
				apply_filters( 'pgs_library_script_translations_path', get_parent_theme_file_path( '/languages' ), self::$pgs_library_id )
			);

			wp_localize_script(
				self::$pgs_library_slug . '-editor',
				'pgs_library_data',
				array(
					'obj_name' => self::$pgs_library_id,
				)
			);
			wp_localize_script(
				self::$pgs_library_slug . '-editor',
				self::$pgs_library_id,
				apply_filters(
					self::$pgs_library_slug . '-editor',
					array(
						'ajax_url'                      => admin_url( 'admin-ajax.php' ),
						'ajax_nonce'                    => wp_create_nonce( 'pgs_library_nonce' ),
						'pgs_library_slug'              => self::$pgs_library_slug,
						'pgs_library_id'                => self::$pgs_library_id,
						'pgs_library_btn_title'         => esc_html__( 'Add PGS Library Template', 'cardealer' ),
						'pgs_library_btn_class'         => self::$pgs_library_slug . '-add-template-button',
						'pgs_library_btn_logo'          => trailingslashit( self::$pgs_library_url ) . 'assets/img/studio-icon.png',
						'pgs_library_mdl_icon'          => trailingslashit( self::$pgs_library_url ) . 'assets/img/studio-icon.png',
						'pgs_library_mdl_icon_bg_color' => '#0aa594',
						'pgs_library_mdl_title'         => self::$pgs_library_popup_title,
						'translations'                  => array(
							'submit_n_deactivate' => esc_html__( 'Submit & Deactivate', 'cardealer' ),
							'skip_n_deactivate'   => esc_html__( 'Skip & Deactivate', 'cardealer' ),
							'library_fetch_error' => esc_html__( 'Unable to fetch templates from the server.', 'cardealer' ),
							'library_sync_error'  => esc_html__( 'Unable to sync templates from the server.', 'cardealer' ),
						),
					),
					self::$pgs_library_slug
				)
			);
		}

		/**
		 * Enqueue preview styles.
		 * Enqueue all the preview styles.
		 * @access public
		 */
		public function preview_enqueue_styles() {
			$suffix = \Elementor\Utils::is_script_debug() ? '' : '.min';

			wp_enqueue_style(
				self::$pgs_library_slug . '-preview',
				trailingslashit( self::$pgs_library_url ) . 'assets/css/preview.css', // trailingslashit( self::$pgs_library_url ) . 'assets/css/preview' . $suffix . '.css',
				array(),
				self::$version
			);
		}

		/**
		 * Sync library.
		 * @access public
		 * @return void
		 */
		public function sync_library() {
			if ( ! check_ajax_referer( 'pgs_library_nonce', 'security', false ) ) {
				wp_send_json_error( esc_html__( 'Unable to verify security.', 'cardealer' ), 403 );
			}

			$templates = $this->get_templates();

			if ( empty( $templates ) ) {
				wp_send_json_error( esc_html__( 'No templates found.', 'cardealer' ) );
			}

			wp_send_json_success( $templates );
		}

		/**
		 * Loads all PHP files in a given directory.
		 *
		 * @param string $directory_name The directory name to load the files.
		 */
		public static function load_directory( $directory_name, $args = array() ) {
			$path       = trailingslashit( trailingslashit( self::get_dir() ) . '/' . $directory_name );
			$file_names = glob( $path . '*.php' );
			foreach ( $file_names as $filename ) {
				if ( file_exists( $filename ) ) {
					require_once $filename;
				}
			}
		}

		/**
		 * Loads specified PHP files from the plugin includes directory.
		 * @param array $file_names The names of the files to be loaded in the includes directory.
		 */
		public static function load_files( $file_names = [] ) {
			foreach ( $file_names as $file_name ) {
				$path = trailingslashit( self::get_dir() ) . $file_name;

				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}
		}

		/**
		 * Sync library.
		 * @access public
		 * @return void
		 */
		public function get_template() {
			if ( ! check_ajax_referer( 'pgs_library_nonce', 'security', false ) ) {
				wp_send_json_error( esc_html__( 'Unable to verify security.', 'cardealer' ), 403 );
			}

			$template_id         = sanitize_text_field( wp_unslash( $_POST['template_id'] ) );
			$editor_post_id      = sanitize_text_field( wp_unslash( $_POST['editor_post_id'] ) );
			$templates_directory = $this->get_templates_dir();
			$template_dir        = trailingslashit( $templates_directory ) . $template_id;
			$template_file       = trailingslashit( $template_dir ) . 'template.json';

			if ( ! is_dir( $template_dir ) || ! file_exists( $template_file ) ) {
				wp_send_json_error( esc_html__( 'Template not found.', 'cardealer' ) );
			}

			ob_start();
			include_once( $template_file );
			$json_data = ob_get_contents();
			ob_end_clean();

			try {
				$template_data = json_decode( $json_data, true );
			} catch ( Exception $e ) {
				wp_send_json_error( esc_html__( 'Template data file is invalid.', 'cardealer' ) );
			}

			if ( ! $template_data ) {
				wp_send_json_error( esc_html__( 'Template data is broken.', 'cardealer' ) );
			}

			$template_data['content'] = $this->replace_elements_ids( $template_data['content'] );
			$template_data['content'] = $this->process_export_import_content( $template_data['content'], 'on_import' );

			$document = Plugin::$instance->documents->get( $editor_post_id );
			if ( $document ) {
				$template_data['content'] = $document->get_elements_raw_data( $template_data['content'], true );
			}
			wp_send_json_success( $template_data );
		}

		/**
		 * Get templates.
		 * @param array $args Optional.
		 * @return array.
		 */
		public function get_templates( $args = array() ) {
			$data       = array();
			$templates  = array();
			$categories = array();
			$tags       = array();

			// Read Local Directory
			$templates_directory_path = $this->get_templates_dir();
			$templates_directory_url  = $this->get_templates_url();
			$handler                  = opendir( $templates_directory_path );

			while ( $handler && false !== ( $directory_name = readdir( $handler ) ) ) {
				if ( in_array( $directory_name, ['.', '..', 'demo-element'] ) ) {
					continue;
				}

				// Check if we have a val;id directory.
				$template_dir = trailingslashit( $templates_directory_path ) . $directory_name;
				if ( ! is_dir( $template_dir ) ) {
					continue;
				}

				// Make sure we have mandatory files.
				$mandatory_files_missing = false;
				foreach ( array( 'thumbnail.jpg', 'config.php', 'template.json' ) as $file ) {
					if ( ! file_exists( $template_dir . DIRECTORY_SEPARATOR . $file ) ) {
						$mandatory_files_missing = true;
						break;
					}
				}

				// Skip if mandatory files not found.
				if ( $mandatory_files_missing ) {
					continue;
				}

				$template_dir_url = trailingslashit( $templates_directory_url ) . $directory_name;
				$template_id      = sanitize_title_with_dashes( $directory_name );
				$external_url     = self::$external_url;
				$template_data    = require_once( $template_dir . DIRECTORY_SEPARATOR . 'config.php' );

				if (
					! isset( $template_data['title'] )
					|| ! isset( $template_data['type'] )
					|| ! isset( $template_data['category'] )
				) {
					continue;
				}

				// Process Categories
				$template_categories = array();
				foreach ( (array) $template_data['category'] as $template_category ) {
					$template_categories[ sanitize_title_with_dashes( $template_category ) ] = $template_category;
				}
				$template_data['category'] = $template_categories;
				$categories = array_merge( $categories, $template_data['category'] );

				// Process Tags
				$template_tags = array();
				foreach ( (array) $template_data['tags'] as $template_tag ) {
					$template_tags[ sanitize_title_with_dashes( $template_tag ) ] = $template_tag;
				}
				$template_data['tags'] = $template_tags;
				$tags = array_merge( $tags, $template_data['tags'] );

				$args = array(
					'template_id' => $template_id,
					'thumbnail'   => trailingslashit( $template_dir_url ) . 'thumbnail.jpg',
					'preview'     => trailingslashit( self::$external_url ) . "$directory_name.jpg",
				);

				$templates[ $template_id ] = $this->prepare_template( $args, $template_data );
			}

			if ( ! empty( $templates ) ) {
				ksort( $categories );
				$categories = array_merge(
					array(
						'all' => esc_html__( 'All', 'cardealer' ),
					),
					$categories
				);
				$data['categories'] = $categories;
				$data['tags']       = $tags;
				$data['templates']  = $templates;
			}

			return $data;
		}

		/**
		 * @access private
		 */
		private function prepare_template( $args, array $template_data ) {
			return array(
				'template_id'     => $args['template_id'],
				'title'           => $template_data['title'],
				'thumbnail'       => $args['thumbnail'],
				'preview'         => $args['preview'],
				'demo_url'        => isset( $template_data['demo_url'] ) ? $template_data['demo_url'] : '',
				'type'            => $template_data['type'],
				'category'        => isset( $template_data['category'] ) ? $template_data['category'] : array(),
				'tags'            => isset( $template_data['tags'] ) ? $template_data['tags'] : array(),
			);
		}

		private function get_templates_dir() {
			return apply_filters(
				'pgses_get_templates_dir',
				self::$pgs_library_templates_dir
			);
		}

		private function get_templates_url() {
			return apply_filters(
				'pgses_get_templates_url',
				self::$pgs_library_templates_url
			);
		}

		function editor_templates() {
			$this->load_directory( 'editor-templates' );
		}

		/**
		 * Replace elements IDs.
		 *
		 * For any given Elementor content/data, replace the IDs with new randomly
		 * generated IDs.
		 *
		 * @since 1.0.0
		 * @access protected
		 *
		 * @param array $content Any type of Elementor data.
		 *
		 * @return mixed Iterated data.
		 */
		protected function replace_elements_ids( $content ) {
			return Plugin::$instance->db->iterate_data( $content, function( $element ) {
				$element['id'] = Utils::generate_random_string();

				return $element;
			} );
		}

		/**
		 * Process content for export/import.
		 *
		 * Process the content and all the inner elements, and prepare all the
		 * elements data for export/import.
		 *
		 * @since 1.5.0
		 * @access protected
		 *
		 * @param array  $content A set of elements.
		 * @param string $method  Accepts either `on_export` to export data or
		 *                        `on_import` to import data.
		 *
		 * @return mixed Processed content data.
		 */
		protected function process_export_import_content( $content, $method ) {
			return Plugin::$instance->db->iterate_data(
				$content, function( $element_data ) use ( $method ) {
					$element = Plugin::$instance->elements_manager->create_element_instance( $element_data );

					// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
					if ( ! $element ) {
						return null;
					}

					return $this->process_element_export_import_content( $element, $method );
				}
			);
		}

		/**
		 * Process single element content for export/import.
		 *
		 * Process any given element and prepare the element data for export/import.
		 *
		 * @since 1.5.0
		 * @access protected
		 *
		 * @param Controls_Stack $element
		 * @param string         $method
		 *
		 * @return array Processed element data.
		 */
		protected function process_element_export_import_content( Controls_Stack $element, $method ) {
			$element_data = $element->get_data();

			if ( method_exists( $element, $method ) ) {
				// TODO: Use the internal element data without parameters.
				$element_data = $element->{$method}( $element_data );
			}

			foreach ( $element->get_controls() as $control ) {
				$control_class = Plugin::$instance->controls_manager->get_control( $control['type'] );

				// If the control isn't exist, like a plugin that creates the control but deactivated.
				if ( ! $control_class ) {
					return $element_data;
				}

				if ( method_exists( $control_class, $method ) ) {
					$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
				}

				// On Export, check if the control has an argument 'export' => false.
				if ( 'on_export' === $method && isset( $control['export'] ) && false === $control['export'] ) {
					unset( $element_data['settings'][ $control['name'] ] );
				}
			}

			return $element_data;
		}

	}
}

/**
 * Returns the PGS_Library application instance.
 * @return PGS_Library
 */
function pgs_library( $args = array() ) {
	return new PGS_Library( $args );
}
