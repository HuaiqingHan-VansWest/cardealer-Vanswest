<?php
$social_profiles = $args['social_profiles'];
?>
<div class="social">
	<ul>
		<?php
		if ( ! empty( $social_profiles ) ) {
			foreach ( $social_profiles as $key => $profile_data ) {
				?>
				<li>
					<?php
					echo sprintf(
						'<a class="%1$s" href="%2$s" target="_blank">%3$s<i class="%4$s"></i></a>',
						esc_attr( $key ),
						esc_url( $profile_data['profile_url'] ),
						esc_html( $profile_data['title'] ),
						esc_attr( $profile_data['icon_class'] )
					);
					?>
				</li>
				<?php
			}
		}
		?>
	</ul>
</div>
