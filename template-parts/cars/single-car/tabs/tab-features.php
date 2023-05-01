<div class="masonry-main cd-vehicle-features">
	<?php
	$child_features_and_options = wp_get_post_terms( get_the_ID(), 'car_features_options' );
	$features_and_options       = $child_features_and_options;

	// check all are parent features.
	$parent_found = 0;

	foreach ( $features_and_options as $feature ) {
		if ( 0 !== $feature->parent ) {
			$parent_found = 1;
			break;
		}
	}

	if ( 1 === $parent_found ) {
		?>
		<div class="tab-isotope-2 masonry">
			<div class="grid-sizer"></div>
			<?php
			$all_featuer_array = array();
			$display_array     = array();

			foreach ( $features_and_options as $feature ) {
				if ( 0 === $feature->parent ) {
					$term_id = $feature->term_id;
					$head    = 0;
					foreach ( $child_features_and_options as $key => $features ) {
						// Parent feature and child feature.
						if ( 0 !== $features->parent && $term_id === $features->parent ) {
							if ( 0 === $head ) {
								echo "<div class='col-sm-4 masonry-item'>"; // start div.
									echo '<h4>' . esc_html( $feature->name ) . '</h4>';// heading.
									$head            = 1;
									$display_array[] = $feature->term_id;
									echo "<ul class='list-style-1'>";
							}
							?>
							<li><i class='fas fa-check'></i><?php echo esc_html( $features->name ); ?></li>
							<?php
							$display_array[] = $features->term_id;
						}
						if ( ( count( $child_features_and_options ) - 1 ) === $key && 0 !== $head ) {
							echo '</ul>';
							echo '</div>'; // close div.
						}
					}
				}
				$all_featuer_array[] = $feature->term_id;
			}

			// rest of features.
			$class_parent       = '';
			$remaining_features = array_diff( $all_featuer_array, $display_array );

			if ( count( $child_features_and_options ) !== count( $remaining_features ) ) {
				$class_parent = 'masonry-item';
			}

			if ( ! empty( $remaining_features ) ) {
				foreach ( $remaining_features as $r_feature ) {
					?>
					<div class="col-sm-4 <?php echo esc_attr( $class_parent ); ?>">
						<ul class='list-style-1'>
							<?php
							$feat_terms = get_term_by( 'id', $r_feature, 'car_features_options' );
							if ( ! empty( $feat_terms ) && isset( $feat_terms->name ) ) {
								?>
								<li><i class='fas fa-check'></i><?php echo esc_html( $feat_terms->name ); ?></li>
								<?php
							}
							?>
						</ul>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	} else { // if all child features, no parents / all are at same level.
		?>
		<ul class="list-style-1 list-col-3">
			<?php
			foreach ( $features_and_options as $feature ) {
				?>
				<li><i class='fas fa-check'></i><?php echo esc_html( $feature->name ); ?></li>
				<?php
			}
			?>
		</ul>
		<?php
	}
	?>
</div>
