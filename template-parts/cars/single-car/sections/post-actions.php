<?php
global $car_dealer_options;
?>
<div class="car-detail-post-option">
	<ul>
		<?php
		if ( ! isset( $car_dealer_options['is-compare-on-vehicle-detail'] ) || 'yes' === $car_dealer_options['is-compare-on-vehicle-detail'] ) {
			?>
			<li><a href="javascript:void(0)" title="<?php echo esc_attr( get_the_title() ); ?>" data-id="<?php echo get_the_ID(); ?>" class="pgs_compare_popup compare_pgs"><i class="fas fa-exchange-alt"></i> <?php esc_html_e( 'Add to compare', 'cardealer' ); ?></a></li>
			<?php
		}
		$wishlist_status = ( isset( $car_dealer_options['cars-is-wishlist-on'] ) ) ? $car_dealer_options['cars-is-wishlist-on'] : 'yes';
		if ( class_exists( 'CDFS_Wishlist' ) && 'yes' === $wishlist_status && is_user_logged_in() ) {

			$wishlist_label = isset( $car_dealer_options['add_to_wishlist_text'] ) ? $car_dealer_options['add_to_wishlist_text'] : esc_html__( 'Add to wishlist', 'cardealer' );

			$cdfs_wishlist        = new CDFS_Wishlist();
			$wishlist_class       = 'pgs_wishlist';
			$wishlist_icon_class  = 'far fa-heart';
			if ( $cdfs_wishlist->is_car_in_wishlist( get_the_ID() ) ) {
				$wishlist_class      .= ' added-wishlist';
				$wishlist_icon_class  = 'fas fa-heart';
				$wishlist_label       = isset( $car_dealer_options['added_to_wishlist_text'] ) ? $car_dealer_options['added_to_wishlist_text'] : esc_html__( 'Added to Wishlist', 'cardealer' );
			}
			?>
			<li>
				<a href="javascript:void(0)" title="<?php echo esc_attr( get_the_title() ); ?>" data-id="<?php echo get_the_ID(); ?>" class="<?php echo esc_attr( $wishlist_class ); ?>">
					<i class="<?php echo esc_attr( $wishlist_icon_class ); ?>"></i> <?php echo esc_html( $wishlist_label ); ?>
				</a>
			</li>
			<?php
		}
		get_template_part( 'template-parts/cars/single-car/forms/pdf_brochure' );
		get_template_part( 'template-parts/cars/single-car/forms/print_form' );
		?>
	</ul>
	<?php get_template_part( 'template-parts/cars/single-car/share' ); ?>
	<div class="clearfix"></div>
</div>
