<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$post_id = get_the_ID();
?>
<div class="car-details-sidebar">

	<?php do_action( 'cardealer_single_vehicle_before_sidebar', $post_id ); ?>

	<?php
	/**
	 * Hook: cardealer_single_vehicle_sidebar.
	 *
	 * @hooked cardealer_single_vehicle_sidebar_trade_in_appraisal - 10
	 * @hooked cardealer_single_vehicle_sidebar_buy_online_btn - 20
	 * @hooked cardealer_single_vehicle_sidebar_review_stamps - 30
	 * @hooked cardealer_single_vehicle_sidebar_attributes - 40
	 */
	do_action( 'cardealer_single_vehicle_sidebar', $post_id );
	?>

	<?php do_action( 'cardealer_single_vehicle_after_sidebar', $post_id ); ?>

</div>
