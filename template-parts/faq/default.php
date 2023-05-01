<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

$faq_type    = get_post_meta( get_the_ID(), 'faq_type', true );
$faq_type    = ( ! empty( $faq_type ) ) ? $faq_type : 'all';
$faq_cat_ids = '';

if ( 'all' === $faq_type ) {
	$faq_cat_ids = 'all';
} else {
	$selected_category = get_post_meta( get_the_ID(), 'select_category', true );
	$faq_cat_ids = ( is_array( $selected_category ) && ! empty( $selected_category ) ) ? $selected_category : array();
}

$faq_tabs_data = cardealer_get_faq_tabs_data( $faq_cat_ids );

if ( ! empty( $faq_tabs_data ) ) {
	?>
	<div id="<?php echo esc_attr( $faq_tabs_data['tabs_id'] ); ?>" class="cardealer-tabs tabs_wrapper">
		<?php
		if ( (int) $faq_tabs_data['tab_counts' ] > 1 ) {
			?>
			<ul class="tabs text-center nav nav-tabs">
				<?php
				$tab_sr = 1;
				foreach ( $faq_tabs_data['faq_tabs' ] as $faq_k => $faq_data ) {
					$activ       = ( 1 === $tab_sr ) ? 'active' : '';
					$tab_item_id = $faq_data['tab_item_id'];
					?>
					<li role="presentation" class="<?php echo esc_attr( $activ ); ?>">
						<a href="#<?php echo esc_attr( $tab_item_id ); ?>" aria-controls="<?php echo esc_attr( $tab_item_id ); ?>" role="tab" data-toggle="tab">
							<span aria-hidden="true"></span><?php echo esc_html( $faq_data['title'] );?>
						</a>
					</li>
					<?php
					$tab_sr++;
				}
				?>
			</ul>
			<?php
		}
		$tab_sr = 1;
		?>
		<div class="tab-content">
			<?php
			foreach ( $faq_tabs_data['faq_tabs' ] as $faq_k => $faq_data ) {
				$tab_item_id = $faq_data['tab_item_id'];
				?>
				<div role="tabpanel" id="<?php echo esc_attr( $tab_item_id ); ?>" class="tabcontent accordion faq-accordion fade tab-pane <?php echo esc_attr( 1 === $tab_sr ? ' active in' : '' ); ?>">
					<?php
					// The Query.
					$faq_query = $faq_data['query'];
					while ( $faq_query->have_posts() ) {
						$faq_query->the_post();
						?>
						<div class="accordion-title">
							<a href="#"><?php the_title(); ?></a>
						</div>
						<div class="accordion-content">
							<?php the_content(); ?>
						</div>
						<?php
					}
					wp_reset_postdata();
					?>
				</div>
				<?php
				$tab_sr++;
			}
			?>
		</div>
	</div>
	<?php
}
