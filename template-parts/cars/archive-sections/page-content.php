<div class="listing-page--page-content">
	<?php
	$inventory_page_edit_mode = function_exists( 'cardealer_is_inventory_page_edit_mode' ) ? cardealer_is_inventory_page_edit_mode() : false;
	if ( $inventory_page_edit_mode ) {
		?>
		<div id="content" role="main">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
		<?php
	} else {
		if ( isset( $args['inv_page_content'] ) && ! empty( $args['inv_page_content'] ) ) {
			$page_built_with  = cardealer_post_edited_with( $args['inv_page_id'] );

			$content = $args['inv_page_content'];

			$content = apply_filters( 'cd_' . $args['layout'] . '_inv_style_content', $content, $args['inv_page_id'], $args['layout'] );
			$content = apply_filters( 'cardealer_listing_page__page_content', $content, $args['inv_page_id'], $args['layout'] );

			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );

			if ( 'elementor' === $page_built_with ) {
				$has_css = false;

				/**
				 * CSS Print Method Internal and Exteral option support for Header and Footer Builder.
				 */
				if ( ( 'internal' === get_option( 'elementor_css_print_method' ) ) || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
					$has_css = true;
				}
				echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $args['inv_page_id'], $has_css );
			} elseif ( 'wpbakery' === $page_built_with ) {
				echo do_shortcode( $content );
			}

			if ( ! empty( $content ) ) {
				$cd_inv_content = 'cd-content';
			} else {
				$cd_inv_content = 'cd-no-content';
			}
		}
	}
	?>
</div>
