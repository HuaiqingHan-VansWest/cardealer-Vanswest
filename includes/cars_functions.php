<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Theme vehicle functions
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package CarDealer/Functions
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealer_vehicle_title' ) ) {
	/**
	 * Vehicle Details Page Vehicle title.
	 *
	 * @param string $title .
	 */
	function cardealer_vehicle_title() {
		global $car_dealer_options;
		$is_custom_cars_details_title  = ( isset( $car_dealer_options['vehicle-title-location'] ) ) ? $car_dealer_options['vehicle-title-location'] : false;
		if ( $is_custom_cars_details_title === 'header' ) {
			the_title( '<h2>', '</h2>' );
		}

	}
}

if ( ! function_exists( 'cardealer_taxonomy_template_chooser' ) ) {
	/**
	 * Template redirection to archive-cars.php for taxonomy page template.
	 *
	 * @param string $template .
	 */
	function cardealer_taxonomy_template_chooser( $template ) {
		global $wp_query;

		if ( is_tax() ) {
			$current_taxonomy = get_query_var( 'taxonomy' );
			if ( $current_taxonomy ) {
				$current_taxonomy_object = get_taxonomy( $current_taxonomy );
				$current_post_types      = $current_taxonomy_object->object_type;

				if ( in_array( 'cars', $current_post_types ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$new_template = locate_template( array( 'archive-cars.php' ) );
					if ( '' !== $new_template ) {
						$template = $new_template;
					}
				}
			}
		}
		return $template;
	}
}
add_filter( 'template_include', 'cardealer_taxonomy_template_chooser', 99 );

add_action( 'cardealer_car_loop_link_open', 'cardealer_car_link_open', 10, 2 );
add_action( 'cardealer_car_loop_link_close', 'cardealer_car_link_close', 10, 2 );
if ( ! function_exists( 'cardealer_car_link_open' ) ) {
	/**
	 * Car link open.
	 *
	 * @param string $id .
	 * @param string $is_hover_overlay .
	 */
	function cardealer_car_link_open( $id, $is_hover_overlay ) {
		if ( 'no' === $is_hover_overlay ) {
			echo '<a href="' . esc_url( get_the_permalink( $id ) ) . '">';
		}
	}
}
if ( ! function_exists( 'cardealer_car_link_close' ) ) {
	/**
	 * Link close
	 *
	 * @param string $id .
	 * @param string $is_hover_overlay .
	 */
	function cardealer_car_link_close( $id, $is_hover_overlay ) {
		if ( 'no' === $is_hover_overlay ) {
			echo '</a>';
		}
	}
}
if ( ! function_exists( 'cardealer_is_hover_overlay' ) ) {
	/**
	 * Hover overlay
	 */
	function cardealer_is_hover_overlay() {
		global $car_dealer_options;
		$is_hover_overlay = 'yes';
		if ( isset( $car_dealer_options['cars-is-hover-overlay-on'] ) && ! empty( $car_dealer_options['cars-is-hover-overlay-on'] ) ) {
			$is_hover_overlay = $car_dealer_options['cars-is-hover-overlay-on'];
		}
		return $is_hover_overlay;
	}
}
add_action( 'car_before_overlay_banner', 'cardealer_get_cars_condition', 10, 2 );
add_action( 'car_before_overlay_banner', 'cardealer_get_cars_status', 20, 2 );
/**
 * Actions to used in car listing loop items overlay : Default Listing Style
 */
add_action( 'car_overlay_banner', 'cardealer_view_cars_overlay_link', 10, 1 );
add_action( 'car_overlay_banner', 'cardealer_compare_cars_overlay_link', 20, 1 );
add_action( 'car_overlay_banner', 'cardealer_images_cars_overlay_link', 30, 1 );
add_action( 'car_overlay_banner', 'cardealer_wishlist_cars_overlay_link', 40, 1 );

if ( ! function_exists( 'cardealer_view_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for view car details page
	 *
	 * @param string $id .
	 */
	function cardealer_view_cars_overlay_link( $id ) {
		$html = '<li><a href="' . get_the_permalink( $id ) . '" data-toggle="tooltip" title="' . esc_attr__( 'View', 'cardealer' ) . '"><i class="fas fa-link"></i></a></li>';
		/**
		 * Filters the HTML content of the vehicle detail page link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle detail page link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_view_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_wishlist_cars_overlay_link' ) ) {

	/**
	 * Wishlist cars overlay link
	 *
	 * @param string $id .
	 */
	function cardealer_wishlist_cars_overlay_link( $id ) {
		global $car_dealer_options;

		$wishlist_status = ( isset( $car_dealer_options['cars-is-wishlist-on'] ) ) ? $car_dealer_options['cars-is-wishlist-on'] : 'yes';
		if ( 'no' === $wishlist_status ) {
			return;
		}

		$text       = false;
		$list_style = cardealer_get_inv_list_style();

		if ( 'classic' === $list_style ) {
			$text = true;
		}

		cardealer_wishlist_button( $id, $text );
	}
}

if ( ! function_exists( 'cardealer_wishlist_button' ) ) {

	/**
	 * Wishlist cars overlay link
	 *
	 * @param string $id .
	 */
	function cardealer_wishlist_button( $id, $text = false ) {
		global $car_dealer_options, $post;

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( ! class_exists( 'CDFS_Wishlist' ) ) {
			return;
		}

		if ( (int) $post->post_author === (int) get_current_user_id() ) {
			return;
		}

		$wishlist_label = '';
		if ( $text ) {
			$wishlist_label = isset( $car_dealer_options['add_to_wishlist_text'] ) ? $car_dealer_options['add_to_wishlist_text'] : esc_html__( 'Add to wishlist', 'cardealer' );
		}

		$cdfs_wishlist        = new CDFS_Wishlist();
		$wishlist_class       = 'pgs_wishlist';
		$wishlist_icon_class  = 'far fa-heart';
		if ( $cdfs_wishlist->is_car_in_wishlist( $id ) ) {
			$wishlist_class      .= ' added-wishlist';
			$wishlist_icon_class  = 'fas fa-heart';
			$wishlist_label       = isset( $car_dealer_options['added_to_wishlist_text'] ) ? $car_dealer_options['added_to_wishlist_text'] : esc_html__( 'Added to Wishlist', 'cardealer' );
		}

		$html  = '<li>';
		$html .= '<a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Wishlist', 'cardealer' ) . '" data-id="' . esc_attr( $id ) . '" class="' . esc_attr( $wishlist_class ) . '">';
		$html .= '<i class="pgs-wishlist-icon ' . esc_attr( $wishlist_icon_class ) . '"></i>';
		$html .= '<span class="pgs-wishlist-label">'. esc_html( $wishlist_label ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';

		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Wishlist button content.
		 * @param string      $id   Vehicle ID.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_wishlist_button', $html, $id ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_compare_cars_overlay_link' ) ) {

	/**
	 * Compare cars overlay link
	 *
	 * @param string $id .
	 */
	function cardealer_compare_cars_overlay_link( $id ) {
		global $car_dealer_options;
		$compare_status = ( isset( $car_dealer_options['cars-is-compare-on'] ) ) ? $car_dealer_options['cars-is-compare-on'] : 'yes';
		if ( 'no' === $compare_status ) {
			return;
		}

		$car_in_compare = car_dealer_get_car_compare_ids();
		$compared_pgs   = '';
		$icon           = 'exchange-alt';
		if ( $car_in_compare && in_array( $id, $car_in_compare, true ) ) {
			$icon         = 'check';
			$compared_pgs = 'compared_pgs';
		}
		$html = '<li><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Compare', 'cardealer' ) . '" class="compare_pgs ' . $compared_pgs . '" data-id="' . esc_attr( $id ) . '"><i class="fas fa-' . esc_attr( $icon ) . '"></i></a></li>';
		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle gallery link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_compare_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_images_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for image gallery popup
	 *
	 * @param string $id .
	 */
	function cardealer_images_cars_overlay_link( $id ) {
		$images = cardealer_get_images_url( 'car_catalog_image', $id );
		$html   = '';
		if ( ! empty( $images ) ) {
			$html = '<li class="pssrcset"><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Gallery', 'cardealer' ) . '" class="psimages" data-image="' . implode( ', ', $images ) . '"><i class="fas fa-expand"></i></a></li>';
		}
		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle gallery link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_images_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}


/**
 * Actions to used in car CLASSIC grid loop items overlay
 */
add_action( 'vehicle_classic_grid_overlay', 'cardealer_compare_cars_overlay_link', 10, 1 );
add_action( 'vehicle_classic_grid_overlay', 'cardealer_images_cars_overlay_link', 20, 1 );
add_action( 'vehicle_classic_grid_overlay', 'cardealer_wishlist_cars_overlay_link', 30, 1 );


/**
 * Actions to used in car CLASSIC listing loop items overlay
 */
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_classic_view_cars_overlay_link', 10, 1 );
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_classic_compare_cars_overlay_link', 20, 1 );
add_action( 'vehicle_classic_list_overlay_gallery', 'cardealer_classic_images_cars_overlay_link', 10, 1 );
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_classic_vehicle_video_link', 30, 1 );
add_action( 'vehicle_classic_list_overlay_banner', 'cardealer_wishlist_cars_overlay_link', 40, 1 );

if ( ! function_exists( 'cardealer_classic_view_cars_overlay_link' ) ) {

	/**
	 * Get overlay link for view car details page
	 *
	 * @param string $id .
	 */
	function cardealer_classic_view_cars_overlay_link( $id ) {
		$html = '<li><a href="' . get_the_permalink( $id ) . '" data-toggle="tooltip" title="' . esc_attr__( 'View', 'cardealer' ) . '"><i class="fas fa-link"></i>' . esc_html__( 'Detail', 'cardealer' ) . '</a></li>';
		/**
		 * Filters the HTML content of the vehicle detail page link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle compare detail page link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_view_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_classic_compare_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for compare cars popup
	 *
	 * @param string $id .
	 */
	function cardealer_classic_compare_cars_overlay_link( $id ) {
		global $car_dealer_options;
		$compare_status = ( isset( $car_dealer_options['cars-is-compare-on'] ) ) ? $car_dealer_options['cars-is-compare-on'] : 'yes';
		if ( 'no' === $compare_status ) {
			return;
		}

		// @codingStandardsIgnoreStart
		if ( isset( $_COOKIE['cars'] ) && ! empty( $_COOKIE['cars'] ) ) {
			$car_in_compare = json_decode( $_COOKIE['cars'] );
		}
		$compared_pgs = '';
		$icon         = 'exchange-alt';
		if ( isset( $car_in_compare ) && ! empty( $car_in_compare ) && in_array( $id, $car_in_compare ) ) {
			$cars = json_decode( $_COOKIE['cars'] );
			if ( $cars ) {
				$icon         = 'check';
				$compared_pgs = 'compared_pgs';
			}
		}
		// @codingStandardsIgnoreEnd

		$html = '<li><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Compare', 'cardealer' ) . '" class="compare_pgs ' . $compared_pgs . '" data-id="' . esc_attr( $id ) . '"><i class="fas fa-' . $icon . '"></i>' . esc_html__( 'Compare', 'cardealer' ) . '</a></li>';
		/**
		 * Filters the HTML content of the vehicle compare link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle compare link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_compare_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_classic_images_cars_overlay_link' ) ) {
	/**
	 * Get overlay link for image gallery popup
	 *
	 * @param string $id .
	 */
	function cardealer_classic_images_cars_overlay_link( $id ) {
		$images = cardealer_get_images_url( 'car_catalog_image', $id );
		$html   = '';
		if ( ! empty( $images ) ) {
			$html = '<li class="pssrcset"><a href="javascript:void(0)" data-toggle="tooltip" title="' . esc_attr__( 'Gallery', 'cardealer' ) . '" class="psimages" data-image="' . implode( ', ', $images ) . '"><i class="fas fa-expand"></i></a></li>';
		}
		/**
		 * Filters the HTML content of the vehicle gallery link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle gallery link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_images_cars_overlay_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_classic_vehicle_video_link' ) ) {
	/**
	 * Get overlay link for video popup
	 *
	 * @param string $id .
	 */
	function cardealer_classic_vehicle_video_link( $id ) {
		$video_link = get_post_meta( $id, 'video_link', true );
		$html       = '';
		if ( ! empty( $video_link ) ) {
			$html = '<li><a class="popup-youtube" href="' . esc_attr( $video_link ) . '" data-toggle="tooltip" title="' . esc_attr__( 'Video', 'cardealer' ) . '"> <i class="fas fa-play"></i>' . esc_html__( 'Video', 'cardealer' ) . '</a></li>';
		}
		/**
		 * Filters the HTML content of the vehicle video link in vehicle listing.
		 *
		 * @since 1.0
		 * @param string      $html Vehicle video link HTML content.
		 * @visible           true
		 */
		echo apply_filters( 'cardealer_classic_vehicle_video_link', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

/**
 * Actions to used in car listing loop items title : Classic View
 */
add_action( 'cardealer_classic_list_car_title', 'cardealer_list_car_link_title', 10 );


/**
 * Actions to used in car listing loop items title : Default View
 */
add_action( 'cardealer_list_car_title', 'cardealer_list_car_link_title', 5 );
add_action( 'cardealer_list_car_title', 'cardealer_list_car_title_separator', 10 );

if ( ! function_exists( 'cardealer_list_car_link_title' ) ) {
	/**
	 * Get loop items title and link
	 */
	function cardealer_list_car_link_title() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_the_title() ) . '</a>';
	}
}

if ( ! function_exists( 'cardealer_list_car_title_separator' ) ) {
	/**
	 * Get loop items title after separator
	 */
	function cardealer_list_car_title_separator() {
		echo '<div class="separator"></div>';
	}
}

if ( ! function_exists( 'cardealer_link_feature_image_to_header' ) ) {
	/**
	 * Add Facebook meta tags for sharing
	 */
	function cardealer_link_feature_image_to_header() {
		$post_featured_image = cardealer_get_single_image_url();
		if ( ( is_single() && $post_featured_image ) && 'cars' === get_post_type() ) {
			echo '<meta property="og:type" content="article" />';
			echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />';
			echo '<meta property="og:url" content="' . esc_attr( get_permalink() ) . '" />';
			echo '<meta property="og:description" content="' . cardealer_car_short_content( get_the_ID() ) . '" />'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			echo '<meta property="og:image" content="' . esc_attr( $post_featured_image ) . '" />';
			echo '<link rel="image_src" href="' . esc_attr( $post_featured_image ) . '" />';
		}
	}
}
add_action( 'wp_head', 'cardealer_link_feature_image_to_header', 10 );

if ( ! function_exists( 'cardealer_car_short_content' ) ) {
	/**
	 * Get cars short content
	 *
	 * @param string $id .
	 */
	function cardealer_car_short_content( $id ) {
		$excerpt          = get_post_field( 'post_excerpt', $id );
		$vehicle_overview = get_post_meta( $id, 'vehicle_overview', true );
		$summary          = '&nbsp;';
		if ( ! empty( $excerpt ) ) {
			$summary = $excerpt;
		} elseif ( ! empty( $vehicle_overview ) ) {
			$summary = wp_trim_words( $vehicle_overview, 30, '...' );
		}

		/**
		 * Filters the vehicle summary(short) contents.
		 *
		 * @since 1.0
		 * @param string      $summary Vehicle short contents.
		 * @visible           true
		 */
		return apply_filters( 'cardealer_car_short_content', $summary );
	}
}

if ( ! function_exists( 'cardealer_get_cars_attributes' ) ) {
	/**
	 * Display cars features list in cars details page
	 *
	 * @param string $post_id .
	 */
	function cardealer_get_cars_attributes( $post_id = null ) {
		global $car_dealer_options;
		// bail early if $post_id is not provided.
		if ( ! $post_id ) {
			return;
		}

		$vehicle_mileage_unit = isset( $car_dealer_options['vehicle_mileage_unit'] ) ? $car_dealer_options['vehicle_mileage_unit'] : 'none';

		if ( isset( $car_dealer_options['vehicle-detail-attributes'] ) && ! empty( $car_dealer_options['vehicle-detail-attributes'] ) ) {
			$taxonomys = apply_filters( 'cardealer_taxonomys_array', $car_dealer_options['vehicle-detail-attributes'] );
		} else {
			$tax_arr = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_condition', 'car_mileage', 'car_transmission', 'car_drivetrain', 'car_engine', 'car_fuel_type', 'car_fuel_economy', 'car_trim', 'car_exterior_color', 'car_interior_color', 'car_stock_number', 'car_vin_number' );

			$taxonomies_raw = get_object_taxonomies( 'cars' );

			foreach ( $taxonomies_raw as $new_tax ) {
				if ( in_array( $new_tax, $tax_arr, true ) ) {
					continue;
				}

				$new_tax_obj = get_taxonomy( $new_tax );
				if ( isset( $new_tax_obj->include_in_filters ) && true === (bool) $new_tax_obj->include_in_filters ) {
					$tax_arr[] = $new_tax;
				}
			}

			$taxonomys = apply_filters(
				'cardealer_taxonomys_array',
				$tax_arr
			);

		}

		/**
		 * Filters the Array of the vehicle taxonomies used to display on the vehicle details page.
		 *
		 * @since 1.0
		 * @param array        $taxonomys  Array of the vehicle taxonomies selected to be display on vehicle detail page.
		 * @param int          $post_id    Vehicle ID.
		 * @visible            true
		 */
		$taxonomys      = apply_filters( 'cardealer_cars_details_attributes_array', $taxonomys, $post_id );
		$attributes     = array();
		$taxonomies_obj = get_object_taxonomies( 'cars', 'object' );

		foreach ( $taxonomys as $tax ) {
			$term = wp_get_post_terms( $post_id, $tax );
			if ( ! is_wp_error( $term ) && ! empty( $term ) ) {
				$label              = $taxonomies_obj[ $tax ]->labels->singular_name;
				$attributes[ $tax ] = array(
					'attr'  => $label,
					'value' => $term[0]->name,
				);
			}
		}

		/**
		 * Filters the Array of the vehicle attributes used to display on the vehicle details page.
		 *
		 * @since 1.0
		 * @param array        $attributes Array of the vehicle attributes selected to be display on vehicle detail page.
		 * @param int          $post_id    Vehicle ID.
		 * @visible            true
		 */
		$attributes     = apply_filters( 'cardealer_car_attributes', $attributes, $post_id );
		$attributs_html = '';
		if ( is_array( $attributes ) && ! empty( $attributes ) ) {

			$attributs_html = '<ul class="car-attributes">';
			foreach ( $attributes as $attribute_k => $attribute ) {

				// skip if attribute or value is not set.
				if ( ( ! isset( $attribute['attr'] ) || '' === $attribute['attr'] ) || ( ! isset( $attribute['value'] ) || '' === $attribute['value'] ) ) {
					continue;
				}

				if ( $vehicle_mileage_unit && 'none' !== $vehicle_mileage_unit && 'car_mileage' === $attribute_k ) {
					$attribute_value = $attribute['value'] . ' ' . $vehicle_mileage_unit;
				} else {
					$attribute_value = $attribute['value'];
				}

				$attributs_html .= '<li class="' . esc_attr( $attribute_k ) . '"><span>' . $attribute['attr'] . '</span> <strong class="text-right">' . $attribute_value . '</strong></li>';
			}
			$attributs_html .= '</ul>';

		}

		// Deprecated.
		$attributs_html = apply_filters_deprecated( 'cardealer_get_cars_attributes', array( $attributs_html ), '1.1.1', 'cardealer_car_attributes_html' );

		/**
		 * Filters the HTML of the vehicle attributes to display on the vehicle details page.
		 *
		 * @since 1.0
		 * @param   string       $attributs_html HTML structure of the vehicle attributes to be display on the vehicle details page.
		 * @param   array        $attributes    Array of the vehicle attributes selected to be display on vehicle detail page.
		 * @param   int          $post_id       Vehicle ID.
		 * @visible              true
		 */
		$attributs_html = apply_filters( 'cardealer_car_attributes_html', $attributs_html, $attributes, $post_id );

		$attributs_allowed_html = apply_filters( 'cardealer_car_attributes_allowed_html', array( 'ul', 'li', 'span', 'strong' ), $post_id );

		echo wp_kses( $attributs_html, cardealer_allowed_html( $attributs_allowed_html ) );
	}
}

if ( ! function_exists( 'cardealer_add_vehicle_to_cart' ) ) {
	/**
	 * Display add vehicle to cart details page
	 *
	 * @param string $vehicle_id .
	 */
	function cardealer_add_vehicle_to_cart( $vehicle_id ) {

		$is_vehicle_sell = cardealer_is_vehicle_sellable( $vehicle_id );
		if ( $is_vehicle_sell ) {
			global $car_dealer_options;

			$lable_text = sell_vehicle_lable_text();
			?>
			<div class="car-vehicle-buy-online add-vehicle-to-cart-btn">
				<button class="vehicle-button-link car-buy-online-btn button vehicle-button-link-type-js_event" data-btn_type="js_event" href="javascript:void();" data-event="cardealer-vehicle-button-buy-online" data-vehicle_id="<?php echo esc_attr( $vehicle_id ); ?>">
					<i class="fas fa-shopping-cart"></i><span class="car-buy-online-label"><?php echo esc_html( $lable_text ); ?><span>
				</button>
			</div>
			<?php
		}

	}
}

/**
 * Get vehicle stock.
 *
 * @param int|string $vehicle_id Vehicle ID.
 * @return int
 */
function cardealer_get_vehicle_stock( $vehicle_id = '' ) {
	$stock = 0;

	if ( empty( $vehicle_id ) ) {
		$post = get_post();
		if ( $post && 'cars' === $post->post_type ) {
			$vehicle_id = $post->ID;
		}
	}

	if ( ! empty( $vehicle_id ) ) {
		$vehicle_in_stock        = get_post_meta( $vehicle_id, 'total_vehicle_in_stock', true );
		$vehicle_in_stock_exists = metadata_exists( 'post', $vehicle_id, 'total_vehicle_in_stock' );
		if ( $vehicle_in_stock_exists ) {
			if ( (int) $vehicle_in_stock > 0 ) {
				$stock = (int) $vehicle_in_stock;
			}
		} else {
			global $car_dealer_options;
			$sell_vehicle_option = ( isset( $car_dealer_options['sell_vehicle_enable_all_option'] ) ) ? $car_dealer_options['sell_vehicle_enable_all_option'] : 0;
			if ( $sell_vehicle_option && class_exists( 'WooCommerce' ) ) {
				$stock = 1;
			}
		}
	}

	return (int) apply_filters( 'cardealer_get_vehicle_stock', $stock, $vehicle_id );
}

/**
 * Get whether vehicle selling enabled.
 *
 * @param int|string $vehicle_id Vehicle ID.
 * @return bool
 */
function cardealer_is_vehicle_sell_enabled( $vehicle_id = '' ) {
	$enabled = false;
	if ( empty( $vehicle_id ) ) {
		$post = get_post();
		if ( $post && 'cars' === $post->post_type ) {
			$vehicle_id = $post->ID;
		}
	}

	if ( ! empty( $vehicle_id ) ) {
		$sell_vehicle_status = get_post_meta( $vehicle_id, 'sell_vehicle_status', true );
		if ( ! empty($sell_vehicle_status) ) {
			$enabled = ( 'enable' === $sell_vehicle_status ) ? true : false;
		} else {
			global $car_dealer_options;
			$sell_vehicle_option = ( isset( $car_dealer_options['sell_vehicle_enable_all_option'] ) ) ? $car_dealer_options['sell_vehicle_enable_all_option'] : 0;
			$enabled = ( $sell_vehicle_option && class_exists( 'WooCommerce' ) ) ? true : false;
		}
	}

	return apply_filters( 'cardealer_is_vehicle_sell_enabled', $enabled, $vehicle_id );
}

/**
 * Get sell vehicle lable text.
 *
 * @return string
 */
function sell_vehicle_lable_text() {
	global $car_dealer_options;

	$lable_text              = isset( $car_dealer_options['sell_vehicle_lable_text'] ) ? $car_dealer_options['sell_vehicle_lable_text'] : '';
	$sell_vehicle_lable_text = ( ! empty( $lable_text ) ) ? $lable_text : esc_html__( 'Buy Online', 'cardealer' );

	return apply_filters( 'sell_vehicle_lable_text', $sell_vehicle_lable_text  );
}
/**
 * Returns whether vehicle is sellable.
 *
 * @return bool
 */
function cardealer_is_vehicle_sellable( $vehicle_id = '' ) {
	global $car_dealer_options;

	$sellable = false;

	if ( ! class_exists( 'WooCommerce' ) ) {
		return false;
	}

	if ( empty( $vehicle_id ) ) {
		$post = get_post();
		if ( $post && 'cars' === $post->post_type ) {
			$vehicle_id = $post->ID;
		}
	}

	$sell_vehicle_option = ( isset( $car_dealer_options['sell_vehicle_option'] ) ) ? $car_dealer_options['sell_vehicle_option'] : false;
	$sellable = ( $sell_vehicle_option  ) ? true : false;

	if ( $sellable && cardealer_is_vehicle_sell_enabled( $vehicle_id ) && cardealer_get_vehicle_stock( $vehicle_id ) > 0 )  {
		$sellable = true;
	} else {
		$sellable = false;
	}

	return apply_filters( 'cardealer_is_vehicle_sellable', $sellable, $vehicle_id );
}

if ( ! function_exists( 'cardealer_get_vehicle_review_stamps' ) ) {
	/**
	 * Display vehicle review stamps
	 *
	 * @param string $id .
	 * @param string $echo .
	 */
	function cardealer_get_vehicle_review_stamps( $id = null, $return = false ) {
		if ( null !== $id ) {
			$links_html        = '';
			$html              = '';
			$review_stamp_data = array();

			global $car_dealer_options;

			$review_stamp_limit = isset( $car_dealer_options['review_stamp_limit'] ) ? $car_dealer_options['review_stamp_limit'] : 1;
			$review_stamp_popup = isset( $car_dealer_options['review_stamp_popup'] ) ? $car_dealer_options['review_stamp_popup'] : 'disable';

			for ( $i = 1; $i <= $review_stamp_limit; $i++ ) {
				$review_stamp_logo_id   = get_post_meta( $id, 'review_stamp_logo_' . $i, true );
				$review_stamp_link      = '';
				$review_stamp_logo_url = '';

				if ( ! empty( $review_stamp_logo_id ) ) {
					$review_stamp_logo_url = wp_get_attachment_url( $review_stamp_logo_id );
					$review_stamp_link     = get_post_meta( $id, 'review_stamp_link_' . $i, true );
				}

				if ( ! empty( $review_stamp_logo_url ) ) {

					$review_stamp_data[] = array(
						'img_url' => $review_stamp_logo_url,
						'link'    => $review_stamp_link,
					);

					$popup_class = '';

					if ( 'enable' === $review_stamp_popup ) {
						$popup_class = ' vehicle-review-stamp-popup';
					}

					if ( '' !== $review_stamp_link ) {
						$links_html .= '<a class="vehicle-review-stamp-link' . esc_attr( $popup_class ) . '" href="' . esc_url( $review_stamp_link ) . '" target="_blank" data-elementor-open-lightbox="no">';
					}

					$links_html .= '<img class="vehicle-review-stamp-img" src="' . esc_url( $review_stamp_logo_url ) . '" alt="carfax"/>';

					if ( '' !== $review_stamp_link ) {
						$links_html .= '</a>';
					}
				}
			}

			/**
			 * Filters array of review stamps.
			 *
			 * @param array $review_stamp_data  Array of review stamps.
			 * @param int   $id                 Vehicle ID.
			 */
			$review_stamp_data = apply_filters( 'cardealer_vehicle_review_stamp_data', $review_stamp_data, $id );

			if ( $return ) {
				return $review_stamp_data;
			} else {
				/**
				 * Filters the URL of the vehicle review stamps of the vehicle.
				 *
				 * @since 1.0
				 * @param string $link               URL of the vehicle review stamps.
				 * @param int    $id                 Vehicle ID.
				 * @param array  $review_stamp_data  Array of review stamps.
				 * @Hooked       cardealer_update_stamp_html 10
				 * @visible      true
				 */
				$links_html = apply_filters( 'cardealer_vrs_link_html', $links_html, $id );
				$links_html = apply_filters( 'cardealer_vehicle_review_stamps_links_html', $links_html, $id, $review_stamp_data );

				if ( '' !== $links_html ) {
					$html .= '<div class="car-vehicle-review-stamps">';
					$html .= $links_html;
					$html .= '</div>';
				}

				/**
				 * Filters the HTML structure of vehicle review stamps of vehicle.
				 *
				 * @since 1.0
				 * @param string      $html HTML structure of Vehicle Review Stamps.
				 * @visible           true
				 */
				$html = apply_filters( 'cardealer_get_vehicle_review_stamps', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				echo apply_filters( 'cardealer_vehicle_review_stamps_html', $html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
		}
	}
}

if ( ! function_exists( 'cardealer_get_cars_list_attribute' ) ) {
	/**
	 * Display cars few features list in card catalog view on hover overlay
	 */
	function cardealer_get_cars_list_attribute() {
		global $post, $car_dealer_options;

		$vehicle_mileage_unit = ( isset( $car_dealer_options['vehicle_mileage_unit'] ) && 'none' !== $car_dealer_options['vehicle_mileage_unit'] ) ? $car_dealer_options['vehicle_mileage_unit'] : '';

		$list_style = cardealer_get_inv_list_style();
		if ( 'classic' === $list_style ) {
			if ( isset( $car_dealer_options['inv-list-attributes'] ) && ! empty( $car_dealer_options['inv-list-attributes'] ) ) {
				$car_taxonomys = $car_dealer_options['inv-list-attributes'];
			} else {
				$car_taxonomys = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_transmission' );
			}
			/**
			 * Filters the list of attributes to be displayed in list view layout of inventory page.
			 *
			 * @since 1.0
			 * @param array     $car_taxonomys  Array of attributes to be displayed in list view layout.
			 * @visible         true
			 */
			$taxonomys = apply_filters( 'cardealer_vehicle_list_attr_contents', $car_taxonomys );
			if ( empty( $taxonomys ) ) {
				return;
			}
			$taxonomies_obj  = get_object_taxonomies( 'cars', 'object' );
			$getlayout       = cardealer_get_cars_list_layout_style();

			$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
			$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 'inline';

			ob_start();
			?>
			<div class="vehicle-attributes-list">
				<?php
				if ( ( 'view-list' === $getlayout ) && ( 'no' === $listing_sidebar || 'off-canvas' === $desktop_filter_location ) ) {
					$size         = ceil( count( $taxonomys ) / 2 );
					$parsed_attrs = array_chunk( $taxonomys, $size, true );
					foreach ( $parsed_attrs as $taxonomys ) {
						if ( empty( $taxonomys ) ) {
							continue;
						}
						?>
						<ul class="vehicle-attributes">
						<?php
						foreach ( $taxonomys as $tax ) {
							$attr = get_the_terms( $post->ID, $tax );
							if ( ! is_wp_error( $attr ) && ! empty( $attr ) && isset( $attr[0]->name ) ) {
								?>
								<li class="row">
									<span class="col-xs-6"><?php echo ucwords( esc_html( $taxonomies_obj[ $tax ]->labels->name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
									<?php
									if ( $vehicle_mileage_unit && 'car_mileage' === $tax ) {
										?>
										<strong class="col-xs-6"><?php echo ucwords( esc_html( $attr[0]->name ) ) . ' ' . esc_html( $vehicle_mileage_unit ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></strong>
										<?php
									} else {
										?>
										<strong class="col-xs-6"><?php echo ucwords( esc_html( $attr[0]->name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></strong>
										<?php
									}
									?>
								</li>
								<?php
							}
						}
						?>
						</ul>
						<?php
					}
				} else {
					?>
					<ul class="list-inline">
					<?php

					foreach ( $taxonomys as $tax ) {
						$attr = get_the_terms( $post->ID, $tax );
						if ( ! is_wp_error( $attr ) && ! empty( $attr ) && isset( $attr[0]->name ) ) {
							?>
							<li class="row">
								<span class="col-xs-6"><?php echo ucwords( esc_html( $taxonomies_obj[ $tax ]->labels->singular_name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
								<?php
								if ( $vehicle_mileage_unit && 'car_mileage' === $tax ) {
									?>
									<strong class="col-xs-6"><?php echo ucwords( esc_html( $attr[0]->name ) ) . ' ' . esc_html( $vehicle_mileage_unit ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></strong>
									<?php
								} else {
									?>
									<strong class="col-xs-6"><?php echo ucwords( esc_html( $attr[0]->name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></strong>
									<?php
								}
								?>
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
			$attributs = ob_get_clean();
			echo apply_filters( 'cardealer_get_cars_list_attribute', $attributs ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		} else {
			$year         = get_the_terms( $post->ID, 'car_year' );
			$transmission = get_the_terms( $post->ID, 'car_transmission' );
			$mileage      = get_the_terms( $post->ID, 'car_mileage' );
			if ( empty( $year ) && empty( $transmission ) && empty( $mileage ) ) {
				return;
			}
			$car_year         = '';
			$car_transmission = '';
			$car_mileage      = '';
			if ( ! is_wp_error( $year ) && isset( $year[0]->name ) ) {
				$car_year = $year[0]->name;
			}
			if ( ! is_wp_error( $year ) && isset( $transmission[0]->name ) ) {
				$car_transmission = $transmission[0]->name;
			}
			if ( ! is_wp_error( $year ) && isset( $mileage[0]->name ) ) {
				$car_mileage = $mileage[0]->name;
			}

			$cars_grid = cardealer_get_cars_catlog_style();
			$type      = '';
			$trn_cls   = ' class="car-transmission-dots" ';
			if ( '' !== $cars_grid && 'yes' !== $cars_grid ) {
				$trn_cls = ' ';
			}

			$attributs_html = '<div class="car-list"><ul class="list-inline">';
			if ( ! empty( $car_year ) ) {
				$attributs_html .= '<li><i class="fas fa-calendar-alt"></i> ' . esc_html( $car_year ) . '</li>';
			}
			if ( ! empty( $car_transmission ) ) {
				$car_transmission_full = $car_transmission;
				$car_transmission      = strlen( $car_transmission ) > 5 ? substr( $car_transmission, 0, 5 )."..." : $car_transmission;
				$car_transmission      = empty( esc_html( $car_transmission ) ) ? $car_transmission_full : $car_transmission;
				$attributs_html       .= '<li' . $trn_cls . 'title="' . esc_html( $car_transmission ) . '"><i class="fas fa-cog"></i> ' . esc_html( $car_transmission ) . '</li>';
			}
			if ( $car_mileage ) {
				if ( $vehicle_mileage_unit ) {
					$attributs_html .= '<li><i class="glyph-icon flaticon-gas-station"></i> ' . esc_html( $car_mileage . ' ' . $vehicle_mileage_unit ) . '</li>';
				} else {
					$attributs_html .= '<li><i class="glyph-icon flaticon-gas-station"></i> ' . esc_html( $car_mileage ) . '</li>';
				}
			}
			$attributs_html .= '</ul></div>';

			/**
			 * Filters the HTML contents which displays vehicle attributes in inventory page.
			 *
			 * @since 1.0
			 * @param string        $attributs_html          HTML contents which displays vehicle attributes in inventory page.
			 * @param $year         WP_Term[]|false|WP_Error Array of year WP_Term objects.
			 * @param $transmission WP_Term[]|false|WP_Error Array of transmission WP_Term objects.
			 * @param $mileage      WP_Term[]|false|WP_Error Array of mileage WP_Term objects.
			 * @visible           true
			 */
			echo apply_filters( 'cardealer_get_cars_list_attribute', $attributs_html, $year, $transmission, $mileage ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}

if ( ! function_exists( 'cardealer_cars_filter_methods' ) ) {
	/**
	 * Check filter with ajax or get method
	 */
	function cardealer_cars_filter_methods() {
		global $car_dealer_options;
		$cars_filter_with = '';
		if ( isset( $car_dealer_options['cars-filter-with'] ) ) {
			$cars_filter_with = $car_dealer_options['cars-filter-with'];
		}

		return apply_filters( 'cardealer_cars_filter_methods', $cars_filter_with );
	}
}

if ( ! function_exists( 'cardealer_get_cars_currency_symbol' ) ) {
	/**
	 * Get currenc currency symbol
	 */
	function cardealer_get_cars_currency_symbol() {
		global $car_dealer_options;
		$currency_symbol = '';
		if ( function_exists( 'cdhl_get_currency_symbols' ) ) {
			$currency_code   = isset( $car_dealer_options['cars-currency-symbol'] ) ? $car_dealer_options['cars-currency-symbol'] : '';
			$currency_symbol = cdhl_get_currency_symbols( $currency_code );
		} else {
			$currency_code   = isset( $car_dealer_options['cars-currency-symbol'] ) ? $car_dealer_options['cars-currency-symbol'] : '';
			$currency_symbol = $currency_code;
		}
		return $currency_symbol;
	}
}

if ( ! function_exists( 'cardealer_get_cars_currency_placement' ) ) {
	/**
	 * Get currenc currency placement
	 */
	function cardealer_get_cars_currency_placement() {
		global $car_dealer_options;

		$currency_placement = isset( $car_dealer_options['cars-currency-symbol-placement'] ) ? $car_dealer_options['cars-currency-symbol-placement'] : '';
		$placement          = 'right';
		switch ( $currency_placement ) {
			case 1:
				$placement = 'left';
				break;
			case 3:
				$placement = 'left-with-space';
				break;
			case 4:
				$placement = 'right-with-space';
				break;
			default:
				$placement = 'right';
		}
		return $placement;
	}
}

if ( ! function_exists( 'cardealer_is_request_price_enable' ) ) {
	/**
	 * Check if request_price enabled.
	 *
	 * @param int $id .
	 */
	function cardealer_is_request_price_enable( $id = null ) {
		global $car_dealer_options;

		$req_price_form_status = false;
		if ( ! $id ) {
			$id = get_the_ID();
		}

		if ( isset( $car_dealer_options['req_price_form_status'] ) && $car_dealer_options['req_price_form_status'] ) {
			$req_price_form_status = true;
		}

		if ( get_post_meta( $id, 'enable_request_price', true ) ) {
			$req_price_form_status = true;
		}

		if ( ! isset( $car_dealer_options['req_price_form_shortcode'] ) || ( isset( $car_dealer_options['req_price_form_shortcode'] ) && ! $car_dealer_options['req_price_form_shortcode'] ) ) {
			$req_price_form_status = false;
		}

		return $req_price_form_status;
	}
}

if ( ! function_exists( 'cardealer_car_price_html' ) ) {
	/**
	 * CAR Price formating
	 *
	 * @param string $class .
	 * @param string $id .
	 * @param string $tax_label .
	 * @param string $echo .
	 */
	function cardealer_car_price_html( $class = '', $id = null, $tax_label = true, $echo = true, $is_page_price = false ) {
		global $car_dealer_options, $post;

		$car_id    = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		$btn_label = cardealer_get_theme_option( 'req_price_btn_label', esc_html__( 'Request Price', 'cardealer' ) );

		if ( cardealer_is_request_price_enable( $car_id ) ) {

			$enable_request_price = get_post_meta( $car_id, 'enable_request_price', true );
			$request_price_label  = get_post_meta( $car_id, 'request_price_label', true );
			if ( $request_price_label && $enable_request_price ) {
				$btn_label = $request_price_label;
			}

			$args = array(
				'car_id'	    => $car_id,
				'is_page_price' => $is_page_price,
				'btn_label'     => $btn_label,
			);

			if ( $echo ) {
				get_template_part( 'template-parts/cars/single-car/forms/req-price-form', null, $args );
			} else {
				ob_start();
				get_template_part( 'template-parts/cars/single-car/forms/req-price-form', null, $args );
				$price_html = ob_get_clean();

				return $price_html;
			}

		} else {
			$class_array = ( ! empty($class) ) ? explode( ' ', $class ) : array();

			$currency_code = ( isset( $car_dealer_options['cars-currency-symbol'] ) && ! empty( $car_dealer_options['cars-currency-symbol'] ) ) ? $car_dealer_options['cars-currency-symbol'] : '';
			if ( function_exists( 'cdhl_get_currency_symbols' ) ) {
				$currency_symbol = cdhl_get_currency_symbols( $currency_code );
			} else {
				$currency_symbol = '$';
			}

			$is_single = is_single();

			$symbol_position          = ( isset( $car_dealer_options['cars-currency-symbol-placement'] ) && ! empty( $car_dealer_options['cars-currency-symbol-placement'] ) ) ? $car_dealer_options['cars-currency-symbol-placement'] : '';
			$seperator                = ( isset( $car_dealer_options['cars-disable-currency-separators'] ) && ! empty( $car_dealer_options['cars-disable-currency-separators'] ) ) ? $car_dealer_options['cars-disable-currency-separators'] : '';
			$seperator_symbol         = ( isset( $car_dealer_options['cars-thousand-separator'] ) && ! empty( $car_dealer_options['cars-thousand-separator'] ) ) ? $car_dealer_options['cars-thousand-separator'] : '';
			$decimal_separator_symbol = ( isset( $car_dealer_options['cars-decimal-separator'] ) && ! empty( $car_dealer_options['cars-decimal-separator'] ) ) ? $car_dealer_options['cars-decimal-separator'] : '.';

			$decimal_places = ( ! empty( $car_dealer_options['cars-number-decimals'] ) && is_numeric( $car_dealer_options['cars-number-decimals'] ) ) ? $car_dealer_options['cars-number-decimals'] : 0;

			$price_html    = '';
			$space         = '';
			$regular_price = 0;
			$sale_price    = 0;
			$regular_price = function_exists( 'get_field' ) ? get_field( 'regular_price', $car_id ) : get_post_meta( $car_id, 'regular_price', true );
			$sale_price    = function_exists( 'get_field' ) ? get_field( 'sale_price', $car_id ) : get_post_meta( $car_id, 'sale_price', true );

			$regular_price  = floatval( $regular_price );
			$sale_price     = floatval( $sale_price );
			$decimal_places = (int) $decimal_places;

			if ( ( $regular_price > 0 ) || ( $sale_price > 0 ) ) {

				$price_html .= '<div class="price car-price ' . esc_attr( $class ) . '">';
				if ( 3 === (int) $symbol_position || 4 === (int) $symbol_position ) {
					$space = ' ';
				}

				$currency_class = 'currency currency-' . $currency_code;

				if ( ! empty( $regular_price ) && ( $regular_price > 0 ) ) {
					$regular_price = ( isset( $seperator ) && 1 === (int) $seperator ) ? number_format( $regular_price, $decimal_places, $decimal_separator_symbol, $seperator_symbol ) : get_post_meta( $car_id, 'regular_price', true );
					if ( $sale_price > 0 ) {
						$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<bdi class="old-price"><span class="' . esc_attr( $currency_class ).'">' . $currency_symbol . '</span>' . $space . esc_html( $regular_price ) . '</bdi>' : '<bdi class="old-price"> ' . esc_html( $regular_price ) . '<span class="' . esc_attr( $currency_class ).'">' . $space . $currency_symbol . '</span></bdi>';
					} else {
						$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<bdi class="new-price"><span class="' . esc_attr( $currency_class ).'">' . $currency_symbol . '</span>' . $space . esc_html( $regular_price ) . '</bdi>' : '<bdi class="new-price"> ' . esc_html( $regular_price ) . '<span class="' . esc_attr( $currency_class ).'">' . $space . $currency_symbol . '</span></bdi>';
					}
				}

				if ( $sale_price > 0 && $sale_price <= 200000 ) {
					$sale_price  = ( isset( $seperator ) && 1 === (int) $seperator ) ? number_format( $sale_price, $decimal_places, $decimal_separator_symbol, $seperator_symbol ) : get_post_meta( $car_id, 'sale_price', true );
					$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<bdi class="new-price"><span class="' . esc_attr( $currency_class ).'">' . $currency_symbol . '</span>' . $space . esc_html( $sale_price ) . '</bdi>' : '<bdi class="new-price"> ' . esc_html( $sale_price ) . '<span class="' . esc_attr( $currency_class ).'">' . $space . $currency_symbol . '</span></bdi>';
				}
				//if the price is higher than 200000, contact our sales for detail 
				elseif($sale_price>=200000) {
					$price_html .= ( 1 === (int) $symbol_position || 3 === (int) $symbol_position ) ? '<bdi class="new-price"><span class="' . esc_attr( $currency_class ).'">' . '' . '</span>' . $space . 'Contact Our Sales' . '</bdi>' : '<bdi class="new-price"> ' . 'Contact Our Sales' . '<span class="' . esc_attr( $currency_class ).'">' . $space . $currency_symbol . '</span></bdi>';
				}

				if ( $is_single ) {
					if ( did_action( 'elementor/loaded' ) ) {
						if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() && ! in_array( 'hide-status', $class_array ) && ! in_array( 'related-slider', $class_array ) ) {
							$price_html .= cardealer_get_cars_status( $car_id );
						}
					} else {
						if ( ! in_array( 'hide-status', $class_array ) && ! in_array( 'related-slider', $class_array ) ) {
							$price_html .= cardealer_get_cars_status( $car_id );
						}
					}
				}

				if ( true === $tax_label ) {
					$tax_label_content = get_post_meta( $car_id, 'tax_label', true );
					if ( ! empty( $tax_label_content ) ) {
						$price_html .= '<p>' . get_post_meta( $car_id, 'tax_label', true ) . '</p>';
					}
				}

				$price_html .= '</div>';

				$is_sell = cardealer_is_vehicle_sellable( $car_id );

				if ( $is_sell && ( ! $is_single || in_array( 'related-slider', $class_array) ) && ! in_array( 'hide-sell', $class_array ) ) {
					$lable_text  = sell_vehicle_lable_text();
					$price_html .= '<div class="vehicle-button-link car-buy-online-btn vehicle-button-link-type-js_event" data-btn_type="js_event" data-event="cardealer-vehicle-button-buy-online" data-vehicle_id="' . esc_attr( $car_id ) . '">';
					$price_html .= '<span class="car-buy-online-label">' .  esc_html( $lable_text ) . '</span></div>';
				}

			}

			// options to add in filter.
			$options = array(
				'class'                     => ( ! empty($class_array) ) ? implode( ' ', $class_array ) : '',
				'id'                        => $car_id,
				'tax_label'                 => $tax_label,
				'currency_symbol'           => $currency_symbol,
				'symbol_position'           => $symbol_position,
				'seperator'                 => $seperator,
				'thousand_seperator_symbol' => $seperator_symbol,
				'decimal_separator_symbol'  => $decimal_separator_symbol,
				'decimal_places'            => $decimal_places,
				'currency_code'             => $currency_code,
			);
			/**
			 * Filters the HTML layout of the vehicle price.
			 *
			 * @since 1.0
			 *
			 * @param string    $price_html HTML layout of the vehicle price.
			 * @param array     $options    Array of price elements used to build price HTML.
			 * @visible         true
			 */
			$price_html = apply_filters( 'cardealer_vehicle_price_html_body', $price_html, $options );

			/**
			 * Filters the HTML layout of the vehicle price.
			 *
			 * @since 1.0
			 *
			 * @param string      $price_html HTML layout of the vehicle price.
			 * @param int         $car_id      Vehicle ID.
			 * @visible           true
			 */
			$price_html = apply_filters( 'cardealer_car_price_html', $price_html, $car_id );

			if ( $echo ) {
				echo wp_kses(
					$price_html,
					array(
						'div'  => array(
							'class' => true,
							'data-btn_type' => true,
							'data-vehicle_id' => true,
							'data-event' => true,
						),
						'p'    => array(),
						'bdi'  => array(
							'class' => true,
						),
						'span' => array(
							'class' => true,
						),
					)
				);
			} else {
				return $price_html;
			}
		}
	}
}

if ( ! function_exists( 'cardealer_get_car_price' ) ) {
	/**
	 * CAR Price formating with retur value
	 *
	 * @param string $class .
	 * @param string $id .
	 */
	function cardealer_get_car_price( $class = '', $id = null ) {
		global $car_dealer_options, $post;
		$currency_code     = isset( $car_dealer_options['cars-currency-symbol'] ) ? $car_dealer_options['cars-currency-symbol'] : '';
		$currency_symbol   = cdhl_get_currency_symbols( $currency_code );
		$price_html        = '<div class="price car-price ' . $class . '">';
			$regular_price = 0;
		$sale_price        = 0;
			$car_id        = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
			$regular_price = get_post_meta( $car_id, 'regular_price', true );
			$regular_price = floatval( $regular_price );
			$sale_price    = get_post_meta( $car_id, 'sale_price', true );
			$sale_price    = floatval( $sale_price );
		if ( $regular_price > 0 && $sale_price > 0 ) {
			$price_html .= '<span class="old-price"> ' . esc_html( $currency_symbol . $regular_price ) . '</span>';
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol . $sale_price ) . '</span>';
		} elseif ( 0 === $regular_price || empty( $regular_price ) && $sale_price > 0 ) {
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol . $sale_price ) . '</span>';
		} elseif ( 0 === $sale_price || empty( $sale_price ) && $regular_price > 0 ) {
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol . $regular_price ) . '</span>';
		} elseif ( $sale_price > 200000 ){
			$price_html .= '<span class="new-price"> ' . esc_html( 'Contact Our Sales' ) . '</span>';
		} else {
			$price_html .= '<span class="new-price"> ' . esc_html( $currency_symbol ) . '0.00</span>';
		}
		$price_html .= '</div>';
		return $price_html;
	}
}

if ( ! function_exists( 'cardealer_get_car_price_array' ) ) {
	/**
	 * CAR Price array
	 *
	 * @param string $id .
	 */
	function cardealer_get_car_price_array( $id = null ) {
		global $car_dealer_options, $post;
		$currency_code   = $car_dealer_options['cars-currency-symbol'];
		$currency_symbol = cdhl_get_currency_symbols( $currency_code );
		$price_arr       = array();
		$regular_price   = 0;
		$sale_price      = 0;
		$car_id          = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		$regular_price   = get_post_meta( $car_id, 'regular_price', true );
		$regular_price   = (int) $regular_price;
		$sale_price      = get_post_meta( $car_id, 'sale_price', true );
		$sale_price      = (int) $sale_price;
		if($sale_price > 200000){
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => 0,
				'sale_price'      => 'Contact Our Sales',
			);
		}
		if ( $regular_price > 0 && $sale_price > 0 ) {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => $regular_price,
				'sale_price'      => $sale_price,
			);
		} elseif ( 0 === $regular_price || empty( $regular_price ) && $sale_price > 0 ) {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => 0,
				'sale_price'      => $sale_price,
			);
		} elseif ( 0 === $sale_price || empty( $sale_price ) && $regular_price > 0 ) {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => $regular_price,
				'sale_price'      => 0,
			);
		} else {
			$price_arr = array(
				'currency_symbol' => $currency_symbol,
				'regular_price'   => 0,
				'sale_price'      => 0,
			);
		}
		return $price_arr;
	}
}

if ( ! function_exists( 'cardealer_template_chooser' ) ) {
	/**
	 * Set template on search cars in cars catalog page
	 *
	 * @param string $template .
	 */
	function cardealer_template_chooser( $template ) {
		global $wp_query, $car_dealer_options;
		if ( $wp_query->is_search && is_post_type_archive( 'cars' ) ) {
			return locate_template( 'archive-cars.php' );  // redirect to archive-search.php .
		} elseif ( is_post_type_archive( 'cars' ) || ( isset( $car_dealer_options['cars_inventory_page'] ) && '' !== $car_dealer_options['cars_inventory_page'] && is_page( $car_dealer_options['cars_inventory_page'] ) ) ) { // if cars post type and archive page.
			return locate_template( 'archive-cars.php' );  // redirect to archive-search.php .
		}
		return $template;
	}
}
add_filter( 'template_include', 'cardealer_template_chooser' );

if ( ! function_exists( 'cardealer_get_carplaceholder' ) ) {
	/**
	 * Default cars placeholder image
	 *
	 * @param string $size .
	 * @param string $return_type .
	 */
	function cardealer_get_carplaceholder( $size = '', $return_type = 'image' ) {
		global $car_dealer_options;

		$url  = CDHL_URL;
		$url .= 'images/carplaceholder.jpg';
		if ( '' !== $size ) {
			if ( 'car_thumbnail' === $size ) {
				$meta = 'width="190" height="138"';
			} elseif ( 'car_catalog_image' === $size ) {
				if ( ( is_post_type_archive( 'cars' ) && ! wp_is_mobile() ) || ( isset( $_POST['action'] ) && 'cardealer_cars_filter_query' === $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$getlayout = cardealer_get_cars_list_layout_style();
					$col       = cardealer_get_grid_column();

					$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
					$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 'inline';
					if ( ! empty( $getlayout ) ) {
						if ( 'view-list' === $getlayout ) {
							$col = 3;
						} else {
							if ( 'no' === $listing_sidebar || 'off-canvas' === $desktop_filter_location ) {
								$col = 3;
							}
						}
					}
					if ( 4 === $col ) {
						$meta = 'width="187" height="134"';
					} else {
						$meta = 'width="265" height="190"';
					}
				} else {
					$meta = 'width="265" height="190"';
				}
			} elseif ( 'car_list_thumbnail' === $size ) {
				$meta = 'width="110" height="79"';
			} elseif ( 'large' === $size || 'car_single_image' === $size ) {
				$meta = 'class="img-responsive"';
			} elseif ( 'car_tabs_image' === $size ) {
				$meta = 'class="img-responsive"';
			} elseif ( 'cardealer-50x50' === $size ) {
				$meta = 'width="50" height="50"';
			} else {
				$meta = 'width="265" height="190"';
			}
		}

		return ( ! empty( $return_type ) && 'image' === $return_type ) ? '<img src="' . esc_url( $url ) . '" class="img-responsive" ' . $meta . ' alt="carplaceholder"/>' : $url;
	}
}

if ( ! function_exists( 'cardealer_get_cars_status' ) ) {
	/**
	 * Get cars status
	 *
	 * @param string $car_id .
	 * @param string $echo .
	 */
	function cardealer_get_cars_status( $car_id = null, $echo = false ) {

		if ( ! $car_id ) {
			return false;
		}

		$html       = '';
		$car_status = '';

		$car_status = get_post_meta( $car_id, 'car_status', true );
		if ( ! empty( $car_status ) ) {
			if ( 'sold' === $car_status ) {
				$html = '<span class="label car-status ' . $car_status . '">' . esc_html__( 'SOLD', 'cardealer' ) . '</span>';
			}
		}


		/**
		 * Filters the HTML of the vehicle status(SOLD/UNSOLD).
		 *
		 * @since 1.0
		 *
		 * @param string      $html  HTML of the vehicle status badge.
		 * @param int         $car_id Vehicle ID.
		 * @visible           true
		 */

		$html = apply_filters( 'cardealer_get_cars_status', $html, $car_id );
		if ( $echo ) {
			echo wp_kses(
				$html,
				array(
					'span' => array(
						'class' => true,
					),
				)
			);
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'cardealer_get_cars_condition' ) ) {
	/**
	 * Get cars condition
	 *
	 * @param string $id .
	 * @param string $echo .
	 */
	function cardealer_get_cars_condition( $id = null, $echo = false ) {
		global $car_dealer_options;
		if ( ! $id || ( isset( $car_dealer_options['display-condition-tags'] ) && 'no' === $car_dealer_options['display-condition-tags'] ) ) {
			return false;
		}

		$html  = '';
		$args  = array(
			'orderby' => 'name',
			'order'   => 'ASC',
			'fields'  => 'all',
		);
		$terms = wp_get_post_terms( $id, 'car_condition', $args );

		if ( ! is_wp_error( $terms ) && isset( $terms ) && ! empty( $terms ) ) {
			$is_wpml   = cardealer_is_wpml_active();
			$term_name = $terms[0]->name;
			if ( $is_wpml ) {
				$lang_term_name = cardealer_get_term_for_default_lang( $terms[0]->term_id, 'car_condition' );
				$term_name      = $lang_term_name->name;
			}

			if ( preg_match( '(new|New)', $term_name ) === 1 ) {
				$class = 'new';
			} elseif ( preg_match( '(used|Used)', $term_name ) === 1 ) {
				$class = 'used';
			} elseif ( preg_match( '(certified|Certified)', $term_name ) === 1 ) {
				$class = 'certified';
			} else {
				$class = $terms[0]->slug;
			}

			$color = get_term_meta( $terms[0]->term_id, 'label_color', true );
			$html  = '<span class="label car-condition ' . esc_attr( $class ) . '"';
			if ( $color ) {
				$html .= ' style="background:' . esc_attr( $color ) . '"';
			}
			$html .= ' >' . esc_html( $terms[0]->name ) . '</span>';
		}
		/**
		 * Filters the HTML of the vehicle condition tag.
		 *
		 * @since 1.0
		 *
		 * @param string       $html HTML of the vehicle condition tag.
		 * @param int          $id   Vehicle ID.
		 * @visible            true
		 */

		$html = apply_filters( 'cardealer_get_cars_condition', $html, $id );
		if ( $echo ) {
			echo wp_kses(
				$html,
				array(
					'span' => array(
						'class' => true,
						'style' => true,
					),
				)
			);
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'cardealer_get_term_for_default_lang' ) ) {
	/**
	 * Get default language terms object
	 *
	 * @param string $term .
	 * @param string $taxonomy .
	 */
	function cardealer_get_term_for_default_lang( $term, $taxonomy ) {
			global $sitepress;
			global $icl_adjust_id_url_filter_off;
			$term_id                      = is_int( $term ) ? $term : $term->term_id;
			$default_term_id              = (int) icl_object_id( $term_id, $taxonomy, true, $sitepress->get_default_language() );
			$orig_flag_value              = $icl_adjust_id_url_filter_off;
			$icl_adjust_id_url_filter_off = true;
			$term                         = get_term( $default_term_id, $taxonomy );
			$icl_adjust_id_url_filter_off = $orig_flag_value;
			return $term;
	}
}
if ( ! function_exists( 'cardealer_is_wpml_active' ) ) {
	/**
	 * Check if WPML is active
	 *
	 * @return bool
	 */
	function cardealer_is_wpml_active() {
		return ( class_exists( 'SitePress' ) ? true : false );
	}
}

if ( ! function_exists( 'cardealer_get_cars_image' ) ) {
	/**
	 * Get cars images
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_cars_image( $car_size = 'car_catalog_image', $id = null ) {
		if ( empty( $car_size ) ) {
			$car_size = 'car_catalog_image';
		}
		global $post;
		$car_id = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		if ( function_exists( 'get_field' ) ) {
			$images = get_field( 'car_images', $car_id );

			if ( ! empty( $images ) ) {
				if ( ( isset( $_POST['action'] ) && 'cardealer_load_more_vehicles' === $_POST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$img = '<img class="img-responsive" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '" src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '"/>';
				} elseif ( cardealer_lazyload_enabled() && isset( $images[0]['sizes'] ) && isset( $images[0]['alt'] ) ) {
					$img = '<img class="img-responsive cardealer-lazy-load" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '" src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '"/>';
				} else {
					if ( isset( $images[0]['sizes'] ) && isset( $images[0]['alt'] ) && isset( $images[0]['sizes'][ $car_size ] ) ) {
						$img = '<img class="img-responsive" src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '"/>';
					} else {
						$img = cardealer_get_carplaceholder( $car_size );
					}
				}
			} else {
				$img = cardealer_get_carplaceholder( $car_size );
			}
		} else {
			$img = cardealer_get_carplaceholder( $car_size );
		}
		return $img;
	}
}

if ( ! function_exists( 'cardealer_get_cars_owl_image' ) ) {

	/**
	 * Get cars images for owl carousal
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_cars_owl_image( $car_size = 'car_catalog_image', $id = null ) {
		if ( empty( $car_size ) ) {
			$car_size = 'car_catalog_image';
		}
		global $post;
		$car_id = ( isset( $id ) && null !== $id ) ? $id : $post->ID;
		if ( function_exists( 'get_field' ) ) {
			$images = get_field( 'car_images', $car_id );
			if ( ! empty( $images ) ) {
				if ( ( isset( $_POST['action'] ) && 'cardealer_load_more_vehicles' === $_POST['action'] ) || cardealer_lazyload_enabled() ) { // phpcs:ignore WordPress.Security.NonceVerification
					$img = '<img class="img-responsive owl-lazy" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '" src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '"/>';
				} else {
					$img = '<img class="img-responsive" src="' . esc_url( $images[0]['sizes'][ $car_size ] ) . '" alt="' . esc_attr( $images[0]['alt'] ) . '" width="' . esc_attr( $images[0]['sizes'][ $car_size . '-width' ] ) . '" height="' . esc_attr( $images[0]['sizes'][ $car_size . '-height' ] ) . '"/>';
				}
			} else {
				$img = cardealer_get_carplaceholder( $car_size );
			}
		} else {
			$img = cardealer_get_carplaceholder( $car_size );
		}
		return $img;
	}
}
if ( ! function_exists( 'cardealer_get_single_image_url' ) ) {
	/**
	 * Single image url
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_single_image_url( $car_size = 'car_catalog_image', $id = null ) {
		global $post;

		$car_id   = '';
		$url      = CARDEALER_URL . '/images/carplaceholder.jpg';
		$img_path = CARDEALER_PATH . '/images/carplaceholder.jpg';

		if ( function_exists( 'get_field' ) ) {
			if ( ! empty( $id ) ) {
				$car_id = $id;
			} elseif ( isset( $post ) ) {
				$car_id = $post->ID;
			}
			$car_images = get_field( 'car_images', $car_id );
			if ( $car_images && is_array( $car_images ) && isset( $car_images[0]['url'] ) && ! empty( $car_images[0]['url'] ) ) {
				$url = $car_images[0]['url'];
			}
		}

		return $url;
	}
}

if ( ! function_exists( 'cardealer_get_images_url' ) ) {
	/**
	 * Image url
	 *
	 * @param string $car_size .
	 * @param string $id .
	 */
	function cardealer_get_images_url( $car_size = 'car_catalog_image', $id = null ) {
		global $post;
		$url = null;
		if ( function_exists( 'get_field' ) ) {
			if ( isset( $id ) && ! empty( $id ) ) {
				$car_id = $id;
			} elseif ( isset( $post ) ) {
				$car_id = $post->ID;
			}
			$car_images = get_field( 'car_images', $car_id );
			$url        = array();
			if ( ! empty( $car_images ) ) {
				foreach ( $car_images as $car_image ) {
					$url[] = $car_image['url'];
				}
			}
		}
		return $url;
	}
}
if ( ! function_exists( 'cardealer_get_price_filters' ) ) :
	/**
	 * Price filter
	 */
	function cardealer_get_price_filters( $args = array() ) {
		global $car_dealer_options, $cardealer_price_range_instance;
		$cardealer_price_range_instance = ( isset( $cardealer_price_range_instance ) ) ? $cardealer_price_range_instance + 1 : 1;

		$price_range_slider_id = "dealer-slider-amount-$cardealer_price_range_instance";
		$price_slider_range_id = "slider-range-$cardealer_price_range_instance";

		$pgs_min_price = isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$pgs_max_price = isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		// echo($pgs_max_price);
		// Find min and max price in current result set.
		$prices = cardealer_get_car_filtered_price();
		$min    = floor( $prices->min_price );
		$max    = ceil( $prices->max_price );

		if ( $min === $max ) {
			return;
		}

		// Range Slider Step.
		$step = 100;
		if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
			$step = $car_dealer_options['price_range_step'];
		}

		$price_range_slider_args = array(
			'pgs_min_price'         => $pgs_min_price,
			'pgs_max_price'         => $pgs_max_price,
			'min'                   => $min,
			'max'                   => $max,
			'step'                  => $step,
			'price_range_slider_id' => $price_range_slider_id,
			'price_slider_range_id' => $price_slider_range_id,
			'price_range_instance'  => $cardealer_price_range_instance,
		);

		if ( isset( $args['filter_location'] ) && ! empty( $args['filter_location'] ) ) {
			$price_range_slider_args['filter_location'] = $args['filter_location'];
		}

		ob_start();
		get_template_part( 'template-parts/cars/archive-sections/price-range-slider', null, $price_range_slider_args );
		$html = ob_get_clean();

		/**
		 * Filters the vehicle price slider HTML layout.
		 *
		 * @since 1.0
		 * @param string     $html HTML string of the vehicle price slider HTML layout.
		 * @visible          true
		 */
		$html = apply_filters( 'car_dealer_price_slider_html', $html );
		echo wp_kses(
			$html,
			array(
				'div'    => array(
					'class' => true,
					'id'    => true,
				),
				'input'  => array(
					'class'     => true,
					'type'      => true,
					'id'        => true,
					'name'      => true,
					'value'     => true,
					'data-min'  => true,
					'data-max'  => true,
					'data-step' => true,
					'readonly'  => true,
				),
				'label'  => array(
					'class' => true,
					'for'   => true,
				),
				'button' => array(
					'class' => true,
					'id'    => true,
				),
			)
		);
	}
endif;


if ( ! function_exists( 'cardealer_get_year_range_filters' ) ) :
	/**
	 * Year rang filter
	 *
	 * @param string $cfb .
	 */
	function cardealer_get_year_range_filters( $cfb = '', $args = array() ) {
		global $cardealer_year_range_instance;
		$cardealer_year_range_instance = ( isset( $cardealer_year_range_instance ) ) ? $cardealer_year_range_instance + 1 : 1;

		$year_range_slider_id = "dealer-slider-year-range-$cardealer_year_range_instance";
		$year_slider_range_id = "slider-year-range-$cardealer_year_range_instance";
		$pgs_year_range_min   = isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$pgs_year_range_max   = isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		// Find min and max price in current result set.
		$year_range = ( function_exists( 'cardealer_get_year_range' ) ) ? cardealer_get_year_range() : '';

		if ( empty( $year_range ) ) {
			return;
		}

		$yearmin = floor( $year_range['min_year'] );
		$yearmax = ceil( $year_range['max_year'] );

		if ( $yearmin === $yearmax ) {
			return;
		}

		$location = ( isset( $args['location'] ) && ! empty( $args['location'] ) ) ? $args['location'] : '-';

		ob_start();
		?>
		<div class="year-range-slider-wrapper" data-range-location="<?php echo esc_attr( $location ); ?>">
			<div class="year-range-slide">
				<div class="year_range">
					<input type="hidden" class="pgs-year-range-min" name="min_year" value="<?php echo esc_attr( $pgs_year_range_min ); ?>" data-yearmin="<?php echo esc_attr( $yearmin ); ?>" />
					<input type="hidden" class="pgs-year-range-max" name="max_year" value="<?php echo esc_attr( $pgs_year_range_max ); ?>" data-yearmax="<?php echo esc_attr( $yearmax ); ?>" />
					<?php
					if ( 'filters' === $location ) {
						?>
						<div id="<?php echo esc_attr( $year_slider_range_id ); ?>" class="slider-year-range range-slide-slider" data-cfb="<?php echo esc_attr( $cfb ); ?>"></div>
						<?php
					}
					?>
					<div class="range-btn-wrapper year-range-btn-wrapper">
						<div class="year-range-slider-value-wrapper range-slider-value-wrapper">
							<label for="<?php echo esc_attr( $year_range_slider_id ); ?>"><?php echo esc_html__( 'Year:', 'cardealer' ); ?></label>
							<input type="text" id="<?php echo esc_attr( $year_range_slider_id ); ?>" class="dealer-slider-year-range" readonly="" class="amount" value="" />
						</div>
					</div>
					<?php
					if ( 'filters' !== $location ) {
						?>
						<div id="<?php echo esc_attr( $year_slider_range_id ); ?>" class="slider-year-range range-slide-slider" data-cfb="<?php echo esc_attr( $cfb ); ?>"></div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
		$html = ob_get_clean();
		return apply_filters( 'cardealer_year_range_filters', $html );
	}
endif;

if ( ! function_exists( 'cardealer_is_year_range_active' ) ) :
	/**
	 * Check year rang slider is active for listing page
	 */
	function cardealer_is_year_range_active() {
		global $car_dealer_options;

		$year_range_active      = true;
		$cars_year_range_slider = ( isset( $car_dealer_options['cars-year-range-slider'] ) && ! empty( $car_dealer_options['cars-year-range-slider'] ) ) ? $car_dealer_options['cars-year-range-slider'] : 'no';

		if ( 'no' === $cars_year_range_slider ) {
			$year_range_active = false;
		}

		return $year_range_active;
	}
endif;

if ( ! function_exists( 'cardealer_get_year_range_slider_location' ) ) :
	/**
	 * get year range slider location.
	 */
	function cardealer_get_year_range_slider_location() {
		global $car_dealer_options;

		$key       = 'cars_year_range_slider_location';
		$locations = array(
			'in_filters',
			'in_widgets',
		);

		$location = ( isset( $car_dealer_options[ $key ] ) && ! empty( $car_dealer_options[ $key ] ) && in_array( $car_dealer_options[ $key ], $locations, true ) ) ? $car_dealer_options[ $key ] : 'in_filters';

		return $location;
	}
endif;

if ( ! function_exists( 'cardealer_get_car_filtered_price' ) ) {
	/**
	 * Get filtered min price for current list.
	 *
	 * @return int
	 */
	function cardealer_get_car_filtered_price() {
		global $wpdb, $car_dealer_options;

		// @codingStandardsIgnoreStart

		// Current site prefix.
		$end_condition = '';
		$tbprefix      = $wpdb->prefix;
		$sql           = 'SELECT ';
		$sql          .= ' min( FLOOR( price_meta.meta_value ) ) as min_price,';
		$sql          .= ' 150000 as max_price';
		$sql          .= ' FROM ' . $tbprefix . 'posts';

		$sql .= ' LEFT JOIN ' . $tbprefix . 'postmeta as price_meta ON ' . $tbprefix . 'posts.ID = price_meta.post_id';
		if ( is_tax( 'vehicle_cat' ) ) {
			global $wp_query;
			$term_id       = get_term_by( 'slug', $wp_query->query_vars['vehicle_cat'], 'vehicle_cat' );
			$end_condition = ' AND ' . $tbprefix . 'term_relationships.term_taxonomy_id=' . $term_id->term_taxonomy_id;
			$sql          .= ' LEFT JOIN ' . $tbprefix . 'term_relationships ON (' . $tbprefix . 'posts.ID = ' . $tbprefix . 'term_relationships.object_id)';
		}
		$sql .= ' INNER JOIN ' . $tbprefix . 'postmeta ON (' . $tbprefix . 'posts.ID = ' . $tbprefix . 'postmeta.post_id )';
		$sql .= ' WHERE ' . $tbprefix . "posts.post_type IN ('cars')";
		$sql .= ' AND ' . $tbprefix . "posts.post_status = 'publish'";
		$sql .= " AND price_meta.meta_key IN ('sale_price')$end_condition";
		if ( isset( $car_dealer_options['car_no_sold'] ) && 0 === (int) $car_dealer_options['car_no_sold'] ) {
			$sql .= ' AND ( ( ' . $tbprefix . "postmeta.meta_key = 'car_status' AND " . $tbprefix . "postmeta.meta_value != 'sold' ) )";
		}

		$price_arr = $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// code for price step theme option.
		if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
			$min_difference        = $car_dealer_options['price_range_step'] - ( $price_arr->min_price % $car_dealer_options['price_range_step'] );
			$price_arr->min_price += $min_difference - $car_dealer_options['price_range_step']; // Round up min price.
			$max_difference        = $car_dealer_options['price_range_step'] - ( $price_arr->max_price % $car_dealer_options['price_range_step'] );
			$price_arr->max_price += $max_difference; // Round up max price.
		}

		return apply_filters( 'cd_vehicle_filtered_price', $price_arr );

		// @codingStandardsIgnoreEnd
	}
}
if ( ! function_exists( 'cardealer_get_year_range' ) ) {
	/**
	 * Get filtered year range.
	 *
	 * @return int
	 */
	function cardealer_get_year_range() {
		global $wpdb;
		$terms = get_terms(
			array(
				'taxonomy'   => 'car_year',
				'hide_empty' => true,
				'order'      => 'ASC',
			)
		);

		$taxonomy_name = get_taxonomy( 'car_year' );
		$data          = array();
		if ( ! empty( $taxonomy_name ) ) {
			$slug  = $taxonomy_name->rewrite['slug'];
			$label = $taxonomy_name->labels->menu_name;

			if ( ! empty( $terms ) ) {
				$year_arr = array();
				foreach ( $terms as $tdata ) {
					$year_arr[] = $tdata->slug;
				}
				$first = reset( $year_arr );
				$last  = end( $year_arr );
				$data  = array(
					'min_year' => $first,
					'max_year' => $last,
				);
			}
		}
		/**
		 * Filters the year range to be used in inventory filter.
		 *
		 * @since 1.0
		 * @param array     $data Year range array - minimum year and maximum year.
		 * @hooked cardealer_list_layout_style_lazyload - 10
		 * @visible          true
		 */
		return apply_filters( 'cardealer_year_range', $data );
	}
}

if ( ! function_exists( 'cardealer_get_show_hide_list_layout_style' ) ) {
	/**
	 * Show Hide list layout style
	 */
	function cardealer_get_show_hide_list_layout_style() {
		$getlayout = array(
			'view-grid',
			'view-masonry',
			'view-list',
		);
		/**
		 * Filters the layout style option for inventory listing(grid/list).
		 *
		 * @since 1.0
		 * @param  string    $getlayout Layout style selected for vehicle listing.
		 * @hooked cardealer_get_show_hide_list_layout_style - 10
		 * @visible          true
		 */
		return apply_filters( 'cardealer_get_show_hide_list_layout_style', $getlayout );
	}
}

if ( ! function_exists( 'cardealer_get_cars_list_layout_style' ) ) {
	/**
	 * Add layout style in cookie
	 */
	function cardealer_get_cars_list_layout_style() {
		global $car_dealer_options;

		// @codingStandardsIgnoreStart
		$getlayout = ( isset( $car_dealer_options['listing-layout'] ) && ! empty( $car_dealer_options['listing-layout'] ) ) ? $car_dealer_options['listing-layout'] : 'view-grid';
		if ( isset( $_REQUEST['lay_style'] ) && ! empty( $_REQUEST['lay_style'] ) ) {
			$getlayout = $_REQUEST['lay_style']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} elseif ( isset( $_COOKIE['lay_style'] ) && ! empty( $_COOKIE['lay_style'] ) ) {
			$getlayout = $_COOKIE['lay_style']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
		// @codingStandardsIgnoreEnd

		if ( ! in_array( $getlayout, array( 'view-grid', 'view-masonry', 'view-list' ) ) ) {
			$getlayout = 'view-grid';
		}

		/**
		 * Filters the layout style option for inventory listing(grid/list).
		 *
		 * @since 1.0
		 * @param  string    $getlayout Layout style selected for vehicle listing.
		 * @hooked cardealer_list_layout_style_lazyload - 10
		 * @visible          true
		 */
		return apply_filters( 'cardealer_list_layout_style', $getlayout );
	}
}

if ( ! function_exists( 'cardealer_get_cars_catlog_style' ) ) {
	/**
	 * Catalog style
	 */
	function cardealer_get_cars_catlog_style() {
		$getlayout = cardealer_get_cars_list_layout_style();
		$return    = 'yes';

		if ( $getlayout ) {
			switch ( $getlayout ) {
				case 'view-grid':
				case 'view-masonry':
					$return = 'yes';
					break;
				case 'view-list':
					$return = 'no';
					break;
			}
		}

		return $return;
	}
}

if ( ! function_exists( 'cardealer_get_default_sort_by' ) ) {
	/**
	 * Get default listing sort by dropdown option value
	 */
	function cardealer_get_default_sort_by() {
		global $car_dealer_options;
		$cars_orderby = '';
		if ( isset( $car_dealer_options['cars-default-sort-by'] ) ) {
			$cars_orderby = $car_dealer_options['cars-default-sort-by'];
		}
		return $cars_orderby;
	}
}

if ( ! function_exists( 'cardealer_get_default_sort_by_order' ) ) {
	/**
	 * Get default listing order by value
	 */
	function cardealer_get_default_sort_by_order() {
		global $car_dealer_options;
		$cars_order = 'desc';
		if ( isset( $car_dealer_options['cars-default-sort-by-order'] ) ) {
			$cars_order = $car_dealer_options['cars-default-sort-by-order'];
		}
		return $cars_order;
	}
}

if ( ! function_exists( 'cardealer_get_vehicle_listing_page_layout' ) ) {
	/**
	 * Vehicle listing layout style
	 *
	 * @param string $extra_classes .
	 */
	function cardealer_get_vehicle_listing_page_layout() {
		global $car_dealer_options;

		$layout = ( isset( $car_dealer_options['vehicle-listing-layout'] ) && ! empty( $car_dealer_options['vehicle-listing-layout'] ) ) ? $car_dealer_options['vehicle-listing-layout'] : 'default';

		return $layout;
	}
}

if ( ! function_exists( 'cardealer_cars_content_class' ) ) {
	/**
	 * Cars content class
	 *
	 * @param string $extra_classes .
	 */
	function cardealer_cars_content_class( $extra_classes = '' ) {
		global $car_dealer_options;

		$listing_layout          = cardealer_get_vehicle_listing_page_layout();
		$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
		$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 'inline';

		if ( 'default' === $listing_layout ) {
			if ( 'off-canvas' === $desktop_filter_location || 'no' === $listing_sidebar  || ! is_active_sidebar( 'listing-cars' ) ) {
				$content_class = 12;
			} else {
				$content_class = 9;
			}
		} else {
			$content_class = 9;
		}

		$classes   = array( 'content' );
		$classes[] = 'col-lg-' . $content_class . ' col-md-' . $content_class . ' col-sm-12';
		if ( ! empty( $extra_classes ) ) {
			$classes[] = $extra_classes;
		}

		echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}
if ( ! function_exists( 'cardealer_get_grid_column' ) ) {
	/**
	 * Grid column
	 */
	function cardealer_get_grid_column() {
		global $car_dealer_options, $sold_vehicle_pg;

		$col            = 3;
		$classes        = array();
		$getlayout      = '';
		$listing_layout = cardealer_get_vehicle_listing_page_layout();

		if ( 'lazyload' === $listing_layout && true !== $sold_vehicle_pg ) {
			$col = 5;
		} else {
			if ( isset( $car_dealer_options['cars-col-sel'] ) && ! empty( $car_dealer_options['cars-col-sel'] ) ) {
				$col = $car_dealer_options['cars-col-sel'];
			}

			$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
			$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 'inline';

			$getlayout = cardealer_get_cars_list_layout_style();
			if ( 'view-list' === $getlayout ) {
				$col = 4;
			} elseif ( ( 'view-grid' === $getlayout ) && ( 'off-canvas' === $desktop_filter_location || 'no' === $listing_sidebar ) ) {
				$col = 4;
			}
		}

		return apply_filters( 'cardealer_get_grid_column', $col );
	}
}
if ( ! function_exists( 'cardealer_grid_view_class' ) ) {
	/**
	 * Grid view class
	 */
	function cardealer_grid_view_class() {
		global $car_dealer_options;
		$classes         = array();
		$columns         = cardealer_get_grid_column();
		$grid_view_class = '';

		if ( 3 === $columns || '3' === $columns ) {
			$col = 4;
		}

		if ( 4 === $columns || '4' === $columns ) {
			$col = 3;
		}

		if ( wp_is_mobile() ) {
			$col = 4;
		}

		$getlayout = cardealer_get_cars_list_layout_style();

		if ( wp_is_mobile() || ( ! empty( $getlayout ) && 5 !== $columns ) ) {
			$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
			$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 	'inline';

			if ( 'off-canvas' === $desktop_filter_location || 'no' === $listing_sidebar ) {
				$classes[] = 'col-lg-3 col-md-3 col-sm-3 col-xs-6';
			} else {
				$classes[] = 'col-lg-' . $col . ' col-md-' . $col . ' col-sm-' . $col . ' col-xs-6';
			}

			if ( 'view-masonry' === $getlayout ) {
				$classes[] = 'masonry-item';
			}
		} elseif ( 5 === $columns ) {
			$classes[] = 'cd-lazy-load-item';
			$classes[] = 'masonry-item';
		}

		$classes = apply_filters( 'cardealer_grid_view_class', $classes, $columns );
		$classes = apply_filters( 'cardealer_grid_view_classes_list', $classes, $columns );

		$grid_view_class = implode( ' ', $classes );

		/**
		 * Filters the classes of grid view style for the inventory listing.
		 *
		 * @since 1.0
		 *
		 * @param string     $grid_view_class Class for grid listing of vehicles.
		 * @param array      $classes        Array of classes.
		 * @param string|int $columns        Number of columns.
		 * @visible          true
		 */
		$grid_view_class = apply_filters( 'cardealer_grid_view_classes', $grid_view_class, $classes, $columns );
		$grid_view_class = apply_filters( 'cardealer_grid_view_classes_str', $grid_view_class, $classes, $columns );

		echo 'class="' . esc_attr( $grid_view_class ) . '"';  // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

if ( ! function_exists( 'cardealer_cars_loop' ) ) {
	/**
	 * Cars loop
	 */
	function cardealer_cars_loop() {
		global $cars_loop;
		$cars_loop['loop']    = ! empty( $cars_loop['loop'] ) ? $cars_loop['loop'] + 1 : 1;
		$col                  = cardealer_get_grid_column();
		$cars_loop['columns'] = max( 1, ! empty( $cars_loop['columns'] ) ? $cars_loop['columns'] : $col );
		if ( 0 === ( $cars_loop['loop'] - 1 ) % $cars_loop['columns'] || 1 === $cars_loop['columns'] ) {
			return 'first';
		} elseif ( 0 === $cars_loop['loop'] % $cars_loop['columns'] ) {
			return 'last';
		} else {
			return '';
		}
	}
}
if ( ! function_exists( 'cardealer_list_view_class_1' ) ) {
	/**
	 * Function to get list layout class for first section
	 */
	function cardealer_list_view_class_1() {
		global $car_dealer_options;
		$classes    = array();
		$getlayout  = cardealer_get_cars_list_layout_style();
		$list_style = cardealer_get_inv_list_style();

		if ( 'view-list' === $getlayout ) {

			$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
			$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 	'inline';

			if ( is_page_template( 'templates/sold-cars.php' ) ) {
				if ( 'classic' === $list_style ) {
					$classes[] = 'col-lg-3 col-md-3 col-sm-4';
				} else {
					$classes[] = 'col-lg-3 col-md-4 col-sm-6';
				}
			} else {
				if ( 'off-canvas' === $desktop_filter_location || 'no' === $listing_sidebar ) {
					if ( 'classic' === $list_style ) {
						$classes[] = 'col-lg-3 col-md-3 col-sm-4';
					} else {
						$classes[] = 'col-lg-3 col-md-4 col-sm-6';
					}
				} else {
					if ( 'classic' === $list_style ) {
						$classes[] = 'col-lg-4 col-md-5 col-sm-4';
					} else {
						$classes[] = 'col-lg-4 col-md-4 col-sm-4';
					}
				}
			}

			echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}

if ( ! function_exists( 'cardealer_list_view_class_2' ) ) {
	/**
	 * List view class 2
	 */
	function cardealer_list_view_class_2() {
		global $car_dealer_options;
		$classes    = array();
		$getlayout  = cardealer_get_cars_list_layout_style();
		$list_style = cardealer_get_inv_list_style();

		if ( 'view-list' === $getlayout ) {

			$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
			$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 	'inline';

			if ( is_page_template( 'templates/sold-cars.php' ) ) {
				if ( 'classic' === $list_style ) {
					$classes[] = 'col-lg-9 col-md-9 col-sm-8';
				} else {
					$classes[] = 'col-lg-9 col-md-8 col-sm-6';
				}
			} else {
				if ( 'off-canvas' === $desktop_filter_location || 'no' === $listing_sidebar ) {
					if ( 'classic' === $list_style ) {
						$classes[] = 'col-lg-9 col-md-9 col-sm-8';
					} else {
						$classes[] = 'col-lg-9 col-md-8 col-sm-6';
					}
				} else {

					if ( 'classic' === $list_style ) {
						$classes[] = 'col-lg-8 col-md-7 col-sm-8';
					} else {
						$classes[] = 'col-lg-8 col-md-8 col-sm-8';
					}
				}
			}

			echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}
if ( ! function_exists( 'cardealer_get_cars_details_page_sidebar_position' ) ) {
	/**
	 * Cars details page sidebar position
	 */
	function cardealer_get_cars_details_page_sidebar_position() {
		global $car_dealer_options;
		$details_page_sidebar = 'left';
		if ( isset( $car_dealer_options['cars-details-page-sidebar'] ) && ! empty( $car_dealer_options['cars-details-page-sidebar'] ) ) {
			$details_page_sidebar = $car_dealer_options['cars-details-page-sidebar'];
		}
		return $details_page_sidebar;
	}
}

if ( ! function_exists( 'cardealer_get_widget_fuel_efficiency' ) ) {
	/**
	 * Widget fuel efficiency
	 */
	function cardealer_get_widget_fuel_efficiency() {
		global $car_dealer_options;
		if ( isset( $car_dealer_options['cars-details-page-sidebar'] ) && 'no' === $car_dealer_options['cars-details-page-sidebar'] ) {
			$cars_fuel_efficiency_option = ( isset( $car_dealer_options['cars-fuel-efficiency-option'] ) ) ? $car_dealer_options['cars-fuel-efficiency-option'] : 1;
			if ( 1 === (int) $cars_fuel_efficiency_option ) {
				the_widget( 'CarDealer_Helper_Widget_Fuel_Efficiency' );
			}
		}
	}
}

if ( ! function_exists( 'cardealer_cars_sidebar_class' ) ) {
	/**
	 * Cars sidebar class
	 *
	 * @param string $custom_class add custom class.
	 */
	function cardealer_cars_sidebar_class( $custom_class = 'sidebar' ) {
		global $car_dealer_options;

		$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
		$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 	'inline';

		if ( 'off-canvas' === $desktop_filter_location || 'no' === $listing_sidebar ) {
			$content_class = 12;
		} else {
			$content_class = 3;
		}

		$classes   = array( $custom_class );
		$classes[] = 'col-lg-' . $content_class . ' col-md-' . $content_class . ' col-sm-12';
		echo 'class="' . join( ' ', $classes ) . '"'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}
if ( ! function_exists( 'cardealer_get_car_catlog_sidebar' ) ) {
	/**
	 * Catlog sidebar left
	 */
	function cardealer_get_car_catlog_sidebar() {
		global $car_dealer_options;

		$layout                  = cardealer_get_vehicle_listing_page_layout();
		$listing_sidebar         = isset( $car_dealer_options['listing_sidebar'] ) ? $car_dealer_options['listing_sidebar'] : 'left';
		$desktop_filter_location = isset( $car_dealer_options['vehicle_listing_desktop_filter_location'] ) ? $car_dealer_options['vehicle_listing_desktop_filter_location'] : 	'inline';

		if ( 'lazyload' === $layout ) {
			$listing_sidebar = 'left';
		}

		if ( 'off-canvas' !== $desktop_filter_location && ( 'left' === $listing_sidebar || 'right' === $listing_sidebar ) ) {
			if ( is_active_sidebar( 'listing-cars' ) ) {
				?>
				<aside id="sleft" <?php cardealer_cars_sidebar_class(); ?>>
					<div class="listing-sidebar">
						<?php dynamic_sidebar( 'listing-cars' ); ?>
					</div>
				</aside>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'cardealer_get_catlog_view' ) ) {
	/**
	 * Catlog view
	 */
	function cardealer_get_catlog_view() {
		global $car_dealer_options;

		$theme_color      = isset( $car_dealer_options['site_color_scheme_custom']['color'] ) ? $car_dealer_options['site_color_scheme_custom']['color'] : '';
		$getlayout        = cardealer_get_cars_list_layout_style();
		$list_view_layout = cardealer_get_show_hide_list_layout_style();
		$layout_css_style = array();

		if ( ! empty( $list_view_layout ) ) {
			?>
			<div class="grid-view change-view-button">
				<div class="view-icon">
					<?php
					foreach ( $list_view_layout as $key => $value ) {
						$layout_css_style = ( $getlayout === $value ) ? "background-color:$theme_color;" : '';
						if ( 'view-grid' === $value ) {
							$class = 'view-grid-full';
						} elseif( 'view-list' === $value ) {
							$class = 'view-list-full';
						} else {
							$class = 'view-grid-masonry-full';
						}
						?>
						<a class="catlog-layout <?php echo esc_attr( $class ); ?>" data-id="<?php echo esc_attr( $value ); ?>" href="javascript:void(0)">
							<span style="<?php echo esc_attr( $layout_css_style ); ?>">
								<i class="<?php echo esc_attr( $class ); ?>"></i>
							</span>
						</a>
						<?php
					}
					?>
				</div>
			</div><!--.grid-view-->
			<?php
		}
	}
}

if ( ! function_exists( 'cardealer_cars_catalog_ordering' ) ) :
	/**
	 * Catalog ordering
	 */
	function cardealer_cars_catalog_ordering( $args = array() ) {
		global $car_dealer_options;

		$enable_ordering  = true;
		$getlayout        = cardealer_get_cars_list_layout_style();

		// @codingStandardsIgnoreStart
		parse_str( $_SERVER['QUERY_STRING'], $params ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$query_string = '?' . $_SERVER['QUERY_STRING'];  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		// @codingStandardsIgnoreEnd

		// Vehicle listing layout.
		$layout = cardealer_get_vehicle_listing_page_layout();

		// replace it with theme option.
		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$per_page = $car_dealer_options['cars-per-page'];
		} else {
			$per_page = 12;
		}

		$cars_orderby_selected = cardealer_get_default_sort_by(); // get default option value.
		if ( isset( $params['cars_orderby'] ) && ! empty( $params['cars_orderby'] ) ) {
			$cars_orderby_selected = $params['cars_orderby'];
		}

		$cars_order_selected = cardealer_get_default_sort_by_order();// get default option value.
		if ( isset( $params['cars_order'] ) && ! empty( $params['cars_order'] ) && in_array( $params['cars_order'], array( 'desc', 'asc' ), true ) ) {
			$cars_order_selected = $params['cars_order'];
		}

		$cars_pp_selected = ( isset( $params['cars_pp'] ) && ! empty( $params['cars_pp'] ) ) ? $params['cars_pp'] : $per_page;

		if ( isset( $args['enable_ordering'] ) && is_bool( $args['enable_ordering'] ) ) {
			$enable_ordering = $args['enable_ordering'];
		}

		if ( $enable_ordering ) {
			?>
			<div class="selected-box pgs-cars-pp-outer">
				<select name="cars_pp" id="pgs_cars_pp" class="cd-select-box">
					<?php
					for ( $i = 1; $i <= 5; $i++ ) {
						$per_page_value = $per_page * $i;
						?>
						<option value="<?php echo esc_html( $per_page_value ); ?>" <?php selected( $cars_pp_selected, $per_page_value ); ?>><?php echo esc_html( $per_page_value ); ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<?php
		}

		$cardealer_orderby_types = array(
			'name'       => esc_html__( 'Sort by Name', 'cardealer' ),
			'sale_price' => esc_html__( 'Sort by Price', 'cardealer' ),
			'date'       => esc_html__( 'Sort by Date', 'cardealer' ),
			'year'       => esc_html__( 'Sort by Year', 'cardealer' ),
		);
		?>
		<div class="selected-box pgs-cars-orderby-outer">
			<div class="select">
				<select class="select-box cd-select-box" name="cars_orderby" id="pgs_cars_orderby">
					<option value=""><?php esc_html_e( 'Sort by Default', 'cardealer' ); ?></option>
					<?php
					foreach ( $cardealer_orderby_types as $cardealer_orderby_v => $cardealer_orderby_label ) {
						?>
						<option value="<?php echo esc_attr( $cardealer_orderby_v ); ?>" <?php selected( $cars_orderby_selected, $cardealer_orderby_v ); ?>><?php echo esc_html( $cardealer_orderby_label ); ?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<?php
		if ( 'asc' === $cars_order_selected ) {
			?>
			<div class="cars-order text-right"><a id="pgs_cars_order" data-order="desc" data-current_order="asc" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a></div>
			<?php
		} else {
			?>
			<div class="cars-order text-right"><a id="pgs_cars_order" data-order="asc" data-current_order="desc" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a></div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'cardealer_get_taxonomys_array' ) ) {
	/**
	 * Taxonomys array
	 */
	function cardealer_get_taxonomys_array() {
		$taxonomies = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_mileage', 'car_fuel_type', 'car_fuel_economy', 'car_trim', 'car_transmission', 'car_condition', 'car_drivetrain', 'car_engine', 'car_exterior_color', 'car_interior_color', 'car_stock_number', 'car_vin_number', 'car_features_options' );

		$taxonomies_raw = get_object_taxonomies( 'cars' );

		foreach ( $taxonomies_raw as $new_tax ) {
			if ( in_array( $new_tax, $taxonomies, true ) ) {
				continue;
			}

			$new_tax_obj = get_taxonomy( $new_tax );
			if ( isset( $new_tax_obj->include_in_filters ) && true === (bool) $new_tax_obj->include_in_filters ) {
				$taxonomies[] = $new_tax;
			}
		}

		return apply_filters( 'cardealer_taxonomys_array', $taxonomies );
	}
}

if ( ! function_exists( 'cardealer_get_all_taxonomy_with_terms' ) ) {
	/**
	 * Taxonomy with terms
	 */
	function cardealer_get_all_taxonomy_with_terms() {
		$attributs = array();
		$taxonomys = cardealer_get_taxonomys_array();

		foreach ( $taxonomys as $tax ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $tax,
					'hide_empty' => true,
				)
			);

			$taxonomy_name = get_taxonomy( $tax );
			$slug          = $taxonomy_name->rewrite['slug'];
			$label         = $taxonomy_name->labels->singular_name;
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $tdata ) {
					$attributs[ $slug ]['terms'][] = $tdata->slug;
					$attributs[ $slug ]['label']   = $label;
					$attributs[ $slug ]['slug']    = $slug;
				}
			} else {
				$attributs[ $slug ]['label'] = $label;
				$attributs[ $slug ]['slug']  = $slug;
			}
		}
		return $attributs;
	}
}

if ( ! function_exists( 'cardealer_cars_get_catalog_ordering_args' ) ) {
	/**
	 * Pass arguments on cars listing page
	 *
	 * @param array $wp_query get the value.
	 */
	function cardealer_cars_get_catalog_ordering_args( $wp_query ) {

		global $wp_query, $car_dealer_options;
		$taxonomies   = cardealer_get_vehicles_taxonomies();
		$taxonomies   = array_values( $taxonomies );
		$tax_query    = array( 'relation' => 'AND' );
		$current_term = ( is_tax() ) ? $wp_query->get_queried_object() : '';

		$cars_inventory_page    = ( isset( $car_dealer_options['cars_inventory_page'] ) ) ? $car_dealer_options['cars_inventory_page'] : '';
		$is_cars_inventory_page = ( $wp_query->is_page() && 'page' === get_option( 'show_on_front' ) && '' !== $cars_inventory_page && absint( $wp_query->get( 'page_id' ) ) === absint( $cars_inventory_page ) );
		$is_cars_taxonomy_page  = ( is_tax() && $current_term && ( isset( $current_term->taxonomy ) && in_array( $current_term->taxonomy, $taxonomies ) ) );

		if (
			( ! is_admin() && $wp_query->is_main_query() && is_post_type_archive( 'cars' ) )
			|| $is_cars_inventory_page
			|| $is_cars_taxonomy_page
		) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict

			$wp_query->set( 'page_id', '' );
			$wp_query->is_singular          = false;
			$wp_query->is_post_type_archive = true;
			$wp_query->bis_archive          = true;
			$wp_query->is_page              = false;

			// @codingStandardsIgnoreStart
			parse_str( $_SERVER['QUERY_STRING'], $params );
			// @codingStandardsIgnoreEnd

			$pgs_min_price = isset( $params['min_price'] ) ? esc_attr( $params['min_price'] ) : 0;
			$pgs_max_price = isset( $params['max_price'] ) ? esc_attr( $params['max_price'] ) : 0;
			if ( $pgs_min_price > 0 || $pgs_max_price > 0 ) {
				$prices = cardealer_get_car_filtered_price();
				$min    = floor( $prices->min_price );
				$max    = ceil( $prices->max_price );

				if ( $min !== $pgs_min_price || $max !== $pgs_max_price ) {
					$args['meta_query'][] = array(
						'key'     => 'sale_price',
						'value'   => array( $pgs_min_price, $pgs_max_price ),
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					);
				}
			}
			/* Don't want to show sold car on car listing page */
			if ( isset( $car_dealer_options['car_no_sold'] ) && 0 === (int) $car_dealer_options['car_no_sold'] ) {
				$args['meta_query'][] =
					array(
						'key'     => 'car_status',
						'value'   => 'sold',
						'compare' => '!=',
					);

			}

			if ( isset( $params['vehicle_location'] ) && ! empty( $params['vehicle_location'] ) ) {
				$args['meta_query'][] = array(
					'key'     => 'vehicle_location',
					'value'   => $params['vehicle_location'],
					'compare' => 'LIKE',
				);
			}

			/* Set meta query*/
			if ( ! empty( $args['meta_query'] ) ) {
				$wp_query->set( 'meta_query', $args['meta_query'] );
			}

			/* Check Year range option enable from backend */
			$is_year_range_active = cardealer_is_year_range_active();
			if ( $is_year_range_active ) {
				$year_range    = cardealer_get_year_range();
				$yearmin       = isset( $year_range['min_year'] ) ? $year_range['min_year'] : '';
				$yearmax       = isset( $year_range['max_year'] ) ? $year_range['max_year'] : '';
				$pgs_min_year  = isset( $params['min_year'] ) ? esc_attr( $params['min_year'] ) : 0;
				$pgs_max_year  = isset( $params['max_year'] ) ? esc_attr( $params['max_year'] ) : 0;
				if ( ! empty( $year_range ) && ( $pgs_min_year > 0 || $pgs_max_year > 0 ) ) {
					if ( $yearmin !== $pgs_min_year || $yearmax !== $pgs_max_year ) {
						$terms   = get_terms(
							array(
								'taxonomy'   => 'car_year',
								'hide_empty' => true,
							)
						);
						$quryear = array();
						if ( ! empty( $terms ) ) {
							foreach ( $terms as $tdata ) {
								if ( ( $tdata->slug >= $pgs_min_year ) && ( $tdata->slug <= $pgs_max_year ) ) {
									$quryear[] = $tdata->slug;
								}
							}
						}

						$tax_query[] = array(
							'taxonomy' => 'car_year',
							'field'    => 'slug',
							'terms'    => $quryear,
						);
					}
				}
			}

			$is_mileage_individual = ( isset( $params['mileage_type'] ) && 'individual' === $params['mileage_type'] ) ? $params['mileage_type'] : '';
			if ( isset( $params['car_mileage'] ) && ! empty( $params['car_mileage'] ) ) {
				if ( 'individual' === $is_mileage_individual ) {
					$tax_query[] = array(
						'taxonomy' => 'car_mileage',
						'field'    => 'slug',
						'terms'    => $params['car_mileage'],
					);
				} else {
					$mileage_terms   = array();
					$get_car_mileage = $params['car_mileage'];
					$terms           = get_terms(
						array(
							'taxonomy'   => 'car_mileage',
							'hide_empty' => true,
						)
					);
					foreach ( $terms as $tdata ) {
						$mileage = $tdata->slug;
						if ( is_numeric( $mileage ) && is_numeric( $get_car_mileage ) ) {
							if ( $mileage < $get_car_mileage ) {
								$mileage_terms[] = $tdata->slug;
							}
						}
					}
					if ( ! empty( $mileage_terms ) ) {

						unset( $wp_query->query_vars['car_mileage'] );

						$tax_query[] = array(
							'taxonomy' => 'car_mileage',
							'field'    => 'slug',
							'terms'    => $mileage_terms,
						);
					}
				}
			}

			$wp_query->set( 'post_type', array( 'cars' ) );
			$wp_query->set( 'tax_query', $tax_query );

			$pob = cardealer_get_default_sort_by();// get default option value.
			if ( isset( $params['cars_orderby'] ) && ! empty( $params['cars_orderby'] ) ) {
				$pob = $params['cars_orderby'];
			}

			$order = cardealer_get_default_sort_by_order();// get default option value.
			if ( isset( $params['cars_order'] ) && ! empty( $params['cars_order'] ) && in_array( $params['cars_order'], array( 'desc', 'asc' ), true ) ) {
				$order = $params['cars_order'];
			}
			switch ( $pob ) {
				case 'name':
					$orderby = 'title';
					break;
				case 'sale_price':
					$orderby = 'meta_value_num';
					$wp_query->set( 'meta_key', 'sale_price' );
					$wp_query->set( 'type', 'NUMERIC' );
					break;
				case 'year':
					$orderby = 'year';
					break;
				case 'date':
					$orderby = 'date (post_date)';
					break;
				default:
					$orderby = 'date (post_date)';
					break;
			}
			$wp_query->set( 'orderby', $orderby );
			$wp_query->set( 'order', $order );

			/* set number of car on car listing page */
			if ( isset( $params['cars_pp'] ) && ! empty( $params['cars_pp'] ) ) {
				$per_page = $params['cars_pp'];
			} elseif ( isset( $car_dealer_options['cars-per-page'] ) && ! empty( $car_dealer_options['cars-per-page'] ) ) {
				$per_page = $car_dealer_options['cars-per-page'];
			} else {
				$per_page = 12;
			}

			$wp_query->set( 'posts_per_page', $per_page );

		}
	}
}
add_action( 'pre_get_posts', 'cardealer_cars_get_catalog_ordering_args' );

if ( ! function_exists( 'orderby_car_year_qur' ) ) {
	/**
	 * Year qur
	 *
	 * @param string $orderby set orderby.
	 * @param array  $wp_query get the value.
	 */
	function orderby_car_year_qur( $orderby, $wp_query ) {
		global $wpdb;
		if ( isset( $wp_query->query_vars['orderby'] ) && 'year' === $wp_query->query_vars['orderby'] ) {
			$orderby  = "(
				SELECT GROUP_CONCAT(name ORDER BY name ASC)
				FROM $wpdb->term_relationships
				INNER JOIN $wpdb->term_taxonomy USING (term_taxonomy_id)
				INNER JOIN $wpdb->terms USING (term_id)
				WHERE $wpdb->posts.ID = object_id
				AND taxonomy = 'car_year'
				GROUP BY object_id
			) ";
			$orderby .= ( 'ASC' === strtoupper( $wp_query->get( 'order' ) ) ) ? 'ASC' : 'DESC';
		}
		return $orderby;
	}
	/**
	 * Filter for add custom subquery for year wise sorting order in car listing page
	 */
}
add_filter( 'posts_orderby', 'orderby_car_year_qur', 10, 2 );

if ( ! function_exists( 'cardealer_set_tex_query_array' ) ) {
	/**
	 * Tax query
	 *
	 * @param array $taxonomys .
	 * @param array $post .
	 */
	function cardealer_set_tex_query_array( $taxonomys, $post ) {
		
		$mileage_terms         = array();
		$arg                   = array();
		$year_rang_slider      = cardealer_is_year_range_active();
		$is_mileage_individual = ( isset( $_GET['mileage_type'] ) && 'individual' === $_GET['mileage_type'] ) ? $_GET['mileage_type'] : '';
		
		if ( isset( $_GET ) && function_exists( 'cdhl_get_cars_taxonomy' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$cars_taxonomy = cdhl_get_cars_taxonomy();

			$cfb = array();
			foreach ( $_GET as $key => $val ) { // phpcs:ignore WordPress.Security.NonceVerification
				if ( in_array( $key, $cars_taxonomy ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$cfb[] = $key;
				}
			}
			if ( ! empty( $cfb ) ) {
				$taxonomys = array_unique( array_merge( $taxonomys, $cfb ) );
			}
		}
		foreach ( $taxonomys as $tax ) {
			if ( isset( $post[ $tax ] ) && '' !== $post[ $tax ] ) {
				foreach ( $post as $key => $val ) {
					if ( $key === $tax ) {
						if ( 'car_mileage' === $key && 'individual' !== $is_mileage_individual ) {
							$terms = get_terms(
								array(
									'taxonomy'   => 'car_mileage',
									'hide_empty' => true,
								)
							);
							foreach ( $terms as $tdata ) {
								$mileage      = $tdata->slug;
								$post_mileage = $post[ $tax ];
								if ( is_numeric( $mileage ) && is_numeric( $post_mileage ) ) {
									if ( $mileage < $post[ $tax ] ) {
										$mileage_terms[] = $tdata->slug;
									}
								}
							}

							$arg[] = array(
								'taxonomy' => $tax,
								'field'    => 'slug',
								'terms'    => $mileage_terms,
							);
						} else {

							if ( 'car_year' !== $key || ( 'car_year' === $key && ! $year_rang_slider ) ) {
								$arg[] = array(
									'taxonomy' => $tax,
									'field'    => 'slug',
									'terms'    => array( $post[ $tax ] ),
								);
							}
						}
					}
				}
			}
		}

		if ( is_tax( 'vehicle_cat' ) || ( isset( $post['is_vehicle_cat'] ) && 'yes' === $post['is_vehicle_cat'] ) ) {
			global $wp_query;
			if ( isset( $wp_query->query_vars['vehicle_cat'] ) && ! empty( $wp_query->query_vars['vehicle_cat'] ) ) {
				$vehicle_cat = $wp_query->query_vars['vehicle_cat'];
			} elseif ( isset( $post['vehicle_cat'] ) && ! empty( $post['vehicle_cat'] ) ) {
				$vehicle_cat = $post['vehicle_cat'];
			}
			$arg[] = array(
				'taxonomy' => 'vehicle_cat',
				'field'    => 'slug',
				'terms'    => array( $vehicle_cat ),
			);
		}

		if ( $year_rang_slider ) {

			$year_range    = cardealer_get_year_range();
			$yearmin       = isset( $year_range['min_year'] ) ? $year_range['min_year'] : '';
			$yearmax       = isset( $year_range['max_year'] ) ? $year_range['max_year'] : '';
			$pgs_min_year  = isset( $post['min_year'] ) ? esc_attr( $post['min_year'] ) : 0;
			$pgs_max_year  = isset( $post['max_year'] ) ? esc_attr( $post['max_year'] ) : 0;
			$year_rang_qur = array();
			if ( $pgs_min_year > 0 || $pgs_max_year > 0 ) {

				if ( $yearmin !== $pgs_min_year || $yearmax !== $pgs_max_year ) {

					$terms         = get_terms(
						array(
							'taxonomy'   => 'car_year',
							'hide_empty' => true,
						)
					);
					$quryear       = array();
					$taxonomy_name = get_taxonomy( 'car_year' );
					$slug          = $taxonomy_name->rewrite['slug'];
					$label         = $taxonomy_name->labels->menu_name;
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $tdata ) {
							if ( ( $tdata->slug >= $pgs_min_year ) && ( $tdata->slug <= $pgs_max_year ) ) {
								$quryear[] = $tdata->slug;
							}
						}
					}
					$arg[] = array(
						'taxonomy' => 'car_year',
						'field'    => 'slug',
						'terms'    => $quryear,
						'operator' => 'IN',
					);

				}
			}
		}
		/**
		 * Filters vehicle taxonomy query.
		 *
		 * @since 1.0
		 * @param array      $arg   Vehicle taxonomy query arguments.
		 * @visible          true
		 */
		return apply_filters( 'cardealer_set_tax_query', $arg );
	}
}

add_action( 'before_vehicle_inventory_page_content', 'cardealer_inventory_page_hidden_fields', 10 );
function cardealer_inventory_page_hidden_fields( $layout ) {

	$vehicle_location = ( isset($_GET['vehicle_location']) && ! empty($_GET['vehicle_location']) ) ? $_GET['vehicle_location'] : '';
	if ( ! empty($vehicle_location) ) {
		ob_start();
		?>
		<input type="hidden" name="vehicle_location" class="vehicle-location-input" value="<?php echo esc_html($vehicle_location); ?>" />
		<?php
		echo ob_get_clean();
	}

	return $layout;
}

if ( ! function_exists( 'cardealer_get_all_filters' ) ) {
	/**
	 * Get all filter select box
	 */
	function cardealer_get_all_filters( $active_filters = false ) {
		$taxonomys     = cardealer_get_filters_taxonomy();
		$get_arg       = array();
		$get_url_terms = array();

		if ( is_tax() ) {
			if ( 'car_mileage' !== get_query_var( 'taxonomy' ) ) {
				$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				if ( ! empty( $term ) ) {
					$_GET[ $term->taxonomy ] = $term->slug;
				}
			}
		}

		// @codingStandardsIgnoreStart
		foreach ( $taxonomys as $tax ) {
			/** Check from url if there any filter*/
			if ( isset( $_GET[ $tax ] ) && '' !== $_GET[ $tax ] ) {
				if ( isset( $_GET['car_mileage'] ) && ! empty( $_GET['car_mileage'] ) ) {
					$get_arg[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $_GET[ $tax ] ),
						'compare'  => '<',
						'type'     => 'NUMERIC',
					);
				} else {
					$get_arg[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $_GET[ $tax ] ),
					);
				}
			}
		}
		// @codingStandardsIgnoreEnd

		/** Check year_range filter is active then add in query*/
		$year_range    = cardealer_get_year_range();
		$yearmin       = isset( $year_range['min_year'] ) ? $year_range['min_year'] : 0;
		$yearmax       = isset( $year_range['max_year'] ) ? $year_range['max_year'] : 0;
		$pgs_min_year  = isset( $_GET['min_year'] ) ? sanitize_text_field( wp_unslash( $_GET['min_year'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
		$pgs_max_year  = isset( $_GET['max_year'] ) ? sanitize_text_field( wp_unslash( $_GET['max_year'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
		$year_rang_qur = array();
		if ( $pgs_min_year > 0 || $pgs_max_year > 0 ) {

			if ( $yearmin !== $pgs_min_year || $yearmax !== $pgs_max_year ) {

				$terms         = get_terms(
					array(
						'taxonomy'   => 'car_year',
						'hide_empty' => true,
					)
				);
				$quryear       = array();
				$taxonomy_name = get_taxonomy( 'car_year' );
				$slug          = $taxonomy_name->rewrite['slug'];
				$label         = $taxonomy_name->labels->menu_name;
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $tdata ) {
						if ( ( $tdata->slug >= $pgs_min_year ) && ( $tdata->slug <= $pgs_max_year ) ) {
							$quryear[] = $tdata->slug;
						}
					}
				}
				$get_arg['tax_query'][] = array(
					'taxonomy' => 'car_year',
					'field'    => 'slug',
					'terms'    => $quryear,
					'operator' => 'IN',
				);

			}
		}

		/**
		 * Filters the search arguments used in filtering vehicle inventory on inventory page.
		 *
		 * @since 1.0
		 *
		 * @param array      $get_arg   Array arguments for vehicle filter query.
		 * @visible          true
		 */
		$get_arg = apply_filters( 'cardealer_get_all_filters', $get_arg );

		/**
		 * Pass query var
		 *
		 * @param array $get_arg pass query var if any in url else it blank
		 */

		// https://potezasupport.ticksy.com/ticket/3194813/
		// https://gitlab.com/dinesh4monto/cardealer/-/issues/754
		echo cardealer_new_get_all_filters( $get_arg, $active_filters );

		/*
		echo wp_kses(
			$attributs,
			array(
				'div'    => array(
					'id'       => true,
					'class'    => true,
					'tabindex' => true,
					'data-*'   => true,
				),
				'span'   => array(
					'id'    => true,
					'class' => true,
				),
				'strong' => array(
					'id'    => true,
					'class' => true,
				),
				'ul'     => array(
					'id'                => true,
					'class'             => true,
					'data-all-listings' => true,
				),
				'li'     => array(
					'id'        => true,
					'class'     => true,
					'style'     => true,
					'data-type' => true,
				),
				'select' => array(
					'id'       => true,
					'class'    => true,
					'data-tax' => true,
					'data-id'  => true,
					'name'     => true,
					'style'    => true,
				),
				'option' => array(
					'id'       => true,
					'class'    => true,
					'value'    => true,
					'selected' => true,
				),
				'a'      => array(
					'id'    => true,
					'class' => true,
					'href'  => true,
				),
				'i'      => array(
					'class' => true,
				),
				'input'  => array(
					'class'        => true,
					'type'         => true,
					'id'           => true,
					'name'         => true,
					'value'        => true,
					'data-yearmin' => true,
					'data-yearmax' => true,
					'readonly'     => true,
					'data-cfb'     => true,
					'data-min'     => true,
					'data-max'     => true,
					'data-step'    => true,
				),
				'label'  => array(
					'class' => true,
					'for'   => true,
				),
			)
		);
		*/
	}
}

if ( ! function_exists( 'cardealer_new_get_all_filters' ) ) {
	/**
	 * Get all filters
	 *
	 * @param array $get_arg .
	 */
	function cardealer_new_get_all_filters( $get_arg, $active_filters = false ) {
		global $car_dealer_options;

		$vehicle_mileage_unit = ( isset( $car_dealer_options['vehicle_mileage_unit'] ) && 'none' !== $car_dealer_options['vehicle_mileage_unit'] ) ? $car_dealer_options['vehicle_mileage_unit'] : '';

		$is_vehicle_cat = false;
		if ( is_tax( 'vehicle_cat' ) ) {
			$is_vehicle_cat = false;
			global $wp_query;
			$get_arg[] = array(
				'taxonomy' => 'vehicle_cat',
				'field'    => 'slug',
				'terms'    => array( $wp_query->query_vars['vehicle_cat'] ),

			);
		}

		$taxonomys     = cardealer_get_filters_taxonomy();
		$args          = cardealer_make_filter_wp_query( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification
		$result_filter = array();

		$args_new                  = $args;
		$args_new['fields']        = 'ids';
		$args_new['no_found_rows'] = true;
		$filter_query_args         = array_replace( $args_new, array( 'posts_per_page' => -1 ) );

		$filter_query = new WP_Query( $filter_query_args );
		$tot_result   = $filter_query->post_count;
		if ( $filter_query->have_posts() ) {
			if ( isset( $get_arg ) && ! empty( $get_arg ) && $tot_result > 0 ) {
				foreach ( $taxonomys as $tax ) {
					$tax_args = array(
						'orderby' => 'name',
						'order'   => 'ASC',
						'fields'  => 'all',
					);
					$terms    = wp_get_object_terms( $filter_query->posts, $tax, $tax_args );
					foreach ( $terms as $tdata ) {
						if ( $tdata->taxonomy === $tax ) {
							$result_filter[ $tax ][] = array(
								'term_id'  => $tdata->term_id,
								'slug'     => $tdata->slug,
								'name'     => $tdata->name,
								'taxonomy' => $tdata->taxonomy,
							);
						}
					}
				}
			}
			if ( $is_vehicle_cat ) {
				$args  = array(
					'orderby' => 'name',
					'order'   => 'ASC',
					'fields'  => 'all',
				);
				$terms = wp_get_object_terms( $filter_query->posts, 'vehicle_cat', $tax_args );
				foreach ( $terms as $tdata ) {
					if ( 'vehicle_cat' === $tdata->taxonomy ) {
						$result_filter[ $tax ][] = array(
							'term_id'  => $tdata->term_id,
							'slug'     => $tdata->slug,
							'name'     => $tdata->name,
							'taxonomy' => $tdata->taxonomy,
						);
					}
				}
			}
			wp_reset_postdata();
		}

		$is_year_range_active = cardealer_is_year_range_active();

		$attributs      = '<div class="cars-total-vehicles">';
		$attributs .= '<span class="stripe"><strong><span class="number_of_listings">' . esc_html( $tot_result ) . '</span> ';
		$attributs .= '<span class="listings_grammar">' . esc_html__( 'Vehicles Matching', 'cardealer' ) . '</span></strong></span>';
		$attributs .= '<ul class="stripe-item filter margin-bottom-none" data-all-listings="All Listings">';

		foreach ( $_GET as $gkey => $gval ) { // phpcs:ignore WordPress.Security.NonceVerification

			if ( in_array( $gkey, $taxonomys ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$taxonomy_name   = get_taxonomy( $gkey );
				$label           = $taxonomy_name->labels->singular_name;
				$mileage_compare = '≤';
				if ( 'car_mileage' === $gkey ) {
					if ( isset( $_GET['mileage_type'] ) && 'individual' === $_GET['mileage_type'] ) {
						$mileage_compare = '';
					}

					if ( $vehicle_mileage_unit ) {
						$attributs .= '<li class="stripe-single-item stripe-item-' . esc_attr( $gkey ) . '" data-type="' . esc_attr( $gkey ) . '" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> ' . esc_html( $label ) . ' :  <span data-key="' . esc_attr( sanitize_text_field( wp_unslash( $_GET[ $gkey ] ) ) ) . '">' . $mileage_compare . ' ' . esc_html( sanitize_text_field( wp_unslash( $_GET[ $gkey ] ) ) ) . ' ' . $vehicle_mileage_unit .'</span></a></li>'; // phpcs:ignore
					} else {
						$attributs .= '<li class="stripe-single-item stripe-item-' . esc_attr( $gkey ) . '" data-type="' . esc_attr( $gkey ) . '" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> ' . esc_html( $label ) . ' :  <span data-key="' . esc_attr( sanitize_text_field( wp_unslash( $_GET[ $gkey ] ) ) ) . '">' . $mileage_compare . ' ' . esc_html( sanitize_text_field( wp_unslash( $_GET[ $gkey ] ) ) ) . '</span></a></li>'; // phpcs:ignore
					}

				} else {
					$term       = get_term_by( 'slug', $gval, $gkey );
					$term_name  = isset( $term->name ) ? $term->name : '';
					$attributs .= '<li class="stripe-single-item stripe-item-' . esc_attr( $gkey ) . '" data-type="' . esc_attr( $gkey ) . '" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> ' . esc_html( $label ) . ' :  <span data-key="' . esc_attr( $gval ) . '">' . esc_html( $term_name ) . '</span></a></li>';
				}
			}

			if ( 'vehicle_location' === $gkey ) {
				$attributs .= '<li class="stripe-single-item stripe-item-' . esc_attr( $gkey ) . '" data-type="' . esc_attr( $gkey ) . '" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> ' . esc_html__( 'Location', 'cardealer' ) . ' :  <span data-key="' . esc_attr( sanitize_text_field( wp_unslash( $_GET[ $gkey ] ) ) ) . '">' . esc_html( sanitize_text_field( wp_unslash( $_GET[ $gkey ] ) ) ) . '</span></a></li>'; // phpcs:ignore
			}
		}
			$attributs .= '</ul>';
		$attributs     .= '</div>';

		if ( ! $active_filters ) {
			$attributs     .= '<div class="listing_sort">';
			$attributs .= '<div class="sort-filters">';
			$t          = 1;

			$year_range_slider_location = cardealer_get_year_range_slider_location();

			if ( $is_year_range_active ) {
				if ( 'in_filters' === $year_range_slider_location ) {
					$year_range_filters = cardealer_get_year_range_filters( '', array( 'location' => 'filters' ) );
					$attributs         .= $year_range_filters;
				}

				$key = array_search( 'car_year', $taxonomys, true );
				if ( $key ) {
					unset( $taxonomys[ $key ] );
				}
			}

			/** Here we create selectbox as per query or default*/
			foreach ( $taxonomys as $tax ) {

				$taxonomy_name = get_taxonomy( $tax );
				$label         = $taxonomy_name->labels->singular_name;
				$attributs    .= '<select data-tax="' . esc_attr( $label ) . '" data-id="' . esc_attr( $tax ) . '" name="' . esc_attr( $tax ) . '" class="select-sort-filters cd-select-box sort_' . esc_attr( $tax ) . '">';
				$attributs    .= '<option value="">' . esc_html( $label ) . '</option>';
				/** Cehck is there any argumet for filter term */
				if ( isset( $get_arg ) && ! empty( $get_arg ) ) {
					$newarr = array();

					if ( ! empty( $result_filter[ $tax ] ) ) {
						foreach ( $result_filter[ $tax ] as $term_data ) {
								$selected = '';
							if ( 'car_mileage' !== $tax ) {
								if ( isset( $_GET[ $tax ] ) && sanitize_text_field( wp_unslash( $_GET[ $tax ] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
									if ( $_GET[ $tax ] === $term_data['slug'] ) { // phpcs:ignore WordPress.Security.NonceVerification
										$selected = "selected='selected'";
									}
								}

								if ( ! in_array( $term_data['slug'], $newarr ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									$attributs .= '<option value="' . $term_data['slug'] . '" ' . $selected . '>' . $term_data['name'] . '</option>';
									$newarr[]   = $term_data['slug'];
								}
							} else {

								$mileage_array = cardealer_get_mileage_array();
								if ( 'car_mileage' === $tax && 1 === $t ) {
									foreach ( $mileage_array as $mileage ) {
										$selected = '';
										if ( isset( $_GET['car_mileage'] ) && (int) $_GET['car_mileage'] === (int) $mileage ) { // phpcs:ignore WordPress.Security.NonceVerification
											$selected = "selected=''";
										}

										if ( $vehicle_mileage_unit ) {
											$attributs .= '<option value="' . esc_attr( $mileage ) . '" ' . esc_attr( $selected ) . '>&leq; ' . esc_html( $mileage ) . ' ' . $vehicle_mileage_unit . '</option>';
										} else {
											$attributs .= '<option value="' . esc_attr( $mileage ) . '" ' . esc_attr( $selected ) . '>&leq; ' . esc_html( $mileage ) . '</option>';
										}
									}
									$t++;
								}
							}
						}
					}
				} else {
					/** Here we set default terms list */
					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'hide_empty' => true,
						)
					);

					foreach ( $terms as $tdata ) {
						if ( 'car_mileage' !== $tax ) {
							$selected = '';
							if ( isset( $_GET[ $tax ] ) && '' !== $_GET[ $tax ] ) { // phpcs:ignore WordPress.Security.NonceVerification
								if ( $_GET[ $tax ] === $tdata->slug ) { // phpcs:ignore WordPress.Security.NonceVerification
									$selected = "selected=''";
								}
							}
							$attributs .= '<option value="' . esc_attr( $tdata->slug ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $tdata->name ) . '</option>';
						} else {

							$mileage_array = cardealer_get_mileage_array();
							if ( 'car_mileage' === $tax && 1 === $t ) {
								foreach ( $mileage_array as $mileage ) {
									$selected = '';
									if ( isset( $_GET['car_mileage'] ) && $_GET['car_mileage'] === $mileage ) { // phpcs:ignore WordPress.Security.NonceVerification
										$selected = "selected=''";
									}
									if ( $vehicle_mileage_unit ) {
										$attributs .= '<option value="' . esc_attr( $mileage ) . '" ' . esc_attr( $selected ) . '>&leq; ' . esc_html( $mileage ) . ' ' . $vehicle_mileage_unit . '</option>';
									} else {
										$attributs .= '<option value="' . esc_attr( $mileage ) . '" ' . esc_attr( $selected ) . '>&leq; ' . esc_html( $mileage ) . '</option>';
									}
								}
								$t++;
							}
						}
					}
				}
				$attributs .= '</select>';
			}
			$attributs .= '<div class=""><a class="button" href="" id="reset_filters">' . esc_html__( 'Reset', 'cardealer' ) . '</a></div>';
			$attributs .= '</div>';
			$attributs .= '<span class="filter-loader"></span></div>';
		}

		return $attributs;
	}
}

if ( ! function_exists( 'cardealer_get_filters_taxonomy' ) ) {
	/**
	 * Filters taxonomy
	 */
	function cardealer_get_filters_taxonomy() {
		global $car_dealer_options;

		$taxonomies     = array( 'car_year', 'car_make', 'car_model', 'car_body_style', 'car_condition', 'car_mileage', 'car_transmission', 'car_drivetrain', 'car_engine', 'car_fuel_economy', 'car_exterior_color' );
		$taxonomies_raw = get_object_taxonomies( 'cars' );

		foreach ( $taxonomies_raw as $new_tax ) {
			if ( in_array( $new_tax, $taxonomies, true ) ) {
				continue;
			}

			$new_tax_obj = get_taxonomy( $new_tax );
			if ( isset( $new_tax_obj->include_in_filters ) && true === (bool) $new_tax_obj->include_in_filters ) {
				$taxonomies[] = $new_tax;
			}
		}

		/**
		* Filters the elements of vehicle taxonomy used on vehicle inventory page filters.
		*
		* @since 1.0
		* @return array     Array of vehicle taxonomy slugs.
		* $visible          true
		*/
		$taxonomys = apply_filters( 'cardealer_filters_taxonomy_array', $taxonomies );
		if ( isset( $car_dealer_options['cars_listing_filters']['Added Filters'] ) ) {
			unset( $car_dealer_options['cars_listing_filters']['Added Filters']['placebo'] );
			$car_attributes = $car_dealer_options['cars_listing_filters']['Added Filters'];
			if ( ! empty( $car_attributes ) ) {
				$taxonomys = array();
				foreach ( $car_attributes as $key => $car_att ) {
					$taxonomys[] = $key;
				}
			}
		}

		// Check year range slider enabled?
		$is_year_range_active       = cardealer_is_year_range_active();
		if ( $is_year_range_active ) {
			if ( ! array_search( 'car_year', $taxonomys ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$key = array_search( 'car_year', $taxonomys ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				unset( $taxonomys[ $key ] );
			}
		}

		/**
		 * Filters the array of vehicle filter attributes displayed on vehicle inventory page.
		 *
		 * @since 1.0
		 *
		 * @param array        $taxonomys   Array of vehicle filter attributes.
		 * @visible            true
		 */
		return apply_filters( 'cardealer_get_filters_taxonomy', $taxonomys );
	}
}

if ( ! function_exists( 'cardealer_get_mileage_array' ) ) {
	/**
	 * Get array
	 */
	function cardealer_get_mileage_array() {
		global $car_dealer_options;

		$min_mileage     = ( isset( $car_dealer_options['min_mileage'] ) && $car_dealer_options['min_mileage'] ) ? (int) $car_dealer_options['min_mileage'] : 10000;
		$add_per_mileage = ( isset( $car_dealer_options['add_per_mileage'] ) && $car_dealer_options['add_per_mileage'] ) ? (int) $car_dealer_options['add_per_mileage'] : 10000;
		$mileage_step    = ( isset( $car_dealer_options['mileage_step'] ) && $car_dealer_options['mileage_step'] ) ? (int) $car_dealer_options['mileage_step'] : 10;

		if ( $min_mileage && $add_per_mileage && $mileage_step ) {
			$mileage_array = array( $min_mileage );

			$new_mileage = $min_mileage;
			for ( $i = 1; $i < $mileage_step; $i++ ) {
				$new_mileage     = $new_mileage + $add_per_mileage;
				$mileage_array[] = $new_mileage;
			}

			$mileage_max = cardealer_get_mileage_max();
			if ( $new_mileage < $mileage_max ) {
				$mileage_array[] = cardealer_roundup_to_nearest_multiple( $mileage_max, apply_filters( 'cardealer_roundup_to_nearest_multiple_increment', 1000 ) );
			}
		} else {
			$mileage_array = array( '10000', '20000', '30000', '40000', '50000', '60000', '70000', '80000', '90000', '100000' );
		}
		/**
		 * Filters the vehicle mileage array - mostly used in vehicle filters on "vehicle inventory page" and "potenza custom filters" shortcode.
		 *
		 * @since 1.0
		 * @param array         $mileage_array  Mileage array elements.
		 * @visible             true
		 */
		return apply_filters( 'cardealer_get_mileage_array', $mileage_array );
	}
}

if ( ! function_exists( 'cardealer_make_filter_wp_query' ) ) {
	/**
	 * Make filter wp query
	 *
	 * @param array $request_method .
	 */
	function cardealer_make_filter_wp_query( $request_method ) {

		$tax_query_arry = array();
		$taxonomys      = cardealer_get_filters_taxonomy();
		if ( isset( $request_method['selected_attr'] ) && ! empty( $request_method['selected_attr'] ) ) {
			$taxonomys = explode( ',', $request_method['selected_attr'] );
		}

		$tax_query_arry  = cardealer_set_tex_query_array( $taxonomys, $request_method );
		$data_html       = '';
		$pagination_html = '';
		$cars_orderby    = 'date (post_date)';
		$data_order      = 'asc';

		global $car_dealer_options;

		$params = '';

		// @codingStandardsIgnoreStart
		if( isset( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( $_SERVER['QUERY_STRING'], $params ); // the context is safe and reliable.
		}
		// @codingStandardsIgnoreEnd

		$per_page   = 12;
		$cars_order = 'date (post_date)';
		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$per_page = $car_dealer_options['cars-per-page'];
		}
		if ( isset( $request_method['cars_pp'] ) && ! empty( $request_method['cars_pp'] ) ) {
			$per_page = $request_method['cars_pp'];
		}

		if ( isset( $request_method['cars_order'] ) && ! empty( $request_method['cars_order'] ) ) {
			$data_order = $request_method['cars_order'];
		}
		$paged = isset( $request_method['paged'] ) ? (int) $request_method['paged'] : 1;
		$args  = array(
			'post_type'      => 'cars',
			'post_status'    => array( 'publish', 'acf-disabled' ),
			'posts_per_page' => $per_page,
			'order'          => $data_order,
			'paged'          => $paged,
		);

		if ( isset( $request_method['cars_orderby'] ) && ! empty( $request_method['cars_orderby'] ) ) {
			$cars_orderby = $request_method['cars_orderby'];
		}

		if ( 'sale_price' === $cars_orderby ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'sale_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		} elseif ( 'featured' === $cars_orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = 'featured'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		} else {
			$args['orderby'] = $cars_orderby;
		}

		if ( isset( $request_method['s'] ) && ! empty( $request_method['s'] ) ) {
			$args['s'] = $request_method['s'];
		}

		if ( ! empty( $tax_query_arry ) ) {

			$args['tax_query'] = array( 'relation' => 'AND' ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

			foreach ( $tax_query_arry as $k => $val ) {
				$args['tax_query'][ $k ] = $val;
			}
		}

		/* Set Price meta query  */
		$pgs_min_price = isset( $request_method['min_price'] ) ? esc_attr( $request_method['min_price'] ) : 0;
		$pgs_max_price = isset( $request_method['max_price'] ) ? esc_attr( $request_method['max_price'] ) : 0;
		if ( $pgs_min_price > 0 || $pgs_max_price > 0 ) {
			$prices = cardealer_get_car_filtered_price();
			$min    = floor( $prices->min_price );
			$max    = ceil( $prices->max_price );
			if ( $min !== $pgs_min_price || $max !== $pgs_max_price ) {
				$args['meta_query'][] = array(
					'key'     => 'sale_price',
					'value'   => array( $pgs_min_price, $pgs_max_price ),
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC',
				);
			}
		}

		/* Don't want to show sold car on car listing page */
		if ( isset( $car_dealer_options['car_no_sold'] ) && 0 === (int) $car_dealer_options['car_no_sold'] ) {
			$args['meta_query'][] = array(
				'key'     => 'car_status',
				'value'   => 'sold',
				'compare' => '!=',
			);

		}

		if ( isset( $request_method['vehicle_location'] ) && ! empty( $request_method['vehicle_location'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'vehicle_location',
				'value'   => $request_method['vehicle_location'],
				'compare' => 'LIKE',
			);
		}
		return $args;
	}
}

add_action( 'wp_ajax_cardealer_cars_filter_query', 'cardealer_cars_filter_query' );
add_action( 'wp_ajax_nopriv_cardealer_cars_filter_query', 'cardealer_cars_filter_query' );
if ( ! function_exists( 'cardealer_cars_filter_query' ) ) {
	/**
	 * Filter query
	 */
	function cardealer_cars_filter_query() {

		$nonce = isset( $_REQUEST['query_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['query_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'cardealer_cars_filter_query_nonce' ) ) {
			$attributs = cardealer_get_all_filters_with_ajax();
			echo wp_json_encode( $attributs );
		}

		exit();
	}
}

add_action( 'wp_ajax_cardealer_load_more_vehicles', 'cardealer_load_more_vehicles' );
add_action( 'wp_ajax_nopriv_cardealer_load_more_vehicles', 'cardealer_load_more_vehicles' );
if ( ! function_exists( 'cardealer_load_more_vehicles' ) ) {
	/**
	 * Load more vehicles
	 */
	function cardealer_load_more_vehicles() {
		global $car_dealer_options;

		do_action( 'cardealer_before_load_more_vehicles', $car_dealer_options );

		// @codingStandardsIgnoreStart
		$data_html            = '';
		$status               = 0;
		$filter_vars          = json_decode( stripslashes( $_POST['filter_vars'] ), true );
		$paged                = ( isset( $_POST['paged'] ) && ! empty( $_POST['paged'] ) ) ? sanitize_text_field( wp_unslash( $_POST['paged'] ) ) : 2;
		$filter_vars['paged'] = $paged;

		$first_page_records = 12;
		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$first_page_records = $car_dealer_options['cars-per-page']; // records displayed on page load.
		}
		$records_processed = ( isset( $_POST['records_processed'] ) && ( 0 !== (int) $_POST['records_processed'] ) ) ? sanitize_text_field( wp_unslash( $_POST['records_processed'] ) ) : $first_page_records;
		// @codingStandardsIgnoreEnd

		/**
		 * Filters the value of number of records to load per ajax call for lazyload vehicle listing.
		 *
		 * @since 1.0
		 *
		 * @param number         $first_page_records    Number of records to show on ajax call.
		 * @visible              true
		 */
		$filter_vars['cars_pp'] = apply_filters( 'cd_ajax_inventory_load_per_call', $first_page_records );

		$args    = cardealer_make_filter_wp_query( $filter_vars );
		$query   = new WP_Query( $args );
		$imgurls = array();

		// Check for nonce security.
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'load_more_vehicles_nonce' ) ) {
			$status    = 2;
			$data_html = '<div class="col-sm-12 load-status"><div class="alert alert-warning">' . esc_html__( 'Something is wrong; please try again..!', 'cardealer' ) . '</div></div>';
		} else {
			if ( $query->have_posts() ) {
				$status = 1;
				while ( $query->have_posts() ) :
					$query->the_post();
					ob_start();
					$imgurls[] = cardealer_get_cars_image_src( 'car_catalog_image', get_the_ID() );
					get_template_part( 'template-parts/cars/content', 'cars' );
					$datahtml   = ob_get_clean();
					$data_html .= $datahtml;
				endwhile;
				$records_processed += $query->post_count;
				wp_reset_postdata();
			} else {
				$status    = 2;
				$data_html = '<div class="col-sm-12 load-status"><div class="alert alert-warning">' . esc_html__( 'All vehicles loaded..!', 'cardealer' ) . '</div></div>';
			}
		}

		echo wp_json_encode(
			array(
				'status'            => $status,
				'imgURLs'           => $imgurls,
				'data_html'         => $data_html,
				'paged'             => $paged + 1,
				'records_processed' => $records_processed,
			)
		);
		wp_die();
	}
}

if ( ! function_exists( 'cardealer_get_all_filters_with_ajax' ) ) {
	/**
	 * Filter with ajax
	 */
	function cardealer_get_all_filters_with_ajax() {
		global $car_dealer_options;

		do_action( 'cardealer_before_get_all_filters_with_ajax', $car_dealer_options );

		$taxonomys = cardealer_get_filters_taxonomy();

		// @codingStandardsIgnoreStart
		if ( isset( $_REQUEST['selected_attr'] ) && ! empty( $_REQUEST['selected_attr'] ) ) {
			$taxonomys = explode( ',', $_REQUEST['selected_attr'] );
		}
		// @codingStandardsIgnoreEnd

		$result_filter   = array();
		$pagination_html = '';
		$data_html       = '';
		$featured_cars   = '';
		$data_order      = 'asc';
		$args            = cardealer_make_filter_wp_query( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification

		if ( isset( $_POST['cars_order'] ) && ! empty( $_POST['cars_order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$data_order = sanitize_text_field( wp_unslash( $_POST['cars_order'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}
		$paged = isset( $_POST['paged'] ) ? (int) $_POST['paged'] : 1; // phpcs:ignore WordPress.Security.NonceVerification

		$query       = new WP_Query( $args );
		$tot_result  = $query->post_count;
		$found_posts = $query->found_posts;
		/**
		 * Get data html
		 * */
		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/cars/content', 'cars' );
			endwhile;
			$datahtml   = ob_get_clean();
			$data_html .= $datahtml;
			wp_reset_postdata();

			$pagination_html = cardealer_cars_pagination( false, $query, $paged );
		} else {
			$data_html = '<div class="col-sm-12"><div class="alert alert-warning">' . esc_html__( 'No result were found matching your selection.', 'cardealer' ) . '</div></<div>';
		}

		// Featured vehicles start.
		$featured_vehicles_count    = cardealer_get_featured_vehicles_count();
		$featured_vehicles_filtered = cardealer_show_featured_vehicles_filtered();
		if ( 'non_filtered' == $featured_vehicles_filtered ) {
			$featured_args = array(
				'post_type'      => 'cars',
				'posts_per_page' => $featured_vehicles_count,
				'paged'          => 1,
				'orderby'        => 'rand',
				'tax_query'      => array(),
				'meta_query'     => array(),
			);
		} else {
			$featured_args                   = $args;
			$featured_args['paged']          = 1;
			$featured_args['posts_per_page'] = $featured_vehicles_count;
			$featured_args['orderby']        = 'rand';
		}

		$featured_args['post_status'] = array(
			'publish'
		);

		$featured_args['meta_query']['relation'] = 'AND';
		$featured_args['meta_query'][]           = array(
			'key'     => 'featured',
			'value'   => '1',
			'compare' => '=',
		);
		// $featured_args['meta_query'][] = array(
			// 'key'     => 'cdfs_advertise_item_status',
			// 'value'   => $current_timestamp,
			// 'compare' => '>',
			// 'type'    => 'NUMERIC'
		// );

		$featured_args       = apply_filters( 'cardealer/featured_vehicles/query_args', $featured_args );
		$featured_query      = new WP_Query( $featured_args );
		$featured_cars_count = $featured_query->post_count;
		$featured_cars_found = $featured_query->found_posts;
		ob_start();
		global $cardealer_is_featured_vehicles_section;
		if ( $featured_query->have_posts() ) {
			$cardealer_is_featured_vehicles_section = true;
			while ( $featured_query->have_posts() )  {
				$featured_query->the_post();

				get_template_part( 'template-parts/cars/content', 'cars' );
			}
			wp_reset_postdata();
			$cardealer_is_featured_vehicles_section = false;
		}
		$featured_cars .= ob_get_clean();
		// Featured vehicles end.

		if ( ! isset( $_POST['cfb'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$attributs = '<div class="listing_sort">';
		}
		if ( isset( $_POST['cfb'] ) && 'yes' === $_POST['cfb'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$attributs = '';
		}

		$args['fields']    = 'ids';
		$filter_query_args = array_replace( $args, array( 'posts_per_page'=> -1 ) );
		$tax_query_arry    = cardealer_set_tex_query_array( $taxonomys, $_POST ); // phpcs:ignore WordPress.Security.NonceVerification
		$filter_query      = new WP_Query( $filter_query_args );
		$tot_result_filter = $filter_query->post_count;
		$filtered_makes    = array();

		if ( $filter_query->have_posts() ) {
			$filtered_makes = wp_get_object_terms(
				$filter_query->posts,
				'car_make',
				array(
					'orderby' => 'name',
					'order'   => 'ASC',
					'fields'  => 'slugs',
				)
			);

			foreach ( $taxonomys as $tax ) {
				if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {
					$tax_args = array(
						'orderby' => 'name',
						'order'   => 'ASC',
						'fields'  => 'all',
					);
					$terms    = wp_get_object_terms( $filter_query->posts, $tax, $tax_args );

					foreach ( $terms as $tdata ) {

						if ( $tdata->taxonomy === $tax ) {
							$result_filter[ $tax ][] = array(
								'post_id'  => get_the_ID(),
								'term_id'  => $tdata->term_id,
								'slug'     => $tdata->slug,
								'name'     => $tdata->name,
								'taxonomy' => $tdata->taxonomy,
							);
						}
					}
				}
			}

			wp_reset_postdata();
		}

		$cardealer_ganerate_filter_box = cardealer_ganerate_filter_box( $taxonomys, $tax_query_arry, $result_filter );

		$html = '';
		if ( 'asc' === $data_order ) :
			$html .= '<a id="pgs_cars_order" data-order="desc" data-current_order="asc" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a>';
		endif;
		if ( 'desc' === $data_order ) :
			$html .= '<a id="pgs_cars_order" data-order="asc" data-current_order="desc" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a>';
		endif;
		if ( ! isset( $_POST['cfb'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification

			$vehicle_make = cdhl_vehicle_make_logos_html( $filtered_makes );

			$attributs .= '<div class="submit-filters-btn"><a class="button" href="javascript:void(0);" id="submit_all_filters">' . esc_html__( 'Submit', 'cardealer' ) . '</a></div>';
			$attributs .= '<div class=""><a class="button" href="javascript:void(0);" id="reset_filters">' . esc_html__( 'Reset All Filters', 'cardealer' ) . '</a></div>';
			$attributs .= '<span class="filter-loader"></span></div>';
			$data       = array(
				'status'              => 'success',
				'all_filters'         => $cardealer_ganerate_filter_box,
				'data_html'           => $data_html,
				'featured_cars_count' => $featured_cars_count,
				'featured_cars_found' => $featured_cars_found,
				'featured_cars'       => $featured_cars,
				'pagination_html'     => $pagination_html,
				'order_html'          => $html,
				'tot_result'          => $tot_result,
				'tot_result_filter'   => $tot_result_filter,
				'found_posts'         => $found_posts,
				'vehicle_make'        => $vehicle_make,
			);
		} else {
			$data = array(
				'status'      => 'success',
				'all_filters' => $cardealer_ganerate_filter_box,
			);
		}
		return $data;
	}
}
if ( ! function_exists( 'cardealer_ganerate_filter_box' ) ) {
	/**
	 * Filter box
	 *
	 * @param string $taxonomys get the taxonomys.
	 * @param array  $tax_query_arry get tex query array.
	 * @param array  $result_filter .
	 */
	function cardealer_ganerate_filter_box( $taxonomys, $tax_query_arry = array(), $result_filter = array() ) {
		global $car_dealer_options;

		/**
		 * IF Request from custom search box Widgets
		 */
		$result_data = array();
		$vehicle_mileage_unit = ( isset( $car_dealer_options['vehicle_mileage_unit'] ) && 'none' !== $car_dealer_options['vehicle_mileage_unit'] ) ? $car_dealer_options['vehicle_mileage_unit'] : '';

		/**
		 * CFB used for custom filter box
		 * */
		if ( isset( $_POST['cfb'] ) && 'yes' === $_POST['cfb'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$attributs = '';
			foreach ( $taxonomys as $tax ) {
				$taxonomy_name = get_taxonomy( $tax );
				$label         = $taxonomy_name->labels->menu_name;
				// Check filter array set.
				if ( ! empty( $tax_query_arry ) ) {
					$newarr = array();
					if ( isset( $result_filter[ $tax ] ) ) {
						foreach ( $result_filter[ $tax ] as $term_data ) {
							if ( 'car_mileage' === $tax ) {

								$mileage_array = cardealer_get_mileage_array();
								if ( 'car_mileage' === $tax && isset( $t ) && 1 === $t ) {
									foreach ( $mileage_array as $mileage ) {
										if ( $vehicle_mileage_unit ) {
											$mileage_text = '&leq; ' . $mileage . ' ' . $vehicle_mileage_unit;
										} else {
											$mileage_text = '&leq; ' . $mileage;
										}

										$result_data[ $tax ][] = array(
											$mileage => $mileage_text,
										);
									}
									$t++;
								}
							} else {
								$selected = '';
								if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) { // phpcs:ignore WordPress.Security.NonceVerification
									if ( $_POST[ $tax ] === $term_data['slug'] ) { // phpcs:ignore WordPress.Security.NonceVerification
										$selected = "selected='selected'";

									}
								}
								if ( ! in_array( $term_data['slug'], $newarr ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									$newarr[]              = $term_data['slug'];
									$result_data[ $tax ][] = array(
										$term_data['slug'] => $term_data['name'],
									);
								}
							}
						}
					}
				} else {
					// When not set any filter.
					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'hide_empty' => true,
						)
					);
					foreach ( $terms as $tdata ) {
						$selected = '';
						if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) { // phpcs:ignore WordPress.Security.NonceVerification
							if ( $_POST[ $tax ] === $tdata->slug ) { // phpcs:ignore WordPress.Security.NonceVerification
								$selected = "selected=''";
							}
						}

						$result_data[ $tax ][] = array(
							$tdata->slug => $tdata->name,
						);
					}
				}
			}
		} else {

			/**
			 * Without CFB
			 * */
			$tot_count = count( $taxonomys );
			$i         = 0;
			$t         = 1;
			foreach ( $taxonomys as $tax ) {
				$flg = 0;
				if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {
					$newarr = array();
					if ( isset( $result_filter[ $tax ] ) ) {
						foreach ( $result_filter[ $tax ] as $term_data ) {
							if ( 'car_mileage' === $tax ) {

								$mileage_array = cardealer_get_mileage_array();
								if ( 'car_mileage' === $tax && 1 === $t ) {
									foreach ( $mileage_array as $mileage ) {
										if ( $vehicle_mileage_unit ) {
											$mileage_text = '&leq; ' . $mileage . ' ' . $vehicle_mileage_unit;
										} else {
											$mileage_text = '&leq; ' . $mileage;
										}

										$result_data[ $tax ][] = array(
											$mileage => $mileage_text,
										);
									}
									$t++;
								}
							} else {
								$selected = '';
								if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) { // phpcs:ignore WordPress.Security.NonceVerification
									if ( $_POST[ $tax ] === $term_data['slug'] ) { // phpcs:ignore WordPress.Security.NonceVerification
										$selected = "selected='selected'";

									}
								}
								if ( ! in_array( $term_data['slug'], $newarr ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
									$newarr[]              = $term_data['slug'];
									$result_data[ $tax ][] = array(
										$term_data['slug'] => $term_data['name'],
									);
								}
							}
						}
					}
				} else {
					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'hide_empty' => true,
						)
					);
					foreach ( $terms as $tdata ) {
						$selected = '';
						if ( isset( $_POST[ $tax ] ) && '' !== $_POST[ $tax ] ) { // phpcs:ignore WordPress.Security.NonceVerification
							if ( $_POST[ $tax ] === $tdata->slug ) { // phpcs:ignore WordPress.Security.NonceVerification
								$selected = "selected=''";
							}
						}

						$result_data[ $tax ][] = array(
							$tdata->slug => $tdata->name,
						);
					}
				}
			}
		}

		/* https://gitlab.com/dinesh4monto/cardealer/-/issues/817
		 *
		 if ( isset( $_POST['current_attr'] ) && isset( $result_data[ $_POST['current_attr'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			unset( $result_data[ $_POST['current_attr'] ] ); // phpcs:ignore WordPress.Security.NonceVerification
		}
		 */

		return $result_data;
	}
}
if ( ! function_exists( 'cardealer_cars_pagination' ) ) {
	/**
	 * Cars pagination
	 *
	 * @param bool $echo .
	 * @param bool $query .
	 * @param bool $paged .
	 */
	function cardealer_cars_pagination( $echo = true, $query = null, $paged = null ) {
		if ( null !== $query || ! empty( $query ) ) {
			$wp_query = $query;
			if ( null !== $paged ) {
				$paged = ( 0 === $paged ) ? 1 : $paged;
			} else {
				$paged = ( 0 === get_query_var( 'paged' ) ) ? 1 : get_query_var( 'paged' );
			}
		} else {
			global $wp_query;
			$paged = ( 0 === get_query_var( 'paged' ) ) ? 1 : get_query_var( 'paged' );
		}

		$big   = 999999999; // need an unlikely integer.
		$pages = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', wp_specialchars_decode( esc_url( get_pagenum_link( $big ) ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, $paged ),
				'total'     => $wp_query->max_num_pages,
				'type'      => 'array',
				'prev_next' => true,
				'prev_text' => esc_html__( '&larr; Prev', 'cardealer' ),
				'next_text' => esc_html__( 'Next &rarr;', 'cardealer' ),
			)
		);
		if ( is_array( $pages ) ) {
			$pagination = '<ul class="pagination">';
			foreach ( $pages as $page ) {
				$pagination .= "<li>$page</li>";
			}
			$pagination .= '</ul>';
			$pagination .= '<span class="pagination-loader"></span>';

			$pagination_escaped = wp_kses(
				$pagination,
				array(
					'ul'   => array(
						'class' => true,
					),
					'li'   => array(
						'class' => true,
					),
					'span' => array(
						'class'        => true,
						'aria-current' => true,
					),
					'a'    => array(
						'class' => true,
						'href'  => true,
					),
				)
			);

			if ( $echo ) {
				// This variable has been safely escaped in the following file: cardealer/includes/cars_functions.php Line: 3277.
				echo $pagination_escaped; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped.
			} else {
				// This variable has been safely escaped in the following file: cardealer/includes/cars_functions.php Line: 3277.
				return $pagination_escaped; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped.
			}
		}
	}
}

/**
* Menu search box auto complate search
*/
add_action( 'wp_ajax_pgs_auto_complate_search', 'cardealer_auto_complate_search' );
add_action( 'wp_ajax_nopriv_pgs_auto_complate_search', 'cardealer_auto_complate_search' );
if ( ! function_exists( 'cardealer_auto_complate_search' ) ) {
	/**
	 * Auto complate search
	 */
	function cardealer_auto_complate_search() {
		global $car_dealer_options;

		// Check for nonce security.
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'cardealer_auto_complate_search_nonce' ) ) {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'Unable to verify security nonce. Please try again later.', 'cardealer' ) . '</div>',
			);
			echo wp_json_encode( $data );
			exit();
		}

		$data  = array();
		if ( ! isset( $_POST['search'] ) || empty( $_POST['search'] ) ) {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'No search term entered.', 'cardealer' ) . '</div>',
			);
			echo wp_json_encode( $data );
			exit();
		}

		if ( ! isset( $_POST['seach_type'] ) || empty( $_POST['seach_type'] ) || ! in_array( $_POST['seach_type'], array( 'default', 'vehicles' ), true ) ) {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'Wrong search type. Please try again later.', 'cardealer' ) . '</div>',
			);
			echo wp_json_encode( $data );
			exit();
		}

		$search_term = sanitize_text_field( wp_unslash( $_POST['search'] ) );

		if ( 'default' === $_POST['seach_type'] ) {
			$posttype = cardealer_search_post_type();
			$args     = array(
				'post_type'      => $posttype,
				'post_status'    => 'publish',
				'posts_per_page' => defined( 'PHP_INT_MAX' ) ? PHP_INT_MAX : -1,
				's'              => $search_term,
			);
			$args     = apply_filters_deprecated( 'cardealer_auto_complate_search_args', array( $args, $_POST ), '4.7.0', 'cardealer_autocomplate_default_search_args' );
			$args     = apply_filters( 'cardealer_autocomplate_default_search_args', $args, $_POST );
		}elseif ( 'vehicles' === $_POST['seach_type'] ) {
			$_GET['s'] = $search_term;
			$args      = cardealer_make_filter_wp_query( $_GET );
			$args     = apply_filters( 'cardealer_autocomplate_vehicles_search_args', $args, $_GET, $_POST );
		}

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) :
				$query->the_post();

				$pid     = get_the_ID();
				$car_img = '';
				$class   = 'no-image';
				$ptype   = get_post_type( $pid );

				if ( 'cars' === $ptype ) {
					$image   = cardealer_get_cars_image( 'cardealer-50x50', $pid );
					$car_img = '<div class="search-result-image">' . $image . '</div>';
					$class   = '';
				} else {
					if ( has_post_thumbnail( $pid ) ) {
						$thmb    = get_the_post_thumbnail( $pid, 'cardealer-50x50' );
						$car_img = '<div class="search-result-image">' . $thmb . '</div>';
						$class   = '';
					}
				}
				$data[] = array(
					'status'   => true,
					'image'    => $car_img,
					'link_url' => get_the_permalink(),
					'title'    => '<div class="search-result-name ' . $class . '">' . get_the_title() . '</div>',
					'msg'      => '',
				);
			endwhile;
			wp_reset_postdata();
		} else {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
			);
		}
		echo wp_json_encode( $data );
		exit();
	}
}

/**
* List page search box auto complate search filters and sidebar area
*/
add_action( 'wp_ajax_pgs_cars_list_search_auto_compalte', 'cars_list_search_auto_compalte' );
add_action( 'wp_ajax_nopriv_pgs_cars_list_search_auto_compalte', 'cars_list_search_auto_compalte' );
if ( ! function_exists( 'cars_list_search_auto_compalte' ) ) {
	/**
	 * Search auto complate
	 */
	function cars_list_search_auto_compalte() {

		// Check for nonce security.
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'pgs_cars_list_search_auto_compalte_nonce' ) ) {
			$data[] = array(
				'status'   => false,
				'image'    => '',
				'link_url' => '',
				'title'    => '',
				'msg'      => '<div class="search-result-name">' . esc_html__( 'Something is wrong; please try again..!', 'cardealer' ) . '</div>',
			);

		} else {

			if ( isset( $_POST['search'] ) && ! empty( $_POST['search'] ) ) {
				$data      = array();
				$search    = trim( sanitize_text_field( wp_unslash( $_POST['search'] ) ) );
				$_GET['s'] = $search;
				$args      = cardealer_make_filter_wp_query( $_GET );

				$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) :
						$query->the_post();
						$pid     = get_the_ID();
						$car_img = '';
						$class   = 'no-image';
						$ptype   = get_post_type( $pid );
						if ( 'cars' === $ptype ) {
							$image   = cardealer_get_cars_image( 'cardealer-50x50', $pid );
							$car_img = '<div class="search-result-image">' . $image . '</div>';
							$class   = '';
						} else {
							if ( has_post_thumbnail( $pid ) ) {
								$thmb    = get_the_post_thumbnail( $pid, 'cardealer-50x50' );
								$car_img = '<div class="search-result-image">' . $thmb . '</div>';
								$class   = '';
							}
						}

						$data[] = array(
							'status'   => true,
							'image'    => $car_img,
							'link_url' => get_the_permalink(),
							'title'    => '<div class="search-result-name ' . $class . '">' . get_the_title() . '</div>',
							'msg'      => '',
						);
					endwhile;
					wp_reset_postdata();
				} else {
					$data[] = array(
						'status'   => false,
						'image'    => '',
						'link_url' => '',
						'title'    => '',
						'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
					);
				}
			} else {
				$data[] = array(
					'status'   => false,
					'image'    => '',
					'link_url' => '',
					'title'    => '',
					'msg'      => '<div class="search-result-name">' . esc_html__( 'No Results', 'cardealer' ) . '</div>',
				);
			}
		}
		echo wp_json_encode( $data );

		exit();
	}
}
if ( ! function_exists( 'cardealer_validate_google_captch' ) ) {
	/**
	 * Validate google captch.
	 *
	 * @param string $captcha .
	 */
	function cardealer_validate_google_captch( $captcha ) {
		$secret_key = cardealer_get_goole_api_keys( 'secret_key' );
		if ( empty( $secret_key ) ) {
			return array( 'success' => true );
		}
		$response = array();

		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $captcha . '&remoteip=' . sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ), array( 'timeout' => 30 ) );
		}

		if ( ! empty( $response ) && isset( $response['body'] ) ) {
			return json_decode( $response['body'], true );
		} else {
			return array( 'success' => false );
		}
	}
}
if ( ! function_exists( 'cardealer_get_goole_api_keys' ) ) {
	/**
	 * Get google api
	 *
	 * @param string $key_type .
	 */
	function cardealer_get_goole_api_keys( $key_type = '' ) {
		global $car_dealer_options;

		$key = '';

		$site_key   = ( isset( $car_dealer_options['google_captcha_site_key'] ) && ! empty( $car_dealer_options['google_captcha_site_key'] ) ) ? $car_dealer_options['google_captcha_site_key'] : '';
		$secret_key = ( isset( $car_dealer_options['google_captcha_secret_key'] ) && ! empty( $car_dealer_options['google_captcha_secret_key'] ) ) ? $car_dealer_options['google_captcha_secret_key'] : '';

		if ( ( ! empty( $site_key ) && ! empty( $secret_key ) ) && 'site_key' === $key_type ) {
			$key = $site_key;
		}

		if ( ( ! empty( $site_key ) && ! empty( $secret_key ) ) && 'secret_key' === $key_type ) {
			$key = $secret_key;
		}

		return $key;
	}
}
if ( ! function_exists( 'cardealer_photoswipe' ) ) {
	/**
	 *   Photo swipe popup for cars
	 */
	function cardealer_photoswipe() {
		?>
		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="pswp__bg"></div>
			<div class="pswp__scroll-wrap">
				<div class="pswp__container">
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
				</div>
				<div class="pswp__ui pswp__ui--hidden">
					<div class="pswp__top-bar">
						<div class="pswp__counter"></div>
						<button class="pswp__button pswp__button--close" title="<?php esc_attr_e( 'Close (Esc)', 'cardealer' ); ?>"></button>
						<button class="pswp__button pswp__button--fs" title="<?php esc_attr_e( 'Toggle fullscreen', 'cardealer' ); ?>"></button>
						<button class="pswp__button pswp__button--zoom" title="<?php esc_attr_e( 'Zoom in/out', 'cardealer' ); ?>"></button>

						<div class="pswp__preloader">
							<div class="pswp__preloader__icn">
							<div class="pswp__preloader__cut">
								<div class="pswp__preloader__donut"></div>
							</div>
							</div>
						</div>
					</div>
					<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
						<div class="pswp__share-tooltip"></div>
					</div>
					<button class="pswp__button pswp__button--arrow--left" title="<?php esc_attr_e( 'Previous (arrow left)', 'cardealer' ); ?>">
					</button>
					<button class="pswp__button pswp__button--arrow--right" title="<?php esc_attr_e( 'Next (arrow right)', 'cardealer' ); ?>">
					</button>
					<div class="pswp__caption">
						<div class="pswp__caption__center"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'wp_footer', 'cardealer_photoswipe' );

add_action( 'admin_init', 'cardealer_remove_metabox' );
if ( ! function_exists( 'cardealer_remove_metabox' ) ) {
	/**
	 * Remove metabox.
	 */
	function cardealer_remove_metabox() {
		remove_meta_box( 'tagsdiv-car_year', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_make', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_model', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_body_style', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_condition', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_mileage', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_transmission', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_drivetrain', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_engine', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_economy', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_exterior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_interior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_stock_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_vin_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_type', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_trim', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_features_options', 'cars', 'side' );
		remove_meta_box( 'car_features_optionsdiv', 'cars', 'side' );

		// Get all taxnomies for cars post type.
		$taxonomies_raw = get_object_taxonomies( 'cars' );

		foreach ( $taxonomies_raw as $new_tax ) {
			$new_tax_obj = get_taxonomy( $new_tax );
			if ( isset( $new_tax_obj->include_in_filters ) && true === (bool) $new_tax_obj->include_in_filters ) {
				if ( false === (bool) $new_tax_obj->hierarchical ) {
					remove_meta_box( "tagsdiv-{$new_tax}", 'cars', 'side' );
				} else {
					remove_meta_box( "{$new_tax}div", 'cars', 'side' );
				}
			}
		}

	}
}
if ( ! function_exists( 'cardealer_get_vehicles_taxonomies' ) ) {
	/**
	 * Get vehicles taxonomies
	 *
	 * @param array $taxonomies_unset .
	 * @param array $return_array_type .
	 */
	function cardealer_get_vehicles_taxonomies( $taxonomies_unset = array(), $return_array_type = 'val_to_key' ) {
		if ( ! empty( $taxonomies_unset ) ) {
			$unset_taxonomies = $taxonomies_unset;
		} else {
			$unset_taxonomies = array( 'car_features_options' );
		}
		$taxonomies = get_object_taxonomies( 'cars' );
		foreach ( $unset_taxonomies as $taxo ) {
			if ( array_search( $taxo, $taxonomies ) !== false ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$key = array_search( $taxo, $taxonomies ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				unset( $taxonomies[ $key ] );
			}
		}
		$taxonomy_array = array();
		if ( 'val_to_key' === $return_array_type ) { // array( taxonomy_label => taxonomy_key ).
			foreach ( $taxonomies as $taxonomy ) {
				$tax_obj                           = get_taxonomy( $taxonomy );
				$taxonomy_array[ $tax_obj->label ] = $taxonomy;
			}
		} else { // array( taxonomy_key => taxonomy_label ).
			foreach ( $taxonomies as $taxonomy ) {
				$tax_obj                     = get_taxonomy( $taxonomy );
				$taxonomy_array[ $taxonomy ] = $tax_obj->label;
			}
		}
		/**
		 * Filters the vehicle taxonomy array .
		 *
		 * @since 1.0
		 *
		 * @param array    $taxonomy_array   A list of taxonomies with taxonomy slug to taxonomy label pair.
		 * @visible        true
		 */
		return apply_filters( 'cardealer_vehicles_taxonomies', $taxonomy_array );
	}
}

if ( ! function_exists( 'cardealer_term_redirect' ) ) {
	/**
	 * TAXONOMY REDIRECT CODE START
	 * Function used to redirect page from 404 to car archive when term of the taxonomy is called in URL, But not available in taxonomy
	 * If this is not used page will redirect to 404 when term(which is not available for taxonomy) called in URL.
	 */
	function cardealer_term_redirect() {
		global $wp_query;

		if ( $wp_query->is_404() ) {
			if ( isset( $wp_query->query['post_type'] ) && 'cars' === $wp_query->query['post_type'] ) {
				if ( isset( $wp_query->query['car_condition'] ) ) {
					$car_condition = strtolower( $wp_query->query['car_condition'] );
					if ( in_array( $car_condition, array( 'used', 'certified', 'new', 'n', 'u', 'c' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
						add_filter( 'cardealer_page_title', 'cardealer_term_page_title' ); // Page Title .
						add_filter( 'cardealer_subtitle_title', 'cardealer_term_subtitle_title' ); // Sub Title .
						add_filter( 'document_title_parts', 'cardealer_vehicle_browser_title', 99 ); // Browser Title .
						add_filter( 'template_include', 'cardealer_term_template_redirect' ); // Redirect to archive-cars.php .
					}
				}
			}
		}
	}
}
add_action( 'wp', 'cardealer_term_redirect' );

if ( ! function_exists( 'cardealer_vehicle_browser_title' ) ) {
	/**
	 * Vehicle browser title
	 *
	 * @param string $title .
	 */
	function cardealer_vehicle_browser_title( $title ) {
		$title_array = cardealer_get_vehicle_page_titles();
		if ( ! empty( $title_array['title'] ) ) {
			$title['title'] = $title_array['title'];
		}
		return $title;
	}
}
if ( ! function_exists( 'cardealer_term_template_redirect' ) ) {
	/**
	 * Term template redirect
	 *
	 * @param string $template .
	 */
	function cardealer_term_template_redirect( $template ) {
		$template = get_query_template( 'archive-cars' );
		return $template;
	}
}
if ( ! function_exists( 'cardealer_term_page_title' ) ) {
	/**
	 * Term page title
	 *
	 * @param string $title .
	 */
	function cardealer_term_page_title( $title ) {
		$title_array = cardealer_get_vehicle_page_titles();
		if ( ! empty( $title_array['title'] ) ) {
			return $title_array['title'];
		}
		return $title;
	}
}
if ( ! function_exists( 'cardealer_term_subtitle_title' ) ) {
	/**
	 * Term subtitle
	 *
	 * @param string $subtitle .
	 */
	function cardealer_term_subtitle_title( $subtitle ) {
		$subtitle_array = cardealer_get_vehicle_page_titles();
		if ( ! empty( $subtitle_array['subtitle'] ) ) {
			return $subtitle_array['subtitle'];
		}
		return $subtitle;
	}
}
if ( ! function_exists( 'cardealer_get_vehicle_page_titles' ) ) {
	/**
	 * Vehicle page titles
	 */
	function cardealer_get_vehicle_page_titles() {
		global $car_dealer_options;
		$titles = array(
			'title'    => '',
			'subtitle' => '',
		);
		// Theme option vehicle inventory page title.
		$cars_listing_title = ( isset( $car_dealer_options['cars-listing-title'] ) ) ? $car_dealer_options['cars-listing-title'] : '';
		$page_title         = '';
		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
			$car_page  = get_post( $car_dealer_options['cars_inventory_page'] );
			$page_path = isset( $car_page->post_name ) ? $car_page->post_name : 'cars';
			$page      = get_page_by_path( $page_path );
			if ( $page ) {
				$page_title         = get_the_title( $page );
				$titles['subtitle'] = get_post_meta( $page->ID, 'subtitle', true );
			}
		} else {
			$page_title = $cars_listing_title;
		}

		if ( ! empty( $page_title ) ) {
			$title = $page_title;
		} else {
			$title = post_type_archive_title( '', false );
		}
		$titles['title'] = $title;
		return $titles;
	}
}
if ( ! function_exists( 'cardealer_get_inv_list_style' ) ) {
	/**
	 * Get inv list style
	 */
	function cardealer_get_inv_list_style() {
		global $car_dealer_options;
		$list_style = 'default';
		if ( isset( $car_dealer_options['inv-list-style'] ) && ! empty( $car_dealer_options['inv-list-style'] ) ) {
			$list_style = $car_dealer_options['inv-list-style'];
		}
		return $list_style;
	}
}
if ( ! function_exists( 'cardealer_get_cars_image_src' ) ) {
	/**
	 * TAXONOMY REDIRECT CODE END
	 *
	 * @param string $car_size .
	 * @param bool   $id .
	 */
	function cardealer_get_cars_image_src( $car_size = 'car_catalog_image', $id = null ) {
		if ( empty( $car_size ) ) {
			$car_size = 'car_catalog_image';
		}
		global $post;
		$car_id = ( null !== $id ) ? $id : $post->ID;
		if ( function_exists( 'get_field' ) ) {
			$images = get_field( 'car_images', $car_id );
			if ( ! empty( $images ) ) {
				$img_url = esc_url( $images[0]['sizes'][ $car_size ] );
			} else {
				$img_url = cardealer_get_carplaceholder( $car_size, 'url' );
			}
		} else {
			$img_url = cardealer_get_carplaceholder( $car_size, 'url' );
		}
		return $img_url;
	}
}
if ( ! function_exists( 'cardealer_invenory_pg_vc_css' ) ) {
	/**
	 * VC Style Sheets
	 */
	function cardealer_invenory_pg_vc_css() {
		global $car_dealer_options;
		$inventory_pg_id = (int) cardealer_get_current_post_id();
		$front_page      = (int) get_option( 'page_on_front' );

		if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) && (int) $car_dealer_options['cars_inventory_page'] === $front_page ) {
			$inventory_pg_id = $front_page;
		}

		if ( isset( $car_dealer_options['cars_inventory_page'] ) ) {
			if ( ! is_wp_error( $inventory_pg_id ) && (int) $car_dealer_options['cars_inventory_page'] === $inventory_pg_id ) {
				$shortcodes_custom_css = get_post_meta( $inventory_pg_id, '_wpb_shortcodes_custom_css', true );
				if ( ! empty( $shortcodes_custom_css ) ) {
					$shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
					wp_add_inline_style( 'cardealer-main', $shortcodes_custom_css );
				}
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'cardealer_invenory_pg_vc_css', 160 );

if ( ! function_exists( 'cardealer_list_layout_style_lazyload' ) ) {
	/**
	 * List layout style lazyload
	 *
	 * @param string $layout .
	 */
	function cardealer_list_layout_style_lazyload( $layout ) {

		if ( is_author() && isset( $_GET['profile-tab'] ) && 'listing' === $_GET['profile-tab'] ) {
			return $layout;
		}

		$listing_layout = cardealer_get_vehicle_listing_page_layout();
		if ( 'lazyload' === $listing_layout ) {
			$layout = 'view-grid';
		}
		return $layout;
	}
}
add_filter( 'cardealer_list_layout_style', 'cardealer_list_layout_style_lazyload' );

add_action(
	'init',
	function() {
		$listing_layout = cardealer_get_vehicle_listing_page_layout();
		if ( 'lazyload' === $listing_layout ) {
			if ( isset( $_COOKIE['lay_style'] ) ) {
				setcookie( 'lay_style', 'view-grid', time() + ( 10 * 365 * 24 * 60 * 60 ) );
			}
		}
	}
);

if ( ! function_exists( 'cardealer_set_vehicle_list_view_type' ) ) {
	/**
	 * Vehicle list view type
	 *
	 * @param string $cars_grid .
	 */
	function cardealer_set_vehicle_list_view_type( $cars_grid ) {

		if ( is_author() && isset( $_GET['profile-tab'] ) && 'listing' === $_GET['profile-tab'] ) {
			return $cars_grid;
		}

		$listing_layout = cardealer_get_vehicle_listing_page_layout();
		if ( 'lazyload' === $listing_layout ) {
			return 'yes';
		}
		return $cars_grid;
	}
}
add_filter( 'cardealer_vehicle_list_view_type', 'cardealer_set_vehicle_list_view_type' );

/**
 * Vehicle make logo html
 *
 * @param array $filtered_makes filtered makes.
 */
function cdhl_vehicle_make_logos_html( $filtered_makes ) {
	$make_widgets_html = array();
	$make_widgets      = ( isset( $_REQUEST['make_widgets'] ) && ! empty( $_REQUEST['make_widgets'] ) ) ? wp_unslash( $_REQUEST['make_widgets'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$make_generator    = new CDHL_Vehicle_Make_Logos_Generator();

	$car_makes = $make_generator->get_makes();

	foreach ( $make_widgets as $widget => $widget_args ) {
		$make_widgets_html[ $widget ] = '';

		if ( ! is_array( $car_makes ) || empty( $car_makes ) ) {
			continue;
		}

		$widget_args['include_makes'] = $filtered_makes;

		ob_start();
		$make_generator->generate_makes( $widget_args );
		$make_widgets_html[ $widget ] = ob_get_clean();
	}

	return $make_widgets_html;
}


if ( ! function_exists( 'cd_sell_car_online' ) ) {
	/**
	 * Ajax call for buy vehicle
	 */
	function cd_sell_car_online() {

		if ( ! isset( $_POST['ajax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), 'cd_sell_car_online_ajax' ) ) {
			$response = array(
				'status' => 'error',
				'msg'    => esc_html__( 'Unable to verify security nonce. Please try again.', 'cardealer' ),
			);
		} else {
			$response = array(
				'status' => 'error',
				'msg'    => esc_html__( 'Something went wrong!', 'cardealer' ),
			);

			$vehicle_id = isset( $_POST['vehicle_id'] ) ? intval( $_POST['vehicle_id'] ) : '';
			if ( $vehicle_id ) {
				if ( class_exists( 'WooCommerce' ) ) {

					$price_arr = cardealer_get_car_price_array( $vehicle_id );
					if ( ! empty( $price_arr ) ) {

						$final_price = $price_arr['sale_price'];
						if ( 0 < $price_arr['sale_price'] ) {
							$final_price = $price_arr['sale_price'];
						}
					}

					update_post_meta( $vehicle_id, '_price', $final_price );

					$checkout_url = wc_get_checkout_url() . '?add-to-cart=' . $vehicle_id;
					$response     = array(
						'status'       => 'success',
						'msg'          => '',
						'redirect_url' => $checkout_url,
					);

					wp_send_json( $response );
				}
			}
		}

		wp_send_json( $response );
	}
}
add_action( 'wp_ajax_cd_sell_car_online', 'cd_sell_car_online' );
add_action( 'wp_ajax_nopriv_cd_sell_car_online', 'cd_sell_car_online' );

function cardealer_vehicle_listing_mobile_filter_location() {
	global $car_dealer_options;
	return ( isset( $car_dealer_options['vehicle_listing_mobile_filter_location'] ) && ! empty( $car_dealer_options['vehicle_listing_mobile_filter_location'] ) ) ? $car_dealer_options['vehicle_listing_mobile_filter_location'] : 'off-canvas';
}

function cardealer_get_featured_vehicles_list_style() {
	global $car_dealer_options;

	$list_style = ( isset( $car_dealer_options['featured_vehicles_list_style'] ) && ! empty( $car_dealer_options['featured_vehicles_list_style'] ) ) ? $car_dealer_options['featured_vehicles_list_style'] : 'grid';

	$cars_list_layout_style = cardealer_get_cars_list_layout_style();
	if ( 'view-list' === $cars_list_layout_style ) {
		$list_style = 'grid';
	}

	return apply_filters( 'cardealer_get_featured_vehicles_list_style', $list_style );
}
