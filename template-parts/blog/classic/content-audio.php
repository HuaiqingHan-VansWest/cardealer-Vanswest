<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$audio_file      = get_post_meta( get_the_ID(), 'audio_file', true );
$audio_file_data = false;
if ( $audio_file ) {
	$audio_file_data = cardealer_acf_get_attachment( $audio_file );
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( $audio_file_data ) {
		?>
		<div class="blog-entry-audio audio-video">
			<audio id="player2" src="<?php echo esc_url( $audio_file_data['url'] ); ?>" width="100%" controls="controls"></audio>
		</div>
		<?php
	}

	if ( ! is_single() ) {
		?>

	<div class="entry-title">
		<i class="far fa-file-audio"></i> <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</div>
		<?php
	}

	get_template_part( 'template-parts/entry_meta' );
	?>

	<div class="entry-content">
		<?php
		if ( is_single() ) {
			the_content();
			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages', 'cardealer' ) . ':</span>',
					'after'       => '</div>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				)
			);
		} else {
			the_excerpt();
		}
		?>
	</div>

	<?php get_template_part( 'template-parts/entry_footer' ); ?>

</article><!-- #post -->
