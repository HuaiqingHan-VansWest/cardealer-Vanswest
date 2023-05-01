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
$comments_count          = wp_count_comments( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-2">
		<?php
		// Check if video type is html5 and have rows.
		if ( 'html5' === $video_type ) {
			if ( function_exists( 'have_rows' ) && have_rows( 'post_format_video_html5' ) ) {
				?>
				<div class="blog-image blog-entry-html-video audio-video">
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
						<video style="width:100%;height:100%;" id="player1"<?php if ( ! empty( $cover_img ) ) { ?> poster="<?php echo esc_url( $cover_img ); ?>"<?php } ?> controls="controls" preload="none">
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
					<div class="date-box">
						<span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span>
					</div>
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
				<div class="blog-image blog-entry-you-tube">
					<div class="js-video [youtube, widescreen]">
						<iframe src="<?php echo esc_url( $new_src ); ?>" frameborder="0" allowfullscreen></iframe>
					</div>
					<div class="date-box">
						<span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span>
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
				<div class="blog-image blog-entry-vimeo">
					<div class="js-video [vimeo, widescreen]">
						<iframe src="<?php echo esc_url( $src ); ?>" frameborder="0" allowfullscreen></iframe>
					</div>
					<div class="date-box">
						<span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span>
					</div>
				</div>
				<?php
			}
		}

		if ( ! is_single() ) {
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
			<?php
		}
		?>
	</div>

</article><!-- #post -->
