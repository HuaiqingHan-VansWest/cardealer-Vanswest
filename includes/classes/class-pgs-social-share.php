<?php
/**
 * Handle social share.
 *
 * @package cardealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PGS_Social_Share class.
 */
class PGS_Social_Share {

	public $post = null;

	/**
	 * Set the initial state of the class
	 *
	 * @param array $data  The array of data.
	 * @param array $args  The array of arguments.
	 */
	public function __construct( $post = 0, $args = array() ) {
		$this->set_post( $post );
	}

	public function set_post( $post ) {
		$post = ( empty( $post ) ) ? get_post() : get_post( $post );
		if ( $post ) {
			$this->post = $post;
		}
	}

	public static function share_medias() {
		$medias = array(
			'facebook' => array(
				'title'        => esc_html__( 'Facebook', 'cardealer' ),
				'icon_class'   => 'fab fa-facebook-f',
				'url_template' => 'https://www.facebook.com/sharer/sharer.php?u={url}',
				'default'      => true,
				'legacy'       => true,
			),
			'twitter' => array(
				'title'        => esc_html__( 'Twitter', 'cardealer' ),
				'icon_class'   => 'fab fa-twitter',
				'url_template' => 'https://twitter.com/intent/tweet?url={url}&text={title}',
				'default'      => true,
				'legacy'       => true,
			),
			'linkedin' => array(
				'title'        => esc_html__( 'LinkedIn', 'cardealer' ),
				'icon_class'   => 'fab fa-linkedin-in',
				'url_template' => 'https://www.linkedin.com/sharing/share-offsite/?url={url}',
				'default'      => true,
				'legacy'       => true,
			),
			'pinterest' => array(
				'title' => esc_html__( 'Pinterest', 'cardealer' ),
				'icon_class'   => 'fab fa-pinterest',
				'url_template' => 'https://pinterest.com/pin/create/button/?url={url}&description={title}&media={image}',
				'default'      => true,
				'legacy'       => true,
			),
			'whatsapp' => array(
				'title'        => esc_html__( 'WhatsApp', 'cardealer' ),
				'icon_class'   => 'fab fa-whatsapp',
				'url_template' => ( ! wp_is_mobile() ) ? 'https://web.whatsapp.com/send?text={title}{linebreak}{url}' : 'https://wa.me/?text={title}{linebreak}{url}',
				'default'      => true,
				'legacy'       => true,
			),
			'telegram' => array(
				'title'        => esc_html__( 'Telegram', 'cardealer' ),
				'icon_class'   => 'fab fa-telegram',
				'url_template' => 'https://t.me/share/url?url={url}&text={title}',
				'default'      => true,
			),
			'xing' => array(
				'title'        => esc_html__( 'Xing', 'cardealer' ),
				'icon_class'   => 'fab fa-xing',
				'url_template' => 'https://www.xing.com/social/share/spi?url={url}',
				'default'      => false,
			),
			'mastodon' => array(
				'title'        => esc_html__( 'Mastodon', 'cardealer' ),
				'icon_class'   => 'fab fa-mastodon',
				'url_template' => 'https://mastodon.social/share?title={title}&url={url}',
				'default'      => false,
			),
			'reddit' => array(
				'title'        => esc_html__( 'Reddit', 'cardealer' ),
				'icon_class'   => 'fab fa-reddit-alien',
				'url_template' => 'https://www.reddit.com/submit?title={title}&url={url}',
				'default'      => false,
			),
			'pocket' => array(
				'title'        => esc_html__( 'Pocket', 'cardealer' ),
				'icon_class'   => 'fab fa-get-pocket',
				'url_template' => 'https://getpocket.com/edit?url={url}',
				'default'      => false,
			),
			'skype' => array(
				'title'        => esc_html__( 'Skype', 'cardealer' ),
				'icon_class'   => 'fab fa-skype',
				'url_template' => 'https://web.skype.com/share?url={url}',
				'default'      => false,
			),
			'tumblr' => array(
				'title'        => esc_html__( 'Tumblr', 'cardealer' ),
				'icon_class'   => 'fab fa-tumblr',
				'url_template' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl={url}&caption={title}',
				'default'      => false,
			),
			'vk' => array(
				'title'        => esc_html__( 'Vkontakte', 'cardealer' ),
				'icon_class'   => 'fab fa-vk',
				'url_template' => 'https://vk.com/share.php?url={url}&title={title}&image={image}',
				'default'      => false,
			),
		);

		// Additional social medias.
		$additional_medias = apply_filters( 'cardealer_social_share_additional_medias', array() );

		// Check and filter additional social medias if missing required parameters or invalid parameters.
		$additional_medias = array_filter( $additional_medias, function( $data, $media ) {
			return (
				( ! empty( $media ) && is_string( $media ) )
				&& ( isset( $data['icon_class'] ) && ! empty( $data['icon_class'] ) || isset( $data['icon_url'] ) && ! empty( $data['icon_url'] ) )
				&& isset( $data['url_template'] ) && ! empty( $data['url_template'] )
				&& isset( $data['title'] ) && ! empty( $data['title'] )
			);
		}, ARRAY_FILTER_USE_BOTH );

		$medias = array_merge( $medias, $additional_medias );
		$medias = apply_filters( 'cardealer_social_share_medias', $medias );

		return $medias;
	}

