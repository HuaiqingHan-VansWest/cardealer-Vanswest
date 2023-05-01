<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Theme base functions.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package CarDealer/Functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealer_get_next_posts_link_attributes' ) ) {
	/**
	 * Acf get attachment
	 *
	 * Add rel and title attribute to next pagination link.

	 * @param array $attr (array) the array to look within.
	 */
	function cardealer_get_next_posts_link_attributes( $attr ) {
		$attr = 'rel="next" title="' . esc_attr__( 'View the Next Page', 'cardealer' ) . '"';
		return $attr;
	}
}
add_filter( 'next_posts_link_attributes', 'cardealer_get_next_posts_link_attributes' );

if ( ! function_exists( 'cardealer_get_previous_posts_link_attributes' ) ) {
	/**
	 * Acf get attachment
	 *
	 * Add rel and title attribute to next pagination link.

	 * @param array $attr (array) the array to look within.
	 */
	function cardealer_get_previous_posts_link_attributes( $attr ) {
		$attr = 'rel="prev" title="' . esc_attr__( 'View the Previous Page', 'cardealer' ) . '"';
		return $attr;
	}
}
add_filter( 'previous_posts_link_attributes', 'cardealer_get_previous_posts_link_attributes' );

if ( ! function_exists( 'cardealer_custom_admin_footer' ) ) {
	/**
	 * Acf get attachment
	 *
	 * Custom Backend Footer.
	 */
	function cardealer_custom_admin_footer() {
		sprintf(
			wp_kses(
				__( '<span id="footer-thankyou">Developed by <a href="$1" target="_blank">TeamWP @Potenza Global Solutions</a></span>.', 'cardealer' ),
				array(
					'span' => array(),
					'a'    => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			),
			esc_url( 'http://www.potenzaglobalsolutions.com/' )
		);
	}
}
add_filter( 'admin_footer_text', 'cardealer_custom_admin_footer' );

if ( ! function_exists( 'cardealer_wp_list_pages_filter' ) ) {
	/**
	 * Add page title attribute to wp_list_pages link tags
	 *
	 * @param array $output (array) the array to look within.
	 * @since Car Dealer 1.0
	 */
	function cardealer_wp_list_pages_filter( $output ) {
		$output = preg_replace( '/<a(.*)href="([^"]*)"(.*)>(.*)<\/a>/', '<a$1 title="$4" href="$2"$3>$4</a>', $output );
		return $output;
	}
}
add_filter( 'wp_list_pages', 'cardealer_wp_list_pages_filter' );

/************************************
 * ADMIN CUSTOMIZATION
 * - Set content width
 * - Set image attachment width
 * - Disable default dashboard widgets
 * - Change name of "Posts" in admin menu
 *********************************** */

if ( ! function_exists( 'cardealer_content_width' ) ) {
	/**
	 * Adjust content_width value for image attachment template
	 *
	 * @since Car Dealer 1.0
	 */
	function cardealer_content_width() {
		if ( is_attachment() && wp_attachment_is_image() ) {
			$GLOBALS['content_width'] = 810;
		}
	}
}
add_action( 'template_redirect', 'cardealer_content_width' );

if ( ! function_exists( 'cardealer_body_classes' ) ) {
	/**
	 * Adjust content_width value for image attachment template
	 *
	 * @param array $classes (array) the array to look within.
	 */
	function cardealer_body_classes( $classes ) {
		global $post, $car_dealer_options;

		/* Sidebar Classes */
		if ( is_front_page() || is_single() ) {
			$cardealer_blog_sidebar = isset( $car_dealer_options['blog_sidebar'] ) ? $car_dealer_options['blog_sidebar'] : '';
			$classes[]              = "sidebar-$cardealer_blog_sidebar";
		} elseif ( is_page() ) {
			$cardealer_page_sidebar = isset( $car_dealer_options['page_sidebar'] ) ? $car_dealer_options['page_sidebar'] : '';

			/* Page sidebar set inside page */
			$page_layout_custom = get_post_meta( $post->ID, 'page_layout_custom', true );
			if ( $page_layout_custom ) {
				$page_sidebar = get_post_meta( $post->ID, 'page_sidebar', true );
				if ( $page_sidebar ) {
					$cardealer_page_sidebar = $page_sidebar;
				}
			}

			$classes[] = "sidebar-$cardealer_page_sidebar";
		}
		if ( cardealer_is_vc_enabled() ) {
			$classes[] = 'is_vc_enabled';
		} else {
			$classes[] = 'is_vc_disabled';
		}

		if ( $post && 'cars' === $post->post_type && is_single( $post ) ) {
			$vehicle_detail_page_layout = cardear_get_vehicle_detail_page_layout();
			if ( wp_is_mobile() ) {
				$vehicle_detail_page_layout = 'mobile';
			}
			$classes[] = "single-cars-layout-$vehicle_detail_page_layout";
		}

		if ( is_post_type_archive( 'cars' ) ) {
			$layout = cardealer_get_cars_list_layout_style();
			$classes[] = 'cd-vehicle-layouts-' . $layout;
		}

		return $classes;
	}
}
add_filter( 'body_class', 'cardealer_body_classes' );

if ( ! function_exists( 'cardealer_get_site_logo' ) ) {
	/**
	 * Site logo settings.
	 */
	function cardealer_get_site_logo() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['site-logo'] ) && isset( $car_dealer_options['site-logo']['url'] ) ) {
			return $car_dealer_options['site-logo']['url'];
		} else {
			return false;
		}
	}
}

add_action( 'wp_ajax_cardealer_debug_send_mail', 'cardealer_debug_send_mail' );
add_action( 'wp_ajax_nopriv_cardealer_debug_send_mail', 'cardealer_debug_send_mail' );
/**
 * Debug Send mail
 */
function cardealer_debug_send_mail() {
	$response_array = array();
	$nonce          = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'pgs_mail_debug_nonce' ) ) {
		$sitename = wp_parse_url( network_home_url(), PHP_URL_HOST );
		if ( 'www.' === substr( $sitename, 0, 4 ) ) {
			$sitename = substr( $sitename, 4 );
		}
		$from_email = 'wordpress@' . $sitename;

		$from_email = isset( $_POST['from_email'] ) ? sanitize_email( wp_unslash( $_POST['from_email'] ) ) : $from_email;
		$to_mail    = isset( $_POST['to_email'] ) ? sanitize_email( wp_unslash( $_POST['to_email'] ) ) : get_option( 'admin_email' );
		$subject    = esc_html__( 'Testing Mail For Cardealer', 'cardealer' );
		$message    = esc_html__( 'Theme mail is working properly.', 'cardealer' );

		$headers[] = 'From: ' . $from_email . ' <' . $from_email . '>';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'Reply-To: ' . wp_unslash( $from_email ) . '\r\n';

		$result = wp_mail( $to_mail, $subject, $message, $headers );
		if ( ! $result ) {
			global $ts_mail_errors;
			global $phpmailer;
			if ( ! isset( $ts_mail_errors ) ) {
				$ts_mail_errors = array();
			}
			if ( isset( $phpmailer ) ) {
				$response_array['status'] = false;
				$response_array['msg']    = sprintf(
					wp_kses(
						/* translators: 1: URL */
						__( 'There is an error while sending the email. Here is the <strong>PHPMailer Debug error:</strong><br><p>%s</p>', 'cardealer' ),
						array(
							'p'      => array(),
							'strong' => array(),
							'br'     => array(),
						)
					),
					$phpmailer->ErrorInfo // phpcs:ignore WordPress.NamingConventions.ValidVariableName
				);
			}
		} else {
			$response_array['status'] = true;
			$response_array['msg']    = esc_html__( 'Email sent successfully.', 'cardealer' );
		}
	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );

	exit();
}

add_action( 'wp_ajax_cardealer_debug_vinquery', 'cardealer_debug_vinquery' );
add_action( 'wp_ajax_nopriv_cardealer_debug_vinquery', 'cardealer_debug_vinquery' );
/**
 * Debug Vinquery
 */
function cardealer_debug_vinquery() {
	global $car_dealer_options;

	$response_array = array();
	$nonce          = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	$vinnumber      = isset( $_POST['vinnumber'] ) ? sanitize_text_field( wp_unslash( $_POST['vinnumber'] ) ) : '3GCPCREC7FG000000';

	if ( wp_verify_nonce( $nonce, 'pgs_vinquery_debug_nonce' ) ) {
		$vinquery_api_key = isset( $car_dealer_options['vinquery_api_key'] ) ? $car_dealer_options['vinquery_api_key'] : '';
		if ( ! $vinquery_api_key ) {
			$response_array['status'] = false;
			$response_array['msg']    = sprintf(
				wp_kses(
					/* translators: %s: VINquery URL */
					__( 'The <strong>VINquery API Key</strong> field is empty. Please add <strong>VINquery API Key</strong> in <a href="%s" target="_blank">Theme Options</a>.', 'cardealer' ),
					array(
						'strong' => array(),
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
					)
				),
				admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'vinquery_vin_settings' ) )
			);
		} else {
			if ( $vinnumber ) {
				if ( class_exists( 'CDVQI' ) ) {
					$cdvqi         = new CDVQI();
					$responce_body = $cdvqi->cdvi_get_vinquery_data( $vinnumber );
					if ( isset( $responce_body['Status'] ) ) {
						if ( 'FAILED' === $responce_body['Status'] ) {
							$response_array['status'] = false;
							$response_array['msg']    = wp_kses_post( $responce_body['Message'] )
							. '<br>'
							. sprintf(
								wp_kses(
									/* translators: %s: documentation URL */
									__( 'Please refer to this <a href="%s" target="_blank">documentation</a> for more details.', 'cardealer' ),
									array(
										'strong' => array(),
										'a'      => array(
											'href'   => true,
											'target' => true,
										),
									)
								),
								'https://vinquery.com/docs-vindecoding'
							);
						} elseif ( 'SUCCESS' === $responce_body['Status'] ) {
							$response_array['status'] = true;
							$response_array['msg']    = wp_kses(
								__( '<strong>VINquery VIN Import</strong> is working fine.', 'cardealer' ),
								array(
									'strong' => array(),
								)
							);
						}
					} else {
						$response_array['status'] = false;
						$response_array['msg']    = esc_html__( 'Something went wrong. Unable to get request response.', 'cardealer' );
					}
				} else {
					$response_array['status'] = false;
					$response_array['msg']    = wp_kses(
						__( 'Make sure that the <strong>Car Dealer - VINquery Import</strong> plugin is activated.', 'cardealer' ),
						array(
							'strong' => array(),
						)
					);
				}
			} else {
				$response_array['status'] = false;
				$response_array['msg']    = esc_html__( 'VIN is misssing.', 'cardealer' );
			}
		}
	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );

	exit();
}

