<?php
/**
 * Do not allow directly accessing this file.
 *
 * @package Cardealer
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

?>
<div class="wrap cardealer-admin-theme-page cardealer-admin-wrap cardealer-system-status cardealer-admin-status-screen">
	<?php cardealer_get_cardealer_tabs( 'more-features' ); ?>
	<div class="cardealer-panel-tab-content">
		<div class="cardealer-more-features-wrap">
			<div class="cardealer-more-features">
				<div class="cardealer-more-feature cardealer-more-feature-pricing-packages">
					<h2 class="cardealer-more-feature-heading"><?php esc_html_e( 'Frontend Submission - Pricing Packages Plan (with Subscriptio Plugin)', 'cardealer' ) ?></h2>
					<div class="cardealer-more-feature-content">
						<p><?php echo wp_kses( __( 'The <strong>Pricing Packages Plan</strong> feature allows frontend users (registered with the site) to purchase plans to upload vehicles and images more than the permitted limit.', 'cardealer' ), cardealer_allowed_html( 'strong' ) ); ?></p>
						<p><?php echo wp_kses( __( 'By default, the <strong>Cardealer - Frontend Submission</strong> plugin allows dealers to upload cars and images up to a specific limit (which site admin can manage in theme options). To upload more vehicles and images, frontend users need to purchase a package from the Pricing Packages Plan.', 'cardealer' ), cardealer_allowed_html( 'strong' ) ); ?></p>
						<p><?php echo wp_kses( __( 'To manage plans, you need to install <strong>Subscriptio</strong> and <strong>WooCommerce</strong> plugin. Subscriptio plugin is a WooCommerce extension that allows you to manage plans/packages.', 'cardealer' ), cardealer_allowed_html( 'strong' ) ); ?></p>
						<p><?php echo esc_html__( 'If you want to use this feature then please:', 'cardealer' ); ?></p>
						<?php $is_subscriptio_enabled = get_option( 'cardealer_tgmpa_is_pricing_packages_enabled' ); ?>
						<form method="post">
							<?php wp_nonce_field( 'cardealer_more_feature_pricing_packages_action', 'cardealer_more_feature_pricing_packages_nonce_field' ); ?>
							<label for="cardealer_more_feature_pricing_packages"><input type="checkbox" id="cardealer_more_feature_pricing_packages" name="cardealer_more_feature_pricing_packages" value="1" <?php checked( $is_subscriptio_enabled, 1 ); ?>><?php echo esc_html__( 'Enable Subscriptio and WooCommerce plugins in the theme plugin installer', 'cardealer' ); ?></label>
							<?php /* translators: %1$s: url */ ?>
							<p>
							<?php
							echo wp_kses(
								sprintf(
									/* translators: %1$s: dcoument url */
									__( 'Then, go to <strong>Appearance > Install Plugins</strong>, to install <strong>Subscriptio</strong> and <strong>WooCommerce</strong> plugins. After installing Subscriptio and WooCommerce, refer to this <a href="%1$s" target="_blank">documentation</a> for information about manage packages.', 'cardealer' ),
									esc_url( 'https://docs.potenzaglobalsolutions.com/docs/cardealer/?section=pricing-packages-plan' )
								),
								cardealer_allowed_html( 'strong,a' )
							);
							?>
							</p>
							<p class="submit"><input type="submit" name="cardealer_more_feature_pricing_packages_submit" id="submit" class="button button-primary" value="Save Changes"></p>
							<input type="hidden" name="" value="" />
						</form>
					</div>
				</div>
				<?php
				$cardealer_other_fetures = apply_filters( 'cardealer_other_fetures', array() );
				$cardealer_other_fetures = array_filter( $cardealer_other_fetures, function( $v, $k ) {
					return ( isset( $v['link'] ) && ! empty( $v['link'] ) ) && ( isset( $v['title'] ) && ! empty( $v['title'] ) );
				}, ARRAY_FILTER_USE_BOTH );

				if ( is_array( $cardealer_other_fetures ) && ! empty( $cardealer_other_fetures ) ) {
					?>
					<div class="cardealer-more-feature cardealer-more-feature-other-features">
						<h2 class="cardealer-more-feature-heading"><?php esc_html_e( 'Other Features', 'cardealer' ) ?></h2>
						<div class="cardealer-more-feature-content">
							<table class="cardealer-other-fetures">
								<?php
								foreach ( $cardealer_other_fetures as $feature_k => $feature_v ) {
									if ( ( isset( $feature_v['link'] ) && ! empty( $feature_v['link'] ) ) && ( isset( $feature_v['title'] ) && ! empty( $feature_v['title'] ) ) ) {
										?>
										<tr class="cardealer-other-feature cardealer-other-feature-<?php echo esc_attr( $feature_k ); ?>">
											<td class="cardealer-other-feature-btn"><a class="cardealer-other-feature-link cardealer-other-feature-link-<?php echo esc_attr( $feature_k ); ?> button button-primary button-large" href="<?php echo esc_url( $feature_v['link'] ); ?>"><?php echo esc_html( $feature_v['title'] ); ?></a></td>
											<td class="cardealer-other-feature-desc">
												<?php
												if ( isset( $feature_v['desc'] ) && ! empty( $feature_v['desc'] ) ) {
													echo esc_html( $feature_v['desc'] );
												}
												?>
											</td>
										</tr>
										<?php
									}
								}
								?>
							</table>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
