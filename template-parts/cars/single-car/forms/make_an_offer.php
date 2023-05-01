<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( isset( $car_dealer_options['make_offer_form_status'] ) && ! $car_dealer_options['make_offer_form_status'] ) {
	return;
}
$lead_form = cardealer_get_lead_form( 'make_an_offer' );
?>
<li>
	<a data-toggle="modal" data-target="#make_an_offer" href="#"><i class="<?php echo esc_attr( $lead_form['icon'] ); ?>"></i><?php echo esc_html( $lead_form['btn_label'] ); ?></a>
	<div class="modal fade cardealer-lead-form cardealer-lead-form-make-an-offer" id="make_an_offer" tabindex="-1" role="dialog" aria-labelledby="make_an_offer_lbl" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h6 class="modal-title" id="make_an_offer_lbl"><?php echo esc_html( $lead_form['modal_title'] ); ?></h6>
				</div>
				<div class="modal-body">
					<?php
					if ( isset( $car_dealer_options['make_offer_form'] ) && ! empty( $car_dealer_options['make_offer_form'] ) && '1' === $car_dealer_options['make_offer_contact_7'] ) {
						
						global $cdhl_make_offer_form, $cdhl_make_offer_form_fields;

						$cdhl_make_offer_form        = true;
						$cdhl_make_offer_form_fields = array(
							'cdhl_make_offer_form'            => 'yes',
							'cdhl_make_offer_form_user_email' => get_the_author_meta( 'email' ),
						);

						echo do_shortcode( $car_dealer_options['make_offer_form'] );

						$cdhl_make_offer_form        = false;
						$cdhl_make_offer_form_fields = array();
						
					} else {
						?>
						<form name="make_an_offer" class="gray-form" method="post" id="make_an_offer_test_form">
							<div class="row">
								<input type="hidden" name="action" class="form-control" value="make_an_offer_action" />
								<?php wp_nonce_field( 'make_an_offer', 'mno_nonce' ); ?>
								<input type="hidden" name="car_id" value="<?php echo get_the_ID(); ?>">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="mao_fname"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="mao_fname" class="form-control cdhl_validate" id="mao_fname" maxlength="25"/>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="mao_lname"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="mao_lname" class="form-control cdhl_validate" id="mao_lname" maxlength="25"/>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="mao_email"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="mao_email" id="mao_email" class="form-control cdhl_validate cardealer_mail" >
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="mao_phone"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_phone', esc_html__( 'Phone', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="mao_phone" id="mao_phone" class="form-control cdhl_validate" maxlength="15" >
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="mao_message"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_message', esc_html__( 'Message', 'cardealer' ) ) ); ?></label>
										<textarea name="mao_message" class="form-control" id="mao_message" maxlength="300"></textarea>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="mao_reques_price"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_request_price', esc_html__( 'Request Price', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="mao_reques_price" id="mao_reques_price" class="form-control cdhl_validate" maxlength="15" >
									</div>
								</div>
								<?php
								if ( function_exists( 'the_privacy_policy_link' ) && isset( $car_dealer_options['mao_policy_terms'] ) && '1' === $car_dealer_options['mao_policy_terms'] ) {
									?>
									<div class="col-sm-12 cdhl-terms-privacy-container">
										<label>
											<input type="checkbox" name="cdhl_terms_privacy" class="form-control cdhl_validate terms" />
											<?php
											echo esc_html(
												apply_filters(
													'cd_mno_inquiry_privacy_text',
													cardealer_get_theme_option( 'cstfrm_lbl_privacy_agreement', esc_html__( 'You agree with the storage and handling of your personal and contact data by this website.', 'cardealer' ) )
												)
											);
											?>
										</label>
									</div>
									<?php
								}
								$google_captcha_site_key   = cardealer_get_theme_option( 'google_captcha_site_key' );
								$google_captcha_secret_key = cardealer_get_theme_option( 'google_captcha_secret_key' );
								if ( ! empty( $google_captcha_site_key ) && ! empty( $google_captcha_secret_key ) ) {
									?>
									<div class="col-sm-12">
										<div class="form-group">
											<div id="recaptcha2"></div>
										</div>
									</div>
									<?php
								}
								?>
								<div class="col-sm-12">
									<div class="form-group">
										<button id="make_an_offer_test_request" class="button red" ><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_send_btn', esc_html__( 'Send', 'cardealer' ) ) ); ?></button>
										<span class="make_an_offer_test_spinimg"></span>
										<p class="make_an_offer_test_msg" style="display:none;"></p>
									</div>
								</div>
							</div>
						</form>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</li>
