<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */
$has_post_thumbnail = has_post_thumbnail();
$blog2_class        = array( 'blog-2' );
$comments_count     = wp_count_comments( get_the_ID() );

if ( ! $has_post_thumbnail ) {
	$blog2_class[] = 'blog-no-image';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="<?php cardealer_class_generator( $blog2_class ); ?>">
		<?php
		// $excerpt = get_the_excerpt();
		// $excerpt = cdhl_shortenString( $excerpt, 350, false, true );
		?>
		<div class="blog-image">
			<?php
			if ( $has_post_thumbnail ) {
				if ( cardealer_lazyload_enabled() ) {
					?>
					<img alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php echo esc_url( LAZYLOAD_IMG ); ?>" data-src="<?php the_post_thumbnail_url( 'cardealer-blog-thumb' ); ?>" class="cardealer-lazy-load img-responsive"/>
					<?php
				} else {
					?>
					<img alt="<?php echo esc_attr( get_the_title() ); ?>" src="<?php the_post_thumbnail_url( 'cardealer-blog-thumb' ); ?>" class="img-responsive"/>
					<?php
				}
			}
			?>
			<div class="date-box"><span><?php echo sprintf( '%1$s', esc_html( get_the_date( 'M Y' ) ) ); ?></span></div>
		</div>
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
</article><!-- #post-## -->
