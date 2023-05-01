<?php
/**
 * Function ini
 *
 * @package cardealer
 */

if ( ! function_exists( 'cardealer_init_theme' ) ) {
	/**
	 * Clean up wp_head() output
	 *
	 * This function is called in cardealer_init_theme().
	 *
	 * @since Car Dealer 1.0
	 */
	function cardealer_init_theme() {
		add_filter( 'the_generator', 'cardealer_rss_version' );                        // remove WP version from RSS.
		cardealer_theme_support();                                                     // launching this stuff after theme setup.
		cardealer_add_image_sizes();                                                   // add additional image sizes.
		add_action( 'widgets_init', 'cardealer_register_sidebars' );                   // adding sidebars to WordPress (these are created in functions.php).
		add_filter( 'get_search_form', 'cardealer_wpsearch' );                         // adding the search form.
		add_filter( 'the_content', 'cardealer_filter_ptags_on_images' );               // cleaning up random code around images.

		// Hide Revolution Slider notice.
		update_option( 'revslider-valid-notice', 'false' );

		// Set transient for welcome loader.
		set_transient( '_cardealer_welcome_screen_activation_redirect', true, 30 );
	}
}
add_action( 'after_setup_theme', 'cardealer_init_theme' );

if ( ! function_exists( 'cardealer_rss_version' ) ) {
	/**
	 * Remove WP version from RSS
	 *
	 * This function is called in cardealer_rss_version().
	 */
	function cardealer_rss_version() {
		return '';
	}
}
// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/).
if ( ! function_exists( 'cardealer_filter_ptags_on_images' ) ) {
	/**
	 * Filter page image
	 *
	 * @param string $content .
	 */
	function cardealer_filter_ptags_on_images( $content ) {
		return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	}
}
if ( ! function_exists( 'cardealer_wpsearch' ) ) {
	/**
	 * Search Form
	 *
	 * Call using get_search_form().
	 *
	 * @since Car Dealer 1.0
	 * @param string $form .
	 */
	function cardealer_wpsearch( $form ) {
		ob_start();
		?>
		<form role="search" method="get" id="searchform" class="clearfix" action="<?php echo esc_attr( home_url( '/' ) ); ?>" >
			<div class="search cd-search-wrap">
				<label class="screen-reader-text" for="s"><?php echo esc_html__( 'Search for:', 'cardealer' ); ?></label>
				<input type="text" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" class="cardealer-default-search cd-search-autocomplete-input" placeholder="<?php echo esc_attr__( 'Search...', 'cardealer' ); ?>" data-seach_type="default" />
				<input type="submit" class="cd-search-submit" id="searchsubmit" value="<?php echo esc_attr__( 'Go', 'cardealer' ); ?>" />
				<div class="cd-search-autocomplete"><ul class="cd-search-autocomplete-list"></ul></div>
			</div>
		</form>
		<?php
		$form = ob_get_clean();
		return $form;
	}
}
