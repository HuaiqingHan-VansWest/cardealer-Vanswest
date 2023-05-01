<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sold Cars function
 *
 * @package Cardealer
 */

if ( ! function_exists( 'get_sold_query' ) ) {
	/**
	 * Get sold cars query arguments.
	 *
	 * @see get_sold_query()
	 *
	 * @return query arguments array
	 */
	function get_sold_query() {
		$data_html       = '';
		$pagination_html = '';

		global $car_dealer_options;

		$params = array();

		if ( isset( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ), $params ); // the context is safe and reliable.
		}

		$per_page   = 12;
		$cars_order = 'date (post_date)';

		if ( isset( $car_dealer_options['cars-per-page'] ) ) {
			$per_page = $car_dealer_options['cars-per-page'];
		}
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		if ( isset( $params['cars_pp'] ) && ! empty( $params['cars_pp'] ) ) {
			$per_page = $params['cars_pp'];
		}

		$data_order = cardealer_get_default_sort_by_order();// get default option value.
		if ( isset( $params['cars_order'] ) && ! empty( $params['cars_order'] ) && in_array( $params['cars_order'], array( 'desc', 'asc' ), true ) ) {
			$data_order = $params['cars_order'];
		}

		$args = array(
			'post_type'      => 'cars',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'order'          => $data_order,
			'paged'          => $paged,
		);

		$pgs_min_price = isset( $params['min_price'] ) ? esc_attr( $params['min_price'] ) : 0;
		$pgs_max_price = isset( $params['max_price'] ) ? esc_attr( $params['max_price'] ) : 0;
		if ( $pgs_min_price > 0 || $pgs_max_price > 0 ) {
			$prices = cardealer_get_sold_car_filtered_price();
			$min    = floor( $prices->min_price );
			$max    = ceil( $prices->max_price );

			if ( $min !== $pgs_min_price || $max !== $pgs_max_price ) {
				$args['meta_query'][] = array(
					'key'     => 'final_price',
					'value'   => array( $pgs_min_price, $pgs_max_price ),
					'compare' => 'BETWEEN',
					'type'    => 'NUMERIC',
				);
			}
		}

		if ( isset( $params['cars_orderby'] ) && ! empty( $params['cars_orderby'] ) ) {
			$cars_orderby = $params['cars_orderby'];
		}

		if ( isset( $cars_orderby ) && ! empty( $cars_orderby ) ) {
			if ( 'sale_price' === $cars_orderby ) {
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'final_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			} else {
				$args['orderby'] = $cars_orderby;
			}
		}

		$args['meta_query'][] =
		array(
			'key'     => 'car_status',
			'value'   => 'sold',
			'compare' => '=',
		);
		return $args;
	}
}

if ( ! function_exists( 'cardealer_sold_list_layout_style' ) ) {
	/**
	 * Sold List layout styles
	 */
	function cardealer_sold_list_layout_style() {
		$lay_style = 'view-grid';
		if ( isset( $_GET['lay_style'] ) && 'view-list' === $_GET['lay_style'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$lay_style = 'view-list';
		}
		return $lay_style;
	}
}


if ( ! function_exists( 'cardealer_get_sold_cars_price_filters' ) ) {
	/**
	 * Sold cars price filter slider html
	 */
	function cardealer_get_sold_cars_price_filters() {
		global $car_dealer_options, $cardealer_price_range_instance;
		$cardealer_price_range_instance = ( isset( $cardealer_price_range_instance ) ) ? $cardealer_price_range_instance + 1 : 1;

		$price_range_slider_id = "dealer-slider-amount-$cardealer_price_range_instance";
		$price_slider_range_id = "slider-range-$cardealer_price_range_instance";

		$pgs_min_price = isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$pgs_max_price = isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		// Find min and max price in current result set.
		$prices = cardealer_get_sold_car_filtered_price();
		$min    = floor( $prices->min_price );
		$max    = ceil( $prices->max_price );

		// code for price step theme option.
		if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
			$min_difference = $car_dealer_options['price_range_step'] - ( $prices->min_price % $car_dealer_options['price_range_step'] );
			$min            = floor( $prices->min_price += $min_difference - $car_dealer_options['price_range_step'] ); // Round up min price.
			$max_difference = $car_dealer_options['price_range_step'] - ( $prices->max_price % $car_dealer_options['price_range_step'] );
			$max            = ceil( $prices->max_price += $max_difference ); // Round up max price.
		}

		// Range Slider Step.
		$step = 100;
		if ( isset( $car_dealer_options['price_range_step'] ) && ! empty( $car_dealer_options['price_range_step'] ) ) {
			$step = $car_dealer_options['price_range_step'];
		}

		if ( $min === $max ) {
			return;
		}

		?>
		<div class="price_slider_wrapper">
			<div class="price-slide">
				<div class="price">
					<input type="hidden" class="pgs-price-slider-min" name="min_price" value="<?php echo esc_attr( $pgs_min_price ); ?>" data-min="<?php echo esc_attr( $min ); ?>" />
					<input type="hidden" class="pgs-price-slider-max" name="max_price" value="<?php echo esc_attr( $pgs_max_price ); ?>" data-max="<?php echo esc_attr( $max ); ?>" data-step="<?php echo esc_attr( $step ); ?>" />
					<label for="<?php echo esc_attr( $price_range_slider_id ); ?>"><?php echo esc_html__( 'Price:', 'cardealer' ); ?></label>
					<div id="<?php echo esc_attr( $price_range_slider_id ); ?>" class="dealer-slider-amount" class="amount"></div>
					<div id="<?php echo esc_attr( $price_slider_range_id ); ?>" class="slider-range range-slide-slider"></div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'cardealer_get_sold_car_filtered_price' ) ) {
	/**
	 * Get filtered min price for current list query.
	 *
	 * @return int
	 */
	function cardealer_get_sold_car_filtered_price() {
		global $wpdb;

		// @codingStandardsIgnoreStart
		return $wpdb->get_row(
			$wpdb->prepare(
				'
				SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM ' . $wpdb->prefix . 'posts
				LEFT JOIN ' . $wpdb->prefix . 'postmeta as price_meta ON ' . $wpdb->prefix . 'posts.ID = price_meta.post_id
				INNER JOIN ' . $wpdb->prefix . 'postmeta ON (' . $wpdb->prefix . 'posts.ID = ' . $wpdb->prefix . 'postmeta.post_id )
				WHERE ' . $wpdb->prefix . 'posts.post_type IN ("cars")
				AND ' . $wpdb->prefix . 'posts.post_status = "publish"
				AND ' . $wpdb->prefix . 'postmeta.meta_key = "car_status"
				AND ' . $wpdb->prefix . 'postmeta.meta_value = "sold"
				AND price_meta.meta_key IN ("final_price")
				AND %d',
				1
			)
		);
		// @codingStandardsIgnoreEnd
	}
}

