<?php
/**
 * Cardealer Panel function file
 *
 * @package Cardealer
 */

// Support page.
if ( ! function_exists( 'cardealer_get_support_data' ) ) {
	/**
	 * Cardealer get support data
	 */
	function cardealer_get_support_data() {
		cardealer_load_theme_template( 'support' );
	}
}

// Registration page.
if ( ! function_exists( 'cardealer_display_theme_page' ) ) {
	/**
	 * Cardealer display theme page
	 */
	function cardealer_display_theme_page() {
		cardealer_load_theme_template( 'theme_token' );
	}
}

// Plugin Page.
if ( ! function_exists( 'cardealer_get_plugin_data' ) ) {
	/**
	 * Cardealer get plugin data
	 */
	function cardealer_get_plugin_data() {
		cardealer_load_theme_template( 'plugins' );
	}
}
// System Status.
if ( ! function_exists( 'cardealer_get_system_status' ) ) {
	/**
	 * Cardealer get system status
	 */
	function cardealer_get_system_status() {
		cardealer_load_theme_template( 'system_status' );
	}
}
// Cardealer Ratings.
if ( ! function_exists( 'cardealer_get_ratings_page' ) ) {
	/**
	 * Cardealer get ratings page
	 */
	function cardealer_get_ratings_page() {
		cardealer_load_theme_template( 'ratings' );
	}
}

// Cardealer Third Party Testing.
if ( ! function_exists( 'cardealer_get_third_party_testing_page' ) ) {
	/**
	 * Cardealer get ratings page
	 */
	function cardealer_get_third_party_testing_page() {
		cardealer_load_theme_template( 'third-party-testing' );
	}
}

// Cardealer More Features.
if ( ! function_exists( 'cardealer_get_more_features_page' ) ) {
	/**
	 * Cardealer get ratings page
	 */
	function cardealer_get_more_features_page() {
		cardealer_load_theme_template( 'more-features' );
	}
}

/*Theme info*/
if ( ! function_exists( 'cardealer_get_theme_info' ) ) {
	/**
	 * Cardealer get theme info
	 */
	function cardealer_get_theme_info() {
		$theme      = wp_get_theme( get_template() );
		$theme_name = $theme->get( 'Name' );
		$theme_v    = $theme->get( 'Version' );
		$theme_info = array(
			'name' => $theme_name,
			'slug' => sanitize_file_name( strtolower( $theme_name ) ),
			'v'    => $theme_v,
		);
		return $theme_info;
	}
}
if ( ! function_exists( 'cardealer_load_theme_template' ) ) {
	/**
	 * Cardealer load theme template
	 *
	 * @see cardealer_load_theme_template()
	 *
	 * @param string $path is string variable.
	 * @param array  $args argument.
	 * @param bool   $require_once is require once.
	 */
	function cardealer_load_theme_template( $path, $args = array(), $require_once = true ) {
		$located = locate_template( 'templates/cardealer-panel/' . $path . '.php' );
		if ( $located ) {
			load_template( $located, $require_once, $args );
		} elseif ( file_exists( dirname( __FILE__ ) . '/templates/' . $path . '.php' ) ) {
			load_template( dirname( __FILE__ ) . '/templates/' . $path . '.php', $require_once, $args );
		}
	}
}

