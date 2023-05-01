<?php
$social_profiles = $args['social_profiles'];
foreach ( $social_profiles as $key => $profile_data ) {
	echo sprintf(
		'<a class="%1$s" href="%2$s" target="_blank" style="--brand-color:%5$s;">%3$s<i class="%4$s"></i></a>',
		esc_attr( $key ),
		esc_url( $profile_data['profile_url'] ),
		esc_html( $profile_data['title'] ),
		esc_attr( $profile_data['icon_class'] ),
		esc_attr( $profile_data['brand_color'] )
	);
}
