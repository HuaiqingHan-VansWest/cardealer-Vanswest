<?php
/**
 * Envato Theme Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their ThemeForest theme.
 *
 * @author      dtbaker, vburlak
 * @package     envato_wizard
 * @version     1.2.4
 *
 *
 * 1.2.0 - added custom_logo
 * 1.2.1 - ignore post revisioins
 * 1.2.2 - elementor widget data replace on import
 * 1.2.3 - auto export of content.
 * 1.2.4 - fix category menu links
 *
 * Based off the WooThemes installer.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Envato_Theme_Setup_Wizard' ) ) {
	/**
	 * Envato_Theme_Setup_Wizard class
	 */
	class Envato_Theme_Setup_Wizard {

		/**
		 * The class version number.
		 *
		 * @since 1.1.1
		 * @access private
		 *
		 * @var string
		 */
		protected $version = '1.2.4';

		/**
		 * Current theme data.
		 *
		 * @access private
		 *
		 * @var mixed
		 */
		protected $theme_data = '';

		/**
		 * Current theme name, used as namespace in actions.
		 *
		 * @var string
		 */
		protected $theme_name = '';

		/**
		 * Current Step
		 *
		 * @var string
		 */
		protected $step = '';

		/**
		 * Steps for the setup wizard
		 *
		 * @var array
		 */
		protected $steps = array();

		/**
		 * Relative plugin path
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $plugin_path = '';

		/**
		 * Relative plugin url for this plugin folder, used when enquing scripts
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $plugin_url = '';

		/**
		 * The slug name to refer to this menu
		 *
		 * @since 1.1.1
		 *
		 * @var string
		 */
		protected $page_slug;

		/**
		 * TGMPA instance storage
		 *
		 * @var object
		 */
		protected $tgmpa_instance;

		/**
		 * TGMPA Menu slug
		 *
		 * @var string
		 */
		protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

		/**
		 * TGMPA Menu url
		 *
		 * @var string
		 */
		protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

		/**
		 * The slug name for the parent menu
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $page_parent;

		/**
		 * Complete URL to Setup Wizard
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		public $page_url;

		/**
		 * Sample datas.
		 *
		 * @var mixed
		 */
		protected $sample_datas;

		/**
		 * Themeforest profile URL.
		 *
		 * @var string
		 */
		protected $themeforest_profile_url;

		/**
		 * Holds the current instance of the theme manager
		 *
		 * @since 1.1.3
		 * @var Envato_Theme_Setup_Wizard
		 */
		private static $instance = null;

		/**
		 * API URL.
		 *
		 * @var string
		 */
		public $api_url;

		/**
		 * Function get_instance.
		 *
		 * @since 1.1.3
		 *
		 * @return Envato_Theme_Setup_Wizard
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function __construct() {
			$this->init_globals();
			$this->init_actions();
		}

		/**
		 * Get the default style. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.7
		 * @access public
		 */
		public function get_default_theme_style() {
			return 'pink';
		}

		/**
		 * Get the default style. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.9
		 * @access public
		 */
		public function get_header_logo_width() {
			return apply_filters( 'envato_theme_setup_wizard_header_logo_width', '200px' );
		}


		/**
		 * Get the default style. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.9
		 * @access public
		 */
		public function get_logo_image() {
			$image_url = trailingslashit( $this->plugin_url ) . 'images/logo.png';
			return apply_filters( 'envato_setup_logo_image', $image_url );
		}

		/**
		 * Setup the class globals.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_globals() {
			$current_theme       = wp_get_theme();
			$current_child_theme = false;

			if ( is_child_theme() ) {
				$current_child_theme = $current_theme;
				$current_theme       = wp_get_theme( $current_theme->get( 'Template' ) );
			}

			$this->theme_data              = $current_theme;
			$this->theme_slug              = apply_filters( 'envato_theme_setup_wizard_theme_slug', sanitize_title( $current_theme->get( 'Name' ) ) );
			$this->theme_name              = apply_filters( 'envato_theme_setup_wizard_theme_name', str_replace( '-', '_', $this->theme_slug ) );
			$this->page_slug               = apply_filters( 'envato_theme_setup_wizard_page_slug', $this->theme_name . '-setup' );
			$this->parent_slug             = apply_filters( 'envato_theme_setup_wizard_parent_slug', '' );
			$this->page_url                = apply_filters( 'envato_theme_setup_wizard_page_url', ( '' !== $this->parent_slug ) ? 'admin.php?page=' . $this->page_slug : 'themes.php?page=' . $this->page_slug );
			$this->api_url                 = apply_filters( 'envato_theme_setup_wizard_api_url', '' );
			$this->sample_datas            = apply_filters( 'envato_theme_setup_wizard_styles', array() );
			$this->themeforest_profile_url = apply_filters( 'envato_theme_setup_wizard_themeforest_profile_url', array() );

			// set relative plugin path url.
			$this->plugin_path = trailingslashit( $this->clean_file_path( dirname( __FILE__ ) ) );
			$relative_url      = str_replace( $this->clean_file_path( get_parent_theme_file_path() ), '', $this->plugin_path );
			$this->plugin_url  = get_parent_theme_file_uri( $relative_url );
		}

		/**
		 * Setup the hooks, actions and filters.
		 *
		 * @uses add_action() To add actions.
		 * @uses add_filter() To add filters.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_actions() {
			if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' ) ) {

				if ( ! is_child_theme() ) {
					add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
				}

				if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
					add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
					add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
				}

				add_action( 'admin_menu', array( $this, 'admin_menus' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action( 'admin_init', array( $this, 'init_wizard_steps' ), 30 );
				add_action( 'admin_init', array( $this, 'setup_wizard' ), 30 );
				add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
				add_action( 'wp_ajax_envato_setup_plugins', array( $this, 'ajax_plugins' ) );
			}
			add_action( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 2 );
		}

		/**
		 * After a theme update we clear the setup_complete option. This prompts the user to visit the update page again.
		 *
		 * @since 1.1.8
		 * @access public
		 *
		 * @param string $return  Param $return.
		 * @param string $theme   Param $theme.
		 */
		public function upgrader_post_install( $return, $theme ) {
			return;
			if ( is_wp_error( $return ) ) {
				return $return;
			}
			if ( get_stylesheet() !== $theme ) {
				return $return;
			}
			update_option( 'envato_setup_complete', false );
			return $return;
		}


		/**
		 * Function enqueue_scripts.
		 */
		public function enqueue_scripts() {
		}

		/**
		 * Function tgmpa_load.
		 *
		 * @param bool $status Status.
		 */
		public function tgmpa_load( $status ) {
			return is_admin() || current_user_can( 'install_themes' );
		}

		/**
		 * Function switch_theme.
		 */
		public function switch_theme() {
			set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
		}

		/**
		 * Function admin_redirects.
		 */
		public function admin_redirects() {
			$after_theme_switch = $this->after_theme_switch();
			if ( 'wizard' === $after_theme_switch ) {
				ob_start();
				if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
					return;
				}
				delete_transient( '_' . $this->theme_name . '_activation_redirect' );
				wp_safe_redirect( admin_url( $this->page_url ) );
				exit;
			}
		}

		/**
		 * Get configured TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		/**
		 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && 'themes.php' !== $this->tgmpa_instance->parent_slug ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );

		}

		/**
		 * Add admin menus/screens.
		 */
		public function admin_menus() {

			if ( $this->is_submenu_page() ) {
				// prevent Theme Check warning about "themes should use add_theme_page for adding admin pages".
				$add_subpage_str      = '_page';
				$add_subpage_function = 'add_submenu' . $add_subpage_str;
				$add_subpage_function(
					$this->parent_slug,
					esc_html__( 'Setup Wizard', 'cardealer' ),
					esc_html__( 'Setup Wizard', 'cardealer' ),
					'manage_options',
					$this->page_slug,
					array(
						$this,
						'setup_wizard',
					)
				);
			} else {
				add_theme_page(
					esc_html__( 'Setup Wizard', 'cardealer' ),
					esc_html__( 'Setup Wizard', 'cardealer' ),
					'manage_options',
					$this->page_slug,
					array(
						$this,
						'setup_wizard',
					)
				);
			}

		}

		/**
		 * Setup steps.
		 *
		 * @since 1.1.1
		 * @access public
		 * @return void
		 */
		public function init_wizard_steps() {

			$purchase_token = cardealer_is_activated();

			$this->steps = array(
				'introduction' => array(
					'name'    => esc_html__( 'Introduction', 'cardealer' ),
					'view'    => array( $this, 'envato_setup_introduction' ),
					'handler' => array( $this, 'envato_setup_introduction_save' ),
				),
			);

			$this->steps['activate'] = array(
				'name'    => esc_html__( 'Activate', 'cardealer' ),
				'view'    => array( $this, 'envato_setup_activate' ),
				'handler' => array( $this, 'envato_setup_activate_save' ),
			);

			$this->steps['customize'] = array(
				'name'    => esc_html__( 'Child Theme', 'cardealer' ),
				'view'    => array( $this, 'envato_setup_customize' ),
				'handler' => '',
			);

			$this->steps['default_builder'] = array(
				'name'    => esc_html__( 'Page Builder', 'cardealer' ),
				'view'    => array( $this, 'envato_setup_step_default_builder_view' ),
				'handler' => array( $this, 'envato_setup_step_default_builder_save' ),
			);

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				$this->steps['default_plugins'] = array(
					'name'    => esc_html__( 'Plugins', 'cardealer' ),
					'view'    => array( $this, 'envato_setup_default_plugins' ),
					'handler' => '',
				);
			}
			$this->steps['default_content'] = array(
				'name'    => esc_html__( 'Content', 'cardealer' ),
				'view'    => array( $this, 'envato_setup_default_content' ),
				'handler' => '',
			);
			$this->steps['help_support']    = array(
				'name'    => esc_html__( 'Support', 'cardealer' ),
				'view'    => array( $this, 'envato_setup_help_support' ),
				'handler' => '',
			);
			$this->steps['final']           = array(
				'name'    => esc_html__( 'Ready!', 'cardealer' ),
				'view'    => array( $this, 'envato_setup_ready' ),
				'handler' => '',
			);

			$this->steps = apply_filters( $this->theme_name . '_theme_setup_wizard_steps', $this->steps );

		}

		/**
		 * Envato setup wizard default builder step view.
		 *
		 * @return mixed
		 */
		public function envato_setup_step_default_builder_view() {
			?>
			<h1><?php esc_html_e( 'Default Page Builder', 'cardealer' ); ?></h1>
			<form method="post">
				<p><?php esc_html_e( 'Please choose a default page builder from the list below.', 'cardealer' ); ?></p>

				<div class="page-builders-wrapper">
					<ul class="page-builders">
						<?php
						$page_builders = cardealer_get_page_builders();
						$page_builder  = cardealer_get_default_page_builder();

						foreach ( $page_builders as $builder_name => $builder_data ) {
							?>
							<li class="page-builder">
								<label for="<?php echo esc_attr( $builder_name ); ?>">
									<input type="radio" id="<?php echo esc_attr( $builder_name ); ?>" name="page_builder" value="<?php echo esc_attr( $builder_name ); ?>" <?php checked( $page_builder, $builder_name ); ?>> <?php echo esc_html( $builder_data['label'] ); ?>
								</label>
							</li>
							<?php
						}
						?>
					</ul>
				</div>

				<div class="license-notice">
					<h3><?php esc_html_e( 'Important note:', 'cardealer' ); ?></h3>
					<p><?php esc_html_e( 'Please note that the sample data import process, on the Theme Options\'s Sample Data page or the Theme Setup Wizard, will import sample-data only for the page-builder plugin selected above.', 'cardealer' ); ?></p>
					<p><?php esc_html_e( 'Also, the above two, or other page-builder plugins, work differently to store data/content. And, the imported sample-data for one page-builder plugin will not work for another.', 'cardealer' ); ?></p>
				</div>

				<p class="envato-setup-actions step">
					<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'cardealer' ); ?>" name="save_step" />
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'cardealer' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}

		/**
		 * Envato setup wizard default builder step handler.
		 */
		public function envato_setup_step_default_builder_save() {
			check_admin_referer( 'envato-setup' );

			$page_builders = cardealer_get_page_builders();
			$page_builder  = ( isset( $_POST['page_builder'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_POST['page_builder'] ) ), $page_builders ) ) ? sanitize_text_field( wp_unslash( $_POST['page_builder'] ) ) : 'wpbakery';

			if ( $page_builder ) {
				update_option( 'cardealer_default_page_builder', $page_builder );
			}

			wp_redirect( esc_url_raw( $this->get_next_step_link() ) );
			exit;
		}

		/**
		 * Show the setup wizard
		 */
		public function setup_wizard() {
			if ( empty( $_GET['page'] ) || $this->page_slug !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}
			if ( ob_get_length() > 0 ) {
				ob_end_clean();
			}

			$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			wp_register_script( 'jquery-blockui', $this->plugin_url . 'js/jquery.blockUI.js', array( 'jquery' ), '2.70', true );
			wp_register_script(
				'envato-setup',
				$this->plugin_url . 'js/envato-setup.js',
				array(
					'jquery',
					'jquery-blockui',
				),
				$this->version,
				true
			);
			wp_localize_script(
				'envato-setup',
				'envato_setup_params',
				array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => admin_url( $this->tgmpa_url ),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'envato_setup_nonce' ),
					'verify_text'      => esc_html__( '...verifying', 'cardealer' ),
				)
			);

			wp_enqueue_style(
				'envato-setup',
				$this->plugin_url . 'css/envato-setup.css',
				array(
					'wp-admin',
					'dashicons',
					'install',
				),
				$this->version
			);

			// enqueue style for admin notices.
			wp_enqueue_style( 'wp-admin' );

			wp_enqueue_media();
			wp_enqueue_script( 'media' );

			ob_start();
			$this->setup_wizard_header();
			$this->setup_wizard_steps();
			$show_content = true;
			?>
			<div class="envato-setup-content envato-setup-content-step-<?php echo esc_attr( $this->step ); ?>">
				<?php
				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'] );
				}
				if ( $show_content ) {
					$this->setup_wizard_content();
				}
				?>
			</div>
			<?php
			$this->setup_wizard_footer();
			exit;
		}

		/**
		 * Function get_step_link.
		 *
		 * @param string $step  Step.
		 */
		public function get_step_link( $step ) {
			return add_query_arg( 'step', $step, admin_url( 'admin.php?page=' . $this->page_slug ) );
		}

		/**
		 * Function get_next_step_link.
		 */
		public function get_next_step_link() {
			$keys = array_keys( $this->steps );

			return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ), true ) + 1 ], remove_query_arg( 'translation_updated' ) );
		}

		/**
		 * Setup Wizard Header
		 */
		public function setup_wizard_header() {
			?>
			<!DOCTYPE html>
			<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width"/>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<?php
				// avoid theme check issues.
				$ttl_element = 'title';
				echo wp_kses(
					"<$ttl_element>" . sprintf(
						/* translators: %1$s Theme Name, %2$s Setup Wizard Step */
						esc_html__( '%1$s &rsaquo; Theme Setup Wizard - %2$s', 'cardealer' ),
						$this->theme_data->get( 'Name' ),
						$this->steps[ $this->step ]['name']
					) . "</$ttl_element>",
					array(
						'title' => array(),
					)
				);

				wp_print_scripts( 'envato-setup' );
				do_action( 'admin_print_styles' );
				do_action( 'admin_print_scripts' );
				?>
			</head>
			<body class="envato-setup wp-core-ui envato-setup-step-<?php echo esc_attr( $this->step ); ?>">
				<h1 id="wc-logo">
					<?php
					$header_logo = sprintf(
						'<img class="site-logo" src="%s" alt="%s" style="width:%s; height:auto" />',
						( $this->get_logo_image() ) ? $this->get_logo_image() : trailingslashit( $this->plugin_url ) . 'images/logo.png',
						$this->theme_data->get( 'Name' ),
						$this->get_header_logo_width()
					);
					echo wp_kses( $header_logo, cardealer_allowed_html( array( 'img' ) ) );
					?>
				</h1>
			<?php
		}

		/**
		 * Setup Wizard Footer
		 */
		public function setup_wizard_footer() {
			if ( 'final' === $this->step ) {
				?>
				<a class="wc-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>">
					<?php esc_html_e( 'Return to the WordPress Dashboard', 'cardealer' ); ?>
				</a>
				<?php
			}
			?>
					<p class="copyrights">
						<?php
						$copyright = '';
						if ( $this->theme_data->get( 'Author' ) ) {
							$copyright = sprintf(
								// Translators: %s is the theme author link.
								esc_html__( '&copy; Created by %s', 'cardealer' ),
								( $this->theme_data->get( 'AuthorURI' ) ) ? sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', $this->theme_data->get( 'AuthorURI' ), $this->theme_data->get( 'Author' ) ) : $this->theme_data->get( 'Author' )
							);
						}
						$copyright = apply_filters( 'envato_setup_wizard_footer_copyright', $copyright, $this->theme_data );

						if ( $copyright ) {
							echo wp_kses( $copyright, cardealer_allowed_html( 'a', 'span', 'i' ) );
						}
						?>
					</p>
				</body>
				<?php
				do_action( 'admin_footer' );
				do_action( 'admin_print_footer_scripts' );
				?>
			</html>
			<?php
		}

		/**
		 * Output the steps
		 */
		public function setup_wizard_steps() {
			$ouput_steps = $this->steps;
			array_shift( $ouput_steps );
			?>
			<ol class="envato-setup-steps">
				<?php
				foreach ( $ouput_steps as $step_key => $step ) {
					$class  = 'envato-setup-step';
					$class .= ' envato-setup-step-' . $step_key;

					$show_link = false;
					if ( $step_key === $this->step ) {
						$class .= ' active';
					} elseif ( array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true ) ) {
						$class    .= ' done';
						$show_link = true;
					}
					?>
					<li class="<?php echo esc_attr( $class ); ?>">
						<?php
						if ( $show_link ) {
							?>
							<a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
							<?php
						} else {
							echo esc_html( $step['name'] );
						}
						?>
					</li>
					<?php
				}
				?>
			</ol>
			<?php
		}

		/**
		 * Output the content for the current step
		 */
		public function setup_wizard_content() {
			isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'] ) : false;
		}

		/**
		 * Introduction step
		 */
		public function envato_setup_introduction() {

			if ( false && isset( $_REQUEST['debug'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				<pre>
					<?php
					// debug inserting a particular post so we can see what's going on.
					$post_type = 'post';
					$post_id   = 1; // debug this particular import post id.
					$all_data  = $this->get_json( 'default.json' );
					if ( ! $post_type || ! isset( $all_data[ $post_type ] ) ) {
						/* translators: %s Post Type */
						echo esc_html( sprintf( esc_html__( 'Post type %s not found.', 'cardealer' ), $post_type ) );
					} else {
						/* translators: %s Post ID */
						echo esc_html( sprintf( esc_html__( 'Looking for post id %s', 'cardealer' ), $post_id ) ) . "\n";
						foreach ( $all_data[ $post_type ] as $post_data ) {

							if ( $post_data['post_id'] === $post_id ) {
								print_r( $post_data );
							}
						}
					}
					print_r( $this->logs );
					?>
				</pre>
				<?php
			} elseif ( get_option( 'envato_setup_complete', false ) ) {
				?>
				<h1>
					<?php
					/* translators: %s Theme Name */
					printf( esc_html__( 'Welcome to the steps for setting the %s theme.', 'cardealer' ), esc_html( $this->theme_data->get( 'Name' ) ) );
					?>
				</h1>
				<p><?php esc_html_e( 'It seems that you have already been through the setup medium. Below are some choices:', 'cardealer' ); ?></p>
				<ul>
					<li>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-large">
							<?php esc_html_e( 'Run Setup Wizard Again', 'cardealer' ); ?>
						</a>
					</li>
				</ul>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( wp_get_referer() && ! strpos( wp_get_referer(), 'update.php' ) ? wp_get_referer() : admin_url( '' ) ); ?>" class="button button-large"><?php esc_html_e( 'Cancel', 'cardealer' ); ?></a>
				</p>
				<?php
			} else {
				?>
				<h1>
					<?php
					/* translators: %s Theme Name */
					printf( esc_html__( 'Welcome to %s Setup Wizard', 'cardealer' ), esc_html( $this->theme_data->get( 'Name' ) ) );
					?>
				</h1>
				<p>
					<?php
					/* translators: %s Theme Name */
					printf( esc_html__( 'Thank you for choosing %s theme.', 'cardealer' ), esc_html( $this->theme_data->get( 'Name' ) ) );
					?>
				</p>
				<p><?php printf( esc_html__( 'This setup wizard will help you to refresh and configure your website with a new layout. You will have Child Theme, Content, and Plugins installed in 5-10 minutes (depending on your server configuration). ', 'cardealer' ), esc_html( $this->theme_data->get( 'Name' ) ) ); ?></p>
				<p><?php esc_html_e( 'No time right now? If you do not want to go through the wizard, you can skip, and get back to WordPress dashboard. Come back any time to continue!', 'cardealer' ); ?></p>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-active"><?php esc_html_e( 'Let\'s Go!', 'cardealer' ); ?></a>
					<a href="<?php echo esc_url( wp_get_referer() && ! strpos( wp_get_referer(), 'update.php' ) ? wp_get_referer() : admin_url( '' ) ); ?>" class="button button-large"><?php esc_html_e( 'Not right now', 'cardealer' ); ?></a>
				</p>
				<?php
			}
		}

		/**
		 * Function filter_options.
		 *
		 * @param array $options   Options.
		 */
		public function filter_options( $options ) {
			return $options;
		}

		/**
		 *
		 * Handles save button from welcome page. This is to perform tasks when the setup wizard has already been run. E.g. reset defaults
		 *
		 * @since 1.2.5
		 */
		public function envato_setup_introduction_save() {

			check_admin_referer( 'envato-setup' );

			if ( ! empty( $_POST['reset-font-defaults'] ) && 'yes' === $_POST['reset-font-defaults'] ) {

				// clear font options.
				update_option( 'tt_font_theme_options', array() );

				// reset site color.
				remove_theme_mod( 'dtbwp_site_color' );

				if ( class_exists( 'dtbwp_customize_save_hook' ) ) {
					$site_color_defaults = new dtbwp_customize_save_hook();
					$site_color_defaults->save_color_options();
				}

				$file_name = get_parent_theme_file_path( '/style.custom.css' );
				if ( file_exists( $file_name ) ) {
					require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $file_name, '' );
				}
				?>
				<p>
					<strong><?php esc_html_e( 'Options have been reset. Please go to Appearance > Customize in the WordPress backend.', 'cardealer' ); ?></strong>
				</p>
				<?php
				return true;
			}

			return false;
		}

		/**
		 * Payments Step
		 */
		public function envato_setup_activate() {
			?>
			<h1><?php esc_html_e( 'Activate Theme', 'cardealer' ); ?></h1>
			<?php
			$slug = basename( get_template_directory() );

			$output = '';

			// get notice.
			$notices = get_option( 'cardealer_purchase_code_notice', array() );
			delete_option( 'cardealer_purchase_code_notice' );

			// get purchase code and purchase token.
			$purchase_code = sanitize_text_field( get_option( 'cardealer_theme_purchase_key', '' ) );
			$notices       = get_option( 'cardealer_purchase_code_notices' );
			delete_option( 'cardealer_purchase_code_notices' );

			if ( ! empty( $purchase_code ) && empty( $notices ) ) {
				$notices = array(
					'notice_type' => 'success',
					'notice'      => esc_html__( 'Purchase code successfully verified.', 'cardealer' ),
				);
			} else {
				?>
				<p class="lead"><?php esc_html_e( 'Enter purchase code to activate your theme.', 'cardealer' ); ?></p>
				<?php
			}
			?>
			<form class="cardealer_activate_theme" method="post" action="">
				<?php
				// display notices.
				if ( ! empty( $notices ) ) {
					?>
					<div class="notice-<?php echo esc_attr( $notices['notice_type'] ); ?> notice-alt notice-large"><p><?php echo wp_kses_post( $notices['notice'] ); ?></p></div>
					<?php
				}
				$purchase_disabled = ! empty( $purchase_code ) ? 'disabled' : '';
				wp_nonce_field( 'cardealer-verify-token', 'purchase_code_nonce' );
				$input_type = apply_filters( 'cardealer/theme-setup-wizard/cardealer-purchase-code-input-type', 'text' );
				?>
				<input type="<?php echo esc_attr( $input_type ); ?>" id="cardealer_purchase_code" name="cardealer_purchase_code" value="<?php echo esc_attr( $purchase_code ); ?>" placeholder="<?php esc_attr_e( 'Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )', 'cardealer' ); ?>" <?php echo esc_attr( $purchase_disabled ); ?>/>
				<div class="activation-instructions">
					<h3><?php esc_html_e( 'Instructions to find the Purchase Code', 'cardealer' ); ?></h3>
					<ol>
						<li><?php esc_html_e( 'Log into your Envato Market account.', 'cardealer' ); ?></li>
						<li><?php esc_html_e( 'Hover the mouse over your username at the top of the screen.', 'cardealer' ); ?></li>
						<li><?php esc_html_e( 'Click \'Downloads\' from the drop-down menu.', 'cardealer' ); ?></li>
						<li>
						<?php
						printf(
							wp_kses(
								// Translators: %s is the ThemeForest Item Support Policy link.
								__( 'Click \'License certificate & purchase code\' (available as PDF or text file). Click <a href="%s" target="_blank">here</a> for more information.', 'cardealer' ),
								cardealer_allowed_html( array( 'a' ) )
							),
							'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code'
						);
						?>
						</li>
					</ol>
				</div>
				<p class="envato-setup-actions step">
					<?php
					if ( empty( $purchase_code ) ) {
						?>
						<input type="submit" class="button button-large button-next button-primary" value="<?php esc_attr_e( 'Activate', 'cardealer' ); ?>"/>
						<?php
					} else {
						?>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-primary button-large button-next">
							<?php esc_html_e( 'Continue', 'cardealer' ); ?>
						</a>
						<?php
					}
					?>
				</p>
			</form>
			<?php
			wp_nonce_field( 'envato-setup' );
		}

		/**
		 * Payments Step save
		 */
		public function envato_setup_activate_save() {
			check_admin_referer( 'envato-setup' );

			// redirect to our custom login URL to get a copy of this token.
			$url = $this->get_oauth_login_url( $this->get_step_link( 'updates' ) );

			wp_safe_redirect( esc_url_raw( $url ) );
			exit;
		}

		/**
		 * Function get_plugins.
		 *
		 * @param bool $version  Version.
		 */
		private function get_plugins( $version = false ) {
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

			$plugins = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $this->is_plugin_check_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
					// No need to display plugins if they are installed, up-to-date and active.
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}
			return $plugins;
		}

		/**
		 * Function is_plugin_check_active.
		 *
		 * @param string $slug  Slug.
		 */
		public function is_plugin_check_active( $slug ) {
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

			return ( ( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) ) || cardealer_check_plugin_active( $instance->plugins[ $slug ]['file_path'] ) );
		}


		/**
		 * Page setup
		 */
		public function envato_setup_default_plugins() {

			tgmpa_load_bulk_installer();

			// Add option to bypass templates loading by Slider Revolution.
			add_option( 'rs-templates', array(), '', false );
			add_option( 'rs-templates-new', false, '', false );
			update_option( 'revslider-templates-check', time() );

			// install plugins with TGM.
			if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
				wp_die( 'Failed to find TGM' );
			}
			$url     = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'envato-setup' );
			$plugins = $this->get_plugins();

			// copied from TGM.
			$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
			$fields = array_keys( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$creds  = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields );

			if ( false === $creds ) {
				return true; // Stop the normal page form from displaying, credential request form will be shown.
			}

			// Now we have some credentials, setup WP_Filesystem.
			if ( ! WP_Filesystem( $creds ) ) {
				// Our credentials were no good, ask the user for them again.
				request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );

				return true;
			}

			$version_import = '';

			if ( isset( $_GET['version'] ) && isset( $this->sample_datas[ $_GET['version'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$version_import = sanitize_text_field( wp_unslash( $_GET['version'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			/* If we arrive here, we have the filesystem */
			?>
			<h1><?php esc_html_e( 'Default Plugins', 'cardealer' ); ?></h1>
			<form method="post" class="plugins-form" data-version="<?php echo esc_attr( $version_import ); ?>">

				<?php
				$plugins = $this->get_plugins( $version_import );

				$required = array_filter(
					$plugins['all'],
					function( $el ) {
						return $el['required'];
					}
				);

				$version_plugins = ( ! empty( $this->sample_datas[ $version_import ]['plugins'] ) ) ? $this->sample_datas[ $version_import ]['plugins'] : array();

				$for_version = array_filter(
					$plugins['all'],
					function( $el ) use ( $version_plugins ) {
						return in_array( $el['slug'], array_merge( $version_plugins ), true );
					}
				);

				$recommended = array_filter(
					$plugins['all'],
					function( $el ) use ( $for_version ) {
						return ( ! $el['required'] && ! isset( $for_version[ $el['slug'] ] ) );
					}
				);

				if ( count( $plugins['all'] ) ) {
					?>
					<p><?php esc_html_e( 'The following plugins can be installed for some supplemented features to your website:', 'cardealer' ); ?></p>
					<ul class="envato-wizard-plugins">
						<?php
						$required_core = array();
						if ( ! empty( $required ) ) {
							?>
							<li class="plugins-title"><?php esc_html_e( 'Required', 'cardealer' ); ?></li>
							<?php
							if ( ! empty( $required_core ) ) {
								$this->list_plugins( $required_core, $plugins, false, 'required' );
							}
							$this->list_plugins( $required, $plugins, false, 'required' );
						}
						if ( ! empty( $for_version ) ) {
							?>
							<li class="plugins-title"><?php esc_html_e( 'Needed for this version', 'cardealer' ); ?></li>
							<?php
							$this->list_plugins( $for_version, $plugins, true, 'for_version' );
						}
						if ( ! empty( $recommended ) ) {
							?>
							<li class="plugins-title"><?php esc_html_e( 'Optional', 'cardealer' ); ?></li>
							<?php
							$this->list_plugins( $recommended, $plugins, false, 'recommended' );
						}
						?>
					</ul>
					<?php
				} else {
					if ( ! cardealer_is_activated() ) {
						?>
						<div class="plugin-activation-notice">
							<p><strong><?php esc_html_e( 'Please activate theme using Purchase Code to display plugins list.', 'cardealer' ); ?></strong></p>
						</div>
						<?php
					}
					if ( count( $plugins['all'] ) > 0 && count( cardealer_tgmpa_plugin_list() ) === 0 ) {
						?>
						<p><strong><?php esc_html_e( 'Good news! All plugins are already installed and up to date. Please continue.', 'cardealer' ); ?></strong></p>
						<?php
					}
				}
				?>

				<p><?php esc_html_e( 'Please, note that every external plugin can affect your website loading speed. You can add and remove plugins later on from within WordPress.', 'cardealer' ); ?></p>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-active" data-callback="install_plugins"><?php esc_html_e( 'Continue', 'cardealer' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}

		/**
		 * Function list_plugins.
		 *
		 * @param array  $plugins        Plugins.
		 * @param array  $all            All plugins.
		 * @param bool   $checked        Is checked.
		 * @param string $plugin_type    Plugin type.
		 */
		private function list_plugins( $plugins, $all, $checked = false, $plugin_type = 'recommended' ) {
			foreach ( $plugins as $slug => $plugin ) {
				$this->plugin_list_item( $slug, $plugin, $all, $checked, $plugin_type );
			}
		}

		/**
		 * Function plugin_list_item.
		 *
		 * @param string $slug          Plugin slug.
		 * @param string $plugin        Plugin.
		 * @param array  $plugins       Plugins.
		 * @param bool   $checked       Is checked.
		 * @param string $plugin_type   Plugin type.
		 */
		private function plugin_list_item( $slug, $plugin, $plugins, $checked, $plugin_type ) {
			$message_strings = array(
				'Activate'                       => esc_html__( 'Activate', 'cardealer' ),
				'Activation required'            => esc_html__( 'Activation required', 'cardealer' ),
				'Install'                        => esc_html__( 'Install', 'cardealer' ),
				'Installation required'          => esc_html__( 'Installation required', 'cardealer' ),
				'Update and Activate'            => esc_html__( 'Update and Activate', 'cardealer' ),
				'Update and Activation required' => esc_html__( 'Update and Activation required', 'cardealer' ),
				'Update required'                => esc_html__( 'Update required', 'cardealer' ),
				'Update'                         => esc_html__( 'Update', 'cardealer' ),
			);
			?>
			<li data-slug="<?php echo esc_attr( $slug ); ?>" class="plugin-to-install">
				<label for="plugin-import[<?php echo esc_attr( $slug ); ?>]">
					<?php
					echo sprintf(
						'<input type="checkbox" name="%s" id="%s"%s>',
						esc_attr( 'plugin-import[' . $slug . ']' ),
						esc_attr( 'plugin-import[' . $slug . ']' ),
						checked( ( ( isset( $plugin['checked_in_wizard'] ) && '' !== $plugin['checked_in_wizard'] ) || $checked ), true, false )
					);

					$plugin_details = ( ( isset( $plugin['details_url'] ) && '' !== $plugin['details_url'] ) ? sprintf( ' (<a href="%s" target="_blank" rel="noopener">%s</a>)', esc_url( $plugin['details_url'] ), esc_html__( 'View details', 'cardealer' ) ) : '' );
					echo sprintf(
						'<span class="plugin-title">%s</span>%s',
						esc_html( trim( $plugin['name'] ) ),
						wp_kses(
							$plugin_details,
							array(
								'a' => array(
									'href'   => true,
									'target' => true,
									'rel'    => true,
								),
							)
						)
					);
					?>
					<span class="status"></span>
					<span class="plugin-action">
						<?php
						$keys = array();
						if ( 'required' === $plugin_type ) {
							if ( isset( $plugins['install'][ $slug ] ) ) {
								$keys[] = 'Installation';
							}
							if ( isset( $plugins['update'][ $slug ] ) ) {
								$keys[] = 'Update';
							}
							if ( isset( $plugins['activate'][ $slug ] ) ) {
								$keys[] = 'Activation';
							}
							echo esc_html( $message_strings[ trim( implode( ' and ', $keys ) . ' required' ) ] );
						} else {
							if ( isset( $plugins['install'][ $slug ] ) ) {
								$keys[] = 'Install';
							}
							if ( isset( $plugins['update'][ $slug ] ) ) {
								$keys[] = 'Update';
							}
							if ( isset( $plugins['activate'][ $slug ] ) ) {
								$keys[] = 'Activate';
							}
							echo esc_html( $message_strings[ trim( implode( ' and ', $keys ) ) ] );
						}
						?>
					</span>
					<div class="spinner"></div>
				</label>
			</li>
			<?php
		}

		/**
		 * Function ajax_plugins.
		 */
		public function ajax_plugins() {
			if ( ! check_ajax_referer( 'envato_setup_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'No Slug Found',
							'cardealer'
						),
					)
				);
			}
			$json = array();

			// send back some json we use to hit up TGM.
			$plugins = $this->get_plugins();

			// what are we doing with this plugin?.
			foreach ( $plugins['activate'] as $slug => $plugin ) {
				if ( $_POST['slug'] === $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => - 1,
						'message'       => esc_html__( 'Activating Plugin', 'cardealer' ),
					);
					break;
				}
			}
			foreach ( $plugins['update'] as $slug => $plugin ) {
				if ( $_POST['slug'] === $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => - 1,
						'message'       => esc_html__( 'Updating Plugin', 'cardealer' ),
					);
					break;
				}
			}
			foreach ( $plugins['install'] as $slug => $plugin ) {
				if ( $_POST['slug'] === $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => - 1,
						'message'       => esc_html__( 'Installing Plugin', 'cardealer' ),
					);
					break;
				}
			}

			if ( $json ) {
				$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin.
				wp_send_json( $json );
			} else {
				wp_send_json(
					array(
						'done'    => 1,
						'message' => esc_html__(
							'Success',
							'cardealer'
						),
					)
				);
			}
			exit;
		}

		/**
		 * Page setup
		 */
		public function envato_setup_default_content() {

			$plugins  = $this->get_plugins();
			$required = array_filter(
				$plugins['all'],
				function( $el ) {
					return $el['required'];
				}
			);

			$required_names = array();
			foreach ( $required as $require ) {
				if ( isset( $require['name'] ) ) {
					$required_names[] = $require['name'];
				}
			}

			$sample_datas_imported = array();

			$sample_datas = $this->sample_datas;
			?>
			<h1><?php esc_html_e( 'Default Content', 'cardealer' ); ?></h1>
			<form method="post">
				<p><?php esc_html_e( 'Select the most suitable option from the below mentioned list of variants to complete the default content for your website. Also, choose the appropriate pages to be imported from the list. The WordPress dashboard handles this content once you import them.', 'cardealer' ); ?></p>
				<?php
				if ( ! empty( $required_names ) ) {
					?>
					<div class="content-missing-plugin-notice-wrapper">
						<div class="content-missing-plugin-notice"><?php esc_html_e( 'One, or more, required plugins (listed below) not installed or activated. Some features may not work correctly, i.e., Sample Data import.', 'cardealer' ); ?></div>
						<ul class="content-missing-plugins">
							<?php
							foreach ( $required_names as $required_name_item ) {
								?>
								<li class='content-missing-plugin'><?php echo esc_html( $required_name_item ); ?></li>
								<?php
							}
							?>
						</ul>
					</div>
					<?php
				}

				if ( count( $plugins['all'] ) === 0 && count( cardealer_tgmpa_plugin_list() ) > 0 ) {
					?>
					<div class="plugin-activation-notice">
						<p><strong><?php esc_html_e( 'Please acivate theme using Purchase Code to display sample data list.', 'cardealer' ); ?></strong></p>
					</div>
					<?php
				}
				if ( ! cardealer_is_activated() ) {
					$sample_datas = array();
				}
				?>
				<div class="sample-contents-wrapper clearfix">
					<div class="sample-contents">
						<?php
						// check for already installed sample datas.
						$installed_sample_datas = get_option( 'pgs_default_sample_data' );
						$installed_demos        = array();
						if ( ! empty( $installed_sample_datas ) ) {
							$installed_demos = json_decode( $installed_sample_datas, true );
						}

						$i                    = 0;
						$first_demo_to_import = '';
						$sample_data_path     = get_parent_theme_file_path( 'includes/sample_data' );
						$sample_data_url      = get_parent_theme_file_uri( 'includes/sample_data' );

						foreach ( $sample_datas as $sample_data_k => $sample_data ) {

							$sample_data_classes   = array( 'sample-content' );
							$sample_data_classes[] = 'sample-content-' . $sample_data_k;
							$imported              = false;
							if ( in_array( $sample_data['id'], $installed_demos, true ) ) {
								$imported              = true;
								$sample_data_classes[] = 'cd-imported';
							} else {
								$i++;
								if ( 1 === $i ) {
									$first_demo_to_import  = $sample_data['id'];
									$sample_data_classes[] = 'sample-content-active';
								}
							}

							$preview_img_path = trailingslashit( trailingslashit( $sample_data_path ) . str_replace( '-elementor', '', $sample_data['id'] ) ) . 'preview.png';
							$preview_img_url  = trailingslashit( trailingslashit( $sample_data_url ) . str_replace( '-elementor', '', $sample_data['id'] ) ) . 'preview.png';

							$sample_data_classes = implode( ' ', array_filter( array_unique( $sample_data_classes ) ) );
							?>
							<div class="<?php echo esc_attr( $sample_data_classes ); ?>" data-version="<?php echo esc_attr( $sample_data_k ); ?>">
								<div class="sample-content-view">
									<?php
									if ( file_exists( $preview_img_path ) ) {
										?>
										<div class="sample-content-thumb">
											<img src="<?php echo esc_url( $preview_img_url ); ?>" alt="<?php echo esc_attr( $sample_data['name'] ); ?>"/>
										</div>
										<?php
									} else {
										?>
										<div class="sample-content-thumb sample-content-thumb-blank">
											<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAAAAJYCAQAAAAwf0r7AAAGrUlEQVR42u3VIQEAAAzDsM+/6dPh4URCSXMAMIgEABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYiAQAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBSACAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgANAeRqACWRdZgjsAAAAASUVORK5CYII=" alt="<?php echo esc_attr( $sample_data['name'] ); ?>"/>
										</div>
										<?php
									}
									?>
									<p>
									<span class="sample-content-thumb-title">
										<?php echo esc_html( $sample_data['name'] ); ?>
									</span>
									<?php
									if ( true === $imported ) {
										?>
										<span class="sample-status">
											<?php echo esc_html__( 'Installed', 'cardealer' ); ?>
										</span>
										<?php
									}
									?>
									</p>
								</div>
								<div class="sample-content-title-wrap">
									<h2 class="sample-content-title"><?php echo esc_html( $sample_data['name'] ); ?></h2>
									<?php
									if ( isset( $sample_data['preview_url'] ) && '' !== $sample_data['preview_url'] ) {
										?>
										<a href="<?php echo esc_url( $sample_data['preview_url'] ); ?>" target="_blank" rel="noopener" class="live-preview-button button button-primary button-small">
											<?php esc_html_e( 'Live Preview', 'cardealer' ); ?>
										</a>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<input type="hidden" name="import-id" id="import-id" value="<?php echo esc_attr( $first_demo_to_import ); ?>">
				<?php wp_nonce_field( 'sample_import_security_check', 'sample_import_nonce' ); ?>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-active" data-callback="install_content"><?php esc_html_e( 'Continue', 'cardealer' ); ?></a>
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large"><?php esc_html_e( 'Skip this step', 'cardealer' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}



		/**
		 * Function imported_term_id.
		 *
		 * @param string $original_term_id   Original term id.
		 * @param string $new_term_id        New term id.
		 */
		private function imported_term_id( $original_term_id, $new_term_id = false ) {
			$terms = get_transient( 'importtermids' );
			if ( ! is_array( $terms ) ) {
				$terms = array();
			}
			if ( $new_term_id ) {
				if ( ! isset( $terms[ $original_term_id ] ) ) {
					$this->log( 'Insert old TERM ID ' . $original_term_id . ' as new TERM ID: ' . $new_term_id );
				} elseif ( $terms[ $original_term_id ] !== $new_term_id ) {
					$this->error( 'Replacement OLD TERM ID ' . $original_term_id . ' overwritten by new TERM ID: ' . $new_term_id );
				}
				$terms[ $original_term_id ] = $new_term_id;
				set_transient( 'importtermids', $terms, 60 * 60 * 24 );
			} elseif ( $original_term_id && isset( $terms[ $original_term_id ] ) ) {
				return $terms[ $original_term_id ];
			}
			return false;
		}

		/**
		 * Function vc_post.
		 *
		 * @param string $post_id   Post ID.
		 */
		public function vc_post( $post_id = false ) {

			$vc_post_ids = get_transient( 'import_vc_posts' );
			if ( ! is_array( $vc_post_ids ) ) {
				$vc_post_ids = array();
			}
			if ( $post_id ) {
				$vc_post_ids[ $post_id ] = $post_id;
				set_transient( 'import_vc_posts', $vc_post_ids, 60 * 60 * 24 );
			} else {

				$this->log( 'Processing vc pages 2: ' );

				return;
				if ( class_exists( 'Vc_Manager' ) && class_exists( 'Vc_Post_Admin' ) ) {
					$this->log( $vc_post_ids );
					$vc_manager = Vc_Manager::getInstance();
					$vc_base    = $vc_manager->vc();
					$post_admin = new Vc_Post_Admin();
					foreach ( $vc_post_ids as $vc_post_id ) {
						$this->log( 'Save ' . $vc_post_id );
						$vc_base->buildShortcodesCustomCss( $vc_post_id );
						$post_admin->save( $vc_post_id );
						$post_admin->setSettings( $vc_post_id );
						// twice? bug?
						$vc_base->buildShortcodesCustomCss( $vc_post_id );
						$post_admin->save( $vc_post_id );
						$post_admin->setSettings( $vc_post_id );
					}
				}
			}

		}

		/**
		 * Function imported_post_id.
		 *
		 * @param string $original_id   Original ID.
		 * @param string $new_id        New ID.
		 */
		private function imported_post_id( $original_id = false, $new_id = false ) {
			if ( is_array( $original_id ) || is_object( $original_id ) ) {
				return false;
			}
			$post_ids = get_transient( 'importpostids' );
			if ( ! is_array( $post_ids ) ) {
				$post_ids = array();
			}
			if ( $new_id ) {
				if ( ! isset( $post_ids[ $original_id ] ) ) {
					$this->log( 'Insert old ID ' . $original_id . ' as new ID: ' . $new_id );
				} elseif ( $post_ids[ $original_id ] !== $new_id ) {
					$this->error( 'Replacement OLD ID ' . $original_id . ' overwritten by new ID: ' . $new_id );
				}
				$post_ids[ $original_id ] = $new_id;
				set_transient( 'importpostids', $post_ids, 60 * 60 * 24 );
			} elseif ( $original_id && isset( $post_ids[ $original_id ] ) ) {
				return $post_ids[ $original_id ];
			} elseif ( false === $original_id ) {
				return $post_ids;
			}

			return false;
		}

		/**
		 * Function post_orphans.
		 *
		 * @param string $original_id         Original ID.
		 * @param string $missing_parent_id   Missing parent ID.
		 */
		private function post_orphans( $original_id = false, $missing_parent_id = false ) {
			$post_ids = get_transient( 'postorphans' );
			if ( ! is_array( $post_ids ) ) {
				$post_ids = array();
			}
			if ( $missing_parent_id ) {
				$post_ids[ $original_id ] = $missing_parent_id;
				set_transient( 'postorphans', $post_ids, 60 * 60 * 24 );
			} elseif ( $original_id && isset( $post_ids[ $original_id ] ) ) {
				return $post_ids[ $original_id ];
			} elseif ( false === $original_id ) {
				return $post_ids;
			}

			return false;
		}

		/**
		 * Function cleanup_imported_ids.
		 */
		private function cleanup_imported_ids() {
			// loop over all attachments and assign the correct post ids to those attachments.
		}

		/**
		 * Delay posts.
		 *
		 * @var array
		 */
		private $delay_posts = array();

		/**
		 * Function delay_post_process.
		 *
		 * @param string $post_type   Post type.
		 * @param array  $post_data    Post data.
		 */
		private function delay_post_process( $post_type, $post_data ) {
			if ( ! isset( $this->delay_posts[ $post_type ] ) ) {
				$this->delay_posts[ $post_type ] = array();
			}
			$this->delay_posts[ $post_type ][ $post_data['post_id'] ] = $post_data;

		}


		/**
		 * Return the difference in length between two strings.
		 *
		 * @param string $a   String a.
		 * @param string $b   String b.
		 */
		public function cmpr_strlen( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}

		/**
		 * Function parse_gallery_shortcode_content.
		 *
		 * @param string $content   Post content.
		 */
		private function parse_gallery_shortcode_content( $content ) {
			// we have to format the post content. rewriting images and gallery stuff.
			$replace      = $this->imported_post_id();
			$urls_replace = array();
			foreach ( $replace as $key => $val ) {
				if ( $key && $val && ! is_numeric( $key ) && ! is_numeric( $val ) ) {
					$urls_replace[ $key ] = $val;
				}
			}
			if ( $urls_replace ) {
				uksort( $urls_replace, array( &$this, 'cmpr_strlen' ) );
				foreach ( $urls_replace as $from_url => $to_url ) {
					$content = str_replace( $from_url, $to_url, $content );
				}
			}
			if ( preg_match_all( '#\[gallery[^\]]*\]#', $content, $matches ) ) {
				foreach ( $matches[0] as $match_id => $string ) {
					if ( preg_match( '#ids="([^"]+)"#', $string, $ids_matches ) ) {
						$ids = explode( ',', $ids_matches[1] );
						foreach ( $ids as $key => $val ) {
							$new_id = $val ? $this->imported_post_id( $val ) : false;
							if ( ! $new_id ) {
								unset( $ids[ $key ] );
							} else {
								$ids[ $key ] = $new_id;
							}
						}
						$new_ids = implode( ',', $ids );
						$content = str_replace( $ids_matches[0], 'ids="' . $new_ids . '"', $content );
					}
				}
			}
			// contact form 7 id fixes.
			if ( preg_match_all( '#\[contact-form-7[^\]]*\]#', $content, $matches ) ) {
				foreach ( $matches[0] as $match_id => $string ) {
					if ( preg_match( '#id="(\d+)"#', $string, $id_match ) ) {
						$new_id = $this->imported_post_id( $id_match[1] );
						if ( $new_id ) {
							$content = str_replace( $id_match[0], 'id="' . $new_id . '"', $content );
						} else {
							// no imported ID found. remove this entry.
							$content = str_replace( $matches[0], '(insert contact form here)', $content );
						}
					}
				}
			}
			return $content;
		}

		/**
		 * Function elementor_id_import.
		 *
		 * @param int    $item     Item.
		 * @param string $key   Key.
		 */
		private function elementor_id_import( &$item, $key ) {
			if ( 'id' === $key && ! empty( $item ) && is_numeric( $item ) ) {
				// check if this has been imported before.
				$new_meta_val = $this->imported_post_id( $item );
				if ( $new_meta_val ) {
					$item = $new_meta_val;
				}
			}
			if ( 'page' === $key && ! empty( $item ) ) {

				if ( false !== strpos( $item, 'p.' ) ) {
					$new_id = str_replace( 'p.', '', $item );
					// check if this has been imported before.
					$new_meta_val = $this->imported_post_id( $new_id );
					if ( $new_meta_val ) {
						$item = 'p.' . $new_meta_val;
					}
				} elseif ( is_numeric( $item ) ) {
					// check if this has been imported before.
					$new_meta_val = $this->imported_post_id( $item );
					if ( $new_meta_val ) {
						$item = $new_meta_val;
					}
				}
			}
			if ( 'post_id' === $key && ! empty( $item ) && is_numeric( $item ) ) {
				// check if this has been imported before.
				$new_meta_val = $this->imported_post_id( $item );
				if ( $new_meta_val ) {
					$item = $new_meta_val;
				}
			}
			if ( 'url' === $key && ! empty( $item ) && strstr( $item, 'ocalhost' ) ) {
				// check if this has been imported before.
				$new_meta_val = $this->imported_post_id( $item );
				if ( $new_meta_val ) {
					$item = $new_meta_val;
				}
			}
			if ( ( 'shortcode' === $key || 'editor' === $key ) && ! empty( $item ) ) {
				// we have to fix the [contact-form-7 id=133] shortcode issue.
				$item = $this->parse_gallery_shortcode_content( $item );

			}
		}

		/**
		 * Function get_json.
		 *
		 * @param string $file   File content.
		 */
		private function get_json( $file ) {
			if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = __DIR__ . '/content/' . basename( $file );
				if ( file_exists( $file_name ) ) {
					return json_decode( $wp_filesystem->get_contents( $file_name ), true );
				}
			}

			return array();
		}

		/**
		 * Function get_sql.
		 *
		 * @param string $file   Sql content.
		 */
		private function get_sql( $file ) {
			if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = __DIR__ . '/content/' . basename( $file );
				if ( file_exists( $file_name ) ) {
					return $wp_filesystem->get_contents( $file_name );
				}
			}

			return false;
		}


		/**
		 * Current theme data.
		 *
		 * @var mixed
		 */
		public $logs = array();

		/**
		 * Function log.
		 *
		 * @param string $message   Message.
		 */
		public function log( $message ) {
			$this->logs[] = $message;
		}

		/**
		 * Errros.
		 *
		 * @var array
		 */
		public $errors = array();

		/**
		 * Function error.
		 *
		 * @param string $message   Message.
		 */
		public function error( $message ) {
			$this->logs[] = 'ERROR!!!! ' . $message;
		}

		/**
		 * Function envato_setup_customize.
		 */
		public function envato_setup_customize() {
			?>
			<h1>
				<?php
				/* translators: %s Theme Name */
				printf( esc_html__( 'Setup %s Child Theme (Optional)', 'cardealer' ), esc_html( $this->theme_data->get( 'Name' ) ) );
				?>
			</h1>

			<p><?php esc_html_e( 'If you want any alterations or changes done on the source code, please use a child theme. Your source code will remain unaffected and meanwhile, your parent theme will get all the updates. The changes in the HTML/CSS/PHP code must be refrained. Use the form below to create and activate the Child Theme.', 'cardealer' ); ?></p>
			<?php
			if ( ! isset( $_REQUEST['theme_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				<p class="lead"><?php esc_html_e( "If you don't want to use the child theme, please click on 'Skip this step'", 'cardealer' ); ?></p>
				<?php
			}

			// Create Child Theme.
			if ( isset( $_REQUEST['theme_name'] ) && current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->make_child_theme( sanitize_text_field( wp_unslash( $_REQUEST['theme_name'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			$theme = $this->theme_data->get( 'Name' ) . ' Child';
			if ( ! isset( $_REQUEST['theme_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				<form action="" method="POST">
					<div class="child-theme-input">
					<label for="theme_name"><?php esc_html_e( 'Child Theme Title', 'cardealer' ); ?></label>
					<input id="theme_name" type="text" name="theme_name" value="<?php echo esc_attr( $theme ); ?>" />
					</div>
					<p class="envato-setup-actions step">
						<button type="submit" type="submit" class="button button-large button-primary button-active">
							<?php esc_html_e( 'Create and Use Child Theme', 'cardealer' ); ?>
						</button>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large">
							<?php esc_html_e( 'Skip this step', 'cardealer' ); ?>
						</a>
					</p>
				</form>
				<?php
			} else {
				?>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-active"><?php esc_html_e( 'Continue', 'cardealer' ); ?></a>
				</p>
				<?php
			}
		}

		/**
		 * Function make_child_theme.
		 *
		 * @param string $child_theme_title   Child theme title.
		 */
		private function make_child_theme( $child_theme_title ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				WP_Filesystem();
			}

			$themes_dir            = get_theme_root();
			$parent_theme_template = 'cardealer';
			$child_theme_slug      = sanitize_title( $child_theme_title ); // Sanitize Child Theme Title.
			$child_theme_name      = str_replace( '-', '_', $child_theme_slug );
			$child_theme_path      = $themes_dir . '/' . $child_theme_slug;
			$child_template_path   = get_parent_theme_file_path( 'includes/theme-setup-wizard/child-theme' );

			$child_theme_data = array(
				'title' => $child_theme_title,
				'name'  => $child_theme_name,
				'slug'  => $child_theme_slug,
			);

			$force_child_creation = true;

			$child_theme_template_files = array(
				'style.css'      => 'template',
				'functions.php'  => 'template',
				'screenshot.png' => 'copy_only',
			);

			// Validate theme name.
			if ( ! file_exists( $child_theme_path ) || ( file_exists( $child_theme_path ) && $force_child_creation ) ) {

				// Rename folder if child-folder exists.
				if ( file_exists( $child_theme_path ) && $force_child_creation ) {
					$child_theme_bak_path = $child_theme_path . '_' . gmdate( 'Y-m-d_H-i-s' );
					rename( $child_theme_path, $child_theme_bak_path );
				}

				// Create child theme directory.
				if ( ! is_dir( $child_theme_path ) ) {
					wp_mkdir_p( $child_theme_path );
				}

				// Process Child Theme template files.
				foreach ( $child_theme_template_files as $child_theme_template_file => $child_theme_template_file_type ) {

					$child_theme_template_file_source = trailingslashit( $child_template_path ) . $child_theme_template_file;
					$child_theme_template_file_target = trailingslashit( $child_theme_path ) . $child_theme_template_file;

					if ( 'template' === $child_theme_template_file_type ) {
						$template_content = $this->template_content( $child_theme_template_file_source, $child_theme_data );
						$wp_filesystem->put_contents(
							$child_theme_template_file_target,
							$template_content,
							FS_CHMOD_FILE
						);
					} else {
						copy( $child_theme_template_file_source, $child_theme_template_file_target );
					}
				}

				// Make child theme an allowed theme (network enable theme).
				$allowed_themes                      = get_site_option( 'allowedthemes' );
				$allowed_themes[ $child_theme_slug ] = true;
				update_site_option( 'allowedthemes', $allowed_themes );
			}

			// Switch to theme.
			if ( $parent_theme_template !== $child_theme_slug ) {
				?>
				<p class="lead success">
					<?php
					printf(
						/* translators: %1$s Child Theme Name, %2$s Child Theme Path */
						esc_html__( 'Child theme [%1$s] created and activated! Newly created child theme is located at below path.', 'cardealer' ),
						'<strong>' . esc_html( $child_theme_title ) . '</strong>'
					);
					?>
				</p>
				<p><strong>wp-content/themes/<?php echo esc_html( $child_theme_slug ); ?></strong></p>
				<?php
				update_option( $this->theme_name . '_has_child', $child_theme_slug );
				switch_theme( $child_theme_slug, $child_theme_slug );
			}
		}

		/**
		 * Function template_content.
		 *
		 * @param string $filename   File name.
		 * @param array  $data       Template data.
		 */
		private function template_content( $filename, $data ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				WP_Filesystem();
			}

			$content = $wp_filesystem->get_contents( $filename );

			foreach ( $data as $key => $value ) {
				$content = str_replace( '{' . $key . '}', $value, $content );
			}

			return $content;
		}

		/**
		 * Function envato_setup_help_support.
		 */
		public function envato_setup_help_support() {
			flush_rewrite_rules( true ); // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_flush_rewrite_rules
			?>
			<h1><?php esc_html_e( 'Help and Support', 'cardealer' ); ?></h1>
			<p><?php esc_html_e( 'This theme is entitled to assist for 6 months item support from the date of purchase (you can definitely lengthen the time). With respect to this license, the theme can be used on one website. To use on another site, please buy an additional license.', 'cardealer' ); ?></p>
			<p>
			<?php
			echo sprintf(
				// Translators: %s is the theme support link.
				wp_kses( __( 'You can get the item support from <a href="%s" target="_blank" rel="noopener">Potenza Support Center</a> and that is comprised of:', 'cardealer' ), cardealer_allowed_html( array( 'a' ) ) ),
				'https://potezasupport.ticksy.com/'
			);
			?>
			</p>

			<div class="help-support-wrapper">
				<div class="help-support-bullets clearfix">
					<div class="includes">
						<h3><?php esc_html_e( 'Item Support comprises of:', 'cardealer' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'In detail explanation of the technical elements of the product', 'cardealer' ); ?></li>
							<li><?php esc_html_e( 'Help if there is any error or concern.', 'cardealer' ); ?></li>
							<li><?php esc_html_e( 'Extensive support for 3rd party plugins (bundled).', 'cardealer' ); ?></li>
						</ul>
					</div>
					<div class="excludes">
						<h3><?php echo wp_kses( __( 'Item Support <strong>DOES NOT</strong> comprise of:', 'cardealer' ), array( 'strong' => array() ) ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Customization services', 'cardealer' ); ?></li>
							<li><?php esc_html_e( 'Installation services', 'cardealer' ); ?></li>
							<li><?php esc_html_e( 'Assistance for non-bundled 3rd party plugins.', 'cardealer' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<p>
			<?php
			echo sprintf(
				// Translators: %s is the ThemeForest Item Support Policy link.
				wp_kses( __( 'ThemeForest <a href="%s" target="_blank" rel="noopener">Item Support Policy</a> can be used to gather extra details about the item support.', 'cardealer' ), cardealer_allowed_html( array( 'a' ) ) ),
				'http://themeforest.net/page/item_support_policy'
			);
			?>
			</p>
			<p class="envato-setup-actions step">
				<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-active"><?php esc_html_e( 'Continue', 'cardealer' ); ?></a>
				<?php wp_nonce_field( 'envato-setup' ); ?>
			</p>
			<?php
		}

		/**
		 * Final step
		 */
		public function envato_setup_ready() {

			update_option( 'envato_setup_complete', time() );
			update_option( $this->theme_name . '_setup_wizard_displayed', true );
			?>
			<div class="envato-setup-done dashicons dashicons-admin-site"><div class="envato-setup-done-check dashicons dashicons-yes">&nbsp;</div></div>

			<h1><?php esc_html_e( 'Your Website is Ready!', 'cardealer' ); ?></h1>

			<p><?php esc_html_e( 'Praises to you, your website is ready now. The required themes have been turned on to enhance the functionality of your site. You can modify the content and make required changes, if important by logging into the WordPress Dashboard.', 'cardealer' ); ?></p>
			<p>
			<?php
			echo sprintf(
				// Translators: %s is the Themeforest downloads link.
				wp_kses( __( 'Please encourage us by <a href="%s" target="_blank" rel="noopener">dropping 5-star</a>.', 'cardealer' ), cardealer_allowed_html( array( 'a' ) ) ),
				'https://themeforest.net/downloads'
			);
			?>
			</p>
			<br>
			<br>
			<div class="envato-setup-final-contents">
				<div class="envato-setup-final-content envato-setup-final-content-first">
					<div class="envato-setup-final-content-inner">
						<div class="envato-setup-final-header"><h2><?php esc_html_e( 'Next Steps', 'cardealer' ); ?></h2></div>
						<ul>
							<li class="envato-setup-final-step-button"><a class="button button-primary button-large" href="http://themeforest.net/user/potenzaglobalsolutions/follow" target="_blank" rel="noopener"><?php esc_html_e( 'Follow PotenzaGlobalSolutions on ThemeForest', 'cardealer' ); ?></a></li>
							<li class="envato-setup-final-step-button"><a class="button button-large" href="<?php echo esc_url( home_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View your new website!', 'cardealer' ); ?></a></li>
						</ul>
					</div>
				</div>
				<div class="envato-setup-final-content envato-setup-final-content-last">
					<div class="envato-setup-final-content-inner">
						<div class="envato-setup-final-header"><h2><?php esc_html_e( 'More Resources', 'cardealer' ); ?></h2></div>
						<div class="more-resources">
							<div class="more-resource documentation"><a href="<?php echo esc_url( 'http://docs.potenzaglobalsolutions.com/docs/cardealer/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Read the Theme Documentation', 'cardealer' ); ?></a></div>
							<div class="more-resource howto"><a href="<?php echo esc_url( 'https://wordpress.org/support/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Learn how to use WordPress', 'cardealer' ); ?></a></div>
							<div class="more-resource rating"><a href="<?php echo esc_url( 'https://themeforest.net/downloads' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Leave an Item Rating', 'cardealer' ); ?></a></div>
							<div class="more-resource support"><a href="<?php echo esc_url( 'https://potezasupport.ticksy.com/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get Help and Support', 'cardealer' ); ?></a></div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}



		/**
		 * Merge array recursive.
		 *
		 * @since 1.1.4
		 *
		 * @param array $array1  Array 1.
		 * @param array $array2  Array 2.
		 *
		 * @return mixed
		 */
		private function array_merge_recursive_distinct( $array1, $array2 ) {
			$merged = $array1;
			foreach ( $array2 as $key => &$value ) {
				if ( is_array( $value ) && isset( $merged [ $key ] ) && is_array( $merged [ $key ] ) ) {
					$merged [ $key ] = $this->array_merge_recursive_distinct( $merged [ $key ], $value );
				} else {
					$merged [ $key ] = $value;
				}
			}

			return $merged;
		}

		/**
		 * Helper function
		 * Take a path and return it clean
		 *
		 * @param string $path  Path.
		 *
		 * @since    1.1.2
		 */
		public static function clean_file_path( $path ) {
			if ( $path ) {
				$path = str_replace( '', '', str_replace( array( '\\', '\\\\', '//' ), '/', $path ) );
				if ( '/' === $path[ strlen( $path ) - 1 ] ) {
					$path = rtrim( $path, '/' );
				}
			}

			return $path;
		}

		/**
		 * Function is_submenu_page.
		 */
		public function is_submenu_page() {
			return ( '' === $this->parent_slug ) ? false : true;
		}

		/**
		 * Function get_stored_code.
		 */
		public function get_stored_code() {
			$code = false;

			$stored = get_option( 'theme_purchase_code', false );

			if ( $stored ) {
				$code = $stored;
			}

			return $code;
		}

		/**
		 * Function domain.
		 */
		public function domain() {
			$domain = get_option( 'siteurl' ); // or home.
			$domain = str_replace( 'http://', '', $domain );
			$domain = str_replace( 'https://', '', $domain );
			$domain = str_replace( 'www', '', $domain ); // add the . after the www if you don't want it.
			return rawurlencode( $domain );
		}

		/**
		 * Function after_theme_switch.
		 */
		public static function after_theme_switch() {
			return ''; // wizard, other.
		}

	}

}// if !class_exists.

/**
 * Loads the main instance of Envato_Theme_Setup_Wizard to have
 * ability extend class functionality
 *
 * @since 1.1.1
 * @return object Envato_Theme_Setup_Wizard
 */
add_action( 'after_setup_theme', 'envato_theme_setup_wizard', 10 );
if ( ! function_exists( 'envato_theme_setup_wizard' ) ) :
	/**
	 * Function envato_theme_setup_wizard.
	 */
	function envato_theme_setup_wizard() {
		Envato_Theme_Setup_Wizard::get_instance();
	}
endif;

/**
 * Function Display admin notice if required plugins are not active.
 */
function cardealer_theme_setup_wizard_notice() {

	$current_theme = wp_get_theme();
	if ( is_child_theme() ) {
		$current_theme = wp_get_theme( $current_theme->get( 'Template' ) );
	}

	$theme_slug = str_replace( '-', '_', sanitize_title( $current_theme->get( 'Name' ) ) );

	if ( get_option( $theme_slug . '_setup_wizard_displayed', false ) ) {
		return;
	}
	?>
	<div class="notice notice-error">
		<p><strong>
		<?php
		echo sprintf(
			/* translators: %s: Theme name */
			esc_html__( 'Welcome to %s', 'cardealer' ),
			esc_html( $current_theme->get( 'Name' ) )
		);
		?>
		</strong></p>
		<p>
		<?php
		echo sprintf(
			// Translators: %1$s Theme name, %2$s Theme name.
			esc_html__( 'You\'re almost there. %1$s contains many useful features and functionalities. For this, some settings are to be done, to enable all features and functionalities. And, %2$s Setup Wizard might be of a great help to you for same.', 'cardealer' ),
			esc_html( $current_theme->get( 'Name' ) ),
			esc_html( $current_theme->get( 'Name' ) )
		);
		?>
		</p>
		<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-setup' ) ); ?>" class="button-primary"><?php esc_html_e( 'Run the Setup Wizard', 'cardealer' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'cardealer-setup-hide-notice', '1' ), 'cardealer_setup_hide_notice_nonce', '_cssetup_notice_nonce' ) ); ?>"><?php esc_html_e( 'Skip setup', 'cardealer' ); ?></a></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'cardealer_theme_setup_wizard_notice' );

/**
 * Function cardealer_hide_setup_wizard_notice.
 */
function cardealer_hide_setup_wizard_notice() {

	$current_theme = wp_get_theme();
	if ( is_child_theme() ) {
		$current_theme = wp_get_theme( $current_theme->get( 'Template' ) );
	}

	$theme_slug = str_replace( '-', '_', sanitize_title( $current_theme->get( 'Name' ) ) );

	if ( isset( $_GET['cardealer-setup-hide-notice'] ) && isset( $_GET['_cssetup_notice_nonce'] ) ) {
		if ( ! wp_verify_nonce( sanitize_key( $_GET['_cssetup_notice_nonce'] ), 'cardealer_setup_hide_notice_nonce' ) ) {
			wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'cardealer' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You don&#8217;t have permission to do this.', 'cardealer' ) );
		}

		$hide_notice = sanitize_text_field( wp_unslash( $_GET['cardealer-setup-hide-notice'] ) );

		if ( '' !== $hide_notice && '1' === $hide_notice ) {
			update_option( $theme_slug . '_setup_wizard_displayed', true );
		}
		$url = remove_query_arg( array( 'cardealer-setup-hide-notice', '_cssetup_notice_nonce' ) );
		wp_safe_redirect( $url );
		exit;
	}
}
add_action( 'admin_init', 'cardealer_hide_setup_wizard_notice' );