if ( ! function_exists( 'cardealer_get_theme_support_url' ) ) {
	/**
	 * Cardealer get theme support url
	 */
	function cardealer_get_theme_support_url() {
		return esc_url( 'https://potezasupport.ticksy.com/submit/#100010500' );
	}
}
if ( ! function_exists( 'cardealer_get_theme_doc_url' ) ) {
	/**
	 * Cardealer get theme doc url
	 */
	function cardealer_get_theme_doc_url() {
		return esc_url( 'http://docs.potenzaglobalsolutions.com/docs/cardealer' );
	}
}
if ( ! function_exists( 'cardealer_get_theme_video_url' ) ) {
	/**
	 * Cardealer get theme video url
	 */
	function cardealer_get_theme_video_url() {
		return esc_url( 'http://docs.potenzaglobalsolutions.com/docs/cardealer/#videos' );
	}
}
if ( ! function_exists( 'cardealer_get_cardealer_tabs' ) ) {
	/**
	 * Cardealer get cardealer tabs
	 *
	 * @see cardealer_get_cardealer_tabs()
	 *
	 * @param string $screen is string variable.
	 */
	function cardealer_get_cardealer_tabs( $screen = 'support' ) {
		global $cardealer_theme_data;

		$theme      = cardealer_get_theme_info();
		$theme_name = $theme['name'];
		?>
		<div class="cardealer-about-text-wrap clearfix">
			<h1>
				<?php
				/* translators: %s: Theme name */
				printf( esc_html__( 'Welcome to %s', 'cardealer' ), esc_html( $cardealer_theme_data->get( 'Name' ) ) );
				?>
			</h1>
			<div class="cardealer-about-text about-text">
				<?php add_thickbox(); ?>
				<div class="cardealer_theme_info cardealer-welcome">
					<div class="welcome-left cardealer-welcome-badge <?php echo esc_attr( cardealer_welcome_logo() ? 'cardealer-welcome-badge-with-logo' : 'cardealer-welcome-badge-without-logo' ); ?>">
						<div class="wp-badge">
							<img src="<?php echo esc_url( CARDEALER_URL . '/images/theme-panel/admin-welcome-logo.png' ); ?>" height="100" width="100" />
						</div>
						<div class="cardealer-welcome-badge-version">
							<?php
							/* translators: %s: Theme name */
							echo sprintf( esc_html__( 'Version %s', 'cardealer' ), esc_html( $theme['v'] ) );
							?>
						</div>
					</div>
					<div class="welcome-right">
						<?php printf( wp_kses( __( '<strong>Car Dealer</strong> is now active and ready to use! <strong>Car Dealer</strong> is an elegant, clean, beautiful and best responsive WordPress theme. <strong>Car Dealer</strong> contains many usefull features and functionalities. And, it requires some plugins to be pre-installed to enable all inbuilt features and functionalities.', 'cardealer' ), array( 'strong' => array() ) ), esc_html( $theme_name ), esc_html( $theme_name ) ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		if ( function_exists( 'cdhl_activate' ) && class_exists( 'redux' ) ) {
			?>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer-panel' ) ); ?>" class="<?php echo ( 'support' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'Support', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer-plugins' ) ); ?>" class="<?php echo ( 'plugins' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'Plugins', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer&cd_section=sample_data' ) ); ?>" class="<?php echo ( 'demos' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'Install Demos', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'themes.php?page=cardealer' ) ); ?>" class="nav-tab"><?php esc_html_e( 'Theme Options', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer-system-status' ) ); ?>" class="<?php echo ( 'system-status' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'System Status', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer-ratings' ) ); ?>" class="<?php echo ( 'ratings' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'Ratings', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer-third-party-testing' ) ); ?>" class="<?php echo ( 'third-party-testing' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'Third Party Testing', 'cardealer' ); ?></a>
				<a href="<?php echo esc_url_raw( admin_url( 'admin.php?page=cardealer-more-features' ) ); ?>" class="<?php echo ( 'more-features' === $screen ) ? 'nav-tab-active' : ''; ?> nav-tab"><?php esc_html_e( 'More Features', 'cardealer' ); ?></a>
			</h2>
			<?php
		}
	}
}
if ( ! function_exists( 'cardealer_convert_memory' ) ) {
	/**
	 * Cardealer convert memory
	 *
	 * @see cardealer_convert_memory()
	 *
	 * @param string $size is string variable.
	 *
	 * @return int
	 */
	function cardealer_convert_memory( $size ) {
		$l    = substr( $size, -1 );
		$ret  = substr( $size, 0, -1 );
		$byte = 1024;

		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= $byte;
				// No break.
			case 'T':
				$ret *= $byte;
				// No break.
			case 'G':
				$ret *= $byte;
				// No break.
			case 'M':
				$ret *= $byte;
				// No break.
			case 'K':
				$ret *= $byte;
				// No break.
		}
		return $ret;
	}
}

/**
 * Save pricing packages.
 */
