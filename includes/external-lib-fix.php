<?php
/**
 *
 * Bundle Plugins Hack
 * Prevent Visual Composer Redirection after plugin activation
 *
 * @package cardealer
 */

remove_action( 'admin_init', 'vc_page_welcome_redirect', 9999 );

/**
 * Lib link
 * http://tgmpluginactivation.com/faq/updating-bundled-visual-composer/
 * https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524297
 */
add_action( 'vc_before_init', 'cardealer_vc_set_as_theme' );
if ( ! function_exists( 'cardealer_vc_set_as_theme' ) ) {
	/**
	 * VC set as theme
	 */
	function cardealer_vc_set_as_theme() {
		vc_set_as_theme();

		$vc_supported_cpts = array(
			'page',
			'post',
		);
		vc_set_default_editor_post_types( $vc_supported_cpts );
	}
}

/*
 * Remove the blog from the 404 and search breadcrumb trail
 */
if ( ! function_exists( 'bcn_display' ) ) {
	/**
	 * Display bcn
	 *
	 * @param string $trail .
	 */
	function cardealer_wpst_override_breadcrumb_trail( $trail ) {
		if ( is_404() || is_search() ) {
			unset( $trail->trail[1] );
			array_keys( $trail->trail );
		}
	}
	add_action( 'bcn_after_fill', 'cardealer_wpst_override_breadcrumb_trail' );
}

add_filter( 'bcn_breadcrumb_title', 'cardealer_bcn_breadcrumb_title', 42, 3 );
if ( ! function_exists( 'cardealer_bcn_breadcrumb_title' ) ) {
	/**
	 * Hide upgrade notice for bundled plugin acf pro
	 *
	 * @param string $value .
	 */
	function cardealer_bcn_breadcrumb_title( $title, $type, $id ) {

		if ( isset( $type[0] ) && 'home' === $type[0] ) {
			$page_on_front = get_option( 'page_on_front' );
			if ( $page_on_front ) {
				$title = get_the_title( $page_on_front );
			}
		}

		return $title;
	}
}

if ( ! function_exists( 'cardealer_remove_acfpro_update_' ) ) {
	/**
	 * Hide upgrade notice for bundled plugin acf pro
	 *
	 * @param string $value .
	 */
	function cardealer_remove_acfpro_update_( $value ) {
		global $pagenow;
		if ( isset( $value->response ) && 'themes.php' !== $pagenow ) {
			unset( $value->response['advanced-custom-fields-pro/acf.php'] );
		}
		return $value;
	}
}
add_filter( 'site_transient_update_plugins', 'cardealer_remove_acfpro_update_' );

