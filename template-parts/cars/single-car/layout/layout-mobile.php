<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */
$title_location   = cardealer_get_cars_details_title_location_mobile();

remove_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_trade_in_appraisal', 10 );
remove_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_buy_online_btn', 20 );
remove_action( 'cardealer_single_vehicle_sidebar', 'cardealer_single_vehicle_sidebar_review_stamps', 30 );
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<?php cardealer_vehicle_detail_page_render_mobile_sections(); ?>
	</div>
</div>
