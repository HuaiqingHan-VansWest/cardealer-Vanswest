<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $woocommerce_loop, $car_dealer_options;

if ( empty( $product ) || ! $product->exists() || ( isset( $car_dealer_options['show_related_products'] ) && 'no' === $car_dealer_options['show_related_products'] ) ) {
	return;
}

if ( ! $related_products ) {
	return;
}

foreach ( $related_products as $related_product ) {
	$related[] = $related_product->get_id();
}
$posts_per_page = cardealer_get_related_products_show();
$args           = apply_filters(
	'woocommerce_related_products_args',
	array(
		'post_type'           => 'product',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
		'posts_per_page'      => $posts_per_page,
		'orderby'             => $orderby,
		'post__in'            => $related,
		'post__not_in'        => array( get_the_ID() ),
	)
);

$products = new WP_Query( $args );

$woocommerce_loop['loop']    = 0;
$woocommerce_loop['name']    = 'related';
$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', $columns );
$column                      = cardealer_get_column_related_products();
$tot_items                   = cardealer_get_related_products_show();
if ( $products->have_posts() ) : ?>

	<div class="related products">

		<h2><?php esc_html__( 'Related Products', 'cardealer' ); ?></h2>

		<?php woocommerce_product_loop_start(); ?>

			<div class='owl-carousel'
				data-nav-arrow='true'
				data-nav-dots='false'
				data-items='<?php echo esc_attr( $column ); ?>'
				data-md-items='2'
				data-sm-items='2'
				data-xs-items='1'
				data-xx-items='1'
				data-autoplay='ture'
				data-loop='false'>
			<?php
			while ( $products->have_posts() ) :
				$products->the_post();
				?>
				<div class='item'>
					<div class="product">
						<?php
						do_action( 'woocommerce_before_shop_loop_item' );
						?>
						<div class="product-thumbnail">
							<?php
							do_action( 'woocommerce_before_shop_loop_item_title' );
							?>
						</div>
						<div class="product-info text-center">
							<?php
							do_action( 'woocommerce_shop_loop_item_title' );
							do_action( 'woocommerce_after_shop_loop_item_title' );
							do_action( 'woocommerce_after_shop_loop_item' );
							?>
						</div>
					</div>
				</div>
			<?php endwhile; // end of the loop. ?>
			</div>
		<?php woocommerce_product_loop_end(); ?>

	</div>
	<?php
	wp_reset_postdata();
endif;