if ( ! function_exists( 'cardealer_get_sold_view' ) ) {
	/**
	 * Sold cars layout grid / list view icon buttons html
	 */
	function cardealer_get_sold_view() {
		global $car_dealer_options;
		$theme_color = isset( $car_dealer_options['site_color_scheme_custom']['color'] ) ? $car_dealer_options['site_color_scheme_custom']['color'] : '';

		add_filter( 'cardealer_list_layout_style', 'cardealer_sold_list_layout_style' );
		$getlayout = cardealer_get_cars_list_layout_style();

		$grid_sts = ( 'view-grid' === $getlayout ) ? 'act' : '';
		$list_sts = ( 'view-list' === $getlayout ) ? 'act' : '';

		$class2 = ( 'view-grid' === $getlayout ) ? "background-color:$theme_color;" : '';
		$class5 = ( 'view-list' === $getlayout ) ? "background-color:$theme_color;" : '';
		?>
		<div class="grid-view change-view-button">
			<div class="view-icon">
				<a class="catlog-layout-sold view-grid-sold" data-sts="<?php echo esc_attr( $grid_sts ); ?>" data-id="view-grid" href="javascript:void(0)"><span style="<?php echo esc_attr( $class2 ); ?>"><i class="view-grid-full"></i></span></a>
				<a class="catlog-layout-sold view-list-sold" data-sts="<?php echo esc_attr( $list_sts ); ?>" data-id="view-list" href="javascript:void(0)"><span style="<?php echo esc_attr( $class5 ); ?>"><i class="view-list-full"></i></span></a>
			</div>
		</div><!--.grid-view-->
		<?php
	}
}

if ( ! function_exists( 'cardealer_cars_sold_ordering' ) ) {
	/**
	 * Sold cars per page / sort by / order by html
	 */
	function cardealer_cars_sold_ordering() {
		global $car_dealer_options;

		$params       = array();
		$query_string = '';

		if ( isset( $_SERVER['QUERY_STRING'] ) ) {
			parse_str( sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ), $params ); // the context is safe and reliable.
			$query_string = '?' . sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) );
		}
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

		$cars_order_selected = cardealer_get_default_sort_by_order(); // get default option value.
		if ( isset( $params['cars_order'] ) && ! empty( $params['cars_order'] ) && in_array( $params['cars_order'], array( 'desc', 'asc' ), true ) ) {
			$cars_order_selected = $params['cars_order'];
		}

		$cars_pp_selected = ( isset( $params['cars_pp'] ) && ! empty( $params['cars_pp'] ) ) ? $params['cars_pp'] : $per_page;
		?>
		<div class="selected-box">
			<select name="cars_pp" id="pgs_cars_pp_sold" class="cd-select-box">
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
		$cardealer_orderby_types = array(
			'name'       => esc_html__( 'Sort by Name', 'cardealer' ),
			'sale_price' => esc_html__( 'Sort by Price', 'cardealer' ),
			'date'       => esc_html__( 'Sort by Date', 'cardealer' ),
		);
		?>
		<div class="selected-box">
			<div class="select">
				<select class="select-box cd-select-box" name="cars_orderby" id="pgs_cars_orderby_sold">
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
			<div class="cars-order text-right"><a id="pgs_cars_order_sold" class="cars-order-sold" data-order="desc" data-current_order="asc" href="javascript:void(0)"><i class="fas fa-arrow-up"></i></a></div>
			<?php
		} else {
			?>
			<div class="cars-order text-right"><a id="pgs_cars_order_sold" class="cars-order-sold" data-order="asc" data-current_order="desc" href="javascript:void(0)"><i class="fas fa-arrow-down"></i></a></div>
			<?php
		}
	}
}

