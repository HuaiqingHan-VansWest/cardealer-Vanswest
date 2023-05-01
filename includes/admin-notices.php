<?php
use WPTRT\AdminNotices\Notices;

class CarDealer_Admin_Notices {

	/**
	 * TGMPA instance.
	 *
	 * @var object
	 */
	protected $tgmpa;

	/**
	 * Notices instance.
	 *
	 * @var object
	 */
	private $admin_notices;

    /**
	 * Constructor.
	 */
	public function __construct() {
		$this->tgmpa         = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
        $this->admin_notices = new Notices();

		add_filter( 'cardealer_admin_notices', array( $this, 'theme_dependencies_admin_notices' ) );
        add_action( 'init', array( $this, 'init' ) );
    }

	function theme_dependencies_admin_notices( $notices ) {
		$plugins            = $this->tgmpa->plugins;
		$attention_required = array();

		foreach ( $plugins as $slug => $plugin ) {
			if (
				filter_var( $plugin['required'], FILTER_VALIDATE_BOOLEAN )
				&& (
					! $this->tgmpa->is_plugin_installed( $slug )
					|| false !== $this->tgmpa->does_plugin_have_update( $slug )
					|| $this->tgmpa->can_plugin_activate( $slug )
				)
			) {
				$attention_required[ $slug ] = $plugin['name'];
			}
		}

		if ( ! empty( $attention_required ) ) {
			$attention_required_names = array_values( $attention_required );

			$install_plugin_url = add_query_arg( array(
				'page' => $this->tgmpa->menu,
			), admin_url( $this->tgmpa->parent_slug ) );

			$notices['theme_dependencies'] = array(
				'title'   => esc_html__( 'Car Dealer - Important Plugins Notice', 'cardealer' ),
				'message' => sprintf(
					wp_kses(
						/* translators: %1$s: theme plugins url. */
						__( 'The below plugins needs to be installed, activated, or updated for the "Car Dealer" theme to work correctly. For this, please go to <a href="%1$s">Appearance -> Install Plugins</a>.', 'cardealer' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					),
					$install_plugin_url
				)
				. '<br><br>'
				. '<p class="theme-required-plugins">' . implode( ', ', $attention_required_names ) . '</p>',
				'options' => array(
					'type'          => 'error',
					'option_prefix' => 'cardealer_plugin_update_notice',
				),
			);
		}

		return $notices;
	}

    public function init() {

		$notices = $this->get_notices();

		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice_k => $notice ) {
				if ( is_string( $notice_k ) && ( isset( $notice['title'] ) && ! empty( $notice['title'] ) ) && ( isset( $notice['message'] ) && ! empty( $notice['message'] ) ) ) {
					$options = ( isset( $notice['options'] ) && is_array( $notice['options'] ) ) ? $notice['options'] : array();
					$this->admin_notices->add( $notice_k, $notice['title'], $notice['message'], $options );
				}
			}
		}

		$this->admin_notices->boot();

    }

	function get_notices() {
		return apply_filters( 'cardealer_admin_notices', array() );
	}

}
new CarDealer_Admin_Notices();
