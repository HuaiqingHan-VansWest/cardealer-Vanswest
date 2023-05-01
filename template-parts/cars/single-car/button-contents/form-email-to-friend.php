<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;
?>
<div class="modal fade cardealer-lead-form cardealer-lead-form-email-to-friend" id="<?php echo esc_attr( $args['modal_id'] ); ?>" tabindex="-1" role="dialog" aria-labelledby="email_to_friend_lbl" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h6 class="modal-title" id="email_to_friend_lbl"><?php echo esc_html( $args['modal_title'] ); ?></h6>
			</div>
			<div class="modal-body">
				<?php
				if ( isset( $car_dealer_options['email_friend_form'] ) && ! empty( $car_dealer_options['email_friend_form'] ) && $car_dealer_options['email_friend_contact_7'] ) {

					global $cdhl_email_friend_form, $cdhl_email_friend_form_fields;

					$cdhl_email_friend_form        = true;
					$cdhl_email_friend_form_fields = array(
						'cdhl_email_friend_form'            => 'yes',
						'cdhl_email_friend_form_user_email' => get_the_author_meta( 'email' ),
					);

					echo do_shortcode( $car_dealer_options['email_friend_form'] );

					$cdhl_email_friend_form        = false;
					$cdhl_email_friend_form_fields = array();

				} else {
					?>
					<form name="friend_email_form" class="gray-form" method="post" id="friend-email-form">
						<div class="row">
							<input type="hidden" name="action" value="email_to_friend">
							<input type="hidden" name="car_id" value="<?php echo get_the_ID(); ?>">
							<?php wp_nonce_field( 'email-to-friend-form', 'etf_nonce' ); ?>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="uname"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_friends_name', esc_html__( "Friend's Name", 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="uname" class="form-control cdhl_validatex" id="uname" required maxlength="25"/>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="friends_email"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_friends_email', esc_html__( 'Friend\'s Email', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="friends_email" id="friends_email" class="form-control cdhl_validatex cardealer_mail" required>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="yourname"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_your_name', esc_html__( 'Your Name', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="yourname" class="form-control cdhl_validatex" id="yourname" required maxlength="25"/>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="email"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_your_email', esc_html__( 'Your Email', 'cardealer' ) ) ); ?>*</label>
									<input type="text" name="email" id="email" class="form-control cdhl_validatex cardealer_mail" required>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label for="message"><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_message', esc_html__( 'Message', 'cardealer' ) ) ); ?></label>
									<textarea name="message" class="form-control" id="message" maxlength="300"></textarea>
								</div>
							</div>
							<?php
							$google_captcha_site_key   = cardealer_get_theme_option( 'google_captcha_site_key' );
							$google_captcha_secret_key = cardealer_get_theme_option( 'google_captcha_secret_key' );
							if ( ! empty( $google_captcha_site_key ) && ! empty( $google_captcha_secret_key ) ) {
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<div id="recaptcha4"></div>
									</div>
								</div>
								<?php
							}
							?>
							<div class="col-sm-12">
								<div class="form-group">
									<button id="submit_friend_frm" class="button red" ><?php echo esc_html( cardealer_get_theme_option( 'cstfrm_lbl_send_btn', esc_html__( 'Send', 'cardealer' ) ) ); ?></button>
									<span class="spinimg"></span>
									<div class="friend-frm-msg" style="display: none;"></div>
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
