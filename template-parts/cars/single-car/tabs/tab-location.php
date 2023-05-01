<?php
global $car_dealer_options;
$car_id = get_the_ID();
$lat    = '';
$lan    = '';
$zoom   = 12;

if (
	( isset( $car_dealer_options['default_value_lat'] ) && ! empty( $car_dealer_options['default_value_lat'] ) )
	&& ( isset( $car_dealer_options['default_value_long'] ) && ! empty( $car_dealer_options['default_value_long'] ) )
	&& ( isset( $car_dealer_options['default_value_zoom'] ) && ! empty( $car_dealer_options['default_value_zoom'] ) )
) {
	$lat  = $car_dealer_options['default_value_lat'];
	$lan  = $car_dealer_options['default_value_long'];
	$zoom = $car_dealer_options['default_value_zoom'];
}

$location = get_post_meta( $car_id, 'vehicle_location', true );
$zoom     = ( $location && isset( $location['zoom'] ) && ! empty( $location['zoom'] ) ) ? $location['zoom'] : $zoom;

if ( $location && ! empty( $location['lat'] ) && ! empty( $location['lng'] ) ) {
	$lat  = $location['lat'];
	$lan  = $location['lng'];
} elseif ( isset( $location['address'] ) && ! empty( $location['address'] ) ) {

	// Get lat/lng from address.
	$addr_latlong = cardealer_get_lat_lnt( $location['address'] );

	// Map location.
	if (
		( isset( $addr_latlong['addr_found'] ) && '1' === $addr_latlong['addr_found'] )
		&& ( isset( $addr_latlong['lat'] ) && ! empty( $addr_latlong['lat'] ) )
		&& ( isset( $addr_latlong['lng'] ) && ! empty( $addr_latlong['lng'] ) )
	) {
		$new_location = array(
			'address' => $location['address'],
			'lat'     => $addr_latlong['lat'],
			'lng'     => $addr_latlong['lng'],
			'zoom'    => $zoom,
		);
		update_field( 'vehicle_location', $new_location, $car_id );
		$lat      = $addr_latlong['lat'];
		$lan      = $addr_latlong['lng'];
		$zoom     = $zoom;
	}

}
?>
<div class="acf-map" data-zoom="<?php echo esc_attr( $zoom ); ?>">
	<div class="marker" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lan ); ?>" data-zoom="<?php echo esc_attr( $zoom ); ?>" style="text-align: center;top: calc( 50% - 11px );position: relative;"><i aria-hidden="true" class="fas fa-map-marker-alt"></i> <?php echo esc_html__( 'Loading...', 'cardealer' ); ?></div>
</div>
