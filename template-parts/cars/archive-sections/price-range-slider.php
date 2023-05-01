<div class="price_slider_wrapper">
	<div class="price-slide">
		<div class="price">
			<input type="hidden" class="pgs-price-slider-min" name="min_price" value="<?php echo esc_attr( $args['pgs_min_price'] ); ?>" data-min="<?php echo esc_attr( $args['min'] ); ?>"/>
			<input type="hidden" class="pgs-price-slider-max" name="max_price" value="<?php echo esc_attr( $args['pgs_max_price'] ); ?>" data-max="<?php echo esc_attr( $args['max'] ); ?>" data-step="<?php echo esc_attr( $args['step'] ); ?>"/>
			<?php
			if ( ! isset( $args['filter_location'] ) || 'widget-vehicle-price-range' !== $args['filter_location'] ) {
				?>
				<label for="<?php echo esc_attr( $args['price_range_slider_id'] ); ?>"><?php echo esc_html__( 'Price:', 'cardealer' ); ?></label>
				<div id="<?php echo esc_attr( $args['price_range_slider_id'] ); ?>" class="dealer-slider-amount" class="amount"></div>
				<?php
			}
			?>
			<div id="<?php echo esc_attr( $args['price_slider_range_id'] ); ?>" class="slider-range range-slide-slider"></div>
		</div>
		<?php
		if ( isset( $args['filter_location'] ) && 'cars-top-filters-box' === $args['filter_location'] ) {
			?>
			<div class="range-btn-wrapper price-range-btn-wrapper">
				<button id="pgs_price_filter_btn-<?php echo esc_attr( $args['price_range_instance'] ); ?>" class="pgs-price-filter-btn button"><?php esc_html_e( 'Filter', 'cardealer' ); ?></button>
			</div>
			<?php
		}
		if ( isset( $args['filter_location'] ) && 'widget-vehicle-price-range' === $args['filter_location'] ) {
			?>
			<div class="range-btn-wrapper price-range-btn-wrapper">
				<div class="dealer-slider-amount-wrapper">
					<label for="<?php echo esc_attr( $args['price_range_slider_id'] ); ?>"><?php echo esc_html__( 'Price:', 'cardealer' ); ?></label>
					<div id="<?php echo esc_attr( $args['price_range_slider_id'] ); ?>" class="dealer-slider-amount" class="amount"></div>
				</div>
				<button id="pgs_price_filter_btn-<?php echo esc_attr( $args['price_range_instance'] ); ?>" class="pgs-price-filter-btn button"><?php esc_html_e( 'Filter', 'cardealer' ); ?></button>
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php
