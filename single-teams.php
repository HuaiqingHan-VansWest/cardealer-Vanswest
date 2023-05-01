<?php
/**
 * The template for displaying single team posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

global $car_dealer_options;
while ( have_posts() ) :
	the_post();
	$facebook  = get_post_meta( get_the_ID(), 'facebook', true );
	$twitter   = get_post_meta( get_the_ID(), 'twitter', true );
	$dribbble  = get_post_meta( get_the_ID(), 'dribbble', true );
	$vimeo     = get_post_meta( get_the_ID(), 'vimeo', true );
	$pinterest = get_post_meta( get_the_ID(), 'pinterest', true );
	$behance   = get_post_meta( get_the_ID(), 'behance', true );
	$linkedin  = get_post_meta( get_the_ID(), 'linkedin', true );
	?>
	<section class="our-team white-bg page-section-pt blog">
		<div class="content-wrapper blog">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 30px;">
							<div class="row">
								<div class="col-md-4 col-sm-5">
									<?php
									// check if the post has a Post Thumbnail assigned to it.
									if ( has_post_thumbnail() ) {
										?>
										<div class="hover-direction clearfix blog">
											<div class="portfolio-item" style="position: relative; overflow: hidden;">
												<?php if ( isset( $car_dealer_options['enable_lazyload'] ) && $car_dealer_options['enable_lazyload'] ) { ?>
												<img alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php the_post_thumbnail_url( 'cardealer-team-thumb' ); ?>" class="img-responsive cardealer-lazy-load"/>
												<?php } else { ?>
												<img alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php the_post_thumbnail_url( 'cardealer-team-thumb' ); ?>" class="img-responsive"/>
												<?php } ?>
												<div class="portfolio-caption" style="position: absolute; top: 0px; left: -1140px;">
													<div class="portfolio-overlay">
														<a href="<?php echo esc_url( get_permalink() ); ?>"><i class="fas fa-plus"></i></a>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
									?>
								</div>
								<div class="col-md-8 col-sm-7">
									<div class="entry-meta"></div>
									<div class="entry-content">
										<div class="custom-content-4">
											<div class="clearfix">
												<div class="title pull-left">
													<h2 class="text-blue"><?php the_title(); ?></h2>
													<span><?php echo esc_html( get_post_meta( get_the_ID(), 'designation', true ) ); ?></span>
												</div>
												<div class="social">
													<ul>
														<?php if ( $facebook ) : ?>
															<li><a href="<?php echo esc_url( $facebook ); ?>"><i class="fab fa-facebook-f"></i> </a></li>
														<?php endif; ?>
														<?php if ( $twitter ) : ?>
															<li><a href="<?php echo esc_url( $twitter ); ?>"><i class="fab fa-twitter"></i> </a></li>
														<?php endif; ?>
														<?php if ( $dribbble ) : ?>
															<li><a href="<?php echo esc_url( $dribbble ); ?>"><i class="fab fa-dribbble"></i> </a></li>
														<?php endif; ?>
														<?php if ( $vimeo ) : ?>
															<li><a href="<?php echo esc_url( $vimeo ); ?>"><i class="fab fa-vimeo-v"></i> </a></li>
														<?php endif; ?>
														<?php if ( $pinterest ) : ?>
															<li><a href="<?php echo esc_url( $pinterest ); ?>"><i class="fab fa-pinterest-p"></i> </a></li>
														<?php endif; ?>
														<?php if ( $behance ) : ?>
															<li><a href="<?php echo esc_url( $behance ); ?>"><i class="fab fa-behance"></i> </a></li>
														<?php endif; ?>
														<?php if ( $linkedin ) : ?>
															<li><a href="<?php echo esc_url( $linkedin ); ?>"><i class="fab fa-linkedin-in"></i> </a></li>
														<?php endif; ?>
													</ul>
												</div>
											</div>
											<div class="clearfix">
												<?php the_content(); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</article>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	/* check have_rows function exists*/
	if ( function_exists( 'have_rows' ) && ( have_rows( 'skills' ) || have_rows( 'expertises' ) ) ) {
		?>
		<section class="our-activities page-section-pt">
			<div class="container">
				<div class="row">
					<?php
					if ( have_rows( 'skills' ) ) {
						$skills_title = get_post_meta( get_the_ID(), 'skills_title', true );
						if ( empty( $skills_title ) ) {
							$skills_title = esc_html__( 'Powerful Skills', 'cardealer' );
						}
						?>
						<!-- Powere Ful Skills-->
						<div class="col-sm-6">
							<div class="skills-2">
								<h3><?php echo esc_html( $skills_title ); ?></h3>
								<ul>
									<?php
									while ( have_rows( 'skills' ) ) {
										the_row();

										// vars.
										$skill   = get_sub_field( 'skill' );
										$percent = get_sub_field( 'percent' );
										if ( ! empty( $skill ) && ! empty( $percent ) ) {
											?>
											<li>
												<?php echo esc_html( $skill ); ?>
												<div class="bar_container">
													<span data-bar="{ &quot;color&quot;: &quot;#00a9da&quot; }" class="bar" style="background-color: rgb(0, 169, 218); width: <?php echo esc_attr( $percent ); ?>%;">
														<span class="pct" style="color: rgb(0, 169, 218); opacity: 1;"><?php echo esc_html( $percent ) . '%'; ?></span>
													</span>
												</div>
											</li>
											<?php
										}
									}
									?>
								</ul>
							</div>
						</div>
						<?php
					}

					if ( have_rows( 'expertises' ) ) {
						$expertise_title = get_post_meta( get_the_ID(), 'expertise_title', true );
						if ( empty( $expertise_title ) ) {
							$expertise_title = esc_html__( 'Areas of Expertise', 'cardealer' );
						}
						?>
						<div class="col-sm-6">
							<div class="team-expertise">
								<h3><?php echo esc_html( $expertise_title ); ?></h3>
								<ul>
									<?php
									while ( have_rows( 'expertises' ) ) {
										the_row();
										// vars.
										$expertise = get_sub_field( 'expertise' );
										if ( ! empty( $expertise ) ) {
											?>
											<li><?php echo esc_html( $expertise ); ?></li>
											<?php
										}
									}
									?>
								</ul>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</section>
		<?php
	}
	?>
	<section class="cd-single-team-navigation page-section-pt">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<?php
					$team_navigation = $car_dealer_options['team_navigation'];
					if ( 'yes' === $team_navigation ) {
						?>
						<nav class="nav-single">
							<?php cardealer_single_nav(); ?>
						</nav>
						<?php
					}
					?>
					<!-- .nav-single -->
				</div>
			</div>
		</div>
	</section>
	<?php
endwhile; // end of the loop.

get_footer();
