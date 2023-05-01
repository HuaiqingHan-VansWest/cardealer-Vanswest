<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Dynamic css helper
 *
 * @package cardealer
 */

global $typography_body, $heading, $elements, $font_properties, $font_properties_main, $sub_heading_font_properties;

/*Body Typo Graphy */
$typography_body = 'body,html';

/*H1 TypoGraphy */
$heading['h1_typography'] = 'h1';

/*H2 TypoGraphy */
$heading['h2_typography'] = 'h2';

/*H3 TypoGraphy */
$heading['h3_typography'] = 'h3';

/*H4 TypoGraphy */
$heading['h4_typography'] = 'h4';

/*H5 TypoGraphy */
$heading['h5_typography'] = 'h5';

/*H6 TypoGraphy */
$heading['h6_typography'] = 'h6';

$elements['heading'] = $heading;

// Header Seperators.
$elements['sub_heading'] = '.section-title span';
// Logo Text.
$elements['logo_text'] = '.logo-text, .sticky-logo-text';

$elements['mobile_logo_text'] = '.device-type-mobile .logo-text, .device-type-mobile .sticky-logo-text';
// inner page header.
$elements['inner_header'] = '.inner-intro';

// Font Properties.
$font_properties = array( 'font-family', 'font-backup', 'font-weight', 'font-style', 'text-transform', 'font-size', 'line-height', 'letter-spacing', 'color' );
// Sub heading.
$sub_heading_font_properties = array( 'font-family', 'font-backup', 'font-weight', 'font-style', 'text-transform', 'font-size', 'line-height', 'letter-spacing' );

$font_properties_main = array( 'font-family', 'font-backup', 'font-weight', 'font-style', 'text-transform', 'font-size', 'line-height', 'letter-spacing' );

