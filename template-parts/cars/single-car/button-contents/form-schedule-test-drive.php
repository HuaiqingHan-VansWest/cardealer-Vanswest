<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template file for Schedule Test Drive Form
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$date_format = ( isset( $car_dealer_options['std_date_format'] ) && ! empty( $car_dealer_options['std_date_format'] ) ) ? $car_dealer_options['std_date_format'] : get_option( 'date_format', 'Y-m-d' );
?>
<div class="modal fade cardealer-lead-form cardealer-lead-form-shedule-test-drive" id="<?php echo esc_attr( $args['modal_id'] ); ?>" tabindex="-1" role="dialog" aria-labelledby="shedule_test_drive_lbl" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h6 class="modal-title" id="shedule_test_drive_lbl"><?php echo esc_html( $args['modal_title'] ); ?></h6>
			</div>
			<div class="modal-body">
				<?php
				if ( isset( $car_dealer_options['schedule_drive_form'] ) && ! empty( $car_dealer_options['schedule_drive_form'] ) && '1' === (string) $car_dealer_options['schedule_drive_contact_7'] ) {

					global $cdhl_schedule_drive_form, $cdhl_schedule_drive_form_fields;

					$cdhl_schedule_drive_form        = true;
					$cdhl_schedule_drive_form_fields = array(
						'cdhl_schedule_drive_form'            => 'yes',
						'cdhl_schedule_drive_form_user_email' => get_the_author_meta( 'email' ),
					);

					echo do_shortcode( $car_dealer_options['schedule_drive_form'] );

					$cdhl_schedule_drive_form        = false;
					$cdhl_schedule_drive_form_fields = array();

				} else {
					?>
					<form class="gray-form" method="post" id="schedule_test_form">
						<div class="row">
							<input type="hidden" name="action" class="form-control" value="shedule_test_drive_action">
							<?php wp_nonce_field( 'shedule_test_drive_nonce', 'shedule_test_drive_nonce' ); ?>
							<input type="hidden" name="car_id" value="<?php echo get_the_ID(); ?>">
							<div class="col-sm-6">
								<div class="form-group">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="first_name" class="form-control cdhl_validate" maxlength="25">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="last_name" class="form-control cdhl_validate" maxlength="25">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="email" class="form-control cdhl_validate cardealer_mail" >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="mobile" class="form-control cdhl_validate" maxlength="15">
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
									<input type="text" name="zip" class="form-control" maxlength="15">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_preferred_contact', esc_html__( 'Preferred Contact', 'cardealer' ) ) ); ?></label>
									<div class="radio-inline">
										<label><input style="width:auto" class="check" type="radio" name="preferred_contact" value="email"  checked><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_email', esc_html__( 'Email', 'cardealer' ) ) ); ?></label>
									</div>
									<div class="radio-inline">
										<label><input style="width:auto" type="radio" name="preferred_contact" value="phone" ><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_phone', esc_html__( 'Phone', 'cardealer' ) ) ); ?></label>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_test_drive', esc_html__( 'Test Drive?', 'cardealer' ) ) ); ?></label>
									<div class="radio-inline">
										<label><input style="width:auto" type="radio" class="test_drive check" name="test_drive" value="yes"  checked><?php echo esc_html__( 'Yes', 'cardealer' ); ?></label>
									</div>
									<div class="radio-inline">
										<label><input style="width:auto" type="radio" class="test_drive" name="test_drive" value="no" ><?php echo esc_html__( 'No', 'cardealer' ); ?></label>
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group show_test_drive">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_date', esc_html__( 'Date', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="date" class="form-control date date-time cdhl_validate pgs-input-field-type-date" data-show_datepicker="yes" data-date_format="<?php echo esc_attr( $date_format ); ?>">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group show_test_drive">
									<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_time', esc_html__( 'Time', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="time" class="form-control time date-time cdhl_validate"  >
								</div>
							</div>
							<?php
							if ( function_exists( 'the_privacy_policy_link' ) && isset( $car_dealer_options['schedule_drive_policy_terms'] ) && '1' === (string) $car_dealer_options['schedule_drive_policy_terms'] ) {
								?>
								<div class="col-sm-12 cdhl-terms-privacy-container">
									<label>
										<input type="checkbox" name="cdhl_terms_privacy" class="form-control cdhl_validate terms" />
										<?php
										echo esc_html(
											apply_filters(
												'cd_std_privacy_text',
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
										<div id="recaptcha3"></div>
									</div>
								</div>
								<?php
							}
							?>
							<div class="col-sm-12">
								<div class="form-group">
									<button id="schedule_test_request" class="button red" ><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_request_testdrive_btn', esc_html__( 'Request Test Drive', 'cardealer' ) ) ); ?></button>
									<span class="schedule_test_spinimg"></span>
									<p class="schedule_test_msg" style="display:none;"></p>
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
