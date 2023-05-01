<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options;

if ( ! isset( $car_dealer_options['pdf_brochure_status'] ) || '1' !== $car_dealer_options['pdf_brochure_status'] ) {
	return;
}

$file_id = get_post_meta( get_the_ID(), 'pdf_file', true );

if ( empty( $file_id ) ) {
	return;
}

if ( ! wp_attachment_is( 'pdf', $file_id ) ) {
	return;
}

$pdf_file_url = wp_get_attachment_url( $file_id );
?>
<li><a href="<?php echo esc_url( $pdf_file_url ); ?>" download><i class="far fa-file-pdf"></i><?php echo esc_html__( 'PDF Brochure', 'cardealer' ); ?></a></li>
