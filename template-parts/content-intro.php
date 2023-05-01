<?php
/**
 * Template part.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CarDealer
 */

global $car_dealer_options, $post;

$content_intro_post_id = cardealer_get_content_intro_post_id();
$hide_header_banner    = cardealer_hide_header_banner();
$banner_type           = cardealer_get_banner_type();
$video_type            = cardealer_get_video_type();
$video_link            = cardealer_get_video_link();

/*** THEME OPTIONS START */
// breadcrumb full width.
if ( isset( $car_dealer_options['header_type'] ) && in_array( $car_dealer_options['header_type'], array( 'light-fullwidth', 'transparent-fullwidth' ), true ) && isset( $car_dealer_options['breadcrumb_full_width'] ) && 1 === (int) $car_dealer_options['breadcrumb_full_width'] ) {
	$container_class = 'container-fluid';
} else {
	$container_class = 'container';
}

// mobile breadcrumb.
( isset( $car_dealer_options['breadcrumbs_on_mobile'] ) && 1 === (int) $car_dealer_options['breadcrumbs_on_mobile'] ) ? $mobile_breadcrumb_class = '' : $mobile_breadcrumb_class = 'breadcrumbs-hide-mobile';

// Titlebar Alignment.
$titlebar_view = ( isset( $car_dealer_options['titlebar_view'] ) ) ? $car_dealer_options['titlebar_view'] : 'default';
if ( function_exists( 'get_field' ) ) {
	$page_specific_title_alignment = get_field( 'enable_title_alignment', $content_intro_post_id );
	if ( $page_specific_title_alignment ) {
		$titlebar_view = get_field( 'title_alignment', $content_intro_post_id );    // get the title alignment.
		$titlebar_view = ( $titlebar_view ) ? $titlebar_view : 'left';   // set left default.
	}
}
$title_alignment = 'left';
if ( 'center' === $titlebar_view ) {
	$title_alignment = 'center';
} elseif ( 'right' === $titlebar_view ) {
	$title_alignment = 'right';
}

/*Background Video options*/

/*** THEME OPTIONS END */

// Return if page banner is set to hide.
if ( $hide_header_banner ) {
	return;
}

