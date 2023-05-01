<?php
/**
 * Sidebars & Widgets Areas
 *
 * Two sidebars registered - left and right.
 * Define additional sidebars here.
 *
 * @package Cardealer
 *
 * @since Car Dealer 1.0
 */

/**
 * **********************************
 * 6.0 - SIDEBARS
 * **********************************
 */
function cardealer_register_sidebars() {
	global $car_dealer_options;
	/*sidebars*/
	register_sidebar(
		array(
			'name'          => esc_html__( 'Default Sidebar', 'cardealer' ),
			'id'            => 'sidebar-default',
			'description'   => esc_html__( 'Widgets in this area will be shown on all posts and pages.', 'cardealer' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6 class="widgettitle">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Left Sidebar', 'cardealer' ),
			'id'            => 'sidebar-left',
			'description'   => esc_html__( 'Widgets in this area will be shown on all posts and pages in left sidebar.', 'cardealer' ),
			'before_widget' => '<div id="%1$s" class="sidebar-widget widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6 class="widgettitle">',
			'after_title'   => '</h6>',
		)
	);

	/* Dealer Cars sidebar */
	register_sidebar(
		array(
			'name'          => esc_html__( 'Vehicles Listing Sidebar', 'cardealer' ),
			'id'            => 'listing-cars',
			'description'   => esc_html__( 'Add widgets here to appear on vehicle list page.', 'cardealer' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6 class="widgettitle">',
			'after_title'   => '</h6>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Vehicle Detail Sidebar', 'cardealer' ),
			'id'            => 'detail-cars',
			'description'   => esc_html__( 'Add widgets here to appear on vehicle details page.', 'cardealer' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6 class="widgettitle">',
			'after_title'   => '</h6>',
		)
	);

	/* footer sidebar */
	global $car_dealer_options;
	$footer_cols = 1;

	$footer_column_layout = 'four-columns';

	if ( empty( ! $car_dealer_options ) && isset( $car_dealer_options['footer_column_layout'] ) && ! empty( $car_dealer_options['footer_column_layout'] ) ) {
		$footer_column_layout = $car_dealer_options['footer_column_layout'];
	} elseif ( class_exists( 'Redux' ) ) {
		$footer_column_layout = Redux::get_option( 'car_dealer_options', 'footer_column_layout', 'four-columns' );
	}

	if ( isset( $footer_column_layout ) && $footer_column_layout ) {
		switch ( $footer_column_layout ) {
			case 'one-column':
				$footer_cols = 1;
				break;
			case 'two-columns':
			case '8-4-columns':
			case '4-8-columns':
				$footer_cols = 2;
				break;
			case 'three-columns':
			case '6-3-3-columns':
			case '3-3-6-columns':
			case '8-2-2-columns':
			case '2-2-8-columns':
				$footer_cols = 3;
				break;
			case 'four-columns':
			case '6-2-2-2-columns':
			case '2-2-2-6-columns':
				$footer_cols = 4;
				break;
		}
	}

	for ( $col = 1; $col <= $footer_cols; $col++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer', 'cardealer' ) . $col,
				'id'            => 'sidebar-footer-' . $col,
				'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'cardealer' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h6 class="text-white widgettitle">',
				'after_title'   => '</h6>',
			)
		);
	}

	// Add sidebar Footer Bottom only if Footer Bottom enabled from theme options.
	$options = get_option( 'car_dealer_options' );
	if ( isset( $options['show_footer_bottom'] ) && 'yes' === $options['show_footer_bottom'] ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Bottom', 'cardealer' ),
				'id'            => 'sidebar-footer-5',
				'description'   => esc_html__( 'Add widgets here to appear in your bottom footer.', 'cardealer' ),
				'before_widget' => '<div id="%1$s" class="widget col-sm-6 %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h6 class="text-white widgettitle">',
				'after_title'   => '</h6>',
			)
		);
	}
}
