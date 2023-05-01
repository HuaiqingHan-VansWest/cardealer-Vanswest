<?php
/**
 * Default page template to display all pages
 *
 * @package CarDealer
 * @author Potenza Global Solutions
 */

get_template_part( 'template-parts/maintenance/header' );

global $car_dealer_options;

$page_content_type = isset( $car_dealer_options['maintenance_page_content_source'] ) ? $car_dealer_options['maintenance_page_content_source'] : 'default';
?>
<section class="coming-soon page-section-ptb mntc-cs-main">
	<div class="container">
	<?php
	if ( 'default' === $page_content_type ) {
		$maintenance_mode = isset( $car_dealer_options['maintenance_mode'] ) ? $car_dealer_options['maintenance_mode'] : 'maintenance';
		if ( 'comingsoon' === $maintenance_mode ) {
			$comingsoon_title    = $car_dealer_options['comingsoon_title'];
			$comingsoon_subtitle = $car_dealer_options['comingsoon_subtitle'];

			$mncd_cs_title    = ( ! empty( $comingsoon_title ) ) ? $comingsoon_title : esc_html__( 'Coming Soon', 'cardealer' );
			$mncd_cs_subtitle = ( ! empty( $comingsoon_subtitle ) ) ? $comingsoon_subtitle : esc_html__( 'We are currently working on a website and won\'t take long. Don\'t forget to check out our Social updates.', 'cardealer' );
		} else {
			$maintenance_title    = $car_dealer_options['maintenance_title'];
			$maintenance_subtitle = $car_dealer_options['maintenance_subtitle'];

			$mncd_cs_title    = ( ! empty( $maintenance_title ) ) ? $maintenance_title : esc_html__( 'Site is Under Maintenance', 'cardealer' );
			$mncd_cs_subtitle = ( ! empty( $maintenance_subtitle ) ) ? $maintenance_subtitle : esc_html__( 'This Site is Currently Under Maintenance. We will be back shortly', 'cardealer' );
		}
		?>
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<?php
				if ( 'comingsoon' === $maintenance_mode ) {
					?>
					<div class="section-title">
						<h2> <?php echo esc_html( $mncd_cs_title ); ?> </h2>
						<div class="separator"></div>
					</div>
					<?php
				} else {
					?>
					<div class="mntc-cs-item mntc-cs-content text-center">
						<i class="fas fa-cog fa-spin fa-3x fa-fw margin-bottom"></i>
						<h1 class="text-blue"><?php echo esc_html( $mncd_cs_title ); ?></h1>
						<p><?php echo esc_html( $mncd_cs_subtitle ); ?></p>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		if ( 'comingsoon' === $maintenance_mode ) {
			$comingsoon_date = $car_dealer_options['comingsoon_date'];
			if ( $comingsoon_date ) {
				$comingsoon_date = str_replace( '-', '/', $comingsoon_date );
				$comingsoon_date = $comingsoon_date . ' 23:59:59';
			}
			?>
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="countdown" data-countdown-date="<?php echo esc_attr( $comingsoon_date ); ?>">
						<p><?php echo esc_html( $mncd_cs_subtitle ); ?></p>
						<ul>
							<li> <span class="days">00</span>
								<p class="days_ref"><?php esc_html__( 'days', 'cardealer' ); ?></p>
							</li>
							<li> <span class="hours">00</span>
								<p class="hours_ref"><?php esc_html__( 'hours', 'cardealer' ); ?></p>
							</li>
							<li> <span class="minutes">00</span>
								<p class="minutes_ref"><?php esc_html__( 'minutes', 'cardealer' ); ?></p>
							</li>
							<li> <span class="seconds">00</span>
								<p class="seconds_ref"><?php esc_html__( 'seconds', 'cardealer' ); ?></p>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="coming-soon-search">
				<div class="container">
					<?php if ( ! empty( $car_dealer_options['comingsoon_back_image']['url'] ) ) { ?>
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<img class="img-responsive center-block" src="<?php echo esc_url( $car_dealer_options['comingsoon_back_image']['url'] ); ?>" alt="<?php echo esc_attr__( 'comingsoon', 'cardealer' ); ?>">
						</div>
					</div>
						<?php
					}
					cardealer_comming_soon_newsletter();
					?>
				</div>
			</div>
			<?php
		}
	} else {
		$maintenance_page_content_page_id = isset( $car_dealer_options['maintenance_page_content_page'] ) ? $car_dealer_options['maintenance_page_content_page'] : '';
		$maintenance_page                 = get_post( $maintenance_page_content_page_id );

		if ( $maintenance_page ) {
			$page_content_type = 'page';
			$post              = $maintenance_page; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}

		setup_postdata( $post, null, false );
		the_content();
		wp_reset_postdata();
	}
	?>
	</div>
</section>
<?php
get_template_part( 'template-parts/maintenance/footer' );
