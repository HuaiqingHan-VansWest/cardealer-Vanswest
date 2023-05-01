<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$comments_count = wp_count_comments( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-2">
		<div class="blog-image blog-entry-slider">
			<?php
			$gallery_type = get_post_meta( get_the_ID(), 'gallery_type', true );

			if ( function_exists( 'have_rows' ) ) {
				if ( have_rows( 'gallery_images' ) ) {
					if ( 'slider' === $gallery_type ) {
						?>
						<div class="owl-carousel-6 blog-post-carousel" data-lazyload="<?php echo esc_attr( cardealer_lazyload_enabled() ); ?>">
							<?php
							while ( have_rows( 'gallery_images' ) ) {
								the_row();

								// vars.
								$image = get_sub_field( 'image' );
								if ( $image ) {
									?>
									<div class="item">
										<?php if ( cardealer_lazyload_enabled() ) { ?>
											<img class="owl-lazy" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $image['sizes']['cardealer-blog-thumb'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
										<?php } else { ?>
											<img src="<?php echo esc_url( $image['sizes']['cardealer-blog-thumb'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
										<?php } ?>

									</div>
									<?php
								}
							}
							?>
						</div>
						<?php
					} elseif ( 'grid' === $gallery_type ) {
						?>
						<div class="blog-entry-grid clearfix hover-direction">
							<ul class="grid-post">
								<?php
								while ( have_rows( 'gallery_images' ) ) {
									the_row();

									// vars.
									$image = get_sub_field( 'image' );
									if ( $image ) {
										?>
										<li>
											<div class="gallery-item" style="position: relative; overflow: hidden;">
												<?php if ( cardealer_lazyload_enabled() ) { ?>
												<img alt="<?php echo esc_attr( $image['alt'] ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $image['sizes']['cardealer-blog-thumb'] ); ?>" class="img-responsive cardealer-lazy-load">
												<?php } else { ?>
												<img alt="<?php echo esc_attr( $image['alt'] ); ?>" src="<?php echo esc_url( $image['sizes']['cardealer-blog-thumb'] ); ?>" class="img-responsive">
												<?php } ?>
											</div>
										</li>
										<?php
									}
								}
								?>
							</ul>
						</div>
						<?php
					}
				}
			} elseif ( has_post_thumbnail() ) {
				?>
				<div class="blog-entry-image hover-direction clearfix blog">
					<?php if ( cardealer_lazyload_enabled() ) { ?>
					<img alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php the_post_thumbnail_url( 'cardealer-blog-thumb' ); ?>" class="cardealer-lazy-load img-responsive">
					<?php } else { ?>
					<img alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php the_post_thumbnail_url( 'cardealer-blog-thumb' ); ?>" class="img-responsive">
					<?php } ?>
				</div>
				<?php
			}
			?>
			<div class="date-box">
				<span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span>
			</div>
		</div>

		<div class="blog-content">
			<div class="blog-admin-main clearfix">
				<div class="blog-admin">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 64 ); ?>
					<span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a></span>
				</div>
				<div class="blog-meta pull-right">
					<ul>
						<li class="blog-meta-comment"><a href="<?php echo esc_url( get_comments_link( get_the_ID() ) ); ?>"> <i class="fas fa-comment"></i><br /><?php echo esc_html( $comments_count->approved ); ?></a></li>
						<li class="blog-meta-share share"><?php get_template_part( 'template-parts/cars/single-car/share' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="blog-description text-center">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
				<div class="separator"></div>
				<?php the_excerpt(); ?>
			</div>
		</div>
	</div>
</article><!-- #post -->
