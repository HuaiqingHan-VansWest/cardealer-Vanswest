<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Dynamic css file
 *
 * @package cardealer
 */

get_template_part( 'includes/color_scheme_selectors' );
get_template_part( 'includes/dynamic_css_helper' );

if ( ! function_exists( 'cardealer_output_css' ) ) {
	/**
	 * Css output
	 */
	function cardealer_output_css() {
		global $car_dealer_options;

		$site_color_scheme_custom           = isset( $car_dealer_options['site_color_scheme_custom'] ) ? $car_dealer_options['site_color_scheme_custom'] : '';
		$site_color_scheme_custom_secondary = isset( $car_dealer_options['site_color_scheme_custom_secondary'] ) ? $car_dealer_options['site_color_scheme_custom_secondary'] : '';
		$site_color_scheme_custom_tertiary  = isset( $car_dealer_options['site_color_scheme_custom_tertiary'] ) ? $car_dealer_options['site_color_scheme_custom_tertiary'] : '';

		$parsed_css = cardealer_dynamic_css( $site_color_scheme_custom, $site_color_scheme_custom_secondary, $site_color_scheme_custom_tertiary );
		if ( ! empty( $parsed_css ) ) {
			wp_add_inline_style( 'cardealer-main-responsive', $parsed_css );
		}
	}
}
add_action( 'cardealer_assets_before_style_enqueue', 'cardealer_output_css', 10, 2 );

