<?php
/**
 * Functions register the required plugins with TGMPA.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package CarDealer
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
get_template_part( 'includes/tgm-plugin-activation/core/class', 'tgm-plugin-activation' );

if ( ! function_exists( 'cardealer_tgmpa_plugin_list' ) ) {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	function cardealer_tgmpa_plugin_list() {
		$plugins = array();

		/* required plugins */
		$required_plugins = array(
			array(
				'name'               => esc_html__( 'Car Dealer - Helper Library', 'cardealer' ),
				'slug'               => 'cardealer-helper-library',
				'source'             => cardealer_tgmpa_plugin_path( 'cardealer-helper-library-4.9.0.zip' ),
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => '',
				'version'            => '4.9.0',
				'checked_in_wizard'  => true,
			),
			array(
				'name'               => esc_html__( 'Slider Revolution', 'cardealer' ),
				'slug'               => 'revslider',
				'source'             => cardealer_tgmpa_plugin_path( 'revslider-6.6.12.zip' ),
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => 'https://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380',
				'version'            => '6.6.12',
				'checked_in_wizard'  => true,
			),
			array(
				'name'              => esc_html__( 'Redux Framework', 'cardealer' ),
				'slug'              => 'redux-framework',
				'required'          => true,
				'details_url'       => 'https://wordpress.org/plugins/redux-framework/',
				'checked_in_wizard' => true,
			),
			array(
				'name'               => esc_html__( 'Advanced Custom Fields PRO', 'cardealer' ),
				'slug'               => 'advanced-custom-fields-pro',
				'source'             => cardealer_tgmpa_plugin_path( 'advanced-custom-fields-pro-6.1.4.zip' ),
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => 'https://www.advancedcustomfields.com/pro/',
				'version'            => '6.1.4',
				'checked_in_wizard'  => true,
			),
			array(
				'name'              => esc_html__( 'Breadcrumb NavXT', 'cardealer' ),
				'slug'              => 'breadcrumb-navxt',
				'required'          => true,
				'details_url'       => 'https://wordpress.org/plugins/breadcrumb-navxt/',
				'checked_in_wizard' => true,
			),
			array(
				'name'              => esc_html__( 'Contact Form 7', 'cardealer' ),
				'slug'              => 'contact-form-7',
				'required'          => true,
				'details_url'       => 'https://wordpress.org/plugins/contact-form-7/',
				'checked_in_wizard' => true,
			),
		);

		$page_builders   = cardealer_get_page_builders();
		$page_builder    = cardealer_get_default_page_builder( true );
		$is_setup_wizard = ( isset( $_GET['page'] ) && 'cardealer-setup' === $_GET['page'] );

		if ( ! $is_setup_wizard || ( $is_setup_wizard && array_key_exists( $page_builder, $page_builders ) && 'wpbakery' === $page_builder ) ) {
			$plugins[] = array(
				'name'               => esc_html__( 'WPBakery Page Builder', 'cardealer' ),
				'slug'               => 'js_composer',
				'source'             => cardealer_tgmpa_plugin_path( 'js_composer-6.10.0.zip' ),
				'required'           => ( 'wpbakery' === $page_builder ),
				'force_activation'   => false,
				'force_deactivation' => false,
				'version'            => '6.10.0',
				'details_url'        => 'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431',
				'checked_in_wizard'  => true,
				'has_notices'        => ( 'wpbakery' === $page_builder ),
			);
		}

		if ( ! $is_setup_wizard || ( $is_setup_wizard && array_key_exists( $page_builder, $page_builders ) && 'elementor' === $page_builder ) ) {
			$plugins[] = array(
				'name'              => esc_html__( 'Elementor Website Builder', 'cardealer' ),
				'slug'              => 'elementor',
				'required'          => ( 'elementor' === $page_builder ),
				'optional'          => true,
				'details_url'       => 'https://wordpress.org/plugins/elementor/',
				'checked_in_wizard' => true,
				'has_notices'        => ( 'elementor' === $page_builder ),
			);
		}

		$required_plugins = apply_filters( 'cardealer_tgmpa_required_plugins', $required_plugins );

		$plugins = array_merge(
			$plugins,
			$required_plugins
		);

		/* recommended plugins */
		$recommended_plugins = array(
			array(
				'name'               => esc_html__( 'Car Dealer - Fronted Submission', 'cardealer' ),
				'slug'               => 'cardealer-front-submission',
				'source'             => cardealer_tgmpa_plugin_path( 'cardealer-front-submission-3.6.0.zip' ),
				'required'           => cardealer_tgmpa_is_cdfs_active(),
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => '',
				'version'            => '3.6.0',
				'checked_in_wizard'  => false,
				'has_notices'        => true,
			),
			array(
				'name'               => esc_html__( 'Max Mega Menu', 'cardealer' ),
				'slug'               => 'megamenu',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => 'https://wordpress.org/plugins/megamenu/',
				'checked_in_wizard'  => false,
				'has_notices'        => false,
			),
			array(
				'name'               => esc_html__( 'MailChimp for WordPress', 'cardealer' ),
				'slug'               => 'mailchimp-for-wp',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => 'https://wordpress.org/plugins/mailchimp-for-wp/',
				'checked_in_wizard'  => false,
				'has_notices'        => false,
			),
			array(
				'name'               => esc_html__( 'Envato Market', 'cardealer' ),
				'slug'               => 'envato-market',
				'source'             => cardealer_tgmpa_plugin_path( 'envato-market-2.0.8.zip' ),
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
				'version'            => '2.0.8',
				'details_url'        => 'https://envato.com/market-plugin/',
				'checked_in_wizard'  => false,
				'has_notices'        => false,
			),
		);

		if ( cardealer_tgmpa_is_subscriptio_enabled() ) {
			$recommended_plugins[] = array(
				'name'               => esc_html__( 'Subscriptio', 'cardealer' ),
				'slug'               => 'subscriptio',
				'source'             => cardealer_tgmpa_plugin_path( 'subscriptio-3.0.7.zip' ),
				'required'           => cardealer_tgmpa_is_cdfs_active(),
				'force_activation'   => false,
				'force_deactivation' => false,
				'details_url'        => 'https://codecanyon.net/item/subscriptio-woocommerce-subscriptions/8754068',
				'version'            => '3.0.7',
				'checked_in_wizard'  => false,
				'has_notices'        => false,
			);
		}

		if ( cardealer_tgmpa_is_woocommerce_enabled() ) {
			$recommended_plugins[] = array(
				'name'              => esc_html__( 'WooCommerce', 'cardealer' ),
				'slug'              => 'woocommerce',
				'required'          => cardealer_tgmpa_is_cdfs_active(),
				'details_url'       => 'https://wordpress.org/plugins/woocommerce/',
				'checked_in_wizard' => false,
				'has_notices'       => false,
			);
		}

		$recommended_plugins = apply_filters( 'cardealer_tgmpa_recommended_plugins', $recommended_plugins );

		if ( ! isset( $_GET['step'] ) || 'default_plugins' !== $_GET['step'] ) {
			/* remove recommended plugins from installation wizard */
			$plugins = array_merge(
				$plugins,
				$recommended_plugins
			);
		}

		$plugins = apply_filters( 'tgmpa_plugin_list', $plugins );

		$plugins = apply_filters( 'cardealer_tgmpa_plugins', $plugins );

		return $plugins;
	}
}

