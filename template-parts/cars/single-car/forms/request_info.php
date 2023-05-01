<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( isset( $car_dealer_options['req_info_form_status'] ) && ! $car_dealer_options['req_info_form_status'] ) {
	return;
}
$lead_form = cardealer_get_lead_form( 'req_more_info' );
?>
<li><a class="dealer-form-btn" data-toggle="modal" data-target="#request_more_info_mdl" data-whatever="@mdo" href="#"><i class="<?php echo esc_attr( $lead_form['icon'] ); ?>"></i><?php echo esc_html( $lead_form['btn_label'] ); ?></a>
	<div class="modal fade cardealer-lead-form cardealer-lead-form-request-more-info" id="request_more_info_mdl" tabindex="-1" role="dialog" aria-labelledby="request_more_info_lbl" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h6 class="modal-title" id="request_more_info_lbl"><?php echo esc_html( $lead_form['modal_title'] ); ?></h6>
				</div>
				<div class="modal-body">
					<?php
					if ( isset( $car_dealer_options['req_info_form'] ) && ! empty( $car_dealer_options['req_info_form'] ) && '1' === (string) $car_dealer_options['req_info_contact_7'] ) {

						global $cdhl_req_info_form, $cdhl_req_info_form_fields;

						$cdhl_req_info_form        = true;
						$cdhl_req_info_form_fields = array(
							'cdhl_req_info_form'            => 'yes',
							'cdhl_req_info_form_user_email' => get_the_author_meta( 'email' ),
						);
						
						echo do_shortcode( $car_dealer_options['req_info_form'] );
						
						$cdhl_req_info_form        = false;
						$cdhl_req_info_form_fields = array();
						
					} else {
						?>
						<form class="gray-form reset_css" method="post" id="inquiry-form">
							<div class="row">
								<input type="hidden" name="action" class="form-control" value="car_inquiry_action">
								<input type="hidden" name="car_id" value="<?php echo get_the_ID(); ?>">
								<?php wp_nonce_field( 'req-info-form', 'rmi_nonce' ); ?>
								<div class="col-sm-6">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="first_name" class="form-control cdhl_validate" required maxlength="25">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="last_name" class="form-control cdhl_validate" required maxlength="25">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="email" class="form-control cdhl_validate cardealer_mail" maxlength="50">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer' ) ) ); ?>*</label>
										<input type="text" name="mobile" class="form-control cdhl_validate"  maxlength="15">
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_address', esc_html__( 'Address', 'cardealer' ) ) ); ?></label>
										<textarea class="form-control" name="address" rows="2" maxlength="300"></textarea>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer' ) ) ); ?></label>
										<input type="text" name="state" class="form-control" maxlength="25">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer' ) ) ); ?></label>
										<input type="text" name="zip" class="form-control"  maxlength="15">
									</div>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer' ) ) ); ?></label>
										<div class="radio-inline">
											<label><input style="width:auto;" type="radio" name="contact" value="email" checked="checked"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer' ) ) ); ?></label>
										</div>
										<div class="radio-inline">
											<label><input style="width:auto;" type="radio" name="contact" value="phone"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_phone', esc_html__( 'Phone', 'cardealer' ) ) ); ?></label>
										</div>
									</div>
								</div>
								<?php
								if ( function_exists( 'the_privacy_policy_link' ) && isset( $car_dealer_options['req_info_policy_terms'] ) && '1' === (string) $car_dealer_options['req_info_policy_terms'] ) {
									?>
									<div class="col-sm-12 cdhl-terms-privacy-container">
										<label>
											<input type="checkbox" name="cdhl_terms_privacy" class="form-control cdhl_validate terms" />
											<?php
											/**
											 * Filters the request more information lead form privacy text.
											 *
											 * @since 1.0
											 *
											 * @param string       $privacy_text    Privacy text for request more information lead form.
											 * @visible        true
											 */
											echo esc_html(
												apply_filters(
													'cd_inquiry_privacy_text',
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
											<div id="recaptcha1"></div>
										</div>
									</div>
									<?php
								}
								?>
								<div class="col-sm-12">
									<div class="form-group">
										<button id="submit_request" class="button red" ><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_request_info_btn', esc_html__( 'Request a Service', 'cardealer' ) ) ); ?></button>
										<span class="spinimg"></span>
										<div class="inquiry-msg" style="display:none;"></div>
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
