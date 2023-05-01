<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>    
	<?php
	the_content(
		sprintf(
			wp_kses(
				/* translators: 1: Post Title */
				__( 'Continue reading <span class="screen-reader-text">"%s"</span>', 'cardealer' ),
				array(
					'span' => array(
						'class' => true,
					),
				)
			),
			get_the_title()
		)
	);
	?>
</article><!-- #post -->
