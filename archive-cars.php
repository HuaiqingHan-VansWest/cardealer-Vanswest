<?php
/**
 * The Template for displaying cars listings.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

get_header();

global $car_dealer_options;

$layout          = cardealer_get_vehicle_listing_page_layout();
$container_class = ( 'lazyload' === $layout ) ? 'container-fluid' : 'container';
$template        = ( 'lazyload' === $layout ) ? 'lazy-load' : $layout;
$template        = ( wp_is_mobile() ) ? 'mobile' : $template;
$layout_class    = ( wp_is_mobile() ) ? 'mobile default' : $layout;

$inv_page_id            = ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) ? $car_dealer_options['cars_inventory_page'] : '';
$inv_page_content       = get_post_field( 'post_content', $inv_page_id );
$inv_page_content_class = ( $inv_page_id && $inv_page_content ) ? 'cd-content' : 'cd-no-content';
?>
<section <?php post_class( 'product-listing page-section-ptb ' . $layout_class ); ?>>

	<div class="<?php echo esc_attr( $container_class ); ?>">

		<?php
		do_action( 'before_vehicle_inventory_page_content', $layout );

		$template_arguments = apply_filters( 'cardealer/cars/archive/layout/args', array(
			'layout'                 => $layout,
			'inv_page_id'            => $inv_page_id,
			'inv_page_content'       => $inv_page_content,
			'inv_page_content_class' => $inv_page_content_class,
		), $car_dealer_options );

		$inventory_page_edit_mode = function_exists( 'cardealer_is_inventory_page_edit_mode' ) ? cardealer_is_inventory_page_edit_mode() : false;
		if ( $inventory_page_edit_mode ) {
			get_template_part( 'template-parts/cars/archive-layout/dummy-inventory', null, $template_arguments );
		} else {
			get_template_part( 'template-parts/cars/archive-layout/' . $template, null, $template_arguments );
		}

		do_action( 'after_vehicle_inventory_page_content', $layout );
		?>

	</div>

</section>
<!--.product-listing-->
<?php
get_footer();
