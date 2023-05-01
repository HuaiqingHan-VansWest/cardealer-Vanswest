/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: For header background video [ theme options ]
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		/***********************************************
		:: For header background video [ theme options ]
		************************************************/
		cardealer_initVimeoVideoBackgrounds();
		cardealer_initVideoBackgrounds();
	});

	/***** Code for Youtube and Vimeo video of theme options starts *****/
	function cardealer_initVimeoVideoBackgrounds(){
		jQuery(".vimeo_video_bg iframe").each(function() {
			var iframe_src    = jQuery(this).attr('src'),
				$element      = jQuery('.vimeo_video_bg').parent(),
				iframe_url    = new URL( iframe_src ),
				iframe_params = iframe_url.searchParams;

			// Set additional params.
			iframe_params.set( 'background', '1' );
			iframe_params.set( 'autoplay', '1' );
			iframe_params.set( 'muted', '1' );
			iframe_params.set( 'loop', '1' );

			iframe_url.search = iframe_params.toString();

			var iframe_url_new = iframe_url.toString();

			jQuery(this).attr( 'src', iframe_url_new );

			ResizeVideoBackground($element);
			jQuery(window).bind("resize", function() {
				ResizeVideoBackground($element);
			});
		});
	}

	function cardealer_initVideoBackgrounds() {
		jQuery("[data-youtube-video-bg]").each(function() {
			var youtubeUrl,
				youtubeId,
				$element = jQuery(this);

			if( $element.data("youtubeVideoBg") ) {
				youtubeUrl = $element.data("youtubeVideoBg"), youtubeId = ExtractYoutubeId(youtubeUrl), youtubeId && ($element.find(".youtube_video-bg").remove(), insertYoutubeVideoAsBackground_($element, youtubeId)), jQuery(window).on("grid:items:added", function(event, $grid) {
					$element.has($grid).length && ResizeVideoBackground($element);
				});
			} else {
				$element.find(".youtube_video-bg").remove();
			}
		});
	}

	function insertYoutubeVideoAsBackground_($element, youtubeId, counter) {
		if ("undefined" == typeof YT || "undefined" == typeof YT.Player) return counter = "undefined" == typeof counter ? 0 : counter, 100 < counter ? void console.warn("Too many attempts to load YouTube api") : void setTimeout(function() {
			insertYoutubeVideoAsBackground_($element, youtubeId, counter++);
		}, 100);

		var $container = $element.prepend('<div class="intro_header_video-bg vc_video-bg"><div class="inner"></div></div>').find(".inner");
		new YT.Player($container[0], {
			width: "100%",
			height: "100%",
			videoId: youtubeId,
			playerVars: {
				playlist: youtubeId,
				iv_load_policy: 3,
				enablejsapi: 1,
				disablekb: 1,
				autoplay: 1,
				playsinline: 1,
				controls: 0,
				showinfo: 0,
				rel: 0,
				loop: 1,
				wmode: "transparent"
			},
			events: {
				onReady: function(event) {
					event.target.mute().setLoop(!0);
					event.target.mute();
					event.target.playVideo();
				}
			}
		}), ResizeVideoBackground($element), jQuery(window).bind("resize", function() {
			ResizeVideoBackground($element);
		});
	}

	function ResizeVideoBackground($element) {
		var iframeW, iframeH, marginLeft, marginTop, containerW = $element.innerWidth(),
		containerH = $element.innerHeight(),
		ratio1 = 16,
		ratio2 = 9;
		containerW / containerH < ratio1 / ratio2 ? (iframeW = containerH * (ratio1 / ratio2), iframeH = containerH, marginLeft = -Math.round((iframeW - containerW) / 2) + "px", marginTop = -Math.round((iframeH - containerH) / 2) + "px", iframeW += "px", iframeH += "px") : (iframeW = containerW, iframeH = containerW * (ratio2 / ratio1), marginTop = -Math.round((iframeH - containerH) / 2) + "px", marginLeft = -Math.round((iframeW - containerW) / 2) + "px", iframeW += "px", iframeH += "px"), $element.find(".intro_header_video-bg iframe").css({
			maxWidth: "1000%",
			marginLeft: marginLeft,
			marginTop: marginTop,
			width: iframeW,
			height: iframeH
		});
	}

	function ExtractYoutubeId(url) {
		if ("undefined" == typeof url) return !1;
		var id = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
		return null !== id && id[1];
	}


}( jQuery ) );
