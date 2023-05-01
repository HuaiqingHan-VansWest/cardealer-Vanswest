<?php
/**
 * Template Name: Testimonials
 * Description: A Page Template that display testimonials.
 *
 * @package CarDealer
 * @author  Potenza Global Solutions
 * version 1.0.0
 */

get_header();

global $car_dealer_options, $post;

// Testimonials coding.
$layout_style = ( isset( $car_dealer_options['testimonials_layout'] ) ) ? $car_dealer_options['testimonials_layout'] : 'testimonial-1'; // Option inside theme option.

?>
<section id="cd_testimonials" class="cd_testimonials testimonial white-bg page-section-ptb <?php esc_attr( $layout_style ); ?>">
	<div class="container">
		<div class="row">
			<?php
			$columns   = ! empty( $car_dealer_options['testimonial-columns'] ) ? $car_dealer_options['testimonial-columns'] : 3;
			$col_class = 'col-md-12';

			if ( 'testimonial-1' === $layout_style ) {
				switch ( $columns ) {
					case 1:
						$col_class = 'col-md-12';
						break;

					case 2:
						$col_class = 'col-md-6 col-sm-6';
						break;

					case 4:
						$col_class = 'col-md-3 col-sm-6';
						break;

					default:
						$col_class = 'col-md-4 col-sm-6';
				}
			}

			$testimonials_per_page = 10;

			if ( isset( $car_dealer_options['testimonials_per_page'] ) && ! empty( $car_dealer_options['testimonials_per_page'] ) ) {
				$testimonials_per_page = $car_dealer_options['testimonials_per_page'];
			}

			$testimonials_paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$args               = array(
				'post_type'      => 'testimonials',
				'posts_per_page' => $testimonials_per_page,
				'paged'          => $testimonials_paged,
				'post_status'    => 'publish',
			);

			$testimonials_query = new WP_Query( $args );

			if ( $testimonials_query->have_posts() ) {
				?>
				<div id="cd_testimonials" class="cd_testimonials <?php echo esc_attr( $layout_style ); ?>">
					<?php
					while ( $testimonials_query->have_posts() ) {
						$testimonials_query->the_post();
						$content        = get_post_meta( get_the_ID(), 'content', true );
						$designation    = get_post_meta( get_the_ID(), 'designation', true );
						$profile_img_id = get_post_meta( get_the_ID(), 'profile_image', true );
						$profile_img    = wp_get_attachment_image_src( $profile_img_id, 'thumbnail' );
						if ( $content ) {
							if ( 'testimonial-1' === $layout_style || 'testimonial-5' === $layout_style ) {
								?>
								<div class="testimonial-block text-center <?php echo esc_attr( $col_class ); ?>">
									<div class="testimonial-image">
										<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail( 'cardealer-blog-thumb' );
										}
										?>
									</div>
									<div class="testimonial-box">
										<div class="testimonial-avtar">
											<img class="img-responsive" src="<?php echo esc_url( $profile_img[0] ); ?>" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
											<h6><?php the_title(); ?></h6>
											<?php if ( $designation ) { ?>
												<span><?php echo esc_html( $designation ); ?></span>
											<?php } ?>
										</div>
										<div class="testimonial-content">
											<p><?php echo wp_kses_post( $content ); ?></p>
											<i class="fas fa-quote-right"></i>
										</div>
									</div>
								</div>
								<?php
							} elseif ( 'testimonial-2' === $layout_style ) {
								$profile_img = wp_get_attachment_image_src( $profile_img_id, 'thumbnail' );
								?>
								<div class="testimonial-block <?php echo esc_attr( $col_class ); ?>">
									<div class="testimonial-content">
										<i class="fas fa-quote-left"></i>
										<p> <?php echo wp_kses_post( $content ); ?></p>
									</div>
									<div class="testimonial-info">
										<div class="testimonial-avatar">
											<img class="img-responsive" src="<?php echo esc_url( $profile_img[0] ); ?>" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
										</div>
										<div class="testimonial-name">
											<h6 class="text-white"><?php the_title(); ?></h6>
											<?php echo wp_kses_post( ( $designation ) ? ' <span> |' . esc_html( $designation ) . '</span>' : '' ); ?>
										</div>
									</div>
								</div>
								<?php
							} elseif ( 'testimonial-3' === $layout_style ) {
								$profile_img = wp_get_attachment_image_src( $profile_img_id, 'large' );
								?>
								<div class="testimonial-block <?php echo esc_attr( $col_class ); ?>">
									<div class="row">
										<div class="col-lg-3 col-md-3 col-sm-3">
											<div class="testimonial-avtar">
												<img class="img-responsive" src="<?php echo esc_url( $profile_img[0] ); ?>" width="<?php echo esc_attr( $profile_img[1] ); ?>" height="<?php echo esc_attr( $profile_img[2] ); ?>">
											</div>
										</div>
										<div class="col-lg-9 col-md-9 col-sm-9">
											<div class="testimonial-content">
												<p><i class="fas fa-quote-left"></i><span><?php echo wp_kses_post( $content ); ?></span><i class="fas fa-quote-right pull-right"></i></p>
											</div>
											<div class="testimonial-info">
												<h6><?php the_title(); ?></h6>
												<?php echo wp_kses_post( ( $designation ) ? ' <span>' . esc_html( $designation ) . '</span>' : '' ); ?>
											</div>
										</div>
									</div>
								</div>
								<?php
							} elseif ( 'testimonial-4' === $layout_style ) {
								?>
								<div class="testimonial-block text-center <?php echo esc_attr( $col_class ); ?>">
									<i class="fas fa-quote-left"></i>
									<p><?php echo wp_kses_post( $content ); ?></p>
									<h6 class="text-red"><?php the_title(); ?></h6>
								</div>
								<?php
							}
						}
					}
					?>
				</div>
				<div class="col-sm-12">
					<?php
					if ( function_exists( 'cardealer_wp_bs_pagination' ) ) {
						cardealer_wp_bs_pagination( $testimonials_query->max_num_pages );
					}
					?>
				</div>
				<?php
				wp_reset_postdata();
			} else {
				?>
				<div class="col-sm-12">
					<p><?php esc_html_e( 'No Testimonials Found.', 'cardealer' ); ?></p>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>

<?php
get_footer();
