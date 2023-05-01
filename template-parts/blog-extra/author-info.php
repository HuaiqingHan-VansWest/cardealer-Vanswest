<?php // phpcs:ignore WPThemeReview.Templates.ReservedFileNamePrefix.ReservedTemplatePrefixFound
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

?>
<!-- Author Info -->
<div class="author-info port-post clearfix">
	<div class="author-avatar port-post-photo">
		<?php
		$author_bio_avatar_size = apply_filters( 'cardealer_author_bio_avatar_size', 170 );
		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		?>
	</div> <!-- .author-avatar -->
	<div class="author-details port-post-info">
		<?php
		$author_link_title = sprintf(
			/* translators: 1: Author Name */
			esc_html__( 'View all posts by %s', 'cardealer' ),
			esc_html( get_the_author() )
		);
		?>
		<h3 class="text-blue"><span><?php esc_html_e( 'Posted by:', 'cardealer' ); ?> </span><a title="<?php echo esc_attr( $author_link_title ); ?>" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></h3>
		<?php
		$author_id       = get_the_author_meta( 'ID' );
		$social_profiles = apply_filters( 'cardealer_user_profile_meta_fields_social_profiles', array() );
		?>
		<div class="author-links port-post-social pull-right">
			<?php
			$social_profiles_count = 0;
			$social_profile_links  = '';
			foreach ( $social_profiles as $profile_ids => $profile ) {
				$profile_icon = $profile['icon'];
				$profile_url  = get_user_meta( $author_id, $profile_ids, true );
				if ( $profile_url ) {
					$social_profiles_count++;
					$social_profile_links .= '<a href="' . esc_url( $profile_url ) . '" target="_blank" rel="noreferrer noopener"><i class="' . esc_attr( $profile_icon ) . '"></i></a>';
				}
			}
			if ( $social_profiles_count > 0 ) {
				?>
				<strong><?php esc_html_e( 'Follow on', 'cardealer' ); ?>:</strong>
				<?php
				if ( ! empty( $social_profile_links ) ) {
					echo wp_kses(
						$social_profile_links,
						array(
							'a' => array(
								'href'   => true,
								'target' => true,
								'rel' => true,
							),
							'i' => array(
								'class' => true,
							),
						)
					);
				}
			}
			?>
		</div><!-- .author-links -->
		<div class="author-description">
			<p><?php the_author_meta( 'description' ); ?></p>
		</div><!-- .author-description -->
	</div><!-- .author-details -->
</div><!-- .author-info    -->
