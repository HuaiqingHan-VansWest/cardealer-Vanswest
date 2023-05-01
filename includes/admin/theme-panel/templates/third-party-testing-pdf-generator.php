<?php
/**
 * Pdf generator.
 *
 * @package Cardealer
 * @version 1.0.0
 */

$vehicles = cardealer_debug_get_recent_vehicles();
?>
<div class="cardealer-debug-pdf-generator-wrap">
	<div class="cardealer-debug-pdf-generator-field">
		<div class="cardealer-debug-pdf-generator-vehicle">
			<label for="cardealer-debug-pdf-generator-select-vehicle"><?php esc_html_e( 'Vehicle', 'cardealer' ); ?></label>
			<select id="cardealer-debug-pdf-generator-select-vehicle" class="regular-text" name="vehicle">
				<?php
				if ( ! empty( $vehicles ) ) {
					foreach ( $vehicles as $vehicle_id => $vehicle_title ) {
						?>
						<option value="<?php echo esc_attr( $vehicle_id ); ?>">
							<?php echo esc_html( "{$vehicle_title} ({$vehicle_id})" ); ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="cardealer-debug-pdf-generator-field">
		<div class="cardealer-debug-pdf-generator-html-template">
			<label for="cardealer-debug-pdf-generator-select-html-template"><?php esc_html_e( 'PDF Template', 'cardealer' ); ?></label>
			<select id="cardealer-debug-pdf-generator-select-html-template" class="regular-text" name="html-template">
				<?php
				if ( function_exists( 'have_rows' ) ) {
					if ( have_rows( 'html_templates', 'option' ) ) {
						while ( have_rows( 'html_templates', 'option' ) ) {
							the_row();

							$templates_title = get_sub_field( 'templates_title' );
							?>
							<option value="<?php echo esc_attr( $templates_title ); ?>"><?php echo esc_html( $templates_title ); ?></option>
							<?php
						}
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="cardealer-debug-pdf-generator-field">
		<button type="button" id="cardealer-debug-pdf-generator-check-pdf" class="cardealer-debug-pdf-generator-check-pdf button-primary button-large"><?php echo esc_html__( 'Check PDF Generator', 'cardealer' ); ?></button>
	</div>
	<div class="cardealer-debug-response"></div>
</div>