function cardealer_more_feature_save_pricing_packages() {

	if ( ! isset( $_POST['cardealer_more_feature_pricing_packages_submit'] ) ) {
		return;
	}

	if ( ! isset( $_POST['cardealer_more_feature_pricing_packages_nonce_field'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cardealer_more_feature_pricing_packages_nonce_field'] ) ), 'cardealer_more_feature_pricing_packages_action' )
	) {
		wp_die( 'Could not verify nonce' );
	} else {
		$pricing_packages = ( isset( $_POST['cardealer_more_feature_pricing_packages'] ) && 1 === (int) $_POST['cardealer_more_feature_pricing_packages'] ) ? true : false;
		update_option( 'cardealer_tgmpa_is_pricing_packages_enabled', (int) $pricing_packages );
	}
}
add_action( 'admin_init', 'cardealer_more_feature_save_pricing_packages' );

/**
 * Ajac call to debug the Get vehicles.
 */
function cardealer_debug_get_vehicles() {
	$result = array(
		'status' => false,
		'msg'    => esc_html__( 'Something went wrong.', 'cardealer' ),
	);

	if ( ! isset( $_POST['ajax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), 'cardealer_debug_nonce' ) ) {
		$result['msg'] = esc_html__( 'Unable to verify security nonce. Please try again.', 'cardealer' );
	} else {
		$search_posts = array();

		$search_args = array(
			'post_type'           => 'cars',
			's'                   => ( isset( $_POST['q'] ) && ! empty( $_POST['q'] ) ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '',
			'post_status'         => array( 'publish', 'pending', 'draft' ),
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => 10,
			'paged'               => absint( ( isset( $_POST['page'] ) && ! empty( $_POST['page'] ) ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : 1 ),
		);

		// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter.
		$search_results = new WP_Query( $search_args );

		if ( $search_results->have_posts() ) {

			while ( $search_results->have_posts() ) :
				$search_results->the_post();

				$post_id = get_the_id();
				$title   = get_the_title();
				$title   = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;

				$search_posts[] = array(
					'id'    => $post_id,
					'title' => $title,
				);

			endwhile;

		}
		$result = array(
			'status'      => true,
			'total_count' => $search_results->found_posts,
			'items'       => $search_posts,
		);
	}

	wp_send_json( $result );
}
add_action( 'wp_ajax_cardealer_debug_get_vehicles', 'cardealer_debug_get_vehicles' );

/**
 * Ajax call for debug the PDF generater.
 */
function cardealer_debug_generate_pdf() {
	$result = array(
		'status' => false,
		'msg'    => esc_html__( 'Something went wrong.', 'cardealer' ),
	);

	if ( ! isset( $_POST['ajax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), 'cardealer_debug_nonce' ) ) {
		$result['msg'] = esc_html__( 'Unable to verify security nonce. Please try again.', 'cardealer' );
	} else {

		try {
			$url    = require_once CDPG_PATH . 'lib/do-pdf.php';
			$result = array(
				'status' => true,
				'msg'    => esc_html__( 'PDF generated successfully.', 'cardealer' ),
				'$url'   => $url,
			);
		} catch ( Exception $e ) {
			$result = array(
				'status' => false,
				'msg'    => sprintf(
					/* translators: exception message */
					esc_html__( 'Something went wrong. Caught exception: %s', 'cardealer' ),
					$e->getMessage()
				),
			);
		}
	}

	wp_send_json( $result );
}
add_action( 'wp_ajax_cardealer_debug_generate_pdf', 'cardealer_debug_generate_pdf' );

/**
 * Get Recent Vhicles
 *
 * @param array $args arguments.
 */
function cardealer_debug_get_recent_vehicles( $args = array() ) {
	$result = array();

	$defaults = array(
		'post_type'           => 'cars',
		'post_status'         => array( 'publish', 'pending', 'draft' ),
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => 10,
	);

	// Parse $args with $defaults.
	$args = wp_parse_args( $args, $defaults );

	$fixed_params = array(
		'post_type' => 'cars',
		'order'     => 'DESC',
		'orderby'   => 'post_date',
	);

	$args = wp_parse_args( $fixed_params, $args );

	$vehicles = get_posts( $args );

	if ( ! empty( $vehicles ) ) {
		$result = array_column( $vehicles, 'post_title', 'ID' ); // Get ID and title.
	}

	return $result;
}
