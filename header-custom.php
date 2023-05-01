<?php
/**
 * Custom header which do not include header section
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CarDealer
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="potenzaglobalsolutions.com" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php do_action( 'cardealer_head_before' ); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
<?php do_action( 'cardealer_page_before' ); ?>
	<!-- Main Body Wrapper Element -->
	<div id="page" class="hfeed site page-wrapper">

		<!-- preloader -->
		<?php cardealer_display_loader(); ?>

		<div class="wrapper" id="main">
