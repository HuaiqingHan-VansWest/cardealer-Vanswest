<?php
/**
 * Vehicle detail page sidebar functions.
 */
if ( ! function_exists( 'cardealer_single_vehicle_sidebar_trade_in_appraisal' ) ) {
	function cardealer_single_vehicle_sidebar_trade_in_appraisal( $post_id ) {
		global $car_dealer_options;

		$tia_form_status        = ( isset( $car_dealer_options['trade_in_appraisal_form_status'] ) && '' !== $car_dealer_options['trade_in_appraisal_form_status'] ) ? filter_var( $car_dealer_options['trade_in_appraisal_form_status'], FILTER_VALIDATE_BOOLEAN ) : true;
		$tia_form_cf7_shortcode = false;

		if ( isset( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] ) && ! empty( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] ) ) {
			$tia_form_cf7_shortcode = $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'];
		}

		if ( $tia_form_status && $tia_form_cf7_shortcode ) {
			get_template_part( 'template-parts/cars/single-car/forms/trade-in-appraisal' );
		}
	}
}

if ( ! function_exists( 'cardealer_single_vehicle_sidebar_buy_online_btn' ) ) {
	function cardealer_single_vehicle_sidebar_buy_online_btn( $post_id ) {
		cardealer_add_vehicle_to_cart( $post_id );
	}
}

if ( ! function_exists( 'cardealer_single_vehicle_sidebar_review_stamps' ) ) {
	function cardealer_single_vehicle_sidebar_review_stamps( $post_id ) {
		cardealer_get_vehicle_review_stamps( $post_id );
	}
}

if ( ! function_exists( 'cardealer_get_cars_details_title_location_mobile' ) ) {
	/**
	 * Cars details page title position in mobile
	 */
	function cardealer_get_cars_details_title_location_mobile() {
		global $car_dealer_options;
		$title_location_mobile = 'below-image-gallery';
		if ( isset( $car_dealer_options['cars-details-title-location-mobile'] ) && ! empty( $car_dealer_options['cars-details-title-location-mobile'] ) ) {
			$title_location_mobile = $car_dealer_options['cars-details-title-location-mobile'];
		}
		return $title_location_mobile;
	}
}

if ( ! function_exists( 'cardealer_get_cars_details_breadcrumb' ) ) {
	/**
	 * Cars details page breadcrumbs
	 */
	function cardealer_get_cars_details_breadcrumb() {
		global $car_dealer_options;
		$mobile_breadcrumb_class = ( isset( $car_dealer_options['breadcrumbs_on_mobile'] ) && 1 === (int) $car_dealer_options['breadcrumbs_on_mobile'] ) ? '' : 'breadcrumbs-hide-mobile';
        if ( function_exists( 'bcn_display_list' )
            && isset( $car_dealer_options['display_breadcrumb'] )
            && ! empty( $car_dealer_options['display_breadcrumb'] )
        ) {
            ob_start();
			?>
			<ul class="page-breadcrumb <?php echo esc_attr( $mobile_breadcrumb_class ); ?>" typeof="BreadcrumbList" vocab="http://schema.org/">
                <?php bcn_display_list(); ?>
            </ul>
            <?php
			echo ob_get_clean();
        }
	}
}

if ( ! function_exists( 'cardealer_bcn_display_list' ) ) {
	function cardealer_bcn_display_list() {

		if ( ! function_exists( 'bcn_display_list' ) ) {
			return null;
		}

		global $post;

		$post_id = get_the_ID();

		if ( class_exists( 'CDHL_CPT_Template' ) && ( is_singular( 'cardealer_template' ) || wp_doing_ajax() ) ) {
			$post_id = CDHL_CPT_Template::get_post_id( 'vehicle_detail' );

			// Setup post data.
			$post = get_post( $post_id );
			setup_postdata( $post );

			bcn_display_list( $return = false, $linked = true, $reverse = false, $force = true );

			// Reset post data.
			wp_reset_postdata();
		} else {
			bcn_display_list();
		}
	}
}

