<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$cat_ids = wp_get_post_categories( get_the_ID() );
if ( $cat_ids ) {
	$args               = array(
		'category__in'        => $cat_ids,
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => 6, // Number of related posts to display.
		'ignore_sticky_posts' => 1,
		'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		),
	);
	$related_post_query = new wp_query( $args );
	$related_post_count = $related_post_query->found_posts;
	if ( $related_post_query->have_posts() ) {
		?>
		<!-- Related Posts -->
		<div class="related_posts_wrapper">
			<div class="related-work hover-direction page-section-1-pt">
				<div class="row">
					<div class="col-ld-12 col-md-12">
						<h3 class="text-blue"><?php esc_html_e( 'Related Posts', 'cardealer' ); ?></h3>
						<?php
						if ( $related_post_count > 3 ) {
							$div_class = 'owl-carousel owl-carousel-7 blog-related-posts-carousel';
						} else {
							$div_class = 'item-listing';
						}
						?>
						<div class="<?php echo esc_attr( $div_class ); ?>">
							<?php
							while ( $related_post_query->have_posts() ) {
								$related_post_query->the_post();
								if ( has_post_thumbnail() ) {
									$related_post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
									$related_post_img_data     = wp_get_attachment_image_src( $related_post_thumbnail_id, 'cardealer-testimonials-thumb' );
									if ( isset( $related_post_img_data[0] ) ) {
										$related_post_img = $related_post_img_data[0];
									}
									?>
									<div class="item">
										<div class="portfolio-item">
											<a href="<?php echo esc_url( get_permalink() ); ?>">
											<?php if ( has_post_thumbnail() ) : ?>
												<?php if ( cardealer_lazyload_enabled() ) { ?>
													<img src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $related_post_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="cardealer-lazy-load img-responsive">
												<?php } else { ?>
													<img src="<?php echo esc_url( $related_post_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="img-responsive">
												<?php } ?>
											<?php endif; ?>
											</a>
											<div class="portfolio-caption">
												<div class="portfolio-overlay">
													<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
												</div>
											</div>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		/* Restore original Post Data */
		wp_reset_postdata();
	}
}
