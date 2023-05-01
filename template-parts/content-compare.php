<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$vehicle_mileage_unit = ( isset( $car_dealer_options['vehicle_mileage_unit'] ) && 'none' !== $car_dealer_options['vehicle_mileage_unit'] ) ? $car_dealer_options['vehicle_mileage_unit'] : '';

$car_ids        = $args['car_ids'];
$num_of_cars    = $args['num_of_cars'];
$compare_fields = $args['compare_fields'];
?>
<div class="modal-header">
	<button type="button" class="close_model" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h1><?php cdhl_compare_popup_title(); ?></h1>
</div>
<div class="modal-content">
	<div class="table-Wrapper">
		<div class="heading-Wrapper">
			<div class="cardealer-vehicle-compare-list-header">
				<?php
				foreach ( $compare_fields as $key => $val ) {
					?>
					<div class="cardealer-vehicle-compare-list-title"><?php echo esc_html( $val ); ?></div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="table-scroll modal-body" id="getCode">
			<div id="sortable" style="width:<?php echo esc_attr( $num_of_cars * 258 ); ?>px;">
				<?php
				for ( $cols = 1; $cols <= $num_of_cars; $cols++ ) {

					$vehicle_id   = $car_ids[ $cols - 1 ];
					$car_post = get_post( $vehicle_id );

					if ( ! $car_post || ( $car_post && 'cars' !== $car_post->post_type ) ) {
						continue;
					}

					$carlink = get_permalink( $vehicle_id );
					$class   = ( 0 === $cols % 2 ) ? 'even' : 'odd';
					?>
					<div class="compare-list compare-datatable cardealer-vehicle-compare-list-column" data-id="<?php echo esc_attr( $vehicle_id ); ?>">
						<?php
						foreach ( $compare_fields as $key => $val ) {
							?>
							<div class="cardealer-vehicle-compare-list-row">
								<?php
								if ( 'remove' === $key ) {
									?>
									<a href="javascript:void(0)" data-car_id="<?php echo esc_attr( $vehicle_id ); ?>" class="compare-remove-column"><span class="remove">x</span></a>
									<?php
								}elseif ( 'car_image' === $key ) {
									$carlink = get_permalink( $vehicle_id );
									?>
									<a href="<?php echo esc_url( $carlink ); ?>">
										<?php
										if ( function_exists( 'cardealer_get_cars_image' ) ) {
											echo wp_kses( cardealer_get_cars_image( 'car_thumbnail', $vehicle_id ), cardealer_allowed_html( array( 'img' ) ) );
										}
										?>
									</a>
									<?php
								}elseif ( 'price' === $key ) {
									
									$price_html = cardealer_car_price_html( '', $vehicle_id, true, false );

									if ( empty( $price_html ) ) {
										$price_html = '<div class="price car-price"><span class="new-price">&mdash;</span></div>';
									}
									echo wp_kses(
										apply_filters( 'cardealer_car_price_html', $price_html, $vehicle_id ),
										array(
											'div'  => array(
												'class'   => true,
												'data-id' => true
											),
											'bdi'  => array(
												'class' => true,
											),
											'p'    => array(),
											'a'    => array(
												'class' => true,
												'href'  => true
											),
											'span' => array(
												'class' => true,
											),
										)
									);
								} else {
									if ( 'features_options' === $key ) {
										$car_features_options = wp_get_post_terms( $vehicle_id, 'car_features_options' );
										$json                 = wp_json_encode( $car_features_options ); // Conver Obj to Array.
										$car_features_options = json_decode( $json, true ); // Conver Obj to Array.
										$name_array           = array_map(
											function ( $options ) {
												return $options['name'];
											},
											(array) $car_features_options
										); // get all name term array.
										$options              = implode( ',', $name_array );
										$options_data         = ( empty( $options ) ) ? '&nbsp;' : $options;
										$html                 = $options_data;
										echo esc_html( $html );
									} else {
										$vehicle_terms = wp_get_post_terms( $vehicle_id, $key );
										if ( empty( $vehicle_terms ) ) {
											echo '&mdash;';
										} else {
											if ( 'car_mileage' === $key && $vehicle_mileage_unit ) {
												echo esc_html( $vehicle_terms[0]->name . ' ' . $vehicle_mileage_unit );
											} else {
												echo esc_html( $vehicle_terms[0]->name );
											}
										}
									}
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
