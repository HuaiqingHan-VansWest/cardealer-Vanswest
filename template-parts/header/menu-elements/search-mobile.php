<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

$search_placeholder_text = ( isset( $car_dealer_options['search_placeholder_text'] ) ) ? $car_dealer_options['search_placeholder_text'] : esc_html__( 'Search...', 'cardealer' );
$search_post_type        = cardealer_search_post_type();
?>
<div class="mobile-searchform-wrapper">
	<form role="search" method="get" id="menu-searchform" name="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="search cd-search-wrap menu-search-wrap">
			<a class="search-open-btn not_click" href="javascript:void(0);"> </a>
			<div class="search-box not-click">
				<?php
				if ( 'any' !== $search_post_type ) {
					?>
					<input type="hidden" name="post_type" value="<?php echo esc_attr( $search_post_type ); ?>"/>
					<?php
				}
				?>
				<input type="text" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="menu-s"  placeholder="<?php echo esc_attr( $search_placeholder_text ); ?>" class="cd-search-autocomplete-input not-click" data-seach_type="default" />
				<button class="cd-search-submit" value="Search" type="submit"><i class="fas fa-search"></i></button>
				<div class="cd-search-autocomplete"><ul class="cd-search-autocomplete-list"></ul></div>
			</div>
		</div>
	</form>
</div>