	public static function get_options() {
		$share_medias = self::share_medias();
		$share_medias = array_map( function( $share_media_data ) {
			$return = $share_media_data['title'];
			if ( isset( $share_media_data['icon_url'] ) && ! empty( $share_media_data['icon_url'] ) && filter_var( $share_media_data['icon_url'], FILTER_VALIDATE_URL ) ) {
				$return = '<img src="' . $share_media_data['icon_url'] . '" />' . $share_media_data['title'];
			} elseif ( isset( $share_media_data['icon_class'] ) ) {
				$return = '<i class="' . $share_media_data['icon_class'] . '"></i> ' . $share_media_data['title'];
			}
			return $return;
		}, $share_medias );
		return $share_medias;
	}

	public static function get_options_defaults() {
		$share_medias = self::share_medias();
		$share_medias = array_map( function( $share_media_data ) {
			return ( isset( $share_media_data['default'] ) ? filter_var( $share_media_data['default'], FILTER_VALIDATE_BOOLEAN ) : false );
		}, $share_medias );
		return $share_medias;
	}

	function get_links( $return_type = 'all' ) {
		$share_medias = self::share_medias();

		foreach ( $share_medias as $social_media => $media_v ) {
			$share_link = PGS_Social_Share::prepare_share_link( $social_media, $media_v['url_template'], $this->post );
			$share_medias[ $social_media ]['share_link'] = $share_link;
		}

		return $share_medias;
	}

	public static function prepare_share_link( $social_media, $url_template ) {
		$share_params = self::get_params( 'all' );
		$search_data  = array_column( $share_params, 'placeholder' );
		$replace_data = array_column( $share_params, 'data' );
		$share_link   = str_replace( $search_data, $replace_data, $url_template );

		$share_link = apply_filters( 'pgs_social_share_prepare_share_link', $share_link, $social_media, $url_template, $share_params );

		return $share_link;
	}

	/**
	 * Get post share parameters.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global $post.
	 */
	public static function get_params( $type = 'data', $post = 0, $encoded = true ) {

		$post    = ( empty( $post ) ) ? get_post() : get_post( $post );
		$post_id = ( $post ) ? $post->ID : 0;
		$return  = array();

		$image_url = '';

		if ( $post ) {
			if ( 'cars' === $post->post_type ) {
				$image_url = cardealer_get_single_image_url( 'car_catalog_image', $post_id );
			} else {
				$post_thumbnail_id = get_post_thumbnail_id( $post );
				if ( $post_thumbnail_id ) {
					$image_url = wp_get_attachment_image_url( $post_thumbnail_id, 'full' );
				}
			}
		}

		$params = array(
			'title'      => get_the_title( $post ),
			'url'        => ( $encoded ) ? urlencode( esc_url( get_permalink( $post ) ) ) : esc_url( get_permalink( $post ) ),
			'image'      => ( $encoded ) ? urlencode( esc_url( $image_url ) ) : esc_url( $image_url ),
			'summary'    => get_the_excerpt( $post ),
			'site_title' => get_bloginfo( 'name' ),
			'linebreak'  => urlencode(PHP_EOL),
		);

		$additional_params   = self::get_additional_params( $post );
		$params              = array_merge( $params, $additional_params );

		$params = apply_filters( 'pgs_social_share_get_params', $params, $post );

		if ( 'data' === $type ) {
			$return = $params;
		} elseif ( 'placeholder' === $type ) {
			foreach ( array_keys( $params ) as $param ) {
				$return[ $param ] = "{{$param}}";
			}
		} elseif ( 'all' === $type ) {
			foreach ( $params as $param => $data ) {
				$return[ $param ] = array(
					'placeholder' => "{{$param}}",
					'data'        => $data,
				);
			}
		}

		return $return;
	}

	public static function get_native_share_params( $post ) {
		$native_share_params = array();
		$params              = PGS_Social_Share::get_params( 'data', $post, false );

		if ( isset( $params['title'] ) && ! empty( $params['title'] ) ) {
			$native_share_params['title'] = $params['title'];
		}

		if ( isset( $params['summary'] ) && ! empty( $params['summary'] ) ) {
			$native_share_params['text'] = $params['summary'];
		}

		if ( isset( $params['url'] ) && ! empty( $params['url'] ) ) {
			$native_share_params['url'] = $params['url'];
		}

		if ( isset( $params['image'] ) && ! empty( $params['image'] ) ) {
			$native_share_params['image'] = $params['image'];
		}

		return $native_share_params;
	}

	public static function get_additional_params( $post = 0 ) {

		$post = ( empty( $post ) ) ? get_post() : get_post( $post );

		return apply_filters( 'pgs_social_share_get_additional_params', array(), $post );
	}
}
