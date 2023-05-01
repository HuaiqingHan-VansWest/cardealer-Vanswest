<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;
do_action( 'cardealer_before_footer' );

$footer_class   = array();
$footer_class[] = 'footer bg-2 bg-overlay-black-90';
$footer_class   = array_merge( $footer_class, cardealer_footer_class() );   /* get the footer classes from base functions */
$footer_class   = implode( ' ', $footer_class );
?>
<footer id="footer" class="<?php echo esc_attr( $footer_class ); ?>">
	<?php do_action( 'cardealer_before_footer_inner' ); ?>
	<div class="container">
	<?php
	if ( isset( $car_dealer_options['show_footer_top'] ) && 'yes' === $car_dealer_options['show_footer_top'] ) {
		$footer_layout   = isset( $car_dealer_options['footer_top_layout'] ) ? $car_dealer_options['footer_top_layout'] : 'layout_1';
		$social_profiles = ( function_exists( 'cdhl_get_social_profiles_legacy' ) ) ? cdhl_get_social_profiles_legacy( array( 'location' => 'footer' ) ) : array();
		?>
		<div class="cd-social-footer social-full <?php echo esc_attr( $footer_layout ); ?>">
			<?php
			if ( 'layout_1' === $footer_layout && ! empty( $social_profiles ) ) {
				get_template_part( 'template-parts/footer/social-profiles-legacy-layout-1', null, array( 'social_profiles' => $social_profiles ) );
			} elseif ( 'layout_2' === $footer_layout ) {
				$logo_url = '';
				if ( isset( $car_dealer_options['footer_top_logo']['url'] ) && ! empty( $car_dealer_options['footer_top_logo']['url'] ) ) {
					$logo_url = $car_dealer_options['footer_top_logo']['url'];
				}
				?>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<?php
					if ( ! empty( $logo_url ) ) {
						?>
					<div class="cd-footer-logo footer-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img class="site-logo" src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"/>
						</a>
					</div>
						<?php
					}
					?>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 text-right">
					<?php get_template_part( 'template-parts/footer/social-profiles-legacy-layout-2', null, array( 'social_profiles' => $social_profiles ) ); ?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	if ( is_active_sidebar( 'sidebar-footer-1' ) || is_active_sidebar( 'sidebar-footer-2' ) || is_active_sidebar( 'sidebar-footer-3' ) || is_active_sidebar( 'sidebar-footer-4' ) ) {
		$footer_cols = 1;
		$classes     = array( 'col-lg-12 col-md-12 col-sm-12' );
		if ( isset( $car_dealer_options['footer_column_layout'] ) ) {
			switch ( $car_dealer_options['footer_column_layout'] ) {
				case 'two-columns':
					$footer_cols = 2;
					$classes     = array( 'col-lg-6 col-md-6 col-sm-6' );
					break;
				case 'three-columns':
					$footer_cols = 3;
					$classes     = array( 'col-lg-4 col-md-4 col-sm-4' );
					break;
				case 'four-columns':
					$footer_cols = 4;
					$classes     = array( 'col-lg-3 col-md-3 col-sm-6' );
					break;
				case '8-4-columns':
					$footer_cols = 2;
					$classes     = array( 'col-lg-8 col-md-8 col-sm-7', 'col-lg-4 col-md-4 col-sm-5' );
					break;
				case '4-8-columns':
					$footer_cols = 2;
					$classes     = array( 'col-lg-4 col-md-4 col-sm-5', 'col-lg-8 col-md-8 col-sm-7' );
					break;
				case '6-3-3-columns':
					$footer_cols = 3;
					$classes     = array( 'col-lg-6 col-md-6 col-sm-6', 'col-lg-3 col-md-3 col-sm-3', 'col-lg-3 col-md-3 col-sm-3' );
					break;
				case '3-3-6-columns':
					$footer_cols = 3;
					$classes     = array( 'col-lg-3 col-md-3 col-sm-3', 'col-lg-3 col-md-3 col-sm-3', 'col-lg-6 col-md-6 col-sm-6' );
					break;
				case '8-2-2-columns':
					$footer_cols = 3;
					$classes     = array( 'col-lg-8 col-md-8 col-sm-8', 'col-lg-2 col-md-2 col-sm-2', 'col-lg-2 col-md-2 col-sm-2' );
					break;
				case '2-2-8-columns':
					$footer_cols = 3;
					$classes     = array( 'col-lg-2 col-md-2 col-sm-2', 'col-lg-2 col-md-2 col-sm-2', 'col-lg-8 col-md-8 col-sm-8' );
					break;
				case '6-2-2-2-columns':
					$footer_cols = 4;
					$classes     = array( 'col-lg-6 col-md-6 col-sm-6', 'col-lg-2 col-md-2 col-sm-2', 'col-lg-2 col-md-2 col-sm-2', 'col-lg-2 col-md-2 col-sm-2' );
					break;
				case '2-2-2-6-columns':
					$footer_cols = 4;
					$classes     = array( 'col-lg-2 col-md-2 col-sm-2', 'col-lg-2 col-md-2 col-sm-2', 'col-lg-2 col-md-2 col-sm-2', 'col-lg-6 col-md-6 col-sm-6' );
					break;
			}
		}
		for ( $col = 1; $col <= $footer_cols; $col++ ) {
			if ( 1 === $col ) { // open parent div.
				echo '<div class=row>';
			}

			if ( is_active_sidebar( 'sidebar-footer-' . $col ) ) {
				if ( ! isset( $classes[ $col - 1 ] ) ) {
					$classes[ $col - 1 ] = $classes[0];
				}
				?>
				<div class="<?php echo esc_attr( $classes[ $col - 1 ] ); ?>">
					<?php
					dynamic_sidebar( 'sidebar-footer-' . $col );
					?>
				</div>
				<?php
			}

			if ( $col === $footer_cols ) { // close parent div.
				echo '</div>';
			}
		}
	}

	get_template_part( 'template-parts/footer/additionalsidebar' );    // show bottom copyright.

	do_action( 'cardealer_after_footer_inner' );
	?>
	</div>
	<!-- BOOTOM COPYRIGHT SECTION START -->
	<?php if ( isset( $car_dealer_options['enable_copyright_footer'] ) && 'yes' === $car_dealer_options['enable_copyright_footer'] ) { ?>
	<div class="copyright-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6 pull-left">
					<?php
					if ( isset( $car_dealer_options['footer_text_left'] ) ) {
						echo do_shortcode( $car_dealer_options['footer_text_left'] );
					}
					?>
				</div>
				<div class="col-lg-6 col-md-6 pull-right">
					<?php
					if ( isset( $car_dealer_options['footer_text_right'] ) ) {
						echo do_shortcode( $car_dealer_options['footer_text_right'] );
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
	<!-- BOOTOM COPYRIGHT SECTION END -->
</footer>
<?php do_action( 'cardealer_after_footer' ); ?>
