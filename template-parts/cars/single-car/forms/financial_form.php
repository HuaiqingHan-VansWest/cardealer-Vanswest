<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( isset( $car_dealer_options['financial_form_status'] ) && ! $car_dealer_options['financial_form_status'] ) {
	return;
}
$lead_form = cardealer_get_lead_form( 'financial_form' );
?>
<li>
	<a data-toggle="modal" data-target="#financial_form_mdl" href="#"><i class="<?php echo esc_attr( $lead_form['icon'] ); ?>"></i><?php echo esc_html( $lead_form['btn_label'] ); ?></a>
	<div class="modal fade cardealer-lead-form cardealer-lead-form-financial-form" id="financial_form_mdl" tabindex="-1" role="dialog" aria-labelledby="financial_form_lbl" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h6 class="modal-title" id="financial_form_lbl"><?php echo esc_html( $lead_form['modal_title'] ); ?></h6>
				</div><!-- .modal-header -->
				<div class="modal-body">
					<?php
					if ( isset( $car_dealer_options['financial_form'] ) && ! empty( $car_dealer_options['financial_form'] ) && '1' === (string) $car_dealer_options['financial_form_contact_7'] ) {
						
						global $cdhl_financial_form, $cdhl_financial_form_fields;

						$cdhl_financial_form        = true;
						$cdhl_financial_form_fields = array(
							'cdhl_financial_form'            => 'yes',
							'cdhl_financial_form_user_email' => get_the_author_meta( 'email' ),
						);

						echo do_shortcode( $car_dealer_options['financial_form'] );

						$cdhl_financial_form        = false;
						$cdhl_financial_form_fields = array();
						
					} else {
						?>
						<form name="financial_form" class="gray-form" method="post" id="financial_form">
							<input type="hidden" name="action" class="form-control" value="financial_form_action">
							<?php wp_nonce_field( 'financial_form', 'financial_nonce' ); ?>
							<input type="hidden" name="car_id" value="<?php echo get_the_ID(); ?>">
							<div class="main">
								<div class="container-fluid">
									<div class="row">
										<div class="col-sm-12">
											<div class="finance-form-block clearfix">
												<div class="col-md-4 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="first_name" class="form-control cdhl_validate" id="first_name" maxlength="25"/>
													</div>
												</div>
												<div class="col-md-4 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_middle_initial', esc_html__( 'Middle Initial', 'cardealer' ) ) ); ?></label>
														<input type="text" name="middle_initial" class="form-control" id="middle_initial" maxlength="5" />
													</div>
												</div>
												<div class="col-md-4 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="last_name" class="form-control cdhl_validate" id="last_name" maxlength="25"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_street_address', esc_html__( 'Street Address', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="street_address" class="form-control cdhl_validate" id="street_address" maxlength="250"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-3">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_city', esc_html__( 'City', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="city" class="form-control cdhl_validate" id="city" maxlength="25"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-3">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="state" class="form-control cdhl_validate" maxlength="25">
													</div>
												</div>
												<div class="col-md-3 col-sm-3">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="zip" class="form-control cdhl_validate" id="zip" maxlength="15"/>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_preferred_email_address', esc_html__( 'Preferred Email Address', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="preferred_email_address" class="form-control cdhl_validate cardealer_mail" id="preferred_email_address" />
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_daytime_phone_number', esc_html__( 'Daytime Phone Number', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="daytime_phone_number" class="form-control cdhl_validate" id="daytime_phone_number" maxlength="15"/>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="mobile_phone_number" class="form-control cdhl_validate" id="mobile_phone_number" maxlength="15"/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<br>
								<br>
								<div class="container-fluid">
									<div class="row">
										<div class="col-sm-6">
											<div class="finance-form-block clearfix">
												<div class="row">
													<div class="col-sm-12">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_date_of_birth', esc_html__( 'Date of Birth', 'cardealer' ) ) ); ?>*</label>
															<div class="row">
																<div class="col-sm-4">
																	<div class="selected-box">
																		<select class="selectpicker cdhl_sel_validate cd-select-box" name="date_of_birth_month" id="date_of_birth_month" >
																			<option value=""><?php esc_html_e( 'Month', 'cardealer' ); ?></option>
																			<?php
																			for ( $i = 1; $i <= 12; $i++ ) {
																				?>
																				<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-4">
																	<div class="selected-box">
																		<select class="selectpicker cdhl_sel_validate cd-select-box" name="date_of_birth_day" id="date_of_birth_day" >
																			<option value=""><?php esc_html_e( 'Day', 'cardealer' ); ?></option>
																			<?php
																			for ( $i = 1; $i <= 31; $i++ ) {
																				?>
																				<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-4">
																	<div class="selected-box">
																		<select class="selectpicker cdhl_sel_validate cd-select-box" name="date_of_birth_year" id="date_of_birth_year" >
																		<option value=""><?php esc_html_e( 'Year', 'cardealer' ); ?></option>
																			<?php
																			for ( $i = 1925; $i <= date( 'Y' ); $i++ ) {
																				?>
																				<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_ssn', esc_html__( 'Social Security Number (SSN)', 'cardealer' ) ) ); ?>*</label>
															<input type="text" name="social_security_number" class="form-control cdhl_validate social_security_number" id="social_security_number" maxlength="20"/>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_employer_name', esc_html__( 'Employer Name', 'cardealer' ) ) ); ?>*</label>
															<input type="text" name="employer_name" class="form-control cdhl_validate" id="employer_name" maxlength="25"/>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_employer_phone', esc_html__( 'Employer Phone', 'cardealer' ) ) ); ?>*</label>
															<input type="text" name="employer_phone" class="form-control cdhl_validate" id="employer_phone" maxlength="25" />
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_job_title', esc_html__( 'Job Title', 'cardealer' ) ) ); ?>*</label>
															<input type="text" name="job_title" class="form-control cdhl_validate" id="job_title" maxlength="25"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="finance-form-block clearfix">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_living_arrangements', esc_html__( 'Living Arrangements', 'cardealer' ) ) ); ?>*</label>
															<div class="selected-box">
																<select class="selectpicker cdhl_sel_validate cd-select-box" name="living_arrangements" id="living_arrangements" >
																	<option value=""><?php esc_html_e( 'Select One', 'cardealer' ); ?></option>
																	<option value="rent"><?php esc_html_e( 'Rent', 'cardealer' ); ?></option>
																	<option value="own"><?php esc_html_e( 'Own', 'cardealer' ); ?></option>
																	<option value="live_with_parents"><?php esc_html_e( 'Live With Parents', 'cardealer' ); ?></option>
																</select>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_monthly_rent_mortgage_payment', esc_html__( 'Monthly Rent/Mortgage Payment', 'cardealer' ) ) ); ?>*</label>
															<input type="text" name="monthly_rent" class="form-control cdhl_validate" id="monthly_rent" />
														</div>
													</div>
													<div class="col-sm-12">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_length_of_time_at_current_address', esc_html__( 'Length of Time at Current Address', 'cardealer' ) ) ); ?>*</label>
															<div class="row">
																<div class="col-sm-6">
																	<div class="selected-box">
																		<select class="selectpicker cdhl_sel_validate cd-select-box" name="length_of_time_at_current_add_year" id="length_of_time_at_current_add_year" >
																			<option value=""><?php esc_html_e( 'Year(s)', 'cardealer' ); ?></option>
																			<?php
																			for ( $i = 1; $i <= 30; $i++ ) {
																				$length_of_time_at_current_add_year_value = sprintf(
																					/* translators: %s: Number of years */
																					_n( '%s Year', '%s Years', $i, 'cardealer' ),
																					esc_html( $i )
																				);
																				$length_of_time_at_current_add_year_label = sprintf(
																					/* translators: %s: Number of years */
																					_n( '%s Year', '%s Years', $i, 'cardealer' ),
																					esc_html( $i )
																				);
																				?>
																				<option value="<?php echo esc_attr( $length_of_time_at_current_add_year_value ); ?>"><?php echo esc_html( $length_of_time_at_current_add_year_label ); ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-6">
																	<div class="selected-box">
																		<select class="selectpicker  cdhl_sel_validate cd-select-box" name="length_of_time_at_current_add_month" id="length_of_time_at_current_add_month" >
																			<option value=""><?php esc_html_e( 'Month(s)', 'cardealer' ); ?></option>
																			<?php
																			for ( $i = 1; $i <= 12; $i++ ) {
																				$length_of_time_at_current_add_month_value = sprintf(
																					/* translators: %s: Number of months */
																					_n( '%s Month', '%s Months', $i, 'cardealer' ),
																					esc_html( $i )
																				);
																				$length_of_time_at_current_add_month_label = sprintf(
																					/* translators: %s: Number of months */
																					_n( '%s Month', '%s Months', $i, 'cardealer' ),
																					esc_html( $i )
																				);
																				?>
																				<option value="<?php echo esc_attr( $length_of_time_at_current_add_month_value ); ?>"><?php echo esc_html( $length_of_time_at_current_add_month_label ); ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-8">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_length_of_employment', esc_html__( 'Length of Employment', 'cardealer' ) ) ); ?>*</label>
															<div class="row">
																<div class="col-sm-6">
																	<div class="selected-box">
																		<select class="selectpicker cdhl_sel_validate cd-select-box" name="length_of_employment_year" id="length_of_employment_year" >
																			<option value=""><?php esc_html_e( 'Year(s)', 'cardealer' ); ?></option>
																			<?php
																			for ( $i = 1; $i <= 30; $i++ ) {
																				$length_of_employment_year_value = sprintf(
																					/* translators: %s: Number of years */
																					_n( '%s Year', '%s Years', $i, 'cardealer' ),
																					esc_html( $i )
																				);
																				$length_of_employment_year_label = sprintf(
																					/* translators: %s: Number of years */
																					_n( '%s Year', '%s Years', $i, 'cardealer' ),
																					esc_html( $i )
																				);
																				?>
																				<option value="<?php echo esc_attr( $length_of_employment_year_value ); ?>"><?php echo esc_html( $length_of_employment_year_label ); ?></option>
																				<?php
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-6">
																	<div class="selected-box">
																		<select class="selectpicker cdhl_sel_validate cd-select-box" name="length_of_employment_month" id="length_of_employment_month" >
																		<option value=""><?php esc_html_e( 'Month(s)', 'cardealer' ); ?></option>
																		<?php
																		for ( $i = 1; $i <= 12; $i++ ) {
																			$length_of_employment_month_value = sprintf(
																				/* translators: %s: Number of months */
																				_n( '%s Month', '%s Months', $i, 'cardealer' ),
																				esc_html( $i )
																			);
																			$length_of_employment_month_label = sprintf(
																				/* translators: %s: Number of months */
																				_n( '%s Month', '%s Months', $i, 'cardealer' ),
																				esc_html( $i )
																			);
																			?>
																			<option value="<?php echo esc_attr( $length_of_employment_month_value ); ?>"><?php echo esc_html( $length_of_employment_month_label ); ?></option>
																			<?php
																		}
																		?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_annual_income', esc_html__( 'Annual Income', 'cardealer' ) ) ); ?>*</label>
															<input type="text" name="annual_income" class="form-control cdhl_validate" id="annual_income" maxlength="15"/>
														</div>
													</div>
													<div class="col-sm-12">
														<div class="form-group">
															<label><input type="checkbox" name="joint_application" class="join-inout" id="joint_application"/><?php esc_html_e( 'Joint Application', 'cardealer' ); ?></label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<br>
							<br>
							<div class="container-fluid">
								<div class="row">
									<div class="col-sm-12">
										<div class="finance-form-block clearfix">
											<br>
											<div class="col-sm-12 form-title text-center">
												<h4><?php esc_html_e( 'Applicant Additional Income Information', 'cardealer' ); ?></h4>
												<p><?php esc_html_e( 'Complete "Other Income" and "Source of Other Income" only if you want this income considered for repayment. Enter monthly amount. Alimony, child support or separate maintenance income need not be revealed if you do not wish to have it considered as a basis for repaying this obligation.', 'cardealer' ); ?></p>
											</div>
											<br>
											<div class="col-sm-6">
												<div class="form-group">
													<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_other_income_amount_monthly', esc_html__( 'Other Income Amount (Monthly)', 'cardealer' ) ) ); ?></label>
													<input type="text" name="other_income_ammount_monthly" class="form-control" id="other_income_ammount_monthly"/>
												</div>
												<div class="form-group">
													<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_other_income_source', esc_html__( 'Other Income (Source)', 'cardealer' ) ) ); ?></label>
													<input type="text" name="other_income_source" class="form-control" id="other_income_source"/>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_include_additional_info', esc_html__( 'Please include any additional information or options that you would like', 'cardealer' ) ) ); ?></label>
													<textarea class="form-control" rows="5" name="additional_information" id="additional_information"></textarea>
												</div>
											</div>
											<div id="personal_application">
												<div class="col-sm-12">
													<br>
													<br>
													<h4 class="text-center"><?php esc_html_e( 'Joint Applicant Personal Information', 'cardealer' ); ?></h4>
													<br>
												</div>
												<div class="col-md-3 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="first_name_joint" class="form-control cdhl_validate_joint" id="first_name_joint" maxlength="25"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_middle_initial', esc_html__( 'Middle Initial', 'cardealer' ) ) ); ?></label>
														<input type="text" name="middle_initial_joint" class="form-control" id="middle_initial_joint" maxlength="5"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="last_name_joint" class="form-control cdhl_validate_joint" id="last_name_joint" maxlength="25"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-6">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_relationship_to_applicant', esc_html__( 'Relationship to Applicant', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="relationship_to_applicant" class="form-control cdhl_validate_joint" id="relationship_to_applicant"  />
													</div>
												</div>
												<div class="col-md-3 col-sm-6">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_street_address', esc_html__( 'Street Address', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="street_address_joint" class="form-control cdhl_validate_joint" id="street_address_joint" maxlength="250"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_city', esc_html__( 'City', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="city_joint" class="form-control cdhl_validate_joint" id="city_joint" maxlength="25"/>
													</div>
												</div>
												<div class="col-md-3 col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="state_joint" class="form-control cdhl_validate_joint" maxlength="25">
													</div>
												</div>
												<div class="col-md-3 col-sm-3">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="zip_joint" class="form-control cdhl_validate_joint" id="zip_joint" maxlength="15"/>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_preferred_email_address', esc_html__( 'Preferred Email Address', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="preferred_email_address_joint" class="form-control cdhl_validate_joint cardealer_mail" id="preferred_email_address_joint" />
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_daytime_phone_number', esc_html__( 'Daytime Phone Number', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="daytime_phone_number_joint" class="form-control cdhl_validate_joint" id="daytime_phone_number_joint" maxlength="15"/>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer' ) ) ); ?>*</label>
														<input type="text" name="mobile_phone_number_joint" class="form-control cdhl_validate_joint" id="mobile_phone_number_joint" maxlength="15"/>
													</div>
												</div>
												<div class="container-fluid">
													<div class="row">
														<div class="col-sm-6">
															<div class="row">
																<div class="finance-form-block clearfix">
																	<div class="col-sm-12">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_date_of_birth', esc_html__( 'Date of Birth', 'cardealer' ) ) ); ?>*</label>
																			<div class="row">
																				<div class="col-sm-4">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="date_of_birth_month_joint" id="date_of_birth_month_joint" >
																							<option value=""><?php esc_html_e( 'Month', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1; $i <= 12; $i++ ) {
																								?>
																								<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-sm-4">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="date_of_birth_day_joint" id="date_of_birth_day_joint" >
																							<option value=""><?php esc_html_e( 'Day', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1; $i <= 31; $i++ ) {
																								?>
																								<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-sm-4">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="date_of_birth_year_joint" id="date_of_birth_year_joint" >
																							<option value=""><?php esc_html_e( 'Year', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1925; $i <= date( 'Y' ); $i++ ) {
																								?>
																								<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_ssn', esc_html__( 'Social Security Number (SSN)', 'cardealer' ) ) ); ?>*</label>
																			<input type="text" name="social_security_number_joint" class="form-control cdhl_validate_joint social_security_number" id="social_security_number_joint" maxlength="20"/>
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_employer_name', esc_html__( 'Employer Name', 'cardealer' ) ) ); ?>*</label>
																			<input type="text" name="employer_name_joint" class="form-control cdhl_validate_joint" id="employer_name_joint" />
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_employer_phone', esc_html__( 'Employer Phone', 'cardealer' ) ) ); ?>*</label>
																			<input type="text" name="employer_phone_joint" class="form-control cdhl_validate_joint" id="employer_phone_joint" />
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_job_title', esc_html__( 'Job Title', 'cardealer' ) ) ); ?>*</label>
																			<input type="text" name="job_title_joint" class="form-control cdhl_validate_joint" id="job_title_joint" maxlength="25"/>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="row">
																<div class="finance-form-block clearfix">
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_living_arrangements', esc_html__( 'Living Arrangements', 'cardealer' ) ) ); ?>*</label>
																			<div class="selected-box">
																				<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="living_arrangements_joint" id="living_arrangements_joint" >
																					<option value=""><?php esc_html_e( 'Select One', 'cardealer' ); ?></option>
																					<option value="rent"><?php esc_html_e( 'Rent', 'cardealer' ); ?></option>
																					<option value="own"><?php esc_html_e( 'Own', 'cardealer' ); ?></option>
																					<option value="live_with_parents"><?php esc_html_e( 'Live With Parents', 'cardealer' ); ?></option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_monthly_rent_mortgage_payment', esc_html__( 'Monthly Rent/Mortgage Payment', 'cardealer' ) ) ); ?>*</label>
																			<input type="text" name="monthly_rent_joint" class="form-control cdhl_validate_joint" id="monthly_rent_joint" />
																		</div>
																	</div>
																	<div class="col-sm-12">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_length_of_time_at_current_address', esc_html__( 'Length of Time at Current Address', 'cardealer' ) ) ); ?>*</label>
																			<div class="row">
																				<div class="col-sm-6">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="length_of_time_at_current_add_year_joint" id="length_of_time_at_current_add_year_joint" >
																							<option value=""><?php esc_html_e( 'Year(s)', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1; $i <= 30; $i++ ) {
																								$length_of_time_at_current_add_year_joint_value = sprintf(
																									/* translators: %s: Number of years */
																									_n( '%s Year', '%s Years', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								$length_of_time_at_current_add_year_joint_label = sprintf(
																									/* translators: %s: Number of years */
																									_n( '%s Year', '%s Years', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								?>
																								<option value="<?php echo esc_attr( $length_of_time_at_current_add_year_joint_value ); ?>"><?php echo esc_html( $length_of_time_at_current_add_year_joint_label ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-sm-6">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="length_of_time_at_current_add_month_joint" id="length_of_time_at_current_add_month_joint" >
																							<option value=""><?php esc_html_e( 'Month(s)', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1; $i <= 12; $i++ ) {
																								$length_of_time_at_current_add_month_joint_value = sprintf(
																									/* translators: %s: Number of months */
																									_n( '%s Month', '%s Months', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								$length_of_time_at_current_add_month_joint_label = sprintf(
																									/* translators: %s: Number of months */
																									_n( '%s Month', '%s Months', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								?>
																								<option value="<?php echo esc_attr( $length_of_time_at_current_add_month_joint_value ); ?>"><?php echo esc_html( $length_of_time_at_current_add_month_joint_label ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-sm-8">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_length_of_employment', esc_html__( 'Length of Employment', 'cardealer' ) ) ); ?>*</label>
																			<div class="row">
																				<div class="col-sm-6">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="length_of_employment_year_joint" id="length_of_employment_year_joint" >
																							<option value=""><?php esc_html_e( 'Year(s)', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1; $i <= 30; $i++ ) {
																								$length_of_employment_year_joint_value = sprintf(
																									/* translators: %s: Number of years */
																									_n( '%s Year', '%s Years', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								$length_of_employment_year_joint_label = sprintf(
																									/* translators: %s: Number of years */
																									_n( '%s Year', '%s Years', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								?>
																								<option value="<?php echo esc_attr( $length_of_employment_year_joint_value ); ?>"><?php echo esc_html( $length_of_employment_year_joint_label ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-sm-6">
																					<div class="selected-box">
																						<select class="selectpicker cdhl_sel_validate_joint cd-select-box" name="length_of_employment_month_joint" id="length_of_employment_month_joint" >
																							<option value=""><?php esc_html_e( 'Month(s)', 'cardealer' ); ?></option>
																							<?php
																							for ( $i = 1; $i <= 12; $i++ ) {
																								$length_of_employment_month_joint_value = sprintf(
																									/* translators: %s: Number of months */
																									_n( '%s Month', '%s Months', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								$length_of_employment_month_joint_label = sprintf(
																									/* translators: %s: Number of months */
																									_n( '%s Month', '%s Months', $i, 'cardealer' ),
																									esc_html( $i )
																								);
																								?>
																								<option value="<?php echo esc_attr( $length_of_employment_month_joint_value ); ?>"><?php echo esc_html( $length_of_employment_month_joint_label ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-sm-4">
																		<div class="form-group">
																			<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_annual_income', esc_html__( 'Annual Income', 'cardealer' ) ) ); ?>*</label>
																			<input type="text" name="annual_income_joint" class="form-control cdhl_validate_joint" id="annual_income_joint" />
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-sm-12 form-title text-center">
													<br><br><h4><?php esc_html_e( 'Joint Applicant Additional Income Information', 'cardealer' ); ?></h4><br>
													<p><?php esc_html_e( 'Complete "Other Income" and "Source of Other Income" only if you want this income considered for repayment. Enter monthly amount. Alimony, child support or separate maintenance income need not be revealed if you do not wish to have it considered as a basis for repaying this obligation.', 'cardealer' ); ?></p>
												</div>
												<br />
												<div class="col-sm-6">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_other_income_amount_monthly', esc_html__( 'Other Income Amount (Monthly)', 'cardealer' ) ) ); ?></label>
														<input type="text" name="other_income_ammount_monthly_joint" class="form-control" id="other_income_ammount_monthly_joint"/>
													</div>
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_other_income_source', esc_html__( 'Other Income (Source)', 'cardealer' ) ) ); ?></label>
														<input type="text" name="other_income_source_joint" class="form-control" id="other_income_source_joint" maxlength="25"/>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_include_additional_info', esc_html__( 'Please include any additional information or options that you would like', 'cardealer' ) ) ); ?></label>
														<textarea class="form-control" rows="5" name="additional_information_joint" id="additional_information_joint"></textarea>
													</div>
												</div>
											</div>
											<div class="col-sm-8 col-sm-offset-2 text-center">
												<p>
												<?php
												echo sprintf(
													/* translators: $s: label */
													esc_html__( 'By clicking on "%s", I acknowledge and declare that I have read and agree with the Application Disclosure above. I certify that I have provided complete and true information in this application.', 'cardealer' ),
													esc_html( cardealer_get_theme_option( 'cstfrm_lbl_submit_credit_app_btn', esc_html__( 'Submit Credit Application', 'cardealer' ) ) )
												);
												?>
												</p>
											</div>
											<br />
											<?php
											if ( function_exists( 'the_privacy_policy_link' ) && isset( $car_dealer_options['financial_form_policy_terms'] ) && '1' === (string) $car_dealer_options['financial_form_policy_terms'] ) {
												?>
												<div class="col-sm-12 cdhl-terms-privacy-container">
													<label>
														<input type="checkbox" name="cdhl_terms_privacy" class="form-control cdhl_validate terms" />
														<?php
														echo esc_html(
															apply_filters(
																'cd_financial_inquiry_privacy_text',
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
												<div class="col-sm-12 text-center">
													<div class="form-group">
														<div id="recaptcha5"></div>
													</div>
												</div>
												<?php
											}
											?>
											<div class="col-sm-12 form-group">
												<br>
												<button id="financial_form_request" name="submit" type="submit" value="Send" class="button red"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_submit_credit_app_btn', esc_html__( 'Submit Credit Application', 'cardealer' ) ) ); ?></button>
												<span class="financial_form_spining"></span>
												<p class="financial_form_msg" style="display:none;"></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
						<?php
					}
					?>
				</div><!-- .modal-body -->
			</div><!-- .modal-content -->
		</div><!-- .modal-dialog -->
	</div><!-- .modal -->
</li>
