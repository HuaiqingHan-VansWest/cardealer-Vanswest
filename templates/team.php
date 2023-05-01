<?php
/**
 * Template Name: Team
 * Description: A Page Template that display team members.
 *
 * @package CarDealer
 * @author  Potenza Global Solutions
 * version 1.0.1
 */

get_header();

// team coding.
$team_layout = ( isset( $car_dealer_options['team_layout'] ) ) ? $car_dealer_options['team_layout'] : 'layout_1'; // Option inside theme option.

$page_specific_layout = ( function_exists( 'get_field' ) ) ? get_field( 'enable_custom_team_layout' ) : false;
if ( $page_specific_layout ) {
	$page_option_layout = get_field( 'team_layout' ); // Optin inside page.
	if ( ! empty( $page_option_layout ) && null !== $page_option_layout ) {
		$team_layout = $page_option_layout;
	}
}?>

<section class="our-team white-bg page-section-ptb">
	<div class="container">
		<div class="row">
			<?php
			$layouttype = ( 'layout_1' === $team_layout ) ? 'layout_1' : 'layout_2';
			get_template_part( 'template-parts/team/team', $layouttype );
			?>
		</div>
	</div>
</section>

<?php
get_footer();
