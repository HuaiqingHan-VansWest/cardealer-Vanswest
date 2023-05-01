<?php
/**
 * Template part to display single car share.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $car_dealer_options, $post;

$blog_layout                   = cardealer_get_blog_layout();
$share_medias                  = cardealer_get_social_share_links( $post );
$cardealer_share_title_enabled = true;
$social_share_label            = ( isset( $car_dealer_options['social_share_label'] ) && ! empty( $car_dealer_options['social_share_label'] ) ) ? $car_dealer_options['social_share_label'] : esc_html__( 'Share This', 'cardealer' );
$social_share_label            = ( isset( $args['label'] ) && ! empty( $args['label'] ) ) ? $args['label'] : $social_share_label;
$social_share_inline_links     = $car_dealer_options['social_share_inline_links'];
$social_share_inline_links     = filter_var( $social_share_inline_links, FILTER_VALIDATE_BOOLEAN );
$cardealer_share_inline_links  = 3;

if ( ( is_home() || is_archive() ) && 'post' === get_post_type() && 'masonry' === $blog_layout ) {
	$cardealer_share_title_enabled = false;
}

$cardealer_share_title_enabled = apply_filters( 'cardealer_share_title_enabled', $cardealer_share_title_enabled );

if ( empty( $post ) || empty( $share_medias ) ) {
	return;
}

$native_share_params = PGS_Social_Share::get_native_share_params( $post );

$post_id       = $post->ID;
$share_title   = get_the_title();
$share_link    = get_permalink();
$share_image   = cardealer_get_single_image_url();
$mobile_native = false;
$cardealer_share_classes = array(
	'cardealer-share',
	'details-social',
	'details-weight',
	'share',
	'cardealer-share-device-' . ( wp_is_mobile() ? 'mobile' : 'desktop' ),
);

$native_share_enabled = cardealer_mobile_native_share_enabled();

if ( $native_share_enabled ) {
	$cardealer_share_classes[] = 'cardealer-share-native-share-enabled';
}

if ( $social_share_inline_links ) {
	$cardealer_share_classes[] = 'cardealer-share-has-inline-links';
}

$modal_id = "cardealer-share-modal-{$post_id}";
?>
<div class="<?php cardealer_class_generator( $cardealer_share_classes ); ?>">
	<div class="cardealer-share-action">
		<?php
		if ( $cardealer_share_title_enabled ) {
			?>
			<h6 class="cardealer-share-action-label uppercase"><?php echo esc_html( $social_share_label ); ?></h6>
			<?php
		}
		?>
		<div class="cardealer-share-action-item cardealer-share-action-more">
			<a class="cardealer-share-action-link" href="javascript:void(0)" data-modal_id="<?php echo esc_attr( $modal_id );?>" title="Click to share" data-native_params="<?php echo esc_attr( wp_json_encode( $native_share_params ) ); ?>"><i class="fa-solid fa-share-nodes"></i></a>
		</div>
		<?php
		if ( $social_share_inline_links && ! empty( $cardealer_share_inline_links ) ) {
			?>
			<div class="cardealer-share-action-item cardealer-share-action-inline-links">
				<div class="cardealer-share-inline-links">
					<ul class="cardealer-share-items single-share-box mk-box-to-trigger" data-title="<?php echo esc_attr( $share_title ); ?>" data-url="<?php echo esc_url( $share_link ); ?>" data-image="<?php echo esc_url( $share_image ); ?>">
						<?php
						$inline_links_sr = 0;
						foreach ( $share_medias as $share_media_k => $share_media_v ) {
							$inline_links_sr++;
							$item_class = "cardealer-share-item cardealer-share-item-{$share_media_k}";
							$link_class = "cardealer-share-link cardealer-share-link-{$share_media_k} {$share_media_k}-share";
							?><li class="<?php echo esc_attr( $item_class ); ?>">
								<a href="<?php echo esc_attr( $share_media_v['share_link'] ); ?>" class="<?php echo esc_attr( $link_class ); ?>" target="_blank">
									<?php
									if ( isset( $share_media_v['icon_url'] ) ) {
										?><img src="<?php echo esc_attr( $share_media_v['icon_url'] ); ?>" /><?php
									}elseif ( isset( $share_media_v['icon_class'] ) ) {
										?><i class="<?php echo esc_attr( $share_media_v['icon_class'] ); ?>"></i><?php
									}
									?>
								</a>
							</li><?php
							if ( $cardealer_share_inline_links === $inline_links_sr ) {
								break;
							}
						}
						?>
					</ul>
				</div><!-- .cardealer-share-links -->
			</div>
			<?php
		}
		?>
	</div>
	<!-- Modal -->
	<div class="cardealer-share-modal modal fade" id="<?php echo esc_attr( $modal_id );?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><?php esc_html_e( 'Share This', 'cardealer' ); ?></h4>
				</div>
				<div class="modal-body">
					<div class="cardealer-share-popup">
						<div class="cardealer-share-links">
							<ul class="cardealer-share-items single-share-box mk-box-to-trigger" data-title="<?php echo esc_attr( $share_title ); ?>" data-url="<?php echo esc_url( $share_link ); ?>" data-image="<?php echo esc_url( $share_image ); ?>">
								<?php
								$links_sr = 0;
								foreach ( $share_medias as $share_media_k => $share_media_v ) {
									$links_sr++;
									// if ( ! empty( $cardealer_share_inline_links ) && $links_sr <= $cardealer_share_inline_links ) {
										// continue;
									// }
									$item_class = "cardealer-share-item cardealer-share-item-{$share_media_k}";
									$link_class = "cardealer-share-link cardealer-share-link-{$share_media_k} {$share_media_k}-share";
									?><li class="<?php echo esc_attr( $item_class ); ?>">
										<a href="<?php echo esc_attr( $share_media_v['share_link'] ); ?>" class="<?php echo esc_attr( $link_class ); ?>" target="_blank">
											<?php
											if ( isset( $share_media_v['icon_url'] ) ) {
												?><img src="<?php echo esc_attr( $share_media_v['icon_url'] ); ?>" /><?php
											}elseif ( isset( $share_media_v['icon_class'] ) ) {
												?><i class="<?php echo esc_attr( $share_media_v['icon_class'] ); ?>"></i><?php
											}
											?>
										</a>
									</li><?php
								}
								?>
							</ul>
						</div><!-- .cardealer-share-links -->
					</div><!-- .cardealer-share-popup -->
				</div>
			</div>
		</div>
	</div>
</div><!-- .share -->
