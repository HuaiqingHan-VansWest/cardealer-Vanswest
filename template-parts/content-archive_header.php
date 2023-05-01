<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( ! empty( $car_dealer_options['archive_header'] ) ) {
	$archive_header          = $car_dealer_options['archive_header'];
	$archive_header_author   = $archive_header['author'];
	$archive_header_category = $archive_header['category'];
	$archive_header_tag      = $archive_header['tag'];
}
if ( is_author() && ! empty( $archive_header_author ) && get_the_author_meta( 'description' ) ) {
	?>
	<div class="archive-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="author-info">
					<div class="author-avatar">
						<?php
						$author_bio_avatar_size = apply_filters( 'cardealer_author_bio_avatar_size', 68 );
						echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
						?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2>
							<?php
							printf(
								/* translators: 1: Author Name */
								esc_html__( 'About %s', 'cardealer' ),
								get_the_author()
							);
							?>
						</h2>
						<p><?php the_author_meta( 'description' ); ?></p>
					</div><!-- .author-description	-->
				</div><!-- .author-info -->
			</div>
		</div>
	</div>
	<?php
} elseif ( is_category() && ! empty( $archive_header_category ) && category_description() ) {
	?>
	<div class="archive-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="archive-description">
					<?php echo category_description(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
} elseif ( is_tag() && ! empty( $archive_header_tag ) && tag_description() ) {
	?>
	<div class="archive-header">
		<div class="row">
			<div class="col-sm-12">
				<div class="archive-description">
					<?php echo tag_description(); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