add_action( 'tgmpa_register', 'cardealer_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function cardealer_register_required_plugins() {
	if ( ! cardealer_is_activated() ) {
		return;
	}

	$plugins    = cardealer_tgmpa_plugin_list();
	$tgmpa_id   = 'cardealer_recommended_plugins';
	$tgmpa_menu = $GLOBALS['cardealer_tgmpa_menu'];

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => $tgmpa_id,            // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                   // Default absolute path to bundled plugins.
		'menu'         => $tgmpa_menu,          // Menu slug.
		'parent_slug'  => 'themes.php',         // Parent menu slug.
		'capability'   => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                 // Show admin notices or not.
		'dismissable'  => true,                 // If false, a user cannot dismiss the nag message.
		'is_automatic' => false,                // Automatically activate plugins after installation or not.
	);
	tgmpa( $plugins, $config );
}

if ( ! function_exists( 'cardealer_tgmpa_setup_status' ) ) {
	/**
	 * Cardealer_tgmpa_setup_status()
	 * Returns plugin activation status
	 */
	function cardealer_tgmpa_setup_status() {

		$pluginy = cardealer_tgmpa_plugins_data();

		$cardealer_tgmpa_plugins_data_all = $pluginy['all'];
		foreach ( $cardealer_tgmpa_plugins_data_all as $cardealer_tgmpa_plugins_data_k => $cardealer_tgmpa_plugins_data_v ) {
			if ( ! $cardealer_tgmpa_plugins_data_v['required'] ) {
				unset( $cardealer_tgmpa_plugins_data_all[ $cardealer_tgmpa_plugins_data_k ] );
			}
		}

		if ( count( $cardealer_tgmpa_plugins_data_all ) > 0 ) {
			return false;
		} else {
			return true;
		}
	}
}

