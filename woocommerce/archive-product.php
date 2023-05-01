<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header( 'shop' );

global $car_dealer_options, $cardealer_page_sidebar;

$cardealer_page_sidebar = isset( $car_dealer_options['page_sidebar'] ) ? $car_dealer_options['page_sidebar'] : '';
if ( empty( $cardealer_page_sidebar ) ) {
	$cardealer_page_sidebar = 'right_sidebar';
}
$curremt_post_id = get_the_ID();
if ( is_archive() ) {
	$curremt_post_id = cardealer_get_current_post_id();
}
if ( is_shop() && ! is_single() ) {
	$shop_page_id = wc_get_page_id( 'shop' );
	if ( ! empty( $shop_page_id ) && -1 !== $shop_page_id ) {
		$curremt_post_id = $shop_page_id;
	}
}

$page_layout_custom = get_post_meta( $curremt_post_id, 'page_layout_custom', true );
if ( $page_layout_custom ) {
	$page_sidebar = get_post_meta( $curremt_post_id, 'page_sidebar', true );
	if ( $page_sidebar ) {
		$cardealer_page_sidebar = $page_sidebar;
	}
}
$width = 12;

$sidebar_stat = '';

if ( 'left_sidebar' === $cardealer_page_sidebar || 'right_sidebar' === $cardealer_page_sidebar ) {
	$width_lg      = $width - 3;
	$width_md      = $width - 3;
	$width_sm      = $width - 4;
	$sidebar_stat .= ' with-sidebar';
	$sidebar_stat .= " with-$cardealer_page_sidebar";
} elseif ( 'two_sidebar' === $cardealer_page_sidebar ) {
	$width_lg = 6;
	$width_md = 6;
	$width_sm = 6;

	$sidebar_stat .= ' with-sidebar';
	$sidebar_stat .= " with-$cardealer_page_sidebar";
} else {
	$width_lg = $width;
	$width_md = $width;
	$width_sm = $width;

	$sidebar_stat .= 'without-sidebar';
}
?>
<section class="product-listing page-section-ptb">
	<div class="container">
		<div class="row <?php echo esc_attr( $sidebar_stat ); ?>"> 
			<?php if ( ( 'left_sidebar' === $cardealer_page_sidebar || 'two_sidebar' === $cardealer_page_sidebar ) ) { ?>
			<aside id="right" class="sidebar col-lg-3 col-md-3 col-sm-4 sidebar-left">
				<?php
				/**
				 * Hook woocommerce_sidebar.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				get_sidebar( 'left' );
				?>
			</aside>
			<?php } ?>
			<div class="content col-lg-<?php echo esc_attr( $width_lg ); ?> col-md-<?php echo esc_attr( $width_md ); ?> col-sm-<?php echo esc_attr( $width_sm ); ?>">
			<?php
			/**
			 * Hook woocommerce_before_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			do_action( 'woocommerce_before_main_content' );
			?>

			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

			<?php endif; ?>

			<?php
			/**
			 * Hook woocommerce_archive_description.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			do_action( 'woocommerce_archive_description' );
			?>

			<?php if ( woocommerce_product_loop() ) : ?>

				<?php
				/**
				 * Hook woocommerce_before_shop_loop.
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
				?>

				<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php
				if ( wc_get_loop_prop( 'total' ) ) {
					?>
					<?php
					while ( have_posts() ) :
						the_post();
						?>

						<?php
						/**
						 * Hook: woocommerce_shop_loop.
						 *
						 * @hooked WC_Structured_Data::generate_product_data() - 10
						 */
						do_action( 'woocommerce_shop_loop' );
						?>

						<?php
						wc_get_template_part( 'content', 'product' );
					endwhile; // end of the loop.
					wp_reset_postdata();
					?>

					<?php woocommerce_product_loop_end(); ?>
					<?php
				}
				?>
				<?php
				/**
				 * Hook woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
				?>

				<?php
				elseif ( ! woocommerce_product_subcategories(
					array(
						'before' => woocommerce_product_loop_start( false ),
						'after'  => woocommerce_product_loop_end( false ),
					)
				) ) :
					?>
					<?php wc_get_template( 'loop/no-products-found.php' ); ?>

			<?php endif; ?>
			</div>
			<?php
			/**
			 * Hook woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );
			?>
			<?php if ( ( 'right_sidebar' === $cardealer_page_sidebar || 'two_sidebar' === $cardealer_page_sidebar ) ) { ?>
			<aside id="right" class="sidebar col-lg-3 col-md-3 col-sm-4 sidebar-right">
				<?php
				/**
				 * Hook woocommerce_sidebar.
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action( 'woocommerce_sidebar' );
				?>
			</aside>
			<?php } ?>
		</div>
	</div>
</section>
<?php get_footer( 'shop' ); ?>