add_action( 'wp_ajax_cardealer_debug_mailchimp', 'cardealer_debug_mailchimp' );
add_action( 'wp_ajax_nopriv_cardealer_debug_mailchimp', 'cardealer_debug_mailchimp' );
/**
 * Debug Mailchimp
 */
function cardealer_debug_mailchimp() {
	global $car_dealer_options;

	$response_array    = array();
	$mailchimp_api_key = isset( $car_dealer_options['mailchimp_api_key'] ) ? $car_dealer_options['mailchimp_api_key'] : '';
	$mailchimp_list_id = isset( $car_dealer_options['mailchimp_list_id'] ) ? $car_dealer_options['mailchimp_list_id'] : '';

	$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'pgs_mailchimp_debug_nonce' ) ) {
		if ( ! $mailchimp_list_id || ! $mailchimp_api_key ) {
			$response_array['status'] = false;
			$response_array['msg']    = sprintf(
				wp_kses(
					/* translators: %s: Theme option URL */
					__( 'There is an error. It seems like the Mailchimp API key or List-ID is empty, please add the value in <a href="%s" target="_blank">Theme Options</a>.', 'cardealer' ),
					array(
						'strong' => array(),
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
					)
				),
				admin_url( 'themes.php?page=cardealer&tab=' . cardealer_get_redux_tab_id( 'mailchimp_settings_section' ) )
			);
		} else {
			$dc = 'us1';
			if ( strstr( $mailchimp_api_key, '-' ) ) {
				list( $key, $dc ) = explode( '-', $mailchimp_api_key, 2 );
				if ( ! $dc ) {
					$dc = 'us1';
				}
			}

			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, 'https://' . $dc . '.api.mailchimp.com/3.0/lists/' . $mailchimp_list_id );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_USERPWD, 'anystring:' . $mailchimp_api_key );
			$response = curl_exec( $ch );
			if ( curl_errno( $ch ) ) {
				$response_array['status'] = false;
				$response_array['msg']    = sprintf(
					wp_kses(
						/* translators: %s: Error Response */
						__( 'There is an error, Please check the response for more details.<br><strong>Respose error:</strong><p>%s</p>', 'cardealer' ),
						array(
							'p'      => array(),
							'strong' => array(),
							'br'     => array(),
						)
					),
					curl_error( $ch )
				);
			} else {
				$data = json_decode( $response, true );
				if ( isset( $data['id'] ) && $mailchimp_list_id === $data['id'] ) {
					$response_array['status'] = true;
					$response_array['msg']    = esc_html__( 'Theme Mailchimp API is working perfectly fine.', 'cardealer' );
				} else {
					$response_array['status'] = false;
					if ( isset( $data['status'] ) && isset( $data['detail'] ) ) {
						$response_array['msg'] = sprintf(
							wp_kses(
								/* translators:  %1$s: Erro status %2$s: Erro detail */
								__( 'There is an error, Please check the response for more details.<br><strong>Respose error:</strong><p>%1$s : %2$s</p>', 'cardealer' ),
								array(
									'p'      => array(),
									'strong' => array(),
									'br'     => array(),
								)
							),
							$data['status'],
							$data['detail']
						);
					} else {
						$response_array['msg'] = esc_html__( 'Something went wrong, API request not getting proper response.', 'cardealer' );
					}
				}
			}
			curl_close( $ch );
		}
	} else {
		$response_array['status'] = false;
		$response_array['msg']    = esc_html__( 'You are not allowed to access this section.', 'cardealer' );
	}

	echo wp_json_encode( $response_array );
	exit();
}