if ( ! function_exists( 'cardealer_tgmpa_plugins_data' ) ) {
	/**
	 * Cardealer_tgmpa_plugins_data()
	 * Returns plugin activation list
	 */
	function cardealer_tgmpa_plugins_data() {
		$plugins = cardealer_tgmpa_plugin_list();

		$tgmpax = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		foreach ( $plugins as $plugin ) {
			call_user_func( array( $tgmpax, 'register' ), $plugin );
		}
		$pluginx = $tgmpax->plugins;

		$pluginy = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);

		foreach ( $tgmpax->plugins as $slug => $plugin ) {
			if ( $tgmpax->is_plugin_active( $slug ) && false === $tgmpax->does_plugin_have_update( $slug ) ) {
				// No need to display plugins if they are installed, up-to-date and active.
				continue;
			} else {
				$pluginy['all'][ $slug ] = $plugin;

				if ( ! $tgmpax->is_plugin_installed( $slug ) ) {
					$pluginy['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $tgmpax->does_plugin_have_update( $slug ) ) {
						$pluginy['update'][ $slug ] = $plugin;
					}

					if ( $tgmpax->can_plugin_activate( $slug ) ) {
						$pluginy['activate'][ $slug ] = $plugin;
					}
				}
			}
		}
		return $pluginy;
	}
}

add_action( 'admin_head', 'cardealer_set_default_cdhl_plugin_version' );
if ( ! function_exists( 'cardealer_set_default_cdhl_plugin_version' ) ) {
	/**
	 * Function for update Car Dealer Helper Plugin
	 * Make entry in database for fresh installation so It will compare and do not ask for update
	 */
	function cardealer_set_default_cdhl_plugin_version() {
		global $pagenow;

		/* return if not on themes.php */
		if ( 'themes.php' !== $pagenow ) {
			return;
		}

		$plugin = 'cardealer-helper-library';
		if ( get_option( 'cdhl_version' ) === false ) {

			$do_version_entry = false;

			/*
			 * Installing from TGMPA
			 */

			// @codingStandardsIgnoreStart
			/* Single installation */
			if ( ( isset( $_GET['tgmpa-install'] ) && 'install-plugin' === $_GET['tgmpa-install'] ) && ( isset( $_GET['plugin'] ) && $_GET['plugin'] === $plugin ) ) {
				$do_version_entry = true;
			} elseif ( ( isset( $_POST['action'] ) || isset( $_POST['action2'] ) ) && ( 'tgmpa-bulk-install' === $_POST['action'] || 'tgmpa-bulk-install' === $_POST['action2'] ) ) {
				/* Bulk installation */
				$plugins_to_install = isset( $_POST['plugin'] ) ? $_POST['plugin'] : '';
				if ( in_array( $plugin, $plugins_to_install ) ) {
					/* check if specified plugin is available in bulk install */
					$do_version_entry = true;
				}
			}
			// @codingStandardsIgnoreEnd

			// Perform default verion entry if cardealer-helper-library plugin is found.
			if ( true === $do_version_entry ) {
				update_option( 'cdhl_version', '0.0.0' );
			}
		}
	}
}


if ( ! function_exists( 'cardealer_tgmpa_plugin_path' ) ) {
	/**
	 * Make plugin source URL
	 *
	 * @see cardealer_tgmpa_plugin_path()
	 *
	 * @param string $plugin_name used for html.
	 */
	function cardealer_tgmpa_plugin_path( $plugin_name = '' ) {
		$purchase_token = cardealer_is_activated();
		/* bail early if no plugin name provided */
		if ( empty( $plugin_name ) ) {
			return '';
		}
		return add_query_arg(
			array(
				'plugin_name' => $plugin_name,
				'token'       => $purchase_token,
				'site_url'    => get_site_url(),
				'product_key' => PGS_PRODUCT_KEY,
			),
			trailingslashit( PGS_ENVATO_API ) . 'install-plugin'
		);
	}
}


add_filter( 'tgmpa_admin_menu_args', 'cardealer_tgmpa_admin_menu_args' );
function cardealer_tgmpa_admin_menu_args( $args ) {

	$args['page_title'] = esc_html__( 'Install Required/Recommended Plugins', 'cardealer' );

	return $args;
}

