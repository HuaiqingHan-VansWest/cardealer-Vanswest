<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	if ( have_comments() ) {
		?>
		<h3 class="comments comments-title">
			<?php
			$cardealer_comment_count = get_comments_number();
			if ( '1' === (string) $cardealer_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html_x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'cardealer' ),
					esc_html( get_the_title() )
				);
			} else {
				printf(
					esc_html(
						/* translators: 1: number of comments, 2: post title */
						_nx(
							'%1$s Reply to &ldquo;%2$s&rdquo;',
							'%1$s Replies to &ldquo;%2$s&rdquo;',
							$cardealer_comment_count,
							'comments title',
							'cardealer'
						)
					),
					esc_html( number_format_i18n( $cardealer_comment_count ) ),
					esc_html( get_the_title() )
				);
			}
			?>
		</h3>

		<nav class="comment-nav">
			<ul class="clearfix">
				<li class="pull-left"><?php previous_comments_link(); ?></li>
				<li class="pull-right"><?php next_comments_link(); ?></li>
			</ul>
		</nav>

		<ol class="commentlist">
			<?php
			wp_list_comments(
				array(
					'callback' => 'cardealer_comments',
					'style'    => 'ol',
				)
			);
			?>
		</ol>

		<nav class="comment-nav">
			<ul class="clearfix">
				<li class="pull-left"><?php previous_comments_link(); ?></li>
				<li class="pull-right"><?php next_comments_link(); ?></li>
			</ul>
		</nav>
		<?php
	}

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?>
		<p class='comment_close'><?php echo esc_html__( 'Comments are closed', 'cardealer' ); ?></p>
		<?php
	}

	if ( comments_open() ) {
		?>
		<section class="respond-form">
			<?php
			$comment_name    = esc_html__( 'Name', 'cardealer' );
			$comment_email   = esc_html__( 'Email', 'cardealer' );
			$comment_website = esc_html__( 'Website', 'cardealer' );
			$comment_comment = esc_html__( 'Comment', 'cardealer' );

			$req                 = get_option( 'require_name_email' );
			$aria_req            = ( $req ? " aria-required='true'" : '' );
						$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
			$comments_args       = array(
				'class_form'          => 'comment-form contact-form',
				'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title text-blue">',
				'comment_notes_after' => '', // remove "Text or HTML to be displayed after the set of comment fields".
				'class_submit'        => 'submit button pull-left',
				'fields'              => apply_filters(
					'comment_form_default_fields',
					array(
						'author'  => '<div class="section-field comment-form-author">' .
							'<input id="author" class="placeholder" placeholder="' . $comment_name . '*" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'</div>',
						'email'   => '<div class="section-field comment-form-email">' .
							'<input id="email" class="placeholder" placeholder="' . $comment_email . '*" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
							'</div>',
						'url'     => '<div class="section-field comment-form-url">' .
							'<input id="url" name="url" placeholder="' . $comment_website . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /> ' .
							'</div>',
						'cookies' => '<div class="section-field comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
										'<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'cardealer' ) . '</label></div>',
					)
				),
				'comment_field'       => '<div class="section-field textarea comment-form-comment">' .
					'<textarea id="comment" class="input-message placeholder" name="comment" placeholder="' . esc_attr( $comment_comment ) . '" cols="45" rows="8" aria-required="true"></textarea>' .
					'</div>',
			);
			comment_form( $comments_args );
			// If registration required and not logged in.
			?>
		</section>
		<?php
	} // if you delete this the sky will fall on your head
	?>
</div>
