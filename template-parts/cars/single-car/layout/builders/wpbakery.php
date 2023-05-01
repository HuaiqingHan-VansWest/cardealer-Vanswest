<?php
$template_post = get_post( $args['template_id'] );
if ( $template_post ) {
	echo do_shortcode( get_the_content( '', '', $args['template_id'] ) );

	$template_css = get_post_meta( $args['template_id'], '_wpb_shortcodes_custom_css', true );
	if ( ! empty( $template_css ) ) {
		?>
		<style type="text/css" data-type="vc_shortcodes-custom-css"><?php echo wp_strip_all_tags( $template_css ); ?></style>
		<?php
	}
	$template_custom_css = get_metadata( 'post', $args['template_id'], '_wpb_template_custom_css', true );
	if ( ! empty( $template_custom_css ) ) {
		?>
		<style type="text/css" data-type="vc_custom"><?php echo wp_strip_all_tags( $template_custom_css ); ?></style>
		<?php
	}
}
