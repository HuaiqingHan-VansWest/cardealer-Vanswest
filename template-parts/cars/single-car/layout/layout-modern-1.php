<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */
global $car_dealer_options;
$sidebar_position = cardealer_get_cars_details_page_sidebar_position();
$push_right = ''; $pull_left = '';
if ( 'left' === $sidebar_position ) {
    $pull_left = 'col-lg-push-4 col-md-push-4 col-sm-push-5';
    $push_right = 'col-lg-pull-8 col-md-pull-8 col-sm-pull-7';
}
?>
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-7 <?php echo esc_attr($pull_left); ?>">
        <?php
        add_action( 'before_image_gallery_slider', 'cardealer_vehicle_sold_label', 10 );
        add_action( 'after_image_gallery_slider', 'cardealer_vehicle_image_gallery_video_button', 10 );
        get_template_part( 'template-parts/cars/single-car/car-image' );
        ?>
        <div class="details-nav">
			<ul>
				<?php
				get_template_part( 'template-parts/cars/single-car/forms/make_an_offer' );
				get_template_part( 'template-parts/cars/single-car/forms/schedule_test_drive' );
				get_template_part( 'template-parts/cars/single-car/forms/email_to_friend' );
				get_template_part( 'template-parts/cars/single-car/forms/financial_form' );
				?>
			</ul>
		</div>
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
        <?php
        get_template_part( 'template-parts/cars/single-car/tabs/tabs' );
        if ( 'no' !== $sidebar_position ) {
            get_template_part( 'template-parts/cars/single-car/related' );
        }
        ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-5 <?php echo esc_attr($push_right); ?>">
        <?php
        cardealer_get_cars_details_breadcrumb();
        ?>
        <h1 class="car-title"><?php the_title(); ?></h1>
        <?php
        cardealer_subtitle_attributes( get_the_ID() );
        the_excerpt();
		cardealer_car_price_html( 'aside-price hide-sell hide-status', null, true, true, true );
		?>
        <ul class="aside-lead-form-btn">
            <?php get_template_part( 'template-parts/cars/single-car/forms/request_info' );?>
        </ul>
        <?php get_template_part( 'template-parts/cars/single-car/car-summary' ); ?>
        <?php
        if ( 'no' === $sidebar_position ) {
            cardealer_get_widget_fuel_efficiency();
        } else {
            dynamic_sidebar( 'detail-cars' );
        }
        ?>
    </div>
</div>
<?php
if ( 'no' === $sidebar_position ) {
    ?>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <?php get_template_part( 'template-parts/cars/single-car/related' ); ?>
        </div>
    </div>
    <?php
}
?>
