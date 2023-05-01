<?php
$elementor_instance = \Elementor\Plugin::instance();
$has_css            = false;

/**
 * CSS Print Method Internal and Exteral option support for Header and Footer Builder.
 */
if ( ( 'internal' === get_option( 'elementor_css_print_method' ) ) || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
	$has_css = true;
}
?>
<div class="row">
	<?php echo $elementor_instance->frontend->get_builder_content_for_display( $args['template_id'], $has_css ); ?>
</div>
