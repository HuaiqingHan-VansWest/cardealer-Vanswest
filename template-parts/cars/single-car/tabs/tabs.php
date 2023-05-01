<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see cardealer_vehicle_tabs()
 */
$vehicle_tabs = apply_filters( 'cardealer_vehicle_tabs', array() );

if ( ! empty( $vehicle_tabs ) ) : ?>

	<?php
	if ( ! wp_is_mobile() ) {
		?>

		<div id="tabs">
			<?php do_action( 'cardealer_vehicle_before_tabs' ); ?>

			<ul class="nav nav-tabs tabs" role="tablist">
				<?php
				$tab_sr = 1;
				foreach ( $vehicle_tabs as $key => $vehicle_tab ) {
					$title = apply_filters( 'cardealer_vehicle_tab_title_' . $key, $vehicle_tab['title'], $key );
					$icon  = isset( $vehicle_tab['icon'] ) && ! empty( $vehicle_tab['icon'] ) ? $vehicle_tab['icon'] : 'fas fa-list';
					$icon  = apply_filters( 'cardealer_vehicle_tab_icon_' . $key, $icon, $key );
					?>
					<li role="presentation" class="<?php echo esc_attr( $key ); ?>_tab<?php echo esc_attr( 1 === $tab_sr ? ' active' : '' ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>" aria-controls="tab-<?php echo esc_attr( $key ); ?>" role="tab" data-toggle="tab">
							<i aria-hidden="true" class="<?php echo esc_attr( $icon ); ?>"></i>&nbsp;<?php echo esc_html( $title ); ?>
						</a>
					</li>
					<?php
					$tab_sr++;
				}
				?>
			</ul>

			<div class="tab-content">
			<?php
			$tab_sr = 1;
			foreach ( $vehicle_tabs as $key => $vehicle_tab ) {
				?>
				<div role="tabpanel" class="tab-pane<?php echo esc_attr( 1 === $tab_sr ? ' active' : '' ); ?>" id="tab-<?php echo esc_attr( $key ); ?>" class="tabcontent" <?php echo esc_attr( 1 === $tab_sr ? ' active' : '' ); ?>>
					<?php
					if ( isset( $vehicle_tab['callback'] ) && is_callable( $vehicle_tab['callback'] ) ) {
						call_user_func( $vehicle_tab['callback'], $key, $vehicle_tab );
					}
					?>
				</div>
				<?php
				$tab_sr++;
			}
			?>
			</div>
			<?php do_action( 'cardealer_vehicle_after_tabs' ); ?>
		</div>
		<?php
	} else {
		?>
		<div class="panel-group" id="tab-accordion" role="tablist" aria-multiselectable="true">
			<?php
			$tab_sr = 1;
			foreach ( $vehicle_tabs as $key => $vehicle_tab ) {
				$title = apply_filters( 'cardealer_vehicle_tab_title_' . $key, $vehicle_tab['title'], $key );
				$icon  = isset( $vehicle_tab['icon'] ) && ! empty( $vehicle_tab['icon'] ) ? $vehicle_tab['icon'] : 'fas fa-list';
				$icon  = apply_filters( 'cardealer_vehicle_tab_icon_' . $key, $icon, $key );
				$in_class = ( $tab_sr == 1 ) ? 'in' : '';
				?>
				<div class="panel panel-default">
					<div class="panel-heading <?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( 1 === $tab_sr ? 'active' : '' ); ?>" role="tab" id="tab-<?php echo esc_attr( $key ); ?>">
						<h4 class="panel-title">
							<a role="button" data-toggle="collapse" data-parent="#tab-accordion" href="#tab-title-<?php echo esc_attr( $key ); ?>" aria-expanded="false" aria-controls="tab-title-<?php echo esc_attr( $key ); ?>">
								<i aria-hidden="true" class="<?php echo esc_attr( $icon ); ?>"></i>&nbsp;<?php echo esc_html( $title ); ?>
							</a>
						</h4>
					</div>
					<div id="tab-title-<?php echo esc_attr( $key ); ?>" class="panel-collapse collapse <?php echo esc_attr($in_class); ?>" role="tabpanel" aria-labelledby="tab-<?php echo esc_attr( $key ); ?>">
						<div class="panel-body">
							<?php
							if ( isset( $vehicle_tab['callback'] ) ) {
								call_user_func( $vehicle_tab['callback'], $key, $vehicle_tab );
							}
							?>
						</div>
					</div>
				</div>
				<?php
				$tab_sr++;
			}
			?>
		</div>
		<?php
	}
endif;
