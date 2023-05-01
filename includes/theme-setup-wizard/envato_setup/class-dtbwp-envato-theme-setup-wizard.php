<?php
/**
 * Envato_Theme_Setup_Wizard init class.
 *
 * This is the setup wizard init file.
 * This file changes for each one of dtbaker's themes
 * This is where I extend the default 'Envato_Theme_Setup_Wizard' class and can do things like remove steps from the setup process.
 *
 * This particular init file has a custom "Update" step that is triggered on a theme update. If the setup wizard finds some old shortcodes after a theme update then it will go through the content and replace them. Probably remove this from your end product.
 *
 * @author      dtbaker, vburlak
 * @package     envato_wizard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_filter( 'envato_setup_logo_image', 'dtbwp_envato_setup_logo_image' );
/**
 * Function dtbwp_envato_setup_logo_image.
 *
 * @param string $old_image_url  Logo image URL.
 */
function dtbwp_envato_setup_logo_image( $old_image_url ) {
	return get_parent_theme_file_uri( '/images/default/logo.png' );
}

if ( ! function_exists( 'envato_theme_setup_wizard' ) ) :

	/**
	 * Function envato_theme_setup_wizard.
	 */
	function envato_theme_setup_wizard() {

		if ( class_exists( 'Envato_Theme_Setup_Wizard' ) ) {

			/**
			 * DTBWP_Envato_Theme_Setup_Wizard class.
			 */
			class DTBWP_Envato_Theme_Setup_Wizard extends Envato_Theme_Setup_Wizard {

				/**
				 * Holds the current instance of the theme manager
				 *
				 * @since 1.1.3
				 * @var Envato_Theme_Setup_Wizard
				 */
				private static $instance = null;

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
				 * Function init_actions.
				 */
				public function init_actions() {
					if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' ) ) {
						add_filter(
							$this->theme_name . '_theme_setup_wizard_content',
							array(
								$this,
								'theme_setup_wizard_content',
							)
						);
						add_filter(
							$this->theme_name . '_theme_setup_wizard_steps',
							array(
								$this,
								'theme_setup_wizard_steps',
							)
						);
					}
					parent::init_actions();
				}

				/**
				 * Function theme_setup_wizard_steps.
				 *
				 * @param array $steps  Array of steps.
				 */
				public function theme_setup_wizard_steps( $steps ) {
					return $steps;
				}

				/**
				 * Function theme_setup_wizard_content.
				 *
				 * @param mixed $content  Mixed content.
				 */
				public function theme_setup_wizard_content( $content ) {
					if ( $this->is_possible_upgrade() ) {
						cardealer_array_unshift_assoc(
							$content,
							'upgrade',
							array(
								'title'            => __( 'Upgrade', 'cardealer' ),
								'description'      => __( 'Upgrade Content and Settings', 'cardealer' ),
								'pending'          => __( 'Pending.', 'cardealer' ),
								'installing'       => __( 'Installing Updates.', 'cardealer' ),
								'success'          => __( 'Success.', 'cardealer' ),
								'install_callback' => array( $this, 'content_install_updates' ),
								'checked'          => 1,
							)
						);
					}
					return $content;
				}

				/**
				 * Function get_default_theme_style.
				 */
				public function get_default_theme_style() {
					return false;
				}

				/**
				 * Function is_possible_upgrade.
				 */
				public function is_possible_upgrade() {
					$widget = get_option( 'widget_text' );
					if ( is_array( $widget ) ) {
						foreach ( $widget as $item ) {
							if ( isset( $item['dtbwp_widget_bg'] ) ) {
								return true;
							}
						}
					}
					// check if shop page is already installed?
					$shoppage = cardealer_get_page_by_title( 'Shop' );
					if ( $shoppage || get_option( 'page_on_front', false ) ) {
						return true;
					}

					return false;
				}

				/**
				 * Function content_install_updates.
				 */
				public function content_install_updates() {

					$widget = get_option( 'widget_text' );
					if ( is_array( $widget ) ) {
						foreach ( $widget as $key => $val ) {
							if ( ! empty( $val['text'] ) ) {
								$widget[ $key ]['text'] = str_replace( '[dtbaker_icon icon="truck"]', '<div class="dtbaker-icon-truck"></div>', $val['text'] );
							}
						}
						update_option( 'widget_text', $widget );
					}

					return true;

				}

			}

			dtbwp_Envato_Theme_Setup_Wizard::get_instance();
		}
	}
endif;
