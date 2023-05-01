<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$quote        = get_post_meta( get_the_ID(), 'quote', true );
$quote_author = get_post_meta( get_the_ID(), 'quote_author', true );
$author_link  = get_post_meta( get_the_ID(), 'author_link', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	if ( $quote ) {
		?>
		<div class="blog-entry-quote">
			<blockquote class="entry-quote">
					<i class="fas fa-quote-left"></i>
				<p><?php echo esc_html( $quote ); ?></p>
				<?php
				if ( $quote_author ) {
					$author_txt = '';
					?>
					<div class="quote-author text-right">
						<?php
						if ( $author_link ) {
							$author_txt .= '<a href="' . esc_url( $author_link ) . '">';
						}
						$author_txt .= "- $quote_author";
						if ( $author_link ) {
							$author_txt .= '</a>';
						}
						echo wp_kses(
							$author_txt,
							array(
								'a' => array(
									'href'   => true,
									'target' => true,
								),
							)
						);
						?>
					</div>
					<?php
				}
				?>
			</blockquote>
		</div>
		<?php
	}
	?>

	<?php if ( ! is_single() ) : ?>
	<div class="entry-title">
		<i class="fas fa-quote-left"></i> <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
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

</article><!-- #post-## -->
