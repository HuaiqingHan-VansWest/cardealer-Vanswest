<?php
/**
 * Car attribute.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package CarDealer
 */

/**
 * Core attributes static data.
 */
function cardealer_core_attributes_static_data() {
	$attributes = array(
		'car_year'             => array(
			'taxonomy'      => 'car_year',
			'slug'          => 'year',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Year', 'cardealer' ),
			'plural_name'   => esc_html__( 'Years', 'cardealer' ),
			'args'          => array(
				'rewrite' => array(
					'slug' => 'year',
				),
			),
		),
		'car_make'             => array(
			'taxonomy'      => 'car_make',
			'slug'          => 'make',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Make', 'cardealer' ),
			'plural_name'   => esc_html__( 'Makes', 'cardealer' ),
			'args'          => array(
				'show_in_nav_menus' => true,
			),
		),
		'car_model'            => array(
			'taxonomy'      => 'car_model',
			'slug'          => 'model',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Model', 'cardealer' ),
			'plural_name'   => esc_html__( 'Models', 'cardealer' ),
		),
		'car_body_style'       => array(
			'taxonomy'      => 'car_body_style',
			'slug'          => 'body-style',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Body Style', 'cardealer' ),
			'plural_name'   => esc_html__( 'Body Styles', 'cardealer' ),
			'args'          => array(
				'show_in_nav_menus' => true,
			),
		),
		'car_mileage'          => array(
			'taxonomy'      => 'car_mileage',
			'slug'          => 'mileage',
			'singular_name' => esc_html__( 'Mileage', 'cardealer' ),
			'plural_name'   => esc_html__( 'Mileages', 'cardealer' ),
		),
		'car_transmission'     => array(
			'taxonomy'      => 'car_transmission',
			'slug'          => 'transmission',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Transmission', 'cardealer' ),
			'plural_name'   => esc_html__( 'Transmissions', 'cardealer' ),
		),
		'car_condition'        => array(
			'taxonomy'      => 'car_condition',
			'slug'          => 'condition',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Condition', 'cardealer' ),
			'plural_name'   => esc_html__( 'Conditions', 'cardealer' ),
			'args'          => array(
				'show_in_nav_menus' => true,
			),
		),
		'car_drivetrain'       => array(
			'taxonomy'      => 'car_drivetrain',
			'slug'          => 'drivetrain',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Drivetrain', 'cardealer' ),
			'plural_name'   => esc_html__( 'Drivetrains', 'cardealer' ),
		),
		'car_engine'           => array(
			'taxonomy'      => 'car_engine',
			'slug'          => 'engine',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Engine', 'cardealer' ),
			'plural_name'   => esc_html__( 'Engines', 'cardealer' ),
		),
		'car_fuel_economy'     => array(
			'taxonomy'      => 'car_fuel_economy',
			'slug'          => 'fuel-economy',
			'singular_name' => esc_html__( 'Fuel Economy', 'cardealer' ),
			'plural_name'   => esc_html__( 'Fuel Economies', 'cardealer' ),
		),
		'car_exterior_color'   => array(
			'taxonomy'      => 'car_exterior_color',
			'slug'          => 'exterior-color',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Exterior Color', 'cardealer' ),
			'plural_name'   => esc_html__( 'Exterior Colors', 'cardealer' ),
		),
		'car_interior_color'   => array(
			'taxonomy'      => 'car_interior_color',
			'slug'          => 'interior-color',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Interior Color', 'cardealer' ),
			'plural_name'   => esc_html__( 'Interior Colors', 'cardealer' ),
		),
		'car_stock_number'     => array(
			'taxonomy'      => 'car_stock_number',
			'slug'          => 'stock-number',
			'singular_name' => esc_html__( 'Stock Number', 'cardealer' ),
			'plural_name'   => esc_html__( 'Stock Numbers', 'cardealer' ),
		),
		'car_vin_number'       => array(
			'taxonomy'      => 'car_vin_number',
			'slug'          => 'vin-number',
			'singular_name' => esc_html__( 'VIN Number', 'cardealer' ),
			'plural_name'   => esc_html__( 'VIN Numbers', 'cardealer' ),
		),
		'car_fuel_type'        => array(
			'taxonomy'      => 'car_fuel_type',
			'slug'          => 'fuel-type',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Fuel Type', 'cardealer' ),
			'plural_name'   => esc_html__( 'Fuel Types', 'cardealer' ),
		),
		'car_trim'             => array(
			'taxonomy'      => 'car_trim',
			'slug'          => 'trim',
			'is_dropdown'   => 'no',
			'singular_name' => esc_html__( 'Trim', 'cardealer' ),
			'plural_name'   => esc_html__( 'Trims', 'cardealer' ),
		),
		'car_features_options' => array(
			'taxonomy'      => 'car_features_options',
			'slug'          => 'features-options',
			'singular_name' => esc_html__( 'Feature & Option', 'cardealer' ),
			'plural_name'   => esc_html__( 'Features & Options', 'cardealer' ),
			'args'          => array(
				'hierarchical'       => true,
				'show_in_quick_edit' => false,
			),
		),
	);
	return $attributes;
}

/**
 * Setup Core attributes.
 */
function cardealer_setup_core_attributes() {
	if ( ! get_option( 'cdhl_core_attributes' ) ) {
		$core_attributes_static = cardealer_core_attributes_static_data();
		$core_attributes_new    = array_map(
			function( $atts ) {
				$fields = array(
					'taxonomy'      => $atts['taxonomy'],
					'slug'          => $atts['slug'],
					'singular_name' => $atts['singular_name'],
					'plural_name'   => $atts['plural_name'],
				);

				if ( isset( $atts['is_dropdown'] ) && $atts['is_dropdown'] ) {
					$fields['is_dropdown'] = $atts['is_dropdown'];
				}

				return $fields;
			},
			$core_attributes_static
		);

		update_option( 'cdhl_core_attributes', $core_attributes_new );
	}
}
add_action( 'after_switch_theme', 'cardealer_setup_core_attributes' );
add_action( 'load-themes.php', 'cardealer_setup_core_attributes' );
add_action( 'after_setup_theme', 'cardealer_setup_core_attributes' );
