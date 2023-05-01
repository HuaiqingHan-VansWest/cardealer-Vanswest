<?php
/**
 * Cardealer comment
 *
 * @package cardealer
 */

if ( ! function_exists( 'cardealer_comments' ) ) :
	/**
	 * Comments
	 *
	 * @param string $comment .
	 * @param string $args .
	 * @param string $depth .
	 */
	function cardealer_comments( $comment, $args, $depth ) {

		switch ( $comment->comment_type ) :
			case 'pingback':
			case 'trackback':
				// Display trackbacks differently than normal comments.
				?>
			<li <?php comment_class( 'comments-1' ); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php esc_html_e( 'Pingback:', 'cardealer' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'cardealer' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
				break;

			default:
				// Proceed with normal comments.
				global $post;
				?>
			<li <?php comment_class( 'comments-1' ); ?> id="li-comment-<?php comment_ID(); ?>">
				<div id="comment-<?php comment_ID(); ?>" class="comment">
					<div class="comments-photo">
						<?php echo get_avatar( $comment, 100 ); ?>
					</div>
					<div class="comments-info">
						<header class="comment-meta comment-author vcard">
							<?php
							printf(
								'<h4 class="text-blue">%1$s %2$s <span class="comment-date">%3$s</span></h4>',
								get_comment_author_link(),
								// If current post author is also comment author, make it known visually.
								( $comment->user_id === $post->post_author ) ? '<span>(' . esc_html__( 'Post author', 'cardealer' ) . ')</span>' : '',
								sprintf(
									'<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									esc_html( get_comment_time( 'c' ) ),
									/* translators: 1: date, 2: time */
									sprintf( esc_html__( '%1$s at %2$s', 'cardealer' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) )
								)
							);
							?>
							<div class="reply port-post-social pull-right">
								<?php
								comment_reply_link(
									array_merge(
										$args,
										array(
											'reply_text' => esc_html__( 'Reply', 'cardealer' ),
											'after'      => '',
											'depth'      => $depth,
											'max_depth'  => $args['max_depth'],
										)
									)
								);
								?>
							</div><!-- .reply -->
						</header><!-- .comment-meta -->

							<?php if ( '0' === $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'cardealer' ); ?></p>
						<?php endif; ?>

						<div class="comment-content comment">
							<?php comment_text(); ?>
							<?php edit_comment_link( esc_html__( 'Edit', 'cardealer' ), '<p class="edit-link">', '</p>' ); ?>
						</div><!-- .comment-content -->
					</div>
				</div><!-- #comment-## -->
				<?php
				break;
		endswitch; // end comment_type check.
	}
endif;

if ( ! function_exists( 'cardealer_move_comment_form_below' ) ) {
	/**
	 * Move comments fields in last
	 *
	 * @param string $fields .
	 */
	function cardealer_move_comment_form_below( $fields ) {
		$comment_field = $fields['comment'];
		$cookies_field = isset( $fields['cookies'] ) ? $fields['cookies'] : '';
		unset( $fields['comment'] );
		if ( isset( $fields['cookies'] ) ) {
			unset( $fields['cookies'] );
		}
		$fields['comment'] = $comment_field;
		$fields['cookies'] = $cookies_field;
		return $fields;
	}
}
add_filter( 'comment_form_fields', 'cardealer_move_comment_form_below' );