add_filter( 'pre_set_site_transient_update_plugins', 'site_transient_update_subscriptio_plugins' );
if ( ! function_exists( 'site_transient_update_subscriptio_plugins' ) ) {
	/**
	 * Subscriptio plugin upgrade transient change.
	 *
	 * @param object $transient .
	 * @return array
	 */
	function site_transient_update_subscriptio_plugins( $transient ) {
		global $pagenow;

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		if ( 'themes.php' === $pagenow ) {
			$tgmpa_plugins     = cardealer_tgmpa_plugin_list();
			$subscriptio_index = array_search( 'subscriptio', array_column( $tgmpa_plugins, 'slug' ) ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$subscriptio_data  = $tgmpa_plugins[ $subscriptio_index ];

			if ( ! isset( $transient->response['subscriptio/subscriptio.php'] ) ) {
				$transient->response['subscriptio/subscriptio.php'] = new stdClass();
			}

			if ( $subscriptio_data['source'] ) {
				$transient->response['subscriptio/subscriptio.php']->package = $subscriptio_data['source'];
			}

			if ( $subscriptio_data['slug'] ) {
				$transient->response['subscriptio/subscriptio.php']->slug = $subscriptio_data['slug'];
			}

			if ( $subscriptio_data['version'] ) {
				$transient->response['subscriptio/subscriptio.php']->new_version = $subscriptio_data['version'];
			}
		}

		return $transient;
	}
}

add_filter( 'acf/updates/plugin_update', 'cardealer_update_acfpro_plugin', 11, 2 );
if ( ! function_exists( 'cardealer_update_acfpro_plugin' ) ) {
	/**
	 * Update acf pro plugin
	 *
	 * @param string $update .
	 * @param string $transient .
	 */
	function cardealer_update_acfpro_plugin( $update, $transient ) {

		if ( function_exists( 'acf_pro_is_license_active' ) && ! acf_pro_is_license_active() && is_object( $update ) ) {
			$update->package = CARDEALER_PATH . '/includes/plugins/advanced-custom-fields-pro.zip';
		}
		return $update;
	}
}

add_filter( 'upgrader_package_options', 'cardealer_update_acfpro_package_options' );
if ( ! function_exists( 'cardealer_update_acfpro_package_options' ) ) {
	/**
	 * Update acf package option
	 *
	 * @param string $options .
	 */
	function cardealer_update_acfpro_package_options( $options ) {
		if ( ! empty( $options ) && isset( $options['hook_extra']['plugin'] ) && 'advanced-custom-fields-pro/acf.php' === $options['hook_extra']['plugin'] ) {
			// update source from tgmpa.
			$tgmpa_plugins      = cardealer_tgmpa_plugin_list();
			$acf_pro_index      = array_search( 'advanced-custom-fields-pro', array_column( $tgmpa_plugins, 'slug' ) ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$acf_pro_data       = $tgmpa_plugins[ $acf_pro_index ];
			$options['package'] = $acf_pro_data['source'];
		}
		return $options;
	}
}

// For visual-composer.
add_filter( 'site_transient_update_plugins', 'cardealer_remove_update_notifications' );
if ( ! function_exists( 'cardealer_remove_update_notifications' ) ) {
	/**
	 * Remove update notifications
	 *
	 * @param string $value .
	 */
	function cardealer_remove_update_notifications( $value ) {
		global $pagenow;
		if ( isset( $value->response ) && 'themes.php' !== $pagenow ) {
			unset( $value->response['js_composer/js_composer.php'] );
		}
		return $value;
	}
}

/**
 * Filter sortable field type content.
 *
 * @param string $render Rendered field markup.
 * @param array  $field  Field data.
 * @return string
 */
function cardealer_tweak_redux_field_sortable( $render, $field ) {
	if ( class_exists( 'Redux' ) && in_array( $field['id'], array( 'vehicle_detail_mobile_sections', 'social_share_medias' ), true ) ) {
		$value = Redux::get_option( 'car_dealer_options', $field['id'] );

		ob_start();

		if ( empty( $field['mode'] ) ) {
			$field['mode'] = 'text';
		}

		if ( 'checkbox' !== $field['mode'] && 'text' !== $field['mode'] && 'toggle' !== $field['mode'] ) {
			$field['mode'] = 'text';
		}

		if ( 'toggle' === $field['mode'] ) {
			$field['mode'] = 'checkbox';
		}

		$class   = ( isset( $field['class'] ) ) ? $field['class'] : '';
		$options = $field['options'];

		// This is to weed out missing options that might be in the default
		// Why?  Who knows.  Call it a dummy check.
		if ( ! empty( $value ) ) {
			foreach ( $value as $k => $v ) {
				if ( ! isset( $options[ $k ] ) ) {
					unset( $value[ $k ] );
				}
			}
		}

		$no_sort = false;
		if ( empty( $value ) && ! is_array( $value ) ) {
			if ( ! empty( $field['options'] ) ) {
				$value = $field['options'];
			} else {
				$value = array();
			}
		}
		foreach ( $options as $k => $v ) {
			if ( ! isset( $value[ $k ] ) ) {

				// A save has previously been done.
				if ( is_array( $value ) && array_key_exists( $k, $value ) ) {
					$value[ $k ] = $v;

					// Missing database entry, meaning no save has yet been done.
				} else {
					$no_sort           = true;
					$value[ $k ] = '';
				}
			}
		}

		// If missing database entries are found, it means no save has been done
		// and therefore no sort should be done.  Set the default array in the same
		// order as the options array.  Why?  The sort order is based on the
		// saved default array.  If entries are missing, the sort is messed up.
		// - kp.
		if ( true === $no_sort ) {
			$dummy_arr = array();

			foreach ( $options as $k => $v ) {
				$dummy_arr[ $k ] = $value[ $k ];
			}
			unset( $value );
			$value = $dummy_arr;
			unset( $dummy_arr );
		}

		$use_labels  = false;
		$label_class = ' checkbox';
		if ( 'checkbox' !== $field['mode'] ) {
			if ( ( isset( $field['label'] ) && true === $field['label'] ) ) {
				$use_labels  = true;
				$label_class = ' labeled';
			}
		}

		echo '<ul id="' . esc_attr( $field['id'] ) . '-list" class="redux-sortable ' . esc_attr( $class ) . ' ' . esc_attr( $label_class ) . '">';

		foreach ( $value as $k => $nicename ) {
			$invisible = '';

			if ( 'checkbox' === $field['mode'] ) {
				if ( empty( $value[ $k ] ) ) {
					$invisible = 'invisible';
				}
			}

			echo '<li class="' . esc_attr( $invisible ) . '">';

			$checked = '';
			$name    = 'name="' . $field['name'] . $field['name_suffix'] . '[' . esc_attr( $k ) . ']" ';

			if ( 'checkbox' === $field['mode'] ) {
				$value_display = $value[ $k ];

				if ( ! empty( $value[ $k ] ) ) {
					$checked = 'checked="checked" ';
				}

				$class .= ' checkbox_sortable';
				$name   = '';
				echo '<input
						type="hidden"
						name="' . esc_attr( $field['name'] . $field['name_suffix'] ) . '[' . esc_attr( $k ) . ']"
						id="' . esc_attr( $field['id'] . '-' . $k ) . '-hidden"
						value="' . esc_attr( $value_display ) . '" />';

				echo '<div class="checkbox-container">';
			} else {
				$value_display = $value[ $k ] ? $value[ $k ] : '';
				$nicename      = $field['options'][ $k ];
			}

			if ( 'checkbox' !== $field['mode'] ) {
				if ( $use_labels ) {
					echo '<label class="bugger" for="' . esc_attr( $field['id'] ) . '[' . esc_attr( $k ) . ']"><strong>' . wp_kses( wp_specialchars_decode( $options[ $k ] ), array( 'span' => array() ) ) . '</strong></label>';
					echo '<br />';
				}

				echo '<input
					rel="' . esc_attr( $field['id'] . '-' . $k ) . '-hidden"
					class="' . esc_attr( $class ) . '" ' . esc_html( $checked ) . '
					type="' . esc_attr( $field['mode'] ) . '"
					' . $name . // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'id="' . esc_attr( $field['id'] . '[' . $k ) . ']"
					value="' . esc_attr( $value_display ) . '"
					placeholder="' . esc_attr( $nicename ) . '" />';
			}

			echo '<span class="compact drag">';
			echo '<i class="dashicons dashicons-menu icon-large"></i>';
			echo '</span>';

			if ( 'checkbox' === $field['mode'] ) {
				echo '<i class="dashicons dashicons-visibility visibility"></i>';

				if ( 'checkbox' === $field['mode'] ) {
					echo '<label for="' . esc_attr( $field['id'] . '[' . $k ) . ']"><strong>' . wp_kses( wp_specialchars_decode( $options[ $k ] ), array(
						'span' => array(),
						'i'    => array(
							'class' => true
						),
						'img'    => array(
							'src' => true
						),
					) ) . '</strong></label>';
				}
			}

			if ( 'checkbox' === $field['mode'] ) {
				echo '</div>';
			}

			echo '</li>';
		}

		echo '</ul>';
		$render = ob_get_contents();
		ob_end_clean();
	}
	return $render;
}
add_filter( 'redux/field/car_dealer_options/sortable/render/after', 'cardealer_tweak_redux_field_sortable', 10, 2 );

function cardealer_tweak_redux_field_info( $render, $field ) {
	if ( 'vehicle_detail_mobile_layout_sections_notice' === $field['id'] && class_exists( 'Redux' ) ) {
		ob_start();
		$styles = array(
			'normal',
			'info',
			'warning',
			'success',
			'critical',
			'custom',
		);

		if ( ! in_array( $field['style'], $styles, true ) ) {
			$field['style'] = 'normal';
		}

		if ( 'custom' === $field['style'] ) {
			if ( ! empty( $field['color'] ) ) {
				$field['color'] = 'border-color:' . $field['color'] . ';';
			} else {
				$field['style'] = 'normal';
				$field['color'] = '';
			}
		} else {
			$field['color'] = '';
		}

		if ( empty( $field['desc'] ) && ! empty( $field['default'] ) ) {
			$field['desc'] = $field['default'];
			unset( $field['default'] );
		}

		if ( empty( $field['desc'] ) && ! empty( $field['subtitle'] ) ) {
			$field['desc'] = $field['subtitle'];
			unset( $field['subtitle'] );
		}

		if ( empty( $field['desc'] ) ) {
			$field['desc'] = '';
		}

		if ( empty( $field['raw_html'] ) ) {
			if ( true === $field['notice'] ) {
				$field['class'] .= ' redux-notice-field';
			} else {
				$field['class'] .= ' redux-info-field';
			}

			$field['style'] = 'redux-' . $field['style'] . ' ';
		}

		// Old shim, deprecated arg.
		if ( isset( $field['sectionIndent'] ) ) {
			$field['indent'] = $field['sectionIndent'];
		}
		$indent = ( isset( $field['indent'] ) && $field['indent'] ) ? ' form-table-section-indented' : '';

		echo '</td></tr></table>';
		echo '<div
				id="info-' . esc_attr( $field['id'] ) . '"
				class="' . ( isset( $field['icon'] ) && ! empty( $field['icon'] ) && true !== $field['icon'] ? 'hasIcon ' : '' ) . esc_attr( $field['style'] ) . ' ' . esc_attr( $field['class'] ) . ' redux-field-' . esc_attr( $field['type'] ) . esc_attr( $indent ) . '"' . ( ! empty( $field['color'] ) ? ' style="' . esc_attr( $field['color'] ) . '"' : '' ) . '>';

		if ( ! empty( $field['raw_html'] ) && $field['raw_html'] ) {
			echo wp_kses_post( $field['desc'] );
		} else {
			if ( isset( $field['title'] ) && ! empty( $field['title'] ) ) {
				$field['title'] = '<b>' . wp_kses_post( $field['title'] ) . '</b><br/>';
			}

			if ( isset( $field['icon'] ) && ! empty( $field['icon'] ) && true !== $field['icon'] ) {
				echo '<p class="redux-info-icon"><i class="' . esc_attr( $field['icon'] ) . ' icon-large"></i></p>';
			}

			if ( isset( $field['raw'] ) && ! empty( $field['raw'] ) ) {
				echo wp_kses_post( $field['raw'] );
			}

			if ( ! empty( $field['title'] ) || ! empty( $field['desc'] ) ) {
				echo '<div class="redux-info-desc"><p class="redux-info-desc-title">' . wp_kses_post( $field['title'] ) . '</p>' . wp_kses_post( $field['desc'] ) . '</div>';
			}
		}

		echo '</div>';
		echo '<table class="form-table no-border" style="margin-top: 0;">';
		echo '<tbody>';
		echo '<tr style="border-bottom:0; display:none;">';
		echo '<th style="padding-top:0;"></th>';
		echo '<td style="padding-top:0;">';
		$render = ob_get_contents();
		ob_end_clean();
	}
	return $render;
}
add_filter( 'redux/field/car_dealer_options/info/render/after', 'cardealer_tweak_redux_field_info', 10, 2 );

/**
 * Stop Redirect after elementor active
 */
function cardealer_elementor_activation_redirect() {
	if ( did_action( 'elementor/loaded' ) && get_option( 'cardealer_default_page_builder' ) ) {
		delete_transient( 'elementor_activation_redirect' );
	}
}
add_action( 'admin_init', 'cardealer_elementor_activation_redirect', 1 );
