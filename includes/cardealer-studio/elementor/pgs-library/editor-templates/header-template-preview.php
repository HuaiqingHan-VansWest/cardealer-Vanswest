<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-pgses-header-template-preview">
	<div class="elementor-templates-modal__header">
		<div class="elementor-templates-modal__header__logo-area">
			<div id="elementor-template-library-header-preview-back">
				<i class="eicon-" aria-hidden="true"></i>
				<span><?php echo esc_html__( 'Back to Library', 'cardealer' ); ?></span>
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
				<div id="elementor-template-library-header-preview">
					<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
						<a class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button" data-template-id="{{{ data.tmpl_id }}}">
							<i class="eicon-file-download" aria-hidden="true"></i>
							<span class="elementor-button-title"><?php echo esc_html__( 'Insert', 'cardealer' ); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
