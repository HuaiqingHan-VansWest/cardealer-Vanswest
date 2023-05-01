<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$team_per_page = 10;

if ( isset( $car_dealer_options['team_members_per_page'] ) && ! empty( $car_dealer_options['team_members_per_page'] ) ) {
	$team_per_page = $car_dealer_options['team_members_per_page'];
}

$team_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args       = array(
	'post_type'      => 'teams',
	'posts_per_page' => $team_per_page,
	'paged'          => $team_paged,
	'post_status'    => 'publish',
);

$teams_query = new WP_Query( $args );

if ( $teams_query->have_posts() ) {
	while ( $teams_query->have_posts() ) {
		$teams_query->the_post();
		$facebook  = get_post_meta( get_the_ID(), 'facebook', true );
		$twitter   = get_post_meta( get_the_ID(), 'twitter', true );
		$pinterest = get_post_meta( get_the_ID(), 'pinterest', true );
		$behance   = get_post_meta( get_the_ID(), 'behance', true );
		$dribbble  = get_post_meta( get_the_ID(), 'dribbble', true );
		$vimeo     = get_post_meta( get_the_ID(), 'vimeo', true );
		$linkedin  = get_post_meta( get_the_ID(), 'linkedin', true );
		?>
		<div class="col-lg-3 col-md-3 col-sm-6">
			<div class="team text-center">
				<div class="team-image">
					<?php
					if ( has_post_thumbnail() ) {
						$img_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), 'cardealer-team-thumb' );
						if ( cardealer_lazyload_enabled() ) {
							echo '<img src="' . esc_url( LAZYLOAD_IMG ) . '" data-src="' . esc_url( $img_url ) . '" class="img-responsive icon cardealer-lazy-load"/>';
						} else {
							echo '<img src="' . esc_url( $img_url ) . '" class="img-responsive icon"/>';
						}
					}
					if ( $facebook || $twitter || $pinterest || $behance || $dribbble || $vimeo || $linkedin ) {
						?>
						<div class="team-social">
							<ul>
								<?php
								if ( $facebook ) {
									echo '<li><a class="icon-1" href="' . esc_url( $facebook ) . '"><i class="fab fa-facebook-f"></i></a></li>';
								}
								if ( $twitter ) {
									echo '<li><a class="icon-2" href="' . esc_url( $twitter ) . '"><i class="fab fa-twitter"></i></a></li>';
								}
								if ( $dribbble ) {
									echo '<li><a class="icon-3" href="' . esc_url( $dribbble ) . '"><i class="fab fa-dribbble"></i></i></a></li>';
								}
								if ( $vimeo ) {
									echo '<li><a class="icon-4" href="' . esc_url( $vimeo ) . '"><i class="fab fa-vimeo-v"></i></i></a></li>';
								}
								if ( $pinterest ) {
									echo '<li><a class="icon-5" href="' . esc_url( $pinterest ) . '"><i class="fab fa-pinterest"></i></a></li>';
								}
								if ( $behance ) {
									echo '<li><a class="icon-6" href="' . esc_url( $behance ) . '"><i class="fab fa-behance"></i></a></li>';
								}
								if ( $linkedin ) {
									echo '<li><a class="icon-7" href="' . esc_url( $linkedin ) . '"><i class="fab fa-linkedin"></i></a></li>';
								}
								?>
							</ul>
						</div>
						<?php
					}
					?>
				</div>
				<div class="team-name">
					<h5 class="text-black">
						<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
					</h5>
					<span class="text-black"><?php echo esc_html( get_post_meta( get_the_ID(), 'designation', true ) ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	<div class="col-sm-12">
		<?php
		if ( function_exists( 'cardealer_wp_bs_pagination' ) ) {
			cardealer_wp_bs_pagination( $teams_query->max_num_pages );
		}
		?>
	</div>
	<?php
	wp_reset_postdata();
}