if ( ! function_exists( 'cardealer_single_vehicle_sidebar_attributes' ) ) {
	function cardealer_single_vehicle_sidebar_attributes( $post_id ) {
		?>
		<div class="details-block details-weight">
			<h6><?php esc_html_e( 'Description', 'cardealer' ); ?></h6>
			<?php cardealer_get_cars_attributes( $post_id ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cardealer_vehicle_sold_label' ) ) {
	function cardealer_vehicle_sold_label( $post_id = '' ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$car_status = get_post_meta( $post_id, 'car_status', true );
		if ( ! empty( $car_status ) && 'sold' === $car_status ) {
			global $car_dealer_options;
			$vehicle_sold_label = ( isset( $car_dealer_options['vehicle_sold_label'] ) && '' !== $car_dealer_options['vehicle_sold_label'] ) ? $car_dealer_options['vehicle_sold_label'] : esc_html__( 'Sold', 'cardealer' );
			?>
			<div class="layout-4-vehicle-status"><span class="label layout-4 car-status"><?php echo esc_html( $vehicle_sold_label ); ?></span></div>
			<?php
		}
	}
}

if ( ! function_exists( 'cardealer_vehicle_image_gallery_video_button' ) ) {
	function cardealer_vehicle_image_gallery_video_button( $post_id = '' ) {

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$element_id = uniqid( 'cd_video_' );
        $video_link = get_post_meta( $post_id, 'video_link', true );

		if ( ! empty( $video_link ) ) {
            ob_start();
			?>
			<div class="watch-video-btn">
                <div id="<?php echo esc_attr( $element_id ); ?>"  class="play-video popup-gallery default">
                    <a class="popup-youtube" href="<?php echo esc_url( $video_link ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"> <i class="fas fa-play"></i> <?php esc_html_e( 'Vehicle video', 'cardealer' ); ?></a>
                </div>
            </div>
            <?php
			echo ob_get_clean();
        }
	}
}


/**
 * Vehicle subtitle attributes.
 */
if ( ! function_exists( 'cardealer_subtitle_attributes' ) ) {
	function cardealer_subtitle_attributes( $post_id = null ) {
		global $car_dealer_options;

		$taxonomys            = ( isset( $car_dealer_options['vehicle-subtitle-attributes'] ) ) ? $car_dealer_options['vehicle-subtitle-attributes'] : array();
		$vehicle_mileage_unit = ( isset( $car_dealer_options['vehicle_mileage_unit'] ) && 'none' !== $car_dealer_options['vehicle_mileage_unit'] ) ? $car_dealer_options['vehicle_mileage_unit'] : '';

		if ( empty($taxonomys) || ! $post_id ) {
			return;
		} else if ( empty(array_values($taxonomys) ) ) {
			return;
		}
		$taxonomies_obj = get_object_taxonomies( 'cars', 'object' );
		$attributes     = array();
		foreach ( $taxonomys as $tax ) {
			$term = wp_get_post_terms( $post_id, $tax );
			if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
				$label              = $taxonomies_obj[ $tax ]->labels->singular_name;
				$attributes[ $tax ] = array(
					'attr'  => $label,
					'value' => $term[0]->name,
				);
			}
		}

		$attributs_html = '';
		if ( is_array( $attributes ) && ! empty( $attributes ) ) {

			$attributs_html = '<ul class="vehicle-subtitle-attributes">';
			foreach ( $attributes as $attribute_k => $attribute ) {

				// skip if attribute or value is not set.
				if ( ! isset( $attribute['value'] ) || '' === $attribute['value'] ) {
					continue;
				}

				if ( 'car_mileage' === $attribute_k &&$vehicle_mileage_unit ) {
					$attributs_html .= '<li class="' . esc_attr( $attribute_k ) . '"><span title="' . esc_attr( $attribute['attr'] ) . '">' . $attribute['value'] . ' ' . $vehicle_mileage_unit . '<span></li>';
				} else {
					$attributs_html .= '<li class="' . esc_attr( $attribute_k ) . '"><span title="' . esc_attr( $attribute['attr'] ) . '">' . $attribute['value'] . '<span></li>';
				}
			}
			$attributs_html .= '</ul>';

		}

		echo $attributs_html;
	}
}

if ( ! function_exists( 'cardealer_default_vehicle_tabs' ) ) {

	/**
	 * Get default vehicle tabs.
	 * @return array
	 */
	function cardealer_default_vehicle_tabs() {
		global $car_dealer_options;

		$tabs = array(
			// Overview tabs.
			'overview' => array(
				'id'       => 'overview',
				'title'    => ( isset( $car_dealer_options['cars-vehicle-overview-label'] ) ) ? $car_dealer_options['cars-vehicle-overview-label'] : esc_html__( 'Overview', 'cardealer' ),
				'priority' => 10,
				'callback' => 'cardealer_vehicle_overview_tab',
				'icon'     => 'fas fa-sliders-h',
			),
			// Features & Options tab.
			'features' => array(
				'id'       => 'features',
				'title'    => ( isset( $car_dealer_options['cars-features-options-label'] ) ) ? $car_dealer_options['cars-features-options-label'] : esc_html__( 'Features & Options', 'cardealer' ),
				'priority' => 20,
				'callback' => 'cardealer_vehicle_features_tab',
				'icon'     => 'fas fa-list',
			),
			// Technical Specification tab.
			'technical' => array(
				'id'       => 'technical',
				'title'    => ( isset( $car_dealer_options['cars-technical-specifications-label'] ) ) ? $car_dealer_options['cars-technical-specifications-label'] : esc_html__( 'Technical Specification', 'cardealer' ),
				'priority' => 30,
				'callback' => 'cardealer_vehicle_technical_tab',
				'icon'     => 'fas fa-cogs',
			),

			// General Information tab.
			'general_info' => array(
				'id'       => 'general_info',
				'title'    => ( isset( $car_dealer_options['cars-general-information-label'] ) ) ? $car_dealer_options['cars-general-information-label'] : esc_html__( 'General Information', 'cardealer' ),
				'priority' => 40,
				'callback' => 'cardealer_vehicle_general_info_tab',
				'icon'     => 'fas fa-info-circle',
			),

			// Location tab.
			'location' => array(
				'id'       => 'location',
				'title'    => ( isset( $car_dealer_options['cars-vehicle-location-label'] ) ) ? $car_dealer_options['cars-vehicle-location-label'] : esc_html__( 'Location', 'cardealer' ),
				'priority' => 50,
				'callback' => 'cardealer_vehicle_location_tab',
				'icon'     => 'fas fa-map-marker-alt',
			),
		);

		return $tabs;
	}
}

/**
 * Vehicle detail page tabs functions.
 */
if ( ! function_exists( 'cardealer_vehicle_tabs' ) ) {

	/**
	 * Add default vehicle tabs to vehicle pages.
	 *
	 * @param array $tabs Array of tabs.
	 * @return array
	 */
	function cardealer_vehicle_tabs( $tabs = array() ) {
		$default_tabs = cardealer_default_vehicle_tabs();

		foreach ( $default_tabs as $tab_k => $tab_data ) {

			$is_tab_enabled = apply_filters( 'cardealer_vehicle_is_tab_enabled', false, $tab_k, $tab_data );

			if ( true === $is_tab_enabled ) {
				$tabs[ $tab_k ] = $tab_data;
			}
		}

		return $tabs;
	}
}

function cardealer_vehicle_is_tab_enabled( $enabled, $tab_k, $tab_data ) {
	global $car_dealer_options, $cardealer_pagebuilder_vehicle_tabs;
	$pagebuilder_vehicle_tabs = false;

	if ( isset( $cardealer_pagebuilder_vehicle_tabs ) && true === $cardealer_pagebuilder_vehicle_tabs ) {
		$pagebuilder_vehicle_tabs = true;
	}

	if ( is_singular( 'cardealer_template' ) ) {
		return true;
	}

	$car_id = get_the_ID();

	// Overview tab.
	if ( 'overview' === $tab_k ) {
		$vehicle_overview_display = ( isset( $car_dealer_options['cars-vehicle-overview-option'] ) ) ? $car_dealer_options['cars-vehicle-overview-option'] : 1;
		$vehicle_overview         = get_post_meta( $car_id, 'vehicle_overview', true );
		if ( ( $vehicle_overview_display || $pagebuilder_vehicle_tabs ) && $vehicle_overview ) {
			$enabled = true;
		}

	// Features & Options tab.
	} elseif ( 'features' === $tab_k ) {
		$car_features_options_display = ( isset( $car_dealer_options['cars-features-options-option'] ) ) ? $car_dealer_options['cars-features-options-option'] : 1;
		$car_features_options         = wp_get_post_terms( $car_id, 'car_features_options' );
		if ( ( $car_features_options_display || $pagebuilder_vehicle_tabs ) && ! empty( $car_features_options ) ) {
			$enabled = true;
		}

	// Technical Specification tab.
	} elseif ( 'technical' === $tab_k ) {
		$technical_specifications_display = ( isset( $car_dealer_options['cars-technical-specifications-option'] ) ) ? $car_dealer_options['cars-technical-specifications-option'] : 1;
		$technical_specifications         = get_post_meta( $car_id, 'technical_specifications', true );
		if ( ( $technical_specifications_display || $pagebuilder_vehicle_tabs ) && $technical_specifications ) {
			$enabled = true;
		}

	// General Information tab.
	} elseif ( 'general_info' === $tab_k ) {
		$general_information_display = ( isset( $car_dealer_options['cars-general-information-option'] ) ) ? $car_dealer_options['cars-general-information-option'] : 1;
		$general_information         = get_post_meta( $car_id, 'general_information', true );
		if ( ( $general_information_display || $pagebuilder_vehicle_tabs ) && ! empty( $general_information ) ) {
			$enabled = true;
		}

	// Location tab.
	} elseif ( 'location' === $tab_k ) {
		$location_display = ( isset( $car_dealer_options['cars-vehicle-location-option'] ) ) ? $car_dealer_options['cars-vehicle-location-option'] : 1;
		$location_exist = false;
		$lat            = '';
		$lan            = '';

		if ( isset( $car_dealer_options['default_value_lat'] ) && isset( $car_dealer_options['default_value_long'] ) && ! empty( $car_dealer_options['default_value_lat'] ) && ! empty( $car_dealer_options['default_value_long'] ) ) {
			$location_exist = true;
		}

		$location = get_post_meta( $car_id, 'vehicle_location', true );

		if ( ! empty( $location ) ) {
			$location_exist = true;
		}
		if ( ( $location_display || $pagebuilder_vehicle_tabs ) && $location_exist ) {
			$enabled = true;
		}
	}

	return $enabled;
}

if ( ! function_exists( 'cardealer_sort_vehicle_tabs' ) ) {

	/**
	 * Sort tabs by priority.
	 *
	 * @param array $tabs Array of tabs.
	 * @return array
	 */
	function cardealer_sort_vehicle_tabs( $tabs = array() ) {

		// Make sure the $tabs parameter is an array.
		if ( ! is_array( $tabs ) ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
			trigger_error( 'Function cardealer_sort_vehicle_tabs() expects an array as the first parameter. Defaulting to empty array.' );
			$tabs = array();
		}

		// Re-order tabs by priority.
		if ( ! function_exists( '_sort_priority_callback' ) ) {
			/**
			 * Sort Priority Callback Function
			 *
			 * @param array $a Comparison A.
			 * @param array $b Comparison B.
			 * @return bool
			 */
			function _sort_priority_callback( $a, $b ) {
				if ( ! isset( $a['priority'], $b['priority'] ) || $a['priority'] === $b['priority'] ) {
					return 0;
				}
				return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
			}
		}

		uasort( $tabs, '_sort_priority_callback' );

		return $tabs;
	}
}

if ( ! function_exists( 'cardealer_vehicle_overview_tab' ) ) {

	/**
	 * Output the description tab content.
	 */
	function cardealer_vehicle_overview_tab() {
		get_template_part( 'template-parts/cars/single-car/tabs/tab-overview' );
	}
}

if ( ! function_exists( 'cardealer_vehicle_features_tab' ) ) {

	/**
	 * Output the description tab content.
	 */
	function cardealer_vehicle_features_tab() {
		get_template_part( 'template-parts/cars/single-car/tabs/tab-features' );
	}
}
if ( ! function_exists( 'cardealer_vehicle_technical_tab' ) ) {

	/**
	 * Output the description tab content.
	 */
	function cardealer_vehicle_technical_tab() {
		get_template_part( 'template-parts/cars/single-car/tabs/tab-technical' );
	}
}
if ( ! function_exists( 'cardealer_vehicle_general_info_tab' ) ) {

	/**
	 * Output the description tab content.
	 */
	function cardealer_vehicle_general_info_tab() {
		get_template_part( 'template-parts/cars/single-car/tabs/tab-general-info' );
	}
}
if ( ! function_exists( 'cardealer_vehicle_location_tab' ) ) {

	/**
	 * Output the description tab content.
	 */
	function cardealer_vehicle_location_tab() {
		get_template_part( 'template-parts/cars/single-car/tabs/tab-location' );
	}
}

function cardear_get_vehicle_detail_page_layout() {
	global $car_dealer_options, $post;

	$template_id          = '';
	$template_id_mobile   = '';
	$template_built_with  = false;
	$layout               = isset( $car_dealer_options['cars-details-layout'] ) && ! empty( $car_dealer_options['cars-details-layout'] ) ? $car_dealer_options['cars-details-layout'] : '1';
	$template_type        = isset( $car_dealer_options['vehicle-template-type'] ) && ! empty( $car_dealer_options['vehicle-template-type'] ) ? $car_dealer_options['vehicle-template-type'] : 'template';
	$template_type_mobile = isset( $car_dealer_options['vehicle-template-type-mobile'] ) && ! empty( $car_dealer_options['vehicle-template-type-mobile'] ) ? $car_dealer_options['vehicle-template-type-mobile'] : 'template';
	$active_builders      = cardealer_get_active_page_builders();

	if ( wp_is_mobile() ) {
		$layout = 'mobile';
	}

	if ( class_exists( 'CDHL_CPT_Template' ) ) {
		$template_id        = CDHL_CPT_Template::get_template_id( 'vehicle_detail' );
		$template_id_mobile = CDHL_CPT_Template::get_template_id( 'vehicle_detail_mobile' );
	}

	if ( $post && 'cars' === $post->post_type && ( $template_id || $template_id_mobile ) ) {
		if ( wp_is_mobile() ) {
			$template_built_with = CDHL_CPT_Template::template_built_with( $template_id_mobile );
		} else {
			$template_built_with = CDHL_CPT_Template::template_built_with( $template_id );
		}
	}

	if ( did_action( 'elementor/loaded' ) || class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
		$is_cardealer_template = ( $post && 'cardealer_template' === $post->post_type );
		if (
			$is_cardealer_template
			|| (
				( $post && 'cars' === $post->post_type )
				&& (
					( ! wp_is_mobile() && 'builder' === $template_type && ! empty( $template_id ) )
					|| ( wp_is_mobile() && 'builder' === $template_type_mobile && ! empty( $template_id_mobile ) )
				)
				&& ( false !== $template_built_with && in_array( $template_built_with, $active_builders, true ) )
			)
		) {
			$layout = 'builder';
		}
	}

	$layout = apply_filters( 'cardear_get_vehicle_detail_page_layout', $layout, $post );

	return $layout;
}

function cardear_get_vehicle_detail_page_layout_class() {
	$layout = cardear_get_vehicle_detail_page_layout();
	$class = "car-detail-layout-$layout";
	return $class;
}

function cardealer_vehicle_detail_section_class() {

	$car_section_class = 'car-details page-section-ptb';
	$car_section_class .= ' ' . cardear_get_vehicle_detail_page_layout_class();

	$car_section_class = apply_filters( 'cardealer_vehicle_detail_section_class', $car_section_class );

	echo esc_attr( $car_section_class );
}

function cardealer_vehicle_detail_section_container_class() {

	$class = 'container';
	if ( '3' === cardear_get_vehicle_detail_page_layout() ) {
		$class = 'container-fluid';
	}
	echo esc_attr( $class );
}

function cardealer_get_vehicle_detail_page_mobile_sections( $return = 'all' ) {

	$sections = array(
		'gallery'                => array(
			'label'   => __( 'Gallery - <span>This section will display image gallery.</span>', 'cardealer' ),
			'display' => true,
		),
		'title'                  => array(
			'label'   => __( 'Title/Subtitle - <span>This section will display vehicle title and vehicle attribute as sub-title.</span>', 'cardealer' ),
			'display' => true,
		),
		'price'                  => array(
			'label'   => __( 'Price - <span>This section will display the vehicle price.</span>', 'cardealer' ),
			'display' => false,
		),
		'excerpt'                => array(
			'label'   => __( 'Short Description - <span>This section will display the short description.</span>', 'cardealer' ),
			'display' => false,
		),
		'short_desc'             => array(
			'label'   => __( 'Short Description/Price - <span>This section will display the short description and vehicle price.</span>', 'cardealer' ),
			'display' => true,
		),
		'btn_request_more_info'  => array(
			'label'   => __( 'Request More Info Button - <span>This section will display the "Request More Info" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'btn_buy_online'         => array(
			'label'   => __( 'Buy Online Button - <span>This section will display the "Buy Online" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'review_stamp'           => array(
			'label'   => __( 'Review Stamp - <span>This section will display review stamp.</span>', 'cardealer' ),
			'display' => true,
		),
		'description'            => array(
			'label'   => __( 'Description - <span>This section will display vehicle attributes.</span>', 'cardealer' ),
			'display' => true,
		),
		'fuel_economy'           => array(
			'label'   => __( 'Fuel Economy - <span>This section will display fuel economy.', 'cardealer' ),
			'display' => true,
		),
		'btn_make_an_offer'      => array(
			'label'   => __( 'Make an Offer Button - <span>This section will display the "Make an Offer" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'btn_schedule_test_drive'=> array(
			'label'   => __( 'Schedule Test Drive Button - <span>This section will display the "Schedule Test Drive" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'btn_email_to_friend'    => array(
			'label'   => __( 'Email to a Friend Button - <span>This section will display the "Email to a Friend" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'btn_financial_form'     => array(
			'label'   => __( 'Financial Form Button - <span>This section will display the "Financial Form" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'btn_trade_in_appraisal' => array(
			'label'   => __( 'Trade-In Appraisal Button - <span>This section will display the "Trade-In Appraisal" button.</span>', 'cardealer' ),
			'display' => true,
		),
		'post_actions'           => array(
			'label'   => __( 'Post Actions Button - <span>This section will display Add to Compare, PDF Brochure, Print, and Share buttons.</span>', 'cardealer' ),
			'display' => true,
		),
		'tabs'                   => array(
			'label'   => __( 'Tabs - <span>This section will display all tabs as accordion.</span>', 'cardealer' ),
			'display' => true,
		),
		'related_vehicles'       => array(
			'label'   => __( 'Related Vehicles - <span>This section will display  related vehicles.</span>', 'cardealer' ),
			'display' => true,
		),
		'sidebar_widgets'        => array(
			'label'   => __( 'Sidebar Widgets - <span>This section will display sidebar widgets.</span>', 'cardealer' ),
			'display' => true,
		),
	);

	$sections = apply_filters( 'cardealer_vehicle_detail_page_sections', $sections );
	$sections = apply_filters( 'cardealer_vehicle_detail_page_mobile_sections', $sections );

	if ( 'options' === $return ) {
		$sections = array_map( function( $section_data ) {
			return $section_data['label'];
		}, $sections );
	} elseif ( 'defaults' === $return ) {
		$sections = array_map( function( $section_data ) {
			return ( isset( $section_data['display'] ) ? filter_var( $section_data['display'], FILTER_VALIDATE_BOOLEAN ) : false );
		}, $sections );
	}

	return $sections;
}

function cardealer_vehicle_detail_page_render_mobile_sections() {
	global $car_dealer_options, $post;

	$sections   = cardealer_get_vehicle_detail_page_mobile_sections();
	$selected   = cardealer_get_vehicle_detail_page_mobile_sections( 'defaults' );
	$option_key = 'vehicle_detail_mobile_sections';

	if ( isset( $car_dealer_options[ $option_key ] ) && is_array( $car_dealer_options[ $option_key ] ) && ! empty( $car_dealer_options[ $option_key ] ) ) {
		$selected = filter_var_array( $car_dealer_options[ $option_key ], FILTER_VALIDATE_BOOLEAN );
	}

	if ( is_array( $selected ) && ! empty( $selected ) ) {
		?>
		<div class="detail-sections">
			<?php
			foreach ( $selected as $section => $section_status ) {
				if ( true === $section_status ) {
					if ( isset( $sections[ $section ] ) && ! empty( $sections[ $section ] ) && apply_filters( 'cardealer_vehicle_detail_page_section_enabled', true, $section, $post ) ) {
						$section_slug  = sanitize_file_name( sanitize_title( str_replace( '_', '-', $section ) ) );
						$section_class = "detail-section detail-section-$section_slug";

						do_action( 'cardealer_vehicle_detail_page_render_mobile_sections_before_section', $section, $post );
						?>
						<div class="<?php echo esc_attr( $section_class ); ?>">
							<?php
							do_action( 'cardealer_vehicle_detail_page_render_mobile_sections_inside_section_before', $section, $post );
							$section_data = $sections[ $section ];
							if ( isset( $section_data['callback'] ) && is_callable( $section_data['callback'] ) ) {
								call_user_func( $section_data['callback'], $section, $section_data );
							} else {

								get_template_part( 'template-parts/cars/single-car/sections/' . $section_slug );
							}
							do_action( 'cardealer_vehicle_detail_page_render_mobile_sections_inside_section_after', $section, $post );
							?>
						</div>
						<?php
						do_action( 'cardealer_vehicle_detail_page_render_mobile_sections_after_section', $section, $post );
					}
				}
			}
			?>
		</div>
		<?php
	}
}

function cardealer_vehicle_detail_page_section_enabled( $status, $section, $post ) {
	global $car_dealer_options;

	if ( 'btn_buy_online' === $section ) {
		$status = cardealer_is_vehicle_sellable( $post->ID );
	}
	if ( 'btn_request_more_info' === $section ) {
		if (
			! isset( $car_dealer_options['req_info_form_status'] ) // Option NOT set
			|| ( false === filter_var( $car_dealer_options['req_info_form_status'], FILTER_VALIDATE_BOOLEAN ) ) // Form Disabled
			|| ( // Form Enable, CF7 Enabled, AND CF7 shortcode NOT set OR empty
				true === filter_var( $car_dealer_options['req_info_form_status'], FILTER_VALIDATE_BOOLEAN )
				&& ( isset( $car_dealer_options['req_info_contact_7'] ) && true === filter_var( $car_dealer_options['req_info_contact_7'], FILTER_VALIDATE_BOOLEAN ) )
				&& ( ! isset( $car_dealer_options['req_info_form'] ) || empty( $car_dealer_options['req_info_form'] ) )
			)
		) {
			$status = false;
		}
	}
	if ( 'btn_make_an_offer' === $section ) {
		if (
			! isset( $car_dealer_options['make_offer_form_status'] ) // Option NOT set
			|| ( false === filter_var( $car_dealer_options['make_offer_form_status'], FILTER_VALIDATE_BOOLEAN ) ) // Form Disabled
			|| ( // Form Enable, CF7 Enabled, AND CF7 shortcode NOT set OR empty
				true === filter_var( $car_dealer_options['make_offer_form_status'], FILTER_VALIDATE_BOOLEAN )
				&& ( isset( $car_dealer_options['make_offer_contact_7'] ) && true === filter_var( $car_dealer_options['make_offer_contact_7'], FILTER_VALIDATE_BOOLEAN ) )
				&& ( ! isset( $car_dealer_options['make_offer_form'] ) || empty( $car_dealer_options['make_offer_form'] ) )
			)
		) {
			$status = false;
		}
	}
	if ( 'btn_schedule_test_drive' === $section ) {
		if (
			! isset( $car_dealer_options['schedule_drive_form_status'] ) // Option NOT set
			|| ( false === filter_var( $car_dealer_options['schedule_drive_form_status'], FILTER_VALIDATE_BOOLEAN ) ) // Form Disabled
			|| ( // Form Enable, CF7 Enabled, AND CF7 shortcode NOT set OR empty
				true === filter_var( $car_dealer_options['schedule_drive_form_status'], FILTER_VALIDATE_BOOLEAN )
				&& ( isset( $car_dealer_options['schedule_drive_contact_7'] ) && true === filter_var( $car_dealer_options['schedule_drive_contact_7'], FILTER_VALIDATE_BOOLEAN ) )
				&& ( ! isset( $car_dealer_options['schedule_drive_form'] ) || empty( $car_dealer_options['schedule_drive_form'] ) )
			)
		) {
			$status = false;
		}
	}
	if ( 'btn_email_to_friend' === $section ) {
		if (
			! isset( $car_dealer_options['email_friend_form_status'] ) // Option NOT set
			|| ( false === filter_var( $car_dealer_options['email_friend_form_status'], FILTER_VALIDATE_BOOLEAN ) ) // Form Disabled
			|| ( // Form Enable, CF7 Enabled, AND CF7 shortcode NOT set OR empty
				true === filter_var( $car_dealer_options['email_friend_form_status'], FILTER_VALIDATE_BOOLEAN )
				&& ( isset( $car_dealer_options['email_friend_contact_7'] ) && true === filter_var( $car_dealer_options['email_friend_contact_7'], FILTER_VALIDATE_BOOLEAN ) )
				&& ( ! isset( $car_dealer_options['email_friend_form'] ) || empty( $car_dealer_options['email_friend_form'] ) )
			)
		) {
			$status = false;
		}
	}
	if ( 'btn_financial_form' === $section ) {
		if (
			! isset( $car_dealer_options['financial_form_status'] ) // Option NOT set
			|| ( false === filter_var( $car_dealer_options['financial_form_status'], FILTER_VALIDATE_BOOLEAN ) ) // Form Disabled
			|| ( // Form Enable, CF7 Enabled, AND CF7 shortcode NOT set OR empty
				true === filter_var( $car_dealer_options['financial_form_status'], FILTER_VALIDATE_BOOLEAN )
				&& ( isset( $car_dealer_options['financial_form_contact_7'] ) && true === filter_var( $car_dealer_options['financial_form_contact_7'], FILTER_VALIDATE_BOOLEAN ) )
				&& ( ! isset( $car_dealer_options['financial_form'] ) || empty( $car_dealer_options['financial_form'] ) )
			)
		) {
			$status = false;
		}
	}
	if ( 'btn_trade_in_appraisal' === $section ) {
		if (
			! isset( $car_dealer_options['trade_in_appraisal_form_status'] ) // Option NOT set
			|| ( false === filter_var( $car_dealer_options['trade_in_appraisal_form_status'], FILTER_VALIDATE_BOOLEAN ) ) // Form Disabled
			|| ( ! isset( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] ) || empty( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] ) ) // CF7 shortcode NOT set OR empty
		) {
			$status = false;
		}
	}
	return $status;
}

function cardealer_vehicle_listing_page_mobile_filters() {
	$filter_location = cardealer_vehicle_listing_mobile_filter_location();

	if ( 'off-canvas' === $filter_location ) {
		cardealer_get_offcanvas();
	} else {
		?>
		<div class="col-sm-12">
			<div class="mobile-vehicle-filters-wrap">
				<?php cardealer_widget_area__listing_cars(); ?>
			</div>
		</div>
		<?php
	}
}

function cardealer_widget_area__listing_cars() {
	if ( is_active_sidebar( 'listing-cars' ) ) {
		?>
		<div class="listing-sidebar sidebar">
			<?php dynamic_sidebar( 'listing-cars' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cardealer_get_offcanvas' ) ) {
	/**
	 * Get off-canvas content.
	 *
	 * @since 3.4.0
	 *
	 * @return string.
	 */
	function cardealer_get_offcanvas() {
		global $car_dealer_options;

		$style             = '';
		$listing_sidebar   = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
		$off_sidebar_class = 'cardealer-offcanvas is-closed cardealer-offcanvas-' . $listing_sidebar;
		if ( is_admin_bar_showing() ) {
			$style = 'top:32px;';
		}
		?>
		<aside class="<?php echo esc_attr( $off_sidebar_class ); ?>" style="<?php echo esc_attr( $style ); ?>">
			<a href="#" class="cardealer-offcanvas-close-btn"><?php esc_html_e( 'Close', 'cardealer' ); ?></a>
			<div class="cardealer-offcanvas-content">
				<?php
				/**
				 * Fires before the off-canvas content.
				 *
				 * @since 3.4.0
				 */
				do_action( 'cardealer_before_offcanvas' );

				do_action( 'cardealer_offcanvas' );

				/**
				 * Fires after the off-canvas content.
				 *
				 * @since 3.4.0
				 */
				do_action( 'cardealer_after_offcanvas' );
				?>
			</div>
		</aside>
		<div class="cardealer-offcanvas-overlay is-closed"></div>
		<?php
	}
}

function cardealer_vehicle_list_mobile_view_type( $cars_grid ) {
	if ( wp_is_mobile() ) {
		$cars_grid = 'yes';
	}
	return $cars_grid;
}

/**
 * Vehicle detail page tabs functions.
 */
if ( ! function_exists( 'cardealer_get_lead_form' ) ) {

	/**
	 * List of lead forms.
	 *
	 * @param string $lead_form   Name of lead form.
	 * @return array
	 */
	function cardealer_get_lead_form( $lead_form = '' ) {
		$return = false;

		$lead_forms_defaults = array(
			'req_more_info' => array(
				'btn_label'   => cardealer_get_theme_option( 'lead_form_req_more_info_btn_label', esc_html__( 'Request More Info', 'cardealer' ) ),
				'modal_title' => cardealer_get_theme_option( 'lead_form_req_more_info_modal_title', esc_html__( 'Request More Info', 'cardealer' ) ),
				'icon'        => 'fas fa-question-circle',
			),
			'make_an_offer' => array(
				'btn_label'   => cardealer_get_theme_option( 'lead_form_make_an_offer_btn_label', esc_html__( 'Make an Offer', 'cardealer' ) ),
				'modal_title' => cardealer_get_theme_option( 'lead_form_make_an_offer_modal_title', esc_html__( 'Make an Offer', 'cardealer' ) ),
				'icon'        => 'fas fa-tag',
			),
			'schedule_test_drive' => array(
				'btn_label'   => cardealer_get_theme_option( 'lead_form_schedule_test_drive_btn_label', esc_html__( 'Schedule Test Drive', 'cardealer' ) ),
				'modal_title' => cardealer_get_theme_option( 'lead_form_schedule_test_drive_modal_title', esc_html__( 'Schedule Test Drive', 'cardealer' ) ),
				'icon'        => 'fas fa-tachometer-alt',
			),
			'email_to_friend' => array(
				'btn_label'   => cardealer_get_theme_option( 'lead_form_email_to_friend_btn_label', esc_html__( 'Email to a Friend', 'cardealer' ) ),
				'modal_title' => cardealer_get_theme_option( 'lead_form_email_to_friend_modal_title', esc_html__( 'Email to a Friend', 'cardealer' ) ),
				'icon'        => 'fas fa-envelope',
			),
			'financial_form' => array(
				'btn_label'   => cardealer_get_theme_option( 'lead_form_financial_form_btn_label', esc_html__( 'Financial Form', 'cardealer' ) ),
				'modal_title' => cardealer_get_theme_option( 'lead_form_financial_form_modal_title', esc_html__( 'Financial Form', 'cardealer' ) ),
				'icon'        => 'far fa-file-alt',
			),
			'trade_in_appraisal' => array(
				'btn_label'   => cardealer_get_theme_option( 'lead_form_trade_in_appraisal_btn_label', esc_html__( 'Trade-In Appraisal', 'cardealer' ) ),
				'modal_title' => cardealer_get_theme_option( 'lead_form_trade_in_appraisal_modal_title', esc_html__( 'Trade-In Appraisal', 'cardealer' ) ),
				'icon'        => 'fas fa-exchange-alt',
			),
			'req_price_form' => array(
				'modal_title' => cardealer_get_theme_option( 'req_price_modal_title', esc_html__( 'Request Price Form', 'cardealer' ) ),
			),
		);

		$lead_forms = apply_filters( 'cardealer_get_lead_form', $lead_forms_defaults );

		if ( isset( $lead_forms[ $lead_form ] ) ) {
			$return = $lead_forms[ $lead_form ];
		} elseif ( isset( $lead_forms_defaults[ $lead_form ] ) ) {
			$return = $lead_forms_defaults[ $lead_form ];
		}

		return $return;
	}
}

/**
 * List of vehicle detail page action buttons.
 *
 * @return array
 */
function cardealer_vehicle_buttons() {

	$default_buttons = array(
		'request-more-info' => array(
			'btn_id'      => 'request-more-info',
			'btn_label'   => cardealer_get_theme_option( 'lead_form_req_more_info_btn_label', esc_html__( 'Request More Info', 'cardealer' ) ),
			'icon'        => 'fas fa-question-circle',
			'btn_type'    => 'modal',
			'modal_title' => cardealer_get_theme_option( 'lead_form_req_more_info_modal_title', esc_html__( 'Request More Info', 'cardealer' ) ),
		),
		'make-an-offer' => array(
			'btn_id'      => 'make-an-offer',
			'btn_label'   => cardealer_get_theme_option( 'lead_form_make_an_offer_btn_label', esc_html__( 'Make an Offer', 'cardealer' ) ),
			'icon'        => 'fas fa-tag',
			'btn_type'    => 'modal',
			'modal_title' => cardealer_get_theme_option( 'lead_form_make_an_offer_modal_title', esc_html__( 'Make an Offer', 'cardealer' ) ),
		),
		'schedule-test-drive' => array(
			'btn_id'      => 'schedule-test-drive',
			'btn_label'   => cardealer_get_theme_option( 'lead_form_schedule_test_drive_btn_label', esc_html__( 'Schedule Test Drive', 'cardealer' ) ),
			'icon'        => 'fas fa-tachometer-alt',
			'btn_type'    => 'modal',
			'modal_title' => cardealer_get_theme_option( 'lead_form_schedule_test_drive_modal_title', esc_html__( 'Schedule Test Drive', 'cardealer' ) ),
		),
		'email-to-friend' => array(
			'btn_id'      => 'email-to-friend',
			'btn_label'   => cardealer_get_theme_option( 'lead_form_email_to_friend_btn_label', esc_html__( 'Email to a Friend', 'cardealer' ) ),
			'icon'        => 'fas fa-envelope',
			'btn_type'    => 'modal',
			'modal_title' => cardealer_get_theme_option( 'lead_form_email_to_friend_modal_title', esc_html__( 'Email to a Friend', 'cardealer' ) ),
		),
		'financial-form' => array(
			'btn_id'      => 'financial-form',
			'btn_label'   => cardealer_get_theme_option( 'lead_form_financial_form_btn_label', esc_html__( 'Financial Form', 'cardealer' ) ),
			'icon'        => 'far fa-file-alt',
			'btn_type'    => 'modal',
			'modal_title' => cardealer_get_theme_option( 'lead_form_financial_form_modal_title', esc_html__( 'Financial Form', 'cardealer' ) ),
		),
		'trade-in-appraisal' => array(
			'btn_id'      => 'trade-in-appraisal',
			'btn_label'   => cardealer_get_theme_option( 'lead_form_trade_in_appraisal_btn_label', esc_html__( 'Trade-In Appraisal', 'cardealer' ) ),
			'icon'        => 'fas fa-exchange-alt',
			'btn_type'    => 'modal',
			'modal_title' => cardealer_get_theme_option( 'lead_form_trade_in_appraisal_modal_title', esc_html__( 'Trade-In Appraisal', 'cardealer' ) ),
		),
		'pdf-brochure' => array(
			'btn_id'      => 'pdf-brochure',
			'btn_label'   => esc_html__( 'PDF Brochure', 'cardealer' ),
			'icon'        => 'far fa-file-pdf',
			'btn_type'    => 'link',
			'url'         => '#',
		),
		'print'      => array(
			'btn_id'      => 'print',
			'btn_label'   => esc_html__( 'Print', 'cardealer' ),
			'icon'        => 'fas fa-print',
			'btn_type'    => 'js_event',
			'event'       => 'cardealer-vehicle-button-print',
		),
		'buy-online'      => array(
			'btn_id'      => 'buy-online',
			'btn_label'   => sell_vehicle_lable_text(),
			'icon'        => 'fas fa-shopping-cart',
			'btn_type'    => 'js_event',
			'event'       => 'cardealer-vehicle-button-buy-online',
		),
	);

	$buttons = apply_filters( 'cardealer_vehicle_buttons', $default_buttons );
	return $buttons;
}

function cardealer_extend_cdhl_vehicle_buttons_option( $options ) {

	$buttons = cardealer_vehicle_buttons();

	$options = array_merge( $options, array_column( $buttons, 'btn_label', 'btn_id' ) );

	return $options;
}

function cardealer_builtin_vehicle_buttons() {
	return array (
		'request-more-info',
		'make-an-offer',
		'schedule-test-drive',
		'email-to-friend',
		'financial-form',
		'trade-in-appraisal',
		'pdf-brochure',
		'print',
		'buy-online',
	);
}
function cardealer_get_vehicle_buttons_data( $show_buttons = array() ) {
	global $post;

	$vehicle_buttons      = cardealer_vehicle_buttons();
	$builtin_buttons      = cardealer_builtin_vehicle_buttons();
	$buttons              = array();
	$default_button_types = array(
		'modal',
		'link',
		'js_event',
	);

	if ( ! is_array( $show_buttons ) || empty( $show_buttons ) ) {
		$show_buttons = $builtin_buttons;
	}

	// Prepapre buttons.
	if ( $show_buttons ) {
		foreach ( $show_buttons as $button_k ) {

			// Check whether set in the vehicle buttons.
			if ( ! isset( $vehicle_buttons[ $button_k ] ) || ! is_array( $vehicle_buttons[ $button_k ] ) || empty( $vehicle_buttons[ $button_k ] ) ) {
				continue;
			}

			$button = $vehicle_buttons[ $button_k ];

			// Check if button label is set.
			if ( ! isset( $button['btn_label'] ) || empty( $button['btn_label'] ) ) {
				continue;
			}

			// PDF Attached.
			if ( 'pdf-brochure' === $button_k ) {
				$pdf_attached = false;
				$pdf_file_id  = get_post_meta( get_the_ID(), 'pdf_file', $single = true );
				if ( isset( $pdf_file_id ) && ! empty( $pdf_file_id ) && wp_attachment_is( 'pdf', $pdf_file_id ) ) {
					$pdf_file_url = wp_get_attachment_url( $pdf_file_id );
					if ( $pdf_file_url ) {
						$button['pdf_brochure_url'] = $pdf_file_url;
					}
				}
			}

			// Check if enabled.
			$button_enabled = false;
			if ( in_array( $button_k, $builtin_buttons, true ) ) {
				$button_enabled = apply_filters( 'cardealer_builtin_vehicle_button_enabled', false, $button_k, $button );
			} else {
				$button_enabled = apply_filters( 'cardealer_vehicle_button_enabled', true, $button_k, $button );
			}

			if ( ! $button_enabled ) {
				continue;
			}

			// Check if modal.
			if ( 'modal' === $button['btn_type'] ) {

				// Skip if modal title not found.
				if ( ! isset( $button['modal_title'] ) ) {
					continue;
				}

				$callback = false;
				if ( isset( $button['callback'] ) && ! empty( $button['callback'] ) && is_string( $button['callback'] ) ) {
					$callback = $button['callback'];
				} else {
					$callback = str_replace( '-', '_', "cardealer_vehicle_button_content_callback_{$button_k}" );
				}

				// Skip if content callback not found.
				if ( ! $callback || ! is_callable( $callback ) ) {
					continue;
				}

				$button['callback'] = $callback;

				if ( ! isset( $button['modal_id'] ) ) {
					$button['modal_id'] = $modal_id = 'cardealer-vehicle-button-content-' . str_replace( '_', '-', $button_k );
				}
			}

			// Prepare PDF Brochure link.
			if ( 'link' === $button['btn_type'] && 'pdf-brochure' === $button_k ) {
				$button['url'] = isset( $button['pdf_brochure_url'] ) ? $button['pdf_brochure_url'] : '';
				unset( $button['pdf_brochure_url'] );
			}

			// Prepare Buy Online link.
			if ( 'js_event' === $button['btn_type'] && 'buy-online' === $button_k ) {
				$button['vehicle_id'] = $post->ID;
			}

			$button['btn_type'] = ( isset( $button['btn_type'] ) && is_string( $button['btn_type'] ) && in_array( $button['btn_type'], $default_button_types, true ) ) ? $button['btn_type'] : 'link';

			if ( ( ! isset( $button['icon_html'] ) || empty( $button['icon_html'] ) ) && ( isset ( $button['icon'] ) && ! empty( $button['icon'] ) ) ) {
				$button['icon_html'] = sprintf( '<i class="%1$s"></i>', esc_attr( $button['icon'] ) );
			}

			$button['icon_allowed_html'] = cardealer_allowed_html( 'i' );

			if (
				'js_event' === $button['btn_type']
				&& ( ! isset( $button['event'] ) || empty( $button['event'] ) || ! is_string( $button['event'] ) )
			) {
				$button['event'] = 'blank';
			}

			$buttons[ $button_k ] = $button;
		}
	}

	$buttons = apply_filters( 'cardealer_get_vehicle_buttons_data', $buttons );

	return $buttons;
}

function cardealer_display_vehicle_buttons( $show_buttons = array() ) {
	global $cardealer_vehicle_button_contents_data;

	$buttons = cardealer_get_vehicle_buttons_data( $show_buttons );
	if ( ! empty( $buttons ) ) {
		?>
		<div class="vehicle-buttons-wrap details-nav">
			<ul class="vehicle-buttons">
				<?php
				foreach ( $buttons as $button_k => $button ) {
					if ( 'modal' === $button['btn_type'] && ! isset( $cardealer_vehicle_button_contents_data[ $button_k ] ) ) {
						$cardealer_vehicle_button_contents_data[ $button_k ] = $button;
					}
					$li_classes = array(
						'vehicle-button',
						'vehicle-button-' . $button_k,
						'vehicle-button-type-' . $button['btn_type'],
					);
					if ( isset( $button['btn_class'] ) && ! empty( $button['btn_class'] ) ) {
						$li_classes[] = cardealer_class_generator( $button['btn_class'], false );
					}
					$li_classes = cardealer_class_generator( $li_classes, false );
					$icon_html  = ( isset( $button['icon_html'] ) ) ? $button['icon_html'] : '';

					$link_classes = array(
						'vehicle-button-link',
						'vehicle-button-link-' . $button_k,
						'vehicle-button-link-type-' . $button['btn_type'],
					);
					if ( isset( $button['link_class'] ) && ! empty( $button['link_class'] ) ) {
						$link_classes[] = cardealer_class_generator( $button['link_class'], false );
					}
					$link_classes = cardealer_class_generator( $link_classes, false );
					?>
					<li class="<?php echo esc_attr( $li_classes ); ?>">
						<?php
						if ( 'modal' === $button['btn_type'] ) {
							?>
							<a href="javascript:void(0)" class="<?php echo esc_attr( $link_classes ); ?>" data-btn_type="<?php echo esc_attr( $button['btn_type'] ); ?>" data-toggle="modal" data-target="#<?php echo esc_attr( $button['modal_id'] ); ?>">
								<?php echo wp_kses( $icon_html, $button['icon_allowed_html'] ); ?><?php echo esc_html( $button['btn_label'] ); ?>
							</a>
							<?php
						} elseif ( 'link' === $button['btn_type'] ) {
								if ( 'pdf-brochure' === $button_k ) {
									?>
									<a href="<?php echo esc_url( $button['url'] )?>" class="<?php echo esc_attr( $link_classes ); ?>" data-btn_type="<?php echo esc_attr( $button['btn_type'] ); ?>" download>
										<?php echo wp_kses( $icon_html, $button['icon_allowed_html'] ); ?><?php echo esc_html( $button['btn_label'] ); ?>
									</a>
									<?php
								} else {
									?>
									<a href="<?php echo esc_url( $button['url'] )?>" class="<?php echo esc_attr( $link_classes ); ?>" data-btn_type="<?php echo esc_attr( $button['btn_type'] ); ?>" >
										<?php echo wp_kses( $icon_html, $button['icon_allowed_html'] ); ?><?php echo esc_html( $button['btn_label'] ); ?>
									</a>
									<?php
								}
						} elseif ( 'js_event' === $button['btn_type'] ) {
							if ( 'buy-online' === $button_k ) {
								?>
								<a href="javascript:void(0)" class="<?php echo esc_attr( $link_classes ); ?>" data-btn_type="<?php echo esc_attr( $button['btn_type'] ); ?>" data-event="<?php echo esc_attr( $button['event'] ); ?>" data-vehicle_id="<?php echo esc_attr( $button['vehicle_id'] ); ?>">
									<?php echo wp_kses( $icon_html, $button['icon_allowed_html'] ); ?><?php echo esc_html( $button['btn_label'] ); ?>
								</a>
								<?php
							} else {
								?>
								<a href="javascript:void(0)" class="<?php echo esc_attr( $link_classes ); ?>" data-btn_type="<?php echo esc_attr( $button['btn_type'] ); ?>" data-event="<?php echo esc_attr( $button['event'] ); ?>">
									<?php echo wp_kses( $icon_html, $button['icon_allowed_html'] ); ?><?php echo esc_html( $button['btn_label'] ); ?>
								</a>
								<?php
							}
						} else {
							?>
							<a href="javascript:void(0)" class="<?php echo esc_attr( $link_classes ); ?>" data-btn_type="<?php echo esc_attr( $button['btn_type'] ); ?>">
								<?php echo wp_kses( $icon_html, $button['icon_allowed_html'] ); ?><?php echo esc_html( $button['btn_label'] ); ?>
							</a>
							<?php
						}
						?>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}
}

function cardealer_builtin_vehicle_button_enabled( $status, $button_k, $button ) {
	global $car_dealer_options, $post;

	$builtin_buttons      = cardealer_builtin_vehicle_buttons();
	$theme_option_enabled = false;

	// Enable all buttons if editing template.
	if ( is_singular( CDHL_CPT_Template::CPT ) ) {
		return true;
	}

	if ( in_array( $button_k, $builtin_buttons, true ) ) {

		$is_vehicle_sellable = cardealer_is_vehicle_sellable( $post->ID );

		if (
			// Request More Info.
			(
				'request-more-info' === $button_k
				&& ( isset( $car_dealer_options['req_info_form_status'] ) && cardealer_validate_bool( $car_dealer_options['req_info_form_status'] ) )
			)
			// Make an offer.
			|| (
				'make-an-offer' === $button_k
				&& ( isset( $car_dealer_options['make_offer_form_status'] ) && cardealer_validate_bool( $car_dealer_options['make_offer_form_status'] ) )
			)
			// Schedule test drive.
			|| (
				'schedule-test-drive' === $button_k
				&& ( isset( $car_dealer_options['schedule_drive_form_status'] ) && cardealer_validate_bool( $car_dealer_options['schedule_drive_form_status'] ) )
			)
			// Email to friend.
			|| (
				'email-to-friend' === $button_k
				&& ( isset( $car_dealer_options['email_friend_form_status'] ) && cardealer_validate_bool( $car_dealer_options['email_friend_form_status'] ) )
			)
			// Financial form.
			|| (
				'financial-form' === $button_k
				&& ( isset( $car_dealer_options['financial_form_status'] ) && cardealer_validate_bool( $car_dealer_options['financial_form_status'] ) )
			)
			// Trade-in-appraisal.
			|| (
				'trade-in-appraisal' === $button_k
				&& ( isset( $car_dealer_options['trade_in_appraisal_form_status'] ) && cardealer_validate_bool( $car_dealer_options['trade_in_appraisal_form_status'] ) )
				&& ( isset( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] ) && ! empty( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] ) )
			)
			// PDF Brochure.
			|| (
				'pdf-brochure' === $button_k
				&& ( isset( $car_dealer_options['pdf_brochure_status'] ) && cardealer_validate_bool( $car_dealer_options['pdf_brochure_status'] ) )
				&& ( isset( $button['pdf_brochure_url'] ) && ! empty( $button['pdf_brochure_url'] ) )
			)
			// Print.
			|| (
				'print' === $button_k
				&& ( isset( $car_dealer_options['print_status'] ) && cardealer_validate_bool( $car_dealer_options['print_status'] ) )
			)
			// Buy Online.
			|| (
				'buy-online' === $button_k
				&& $is_vehicle_sellable
			)
		) {
			$status = true;
		}
	}

	return $status;
}

function cardealer_render_vehicle_button_contents() {
	global $cardealer_vehicle_button_contents_data;

	if ( is_array( $cardealer_vehicle_button_contents_data ) && ! empty( $cardealer_vehicle_button_contents_data ) ) {
		?>
		<div class="cardealer-vehicle-buttons-content">
			<?php
			foreach ( $cardealer_vehicle_button_contents_data as $button_k => $button ) {
				if ( 'modal' === $button['btn_type'] && ( $button['callback'] && is_callable( $button['callback'] ) ) ) {
					call_user_func( $button['callback'], $button_k, $button );
				}
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cardealer_vehicle_button_content_callback_request_more_info' ) ) {
	function cardealer_vehicle_button_content_callback_request_more_info( $button_k, $button ) {
		get_template_part( 'template-parts/cars/single-car/button-contents/form-' . $button_k, null, $button );
	}
}
if ( ! function_exists( 'cardealer_vehicle_button_content_callback_make_an_offer' ) ) {
	function cardealer_vehicle_button_content_callback_make_an_offer( $button_k, $button ) {
		get_template_part( 'template-parts/cars/single-car/button-contents/form-' . $button_k, null, $button );
	}
}
if ( ! function_exists( 'cardealer_vehicle_button_content_callback_schedule_test_drive' ) ) {
	function cardealer_vehicle_button_content_callback_schedule_test_drive( $button_k, $button ) {
		get_template_part( 'template-parts/cars/single-car/button-contents/form-' . $button_k, null, $button );
	}
}
if ( ! function_exists( 'cardealer_vehicle_button_content_callback_email_to_friend' ) ) {
	function cardealer_vehicle_button_content_callback_email_to_friend( $button_k, $button ) {
		get_template_part( 'template-parts/cars/single-car/button-contents/form-' . $button_k, null, $button );
	}
}
if ( ! function_exists( 'cardealer_vehicle_button_content_callback_financial_form' ) ) {
	function cardealer_vehicle_button_content_callback_financial_form( $button_k, $button ) {
		get_template_part( 'template-parts/cars/single-car/button-contents/form-' . $button_k, null, $button );
	}
}
if ( ! function_exists( 'cardealer_vehicle_button_content_callback_trade_in_appraisal' ) ) {
	function cardealer_vehicle_button_content_callback_trade_in_appraisal( $button_k, $button ) {
		get_template_part( 'template-parts/cars/single-car/button-contents/form-' . $button_k, null, $button );
	}
}

function cardealer_vehicle_tabs_option( $options ) {

	$tabs    = cardealer_default_vehicle_tabs();
	$tabs    = array_column( $tabs, 'title', 'id' );
	$options = array_merge( $options, $tabs );

	return $options;
}

function cardealer_show_featured_vehicles() {
	global $car_dealer_options;

	$show_featured_vehicles = ( isset( $car_dealer_options['show_featured_vehicles'] ) && ! empty( $car_dealer_options['show_featured_vehicles'] ) ) ? $car_dealer_options['show_featured_vehicles'] : 'no';
	$show_featured_vehicles = filter_var( $show_featured_vehicles, FILTER_VALIDATE_BOOLEAN );
	$show_featured_vehicles = apply_filters( 'cardealer_show_featured_vehicles', $show_featured_vehicles );

	return $show_featured_vehicles;
}

function cardealer_featured_vehicle_listing() {
	$show_featured_vehicles = cardealer_show_featured_vehicles();

	if ( $show_featured_vehicles ) {
		get_template_part( 'template-parts/cars/featured-cars' );
	}
}

function cardealer_featured_vehicles_section_title() {
	global $car_dealer_options;

	$section_title = ( isset( $car_dealer_options['featured_vehicles_section_title'] ) && ! empty( $car_dealer_options['featured_vehicles_section_title'] ) ) ? trim( $car_dealer_options['featured_vehicles_section_title'] ) : esc_html__( 'Featured Listing', 'cardealer' );

	return apply_filters( 'cardealer_featured_vehicles_section_title', $section_title );
}

function cardealer_get_featured_vehicles_count() {
	global $car_dealer_options;

	$list_style = cardealer_get_featured_vehicles_list_style();
	$count      = 3;

	if ( 'carousel' === $list_style ) {
		$count = ( isset( $car_dealer_options['featured_vehicles_count_carousel'] ) && ! empty( $car_dealer_options['featured_vehicles_count_carousel'] ) ) ? $car_dealer_options['featured_vehicles_count_carousel'] : 5;
	} else {
		$listing_layout = cardealer_get_vehicle_listing_page_layout();
		if ( 'lazyload' === $listing_layout ) {
			$count = 5;
		} else {
			$count = 3;
		}
	}

	return (int) apply_filters( 'cardealer_get_featured_vehicles_count', $count, $list_style );
}
function cardealer_show_featured_vehicles_filtered() {
	global $car_dealer_options;

	$filtered_default = 'non_filtered';

	if ( isset( $car_dealer_options['featured_vehicles_filtered'] ) && ! empty( $car_dealer_options['featured_vehicles_filtered'] ) ) {
		$filtered = $car_dealer_options['featured_vehicles_filtered'];
	} else {
		$filtered = $filtered_default;
	}

	$filtered = apply_filters( 'cardealer_show_featured_vehicles_filtered', $filtered );

	if ( ! in_array( $filtered, array( 'non_filtered', 'filtered' ), true ) ) {
		$filtered = $filtered_default;
	}

	return $filtered;
}

function cardealer_get_featured_vehicles_badge_type() {
	global $car_dealer_options;

	$badge_type = ( isset( $car_dealer_options['featured_vehicles_badge_type'] ) && ! empty( $car_dealer_options['featured_vehicles_badge_type'] ) ) ? $car_dealer_options['featured_vehicles_badge_type'] : 'star';

	return apply_filters( 'cardealer_get_featured_vehicles_badge_type', $badge_type );
}

function cardealer_get_featured_vehicle_badge_label( $vehicle = '' ) {
	global $car_dealer_options;

	$badge_label = ( isset( $car_dealer_options['featured_vehicle_badge_label'] ) && ! empty( $car_dealer_options['featured_vehicle_badge_label'] ) ) ? trim( $car_dealer_options['featured_vehicle_badge_label'] ) : esc_html__( 'Featured', 'cardealer' );

	if ( $vehicle && is_a( $vehicle, 'WP_Post' ) && 'cars' === $vehicle->post_type && isset( $vehicle->featured_vehicle_badge_label_source ) && 'custom' === $vehicle->featured_vehicle_badge_label_source ) {
		if ( isset( $vehicle->featured_vehicle_badge_label ) && ! empty( $vehicle->featured_vehicle_badge_label ) ) {
			$badge_label = trim( $vehicle->featured_vehicle_badge_label );
		}
	}

	return apply_filters( 'cardealer_get_featured_vehicle_badge_label', $badge_label, $vehicle );
}

function cardealer_get_featured_vehicle_badge_color( $vehicle = '' ) {
	global $car_dealer_options;

	$badge_color = ( isset( $car_dealer_options['featured_vehicle_badge_color'] ) && ! empty( $car_dealer_options['featured_vehicle_badge_color'] ) ) ? $car_dealer_options['featured_vehicle_badge_color'] : '#0d6efd';

	if ( $vehicle && is_a( $vehicle, 'WP_Post' ) && 'cars' === $vehicle->post_type && isset( $vehicle->featured_vehicle_badge_label_source ) && 'custom' === $vehicle->featured_vehicle_badge_label_source ) {
		if ( isset( $vehicle->featured_vehicle_badge_color ) && ! empty( $vehicle->featured_vehicle_badge_color ) ) {
			$badge_color = $vehicle->featured_vehicle_badge_color;
		}
	}

	return apply_filters( 'cardealer_get_featured_vehicle_badge_color', $badge_color, $vehicle );
}

function cardealer_featured_vehicle_badge( $vehicle_id = null, $echo = false ) {

	if ( empty( $vehicle_id ) ) {
		$vehicle_id = get_the_ID();
	}

	$vehicle = get_post( $vehicle_id );

	if (
		( $vehicle && is_a( $vehicle, 'WP_Post' ) && 'cars' === $vehicle->post_type )
		&& ( isset( $vehicle->featured ) && ! empty( $vehicle->featured ) && 1 === (int) $vehicle->featured )
	) {
		$badge_type           = cardealer_get_featured_vehicles_badge_type();
		$featured_wrapp_class = array(
			'label',
			'label-featured-wrap',
			'label-featured_type-' . $badge_type
		);
		$badge_color
		?>
		<div class="<?php cardealer_class_generator( $featured_wrapp_class, true ); ?>">
			<?php
			if ( 'label' === $badge_type ) {
				$badge_label = cardealer_get_featured_vehicle_badge_label( $vehicle );
				$badge_color = cardealer_get_featured_vehicle_badge_color( $vehicle );
				?>
				<span class="label-featured" style="background-color:<?php echo esc_attr( $badge_color ); ?>;"><?php echo esc_html( $badge_label ); ?></span>
				<?php
			} else {
				?>
				<i class="fas fa-star" aria-hidden="true"></i>
				<?php
			}
			?>
		</div>
		<?php
	}
}

function cardealer_cpt_cars_filter_featured( $post_type, $which ) {
	if ( 'cars' !== $post_type ){
		return;
	}
	$current = ( isset( $_GET['featured_vehicle'] ) && '' !== $_GET['featured_vehicle'] ) ? $_GET['featured_vehicle'] : '';
	?>
	<select name="featured_vehicle">
		<option value=""><?php _e('Filter By Featured', 'cardealer' ); ?></option>
		<option value="yes" <?php selected( $current, 'yes' ); ?>><?php _e( 'Featured', 'cardealer' ); ?></option>
		<option value="no" <?php selected( $current, 'no' ); ?>><?php _e( 'Non-featured', 'cardealer' ); ?></option>
	</select>
	<?php
}

function cardealer_cpt_cars_parse_filter_featured( $query ) {
	global $pagenow;

	if (
		is_admin()
		&& 'edit.php' === $pagenow
		&& ( isset( $_GET['post_type'] ) && 'cars' == isset( $_GET['post_type'] ) )
		&& ( isset( $_GET['featured_vehicle'] ) && $_GET['featured_vehicle'] !== '' )
	) {
		$meta_query = array();

		if ( 'yes' === $_GET['featured_vehicle'] ) {
			$meta_query[] = array(
				'key'     => 'featured',
				'value'   => 1,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
		} elseif ( 'no' === $_GET['featured_vehicle'] ) {
			$meta_query['relation'] = 'OR';
			$meta_query[] = array(
				'key'     => 'featured',
				'value'   => 0,
				'compare' => '=',
				'type'    => 'NUMERIC',
			);
			$meta_query[] = array(
				'key'     => 'featured',
				'compare' => 'NOT EXISTS',
			);
		}
		$query->set( 'meta_query', $meta_query );
	}
}

function cardealer_grid_view_featured_classes( $classes, $columns ) {
	global $cardealer_is_featured_vehicles_section;

	if ( $cardealer_is_featured_vehicles_section ) {
		$list_style = cardealer_get_featured_vehicles_list_style();
		if ( 'carousel' === $list_style ) {
			$classes = array(
				'vehicle-listing-featured-item',
			);
		} else {
			$classes = array(
				/*
				'col-lg-4',
				'col-md-4',
				'col-sm-4',
				'col-xs-6',
				*/
				'vehicle-listing-featured-item',
			);
		}
	}

	return $classes;
}

function cardealer_get_faq_tabs_data( $faq_cat_ids ) {
	global $tab_instance;
	$faq_tabs_data = array();
	$tab_instance  = ( $tab_instance ) ? $tab_instance + 1 : 1;

	if ( is_string( $faq_cat_ids ) ) {
		if ( 'all' === $faq_cat_ids ) {
			$faq_cat_ids = get_terms(
				array(
					'taxonomy'   => 'faq-category',
					'hide_empty' => true,
					'fields'     => 'ids',
				)
			);
		} else {
			$faq_cat_ids = array();
		}
	}

	if ( ! empty( $faq_cat_ids ) ) {
		$faq_tabs       = array();
		$faq_base_query = array(
			'post_type'      => 'faqs',
			'posts_per_page' => defined( 'PHP_INT_MAX' ) ? PHP_INT_MAX : -1,
		);

		foreach ( $faq_cat_ids as $faq_cat_index => $faq_cat_id ) {
			$cat_query_args    = array();
			$faq_category_data = get_term_by( 'id', $faq_cat_id, 'faq-category' );

			if ( $faq_category_data && isset( $faq_category_data->term_id ) && ! empty( $faq_category_data->term_id ) ) {
				$cat_query_args = array_merge(
					$faq_base_query,
					array(
						'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
							array(
								'taxonomy' => 'faq-category',
								'field'    => 'term_id',
								'terms'    => array( $faq_category_data->term_id ),
							),
						),
					)
				);

				$cat_query = new WP_Query( $cat_query_args );

				if ( $cat_query->have_posts() ) {
					$cat_key     = 'term_' . $faq_category_data->term_id;
					$tab_item_id = ( $tab_instance > 1 ) ? "tab_{$tab_instance}_$cat_key" : "tab_{$cat_key}";
					$faq_tabs[ $cat_key ] = array(
						'slug'        => $cat_key,
						'tab_item_id' => $tab_item_id,
						'title'       => $faq_category_data->name,
						'query_args'  => $cat_query_args,
						'query'       => $cat_query,
					);
				} else {
					unset( $faq_cat_ids[ $faq_cat_index ] );
				}
			}
		}

		if ( count( $faq_cat_ids ) > 1 ) {
			$all_tabs_data   = array();
			$all_query_args = array_merge(
				$faq_base_query,
				array(
					'tax_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => 'faq-category',
							'field'    => 'term_id',
							'terms'    => $faq_cat_ids,
						),
					),
				)
			);

			$all_query = new WP_Query( $all_query_args );

			if ( $all_query->have_posts() ) {
				$cat_key     = 'all';
				$tab_item_id = ( $tab_instance > 1 ) ? "tab_{$tab_instance}_$cat_key" : "tab_{$cat_key}";
				$all_tabs_data[ $cat_key ] = array(
					'slug'        => $cat_key,
					'tab_item_id' => $tab_item_id,
					'title'       => esc_html__( 'All', 'cardealer' ),
					'query_args'  => $all_query_args,
					'query'       => $all_query,
				);
			}
			$faq_tabs = array_merge( $all_tabs_data, $faq_tabs );
		}

		$faq_tabs_data['tabs_id']     = "cardealer-faq-tabs-{$tab_instance}";
		$faq_tabs_data['tab_counts' ] = count( $faq_cat_ids );
		$faq_tabs_data['faq_tabs' ]   = $faq_tabs;
	}

	return $faq_tabs_data;
}

/*
 * Hide admin bar when perameter passed.
 */
add_filter( 'show_admin_bar' , 'cardealer_function_admin_bar');
function cardealer_function_admin_bar( $show_admin_bar ) {

	if ( isset( $_GET['cardealer_popup_page'] ) && 'true' === $_GET['cardealer_popup_page'] ) {
		$show_admin_bar = false;
	}

	return $show_admin_bar;
}

/*
 * Set template for popup content.
 */
function cardealer_set_popup_template( $template ) {

	if ( isset( $_GET['cardealer_popup_page'] ) && 'true' === $_GET['cardealer_popup_page'] ) {
		$template = locate_template( 'templates/cardealer-without-header-footer.php', false );
	}

	return $template;
}
add_filter( 'template_include', 'cardealer_set_popup_template' );
