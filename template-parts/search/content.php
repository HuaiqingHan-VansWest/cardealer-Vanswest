<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-title">
		<?php ( is_single() ) ? the_title( sprintf( '<h3 class="entry-title">' ), '</h3>' ) : the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
	</div>

	<?php get_template_part( 'template-parts/search/entry_meta' ); ?>

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
			?>
			<p><?php the_excerpt(); ?></p>
			<?php
		}
		?>
	</div>

	<?php get_template_part( 'template-parts/entry_footer' ); ?>

</article><!-- #post-## -->
