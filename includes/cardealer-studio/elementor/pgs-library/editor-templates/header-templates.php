<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-pgses-header-templates">
	<div class="elementor-templates-modal__header">
		<div class="elementor-templates-modal__header__logo-area">
			<div class="elementor-templates-modal__header__logo">
				<span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper" style="background-color: {{{ data.icon_bg_color }}}">
					<img src="{{{ data.icon }}}">
				</span>
				<span class="elementor-templates-modal__header__logo__title">{{{ data.title }}}</span>
			</div>
		</div>
		<div class="elementor-templates-modal__header__menu-area"></div>
		<div class="elementor-templates-modal__header__items-area">
			<# if ( data.closeType ) { #>
				<div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--{{{ data.closeType }}} elementor-templates-modal__header__item">
					<# if ( 'skip' === data.closeType ) { #>
					<span><?php echo esc_html__( 'Skip', 'cardealer' ); ?></span>
					<# } #>
					<i class="eicon-close" aria-hidden="true" title="<?php echo esc_html__( 'Close', 'cardealer' ); ?>"></i>
					<span class="elementor-screen-only"><?php echo esc_html__( 'Close', 'cardealer' ); ?></span>
				</div>
			<# } #>
			<div id="elementor-template-library-header-tools">
				<div id="elementor-template-library-header-actions"></div>
			</div>
		</div>
	</div>
</script>
