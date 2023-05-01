<?php
/**
 * Vehicle detail page sidebar hooks.
 */
add_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_trade_in_appraisal', 10 );
add_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_buy_online_btn', 20 );
add_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_review_stamps', 30 );
add_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_attributes', 40 );

/**
 * Vehicle detail page tabs hooks.
 */
// add_filter( 'cardealer_vehicle_tabs', 'cardealer_default_vehicle_tabs' );
add_filter( 'cardealer_default_vehicle_tabs', 'cardealer_default_vehicle_tabs' );
add_filter( 'cardealer_vehicle_tabs', 'cardealer_vehicle_tabs' );
add_filter( 'cardealer_vehicle_tabs', 'cardealer_sort_vehicle_tabs', 9999999 );
add_filter( 'cardealer_vehicle_is_tab_enabled', 'cardealer_vehicle_is_tab_enabled', 10, 3 );

add_filter( 'cardealer_vehicle_tabs_option', 'cardealer_vehicle_tabs_option' );

/**
 * Vehicle detail page sections hooks.
 */
add_filter( 'cardealer_vehicle_detail_page_section_enabled', 'cardealer_vehicle_detail_page_section_enabled', 10, 3 );

/**
 * Vehicle listing page hooks.
 */
add_action( 'cardealer/vehicle_listing/mobile/before_listing', 'cardealer_vehicle_listing_page_mobile_filters' );
add_action( 'cardealer_offcanvas', 'cardealer_widget_area__listing_cars' );

/**
 * Mobile item layout
 */
add_filter( 'cardealer_vehicle_list_view_type', 'cardealer_vehicle_list_mobile_view_type' );

add_filter( 'cdhl_vehicle_buttons_option', 'cardealer_extend_cdhl_vehicle_buttons_option' );
add_filter( 'cardealer_builtin_vehicle_button_enabled', 'cardealer_builtin_vehicle_button_enabled', 10, 3 );
add_action( 'wp_footer', 'cardealer_render_vehicle_button_contents' );

// Featured Listing
add_action( 'cardealer_before_vehicle_listing', 'cardealer_featured_vehicle_listing' );
add_action( 'car_before_overlay_banner', 'cardealer_featured_vehicle_badge', 30, 2 );
add_action( 'before_image_gallery_slider', 'cardealer_featured_vehicle_badge', 20 );
add_action( 'restrict_manage_posts', 'cardealer_cpt_cars_filter_featured', 10, 2 );
add_filter( 'parse_query', 'cardealer_cpt_cars_parse_filter_featured' );
add_filter( 'cardealer_grid_view_classes_list', 'cardealer_grid_view_featured_classes', 10, 2 );
