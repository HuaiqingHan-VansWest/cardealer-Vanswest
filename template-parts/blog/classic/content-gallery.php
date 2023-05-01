<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-entry-slider">
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
											<img class="img-responsive cardealer-lazy-load" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" data-src="<?php echo esc_url( $image['sizes']['cardealer-blog-thumb'] ); ?>"/>
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
		<?php } ?>
	</div>
	<?php if ( ! is_single() ) : ?>
	<div class="entry-title">
		<?php
		if ( 'grid' === $gallery_type ) {
			$icon = 'fas fa-th';
		} else {
			$icon = 'far fa-image';
		}
		?>
		<i class="<?php echo esc_attr( $icon ); ?>"></i> <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</div>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/entry_meta' ); ?>

	<div class="entry-content">
		<?php
		if ( is_single() ) {
			the_content();
			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages', 'cardealer' ) . ':</span>',
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				)
			);
		} else {
			the_excerpt();
		}
		?>
	</div>

	<?php get_template_part( 'template-parts/entry_footer' ); ?>

</article><!-- #post -->
