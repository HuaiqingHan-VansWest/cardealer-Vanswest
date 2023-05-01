<?php
/**
 * Do not allow directly accessing this file.
 *
 * @package Cardealer
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealer_megamenu_add_theme_cardealer' ) ) {
	/**
	 * Megamenu add theme colors
	 *
	 * @see cardealer_megamenu_add_theme_cardealer()
	 *
	 * @param array $themes array of colors.
	 *
	 * @return array $themes array of colors.
	 */
	function cardealer_megamenu_add_theme_cardealer( $themes ) {
		$themes['default'] = array(
			'title'                                    => 'Default',
			'container_background_from'                => 'rgba(0, 0, 0, 0)',
			'container_background_to'                  => 'rgba(0, 0, 0, 0)',
			'arrow_up'                                 => 'dash-f343',
			'arrow_down'                               => 'dash-f347',
			'arrow_left'                               => 'dash-f341',
			'arrow_right'                              => 'dash-f345',
			'menu_item_align'                          => 'right',
			'menu_item_background_from'                => 'rgba(254, 0, 0, 0)',
			'menu_item_background_to'                  => 'rgba(254, 0, 0, 0)',
			'menu_item_background_hover_from'          => 'rgba(0, 0, 0, 0)',
			'menu_item_background_hover_to'            => 'rgba(0, 0, 0, 0)',
			'menu_item_link_font_size'                 => '13px',
			'menu_item_link_height'                    => 'inherit',
			'menu_item_link_color'                     => 'rgb(255, 255, 255)',
			'menu_item_link_text_transform'            => 'uppercase',
			'menu_item_border_color'                   => 'rgb(255, 255, 255)',
			'menu_item_highlight_current'              => 'off',
			'panel_background_from'                    => '#ffffff',
			'panel_background_to'                      => '#ffffff',
			'panel_border_color'                       => 'rgb(219, 45, 46)',
			'panel_border_top'                         => '5px',
			'panel_header_text_transform'              => 'none',
			'panel_header_font_size'                   => '14px',
			'panel_header_font_weight'                 => 'normal',
			'panel_header_border_color'                => 'rgb(255, 255, 255)',
			'panel_padding_left'                       => '10px',
			'panel_padding_right'                      => '10px',
			'panel_padding_top'                        => '10px',
			'panel_padding_bottom'                     => '10px',
			'panel_widget_padding_left'                => '10px',
			'panel_widget_padding_right'               => '10px',
			'panel_widget_padding_top'                 => '10px',
			'panel_widget_padding_bottom'              => '10px',
			'panel_font_size'                          => '14px',
			'panel_font_color'                         => 'rgb(85, 85, 85)',
			'panel_font_family'                        => 'inherit',
			'panel_second_level_font_color'            => '#555',
			'panel_second_level_font_color_hover'      => 'rgb(219, 45, 46)',
			'panel_second_level_text_transform'        => 'none',
			'panel_second_level_font'                  => 'inherit',
			'panel_second_level_font_size'             => '14px',
			'panel_second_level_font_weight'           => 'normal',
			'panel_second_level_font_weight_hover'     => 'normal',
			'panel_second_level_text_decoration'       => 'none',
			'panel_second_level_text_decoration_hover' => 'none',
			'panel_second_level_border_color'          => 'rgb(255, 255, 255)',
			'panel_third_level_font_color'             => '#666',
			'panel_third_level_font_color_hover'       => 'rgb(219, 45, 46)',
			'panel_third_level_font'                   => 'inherit',
			'panel_third_level_font_size'              => '14px',
			'flyout_width'                             => '200px',
			'flyout_menu_background_from'              => '#ffffff',
			'flyout_menu_background_to'                => '#ffffff',
			'flyout_border_color'                      => 'rgb(219, 45, 46)',
			'flyout_border_top'                        => '5px',
			'flyout_menu_item_divider'                 => 'on',
			'flyout_menu_item_divider_color'           => '#ededed',
			'flyout_link_padding_left'                 => '12px',
			'flyout_link_padding_right'                => '12px',
			'flyout_link_padding_top'                  => '7px',
			'flyout_link_padding_bottom'               => '7px',
			'flyout_link_weight_hover'                 => 'inherit',
			'flyout_link_height'                       => '24px',
			'flyout_background_from'                   => '#ffffff',
			'flyout_background_to'                     => '#ffffff',
			'flyout_background_hover_from'             => 'rgb(246, 246, 246)',
			'flyout_background_hover_to'               => 'rgb(246, 246, 246)',
			'flyout_link_size'                         => '13px',
			'flyout_link_color'                        => '#333333',
			'flyout_link_color_hover'                  => 'rgb(219, 45, 46)',
			'flyout_link_family'                       => 'inherit',
			'responsive_breakpoint'                    => '992px',
			'shadow'                                   => 'on',
			'shadow_blur'                              => '0px',
			'shadow_spread'                            => '1px',
			'shadow_color'                             => 'rgba(0, 0, 0, 0.06)',
			'transitions'                              => 'on',
			'mobile_columns'                           => '1',
			'toggle_background_from'                   => 'rgba(0, 0, 0, 0)',
			'toggle_background_to'                     => 'rgba(0, 0, 0, 0)',
			'toggle_font_color'                        => '#ffffff',
			'mobile_background_from'                   => 'rgb(51, 51, 51)',
			'mobile_background_to'                     => 'rgb(51, 51, 51)',
			'mobile_menu_item_link_font_size'          => '13px',
			'mobile_menu_item_link_color'              => 'rgb(255, 255, 255)',
			'mobile_menu_item_link_text_align'         => 'left',
			'custom_css'                               => '/** Push menu onto new line **/
    #{$wrap} {
        clear: both;
    }',
		);
		return $themes;
	}
}
add_filter( 'megamenu_themes', 'cardealer_megamenu_add_theme_cardealer' );

if ( ! function_exists( 'cardealer_megamenu_override_default_theme' ) ) {
	/**
	 * Mega Menu Override default theme
	 *
	 * @see cardealer_megamenu_override_default_theme()
	 *
	 * @param array $value array of theme menu.
	 *
	 * @return array $value array of theme menu.
	 */
	function cardealer_megamenu_override_default_theme( $value ) {
		/* change 'primary' to your menu location ID */
		$value['primary-menu']['theme'] = 'default'; /* change my_custom_theme_key to the ID of your exported theme */
		return $value;
	}
}
add_filter( 'default_option_megamenu_settings', 'cardealer_megamenu_override_default_theme' );
define( 'MEGAMENU_DEBUG', true );
