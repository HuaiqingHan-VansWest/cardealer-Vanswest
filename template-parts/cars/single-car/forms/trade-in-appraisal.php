<?php
global $car_dealer_options;
if ( ( isset( $car_dealer_options['trade_in_appraisal_form_status'] ) && ! $car_dealer_options['trade_in_appraisal_form_status'] ) || empty($car_dealer_options['trade_in_appraisal_form_cf7_shortcode']) ) {
	return;
}

if ( ! wp_is_mobile() ) {
	echo '<div class="vehicle-detail-trade-in-appraisal-wrap">';
}
$lead_form = cardealer_get_lead_form( 'trade_in_appraisal' );
?>
<a class="dealer-form-btn" data-toggle="modal" data-target="#trade-in-appraisal-modal" href="#"><i class="<?php echo esc_attr( $lead_form['icon'] ); ?>"></i><?php echo esc_html( $lead_form['btn_label'] ); ?></a>
<div class="modal fade cardealer-lead-form cardealer-lead-form-trade-in-appraisal" id="trade-in-appraisal-modal" tabindex="-1" role="dialog" aria-labelledby="trade-in-appraisal-lbl" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h6 class="modal-title" id="trade-in-appraisal-lbl"><?php echo esc_html( $lead_form['modal_title'] ); ?></h6>
			</div>
			<div class="modal-body">
				<?php
				global $cdhl_trade_in_appraisal_form, $cdhl_trade_in_appraisal_form_fields;

				$cdhl_trade_in_appraisal_form        = true;
				$cdhl_trade_in_appraisal_form_fields = array(
					'cdhl_trade_in_appraisal_form'            => 'yes',
					'cdhl_trade_in_appraisal_form_user_email' => get_the_author_meta( 'email' ),
				);
				
				echo do_shortcode( $car_dealer_options['trade_in_appraisal_form_cf7_shortcode'] );
				
				$cdhl_trade_in_appraisal_form        = false;
				$cdhl_trade_in_appraisal_form_fields = array();
				?>
			</div>
		</div>
	</div>
</div>
<?php
if ( ! wp_is_mobile() ) {
	echo '</div>';
}
?>

