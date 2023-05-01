<?php
/**
 * Template part to show numbered pagination for catalog pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div id="cars-pagination-nav" class="pagination-nav text-center">
	<?php cardealer_cars_pagination(); ?>
	<span class="pagination-loader"></span>
</div>