add_action( 'init', 'cardealer_tgmpa_init_fix' );
function cardealer_tgmpa_init_fix() {
	$tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

	if ( true === $tgmpa_instance->is_tgmpa_complete() ) {
		add_action( 'admin_menu', 'cardealer_tgmpa_menu_fix' );
	}
}

function cardealer_tgmpa_menu_fix() {
	// Make sure privileges are correct to see the page.
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	$tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

	$args = apply_filters(
		'tgmpa_admin_menu_args',
		array(
			'parent_slug' => $tgmpa_instance->parent_slug,                     // Parent Menu slug.
			'page_title'  => $tgmpa_instance->strings['page_title'],           // Page title.
			'menu_title'  => $tgmpa_instance->strings['menu_title'],           // Menu title.
			'capability'  => $tgmpa_instance->capability,                      // Capability.
			'menu_slug'   => $tgmpa_instance->menu,                            // Menu slug.
			'function'    => array( $tgmpa_instance, 'install_plugins_page' ), // Callback.
		)
	);

	add_theme_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'] );
}

function cardealer_tgmpa_plugins_notice_str() {
	return esc_html__( 'Note: These plugins are for adding extra features to the site. Before installing any plugin from the below list, make sure you actually needed it. Installing any plugin that is not required will add extra load to the site performance.', 'cardealer' );
}

function cardealer_display_tgmpa_plugins_panel_notice() {
	$message = cardealer_tgmpa_plugins_notice_str();
	?>
	<div class="cardealer-theme-panel-install-plugin-note">
		<p><?php echo esc_html( $message ); ?></p>
	</div>
	<?php
}
add_action( 'cardealer_tgmpa_plugins_panel_notice', 'cardealer_display_tgmpa_plugins_panel_notice' );

function sample_admin_notice__error() {
	$tgmpa_menu = $GLOBALS['cardealer_tgmpa_menu'];

	if ( isset( $_GET['page'] ) && $tgmpa_menu === $_GET['page'] && ! isset( $_GET['tgmpa-install'] ) ) {
		$message = cardealer_tgmpa_plugins_notice_str();
		printf(
			'<div class="%1$s"><p>%2$s</p></div>',
			'notice notice-error cardealer-theme-plugin-note',
			esc_html( $message )
		);
	}
}
add_action( 'admin_notices', 'sample_admin_notice__error' );

function cardealer_tgmpa_is_subscriptio_enabled() {
	$enabled = false;

	$is_pricing_packages_enabled = get_option( 'cardealer_tgmpa_is_pricing_packages_enabled', null );

	if (
		( null === $is_pricing_packages_enabled && ( function_exists( 'cardealer_check_plugin_installed' ) && cardealer_check_plugin_installed( 'subscriptio/subscriptio.php' ) ) )
		|| ( null !== $is_pricing_packages_enabled && 1 === (int) $is_pricing_packages_enabled )
	) {
		$enabled = true;
	}

	$enabled = apply_filters( 'cardealer_tgmpa_is_subscriptio_enabled', $enabled );

	return $enabled;
}

function cardealer_tgmpa_is_woocommerce_enabled() {
	$enabled = false;

	$is_pricing_packages_enabled = get_option( 'cardealer_tgmpa_is_pricing_packages_enabled', null );

	if (
		( null === $is_pricing_packages_enabled && ( function_exists( 'cardealer_check_plugin_installed' ) && cardealer_check_plugin_installed( 'woocommerce/woocommerce.php' ) ) )
		|| ( null !== $is_pricing_packages_enabled && 1 === (int) $is_pricing_packages_enabled )
	) {
		$enabled = true;
	}

	$enabled = apply_filters( 'cardealer_tgmpa_is_woocommerce_enabled', $enabled );

	return $enabled;
}

function cardealer_tgmpa_is_cdfs_active() {
	$enabled = false;

	$installed = function_exists( 'cardealer_check_plugin_installed' ) && cardealer_check_plugin_installed( 'cardealer-front-submission/cardealer-frontend-submission.php' );
	$active    = function_exists( 'cardealer_check_plugin_active' ) && cardealer_check_plugin_active( 'cardealer-front-submission/cardealer-frontend-submission.php' );

	if ( $installed && $active ) {
		$enabled = true;
	}

	$enabled = apply_filters( 'cardealer_tgmpa_is_cdfs_active', $enabled );

	return $enabled;
}

