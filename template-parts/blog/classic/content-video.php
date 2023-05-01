<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$video_type = get_post_meta( get_the_ID(), 'video_type', true );

if ( function_exists( 'get_field' ) ) {
	$post_format_video_youtube = get_field( 'post_format_video_youtube' );
} else {
	$post_format_video_youtube = get_post_meta( get_the_ID(), 'post_format_video_youtube', true );
	$post_format_video_youtube = wp_oembed_get( $post_format_video_youtube );
}
if ( function_exists( 'get_field' ) ) {
	$post_format_video_vimeo = get_field( 'post_format_video_vimeo' );
} else {
	$post_format_video_vimeo = get_post_meta( get_the_ID(), 'post_format_video_vimeo', true );
	$post_format_video_vimeo = wp_oembed_get( $post_format_video_vimeo );
}

$post_format_video_html5 = get_post_meta( get_the_ID(), 'post_format_video_html5', true );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-entry post-1 clearfix">
		<?php
		// Check if video type is html5 and have rows.
		if ( 'html5' === $video_type ) {
			if ( function_exists( 'have_rows' ) && have_rows( 'post_format_video_html5' ) ) {
				?>
				<div class="blog-entry-html-video audio-video">
					<?php
					while ( have_rows( 'post_format_video_html5' ) ) {
						the_row();

						$mp4   = get_sub_field( 'mp4' );
						$webm  = get_sub_field( 'webm' );
						$ogv   = get_sub_field( 'ogv' );
						$cover = get_sub_field( 'cover' );
						if ( $cover ) {
							$cover_img = $cover['url'];
						} else {
							$cover_img = '';
						}
						?>
						<video style="width:100%;height:100%;" id="player1"
						<?php
						if ( ! empty( $cover_img ) ) {
							?>
							poster="<?php echo esc_url( $cover_img ); ?>"
							<?php
						}
						?>
						controls="controls" preload="none">
							<?php
							if ( isset( $mp4['mime_type'] ) && 'video/mp4' === $mp4['mime_type'] ) {
								?>
								<!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
								<source type="video/mp4" src="<?php echo esc_url( $mp4['url'] ); ?>" />
								<?php
							}
							if ( isset( $webm['mime_type'] ) && 'video/webm' === $webm['mime_type'] ) {
								?>
								<!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
								<source type="video/webm" src="<?php echo esc_url( $webm['url'] ); ?>" />
								<?php
							}
							if ( isset( $ogv['mime_type'] ) && 'video/ogg' === $ogv['mime_type'] ) {
								?>
								<!-- Ogg/Vorbis for older Firefox and Opera versions -->
								<source type="video/ogg" src="<?php echo esc_url( $ogv['url'] ); ?>" />
								<?php
							}
							?>
						</video>
						<?php
					}
					?>
				</div>
				<?php
			}
		} elseif ( 'youtube' === $video_type && $post_format_video_youtube ) {
			// use preg_match to find iframe src.

			preg_match( '/src="(.+?)"/', $post_format_video_youtube, $matches );
			if ( ! empty( $matches ) ) {
				$src = $matches[1];

				// Remove existing params.
				$src = remove_query_arg( array( 'feature' ), $src );

				// add extra params to iframe src.
				$params  = array(
					'rel' => 0,
				);
				$new_src = add_query_arg( $params, $src );
				?>
			<div class="blog-entry-you-tube">
				<div class="js-video [youtube, widescreen]">
					<iframe src="<?php echo esc_url( $new_src ); ?>" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
				<?php
			}
		} elseif ( 'vimeo' === $video_type && $post_format_video_vimeo ) {
			// use preg_match to find iframe src.
			preg_match( '/src="(.+?)"/', $post_format_video_vimeo, $matches );
			if ( ! empty( $matches ) ) {
				$src = $matches[1];
				?>
			<div class="blog-entry-vimeo">
				<div class="js-video [vimeo, widescreen]">
					<iframe src="<?php echo esc_url( $src ); ?>" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
				<?php
			}
		}

		if ( ! is_single() ) :
			?>
		<div class="entry-title">
			<?php
			if ( 'youtube' === $video_type ) {
				$icon = 'fab fa-youtube';
			} elseif ( 'vimeo' === $video_type ) {
				$icon = 'fab fa-vimeo-square';
			} else {
				$icon = 'far fa-file-video';
			}
			?>
			<i class="<?php echo esc_attr( $icon ); ?>"></i> <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/entry_meta' ); ?>

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

	</div>

</article><!-- #post -->
