<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;
?>

<div class="entry-footer entry-share clearfix">
	<?php
	if ( is_single() ) {
		?>
		<div class="entry-footer-tags tags-2 pull-left clearfix">
			<?php the_tags( '<h5>' . esc_html__( 'Tags', 'cardealer' ) . ':</h5><ul><li>', '</li><li>', '</li></ul>' ); ?>
		</div>
		<?php
	} else {
		?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="button pull-left">
			<span><?php esc_html_e( 'Read More', 'cardealer' ); ?></span>
		</a>
		<?php
	}
	?>
	<div class="entry-footer-share pull-right clearfix">
		<?php get_template_part( 'template-parts/cars/single-car/share' ); ?>
	</div>
</div>

<?php
if ( ! is_single() ) {
	?>
	<hr>
	<?php
}
