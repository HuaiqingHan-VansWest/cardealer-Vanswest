<?php
/**
 * Cardealer Panel file
 *
 * @package Cardealer
 * @version 1.0.0
 */

// required functions.
get_template_part( 'includes/admin/theme-panel/cardealer', 'panel-functions' );

add_action( 'admin_menu', 'cardealer_cardealer_menu' );
if ( ! function_exists( 'cardealer_cardealer_menu' ) ) {
	/**
	 * Cardealer Menu function
	 */
	function cardealer_cardealer_menu() {
		add_menu_page( esc_html__( 'Car Dealer', 'cardealer' ), esc_html__( 'Car Dealer', 'cardealer' ), 'manage_options', 'cardealer-panel', 'cardealer_get_support_data', CARDEALER_URL . '/images/menu-icon.png', 3 ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_menu_page

		if ( function_exists( 'cdhl_activate' ) && class_exists( 'redux' ) ) {
			add_submenu_page( 'cardealer-panel', esc_html__( 'Support', 'cardealer' ), esc_html__( 'Support', 'cardealer' ), 'manage_options', 'cardealer-panel', 'cardealer_get_support_data' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'Plugins', 'cardealer' ), esc_html__( 'Plugins', 'cardealer' ), 'manage_options', 'cardealer-plugins', 'cardealer_get_plugin_data' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'Install Demos', 'cardealer' ), esc_html__( 'Install Demos', 'cardealer' ), 'manage_options', 'admin.php?page=cardealer&cd_section=sample_data', '' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'Theme Options', 'cardealer' ), esc_html__( 'Theme Options', 'cardealer' ), 'manage_options', 'themes.php?page=cardealer', '' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'System Status', 'cardealer' ), esc_html__( 'System Status', 'cardealer' ), 'manage_options', 'cardealer-system-status', 'cardealer_get_system_status' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'Ratings', 'cardealer' ), esc_html__( 'Ratings', 'cardealer' ), 'manage_options', 'cardealer-ratings', 'cardealer_get_ratings_page' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'Third Party Testing', 'cardealer' ), esc_html__( 'Third Party Testing', 'cardealer' ), 'manage_options', 'cardealer-third-party-testing', 'cardealer_get_third_party_testing_page' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
			add_submenu_page( 'cardealer-panel', esc_html__( 'More Features', 'cardealer' ), esc_html__( 'More Features', 'cardealer' ), 'manage_options', 'cardealer-more-features', 'cardealer_get_more_features_page' ); // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
		}
	}
}
