<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */
$comments_count  = wp_count_comments( get_the_ID() );
$audio_file      = get_post_meta( get_the_ID(), 'audio_file', true );
$audio_file_data = false;

if ( $audio_file ) {
	$audio_file_data = cardealer_acf_get_attachment( $audio_file );
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-2 blog-no-image">
		<?php
		if ( $audio_file_data ) {
			?>
			<div class="blog-image blog-entry-audio audio-video">
				<audio id="player2" src="<?php echo esc_url( $audio_file_data['url'] ); ?>" width="100%" controls="controls"></audio>
				<div class="date-box">
					<span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span>
				</div>
			</div>
			<?php
		}
		?>
		<div class="blog-content">
			<div class="blog-admin-main clearfix">
				<div class="blog-admin">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 64 ); ?>
					<span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author(); ?></a></span>
				</div>
				<div class="blog-meta pull-right">
					<ul>
						<li class="blog-meta-comment"><a href="<?php echo esc_url( get_comments_link( get_the_ID() ) ); ?>"> <i class="fas fa-comment"></i><br /><?php echo esc_html( $comments_count->approved ); ?></a></li>
						<li class="blog-meta-share share"><?php get_template_part( 'template-parts/cars/single-car/share' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="blog-description text-center">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
				<div class="separator"></div>
				<?php the_excerpt(); ?>
			</div>
		</div>
	</div>
</article><!-- #post -->