$data_youtube_video_bg = '';
if ( 'video' === $banner_type && 'youtube' === $video_type ) {
	$data_youtube_video_bg = $video_link;
}
?>
<section class="inner-intro header_intro <?php cardealer_intro_class(); ?>" data-youtube-video-bg="<?php echo esc_url( $data_youtube_video_bg ); ?>">
	<?php
	// Only Vimeo Video.
	if ( 'video' === $banner_type && 'vimeo' === $video_type ) {
		?>
		<div class="intro_header_video-bg  vc_video-bg vimeo_video_bg">
			<?php
			// URLs go support oembed providers.
			echo wp_oembed_get( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$video_link,
				array(
					'width'  => '500',
					'height' => '280',
				)
			);
			?>
		</div>
		<?php
	}
	?>
	<div class="<?php echo esc_attr( $container_class ); ?>">
		<?php
		$content_intro_title = get_the_title();
		$subtitle            = '';

		$show_on_front     = get_option( 'show_on_front' );
		$page_on_front     = get_option( 'page_on_front' );
		$page_for_posts_id = get_option( 'page_for_posts' );

		if ( is_singular() ) {
			if ( 'cars' === get_post_type() || 'cardealer_template' === get_post_type() ) {
				$vehicle_title_location  = ( isset( $car_dealer_options['vehicle-title-location'] ) ) ? $car_dealer_options['vehicle-title-location'] : false;
				if ( $vehicle_title_location === 'header' ) {
					$content_intro_title = esc_html__( 'Vehicle Details', 'cardealer' );
					$cars_details_title  = ( isset( $car_dealer_options['cars-details-title'] ) ) ? $car_dealer_options['cars-details-title'] : '';
					if ( ! empty( $cars_details_title ) ) {
						$content_intro_title = $cars_details_title;
					}
				}
			}
			$subtitle = get_post_meta( get_the_ID(), 'subtitle', true );
		} elseif ( is_home() ) {
			$content_intro_blog_title = isset( $car_dealer_options['blog_title'] ) ? $car_dealer_options['blog_title'] : '';
			$blog_subtitle            = isset( $car_dealer_options['blog_subtitle'] ) ? $car_dealer_options['blog_subtitle'] : '';

			if ( 'posts' === $show_on_front ) {
				$content_intro_title = esc_html__( 'Blog', 'cardealer' );
				if ( ! empty( $content_intro_blog_title ) ) {
					$content_intro_title = $content_intro_blog_title;
				}
				if ( ! empty( $blog_subtitle ) ) {
					$subtitle = $blog_subtitle;
				}
			} elseif ( 'page' === $show_on_front ) {
				$page_for_posts_data = get_post( $page_for_posts_id );
				$content_intro_title = $page_for_posts_data->post_title;
				if ( ! empty( $content_intro_blog_title ) ) {
					$content_intro_title = $content_intro_blog_title;
				}
				$subtitle_meta = get_post_meta( $page_for_posts_id, 'subtitle', true );
				if ( ! empty( $subtitle_meta ) ) {
					$subtitle = $subtitle_meta;
				} elseif ( empty( $subtitle_meta ) && ! empty( $blog_subtitle ) ) {
					$subtitle = $blog_subtitle;
				}
			}
		} elseif ( is_tax() ) {
			global $wp_query;

			$content_intro_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			if ( ! empty( $content_intro_term ) ) {
				$content_intro_title = $content_intro_term->name;
			}

			/*
			---------------------------------------------------------------------------------------------------
				Check for Vehicle category archieve page and return page id if page is set from theme options.
				Get post type from category archieve page.
			----------------------------------------------------------------------------------------------------
			*/

			$is_cars_cpt            = ( isset( $wp_query->query_vars['post_type'] ) && is_array( $wp_query->query_vars['post_type'] ) && in_array( 'cars', $wp_query->query_vars['post_type'], true ) );
			$cars_tax_archive       = false;
			$cars_tax_archive_value = false;

			if ( $is_cars_cpt ) {
				$taxonomies = get_object_taxonomies( 'cars' );
				foreach ( $wp_query->query as $query_k => $query_v ) {
					if ( in_array( $query_k, $taxonomies, true ) && ! isset( $_GET[ $query_k ] ) ) {
						$cars_tax_archive       = $query_k;
						$cars_tax_archive_value = $query_v;
						break;
					}
				}

				if ( $cars_tax_archive && $cars_tax_archive_value ) {
					$cars_tax       = get_taxonomy( $cars_tax_archive );
					$cars_tax_name  = '';
					$cars_term_name = '';

					if ( $cars_tax ) {
						$cars_tax_name = $cars_tax->labels->singular_name;
					}
					$content_intro_term = get_term_by( 'slug', $cars_tax_archive_value, $cars_tax_archive );
					if ( ! empty( $content_intro_term ) ) {
						$cars_term_name = $content_intro_term->name;
					}
					if ( $cars_tax_name ) {
						$content_intro_title = sprintf( esc_html__( '%1$s: %2$s', 'cardealer' ), $cars_tax_name, $cars_term_name );;

						/* translators: %1$s: Taxonomy singular name %2$s: Term name */
						// $subtitle = sprintf( esc_html__( '%1$s: %2$s', 'cardealer' ), $cars_tax_name, '<span>' . $cars_term_name . '</span>' );
					}
				} else {
					if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
						$content_intro_title = get_the_title( $car_dealer_options['cars_inventory_page'] );
						$subtitle            = get_post_meta( $car_dealer_options['cars_inventory_page'], 'subtitle', true );
					} elseif ( isset( $car_dealer_options['cars-listing-title'] ) && ! empty( $car_dealer_options['cars-listing-title'] ) ) {
						$content_intro_title = $car_dealer_options['cars-listing-title'];
					}
				}
			}
		} elseif ( is_archive() || is_post_type_archive() ) {

			if ( is_day() ) {
				$content_intro_title = esc_html__( 'Daily Archives', 'cardealer' );
				/* translators: 1: Post Date */
				$subtitle = sprintf( esc_html__( 'Date: %s', 'cardealer' ), '<span>' . get_the_date() . '</span>' );
			} elseif ( is_month() ) {
				$content_intro_title = esc_html__( 'Monthly Archives', 'cardealer' );
				/* translators: 1: Post Date Month */
				$subtitle = sprintf( esc_html__( 'Month: %s', 'cardealer' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'cardealer' ) ) . '</span>' );
			} elseif ( is_year() ) {
				$content_intro_title = esc_html__( 'Yearly Archives', 'cardealer' );
				/* translators: 1: Post Date Year */
				$subtitle = sprintf( esc_html__( 'Year: %s', 'cardealer' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'cardealer' ) ) . '</span>' );
			} elseif ( is_category() ) {
				$content_intro_title = esc_html__( 'Category Archives', 'cardealer' );
				/* translators: 1: Category Title */
				$subtitle = sprintf( esc_html__( 'Category Name: %s', 'cardealer' ), '<span>' . single_cat_title( '', false ) . '</span>' );
			} elseif ( is_tag() ) {
				$content_intro_title = esc_html__( 'Tag Archives', 'cardealer' );
				/* translators: 1: Tag Name */
				$subtitle = sprintf( esc_html__( 'Tag Name: %s', 'cardealer' ), '<span>' . single_tag_title( '', false ) . '</span>' );
			} elseif ( is_author() ) {
				$content_intro_title = esc_html__( 'Author Archives', 'cardealer' );
				/* translators: 1: Author Name */
				$subtitle = sprintf( wp_kses( 'Author Name: %s', 'cardealer' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
			} elseif ( is_archive() && 'post' === get_post_type() ) {
				$content_intro_title = esc_html__( 'Archives', 'cardealer' );
			} else {

				$content_intro_post_id = cardealer_get_current_post_id();
				$subtitle              = get_post_meta( $content_intro_post_id, 'subtitle', true );
				$queried_object        = get_queried_object();

				if ( 'cars' === $queried_object->name ) {
					// Theme option vehicle inventory page title.
					$cars_listing_title = ( isset( $car_dealer_options['cars-listing-title'] ) ) ? $car_dealer_options['cars-listing-title'] : '';
					$page_title         = '';
					if ( isset( $car_dealer_options['cars_inventory_page'] ) && ! empty( $car_dealer_options['cars_inventory_page'] ) ) {
							$car_page           = get_post( $content_intro_post_id );
							$page_path          = isset( $car_page->post_name ) ? $car_page->post_name : 'cars';
							$content_intro_page = get_page_by_path( $page_path );
						if ( $content_intro_page ) {
							$page_title = get_the_title( $content_intro_post_id );
						}
					} else {
						$page_title = $cars_listing_title;
					}

					if ( ! empty( $page_title ) ) {
						$content_intro_title = $page_title;
					} else {
						$content_intro_title = post_type_archive_title( '', false );
					}
				} else {
					$content_intro_title = post_type_archive_title( '', false );
				}
			}
		} elseif ( is_search() ) {
			$content_intro_title = esc_html__( 'Search', 'cardealer' );
			$subtitle            = '';
		} elseif ( is_404() ) {
			$content_intro_title = esc_html__( '404 error', 'cardealer' );
			$subtitle            = '';
		}

		if ( function_exists( 'is_shop' ) ) {
			if ( is_shop() ) {
				// This filter is originated from WooCommerce.
				if ( apply_filters( 'woocommerce_show_page_title', true ) ) { // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
					add_filter( 'woocommerce_show_page_title', '__return_false' );
				}
			}
		}

		global $cardealer_title, $cardealer_subtitle;

		$cardealer_title    = apply_filters( 'cardealer_page_title', $content_intro_title );
		$cardealer_subtitle = apply_filters( 'cardealer_subtitle_title', $subtitle );
		do_action( 'cardealer_before_title' );
		?>
		<div class="row intro-title title-<?php echo esc_attr( $title_alignment . ' ' . $titlebar_view ); ?>">
			<?php
			if ( 'title_l_bread_r' === $titlebar_view ) {
				echo '<div class="col-sm-6 col-md-8 text-left">';
			} elseif ( 'bread_l_title_r' === $titlebar_view ) {
				echo '<div class="col-sm-6 text-right col-sm-push-6">';
			}

			if ( ! empty($cardealer_title) ) {
				?>
				<h1 class="text-orange"><?php echo esc_html( $cardealer_title ); ?></h1>
				<?php
			}

			if ( ! empty( $cardealer_subtitle ) ) {
				?>
				<p class="text-orange">
					<?php
					printf(
						wp_kses(
							$cardealer_subtitle,
							array(
								'span' => array(
									'style' => array(),
									'class' => array(),
								),
								'a'    => array(
									'href'  => array(),
									'class' => array(),
									'title' => array(),
									'rel'   => array(),
								),
							)
						)
					);
					?>
				</p>
				<?php
			}

			if ( 'title_l_bread_r' === $titlebar_view ) {
				echo '</div><div class="col-sm-6 col-md-4 text-right">';
			} elseif ( 'bread_l_title_r' === $titlebar_view ) {
				echo '</div><div class="col-sm-6 text-left col-sm-pull-6">';
			}

			$cars_details_layout = cardear_get_vehicle_detail_page_layout();
			if ( function_exists( 'cardealer_bcn_display_list' ) && ( ! is_home() || ( is_home() && 'page' === $show_on_front && ( 0 !== (int) $page_on_front || '0' === (string) $page_on_front ) ) ) && ! is_post_type_archive( 'cars' ) ) {
				if ( isset( $car_dealer_options['display_breadcrumb'] ) && ! empty( $car_dealer_options['display_breadcrumb'] ) ) {
					?>
					<ul class="page-breadcrumb <?php echo esc_attr( $mobile_breadcrumb_class ); ?>" typeof="BreadcrumbList" vocab="http://schema.org/">
						<?php cardealer_bcn_display_list(); ?>
					</ul>
					<?php
				}
			}
			if ( 'title_l_bread_r' === $titlebar_view || 'bread_l_title_r' === $titlebar_view ) {
				echo '</div>';
			}
			?>
		</div>
	</div>
</section>
