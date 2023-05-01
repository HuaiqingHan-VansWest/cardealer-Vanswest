<?php
/**
 * Sample data files
 *
 * @package cardealer
 */

add_filter( 'cdhl_theme_sample_datas', 'cardealer_sample_data_items' );
if ( ! function_exists( 'cardealer_sample_data_items' ) ) {
	/**
	 * Sample data function
	 *
	 * @see cardealer_sample_data_items()
	 *
	 * @param array $sample_data sample data.
	 */
	function cardealer_sample_data_items( $sample_data = array() ) {

		$page_builder = cardealer_get_default_page_builder();

		$sample_data_wpb = array(
			'default' => array(
				'id'          => 'default',
				'name'        => esc_html__( 'Default', 'cardealer' ),
				'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-default',
				'home_page'   => esc_html__( 'Home', 'cardealer' ),
				'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
				'message'     => esc_html__( 'Importing demo content will import Pages, Posts, Testimonials, Teams, FAQs, Menus, Widgets and Theme Options. Importing sample data will override current widgets and theme options. It can take some time to complete the import process.', 'cardealer' ),
				'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-default',
				'menus'       => array(
					'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
					'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
					'top_menu'     => esc_html__( 'Top Bar Menu', 'cardealer' ),
				),
				'revsliders'  => array(
					'cardealer-slider-1.zip',
				),
			),
		);

		$sample_data_elementor = array(
			'default-elementor' => array(
				'id'          => 'default-elementor',
				'name'        => esc_html__( 'Default Elementor', 'cardealer' ),
				'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor',
				'home_page'   => esc_html__( 'Home', 'cardealer' ),
				'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
				'message'     => esc_html__( 'Importing demo content will import Pages, Posts, Testimonials, Teams, FAQs, Menus, Widgets and Theme Options. Importing sample data will override current widgets and theme options. It can take some time to complete the import process.', 'cardealer' ),
				'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor',
				'menus'       => array(
					'primary-menu' => esc_html__( 'Primary Menu', 'cardealer' ),
					'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
				),
				'revsliders'  => array(
					'home-default.zip',
				),
			),
		);

		if ( 'wpbakery' === $page_builder ) {
			$sample_data = $sample_data_wpb;
		} elseif ( 'elementor' === $page_builder ) {
			$sample_data = $sample_data_elementor;
		} else {
			$sample_data = array();
		}

		// check for imported demos.
		$imported_samples       = array();
		$sample_data_arr        = get_option( 'pgs_default_sample_data' );
		$default_demo_installed = false;

		if ( ! empty( $sample_data_arr ) ) {
			$imported_samples = json_decode( $sample_data_arr );
			// if default is imported, then only display other no default demos(sub demos).
			if ( ( 'wpbakery' === $page_builder && in_array( 'default', $imported_samples, true ) ) || ( 'elementor' === $page_builder && in_array( 'default-elementor', $imported_samples, true ) ) ) {
				$default_demo_installed = true;
				$sub_demos              = array(
					'home-02'        => array(
						'id'          => 'home-02',
						'name'        => esc_html__( 'Home 2', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-2/',
						'home_page'   => esc_html__( 'Home 2', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 2 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-2/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-2.zip',
						),
					),
					'home-03'        => array(
						'id'          => 'home-03',
						'name'        => esc_html__( 'Home 3', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-3/',
						'home_page'   => esc_html__( 'Home 3', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 3 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-3/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-3.zip',
						),
					),
					'home-04'        => array(
						'id'          => 'home-04',
						'name'        => esc_html__( 'Home 4', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-4/',
						'home_page'   => esc_html__( 'Home 4', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 4 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-4/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-4.zip',
						),
					),
					'home-05'        => array(
						'id'          => 'home-05',
						'name'        => esc_html__( 'Home 5', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-5/',
						'home_page'   => esc_html__( 'Home 5', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 5 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-5/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-5.zip',
						),
					),
					'home-06'        => array(
						'id'          => 'home-06',
						'name'        => esc_html__( 'Home 6', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-6/',
						'home_page'   => esc_html__( 'Home 6', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 6 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-6/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-6.zip',
						),
					),
					'home-07'        => array(
						'id'          => 'home-07',
						'name'        => esc_html__( 'Home 7', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-7/',
						'home_page'   => esc_html__( 'Home 7', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 7 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-7/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-7.zip',
						),
					),
					'home-08'        => array(
						'id'          => 'home-08',
						'name'        => esc_html__( 'Home 8', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-8/',
						'home_page'   => esc_html__( 'Home 8', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 8 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-8/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-8.zip',
						),
					),
					'home-09'        => array(
						'id'          => 'home-09',
						'name'        => esc_html__( 'Home 9', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-9/',
						'home_page'   => esc_html__( 'Home 9', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 9 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-9/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-9.zip',
						),
					),
					'home-10'        => array(
						'id'          => 'home-10',
						'name'        => esc_html__( 'Home 10', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-10/',
						'home_page'   => esc_html__( 'Home 10', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 10 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-10/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'cardealer-slider-10.zip',
						),
					),
					'home-11'        => array(
						'id'          => 'home-11',
						'name'        => esc_html__( 'Home Directory', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-11/',
						'home_page'   => esc_html__( 'Home Directory', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 11 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-11/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(),
					),
					'home-12'        => array(
						'id'          => 'home-12',
						'name'        => esc_html__( 'Car Landing', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/home-12/',
						'home_page'   => esc_html__( 'Car Landing', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 12 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/home-12/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(),
					),
					'home-inventory' => array(
						'id'          => 'home-inventory',
						'name'        => esc_html__( 'Home - Inventory', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/cars/?layout-style=lazyload',
						'home_page'   => esc_html__( 'Home - Inventory', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home - Inventory content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/cars/?layout-style=lazyload',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(),
					),
					'services-new'   => array(
						'id'          => 'services-new',
						'name'        => esc_html__( 'Car Service', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/car-service',
						'home_page'   => esc_html__( 'Car Service', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import car service content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/car-service',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'theme_options'     => array(
							'header_type' => 'boxed',
						),
						'revsliders'  => array(
							'car-service.zip',
						),
					),
				);

				$sub_demos_elementor = array(
					'home-02-elementor'  => array(
						'id'          => 'home-02-elementor',
						'name'        => esc_html__( 'Home 2', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-2/',
						'home_page'   => esc_html__( 'Home 2', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 2 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-2/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-2.zip',
						),
					),
					'home-03-elementor'  => array(
						'id'          => 'home-03-elementor',
						'name'        => esc_html__( 'Home 3', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-3/',
						'home_page'   => esc_html__( 'Home 3', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 3 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-3/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-3.zip',
						),
					),
					'home-04-elementor'  => array(
						'id'          => 'home-04-elementor',
						'name'        => esc_html__( 'Home 4', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-4/',
						'home_page'   => esc_html__( 'Home 4', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 4 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-4/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-4.zip',
						),

					),
					'home-05-elementor'  => array(
						'id'          => 'home-05-elementor',
						'name'        => esc_html__( 'Home 5', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-5/',
						'home_page'   => esc_html__( 'Home 5', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 5 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-5/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-5.zip',
						),
					),

					'home-06-elementor'  => array(
						'id'          => 'home-06-elementor',
						'name'        => esc_html__( 'Home 6', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-6/',
						'home_page'   => esc_html__( 'Home 6', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 6 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-6/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-6.zip',
						),
					),

					'home-07-elementor'  => array(
						'id'          => 'home-07-elementor',
						'name'        => esc_html__( 'Home 7', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-7/',
						'home_page'   => esc_html__( 'Home 7', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 7 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-7/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-7.zip',
						),
					),

					'home-08-elementor'  => array(
						'id'          => 'home-08-elementor',
						'name'        => esc_html__( 'Home 8', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-8/',
						'home_page'   => esc_html__( 'Home 8', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 8 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-8/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-8.zip',
						),
					),

					'home-09-elementor'  => array(
						'id'          => 'home-09-elementor',
						'name'        => esc_html__( 'Home 9', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-9/',
						'home_page'   => esc_html__( 'Home 9', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 9 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-9/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-9.zip',
						),
					),
					'home-10-elementor'  => array(
						'id'          => 'home-10-elementor',
						'name'        => esc_html__( 'Home 10', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-10/',
						'home_page'   => esc_html__( 'Home 10', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 10 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-10/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'revsliders'  => array(
							'home-10.zip',
						),
					),
					'home-11-elementor'  => array(
						'id'          => 'home-11-elementor',
						'name'        => esc_html__( 'Home Directory', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-11/',
						'home_page'   => esc_html__( 'Home 11', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 11 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-11/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
					),
					'home-12-elementor'  => array(
						'id'          => 'home-12-elementor',
						'name'        => esc_html__( 'Car Landing', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-12/',
						'home_page'   => esc_html__( 'Home 12', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import Home Page 12 content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/home-12/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
					),
					'services-elementor' => array(
						'id'          => 'services-elementor',
						'name'        => esc_html__( 'Car Service', 'cardealer' ),
						'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/car-service/',
						'home_page'   => esc_html__( 'Car Service', 'cardealer' ),
						'blog_page'   => esc_html__( 'Blog', 'cardealer' ),
						'message'     => esc_html__( "This sample will import car service content and set it as the front page. If the page already exists, then the page won't get imported. If you wanted to import the page, please rename the page then try again.", 'cardealer' ),
						'preview_url' => 'https://cardealer.potenzaglobalsolutions.com/elementor/car-service/',
						'menus'       => array(
							'primary-menu' => esc_html__( 'Main Menu', 'cardealer' ),
							'footer-menu'  => esc_html__( 'Footer Menu', 'cardealer' ),
						),
						'theme_options'     => array(
							'header_type' => 'boxed',
						),
						'revsliders'  => array(
							'car-service.zip',
						),
					),
				);

				if ( 'wpbakery' === $page_builder ) {
					$sample_data = array_merge( $sample_data, $sub_demos );
				} elseif ( 'elementor' === $page_builder ) {
					$sample_data = array_merge( $sample_data, $sub_demos_elementor );
				}
			}
		}

		// $sample_data.
		array_walk( $sample_data, 'cardealer_old_sample_data_fix' );

		$sample_data = array_merge( $sample_data, $sample_data );

		return $sample_data;
	}
}

/**
 * Additional Pages array.
 */
function cardealer_additional_pages(){
	$sample_pages_wpb = array(
		'services-new' => array(
			'id'          => 'services-new',
			'name'        => esc_html__( 'Car Service', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/car-service/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/car-service.png' ),
			'revsliders'  => array(
				'car-service.zip',
			),
		),
		'about-1' => array(
			'id'          => 'about-1',
			'name'        => esc_html__( 'About 1', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/about-1/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/about-1.jpg' ),
		),
		'about-2' => array(
			'id'          => 'about-2',
			'name'        => esc_html__( 'About 2', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/about-2/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/about-2.jpg' ),
		),
		'service-01' => array(
			'id'          => 'service-01',
			'name'        => esc_html__( 'Service 01', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/service-01/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/service-01.jpg' ),
		),
		'service-02' => array(
			'id'          => 'service-02',
			'name'        => esc_html__( 'Service 02', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/service-02/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/service-2.jpg' ),
		),
		'privacy-policy' => array(
			'id'          => 'privacy-policy',
			'name'        => esc_html__( 'Privacy Policy', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/privacy-policy/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/privacy-policy.jpg' ),
		),
		'page-right-sidebar' => array(
			'id'          => 'page-right-sidebar',
			'name'        => esc_html__( 'Page Right Sidebar', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/page-right-sidebar/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/page-right-sidebar.jpg' ),
		),
		'page-left-sidebar' => array(
			'id'          => 'page-left-sidebar',
			'name'        => esc_html__( 'Page Left Sidebar', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/page-left-sidebar/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/page-left-sidebar.jpg' ),
		),
		'page-both-sidebar' => array(
			'id'          => 'page-both-sidebar',
			'name'        => esc_html__( 'Page Both Sidebar', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/page-both-sidebar/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/page-both-sidebar.jpg' ),
		),
		'typography' => array(
			'id'          => 'typography',
			'name'        => esc_html__( 'Typography', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/typography/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/typography.jpg' ),
		),
		'terms-and-conditions' => array(
			'id'          => 'terms-and-conditions',
			'name'        => esc_html__( 'Terms And Conditions', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/terms-and-conditions/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/terms-and-conditions.jpg' ),
		),
		'contact-01' => array(
			'id'          => 'contact-01',
			'name'        => esc_html__( 'Contact 01', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/contact-01/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/contact-01.jpg' ),
		),
		'contact-02' => array(
			'id'          => 'contact-02',
			'name'        => esc_html__( 'Contact 02', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/contact-02/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/contact-02.jpg' ),
		),
		'carousel-slider' => array(
			'id'          => 'carousel-slider',
			'name'        => esc_html__( 'Carousel Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/carousel-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/carousel-slider.jpg' ),
		),
		'testimonial' => array(
			'id'          => 'testimonial',
			'name'        => esc_html__( 'Testimonial', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/testimonial/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/testimonial.jpg' ),
		),
		'buttons' => array(
			'id'          => 'buttons',
			'name'        => esc_html__( 'Buttons', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/buttons/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/buttons.jpg' ),
		),
		'columns' => array(
			'id'          => 'columns',
			'name'        => esc_html__( 'Columns', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/columns/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/columns.jpg' ),
		),
		'content-box' => array(
			'id'          => 'content-box',
			'name'        => esc_html__( 'Content Box', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/content-box/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/content-box.jpg' ),
		),
		'counter' => array(
			'id'          => 'counter',
			'name'        => esc_html__( 'Counter', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/counter/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/counter.jpg' ),
		),
		'post-style' => array(
			'id'          => 'post-style',
			'name'        => esc_html__( 'Post Style', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/post-style/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/post-style.jpg' ),
		),
		'lists-style' => array(
			'id'          => 'lists-style',
			'name'        => esc_html__( 'Lists Style', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/lists-style/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/lists-style.jpg' ),
		),
		'team-style' => array(
			'id'          => 'team-style',
			'name'        => esc_html__( 'Team Style', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/team-style/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/team-style.jpg' ),
		),
		'feature-box' => array(
			'id'          => 'feature-box',
			'name'        => esc_html__( 'Feature Box', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/feature-box/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/feature-box.jpg' ),
		),
		'social-icon' => array(
			'id'          => 'social-icon',
			'name'        => esc_html__( 'Social Icon', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/social-icon/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/social-icon.jpg' ),
		),
		'quick-link' => array(
			'id'          => 'quick-link',
			'name'        => esc_html__( 'Quick Link', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/quick-link/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/quick-link.jpg' ),
		),
		'call-to-action' => array(
			'id'          => 'call-to-action',
			'name'        => esc_html__( 'Call To Action', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/call-to-action/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/call-to-action.jpg' ),
		),
		'multitab' => array(
			'id'          => 'multitab',
			'name'        => esc_html__( 'Multitab', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/multitab/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/multitab.jpg' ),
		),
		'timeline' => array(
			'id'          => 'timeline',
			'name'        => esc_html__( 'Timeline', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/timeline/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/timeline.jpg' ),
		),
		'newsletter' => array(
			'id'          => 'newsletter',
			'name'        => esc_html__( 'Newsletter', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/newsletter/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/newsletter.jpg' ),
		),
		'video' => array(
			'id'          => 'video',
			'name'        => esc_html__( 'Video', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/video/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/video.jpg' ),
		),
		'clients' => array(
			'id'          => 'clients',
			'name'        => esc_html__( 'Clients', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/clients/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/clients.jpg' ),
		),
		'image-slider' => array(
			'id'          => 'image-slider',
			'name'        => esc_html__( 'Image Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/image-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/image-slider.jpg' ),
		),
		'opening-hours' => array(
			'id'          => 'opening-hours',
			'name'        => esc_html__( 'Opening Hours', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/opening-hours/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/opening-hours.jpg' ),
		),
		'video-slider' => array(
			'id'          => 'video-slider',
			'name'        => esc_html__( 'Video Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/video-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/video-slider.jpg' ),
		),
		'icon' => array(
			'id'          => 'icon',
			'name'        => esc_html__( 'Icon', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/icon/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/icon.jpg' ),
		),
		'pricing' => array(
			'id'          => 'pricing',
			'name'        => esc_html__( 'Pricing', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/pricing/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/pricing.jpg' ),
		),
		'section-title' => array(
			'id'          => 'section-title',
			'name'        => esc_html__( 'Section Title', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/section-title/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/section-title.jpg' ),
		),
		'vehicles-list' => array(
			'id'          => 'vehicles-list',
			'name'        => esc_html__( 'Vehicles List', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/vehicles-list/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicles-list.jpg' ),
		),
		'vertical-multi-tab' => array(
			'id'          => 'vertical-multi-tab',
			'name'        => esc_html__( 'Vertical Multi tab', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/vertical-multi-tab/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vertical-multi-tab.jpg' ),
		),
		'custom-filters' => array(
			'id'          => 'custom-filters',
			'name'        => esc_html__( 'Custom Filters', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/custom-filters/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/custom-filters.jpg' ),
		),
		'vehicle-by-type' => array(
			'id'          => 'vehicle-by-type',
			'name'        => esc_html__( 'Vehicle By Type', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/vehicle-by-type/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicle-by-type.jpg' ),
		),
		'vehicles-search' => array(
			'id'          => 'vehicles-search',
			'name'        => esc_html__( 'Vehicles Search', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/vehicles-search/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicles-search.jpg' ),
		),
		'vehicles-conditions-tabs' => array(
			'id'          => 'vehicles-conditions-tabs',
			'name'        => esc_html__( 'Vehicles Conditions Tabs', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/vehicles-conditions-tabs/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicles-conditions-tabs.jpg' ),
		),
		'modal-popup' => array(
			'id'          => 'modal-popup',
			'name'        => esc_html__( 'Modal Popup', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/modal-popup/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/modal-popup.jpg' ),
		),
	);

	$sample_pages_elementor = array(
		'services-elementor' => array(
			'id'          => 'services-elementor',
			'name'        => esc_html__( 'Car Service', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/car-service/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/car-service.png' ),
			'revsliders'  => array(
				'car-service.zip',
			),
		),
		'about-1-elementor' => array(
			'id'          => 'about-1-elementor',
			'name'        => esc_html__( 'About 1', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/about-1/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/about-1.jpg' ),
		),
		'about-2-elementor' => array(
			'id'          => 'about-2-elementor',
			'name'        => esc_html__( 'About 2', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/about-2/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/about-2.jpg' ),
		),
		'service-01-elementor' => array(
			'id'          => 'service-01-elementor',
			'name'        => esc_html__( 'Service 01', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/service-01/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/service-01.jpg' ),
		),
		'service-2-elementor' => array(
			'id'          => 'service-2-elementor',
			'name'        => esc_html__( 'Service 2', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/service-02/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/service-2.jpg' ),
		),
		'privacy-policy-elementor' => array(
			'id'          => 'privacy-policy-elementor',
			'name'        => esc_html__( 'Privacy Policy', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/privacy-policy/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/privacy-policy.jpg' ),
		),
		'page-right-sidebar-elementor' => array(
			'id'          => 'page-right-sidebar-elementor',
			'name'        => esc_html__( 'Page Right Sidebar', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/page-right-sidebar/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/page-right-sidebar.jpg' ),
		),
		'page-left-sidebar-elementor' => array(
			'id'          => 'page-left-sidebar-elementor',
			'name'        => esc_html__( 'Page Left Sidebar', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/page-left-sidebar/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/page-left-sidebar.jpg' ),
		),
		'page-both-sidebar-elementor' => array(
			'id'          => 'page-both-sidebar-elementor',
			'name'        => esc_html__( 'Page Both Sidebar', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/page-both-sidebar/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/page-both-sidebar.jpg' ),
		),
		'typography-elementor' => array(
			'id'          => 'typography-elementor',
			'name'        => esc_html__( 'Typography', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/typography/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/typography.jpg' ),
		),
		'terms-and-conditions-elementor' => array(
			'id'          => 'terms-and-conditions-elementor',
			'name'        => esc_html__( 'Terms And Conditions', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/terms-and-conditions/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/terms-and-conditions.jpg' ),
		),
		'contact-01-elementor' => array(
			'id'          => 'contact-01-elementor',
			'name'        => esc_html__( 'Contact 01', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/contact-01/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/contact-01.jpg' ),
		),
		'contact-02-elementor' => array(
			'id'          => 'contact-02-elementor',
			'name'        => esc_html__( 'Contact 02', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/contact-02/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/contact-02.jpg' ),
		),
		'carousel-slider-elementor' => array(
			'id'          => 'carousel-slider-elementor',
			'name'        => esc_html__( 'Carousel Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/carousel-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/carousel-slider.jpg' ),
		),
		'testimonial-elementor' => array(
			'id'          => 'testimonial-elementor',
			'name'        => esc_html__( 'Testimonial', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/testimonial/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/testimonial.jpg' ),
		),
		'buttons-elementor' => array(
			'id'          => 'buttons-elementor',
			'name'        => esc_html__( 'Buttons', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/buttons/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/buttons.jpg' ),
		),
		'columns-elementor' => array(
			'id'          => 'columns-elementor',
			'name'        => esc_html__( 'Columns', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/columns/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/columns.jpg' ),
		),
		'content-box-elementor' => array(
			'id'          => 'content-box-elementor',
			'name'        => esc_html__( 'Content Box', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/content-box/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/content-box.jpg' ),
		),
		'counter-elementor' => array(
			'id'          => 'counter-elementor',
			'name'        => esc_html__( 'Counter', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/counter/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/counter.jpg' ),
		),
		'post-style-elementor' => array(
			'id'          => 'post-style-elementor',
			'name'        => esc_html__( 'Post Style', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/post-style/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/post-style.jpg' ),
		),
		'lists-style-elementor' => array(
			'id'          => 'lists-style-elementor',
			'name'        => esc_html__( 'Lists Style', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/lists-style/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/lists-style.jpg' ),
		),
		'team-style-elementor' => array(
			'id'          => 'team-style-elementor',
			'name'        => esc_html__( 'Team Style', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/team-style/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/team-style.jpg' ),
		),
		'feature-box-elementor' => array(
			'id'          => 'feature-box-elementor',
			'name'        => esc_html__( 'Feature Box', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/feature-box/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/feature-box.jpg' ),
		),
		'social-icon-elementor' => array(
			'id'          => 'social-icon-elementor',
			'name'        => esc_html__( 'Social Icon', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/social-icon/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/social-icon.jpg' ),
		),
		'quick-link-elementor' => array(
			'id'          => 'quick-link-elementor',
			'name'        => esc_html__( 'Quick Link', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/quick-link/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/quick-link.jpg' ),
		),
		'call-to-action-elementor' => array(
			'id'          => 'call-to-action-elementor',
			'name'        => esc_html__( 'Call To Action', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/call-to-action/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/call-to-action.jpg' ),
		),
		'multitab-elementor' => array(
			'id'          => 'multitab-elementor',
			'name'        => esc_html__( 'Multitab', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/multitab/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/multitab.jpg' ),
		),
		'timeline-elementor' => array(
			'id'          => 'timeline-elementor',
			'name'        => esc_html__( 'Timeline', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/timeline/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/timeline.jpg' ),
		),
		'newsletter-elementor' => array(
			'id'          => 'newsletter-elementor',
			'name'        => esc_html__( 'Newsletter', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/newsletter/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/newsletter.jpg' ),
		),
		'video-elementor' => array(
			'id'          => 'video-elementor',
			'name'        => esc_html__( 'Video', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/video/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/video.jpg' ),
		),
		'clients-elementor' => array(
			'id'          => 'clients-elementor',
			'name'        => esc_html__( 'Clients', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/clients/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/clients.jpg' ),
		),
		'image-slider-elementor' => array(
			'id'          => 'image-slider-elementor',
			'name'        => esc_html__( 'Image Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/image-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/image-slider.jpg' ),
		),
		'opening-hours-elementor' => array(
			'id'          => 'opening-hours-elementor',
			'name'        => esc_html__( 'Opening Hours', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/opening-hours/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/opening-hours.jpg' ),
		),
		'video-slider-elementor' => array(
			'id'          => 'video-slider-elementor',
			'name'        => esc_html__( 'Video Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/video-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/video-slider.jpg' ),
		),
		'icon-elementor' => array(
			'id'          => 'icon-elementor',
			'name'        => esc_html__( 'Icon', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/icon/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/icon.jpg' ),
		),
		'pricing-elementor' => array(
			'id'          => 'pricing-elementor',
			'name'        => esc_html__( 'Pricing', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/pricing/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/pricing.jpg' ),
		),
		'section-title-elementor' => array(
			'id'          => 'section-title-elementor',
			'name'        => esc_html__( 'Section Title', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/section-title/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/section-title.jpg' ),
		),
		'vehicles-list-elementor' => array(
			'id'          => 'vehicles-list-elementor',
			'name'        => esc_html__( 'Vehicles List', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/vehicles-list/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicles-list.jpg' ),
		),
		'vertical-multi-tab-elementor' => array(
			'id'          => 'vertical-multi-tab-elementor',
			'name'        => esc_html__( 'Vertical Multi Tab', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/vertical-multi-tab/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vertical-multi-tab.jpg' ),
		),
		'feature-box-slider-elementor' => array(
			'id'          => 'feature-box-slider-elementor',
			'name'        => esc_html__( 'Feature Box Slider', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/feature-box-slider/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/feature-box-slider.jpg' ),
		),
		'custom-filters-elementor' => array(
			'id'          => 'custom-filters-elementor',
			'name'        => esc_html__( 'Custom Filters', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/custom-filters/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/custom-filters.jpg' ),
		),
		'vehicle-by-type-elementor' => array(
			'id'          => 'vehicle-by-type-elementor',
			'name'        => esc_html__( 'Vehicle By Type', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/vehicle-by-type/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicle-by-type.jpg' ),
		),
		'vehicles-search-elementor' => array(
			'id'          => 'vehicles-search-elementor',
			'name'        => esc_html__( 'Vehicles Search', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/vehicles-search/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicles-search.jpg' ),
		),
		'vehicles-conditions-tabs-elementor' => array(
			'id'          => 'vehicles-conditions-tabs-elementor',
			'name'        => esc_html__( 'Vehicles Conditions Tabs', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/vehicles-conditions-tabs/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/vehicles-conditions-tabs.jpg' ),
		),
		'modal-popup-elementor' => array(
			'id'          => 'modal-popup-elementor',
			'name'        => esc_html__( 'Modal Popup', 'cardealer' ),
			'demo_url'    => 'https://cardealer.potenzaglobalsolutions.com/elementor/modal-popup/',
			'message'     => '',
			'previwe_img' => esc_url( get_template_directory_uri() . '/includes/sample_data/additional-pages/modal-popup.jpg' ),
		),
	);

	$page_builder = cardealer_get_default_page_builder();
	if ( 'wpbakery' === $page_builder ) {
		$sample_data = $sample_pages_wpb;
	} elseif ( 'elementor' === $page_builder ) {
		$sample_data = $sample_pages_elementor;
	} else {
		$sample_data = array();
	}

	return $sample_data;
}

if ( ! function_exists( 'cardealer_old_sample_data_fix' ) ) {
	/**
	 * Old Sample data function
	 *
	 * @see cardealer_old_sample_data_fix()
	 *
	 * @param array  $item1 Item variable.
	 * @param string $key store key.
	 */
	function cardealer_old_sample_data_fix( &$item1, $key ) {
		$sample_data_path  = get_parent_theme_file_path( 'includes/sample_data' );
		$sample_data_url   = get_parent_theme_file_uri( 'includes/sample_data' );
		$item1['data_dir'] = trailingslashit( trailingslashit( $sample_data_path ) . str_replace( '-elementor', '', $key ) );
		$item1['data_url'] = trailingslashit( trailingslashit( $sample_data_url ) . str_replace( '-elementor', '', $key ) );
	}
}
