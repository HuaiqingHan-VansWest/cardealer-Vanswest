<?php
/**
 * Do not allow directly accessing this file.
 *
 * @package Cardealer
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Cardealer_Theme_Activation' ) ) {
	/**
	 * Cardelaer Theme activation class
	 */
	class Cardealer_Theme_Activation {
		/**
		 * Instance variable for the file
		 *
		 * @var bool $_instance bolean variable
		 */
		public static $_instance = null;
		/**
		 * Constructor
		 */
		public function __construct() {
			do_action( 'cardealer_theme_class_loaded' );
			$this->init();
		}
		/**
		 * Initialization function
		 */
		public static function init() {
			add_action( 'init', array( __CLASS__, 'cardealer_activate_theme' ) );
		}
		/**
		 * Instance function
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		/**
		 * Set theme credentials
		 */
		public static function cardealer_set_theme_credentials() {
			if ( isset( $_POST['cardealer_purchase_code'] ) && isset( $_POST['purchase_code_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['purchase_code_nonce'] ) ), 'cardealer-verify-token' ) ) {
				// If empty key supplied.
				if ( empty( $_POST['cardealer_purchase_code'] ) ) {
					delete_option( 'cardealer_pgs_token' );  // update pgs_token.
					delete_site_transient( 'cardealer_auth_msg' );
					delete_option( 'cardealer_theme_purchase_key' ); // update purchase_key.
					return;
				} else {
					$product_purchase_key = sanitize_text_field( wp_unslash( $_POST['cardealer_purchase_code'] ) );
					$args                 = array(
						'product_key'  => PGS_PRODUCT_KEY,
						'purchase_key' => $product_purchase_key,
						'site_url'     => get_site_url(),
						'action'       => 'register',
					);

					$url      = add_query_arg( $args, trailingslashit( PGS_ENVATO_API ) . 'verifyproduct' );
					$response = wp_remote_get( $url, array( 'timeout' => 2000 ) );
					if ( is_wp_error( $response ) ) {
						$error_message = $response->get_error_message();
						set_site_transient(
							'cardealer_auth_notice',
							sprintf(
								/* translators: %s: Error message */
								esc_html__( 'There was an error processing your request, please try again later. Error: %s', 'cardealer' ),
								esc_html( $error_message )
							)
						);
						delete_site_transient( 'cardealer_auth_msg' );
						return false;
					}

					$response_code = wp_remote_retrieve_response_code( $response );
					$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( '200' === (string) $response_code ) {
						if ( 1 === $response_body['status'] ) {
							set_site_transient( 'cardealer_auth_msg', $response_body['message'] );
							delete_site_transient( 'cardealer_auth_notice' );
							update_option( 'cardealer_pgs_token', $response_body['pgs_token'] );
							update_option( 'cardealer_theme_purchase_key', $product_purchase_key );
							return $response_body['pgs_token'];
						}
						if ( 429 !== $response_body['status'] ) {
							delete_option( 'cardealer_pgs_token' );
							update_option( 'cardealer_theme_purchase_key', $product_purchase_key );
						}
						set_site_transient( 'cardealer_auth_notice', $response_body['message'] );
						delete_site_transient( 'cardealer_auth_msg' );
						return false;
					} elseif ( '403' === (string) $response_code ) {
						delete_option( 'cardealer_pgs_token' );
						update_option( 'cardealer_theme_purchase_key', $product_purchase_key );
						set_site_transient( 'cardealer_auth_notice', $response_body['message'] );
						delete_site_transient( 'cardealer_auth_msg' );
						return false;
					} else {
						delete_site_transient( 'cardealer_auth_msg' );
						set_site_transient( 'cardealer_auth_notice', $response_body['message'] );
						return false;
					}
				}
			}
		}
		/**
		 * Activate Theme
		 */
		public static function cardealer_activate_theme() {
			if ( isset( $_POST['cardealer_purchase_code'] ) && isset( $_POST['purchase_code_nonce'] ) ) {
				$notices = array();

				// verify nonce.
				if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['purchase_code_nonce'] ) ), 'cardealer-verify-token' ) ) {
					$cardealer_purchase_code = sanitize_text_field( wp_unslash( $_POST['cardealer_purchase_code'] ) ); // Sanitize purchase code.

					// Compare old stored purchase code with newly submitted.
					$cardealer_purchase_code_old     = get_option( 'cardealer_theme_purchase_key' );
					$cardealer_site_url_key_old      = get_option( 'cardealer_site_url_key' );
					$cardealer_purchase_token_old    = get_option( 'cardealer_pgs_token' );
					$cardealer_purchase_code_notices = get_option( 'cardealer_purchase_code_notices' );

					delete_option( 'cardealer_purchase_code_notices' );

					$cardealer_site_url     = get_site_url();
					$cardealer_site_url_key = md5( $cardealer_site_url );

					// If empty purchase code provided.
					if ( empty( $cardealer_purchase_code ) ) {
						delete_option( 'cardealer_theme_purchase_key' );
						delete_option( 'cardealer_pgs_token' );
						if ( $cardealer_purchase_code_old && ! empty( $cardealer_purchase_code_old ) ) {
							$notices = array(
								'notice_type' => 'warning',
								'notice'      => esc_html__( 'Purchase code removed.', 'cardealer' ),
							);
						} else {
							$notices = array(
								'notice_type' => 'warning',
								'notice'      => esc_html__( 'Please enter purchase code.', 'cardealer' ),
							);
						}
					} else {

						// Prevalidate purchase code structure.
						if ( true !== self::cardealer_prevalidate_purchase_code_cppc( $cardealer_purchase_code ) ) {
							delete_option( 'cardealer_theme_purchase_key' );
							delete_option( 'cardealer_pgs_token' );
							$notices = array(
								'notice_type' => 'error',
								'notice'      => esc_html__( 'Please enter a valid purchase code.', 'cardealer' ),
							);
						} else {
							// Check if same key is submitted from same site, and purchase token is already set.
							if ( ( $cardealer_purchase_code_old === $cardealer_purchase_code ) // check old code and new code are same.
								&& ( $cardealer_site_url_key_old === $cardealer_site_url_key ) // check if old url is same as new one.
								&& false !== $cardealer_purchase_token_old
							) {
								$notices = array(
									'notice_type' => 'warning',
									'notice'      => esc_html__( 'Purchase code already activated on this website.', 'cardealer' ),
								);
							} else {
								delete_option( 'cardealer_theme_purchase_key' );
								delete_option( 'cardealer_pgs_token' );

								$template   = get_template();
								$theme_info = wp_get_theme( $template );
								$theme_name = $theme_info['Name'];

								$args = array(
									'product_key'  => PGS_PRODUCT_KEY,
									'purchase_key' => $cardealer_purchase_code,
									'site_url'     => get_site_url(),
									'action'       => 'register',
								);

								$url      = add_query_arg( $args, trailingslashit( PGS_ENVATO_API ) . 'verifyproduct' );
								$response = wp_remote_get( $url, array( 'timeout' => 2000 ) );
								if ( is_wp_error( $response ) ) {
									$error_message = $response->get_error_message();
									$notices       = array(
										'notice_type' => 'error',
										'notice'      => sprintf(
											/* translators: %s: Error message */
											esc_html__( 'There was an error processing your request, please try again later. Error: %s', 'cardealer' ),
											esc_html( $error_message )
										),
									);
								} else {
									$response_code = wp_remote_retrieve_response_code( $response );
									$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

									if ( '200' === (string) $response_code ) {
										if ( 1 === $response_body['status'] ) {
											update_option( 'cardealer_theme_purchase_key', $cardealer_purchase_code );
											update_option( 'cardealer_pgs_token', $response_body['pgs_token'] );
											update_option( 'cardealer_site_url_key', $cardealer_site_url_key );
											$notices = array(
												'notice_type' => 'success',
												'notice' => $response_body['message'],
											);
										} else {
											$notices = array(
												'notice_type' => 'error',
												'notice' => $response_body['message'],
											);
										}
									} else {
										delete_option( 'cardealer_pgs_token' ); // update pgs_token.
										$notices = array(
											'notice_type' => 'warning',
											'notice'      => $response_body['message'],
										);
									}
								}
							}
						}
					}
				} else {
					$notices = array(
						'notice_type' => 'error',
						'notice'      => esc_html__( 'Unable to verify security check. Please try to reload the page.', 'cardealer' ),
					);
				}
				update_option( 'cardealer_purchase_code_notices', $notices );
			}
		}
		/**
		 * Cardealer prevalidate purchase code
		 *
		 * @see cardealer_prevalidate_purchase_code_cppc()
		 *
		 * @param string $purchase_code is string variable.
		 */
		public static function cardealer_prevalidate_purchase_code_cppc( $purchase_code ) {
			if ( $purchase_code ) {
				$purchase_code = preg_replace( '#([a-z0-9]{8})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{12})#', '$1-$2-$3-$4-$5', strtolower( $purchase_code ) );
				if ( 36 === strlen( $purchase_code ) ) {
					return true;
				}
			}
			return false;
		}
		/**
		 * Cardealer verify theme
		 */
		public static function cardealer_verify_theme() {
			$pgs_token = get_option( 'cardealer_pgs_token' );
			if ( $pgs_token && ! empty( $pgs_token ) ) {
				return $pgs_token;
			}
			return false;
		}
	}
}
Cardealer_Theme_Activation::instance();

