<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;
?>
		</div> <!-- #main .wrapper  -->

		<!--==== footer -->
		<footer class="footer page-section-pt">
			<div class="footer-widget">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<p class="text-white"><?php cardealer_footer_copyright(); ?></p>
						</div>
						<?php
						if ( '1' === (string) $car_dealer_options['commin_soon_social_icons'] ) {
							?>
							<div class="col-lg-6 col-md-6">
								<?php
								if ( $car_dealer_options['facebook_url'] || $car_dealer_options['twitter_url'] || $car_dealer_options['dribbble_url'] || $car_dealer_options['vimeo_url'] || $car_dealer_options['pinterest_url'] || $car_dealer_options['behance_url'] || $car_dealer_options['linkedin_url'] ) {
									?>
									<div class="footer-widget-social">
										<ul>
											<?php
											if ( $car_dealer_options['facebook_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['facebook_url'] ); ?>" data-tooltip="facebook"><i class="fab fa-facebook-f"></i></a></li>
												<?php
											}

											if ( $car_dealer_options['twitter_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['twitter_url'] ); ?>" data-tooltip="twitter"><i class="fab fa-twitter"></i></a></li>
												<?php
											}

											if ( $car_dealer_options['dribbble_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['dribbble_url'] ); ?>" data-tooltip="dribbble"><i class="fab fa-dribbble"></i> </a></li>
												<?php
											}

											if ( $car_dealer_options['vimeo_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['vimeo_url'] ); ?>" data-tooltip="Vimeo"><i class="fab fa-vimeo-v"></i> </a></li>
												<?php
											}

											if ( $car_dealer_options['pinterest_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['pinterest_url'] ); ?>" data-tooltip="Pinterest"><i class="fab fa-pinterest-p"></i> </a></li>
												<?php
											}

											if ( $car_dealer_options['behance_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['behance_url'] ); ?>" data-tooltip="Behance"><i class="fab fa-behance"></i> </a></li>
												<?php
											}

											if ( $car_dealer_options['linkedin_url'] ) {
												?>
												<li><a href="<?php echo esc_url( $car_dealer_options['linkedin_url'] ); ?>" data-tooltip="Linkedin"><i class="fab fa-linkedin-in"></i> </a></li>
												<?php
											}
											?>
										</ul>
									</div>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</footer><!-- .footer -->

	</div><!-- #page .site -->
	<!-- page-wrapper ends -->

<?php wp_footer(); ?>

</body>
</html>
