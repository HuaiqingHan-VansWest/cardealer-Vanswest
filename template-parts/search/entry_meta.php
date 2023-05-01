<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( is_single() ) {
	$blog_metas = isset( $car_dealer_options['single_metas'] ) ? $car_dealer_options['single_metas'] : '';
	if ( empty( $blog_metas ) ) {
		$blog_metas = array(
			'date'       => '1',
			'author'     => '1',
			'categories' => '1',
			'tags'       => '1',
			'comments'   => '1',
		);
	}
} else {
	$blog_metas = isset( $car_dealer_options['blog_metas'] ) ? $car_dealer_options['blog_metas'] : '';
	if ( empty( $blog_metas ) ) {
		$blog_metas = array(
			'date'       => '1',
			'author'     => '1',
			'categories' => '1',
			'tags'       => '1',
			'comments'   => '1',
		);
	}
}
?>
<div class="entry-meta">
	<?php
	foreach ( $blog_metas as $blog_meta_k => $blog_meta_v ) {
		if ( 'author' === $blog_meta_k && ! empty( $blog_meta_v ) ) {
			echo sprintf(
				'<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author"><i class="fas fa-user"></i> %3$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr(
					sprintf(
						/* translators: 1: Author Name */
						esc_html__( 'View all posts by %s', 'cardealer' ),
						get_the_author()
					)
				),
				get_the_author()
			);
		}
		if ( 'comments' === $blog_meta_k && ! empty( $blog_meta_v ) && comments_open() ) {
			comments_popup_link( '<i class="far fa-comments"></i> <span class="leave-comment">' . esc_html__( 'No comments', 'cardealer' ) . '</span>', '<i class="far fa-comments"></i> <span class="leave-comment">' . esc_html__( '1 Comment', 'cardealer' ) . '</span>', '<i class="far fa-comments"></i> <span class="leave-comment">' . esc_html__( '% Comments', 'cardealer' ) . '</span>' );
		}

		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( ', ' );

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '<i class="fas fa-tags"></i>', ', ' );

		if ( 'categories' === $blog_meta_k && ! empty( $blog_meta_v ) && ! empty( $categories_list ) ) {
			?>
			<span class="entry-meta-categories"><i class="fas fa-folder-open"></i>&nbsp;<?php echo wp_kses_post( $categories_list ); ?></span>
			<?php
		}

		if ( 'tags' === $blog_meta_k && ! empty( $blog_meta_v ) && has_tag() ) {
			?>
			<span class="entry-meta-tags">
				<?php echo wp_kses_post( $tags_list ); ?>
			</span>
			<?php
		}
		if ( 'date' === $blog_meta_k && ! empty( $blog_meta_v ) ) {
			echo sprintf(
				'<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s"><i class="fas fa-calendar-alt"></i> %4$s</time></a>',
				esc_url( get_permalink() ),
				esc_attr( get_the_time() ),
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);
		}
	}
	edit_post_link( '<i class="fas fa-pencil-alt"></i> ' . esc_html__( 'Edit', 'cardealer' ), '', '' );
	?>
</div>
