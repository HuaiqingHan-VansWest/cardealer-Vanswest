<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Theme template functions.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package CarDealer/Templates
 * @version 1.0.0
 */

if ( ! function_exists( 'cardealer_single_nav' ) ) {
	/**
	 * Cardealer Single nav function
	 */
	function cardealer_single_nav() {
		$prev_post = get_previous_post();
		$next_post = get_next_post();
		?>
	<div class="port-navigation clearfix">
		<?php
		if ( ! empty( $prev_post ) ) {
			if ( has_post_thumbnail( $prev_post->ID ) ) {
				$prev_post_thumb_id = get_post_thumbnail_id( $prev_post->ID );

				$prev_post_thumb_data = wp_get_attachment_image_src( $prev_post_thumb_id, 'cardealer-post_nav' );
				$prev_post_thumb_url  = $prev_post_thumb_data['0'];
			}
			?>
			<div class="port-navigation-left pull-left">
				<div class="tooltip-content-3" data-original-title="<?php echo esc_attr( $prev_post->post_title ); ?>" data-toggle="tooltip" data-placement="right">
					<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
						<div class="port-photo pull-left">
							<?php if ( has_post_thumbnail( $prev_post->ID ) ) : ?>
								<?php if ( cardealer_lazyload_enabled() ) { ?>
								<img src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $prev_post_thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $prev_post->ID ) ); ?>" class="cardealer-lazy-load">
								<?php } else { ?>
								<img class="cd-post-nav-image" src="<?php echo esc_url( $prev_post_thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $prev_post->ID ) ); ?>">
								<?php } ?>

								<?php
							else :
								echo esc_html__( 'Previous Post', 'cardealer' );
							endif;
							?>
						</div>
						<div class="port-arrow">
							<i class="fas fa-angle-left"></i>
						</div>
					</a>
				</div>
			</div>
			<?php
		}
		if ( ! empty( $next_post ) ) {
			if ( has_post_thumbnail( $next_post->ID ) ) {
				$next_post_thumb_id   = get_post_thumbnail_id( $next_post->ID );
				$next_post_thumb_data = wp_get_attachment_image_src( $next_post_thumb_id, 'cardealer-post_nav' );
				$next_post_thumb_url  = $next_post_thumb_data['0'];
			}
			?>
			<div class="port-navigation-right pull-right">
				<div class="tooltip-content-3" data-original-title="<?php echo esc_attr( $next_post->post_title ); ?>" data-toggle="tooltip" data-placement="left">
					<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
						<div class="port-arrow pull-left">
							<i class="fas fa-angle-right"></i>
						</div>
						<div class="port-photo">
							<?php if ( has_post_thumbnail( $next_post->ID ) ) : ?>
								<?php if ( cardealer_lazyload_enabled() ) { ?>
								<img src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php echo esc_url( $next_post_thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $next_post->ID ) ); ?>" class="cardealer-lazy-load">
								<?php } else { ?>
								<img class="cd-post-nav-image" src="<?php echo esc_url( $next_post_thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title( $next_post->ID ) ); ?>">
								<?php } ?>
								<?php
							else :
								echo esc_html__( 'Next Post', 'cardealer' );
							endif;
							?>
						</div>
					</a>
					<div class="clearfix"></div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
		<?php
	}
}

if ( ! function_exists( 'cardealer_wp_bs_pagination' ) ) {
	/**
	 * Bootstrap pagination function
	 *
	 * @see cardealer_wp_bs_pagination()
	 *
	 * @param string $pages store page name.
	 * @param int    $range store range.
	 */
	function cardealer_wp_bs_pagination( $pages = '', $range = 4 ) {
		$showitems = ( $range * 2 ) + 1;
		global $paged;
		if ( empty( $paged ) ) {
			$paged = 1; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
		if ( empty( $pages ) ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( $pages > 1 ) {
			?>
			<div class="text-center">
				<nav>
					<ul class="pagination">
						<?php
						if ( $paged > 1 ) {
							?>
							<li><a href="<?php echo esc_url( get_pagenum_link( $paged - 1 ) ); ?>" aria-label="Previous">&laquo;<span class='hidden-xs'> </span></a></li>
							<?php
						}

						for ( $i = 1; $i <= $pages; $i++ ) {
							if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
								if ( $paged === $i ) {
									?>
									<li class="active"><span>
									<?php echo esc_html( $i ); ?> <span class="sr-only">(current)</span></span></li>
									<?php
								} else {
									?>
									<li><a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>"><?php echo esc_html( $i ); ?></a></li>
									<?php
								}
							}
						}

						if ( $paged < $pages ) {
							?>
							<li>
								<a href="<?php echo esc_url( get_pagenum_link( $paged + 1 ) ); ?>" aria-label="Next"><span class="hidden-xs"> </span>&raquo;</a></li>
							<?php
						}
						?>
					</ul>
				</nav>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'cardealer_footer_copyright' ) ) {
	/**
	 * Cardealer Footer copyrights function
	 */
	function cardealer_footer_copyright() {
		global $car_dealer_options;
		$footer_copyright = sprintf(
			wp_kses(
				/* translators: Copyright */
				__( 'Copyright &copy; <span class="copy_year">%1$s</span>, <a href="%2$s" title="%3$s">%4$s</a> All Rights Reserved.', 'cardealer' ),
				array(
					'a'      => array(
						'href'   => array(),
						'title'  => array(),
						'class'  => array(),
						'target' => array(),
					),
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
					'span'   => array(
						'class' => array(),
					),
				)
			),
			gmdate( 'Y' ),
			esc_url( home_url( '/' ) ),
			esc_attr( get_bloginfo( 'name', 'display' ) ),
			esc_html( get_bloginfo( 'name', 'display' ) )
		);

		if ( isset( $car_dealer_options['footer_copyright'] ) && ! empty( $car_dealer_options['footer_copyright'] ) ) {
			$footer_copyright = $car_dealer_options['footer_copyright'];
			$footer_copyright = do_shortcode( $footer_copyright );
		}

		/**
		 * Filter before copyright content displayed in footer.
		 *
		 * @param string       $fcb_html    HTML contents you want to add before footer copyright.
		 * @visible            true
		 */
		$footer_copyright_before = apply_filters( 'cardealer_footer_copyright_before', $fcb_html = '' );

		/**
		 * Filter after copyright content displayed in footer.
		 *
		 * @param string       $fca_html    HTML contents you want to add after footer copyright.
		 * @visible            true
		 */
		$footer_copyright_after = apply_filters( 'cardealer_footer_copyright_after', $fca_html = '' );

		$footer_copyright = $footer_copyright_before . $footer_copyright . $footer_copyright_after;

		echo do_shortcode( $footer_copyright ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped.
	}
}