if ( ! function_exists( 'cardealer_dynamic_css' ) ) {
	/**
	 * Dynamic css
	 *
	 * @param string $site_color_scheme_custom .
	 * @param string $site_color_scheme_custom_secondary .
	 * @param string $site_color_scheme_custom_tertiary .
	 */
	function cardealer_dynamic_css( $site_color_scheme_custom = '', $site_color_scheme_custom_secondary = '', $site_color_scheme_custom_tertiary = '' ) {
		global $post, $car_dealer_options, $cardealer_color_scheme_selectors, $cardealer_color_scheme_selectors_secondary, $cardealer_color_scheme_selectors_tertiary, $typography_body, $elements, $font_properties, $font_properties_main, $sub_heading_font_properties;

		if ( is_404() ) {
			$post_id = 0;
		} else {
			if ( isset( $post ) ) {
				$post_id = $post->ID;
			} else {
				$post_id = 0;
			}
		}

		$dynamic_css = array();

		$back_top_type = isset( $car_dealer_options['back_top_type'] ) ? $car_dealer_options['back_top_type'] : 'default';

		// Back to top hover image.
		if ( isset( $car_dealer_options['back_to_top_hover_image']['url'] ) && ! empty( $car_dealer_options['back_to_top_hover_image']['url'] ) && 'custom' === $back_top_type ) {
			if ( isset( $car_dealer_options['back_to_top_hover_image_width']['width'] ) ) {
				$hover_image_width = isset( $car_dealer_options['back_to_top_hover_image_width']['width'] ) ? $car_dealer_options['back_to_top_hover_image_width']['width'] : '85px';
				$dynamic_css['.car-top']['width'] = $hover_image_width;
			}
			$dynamic_css['.car-top.back_to_top_type-custom:before,.car-top.back_to_top_type-custom.car-run:before']['background-image'] = "url(" . $car_dealer_options['back_to_top_hover_image']['url'] . ")";
		}

		// Site Layout CSS.
		$fixed_width     = '1240';
		$container_width = '1170';
		$auto_padding    = ( $fixed_width - $container_width ) / 2;
		$auto_margin     = ( $auto_padding ) + 15;

		$dynamic_css['.site-layout-boxed #page,.site-layout-framed #page,.site-layout-rounded #page']['max-width']                   = "{$fixed_width}px";
		$dynamic_css['.site-layout-boxed .vc_row[data-vc-full-width="true"]:not([data-vc-stretch-content="true"])']['padding-right'] = "{$auto_padding}px !important";
		$dynamic_css['.site-layout-boxed .vc_row[data-vc-full-width="true"]:not([data-vc-stretch-content="true"])']['padding-left']  = "{$auto_padding}px !important";
		$dynamic_css['.site-layout-boxed .vc_row[data-vc-full-width="true"]']['margin-left']  = "-{$auto_margin}px !important";
		$dynamic_css['.site-layout-boxed .vc_row[data-vc-full-width="true"]']['margin-right'] = "-{$auto_margin}px !important";

		/****************************
			HEADER CSS STARTS
		*************************** */
		// Body background settings.
		if ( isset( $car_dealer_options['body_background_type'] ) && ! empty( $car_dealer_options['body_background_type'] ) ) {
			if ( ( $car_dealer_options['page_layout'] ) === 'boxed' || 'framed' === $car_dealer_options['page_layout'] ) {
				if ( 'body_color' === $car_dealer_options['body_background_type'] ) {
					if ( isset( $car_dealer_options['body_background_color'] ) && ! empty( $car_dealer_options['body_background_color'] ) ) {
						$dynamic_css['body']['background-color'] = $car_dealer_options['body_background_color'];
					}
				} else {
					if ( isset( $car_dealer_options['body_background_img']['background-image'] ) && ! empty( $car_dealer_options['body_background_img']['background-image'] ) ) {
						$body_background_img_url = $car_dealer_options['body_background_img']['background-image'];
					}
					if ( isset( $car_dealer_options['body_background_img']['background-position'] ) && ! empty( $car_dealer_options['body_background_img']['background-position'] ) ) {
						$body_background_img_position = $car_dealer_options['body_background_img']['background-position'];
					}
					if ( isset( $car_dealer_options['body_background_img']['background-attachment'] ) && ! empty( $car_dealer_options['body_background_img']['background-attachment'] ) ) {
						$body_background_img_attachment = $car_dealer_options['body_background_img']['background-attachment'];
					}
					if ( isset( $car_dealer_options['body_background_img']['background-size'] ) && ! empty( $car_dealer_options['body_background_img']['background-size'] ) ) {
						$body_background_img_size = $car_dealer_options['body_background_img']['background-size'];
					}
					if ( isset( $car_dealer_options['body_background_img']['background-repeat'] ) && ! empty( $car_dealer_options['body_background_img']['background-repeat'] ) ) {
						$body_background_img_repeat = $car_dealer_options['body_background_img']['background-repeat'];
					}
					if ( isset( $body_background_img_url ) && ! empty( $body_background_img_url ) ) {
						$dynamic_css['body']['background-image'] = 'url(\'' . $body_background_img_url . '\')';
					}
					if ( isset( $body_background_img_position ) && ! empty( $body_background_img_position ) ) {
						$dynamic_css['body']['background-position'] = $body_background_img_position;
					}
					if ( isset( $body_background_img_attachment ) && ! empty( $body_background_img_attachment ) ) {
						$dynamic_css['body']['background-attachment'] = $body_background_img_attachment;
					}
					if ( isset( $body_background_img_size ) && ! empty( $body_background_img_size ) ) {
						$dynamic_css['body']['background-size'] = $body_background_img_size;
					}
					if ( isset( $body_background_img_repeat ) && ! empty( $body_background_img_repeat ) ) {
						$dynamic_css['body']['background-repeat'] = $body_background_img_repeat;
					}
				}
			}
		}
		/**
		 * TYPOGRAPHY STARTS
		 * Generate css for font-family added from theme options
		 * BODY FONTS
		 */
		$font_element_array = $elements['heading'];
		array_push( $font_element_array, $typography_body );

		foreach ( $font_properties_main as $property ) {
			if ( isset( $car_dealer_options['opt-typography-body'][ $property ] ) && ! empty( $car_dealer_options['opt-typography-body'][ $property ] ) ) {
				if ( 'font-family' === $property ) {
					if ( in_array( 'font-backup', $font_properties_main ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
						if ( isset( $car_dealer_options['opt-typography-body'][ $property ] ) && $car_dealer_options['opt-typography-body'][ $property ] ) {
							$opt_typography_body = $car_dealer_options['opt-typography-body'][ $property ];
							if ( isset( $car_dealer_options['opt-typography-body'][ 'font-backup' ] ) && $car_dealer_options['opt-typography-body'][ 'font-backup' ] ) {
								$opt_typography_body .= ', ' . $car_dealer_options['opt-typography-body']['font-backup'];
							}
							if ( $opt_typography_body ) {
								$dynamic_css[ implode( ',', $font_element_array ) ][ $property ] = $opt_typography_body;
							}
						}
					}
				} elseif ( 'font-backup' === $property ) {
					continue;
				} else {
					$dynamic_css[ implode( ',', $font_element_array ) ][ $property ] = $car_dealer_options['opt-typography-body'][ $property ];
				}
			}
		}

		// HEADING TAGS.
		$tag_id = 1;
		foreach ( $elements['heading'] as $tag ) {
			foreach ( $font_properties_main as $property ) {
				if ( isset( $car_dealer_options[ 'opt-typography-h' . $tag_id ][ $property ] ) && ! empty( $car_dealer_options[ 'opt-typography-h' . $tag_id ][ $property ] ) ) {
					if ( 'font-family' === $property ) {
						if ( in_array( 'font-backup', $font_properties_main ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
							if ( isset( $car_dealer_options[ 'opt-typography-h' . $tag_id ][ $property ] ) && $car_dealer_options[ 'opt-typography-h' . $tag_id ][ $property ] ) {
								$opt_typography_h = $car_dealer_options[ 'opt-typography-h' . $tag_id ][ $property ];
								if ( isset( $car_dealer_options[ 'opt-typography-h' . $tag_id ]['font-backup'] ) && $car_dealer_options[ 'opt-typography-h' . $tag_id ]['font-backup'] ) {
									$opt_typography_h .= ', ' . $car_dealer_options[ 'opt-typography-h' . $tag_id ]['font-backup'];
								}
								if ( $opt_typography_h ) {
									$dynamic_css[ $tag ][ $property ] = $opt_typography_h;
								}
							}
						}
					} elseif ( 'font-backup' === $property ) {
						continue;
					} else {
						$dynamic_css[ $tag ][ $property ] = $car_dealer_options[ 'opt-typography-h' . $tag_id ][ $property ];
					}
				}
			}
			$tag_id++;
		}
		// TYPOGRAPHY ENDS .
		/**
		 * HEADER CSS STARTS
		 * Logo Font Settings
		 */
		if ( isset( $car_dealer_options['logo_type'] ) && 'text' === $car_dealer_options['logo_type'] ) {
			if ( isset( $car_dealer_options['logo_font'] ) ) {
				foreach ( $font_properties as $property ) {
					if ( isset( $car_dealer_options['logo_font'][ $property ] ) && ! empty( $car_dealer_options['logo_font'][ $property ] ) ) {
						if ( 'font-family' === $property ) {
							if ( in_array( 'font-backup', $font_properties ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
								if ( isset( $car_dealer_options['logo_font'][ $property ] ) && $car_dealer_options['logo_font'][ $property ] ) {
									$opt_logo_font = $car_dealer_options['logo_font'][ $property ];
									if ( isset( $car_dealer_options['logo_font']['font-backup'] ) && $car_dealer_options['logo_font']['font-backup'] ) {
										$opt_logo_font .= ', ' . $car_dealer_options['logo_font']['font-backup'];
									}
									if ( $opt_logo_font ) {
										$dynamic_css[ $elements['logo_text'] ][ $property ] = $opt_logo_font;
									}
								}
							}
						} elseif ( 'font-backup' === $property ) {
							continue;
						} else {
							$dynamic_css[ $elements['logo_text'] ][ $property ] = $car_dealer_options['logo_font'][ $property ];
						}
					}
				}
			}

			// Mobile Logo Font Settings.
			$mobile_font_properties = array( 'font-size', 'line-height' );
			if ( isset( $car_dealer_options['mobile_logo_font'] ) ) {
				foreach ( $font_properties as $property ) {
					if ( isset( $car_dealer_options['mobile_logo_font'][ $property ] ) && ! empty( $car_dealer_options['mobile_logo_font'][ $property ] ) ) {
						$dynamic_css[ $elements['mobile_logo_text'] ][ $property ] = $car_dealer_options['mobile_logo_font'][ $property ];
					}
				}
			}
		}

		if ( isset( $car_dealer_options['logo_type'] ) && 'image' === $car_dealer_options['logo_type'] ) {
			if ( wp_is_mobile() && isset( $car_dealer_options['mobile_logo_height'] ) && ! empty( $car_dealer_options['mobile_logo_height'] ) ) {
				$dynamic_css['.site-logo']['height'] = $car_dealer_options['mobile_logo_height']['height'];
				$dynamic_css['.site-logo img']['height'] = $car_dealer_options['mobile_logo_height']['height'];
				if ( class_exists( 'WooCommerce' ) ) {
					$dynamic_css['.woocommerce-page .site-logo, .woocommerce .site-logo']['height'] = $car_dealer_options['mobile_logo_height']['height'];
				}
			} elseif ( isset( $car_dealer_options['logo_max_height'] ) && ! empty( $car_dealer_options['logo_max_height'] ) ) {
				$dynamic_css['.site-logo']['height'] = $car_dealer_options['logo_max_height']['height'];
				$dynamic_css['.site-logo img']['height'] = $car_dealer_options['logo_max_height']['height'];
				if ( class_exists( 'WooCommerce' ) ) {
					$dynamic_css['.woocommerce-page .site-logo, .woocommerce .site-logo']['height'] = $car_dealer_options['logo_max_height']['height'];
				}
			}
		}

		// Inner Page header height.
		if ( isset( $car_dealer_options['pageheader_height'] ) && ! empty( $car_dealer_options['pageheader_height'] ) ) {
			$header_height = $car_dealer_options['pageheader_height'];
		}
		if ( wp_is_mobile() && isset( $car_dealer_options['pageheader_height_mobile'] ) && ! empty( $car_dealer_options['pageheader_height_mobile'] ) ) {
			$header_height = $car_dealer_options['pageheader_height_mobile'];
		}

		global $wp_query;
		if ( is_page() || is_home() || is_single() ) {
			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			} elseif ( isset( $wp_query->post->ID ) ) {
				$post_id = $wp_query->post->ID;
			}
		}
		if ( is_archive() ) {
			$post_id = cardealer_get_current_post_id();
		}
		if ( isset( $post_id ) && function_exists( 'get_field' ) ) {
			$page_header_height = get_field( 'page_header_height', $post_id );
			if ( $page_header_height ) {
				$header_height = $page_header_height;
			}
		}
		if ( isset( $header_height ) ) {
			$dynamic_css[ $elements['inner_header'] ]['height'] = $header_height . 'px';
		}

			// Sticky Logo.
		if ( isset( $car_dealer_options['sticky_header'] ) && ( '1' === (string) $car_dealer_options['sticky_header'] ) ) {

			if ( wp_is_mobile() && isset( $car_dealer_options['mobile_logo_max_height_sticky_header'] ) && ! empty( $car_dealer_options['mobile_logo_max_height_sticky_header'] ) ) {
				if ( class_exists( 'WooCommerce' ) ) {
					$dynamic_css['.woocommerce-page .sticky-logo, .woocommerce .sticky-logo']['height'] = $car_dealer_options['mobile_logo_max_height_sticky_header']['height'];
				}
				$dynamic_css['.sticky-logo']['height'] = $car_dealer_options['mobile_logo_max_height_sticky_header']['height'];
				if ( isset( $car_dealer_options['sticky_logo_font']['color'] ) && ! empty( $car_dealer_options['sticky_logo_font']['color'] ) ) {
					$dynamic_css['.sticky-logo-text']['color'] = $car_dealer_options['sticky_logo_font']['color'];
				}
				if ( isset( $car_dealer_options['sticky_logo_font']['font-size'] ) && ! empty( $car_dealer_options['sticky_logo_font']['font-size'] ) ) {
					$dynamic_css['.sticky-logo-text']['font-size'] = $car_dealer_options['sticky_logo_font']['font-size'];
				}
			} else {
				if ( class_exists( 'WooCommerce' ) ) {
					$dynamic_css['.woocommerce-page .sticky-logo, .woocommerce .sticky-logo']['height'] = $car_dealer_options['logo_max_height_sticky_header']['height'];
				}
					$dynamic_css['.sticky-logo']['height'] = $car_dealer_options['logo_max_height_sticky_header']['height'];

				if ( isset( $car_dealer_options['sticky_logo_font']['font-size'] ) && ! empty( $car_dealer_options['sticky_logo_font']['font-size'] ) ) {
					$dynamic_css['.sticky-logo-text']['font-size'] = $car_dealer_options['sticky_logo_font']['font-size'];
				}
			}
			// Sticky logo font color.
			if ( isset( $car_dealer_options['sticky_logo_font']['color'] ) && ! empty( $car_dealer_options['sticky_logo_font']['color'] ) ) {
					$dynamic_css['.sticky-logo-text']['color'] = $car_dealer_options['sticky_logo_font']['color'];
			}
		}

			// Generate Banner CSS from Options.
			// Header banner CSS.
			$banner_type = isset( $car_dealer_options['banner_type'] ) ? $car_dealer_options['banner_type'] : '';
		if ( empty( $banner_type ) ) {
			$banner_type = 'image';
		}

		if ( 'image' === $banner_type ) {
			$banner_image_bg_url     = CARDEALER_URL . '/images/default/page-header-bg.jpg';
			$banner_image_position   = 'center center';
			$banner_image_attachment = 'scroll';
			$banner_image_size       = 'cover';
			$banner_image_repeat     = 'no-repeat';
			if ( isset( $car_dealer_options['banner_image_bg_custom'] ) && ! empty( $car_dealer_options['banner_image_bg_custom'] ) ) {
				if ( isset( $car_dealer_options['banner_image_bg_custom']['background-image'] ) && ! empty( $car_dealer_options['banner_image_bg_custom']['background-image'] ) ) {
					$banner_image_bg_url = $car_dealer_options['banner_image_bg_custom']['background-image'];
					if (
						isset( $car_dealer_options['banner_image_bg_custom']['media'] )
						&& isset( $car_dealer_options['banner_image_bg_custom']['media']['id'] )
						&& '' !== $car_dealer_options['banner_image_bg_custom']['media']['id'] )
					{
						$banner_image_bg_url = $banner_image_bg_url . '?id=' . $car_dealer_options['banner_image_bg_custom']['media']['id'];
					}
				}

				if ( isset( $car_dealer_options['banner_image_bg_custom']['background-position'] ) && ! empty( $car_dealer_options['banner_image_bg_custom']['background-position'] ) ) {
					$banner_image_position = $car_dealer_options['banner_image_bg_custom']['background-position'];
				}
				if ( isset( $car_dealer_options['banner_image_bg_custom']['background-attachment'] ) && ! empty( $car_dealer_options['banner_image_bg_custom']['background-attachment'] ) ) {
					$banner_image_attachment = $car_dealer_options['banner_image_bg_custom']['background-attachment'];
				}
				if ( isset( $car_dealer_options['banner_image_bg_custom']['background-size'] ) && ! empty( $car_dealer_options['banner_image_bg_custom']['background-size'] ) ) {
					$banner_image_size = $car_dealer_options['banner_image_bg_custom']['background-size'];
				}
				if ( isset( $car_dealer_options['banner_image_bg_custom']['background-repeat'] ) && ! empty( $car_dealer_options['banner_image_bg_custom']['background-repeat'] ) ) {
					$banner_image_repeat = $car_dealer_options['banner_image_bg_custom']['background-repeat'];
				}
			}
			$dynamic_css['.header_intro_bg-image']['background-image']      = 'url(\'' . $banner_image_bg_url . '\')';
			$dynamic_css['.header_intro_bg-image']['background-position']   = $banner_image_position;
			$dynamic_css['.header_intro_bg-image']['background-attachment'] = $banner_image_attachment;
			$dynamic_css['.header_intro_bg-image']['background-size']       = $banner_image_size;
			$dynamic_css['.header_intro_bg-image']['background-repeat']     = $banner_image_repeat;

			if ( ! empty( $car_dealer_options['banner_image_opacity'] ) && 'custom' === $car_dealer_options['banner_image_opacity'] ) {
				$banner_image_opacity_custom_color = $car_dealer_options['banner_image_opacity_custom_color'];
				if ( ! empty( $banner_image_opacity_custom_color ) ) {
					$dynamic_css['.header_intro_opacity::before']['background-color'] = isset( $banner_image_opacity_custom_color['rgba'] ) ? $banner_image_opacity_custom_color['rgba'] : cardealer_hex2rgba( $banner_image_opacity_custom_color['color'], $banner_image_opacity_custom_color['alpha'] );
				}
			}
		} elseif ( 'color' === $banner_type ) {
			if ( ! empty( $car_dealer_options['banner_image_color'] ) ) {
				$dynamic_css['.header_intro_bg-color']['background-color'] = $car_dealer_options['banner_image_color'];
			} else {
				$dynamic_css['.header_intro_bg-color']['background-color'] = '#000000';
			}
		} elseif ( 'video' === $banner_type ) {
			$banner_video_opacity = $car_dealer_options['banner_video_opacity'];
			if ( ! empty( $banner_video_opacity ) && 'custom' === $banner_video_opacity ) {
				$banner_video_opacity_custom_color = $car_dealer_options['banner_video_opacity_custom_color'];
				if ( ! empty( $banner_video_opacity_custom_color ) ) {
					$dynamic_css['.header_intro_opacity::before']['background-color'] = isset( $banner_video_opacity_custom_color['rgba'] ) ? $banner_video_opacity_custom_color['rgba'] : cardealer_hex2rgba( $banner_video_opacity_custom_color['color'], $banner_video_opacity_custom_color['alpha'] );
				}
			}
		}

			$header_type = ( ! empty( $car_dealer_options['header_type'] ) ) ? $car_dealer_options['header_type'] : 'default';
			// background color.
		if ( ! empty( $car_dealer_options['header_color_settings'] ) && 'custom' === $car_dealer_options['header_color_settings'] ) {
			if ( isset( $car_dealer_options['header_background_color'] ) && ! empty( $car_dealer_options['header_background_color'] ) ) {
				if ( 'boxed' === $header_type ) {
					$dynamic_css['header .header-boxed']['background-color'] = $car_dealer_options['header_background_color'];
				} else {
					$dynamic_css['#header']['background-color'] = $car_dealer_options['header_background_color'];
				}
			}
		}

			// text color.
		if ( ! empty( $car_dealer_options['header_color_settings'] ) && 'custom' === $car_dealer_options['header_color_settings'] ) {
			if ( isset( $car_dealer_options['header_text_color'] ) && ! empty( $car_dealer_options['header_text_color'] ) ) {
				$dynamic_css['header, header a, #header .menu-inner div > .row #primary-menu > li.menu-item > a, #header .menu .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > a, #header .menu-inner #mega-menu-primary-menu > li.menu-item .menu-item-woocommerce-cart-wrapper > a, #header #mega-menu-primary-menu > li > .searchform .search > a, #header #mega-menu-wrap-primary-menu .mega-menu-toggle .mega-toggle-block-1:before,.menu-logo .site-description, #header #primary-menu > li > .searchform .search > a']['color'] = $car_dealer_options['header_text_color'];
				$dynamic_css['.mega-menu .menu-mobile-collapse-trigger:before, .mega-menu .menu-mobile-collapse-trigger:after, .mega-menu  .menu-mobile-collapse-trigger span']['background-color'] = $car_dealer_options['header_text_color'];
			}
		}

			// Header Link color.
		if ( ! empty( $car_dealer_options['header_color_settings'] ) && 'custom' === $car_dealer_options['header_color_settings'] ) {
			if ( isset( $car_dealer_options['header_link_color'] ) && ! empty( $car_dealer_options['header_link_color'] ) ) {
				$dynamic_css['header a:hover, #header .menu-inner div > .row #primary-menu > li.menu-item:hover > a, #header .menu-inner div > .row #primary-menu > li.menu-item > a:hover, #header .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li:hover > a, #header .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > a:hover, #header .menu-inner #mega-menu-primary-menu > li.menu-item .menu-item-woocommerce-cart-wrapper > a:hover, #header .menu-inner #mega-menu-primary-menu > li > .searchform .search > a:hover, #header .menu .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li.mega-current-menu-item  > a, #header .menu .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li.mega-current-menu-ancestor > a, #header .menu .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > ul.mega-sub-menu li.mega-current-menu-ancestor > a, #header .menu .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > ul.mega-sub-menu li.mega-current-menu-item > a, #header .menu .menu-inner div > .row #primary-menu > li.current-menu-ancestor > a, #header .menu .menu-inner div > .row #primary-menu > li > .drop-down-multilevel .current-menu-item a, #header .menu .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > ul > li a:hover, #header .mega-menu .drop-down-multilevel li:hover > a, .mega-menu .drop-down-multilevel li:hover > a i.fa, #header .menu .menu-inner div > .row #primary-menu > li.current-menu-item > a, #header #mega-menu-wrap-primary-menu #mega-menu-primary-menu > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus, #header #mega-menu-wrap-primary-menu #mega-menu-primary-menu > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item > a.mega-menu-link:focus, #header .menu-inner div > .row #primary-menu > li > .menu-item-woocommerce-cart-wrapper > a:hover, #header .menu-inner div > .row #primary-menu > li > .searchform .search > a:hover, #header.default-header .topbar li a:hover']['color'] = $car_dealer_options['header_link_color'];
				$dynamic_css['#header .mega-menu .cart-contents .woo-cart-details.count, #header .mega-menu .menu-item-compare .compare-details.count, #header .menu .menu-inner div > .row #primary-menu > li.menu-item.cdfs-add-vehicle > a']['background-color'] = $car_dealer_options['header_link_color'];
			}
		}

			// Sticky Header.
		if ( isset( $car_dealer_options['sticky_header'] ) && '1' === (string) $car_dealer_options['sticky_header'] ) {
			if ( isset( $car_dealer_options['header_color_settings'] ) && 'custom' === $car_dealer_options['header_color_settings'] ) {
				if ( isset( $car_dealer_options['sticky_header_background_color'] ) && ! empty( $car_dealer_options['sticky_header_background_color'] ) ) {
					if ( 'boxed' === $header_type ) {
						$dynamic_css['header .mega-menu.desktopTopFixed .menu-list-items, header .mega-menu.mobileTopFixed .menu-list-items']['background-color'] = $car_dealer_options['sticky_header_background_color'];
					} else {
						$dynamic_css['header .mega-menu.desktopTopFixed .menu-list-items, header .mega-menu.mobileTopFixed .menu-list-items']['background-color'] = $car_dealer_options['sticky_header_background_color'];
					}
				}

				// Sticky Header Text color.
				if ( isset( $car_dealer_options['sticky_header_text_color'] ) && ! empty( $car_dealer_options['sticky_header_text_color'] ) ) {
					$dynamic_css['header .desktopTopFixed  a, header .mobileTopFixed  a, #header .mobileTopFixed .menu-inner div > .row #primary-menu > li.menu-item > a, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li.menu-item > a, #header .mobileTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > a, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > a, #header .desktopTopFixed .menu-inner #mega-menu-primary-menu > li.menu-item .menu-item-woocommerce-cart-wrapper > a, #header .desktopTopFixed #mega-menu-primary-menu > li > .searchform .search > a, #header .mobileTopFixed #mega-menu-wrap-primary-menu .mega-menu-toggle .mega-toggle-block-1:before, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li > .menu-item-woocommerce-cart-wrapper > a, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li > .searchform .search > a']['color'] = $car_dealer_options['sticky_header_text_color'];
					$dynamic_css['.mega-menu.mobileTopFixed .menu-mobile-collapse-trigger:before, .mega-menu.mobileTopFixed  .menu-mobile-collapse-trigger:after, .mega-menu.mobileTopFixed  .menu-mobile-collapse-trigger span']['background-color'] = $car_dealer_options['sticky_header_text_color'];
				}

				// Sticky Header Link color.
				if ( isset( $car_dealer_options['sticky_header_link_color'] ) && ! empty( $car_dealer_options['sticky_header_link_color'] ) ) {
					$dynamic_css['header .desktopTopFixed  a:hover, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li.menu-item > a:hover, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li.mega-current-menu-item  > a, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > a:hover, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > ul > li a:hover, #header .desktopTopFixed .menu-inner #mega-menu-primary-menu > li.menu-item .menu-item-woocommerce-cart-wrapper > a:hover, #header .desktopTopFixed #mega-menu-primary-menu > li > .searchform .search > a:hover, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li.mega-current-menu-ancestor  > a, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > ul.mega-sub-menu li.mega-current-menu-ancestor > a, #header .desktopTopFixed .menu-inner div > .row .mega-menu-wrap #mega-menu-primary-menu > li > ul.mega-sub-menu li.mega-current-menu-item a, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li.current-menu-ancestor > a, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li.current-menu-item > a, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li.current-menu-ancestor .drop-down-multilevel li.current-menu-item > a, #header .desktopTopFixed #mega-menu-wrap-primary-menu #mega-menu-primary-menu > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus, #header .mobileTopFixed #mega-menu-wrap-primary-menu #mega-menu-primary-menu > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus, #header .desktopTopFixed #mega-menu-wrap-primary-menu #mega-menu-primary-menu > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item > a.mega-menu-link:focus,
						#header .mobileTopFixed #mega-menu-wrap-primary-menu #mega-menu-primary-menu > li.mega-menu-megamenu > ul.mega-sub-menu > li.mega-menu-item > a.mega-menu-link:focus, #header .mega-menu.desktopTopFixed .drop-down-multilevel li:hover > a, #header .mega-menu.mobileTopFixed .drop-down-multilevel li:hover > a, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li > .menu-item-woocommerce-cart-wrapper > a:hover, #header .desktopTopFixed .menu-inner div > .row #primary-menu > li > .searchform .search > a:hover, #header.default-header .topbar li a:hover']['color'] = $car_dealer_options['sticky_header_link_color'];
					$dynamic_css['#header .desktopTopFixed .mega-menu .cart-contents .woo-cart-details.count,
					#header .desktopTopFixed .mega-menu .menu-item-compare .compare-details.count']['background-color'] = $car_dealer_options['sticky_header_link_color'];
				}
			}
			// Sticky Header Height On Scroll.
			global $wp_query;
			if ( isset( $wp_query->post->ID ) && function_exists( 'get_field' ) ) {
				$post_id = is_home() ? get_option( 'page_for_posts' ) : $wp_query->post->ID;

				// avoid confliction of same name between post type and page name.
				if ( is_archive() ) {
					$post_id = cardealer_get_current_post_id();
				}
				$page_sticky_header_height = get_field( 'sticky_header_height', $post_id );
			}

			if ( isset( $page_sticky_header_height ) && ! empty( $page_sticky_header_height ) ) {
				$dynamic_css['header .menu .desktopTopFixed .menu-list-items, header .menu .mobileTopFixed .menu-list-items']['height'] = $page_sticky_header_height . 'px';
			} else {
				if ( isset( $car_dealer_options['header_height_on_scroll']['height'] ) && ! empty( $car_dealer_options['header_height_on_scroll']['height'] ) ) {
					$dynamic_css['header .menu .desktopTopFixed .menu-list-items, header .menu .mobileTopFixed .menu-list-items']['height'] = $car_dealer_options['header_height_on_scroll']['height'];
				}
			}
		}

		// Top bar.
		if ( isset( $car_dealer_options['top_bar'] ) && ( 1 === (int) $car_dealer_options['top_bar'] ) ) {
			if ( isset( $car_dealer_options['header_color_settings'] ) && ( 'custom' === $car_dealer_options['header_color_settings'] ) ) {
				// TopBar.
				if ( isset( $car_dealer_options['top_bar_background_color'] ) && ( ! empty( $car_dealer_options['top_bar_background_color'] ) ) ) {
					$dynamic_css[ 'body #header.' . $car_dealer_options['header_type'] . ' .topbar' ]['background-color'] = $car_dealer_options['top_bar_background_color'];
				}
				if ( isset( $car_dealer_options['top_bar_text_color'] ) && ( ! empty( $car_dealer_options['top_bar_text_color'] ) ) ) {
					$dynamic_css[ 'body #header.' . $car_dealer_options['header_type'] . ' .topbar, #header.' . $car_dealer_options['header_type'] . ' .topbar a, .topbar .top-promocode-box .form-control, .topbar .top-promocode-box button' ]['color'] = $car_dealer_options['top_bar_text_color'];
					$dynamic_css['.topbar .top-promocode-box .form-control, .topbar .top-promocode-box button']['border-color'] = $car_dealer_options['top_bar_text_color'];
				}

				// sticky topbar.
				if ( isset( $car_dealer_options['sticky_topbar'] ) && 'on' === $car_dealer_options['sticky_topbar'] ) {
					if ( isset( $car_dealer_options['sticky_top_bar_background_color'] ) && ( ! empty( $car_dealer_options['sticky_top_bar_background_color'] ) ) ) {
						$dynamic_css[ 'body #header.' . $car_dealer_options['header_type'] . ' .topbar_fixed' ]['background-color'] = $car_dealer_options['sticky_top_bar_background_color'];
					} else {
						$dynamic_css[ 'body #header.' . $car_dealer_options['header_type'] . ' .topbar_fixed' ]['background-color'] = 'transparent';
					}
					if ( isset( $car_dealer_options['sticky_top_bar_text_color'] ) && ( ! empty( $car_dealer_options['sticky_top_bar_text_color'] ) ) ) {
						$dynamic_css[ 'body #header.' . $car_dealer_options['header_type'] . ' .topbar_fixed, body #header.' . $car_dealer_options['header_type'] . '#header .topbar_fixed a, .topbar_fixed .top-promocode-box .form-control, .topbar_fixed .top-promocode-box button' ]['color'] = $car_dealer_options['sticky_top_bar_text_color'];
						$dynamic_css['.topbar_fixed .top-promocode-box .form-control, .topbar_fixed .top-promocode-box button']['border-color'] = $car_dealer_options['sticky_top_bar_text_color'];
					}
				}
			}
		}

		if ( function_exists( 'cdhl_plugin_active_status' ) && cdhl_plugin_active_status( 'cardealer-geo-fencing/cardealer-geo-fencing.php' ) ) {
			if ( isset( $car_dealer_options['cars-geo-fencing'] ) && ( '1' === $car_dealer_options['cars-geo-fencing'] ) ) {
				if ( isset( $car_dealer_options['geo_fencing_background_color'] ) && ( ! empty( $car_dealer_options['geo_fencing_background_color'] ) ) ) {
					$dynamic_css['div.geo-bar']['background-color'] = $car_dealer_options['geo_fencing_background_color'];
				}
			}
		}

		/****************************
			HEADER CSS ENDS
		*************************** */
		/****************************
			FOOTER CSS STARTS
		*************************** */
		$banner_type_footer = isset( $car_dealer_options['banner_type_footer'] ) ? $car_dealer_options['banner_type_footer'] : '';
		if ( empty( $banner_type_footer ) ) {
			$banner_type_footer = 'color';
		}
			// Footer title color.
		if ( isset( $car_dealer_options['footer_title_color'] ) && ! empty( $car_dealer_options['footer_title_color'] ) ) {
			$dynamic_css['.social-full a, footer .widgettitle, .footer-box .box-content h6, footer .widget.widget_rss ul li .rss-date']['color'] = $car_dealer_options['footer_title_color'];
			$dynamic_css['.social-full a i']['color'] = cardealer_hex2rgba( $car_dealer_options['footer_title_color'], 0.5 );
		}
			// Footer text color.
		if ( isset( $car_dealer_options['footer_text_color'] ) && ! empty( $car_dealer_options['footer_text_color'] ) ) {
			$dynamic_css['footer, .footer a, .footer input, footer p, footer ul li a, footer .textwidget ul li a, footer .widget ul li a,  footer span, footer footer .widget_recent_entries .recent-post-info a, footer .widget_recent_entries .recent-post-info span, footer .widget_recent_entries .recent-post-info a, footer ul li i, footer .address li']['color'] = $car_dealer_options['footer_text_color'];
		}
			// Footer link color.
		if ( isset( $car_dealer_options['footer_link_color'] ) && ! empty( $car_dealer_options['footer_link_color'] ) ) {
			$dynamic_css['footer cite, footer .address ul li i, footer .usefull-link ul li a i, footer .widget_recent_entries .recent-post-info i, footer .widget.widget_recent_comments ul li a, footer .widget.widget_rss ul li a, .widget ul li > a:hover, #footer .widget_recent_entries .recent-post-info a:hover, footer .widget ul li a:hover, footer .widget.widget_archive ul li:hover > a, .copyright-block a:hover']['color'] = $car_dealer_options['footer_link_color'];
		}
			// Footer banner.
		if ( 'image' === $banner_type_footer ) {
			$banner_image_bg_url_footer = CARDEALER_URL . '/images/default/page-footer-bg.jpg';
			if ( isset( $car_dealer_options['footer_background_img'] ) && ! empty( $car_dealer_options['footer_background_img'] ) ) {
				// background-image.
				if ( isset( $car_dealer_options['footer_background_img']['background-image'] ) && ! empty( $car_dealer_options['footer_background_img']['background-image'] ) ) {
					$banner_image_bg_url_footer = $car_dealer_options['footer_background_img']['background-image'];
					if ( isset( $car_dealer_options['footer_background_img']['media']['id'] ) && '' !== $car_dealer_options['footer_background_img']['media']['id'] ) {
						$banner_image_bg_url_footer = $banner_image_bg_url_footer . '?id=' . $car_dealer_options['footer_background_img']['media']['id'];
					}
				}

				// background-repeat.
				if ( isset( $car_dealer_options['footer_background_img']['background-repeat'] ) && ! empty( $car_dealer_options['footer_background_img']['background-repeat'] ) ) {
					$dynamic_css['.footer_bg-image']['background-repeat'] = $car_dealer_options['footer_background_img']['background-repeat'];
				}

				// background-size.
				if ( isset( $car_dealer_options['footer_background_img']['background-size'] ) && ! empty( $car_dealer_options['footer_background_img']['background-size'] ) ) {
					$dynamic_css['.footer_bg-image']['background-size'] = $car_dealer_options['footer_background_img']['background-size'];
				}

				// background-attachment.
				if ( isset( $car_dealer_options['footer_background_img']['background-attachment'] ) && ! empty( $car_dealer_options['footer_background_img']['background-attachment'] ) ) {
					$dynamic_css['.footer_bg-image']['background-attachment'] = $car_dealer_options['footer_background_img']['background-attachment'];
				}

				// background-position.
				if ( isset( $car_dealer_options['footer_background_img']['background-position'] ) && ! empty( $car_dealer_options['footer_background_img']['background-position'] ) ) {
					$dynamic_css['.footer_bg-image']['background-position'] = $car_dealer_options['footer_background_img']['background-position'];
				}
			}

				$dynamic_css['.footer_bg-image']['background-image'] = 'url(\'' . $banner_image_bg_url_footer . '\')';

				$banner_image_opacity_footer = $car_dealer_options['banner_image_opacity_footer'];
			if ( ! empty( $banner_image_opacity_footer ) && 'custom' === $banner_image_opacity_footer ) {
				$banner_image_opacity_custom_color_footer = $car_dealer_options['banner_image_opacity_custom_color_footer'];
				if ( ! empty( $banner_image_opacity_custom_color_footer ) ) {
					$dynamic_css['.footer_opacity::before']['background-color'] = isset( $banner_image_opacity_custom_color_footer['rgba'] ) ? $banner_image_opacity_custom_color_footer['rgba'] : cardealer_hex2rgba( $banner_image_opacity_custom_color_footer['color'], $banner_image_opacity_custom_color_footer['alpha'] );
				}
			} elseif ( ! empty( $banner_image_opacity_footer ) && 'none' !== $banner_image_opacity_footer ) {
				$dynamic_css['.footer_opacity::before']['background-color'] = $banner_image_opacity_footer;
			}
		} elseif ( 'color' === $banner_type_footer ) {
			if ( ! empty( $car_dealer_options['footer_background_footer'] ) ) {
				$dynamic_css['.footer_bg-color']['background-color'] = $car_dealer_options['footer_background_footer'];
			} else {
				$dynamic_css['.footer_bg-color']['background-color'] = '#000000';
			}
		}
			// COPYRIGHT CSS.
		if ( isset( $car_dealer_options['enable_copyright_footer'] ) && 'yes' === $car_dealer_options['enable_copyright_footer'] ) {

			// Background Color.
			if ( isset( $car_dealer_options['copyright_back_color'] ) && ! empty( $car_dealer_options['copyright_back_color'] ) ) {
				$dynamic_css['.copyright-block']['background-color'] = $car_dealer_options['copyright_back_color'];
			}

			// Text Color.
			if ( isset( $car_dealer_options['copyright_text_color'] ) && ! empty( $car_dealer_options['copyright_text_color'] ) ) {
				$dynamic_css['.copyright-block, .copyright-block a']['color'] = $car_dealer_options['copyright_text_color'];
			}

			// Opacity.
			$copyright_opacity = isset( $car_dealer_options['copyright_opacity'] ) ? $car_dealer_options['copyright_opacity'] : '';
			if ( ! empty( $copyright_opacity ) ) {
				$opacity_color = $copyright_opacity;
				if ( 'custom' === $copyright_opacity && ! empty( $car_dealer_options['copyright_opacity_custom_color'] ) ) {
					$opacity_color = isset( $car_dealer_options['copyright_opacity_custom_color']['rgba'] ) ? $car_dealer_options['copyright_opacity_custom_color']['rgba'] : cardealer_hex2rgba( $car_dealer_options['copyright_opacity_custom_color']['color'], $car_dealer_options['copyright_opacity_custom_color']['alpha'] );
				}
				$dynamic_css['.copyright-block::before']['background-color'] = $opacity_color;
			}
		}
			/****************************
				FOOTER CSS ENDS
			*************************** */

			// Generate Banner CSS from Singple Page.
		if ( is_page() || is_home() || is_single() || is_archive() ) {
			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' ); // If blog page.
			}

			if ( is_archive() ) {
				$post_id = cardealer_get_current_post_id();
			}

			$enable_custom_banner = get_post_meta( $post_id, 'enable_custom_banner', true );
			if ( $enable_custom_banner ) {
				// Unset data set from options.
				$banner_type = '';
				unset( $dynamic_css['.header_intro_bg-image'] );
				unset( $dynamic_css['.header_intro_opacity::before'] );
				unset( $dynamic_css['.header_intro_bg-color'] );

				$banner_type = get_post_meta( $post_id, 'banner_type', true );
				if ( empty( $banner_type ) ) {
					$banner_type = 'image';
				}
				if ( $banner_type && 'image' === $banner_type ) {
					// Default Image.

					$banner_image_bg_url     = ( ! empty( $banner_image_bg_url ) ) ? $banner_image_bg_url : CARDEALER_URL . '/images/default/page-header-bg.jpg';
					$banner_image_position   = 'center center';
					$banner_image_attachment = 'scroll';
					$banner_image_size       = 'cover';
					$banner_image_repeat     = 'no-repeat';

					if ( function_exists( 'get_field' ) ) { // banner_image_bg_custom.
						$banner_image_bg_custom  = get_field( 'banner_image_bg_custom', $post_id );
						$banner_image_position   = get_field( 'background_position', $post_id );
						$banner_image_attachment = get_field( 'background_attachment', $post_id );
						$banner_image_size       = get_field( 'background_size', $post_id );
						$banner_image_repeat     = get_field( 'background_repeat', $post_id );

					} else {
						$banner_image_bg_custom_raw = get_post_meta( $post_id, 'banner_image_bg_custom', true );
						$banner_image_position      = get_post_meta( $post_id, 'background_position', false );
						$banner_image_attachment    = get_post_meta( $post_id, 'background_attachment', false );
						$banner_image_size          = get_post_meta( $post_id, 'background_size', false );
						$banner_image_repeat        = get_post_meta( $post_id, 'background_repeat', false );
						if ( $banner_image_bg_custom_raw ) {
							$banner_image_bg_custom = cardealer_acf_get_attachment( $banner_image_bg_custom_raw );
						} else {
							$banner_image_bg_custom = false;
						}
					}
					if ( $banner_image_bg_custom ) {
						$banner_image_bg_url = ( is_array( $banner_image_bg_custom ) ) ? $banner_image_bg_custom['url'] : $banner_image_bg_custom;
					}
					$dynamic_css['.header_intro_bg-image']['background-image']      = 'url(\'' . $banner_image_bg_url . '\')';
					$dynamic_css['.header_intro_bg-image']['background-position']   = $banner_image_position;
					$dynamic_css['.header_intro_bg-image']['background-attachment'] = $banner_image_attachment;
					$dynamic_css['.header_intro_bg-image']['background-size']       = $banner_image_size;
					$dynamic_css['.header_intro_bg-image']['background-repeat']     = $banner_image_repeat;

					$background_opacity_color = get_post_meta( $post_id, 'background_opacity_color', true );
					if ( $background_opacity_color && 'custom' === $background_opacity_color ) {
						$banner_image_opacity_custom_color   = get_post_meta( $post_id, 'banner_image_opacity_custom_color', true );
						$banner_image_opacity_custom_opacity = get_post_meta( $post_id, 'banner_image_opacity_custom_opacity', true );
						if ( empty( $banner_image_opacity_custom_color ) ) {
							$banner_image_opacity_custom_color = '#191919';
						}
						if ( empty( $banner_image_opacity_custom_opacity ) ) {
							$banner_image_opacity_custom_opacity = .8;
						}
						$banner_color = cardealer_hex2rgba( $banner_image_opacity_custom_color, $banner_image_opacity_custom_opacity );
						$dynamic_css['.header_intro_opacity::before']['background-color'] = $banner_color;
					}
				} elseif ( $banner_type && 'color' === $banner_type ) {
					$banner_image_color = get_post_meta( $post_id, 'banner_image_color', true );
					if ( $banner_image_color ) {
						$dynamic_css['.header_intro_bg-color']['background-color'] = $banner_image_color;
					}
				} elseif ( $banner_type && 'video' === $banner_type ) {
					$video_background_opacity_color = get_post_meta( $post_id, 'video_background_opacity_color', true );
					if ( $video_background_opacity_color && 'custom' === $video_background_opacity_color ) {
						$banner_video_opacity_custom_color   = get_post_meta( $post_id, 'banner_video_opacity_custom_color', true );
						$banner_video_opacity_custom_opacity = get_post_meta( $post_id, 'banner_video_opacity_custom_opacity', true );
						if ( empty( $banner_video_opacity_custom_color ) ) {
							$banner_video_opacity_custom_color = '#191919';
						}
						if ( empty( $banner_video_opacity_custom_opacity ) ) {
							$banner_video_opacity_custom_opacity = .8;
						}
						$banner_color = cardealer_hex2rgba( $banner_video_opacity_custom_color, $banner_video_opacity_custom_opacity );
						$dynamic_css['.header_intro_opacity::before']['background-color'] = $banner_color;
					}
				}
			}
		}

			// Custom Color Scheme (Primary).
		if ( ! empty( $site_color_scheme_custom ) ) {
			foreach ( $cardealer_color_scheme_selectors as $colors_attr => $colors_selectors ) {
				if ( ! empty( $colors_attr ) && ! empty( $colors_selectors ) ) {
					if ( 'box-shadow' === $colors_attr ) {
						$dynamic_css[ $colors_selectors ][ $colors_attr ] = '-200px 0 0 ' . $site_color_scheme_custom['color'] . ' inset';
					} elseif ( 'color-imp' === $colors_attr ) {
						$dynamic_css[ $colors_selectors ]['color'] = $site_color_scheme_custom['color'] . ' !important';
					} elseif ( 'background-color' === $colors_attr ) {
						$dynamic_css[ $colors_selectors ][ $colors_attr ] = $site_color_scheme_custom['color'];
					} elseif ( 'background-imp' === $colors_attr ) {
						$dynamic_css[ $colors_selectors ]['background-color'] = $site_color_scheme_custom['color'] . ' !important';
					} elseif ( 'background' === $colors_attr ) {
						$dynamic_css[ $colors_selectors ][ $colors_attr ] = cardealer_hex2rgba( $site_color_scheme_custom['color'], $site_color_scheme_custom['alpha'] ) . ' !important';
					} else {
						$dynamic_css[ $colors_selectors ][ $colors_attr ] = $site_color_scheme_custom['color'];
					}
				}
			}
		}

			// Custom Color Scheme (Secondary).
		if ( ! empty( $site_color_scheme_custom_secondary ) ) {
			foreach ( $cardealer_color_scheme_selectors_secondary as $colors_attr => $colors_selectors ) {
				if ( ! empty( $colors_attr ) && ! empty( $colors_selectors ) ) {
					$dynamic_css[ $colors_selectors ][ $colors_attr ] = $site_color_scheme_custom_secondary;
				}
			}
		}

			// Custom Color Scheme (Tertiary).
		if ( ! empty( $site_color_scheme_custom_tertiary ) && 'transparent' !== $site_color_scheme_custom_tertiary ) {
			foreach ( $cardealer_color_scheme_selectors_tertiary as $colors_attr => $colors_selectors ) {
				if ( ! empty( $colors_attr ) && ! empty( $colors_selectors ) ) {
					$dynamic_css[ $colors_selectors ][ $colors_attr ] = $site_color_scheme_custom_tertiary;
				}
			}
		}

			$sold_car_img = ( isset( $car_dealer_options['sold_car_img']['url'] ) ) ? $car_dealer_options['sold_car_img']['url'] : '';
		if ( ! empty( $sold_car_img ) ) {
			$style = 'url(' . $sold_car_img . ') no-repeat 0 0;';
		} else {
			$style = 'url(' . CARDEALER_URL . '/images/sold-img.png) no-repeat 0 0';
		}
			$dynamic_css['.car-item .sold, .car-price .sold']['background'] = $style;

			$parsed_css = cardealer_generate_css_properties( $dynamic_css );
			return $parsed_css;
	}
}