if ( ! function_exists( 'cardealer_get_redux_tab_id' ) ) {
	/**
	 * Get the redux tab id.
	 *
	 * @param string $tab_key tab key.
	 */
	function cardealer_get_redux_tab_id( $tab_key ) {
		global $opt_name;

		if ( class_exists( 'Redux' ) ) {
			$all_fields = Redux::get_sections( $opt_name );
			if ( is_array( $all_fields ) ) {
				foreach ( $all_fields as $option_key => $option_value ) {
					if ( isset( $option_value['id'] ) && $option_value['id'] === $tab_key ) {
						return $option_value['priority'];
					}
				}
			} else {
				return false;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'cardealer_get_site_sticky_logo' ) ) {
	/**
	 * Site sticky logo settings.
	 */
	function cardealer_get_site_sticky_logo() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['site-sticky-logo'] ) && isset( $car_dealer_options['site-sticky-logo']['url'] ) ) {
			return $car_dealer_options['site-sticky-logo']['url'];
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'cardealer_display_loader' ) ) {
	/**
	 * Display loader.
	 */
	function cardealer_display_loader() {
		global $car_dealer_options;
		/* get the status of the side bar */
		$preloader = isset( $car_dealer_options['preloader'] ) ? $car_dealer_options['preloader'] : '';

		if ( isset( $_GET['cardealer_popup_page'] ) && 'true' === $_GET['cardealer_popup_page'] ) {
			$preloader = false;
		}

		if ( $preloader ) {
			$preloader_img = $car_dealer_options['preloader_img'];
			if ( isset( $car_dealer_options['preloader_html'] ) && 'code' === $preloader_img ) {
				if ( ! empty( $car_dealer_options['preloader_html'] ) ) {
					echo do_shortcode( $car_dealer_options['preloader_html'] );
				}
			} else {
				if ( 'pre_loader' === $preloader_img ) {
					$img_url = CARDEALER_URL . '/images/preloader_img/' . $car_dealer_options['predefined_loader_img'] . '.gif';
				} else {
					$img_url = $car_dealer_options['preloader_image']['url'];
				}
				?>
				<!-- preloader -->
				<div id="loading">
					<div id="loading-center">
						<img src="<?php echo esc_url( $img_url ); ?>" alt="Loader" title="loading...">
					</div>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'cardealer_intro_class' ) ) {
	/**
	 * Intro Class.
	 */
	function cardealer_intro_class() {
		global $post, $car_dealer_options;

		$header_intro_class = array();

		/* Set classes from Options */
		$banner_type = isset( $car_dealer_options['banner_type'] ) ? $car_dealer_options['banner_type'] : '';
		if ( empty( $banner_type ) ) {
			$banner_type = 'image';
		}

		$header_intro_class['header_intro_bg'] = 'header_intro_bg-' . $banner_type;

		if ( 'image' === $banner_type ) {
			if ( ! empty( $car_dealer_options['banner_image_opacity'] ) ) {
				$header_intro_class['header_intro_opacity']      = 'header_intro_opacity';
				$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $car_dealer_options['banner_image_opacity'];
			}
		} elseif ( 'video' === $banner_type ) {
			if ( ! empty( $car_dealer_options['banner_video_opacity'] ) ) {
				$header_intro_class['header_intro_opacity']      = 'header_intro_opacity';
				$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $car_dealer_options['banner_video_opacity'];
			}
		}

		if ( is_page() || is_home() || is_single() || is_archive() ) {
			$post_id = ( ( is_home() ) ? get_option( 'page_for_posts' ) : ( isset( $post->ID ) ? $post->ID : null ) );
			if ( is_archive() ) {
				$post_id = cardealer_get_current_post_id();
			}

			$enable_custom_banner = get_post_meta( $post_id, 'enable_custom_banner', true );
			if ( $enable_custom_banner ) {
				unset( $header_intro_class['header_intro_bg'] );
				unset( $header_intro_class['header_intro_opacity'] );
				unset( $header_intro_class['header_intro_opacity_type'] );
				$banner_type = get_post_meta( $post_id, 'banner_type', true );
				if ( empty( $banner_type ) ) {
					$banner_type = 'image';
				}
				$header_intro_class['header_intro_bg'] = 'header_intro_bg-' . $banner_type;

				if ( $banner_type && 'image' === $banner_type ) {
					$header_intro_class['header_intro_opacity'] = 'header_intro_opacity';
					$background_opacity_color                   = get_post_meta( $post_id, 'background_opacity_color', true );
					if ( $background_opacity_color ) {
						$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $background_opacity_color;
					}
				} elseif ( $banner_type && 'video' === $banner_type ) {
					$header_intro_class['header_intro_opacity'] = 'header_intro_opacity';
					$video_background_opacity_color             = get_post_meta( $post_id, 'video_background_opacity_color', true );
					if ( $video_background_opacity_color ) {
						$header_intro_class['header_intro_opacity_type'] = 'header_intro_opacity-' . $video_background_opacity_color;
					}
				}
			}
		}

		$header_intro_class = implode( ' ', $header_intro_class );
		echo esc_attr( $header_intro_class );
	}
}

if ( ! function_exists( 'cardealer_inventory_page_title' ) ) {
	/**
	 * Vehicle Archieve page title.
	 *
	 * @param array $title to look within.
	 */
	function cardealer_inventory_page_title( $title ) {
		global $car_dealer_options;
		$page_id = cardealer_get_current_post_id();
		if ( $page_id && ! empty( $car_dealer_options['cars_inventory_page'] ) && (int) $page_id === (int) $car_dealer_options['cars_inventory_page'] ) {
			$title = get_the_title( $page_id );
		} elseif ( isset( $car_dealer_options['cars-listing-title'] ) && is_post_type_archive( 'cars' ) ) {
			$title = $car_dealer_options['cars-listing-title'];
		}
		/* if WordPress can't find the title return the default */
		return $title;
	}
}
add_filter( 'pre_get_document_title', 'cardealer_inventory_page_title' );

if ( ! function_exists( 'cardealer_footer_class' ) ) {
	/**
	 * Footer class Intro Class.
	 */
	function cardealer_footer_class() {
		global $post, $car_dealer_options;

		$footer_class = array();

		/* Set classes from Options */
		$banner_type_footer = isset( $car_dealer_options['banner_type_footer'] ) ? $car_dealer_options['banner_type_footer'] : '';
		if ( empty( $banner_type_footer ) ) {
			$banner_type_footer = 'color';
		}

		$footer_class['footer_bg'] = 'footer_bg-' . $banner_type_footer;
		if ( 'image' === $banner_type_footer ) {
			if ( ! empty( $car_dealer_options['banner_image_opacity_footer'] ) ) {
				$footer_class['header_intro_opacity_footer']      = 'footer_opacity';
				$footer_class['header_intro_opacity_type_footer'] = 'footer_opacity-' . $car_dealer_options['banner_image_opacity_footer'];
			}
		}
		return $footer_class;
	}
}

if ( ! function_exists( 'cardealer_excerpt_more' ) ) {
	/**
	 * Vehicle Archieve page title.
	 *
	 * @param array $more to look within.
	 */
	function cardealer_excerpt_more( $more ) {
		global $post;
		return '&hellip; <a class="read-more" href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_html__( 'Read', 'cardealer' ) . get_the_title( $post->ID ) . '">' . esc_html__( 'Read more &raquo;', 'cardealer' ) . '</a>';
	} // end cardealer excerpt more function
}

if ( ! function_exists( 'cardealer_remove_img_dimensions' ) ) {
	/**
	 * Vehicle Archieve page title.
	 *
	 * @param array $html to look within.
	 * @link https://gist.github.com/4557917
	 */
	function cardealer_remove_img_dimensions( $html ) {
		/* Loop through all <img> tags */
		if ( preg_match( '/<img[^>]+>/ims', $html, $matches ) ) {
			foreach ( $matches as $match ) {
				/* Replace all occurences of width/height */
				$clean = preg_replace( '/(width|height)=["\'\d%\s]+/ims', '', $match );
				/* Replace with result within html */
				$html = str_replace( $match, $clean, $html );
			}
		}
		return $html;
	}
}
add_filter( 'get_avatar', 'cardealer_remove_img_dimensions', 10 );

if ( ! function_exists( 'cardealer_get_excerpt_max_charlength' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param int    $charlength Maximum length of char.
	 * @param string $excerpt .
	 *
	 * @return string Shotened Text
	 */
	function cardealer_get_excerpt_max_charlength( $charlength, $excerpt = null ) {
		if ( empty( $excerpt ) ) {
			$excerpt = get_the_excerpt();
		}
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

			$new_excerpt = '';
			if ( $excut < 0 ) {
				$new_excerpt = mb_substr( $subex, 0, $excut );
			} else {
				$new_excerpt = $subex;
			}
			$new_excerpt .= '[...]';
			return $new_excerpt;
		} else {
			return $excerpt;
		}
	}
}

if ( ! function_exists( 'cardealer_the_excerpt_max_charlength' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param int    $charlength Maximum length of char.
	 * @param string $excerpt .
	 */
	function cardealer_the_excerpt_max_charlength( $charlength, $excerpt = null ) {
		$new_excerpt = cardealer_get_excerpt_max_charlength( $charlength, $excerpt );
		echo esc_html( $new_excerpt );
	}
}

if ( ! function_exists( 'cardealer_is_login_page' ) ) {
	/**
	 * Check if on login or register page.
	 */
	function cardealer_is_login_page() {
		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
	}
}

if ( ! function_exists( 'cardealer_topbar_layout_content' ) ) {
	/**
	 * Truncate String with or without ellipsis.
	 *
	 * @param string $field .
	 * @param string $context .
	 */
	function cardealer_topbar_layout_content( $field = '', $context = '' ) {
		global $car_dealer_options;
		$content = '';

		if ( empty( $field ) ) {
			return $content;
		}

		switch ( $field ) {
			case 'email':
				if ( isset( $car_dealer_options['site_email'] ) && ! empty( $car_dealer_options['site_email'] ) ) {
					if ( 'topbar' === $context ) {
						$content = '<i class="far fa-envelope"></i> <a href="mailto:' . sanitize_email( $car_dealer_options['site_email'] ) . '">' . sanitize_email( $car_dealer_options['site_email'] ) . '</a>';
					} else {
						$content = sanitize_email( $car_dealer_options['site_email'] );
					}
				}
				break;
			case 'address':
				if ( isset( $car_dealer_options['site_address'] ) && ! empty( $car_dealer_options['site_address'] ) ) {
					if ( 'topbar' === $context ) {
						$content = '<i class="fas fa-map-marker-alt"></i> ' . wp_kses( $car_dealer_options['site_address'], cardealer_allowed_html( 'a' ) ) . '</a>';
					} else {
						$content = wp_kses( $car_dealer_options['site_address'], cardealer_allowed_html( 'a' ) );
					}
				}
				break;
			case 'promocode':
				if ( function_exists( 'cdhl_plugin_active_status' ) && cdhl_plugin_active_status( 'cardealer-promocode/cardealer-promocode.php' ) ) {
					$element_id = uniqid( 'cdhl-promo-' );
					ob_start();
					?>
					<div class="top-promocode-box">
						<div class="promocode-form form-inline" id="<?php echo esc_attr( $element_id ); ?>">
							<input type="hidden" name="action" class="promocode_action" value="validate_promocode"/>
							<input type="hidden" name="promocode_nonce" class="promocode_nonce" value="<?php echo esc_html( wp_create_nonce( 'cdhl-promocode-form' ) ); ?>">
							<div class="form-group">
								<label for="promocode" class="sr-only"><?php esc_html_e( 'Promocode', 'cardealer' ); ?></label>
								<input type="text" name="promocode" class="form-control promocode" placeholder="<?php echo esc_attr__( 'Promocode', 'cardealer' ); ?>">
							</div>
							<button type="button" class="button promocode-btn" data-fid="<?php echo esc_attr( $element_id ); ?>"><?php echo esc_html__( 'Go', 'cardealer' ); ?></button>
							<span class="spinimg"></span>
							<p class="promocode-msg" style="display:none;"></p>
						</div>
					</div>
					<?php
					$content = ob_get_clean();
				}
				break;
			case 'whatsapp_number':
				if ( isset( $car_dealer_options['site_whatsapp_num'] ) && ! empty( $car_dealer_options['site_whatsapp_num'] ) ) {
					$site_whatsapp_url = ( isset( $car_dealer_options['site_whatsapp_url'] ) && ! empty( $car_dealer_options['site_whatsapp_url'] ) ) ? $car_dealer_options['site_whatsapp_url'] : '';
					if ( 'topbar' === $context ) {
						$content = '<i class="fab fa-whatsapp"></i> ';
						if ( ! empty( $site_whatsapp_url ) ) {
							$content .= '<a href="' . $site_whatsapp_url . '" rel="noopener" target="_blank">';
						}
						$content .= esc_html( $car_dealer_options['site_whatsapp_num'] );
						if ( ! empty( $site_whatsapp_url ) ) {
							$content .= '</a>';
						}
					} else {
						$content = esc_html( $car_dealer_options['site_whatsapp_num'] );
					}
				}
				break;
			case 'phone_number':
				if ( isset( $car_dealer_options['site_phone'] ) && ! empty( $car_dealer_options['site_phone'] ) ) {
					if ( 'topbar' === $context ) {
						$content = '<a href="' . esc_url( 'tel:' . $car_dealer_options['site_phone'] ) . '"><i class="fas fa-phone-alt"></i> ' . esc_html( $car_dealer_options['site_phone'] ) . '</a>';
					} else {
						$content = esc_html( $car_dealer_options['site_phone'] );
					}
				}
				break;
			case 'phone_number2':
				if ( isset( $car_dealer_options['site_phone2'] ) && ! empty( $car_dealer_options['site_phone2'] ) ) {
					if ( 'topbar' === $context ) {
						$content = '<a href="' . esc_url( 'tel:' . $car_dealer_options['site_phone2'] ) . '"><i class="fas fa-mobile-alt"></i> ' . esc_html( $car_dealer_options['site_phone2'] ) . '</a>';
					} else {
						$content = esc_html( $car_dealer_options['site_phone2'] );
					}
				}
				break;
			case 'contact_timing':
				if ( isset( $car_dealer_options['site_contact_timing'] ) && ! empty( $car_dealer_options['site_contact_timing'] ) ) {
					if ( 'topbar' === $context ) {
						$content = '<i class="far fa-clock"></i> ' . esc_html( $car_dealer_options['site_contact_timing'] );
					} else {
						$content = esc_html( $car_dealer_options['site_contact_timing'] );
					}
				}
				break;
			case 'top-bar-menu':
				if ( has_nav_menu( 'topbar-menu' ) ) {
					$content = wp_nav_menu(
						array(
							'theme_location' => 'topbar-menu',
							'menu_class'     => 'top-bar-menu list-inline',
							'menu_id'        => 'top-bar-menu',
							'echo'           => false,
						)
					);
				}
				break;
			case 'login':
				if ( ! class_exists( 'WooCommerce' ) ) {
					return;
				}

				$login_label    = ( isset( $car_dealer_options['topbar_login_login_label'] ) && ! empty( $car_dealer_options['topbar_login_login_label'] ) ) ? $car_dealer_options['topbar_login_login_label'] : esc_html__( 'Login', 'cardealer' );
				$loggedin_label = ( isset( $car_dealer_options['topbar_login_loggedin_label'] ) && ! empty( $car_dealer_options['topbar_login_loggedin_label'] ) ) ? $car_dealer_options['topbar_login_loggedin_label'] : esc_html__( 'My Account', 'cardealer' );
				$login_btn      = array(
					'url'   => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
					'label' => $login_label,
					'icon'  => 'fas fa-lock',
				);

				if ( 'topbar' === $context ) {
					if ( is_user_logged_in() ) {
						$login_btn['icon']  = 'far fa-user';
						$login_btn['label'] = $loggedin_label;
					}
				}

				if ( defined( 'CDFS_VERSION' ) && version_compare( CDFS_VERSION, '2.0.0', '<' ) ) {
					$login_btn['label'] = apply_filters( 'topbar_login_url_label', $login_btn['label'] );
					$login_btn['icon']  = apply_filters( 'topbar_login_url_icon', $login_btn['icon'] );
					$login_btn['url']   = apply_filters( 'topbar_login_url', $login_btn['url'] );
				} else {
					$login_btn['label'] = apply_filters_deprecated( 'topbar_login_url_label', array( $login_btn['label'] ), '4.1.0', 'topbar_login_button' );
					$login_btn['icon']  = apply_filters_deprecated( 'topbar_login_url_icon', array( $login_btn['icon'] ), '4.1.0', 'topbar_login_button' );
					$login_btn['url']   = apply_filters_deprecated( 'topbar_login_url', array( $login_btn['url'] ), '4.1.0', 'topbar_login_button' );
				}

				/* Theme Options */
				$is_custom_url = false;
				$custom_url    = '';
				if ( isset( $car_dealer_options['topbar_custom_login_url'] ) && ! empty( $car_dealer_options['topbar_custom_login_url'] ) ) {
					$is_custom_url = true;
					$custom_url    = $car_dealer_options['topbar_custom_login_url'];
					$login_btn['label'] = $loggedin_label;
					$login_btn['icon']  = 'far fa-user';
					$login_btn['url']   = $custom_url;
				}

				/**
				 * Filters the Label of the Top Bar login URL.
				 *
				 * @since 1.0
				 * @param string        $url_label Label of the top bar login url.
				 * @visible             true
				 */
				$login_btn['label'] = apply_filters_deprecated( 'cd_topbar_login_url_label_final', array( $login_btn['label'] ), '4.1.0', 'topbar_login_button' );

				/**
				 * Filters the Icon class of the Top Bar login URL Use this filter to change top bar login icon.
				 *
				 * @since 1.0
				 * @param string        $icon_class Icon class of the top bar login url.
				 * @visible             true
				 */
				$login_btn['icon'] = apply_filters_deprecated( 'cd_topbar_login_url_icon_final', array( $login_btn['icon'] ), '4.1.0', 'topbar_login_button' );

				/**
				 * Filters the Top Bar login URL.
				 *
				 * @since 1.0
				 * @param string        $topbar_login_url top bar login url.
				 * @visible             true
				 */
				$login_btn['url'] = apply_filters_deprecated( 'cd_topbar_login_url_final', array( $login_btn['url'] ), '4.1.0', 'topbar_login_button' );

				/**
				 * Filters the Top Bar login URL.
				 *
				 * @since 4.1.0
				 * @param string        $topbar_login_url top bar login url.
				 * @visible             true
				 */
				$login_btn = apply_filters( 'topbar_login_button', $login_btn, $is_custom_url, $custom_url );

				ob_start();
				printf(
					'<a href="%s"><i class="%s"></i> %s</a>',
					esc_url( $login_btn['url'] ),
					esc_attr( $login_btn['icon'] ),
					esc_html( $login_btn['label'] )
				);
				$content = ob_get_contents();
				ob_end_clean();
				break;
			case 'language-switcher':
				ob_start();
				cardealer_get_multi_lang();
				$content = ob_get_contents();
				ob_end_clean();
				break;
			case 'social_profiles':

				$social_profiles = ( function_exists( 'cdhl_get_social_profiles_legacy' ) ) ? cdhl_get_social_profiles_legacy() : array();
				$social_profiles_data = array();
				if ( ! empty( $social_profiles ) ) {
					foreach ( $social_profiles as $social_profile_k => $social_profile_data ) {
						$social_profiles_data[ $social_profile_k ] = array(
							'key'  => $social_profile_k,
							'name' => $social_profile_data['title'],
							'icon' => '<i class="' . $social_profile_data['icon_class'] . '"></i>',
							'url'  => $social_profile_data['profile_url'],
						);
					}
				}

				if ( ! empty( $social_profiles_data ) ) {
					if ( 'topbar' === $context ) {
						$social_content = '';
						foreach ( $social_profiles_data as $social_profile ) {
							$social_content .= '<li class="topbar_item topbar_item_type-social_profiles"><a href="' . esc_url( $social_profile['url'] ) . '" target="_blank">' . $social_profile['icon'] . '</a></li>';
						}
						/**
						 * Filters the social profile links displayed in site top bar.
						 *
						 * @since 1.0
						 * @param string        $social_content Contents of the social profile in site top bar.
						 * @visible             true
						 */
						$content = apply_filters( 'cardealer_social_profiles', $social_content );
					} else {
						$content = $social_profiles_data;
					}
				}
				break;
			case 'dealer_dashboard':
				$labels = array(
					'login_label'    => ( isset( $car_dealer_options['topbar_dealer_dashboard_login_label'] ) && ! empty( $car_dealer_options['topbar_dealer_dashboard_login_label'] ) ) ? $car_dealer_options['topbar_dealer_dashboard_login_label'] : esc_html__( 'Login', 'cardealer' ),
					'loggedin_label' => ( isset( $car_dealer_options['topbar_dealer_dashboard_loggedin_label'] ) && ! empty( $car_dealer_options['topbar_dealer_dashboard_loggedin_label'] ) ) ? $car_dealer_options['topbar_dealer_dashboard_loggedin_label'] : esc_html__( 'My Account', 'cardealer' ),
				);
				ob_start();
				do_action( 'topbar_element_dealer_dashboard', $field, $labels );
				$content = ob_get_contents();
				ob_end_clean();
				break;
			default:
				ob_start();
				do_action( 'topbar_content', $field );
				$content = ob_get_contents();
				ob_end_clean();
				break;
		}
		/**
		 * Filters topbar layout content.
		 *
		 * @since 3.4.0
		 * @param string $content  Content.
		 * @param string $field    Field name.
		 * @param string $context  Context.
		 * @visible true
		 * @return string
		 */
		return apply_filters( 'cardealer_topbar_layout_content', $content, $field, $context );
	}
}

if ( ! function_exists( 'cardealer_generate_css_properties' ) ) {
	/**
	 * Converts a multidimensional array of CSS rules into a CSS string.
	 *
	 * @param array $rules array of CSS rules.
	 * @param int   $indent is count variable.
	 *
	 * An array of CSS rules in the form of:
	 * array('selector'=>array('property' => 'value')). Also supports selector
	 *   nesting, e.g.,
	 *   array('selector' => array('selector'=>array('property' => 'value'))).
	 *
	 * @return string
	 *   A CSS string of rules. This is not wrapped in style tags.
	 *
	 * @link source : http://www.grasmash.com/article/convert-nested-php-array-css-string
	 */
	function cardealer_generate_css_properties( $rules, $indent = 0 ) {
		$css    = '';
		$prefix = str_repeat( '  ', $indent );
		foreach ( $rules as $key => $value ) {
			if ( is_array( $value ) ) {
				$selector   = $key;
				$properties = $value;

				$css .= $prefix . "$selector {\n";
				$css .= $prefix . cardealer_generate_css_properties( $properties, $indent + 1 );
				$css .= $prefix . "}\n";
			} else {
				$property = $key;
				$css     .= $prefix . "$property: $value;\n";
			}
		}
		return $css;
	}
}

if ( ! function_exists( 'cardealer_hex2rgba' ) ) {
	/**
	 * Convert hexdec color string to rgb(a) string.
	 *
	 * @param string $color .
	 * @param string $opacity .
	 * @link Source : https://support.advancedcustomfields.com/forums/topic/color-picker-values/
	 */
	function cardealer_hex2rgba( $color = '', $opacity = false ) {

		$default = 'rgb(0,0,0)';

		/* Return default if no color provided */
		if ( empty( $color ) ) {
			return $default;
		}

		/* Sanitize $color if "#" is provided */
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}

		/* Check if color has 6 or 3 characters and get values */
		if ( 6 === strlen( $color ) ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 === strlen( $color ) ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		/* Convert hexadec to rgb */
		$rgb = array_map( 'hexdec', $hex );

		/* Check if opacity is set(rgba or rgb) */
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		/* Return rgb(a) color string */
		return $output;
	}
}

if ( ! function_exists( 'cardealer_array_sort_by_column' ) ) {
	/**
	 * Cardealer array sort by column.
	 *
	 * @param array $array refference variable.
	 *
	 * @param int   $column assign the column.
	 * @param array $direction .
	 */
	function cardealer_array_sort_by_column( &$array, $column, $direction = SORT_ASC ) {
		$reference_array = array();
		foreach ( $array as $key => $row ) {
			if ( isset( $row[ $column ] ) ) {
				$reference_array[ $key ] = $row[ $column ];
			}
		}
		if ( count( $reference_array ) === count( $array ) ) {
			array_multisort( $reference_array, $array, $direction );
		}
	}
}

if ( ! function_exists( 'cardealer_is_vc_enabled' ) ) {
	/**
	 * Return whether Visual Composer is enabled on a page/post or not.
	 *
	 * @param string $post_id = numeric post_id .
	 * return true/false .
	 */
	function cardealer_is_vc_enabled( $post_id = '' ) {
		global $post;

		if ( is_search() || is_404() || empty( $post ) ) {
			return;
		}

		if ( empty( $post_id ) ) {
			$post_id = $post->ID;
		}
		$vc_enabled = get_post_meta( $post_id, '_wpb_vc_js_status', true );
		return ( 'true' === $vc_enabled ) ? true : false;
	}
}

if ( ! function_exists( 'cardealer_hide_page_templates' ) ) {
	/**
	 * Hide page template if Car Dealer helper plugin not activate.
	 *
	 * @param string $page_templates .
	 */
	function cardealer_hide_page_templates( $page_templates ) {

		if ( ! cardealer_check_plugin_active( 'cardealer-helper-library/cardealer-helper-library.php' ) ) {
			unset( $page_templates['templates/faq.php'] );
			unset( $page_templates['templates/team.php'] );
		}

		if ( ! cardealer_check_plugin_active( 'cardealer-promocode/cardealer-promocode.php' ) ) {
			unset( $page_templates['templates/promocode.php'] );
		}

		if ( ! cardealer_check_plugin_active( 'js_composer/js_composer.php' ) ) {
			unset( $page_templates['templates/page-vc_compatible.php'] );
		}

		return $page_templates;
	}
}
add_filter( 'theme_page_templates', 'cardealer_hide_page_templates', 10, 1 );

if ( ! function_exists( 'cardealer_get_attachment_detail' ) ) {
	/**
	 * FUNCTION TO GET IMAGE DATA.
	 *
	 * @param string $attachment_id .
	 */
	function cardealer_get_attachment_detail( $attachment_id ) {
		$attachment = get_post( $attachment_id );
		if ( empty( $attachment ) ) {
			return;
		}
		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title,
		);
	}
}

if ( ! function_exists( 'cardealer_na_tag_cloud' ) ) {
	/**
	 * This function is used to remove size style from tags.
	 *
	 * @param string $string .
	 */
	function cardealer_na_tag_cloud( $string ) {
		return preg_replace( "/style='font-size:.+pt;'/", '', $string );
	}
}
add_filter( 'wp_generate_tag_cloud', 'cardealer_na_tag_cloud', 10, 1 );

if ( ! function_exists( 'cardealer_comming_soon_newsletter' ) ) {
	/**
	 * Function to add NewsLetter on Coming Soon page.
	 */
	function cardealer_comming_soon_newsletter() {
		global $car_dealer_options;
		if ( cardealer_check_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) {
			if ( isset( $car_dealer_options['comming_page_newsletter_shortcode'] ) && ! empty( $car_dealer_options['comming_page_newsletter_shortcode'] ) ) {
				$mailchimp_id = $car_dealer_options['comming_page_newsletter_shortcode'];
			} else {
				return;
			}
			if ( ! empty( $car_dealer_options['newsletter_description'] ) ) {
				?>
				<div class="row text-center">
					<div class="col-lg-12 col-md-12">
						<p><?php echo do_shortcode( $car_dealer_options['newsletter_description'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></p>
					</div>
				</div>
				<?php
			}
			?>
			<div class="row gray-form no-gutter coming-soon-newsletter">
				<div class="col-sm-12">
					<?php
					if ( $mailchimp_id ) {
						echo do_shortcode( '[mc4wp_form id=' . $mailchimp_id . ']' );
					}
					?>
				</div>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'cardealer_helper_get_file_list' ) ) {
	/**
	 * Function for adding given file list
	 * It accepts two parameters
	 * extensions: mixed (either array or string - comma separated)
	 * NOTE : Use this instead of GLOB() ( As glob() is having PHP version issue )
	 *
	 * @param string $extensions .
	 * @param string $path .
	 */
	function cardealer_helper_get_file_list( $extensions = '', $path = '' ) {

		/* Return if any paramater is blank */
		if ( empty( $extensions ) || empty( $path ) ) {
			return false;
		}

		/* Convert to array if string is provided */
		if ( ! is_array( $extensions ) ) {
			$extensions = array_filter( explode( ',', $extensions ) );
		}

		/* Fix trailing slash if not provided. */
		$path = rtrim( $path, '/\\' ) . '/';

		if ( defined( 'GLOB_BRACE' ) ) {
			$extensions_with_glob_brace = '{' . implode( ',', $extensions ) . '}'; /* file extensions pattern */
			$files_with_glob            = glob( $path . "*.{$extensions_with_glob_brace}", GLOB_BRACE );

			return $files_with_glob;
		} else {
			$extensions_without_glob = implode( '|', $extensions ); /* file extensions pattern */

			/* Get all files */
			$files_without_glob_all = glob( $path . '*.*' );

			/* Filter files with pattern */
			$files_without_glob = array_values( preg_grep( "~\.($extensions_without_glob)$~", $files_without_glob_all ) );
			return $files_without_glob;
		}

		return $files;
	}
}

if ( ! function_exists( 'cardealer_get_current_post_id' ) ) {
	/**
	 * Cardealer get current post id
	 */
	function cardealer_get_current_post_id() {
		global $car_dealer_options;
		$post_id = get_the_ID();

		/* avoid confliction of same name between post type and page name */
		if ( ! is_admin() && is_archive() ) {
			$post_type = get_queried_object();
			/**
			 * Check for Vehicle category archieve page and return page id if page is set from theme options.
			 * Get post type from category archieve page.
			 */
			$is_cat_archive = false;
			if ( is_tax() ) {
				$tax_post_type = get_taxonomy( $post_type->taxonomy )->object_type;
				if ( ! is_wp_error( $tax_post_type ) && isset( $tax_post_type[0] ) && 'cars' === $tax_post_type[0] && isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
					return apply_filters( 'cardealer_get_current_page_post_id', $car_dealer_options['cars_inventory_page'] );
				}
			}

			/*
			Return if no WooCommerce or Vehicle listing page called.
			*/
			$inventory_slug = ( isset( $car_dealer_options['cars-details-slug'] ) && ! empty( $car_dealer_options['cars-details-slug'] ) ) ? $car_dealer_options['cars-details-slug'] : 'cars';
			if ( ! isset( $post_type->rewrite['slug'] ) || ( $post_type->rewrite['slug'] !== $inventory_slug && 'product' !== $post_type->rewrite['slug'] ) || ( $post_type->rewrite['slug'] === $inventory_slug && empty( $car_dealer_options['cars_inventory_page'] ) ) ) {
				return apply_filters( 'cardealer_get_current_page_post_id', null );
			}

			$page = get_page_by_path( $post_type->has_archive ); // get slug.
			if ( isset( $page->ID ) ) {
				$post_id = $page->ID;
			}

			/* check for WPML */
			if ( cardealer_is_wpml_active() ) {
				$wpml_page = icl_object_id( get_page_by_path( $post_type->has_archive )->ID, 'page', true );
				if ( $wpml_page ) {
					$post_id = $wpml_page;
				}
			}
		}
		return apply_filters( 'cardealer_get_current_page_post_id', $post_id );
	}
}

if ( ! function_exists( 'cardealer_get_lat_lnt' ) ) {
	/**
	 * Cardealer getLatLnt
	 *
	 * @param string $address .
	 */
	function cardealer_get_lat_lnt( $address ) {
		global $car_dealer_options;
		$gapi             = isset( $car_dealer_options['google_maps_api'] ) ? $car_dealer_options['google_maps_api'] : '';
		$vehicle_location = rawurlencode( $address );
		$url              = add_query_arg( array(
			'key'     => $gapi,
			'sensor'  => 'false',
			'address' => rawurlencode( $vehicle_location ),
		), 'https://maps.googleapis.com/maps/api/geocode/json' );

		$api_args = array(
			'timeout' => 600,
		);
		$response = wp_remote_get( $url, $api_args );

		if ( ! is_wp_error( $response ) ) {
			$results = json_decode( $response['body'], true );
			if ( isset( $response['body'] ) && isset( $results['results'][0] ) ) {
				$lat  = $results['results'][0]['geometry']['location']['lat'];
				$long = $results['results'][0]['geometry']['location']['lng'];
			} else {
				$lat  = '';
				$long = '';
			}
		} else {
			$lat  = '';
			$long = '';
		}

		$data = array(
			'lat' => $lat,
			'lng' => $long,
		);

		if ( empty( $lat ) || empty( $long ) ) {
			$data['addr_found'] = '0';
		} else {
			$data['addr_found'] = '1';
		}
		return $data;
	}
}

if ( ! function_exists( 'cardealer_wp_body_classes' ) ) {
	/**
	 * Filter code to add options for Page Layout
	 *
	 * @param string $classes .
	 */
	function cardealer_wp_body_classes( $classes ) {
		global $car_dealer_options;
		if ( wp_is_mobile() ) {
			$classes[] = 'device-type-mobile';
		}

		$post_id = cardealer_get_current_post_id();

		$enable_custom_layout = get_post_meta( $post_id, 'enable_custom_layout', true );
		if ( $enable_custom_layout ) {
			$page_layout = get_post_meta( $post_id, 'page_layout', true );
			$classes[]   = 'site-layout-' . $page_layout;
		} else {
			if ( ! empty( $car_dealer_options['page_layout'] ) ) {
				$classes[] = 'site-layout-' . $car_dealer_options['page_layout'];
			}
		}
		return $classes;
	}
}
add_filter( 'body_class', 'cardealer_wp_body_classes' );

if ( ! function_exists( 'cardealer_get_google_maps_api_key' ) ) {
	/**
	 * Cardealer get google api key
	 */
	function cardealer_get_google_maps_api_key() {
		global $car_dealer_options;
		$google_maps_api = ( isset( $car_dealer_options['google_maps_api'] ) && ! empty( $car_dealer_options['google_maps_api'] ) ) ? $car_dealer_options['google_maps_api'] : '';
		$google_maps_api = apply_filters( 'cardealer_get_google_maps_api_key', $google_maps_api );
		return $google_maps_api;
	}
}

if ( ! function_exists( 'cardealer_acf_init' ) ) {
	/**
	 * ACF map Key
	 */
	function cardealer_acf_init() {

		$car_dealer_options = get_option( 'car_dealer_options' );
		$google_maps_api = ( isset( $car_dealer_options['google_maps_api'] ) && ! empty( $car_dealer_options['google_maps_api'] ) ) ? $car_dealer_options['google_maps_api'] : '';
		if ( ! empty( $google_maps_api ) ) {
			acf_update_setting( 'google_api_key', $google_maps_api );
		}
	}
}
add_action( 'acf/init', 'cardealer_acf_init' );

if ( ! function_exists( 'cardealer_reset_mega_menu' ) ) {
	/**
	 * Cardealer wp body classes
	 *
	 * @param string $args .
	 * @param string $menu_id .
	 * @param string $current_theme_location .
	 */
	function cardealer_reset_mega_menu( $args, $menu_id, $current_theme_location ) {

		/* Reset menu arguments */
		if ( isset( $_GET['disable-mega'] ) && 1 === $_GET['disable-mega'] ) { // phpcs:ignore WordPress.Security.NonceVerification

			/* Reset Primary Menu */
			if ( 'primary-menu' === $current_theme_location ) {
				$args['theme_location']  = $current_theme_location;
				$args['container']       = 'ul';
				$args['container_id']    = 'menu-wrap-primary';
				$args['container_class'] = 'menu-wrap';
				$args['menu_id']         = 'primary-menu';
				$args['menu_class']      = 'menu-links';
				unset( $args['walker'] );

			}
		}

		return $args;
	}
}
add_filter( 'megamenu_nav_menu_args', 'cardealer_reset_mega_menu', 10, 3 );

if ( ! function_exists( 'cardealer_custom_excerpt_length' ) ) {
	/**
	 * For excerpt data limit.
	 *
	 * @param string $length length of the excerpt.
	 */
	function cardealer_custom_excerpt_length( $length ) {
		global $post;
		if ( isset( $post->post_type ) && 'teams' === $post->post_type ) {
			return 15;
		} else {
			return $length;
		}

	}
}
add_filter( 'excerpt_length', 'cardealer_custom_excerpt_length', 999 );

if ( ! function_exists( 'cardealer_check_plugin_active' ) ) {
	/**
	 * Check plugin is active or not .
	 *
	 * @param string $plugin check string .
	 * @return Bool
	 */
	function cardealer_check_plugin_active( $plugin = '' ) {

		if ( empty( $plugin ) ) {
			return false;
		}

		return ( in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin ) ) );
	}
}

if ( ! function_exists( 'cardealer_check_plugin_installed' ) ) {
	/**
	 * Check plugin is active or not .
	 *
	 * @param string $plugin check string .
	 * @return Bool
	 */
	function cardealer_check_plugin_installed( $plugin = '' ) {
		$installed         = false;
		$installed_plugins = array();

		if ( ! empty( $plugin ) ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

			if ( function_exists( 'get_plugins' ) ) {
				$installed_plugins = get_plugins();
			}

			$installed = ( ! empty( $installed_plugins[ $plugin ] ) );
		}

		$installed = apply_filters( 'cardealer_check_plugin_installed', $installed, $plugin );

		return $installed;
	}
}

add_filter( 'upgrader_pre_download', 'cardealer_pre_upgrade_filter', 999, 3 );
/**
 * Cardealer pre upgrade filter
 */
function cardealer_pre_upgrade_filter( $reply, $package, $updater ) {
	$plugins = array(
		'cardealer-helper-library',
		'revslider',
		'advanced-custom-fields-pro',
		'js_composer',
		'cardealer-front-submission',
		'cardealer-pdf-generator',
		'cardealer-vinquery-import',
		'subscriptio'
	);
	$need_verification = false;
	foreach ( $plugins as $plugin ) {
		if ( strpos( $package, $plugin) !== false ) {
			$need_verification = true;
			break;
		}
	}

	if ( $need_verification ) {
		$support_settings_url = esc_url( admin_url('admin.php?page=cardealer-panel') );
		$error = new WP_Error( 'no_credentials',
			sprintf(
				/* translators: 1: Support Panel URL */
				esc_html__( 'Please verify your purchase code from <a href="%s" target="_blank">here</a>. It seems like your purchase code is not activated on this domain.', 'cardealer' ),
				esc_url( $support_settings_url )
			)
		);
		$purchase_key = get_option( 'cardealer_theme_purchase_key' );
		if ( empty( $purchase_key ) ) {
			return $error;
		} else {

			$args = array(
				'product_key'  => PGS_PRODUCT_KEY,
				'purchase_key' => $purchase_key,
				'site_url'     => get_site_url(),
				'action'       => 'register',
			);

			$url           = add_query_arg( $args, trailingslashit( PGS_ENVATO_API ) . 'verifyproduct' );
			$response      = wp_remote_get( $url, array( 'timeout' => 2000 ) );
			$response_code = wp_remote_retrieve_response_code( $response );
			$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

			// Check if response is valid
			if ( is_wp_error( $response ) || '200' !== (string) $response_code ) {
				cardealer_deactivate_license();
				return $error;
			} else if( '200' === (string) $response_code ) {
				if ( 1 !== $response_body['status'] ) {
					cardealer_deactivate_license();
					return $error;
				}
			}
		}
	}
	return $reply;
}

if ( ! function_exists( 'cardealer_deactivate_license' ) ) {
	/**
	 * Cardealer deactivate license
	 */
	function cardealer_deactivate_license() {
		delete_option( 'cardealer_theme_purchase_key' );
	}
}

if ( ! function_exists( 'cardealer_is_activated' ) ) {
	/**
	 * Cardealer check plugin active
	 */
	function cardealer_is_activated() {
		$purchase_token = get_option( 'cardealer_pgs_token' );
		if ( $purchase_token && ! empty( $purchase_token ) ) {
			return $purchase_token;
		}
		return false;
	}
}

if ( ! function_exists( 'cardealer_allowed_html' ) ) {
	/**
	 * Check plugin is active or not .
	 *
	 * @param string $allowed_els .
	 */
	function cardealer_allowed_html( $allowed_els = '' ) {
		/* bail early if parameter is empty */
		if ( empty( $allowed_els ) ) {
			return array();
		}

		if ( is_string( $allowed_els ) ) {
			$allowed_els = explode( ',', $allowed_els );
		}

		$allowed_html = array();
		$allowed_tags = wp_kses_allowed_html( 'post' );
		foreach ( $allowed_els as $el ) {
			$el = trim( $el );
			if ( array_key_exists( $el, $allowed_tags ) ) {
				$allowed_html[ $el ] = $allowed_tags[ $el ];
			}
		}
		return $allowed_html;
	}
}

if ( ! function_exists( 'cardealer_welcome_logo' ) ) {
	/**
	 * Cardealer welcome logo
	 */
	function cardealer_welcome_logo() {
		$welcome_logo      = CARDEALER_URL . '/images/admin/logo.png';
		$welcome_logo_path = CARDEALER_PATH . '/images/admin/logo.png';
		if ( file_exists( $welcome_logo_path ) && getimagesize( $welcome_logo ) !== false ) {
			return $welcome_logo;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'cardealer_get_multi_lang' ) ) {
	/**
	 * Cardealer get multi lang
	 */
	function cardealer_get_multi_lang() {
		global $car_dealer_options;

		/*Checl WPML sitepress multilingual plugin activate */
		if ( cardealer_is_wpml_active() && function_exists( 'icl_get_languages' ) ) {
			$languages = icl_get_languages();
			/* Display Current language */
			$lan_switcher_style = ( isset( $car_dealer_options['language-switcher-style'] ) && ! empty( $car_dealer_options['language-switcher-style'] ) ) ? $car_dealer_options['language-switcher-style'] : 'dropdown';
			$lan_item_style     = ( isset( $car_dealer_options['language-items-style'] ) && ! empty( $car_dealer_options['language-items-style'] ) ) ? $car_dealer_options['language-items-style'] : 'default';
			$label_style        = 'non-translated';
			if ( isset( $car_dealer_options['show-translated-label'] ) && 'true' === $car_dealer_options['show-translated-label'] ) {
				$label_style = 'translated';
			}

			if ( ! empty( $languages ) ) {
				?>
				<div class="language style-<?php echo esc_attr( $lan_switcher_style . ' ' . $label_style ); ?>" id="cardealer-lang-drop-down">
					<?php
					if ( 'horizontal' === $lan_switcher_style ) {
						?>
						<ul id="cardealer-lang-drop-content" class="drop-content">
							<?php
							foreach ( $languages as $l ) {
								?>
								<li>
									<?php
									if ( 1 === (int) $l['active'] ) {
										?>
										<a href="javascript:void(0)" class="cardealer-current-lang active">
										<?php
									} else {
										?>
										<a href="<?php echo esc_url( $l['url'] ); ?>">
										<?php
									}
									if ( isset( $l['country_flag_url'] ) && ! empty( $l['country_flag_url'] ) && ( 'default' === $lan_item_style || 'flag_only' === $lan_item_style ) ) {
										?>
										<img src="<?php echo esc_url( $l['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $l['language_code'] ); ?>" width="18" />
										<?php
									}
									if ( 'default' === $lan_item_style || 'label_only' === $lan_item_style ) {
										?>
										<span class="lang-label"><?php echo icl_disp_language( $l['native_name'], $l['translated_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
										<?php
									}
									?>
									</a>
								</li>
								<?php
							}
							?>
						</ul>
						<?php
					} else {
						foreach ( $languages as $k => $al ) {
							if ( 1 === (int) $al['active'] ) {
								?>
								<a href="javascript:void(0)" class="cardealer-current-lang" data-toggle="collapse" data-target="#cardealer-lang-drop-content">
									<?php
									if ( isset( $al['country_flag_url'] ) && ! empty( $al['country_flag_url'] ) && ( 'default' === $lan_item_style || 'flag_only' === $lan_item_style ) ) {
										?>
										<img src="<?php echo esc_url( $al['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $al['language_code'] ); ?>" width="18" />
										<?php
									}

									if ( 'default' === $lan_item_style || 'label_only' === $lan_item_style ) {
										echo icl_disp_language( $al['native_name'], $al['translated_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
									}
									?>
									&nbsp;<i class="fas fa-angle-down">&nbsp;</i>
								</a>
								<?php
								unset( $languages[ $k ] );
								break;
							}
						}
						?>
						<ul id="cardealer-lang-drop-content" class="drop-content collapse">
							<?php
							foreach ( $languages as $l ) {
								if ( ! $l['active'] ) {
									?>
									<li>
										<a href="<?php echo esc_url( $l['url'] ); ?>">
											<?php
											if ( isset( $l['country_flag_url'] ) && ! empty( $l['country_flag_url'] ) && ( 'default' === $lan_item_style || 'flag_only' === $lan_item_style ) ) {
												?>
												<img src="<?php echo esc_url( $l['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $l['language_code'] ); ?>" width="18" />
												<?php
											}
											if ( 'default' === $lan_item_style || 'label_only' === $lan_item_style ) {
												?>
												<span class="lang-label"><?php echo icl_disp_language( $l['native_name'], $l['translated_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
												<?php
											}
											?>
										</a>
									</li>
									<?php
								}
							}
							?>
						</ul>
						<?php
					}
					?>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'cardealer_lazyload_enabled' ) ) {
	/**
	 * Check lazyload enabled
	 */
	function cardealer_lazyload_enabled() {
		global $car_dealer_options;

		if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return false;
		} else {
			if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] && ! is_admin() ) {
				return true;
			} else {
				return false;
			}
		}
	}
}

if ( ! function_exists( 'car_dealer_get_car_compare_ids' ) ) {
	/**
	 * Gets the car compare ids.
	 *
	 * @return mixed False or array of car ids for compare.
	 */
	function car_dealer_get_car_compare_ids() {

		$car_ids = false;

		if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
			$cars = sanitize_text_field( wp_unslash( $_COOKIE['cars'] ) );
			$cars = json_decode( $cars );
			if ( is_array( $cars ) && ! empty( $cars ) ) {
				$car_ids = $cars;
			}
		}

		return $car_ids;
	}
}

if ( ! function_exists( 'car_dealer_get_options_tab_number' ) ) {
	/**
	 * Gets redux options tab number by field_id.
	 *
	 * @param string $field_id field id.
	 * @return int|string
	 */
	function car_dealer_get_options_tab_number( $field_id = '' ) {
		$tab_number = '';

		if ( ! empty( $field_id ) && class_exists( 'Redux_Instances' ) && class_exists( 'Redux_Helpers' ) ) {
			$redux_instance = Redux_Instances::get_instance( CARDEALER_THEME_OPTIONS_NAME );

			if ( isset( $redux_instance->sections ) && $redux_instance->sections ) {
				$tab_number = Redux_Helpers::tab_from_field( $redux_instance, $field_id );
			}
		}

		return $tab_number;
	}
}

if ( ! function_exists( 'car_dealer_get_options_tab_url' ) ) {
	/**
	 * Gets redux options tab url by field_id.
	 *
	 * @param string $field_id field id.
	 * @return string
	 */
	function car_dealer_get_options_tab_url( $field_id = '' ) {
		$tab_url      = '';
		$tab_url_args = array(
			'page' => 'cardealer',
		);

		if ( ! empty( $field_id ) ) {
			$option_tab_number = car_dealer_get_options_tab_number( $field_id );
			if ( ! empty( $option_tab_number ) ) {
				$tab_url_args['tab'] = $option_tab_number;
			}
		}

		$tab_url = add_query_arg( $tab_url_args, admin_url( 'themes.php' ) );

		return $tab_url;
	}
}

if ( ! function_exists( 'cardealer_get_theme_option' ) ) {
	/**
	 * Get theme option
	 *
	 * @param string $option_id option id.
	 * @param string $fallback fallback.
	 * @param bool   $param param.
	 */
	function cardealer_get_theme_option( $option_id, $fallback = '', $param = false ) {
		global $car_dealer_options;

		if ( empty( $car_dealer_options ) ) {
			$car_dealer_options = get_option( 'car_dealer_options' );
		}

		$output = ( isset( $car_dealer_options[ $option_id ] ) && $car_dealer_options[ $option_id ] ) ? $car_dealer_options[ $option_id ] : $fallback;

		if (
			( isset( $car_dealer_options[ $option_id ] ) && $car_dealer_options[ $option_id ] )
			&& is_array( $car_dealer_options[ $option_id ] )
			&& ! empty( $car_dealer_options[ $option_id ] )
			&& $param && isset( $car_dealer_options[ $option_id ][ $param ] )
		) {
			$output = $car_dealer_options[ $option_id ][ $param ];
		}

		return $output;
	}
}

if ( ! function_exists( 'cardealer_get_mileage_max' ) ) {
	/**
	 * Get maximum mileage
	 */
	function cardealer_get_mileage_max() {
		$mileage_max = 0;

		$mileages = get_terms(
			array(
				'taxonomy'   => 'car_mileage',
				'hide_empty' => true,
				'fields'     => 'names',
			)
		);

		if ( ! empty( $mileages ) && ! is_wp_error( $mileages ) ) {
			$mileages = array_filter( $mileages, 'is_numeric' );
			if ( ! empty( $mileages ) ) {
				$mileage_max = max( $mileages );
			}
		}

		return $mileage_max;
	}
}

if ( ! function_exists( 'cardealer_get_mileage_min' ) ) {
	/**
	 * Get minumum mileag
	 */
	function cardealer_get_mileage_min() {
		$mileage_min = 0;

		$mileages = get_terms(
			array(
				'taxonomy'   => 'car_mileage',
				'hide_empty' => false,
				'fields'     => 'names',
			)
		);

		if ( ! empty( $mileages ) && ! is_wp_error( $mileages ) ) {
			$mileages = array_filter( $mileages, 'is_numeric' );
			if ( ! empty( $mileages ) ) {
				$mileage_min = min( $mileages );
			}
		}

		return $mileage_min;
	}
}

if ( ! function_exists( 'cardealer_roundup_to_nearest_multiple' ) ) {
	/**
	 * Get roundup to nearest multiple
	 *
	 * @param int $n maximum number.
	 * @param int $increment increment number.
	 */
	function cardealer_roundup_to_nearest_multiple( $n, $increment = 1000 ) {
		return (int) ( $increment * ceil( $n / $increment ) );
	}
}

/**
 * Check if wpbakery active
 */
function cardealer_is_wpbakery_active() {

	$wpbakery_active = class_exists( 'WPBakeryVisualComposerAbstract' );
	$wpbakery_active = apply_filters( 'cardealer_is_wpbakery_active', $wpbakery_active );

	return $wpbakery_active;
}

/**
 * Check if elementor active
 */
function cardealer_is_elementor_active() {

	$elementor_active = did_action( 'elementor/loaded' );
	$elementor_active = apply_filters( 'cardealer_is_elementor_active', $elementor_active );

	return $elementor_active;

}

/**
 * Render html attributes
 *
 * @param array $attributes
 *
 * @return string
 */
function cardealer_render_attributes( array $attributes ) {
	$rendered_attributes = array();

	foreach ( $attributes as $attribute_key => $attribute_values ) {
		if ( is_array( $attribute_values ) ) {
			$attribute_values = implode( ' ', array_filter( array_unique( $attribute_values ) ) );
		}

		if ( 'href' === $attribute_key || 'src' === $attribute_key ) {
			$rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_url( $attribute_values ) );
		} elseif ( 'download' === $attribute_key ) {
			$rendered_attributes[] = sprintf( '%1$s', $attribute_key, esc_attr( $attribute_values ) );
		} else {
			$rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
		}
	};

	return $rendered_attributes;
}

/**
 * Render html attributes
 *
 * @param array $attributes
 *
 * @return string
 */
function cardealer_render_attributes_str( array $attributes ) {
	$attributes = cardealer_render_attributes( $attributes );

	return implode( ' ', $attributes );
}

/**
 * Assign Title when page inventory page assign as front page
 *
 * @param string $name
 * @param string $post_type
 *
 * @return string
 */
add_filter( 'post_type_archive_title', 'cdhl_post_type_archive_title', 10, 2 );
function cdhl_post_type_archive_title( $name, $post_type ) {
	global $car_dealer_options;
	if ( 'cars' === $post_type ) {
		$inv_page_id = isset( $car_dealer_options['cars_inventory_page'] ) ? $car_dealer_options['cars_inventory_page'] : '';
		if ( $inv_page_id ) {
			$name = get_the_title( $inv_page_id );
		}
	}
	return $name;
}


function cardealer_get_content_intro_post_id() {
	global $car_dealer_options, $post;

	$content_intro_post_id = is_home() ? get_option( 'page_for_posts' ) : get_the_ID();

	if ( is_post_type_archive( 'cars' ) ) {
		if ( isset( $car_dealer_options['cars_inventory_page'] ) && $car_dealer_options['cars_inventory_page'] ) {
			$page_on_front       = get_option( 'page_on_front' );
			$cars_inventory_page = $car_dealer_options['cars_inventory_page'];

			if ( (int) $page_on_front === (int) $cars_inventory_page ) {
				$content_intro_post_id = $car_dealer_options['cars_inventory_page'];
			}
		}
	}

	if ( is_archive() ) {
		$content_intro_post_id = cardealer_get_current_post_id();

		// check for vehicle inventory home page.
		$front_page = get_option( 'page_on_front' );
		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) && $front_page === $car_dealer_options['cars_inventory_page'] ) {
			$content_intro_post_id = $front_page;
		}
		if ( class_exists( 'WooCommerce' ) && is_shop() ) {
			$shop_pg_id = get_option( 'woocommerce_shop_page_id' );
			if ( ! is_wp_error( $shop_pg_id ) && ! empty( $shop_pg_id ) ) {
				$content_intro_post_id = $shop_pg_id;
			}
		}
	}

	if ( isset( $post ) && ! is_archive() && ! is_post_type_archive() ) {
		if ( ! is_home() ) {
			$content_intro_post_id = $post->ID;
		}
	}

	return $content_intro_post_id;
}

function cardealer_hide_header_banner() {
	$content_intro_post_id = cardealer_get_content_intro_post_id();
	$layout                = cardear_get_vehicle_detail_page_layout();
	$hide_header_banner    = get_post_meta( $content_intro_post_id, 'hide_header_banner', true );

	if ( ! empty( $hide_header_banner ) && ( is_search() ) ) {
		$hide_header_banner = false;
	}

	if ( is_singular( 'cars' ) && ( 'modern-1' === $layout || ( wp_is_mobile() ) ) ) {
		$hide_header_banner = true;
	}

	$hide_header_banner = apply_filters( 'cardealer_hide_header_banner', $hide_header_banner );
	$hide_header_banner = filter_var( $hide_header_banner, FILTER_VALIDATE_BOOLEAN );

	return $hide_header_banner;
}

function cardealer_is_custom_banner_enabled() {
	$content_intro_post_id = cardealer_get_content_intro_post_id();

	$enable_custom_banner = get_post_meta( $content_intro_post_id, 'enable_custom_banner', true );

	return filter_var( $enable_custom_banner, FILTER_VALIDATE_BOOLEAN );
}

function cardealer_get_banner_type() {
	global $car_dealer_options;

	$custom_banner_enabled = cardealer_is_custom_banner_enabled();
	$content_intro_post_id = cardealer_get_content_intro_post_id();

	if ( $custom_banner_enabled ) {
		$banner_type = get_post_meta( $content_intro_post_id, 'banner_type', true );
	} else {
		$banner_type = isset( $car_dealer_options['banner_type'] ) ? $car_dealer_options['banner_type'] : '';
	}

	return $banner_type;
}

function cardealer_get_video_type() {
	global $car_dealer_options;

	$custom_banner_enabled = cardealer_is_custom_banner_enabled();
	$content_intro_post_id = cardealer_get_content_intro_post_id();
	$banner_type           = cardealer_get_banner_type();
	$video_type            = '';

	if ( 'video' === $banner_type ) {
		if ( $custom_banner_enabled ) {
			$video_type  = get_post_meta( $content_intro_post_id, 'banner_video_type_bg_custom', true );
		} else {
			$video_type  = isset( $car_dealer_options['video_type'] ) ? $car_dealer_options['video_type'] : '';
		}
	}

	return $video_type;
}

function cardealer_get_video_link() {
	global $car_dealer_options;

	$custom_banner_enabled = cardealer_is_custom_banner_enabled();
	$content_intro_post_id = cardealer_get_content_intro_post_id();
	$banner_type           = cardealer_get_banner_type();
	$video_type            = cardealer_get_video_type();
	$video_link            = '';

	if ( 'video' === $banner_type ) {
		if ( $custom_banner_enabled ) {
			$video_link = get_post_meta( $content_intro_post_id, 'banner_video_bg_custom', true );
		} else {
			if ( 'youtube' === $video_type && ! empty( $car_dealer_options['youtube_video'] ) ) {
				$video_link = $car_dealer_options['youtube_video'];
			} elseif ( 'vimeo' === $video_type && ! empty( $car_dealer_options['vimeo_video'] ) ) {
				$video_link = $car_dealer_options['vimeo_video'];
			}
		}
	}

	return $video_link;
}

/**
 * Helper function: Validate a value as boolean
 *
 * @param mixed $value Arbitrary value.
 * @return bool
 */
function cardealer_validate_bool( $value ) {
	$is_boolean = false;

	if ( extension_loaded( 'filter' ) ) {
		$is_boolean = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	} else {
		$true  = array( '1', 'true', 'y', 'yes', 'on' );
		$false = array( '0', 'false', 'n', 'no', 'off' );

		if ( is_bool( $value ) ) {
			$is_boolean = $value;
		} elseif ( is_int( $value ) && ( 0 === $value || 1 === $value ) ) {
			$is_boolean = (bool) $value;
		} elseif ( ( is_float( $value ) && ! is_nan( $value ) ) && ( (float) 0 === $value || (float) 1 === $value ) ) {
			$is_boolean = (bool) $value;
		} elseif ( is_string( $value ) ) {
			$value = strtolower( trim( $value ) );
			if ( in_array( $value, $true, true ) ) {
				$is_boolean = true;
			} elseif ( in_array( $value, $false, true ) ) {
				$is_boolean = false;
			} else {
				$is_boolean = false;
			}
		}
	}

	return $is_boolean;
}

/**
 * Helper function: CSS Class generator
 *
 * @param string|array $class  List of classes, either string (separated with space) or array.
 * @param bool         $echo   Whether to return or echo.
 * @return string
 */
function cardealer_class_generator( $class = '', $echo = true ) {
	$classes = array();

	if ( ( is_array( $class ) || is_string( $class ) ) && ! empty( $class ) ) {

		if ( is_array( $class ) ) {
			$class = implode( ' ', $class );
		}

		// If $class is string, convert it to array.
		if ( is_string( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = $class;
	}

	// Sanitize classes.
	$classes = array_map( 'sanitize_html_class', $classes );
	$classes = array_map( 'esc_attr', $classes );

	// Convert array to string.
	$class_str = implode( ' ', array_filter( array_unique( $classes ) ) );

	if ( $echo ) {
		echo esc_attr( $class_str );
	} else {
		return $class_str;
	}
}

/**
 * Get the list of page builder.
 */
function cardealer_get_page_builders() {
	$page_builders = array(
		'wpbakery'  => array(
			'name'         => 'wpbakery',
			'label'        => esc_html__( 'WPBakery Page Builder', 'cardealer' ),
			'is_active_cb' => 'cardealer_page_builder_is_active_wpbakery',
		),
		'elementor' => array(
			'name'         => 'elementor',
			'label'        => esc_html__( 'Elementor', 'cardealer' ),
			'is_active_cb' => 'cardealer_page_builder_is_active_elementor',
		),
	);

	$page_builders = apply_filters( 'cardealer_page_builders', $page_builders );

	return $page_builders;
}

function cardealer_page_builder_is_active_wpbakery() {
	return apply_filters( 'ardealer_page_builder_is_active_wpbakery', class_exists( 'WPBakeryVisualComposerAbstract' ) );
}

function cardealer_page_builder_is_active_elementor() {
	return apply_filters( 'cardealer_page_builder_is_active_elementor', did_action( 'elementor/loaded' ) );
}

/**
 * Get active page builders.
 */
function cardealer_get_active_page_builders() {
	$page_builders   = cardealer_get_page_builders();
	$active_builders = array();

	foreach ( $page_builders as $page_builder => $page_builder_data ) {
		if ( isset( $page_builder_data['is_active_cb'] ) && is_callable( $page_builder_data['is_active_cb'] ) ) {
			$is_active = call_user_func( $page_builder_data['is_active_cb'], $page_builder, $page_builder_data );
			$is_active =  filter_var( $is_active, FILTER_VALIDATE_BOOLEAN );
			if ( is_bool( $is_active ) && true === $is_active ) {
				$active_builders[] = $page_builder;
			}
		}
	}

	$active_builders = apply_filters( 'cardealer_get_active_page_builders', $active_builders );

	return $active_builders;
}

/**
 * Get the default page builder.
 */
function cardealer_get_default_page_builder( $is_setup_wizard = false ) {
	$page_builder_option = 'wpbakery';
	$page_builders       = cardealer_get_page_builders();
	$active_builders     = cardealer_get_active_page_builders();
	$multiple_active     = count( $active_builders ) > 1;

	if ( ! empty( $active_builders ) && 1 === count( $active_builders ) ) {
		$page_builder_option = $active_builders[0];
	}

	if ( ! $page_builder_option || $multiple_active || $is_setup_wizard ) {
		$page_builder_option = get_option( 'cardealer_default_page_builder' );
	}

	$default_page_builder = ( $page_builder_option && array_key_exists( $page_builder_option, $page_builders ) ) ? $page_builder_option : 'wpbakery';
	$default_page_builder = apply_filters( 'cardealer_get_default_page_builder', $default_page_builder );

	return $default_page_builder;
}

function cardealer_get_social_share_links( $post ) {
	global $car_dealer_options;

	$social_share_links = array();
	$social_share_type  = ( $car_dealer_options && isset( $car_dealer_options['social_share_type'] ) && ! empty( $car_dealer_options['social_share_type'] ) && in_array( $car_dealer_options['social_share_type'], array( 'modern', 'legacy' ), true ) ) ? $car_dealer_options['social_share_type'] : 'legacy';
	$pgs_social_share       = new PGS_Social_Share( $post );
	$share_links            = $pgs_social_share->get_links();

	if ( 'legacy' === $social_share_type ) {
		$social_share_links = array_filter( $share_links, function( $data, $media ) {
			global $car_dealer_options;
			$option_key = "{$media}_share";
			return (
				( isset( $data['legacy'] ) && filter_var( $data['legacy'], FILTER_VALIDATE_BOOLEAN ) )
				&& ( isset( $car_dealer_options[ $option_key ] ) && filter_var( $car_dealer_options[ $option_key ], FILTER_VALIDATE_BOOLEAN ) )
			);
		}, ARRAY_FILTER_USE_BOTH );
	} else {
		$selected_social_medias = ( $car_dealer_options && isset( $car_dealer_options['social_share_medias'] ) && ! empty( $car_dealer_options['social_share_medias'] ) ) ? $car_dealer_options['social_share_medias'] : array();

		foreach ( $selected_social_medias as $media_k => $media_v ) {
			if ( filter_var( $media_v, FILTER_VALIDATE_BOOLEAN ) && isset( $share_links[ $media_k ] ) && ! empty( $share_links[ $media_k ] ) ) {
				$social_share_links[ $media_k ] = $share_links[ $media_k ];
			}
		}
	}

	return $social_share_links;
}

function cardealer_get_blog_layout() {
	global $car_dealer_options;

	$blog_layout = 'classic';

	if (
		isset( $car_dealer_options['blog_layout'] )
		&& ! empty( $car_dealer_options['blog_layout'] )
		&& in_array( $car_dealer_options['blog_layout'], array( 'classic', 'masonry' ) )
	) {
		$blog_layout = $car_dealer_options['blog_layout'];
	}

	return $blog_layout;
}

function cardealer_mobile_native_share_feature_enabled() {
	$enabled = false;
	$enabled = apply_filters( 'cardealer_mobile_native_share_feature_enabled', $enabled );
	$enabled = filter_var( $enabled, FILTER_VALIDATE_BOOLEAN );
	return $enabled;
}

function cardealer_mobile_native_share_enabled() {
	global $car_dealer_options;

	$feature_enabled = cardealer_mobile_native_share_feature_enabled();
	$enabled         = false;

	$native_share_opt = isset( $car_dealer_options['mobile_native_share'] ) ? $car_dealer_options['mobile_native_share'] : false;
	$native_share_opt = filter_var( $native_share_opt, FILTER_VALIDATE_BOOLEAN );

	if ( $feature_enabled && $native_share_opt ) {
		$enabled = true;
	}

	$enabled = apply_filters( 'cardealer_mobile_native_share_enabled', $enabled );

	return $enabled;
}

function cardealer_search_post_types() {

	$types = array(
		'post' => esc_html__( 'Posts', 'cardealer' ),
		'cars' => esc_html__( 'Vehicles', 'cardealer' ),
	);

	$types = apply_filters( 'cardealer_search_post_types', $types );

	$types = array_filter( $types, function( $value, $key ) {
		return post_type_exists( $key );
	}, ARRAY_FILTER_USE_BOTH );

	$types = array_merge(
		array(
			'all'  => esc_html__( 'All', 'cardealer' ),
		),
		$types
	);

	return $types;
}

function cardealer_search_post_type() {
	global $car_dealer_options;

	$type = 'all';

	if ( ( isset( $car_dealer_options['search_content_type'] ) && ! empty( $car_dealer_options['search_content_type'] ) ) && post_type_exists( $car_dealer_options['search_content_type'] ) ) {
		$type = $car_dealer_options['search_content_type'];
	}

	$type = ( 'all' === $type ) ? 'any' : $type;

	return $type;
}

/**
 * Get post by title by.
 */
function cardealer_get_page_by_title( $title ) {
	$posts = get_posts(
		array(
			'post_type'              => 'page',
			'title'                  => $title,
			'post_status'            => 'all',
			'numberposts'            => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby'                => 'post_date ID',
			'order'                  => 'ASC',
		)
	);

	if ( ! empty( $posts ) ) {
		$page_got_by_title = $posts[0];
	} else {
		$page_got_by_title = null;
	}

	return $page_got_by_title;
}

function cardealer_is_inventory_page_edit_mode() {
	$options   = get_option( 'car_dealer_options' );
	$edit_mode = false;

	if (
		// Loaded in iframe.
		isset( $_SERVER['HTTP_SEC_FETCH_DEST'] ) && 'iframe' === $_SERVER['HTTP_SEC_FETCH_DEST']

		// Elementor or WPBakery editor.
		&& (
			// Elementor editor.
			( isset( $_GET['elementor-preview'] ) && (int) $options['cars_inventory_page'] === (int) $_GET['elementor-preview'] )
			// WPBakery editor.
			|| (
				( isset( $_GET['vc_editable'] ) && 'true' === $_GET['vc_editable'] )
				&& ( isset( $_GET['vc_post_id'] ) && (int) $options['cars_inventory_page'] === (int) $_GET['vc_post_id'] )
			)
		)
	) {
		$edit_mode = true;
	}
	return $edit_mode;
}

/**
 * Check template built-with.
 *
 * @param string|int $post_id Post ID.
 * @return string
 */
function cardealer_post_edited_with( $post_id = null ) {
	$built_with = false;

	$post = get_post( $post_id );
	if ( $post ) {
		$built_with_elementor_meta = get_post_meta( $post->ID, '_elementor_edit_mode', true );
		$built_with_wpbakery_meta  = get_post_meta( $post->ID, '_wpb_vc_js_status', true );
		$active_builders           = cardealer_get_active_page_builders();

		if ( $built_with_elementor_meta && 'builder' === $built_with_elementor_meta && in_array( 'elementor', $active_builders, true ) ) {
			$built_with = 'elementor';
		} elseif ( $built_with_wpbakery_meta && true === filter_var( $built_with_wpbakery_meta, FILTER_VALIDATE_BOOLEAN ) && in_array( 'wpbakery', $active_builders, true ) ) {
			$built_with = 'wpbakery';
		}
	}

	return $built_with;
}
