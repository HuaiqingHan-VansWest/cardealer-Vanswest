/*================================================
[  Table of contents  ]
================================================
:: window load functions
	:: owl-carousel
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(window).load(function() {

		setTimeout( function(){
			cardealer_owl();
		}, 300 );

		$( document.body ).on( 'cardealer_owl_event', function() {
			console.log('=======> cardealer_owl_event triggered');
			cardealer_owl();
		});

		if ( $( '.pgs-popup-content' ).length > 0 ) {
			$( '.pgs-popup-content' ).on( 'shown.bs.modal', function(e) {
				$( window ).trigger( 'resize' );
				$( window ).trigger( 'cardealer_owl_event' );
			});
		}

		/*************************
		:: owl-carousel
		*************************/
		var blog_post_carousel = jQuery('.blog-post-carousel');
		blog_post_carousel.each(function () {
			var $this = jQuery(this),
			$lazyload = ($this.attr('data-lazyload')) ? $this.data('lazyload') : false;
			jQuery(this).owlCarousel({
				items:1,
				rtl:(jQuery( "body" ).hasClass( "rtl" )) ? true : false,
				loop:true,
				autoplay:true,
				autoplayTimeout:2000,
				smartSpeed:1000,
				autoplayHoverPause:true,
				dots:false,
				nav:true,
				lazyLoad : $lazyload,
				navText:[
					"<i class='fas fa-angle-left'></i>",
					"<i class='fas fa-angle-right'></i>"
				]
			});
		});
		jQuery('.blog-related-posts-carousel').owlCarousel({
			rtl:(jQuery( "body" ).hasClass( "rtl" )) ? true : false,
			items:3,
			margin:5,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				768:{
					items:3
				},
				1300:{
					items:3
				}
			},
			autoplay:true,
			autoplayTimeout:2000,
			autoplayHoverPause:true,
			dots:false,
			autoHeight:false,
			nav:true,
			navText:[
				"<i class='fas fa-angle-left'></i>",
				"<i class='fas fa-angle-right'></i>"
			]
		});
		jQuery('.cardealer-featured-box-carousel').owlCarousel({
			rtl:(jQuery( "body" ).hasClass( "rtl" )) ? true : false,
			items:3,
			margin:5,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				992:{
					items:2
				},
				993:{
					items:3
				}
			},
			autoplay:true,
			autoplayTimeout:2000,
			autoplayHoverPause:true,
			dots:true,
			autoHeight:false,
			nav:false,
		});

		setTimeout(function(){
			setMinHeight(0);
		}, 300 );
	});

	function cardealer_owl() {
		jQuery( '.owl-carousel' ).each( function ( index, el ) {
			// prevent the slider for popup content
			if ( ( jQuery( this ).parents( '.modal-content' ).length > 0 ) || ( jQuery( this ).parents( '.modal-content' ).length > 0 && jQuery( this ).parents( '.modal-content' ).is( ':hidden' ) ) ) {
				return;
			}

			if ( jQuery( this ).parents( '.pgs-popup-content-modal' ).length > 0 && $.magnificPopup.instance.isOpen != true ) {
				return;
			}

			var $this = jQuery( this );

			if ( $this.hasClass( 'owl-carousel-options' ) ) {
				var $carousel                   = $( this ),
					$carousel_option            = ( $carousel.attr('data-owl_options')) ? $carousel.data('owl_options') : {};
					$carousel_option.navElement = 'div';
					$carousel_option.rtl        = ( $( 'body' ).hasClass( 'rtl' ) ) ? true : false;

					$carousel.owlCarousel( $carousel_option );
			} else {
				var $loop     = ( $this.data( 'loop' ) ) ? $this.data( 'loop' ) : false,
					$navdots  = ( $this.data( 'nav-dots' ) ) ? $this.data( 'nav-dots' ) : false,
					$navarrow = ( $this.data( 'nav-arrow' ) ) ? $this.data( 'nav-arrow' ) : false,
					$items    = ( $this.attr( 'data-items' ) ) ? $this.data( 'items' ) : 1,
					$autoplay = ( $this.attr( 'data-autoplay' ) ) ? $this.data( 'autoplay' ) : true,
					$space    = ( $this.attr( 'data-space' ) ) ? $this.data( 'space' ) : 30,
					$lazyload = ( $this.attr( 'data-lazyload' ) ) ? $this.data( 'lazyload' ) : false;

				jQuery( this ).owlCarousel({
					rtl                : ( jQuery( 'body' ).hasClass( 'rtl' ) ) ? true : false,
					loop               : $loop,
					items              : $items,
					responsive: {
						0   : {items: $this.data('xx-items') ? $this.data('xx-items') : 1},
						480 : {items: $this.data('xs-items') ? $this.data('xs-items') : 1},
						768 : {items: $this.data('sm-items') ? $this.data('sm-items') : 2},
						980 : {items: $this.data('md-items') ? $this.data('md-items') : 3},
						1200: {items: $items}
					},
					dots               : $navdots,
					margin             : $space,
					nav                : $navarrow,
					navText            : ["<i class='fas fa-angle-left'></i>","<i class='fas fa-angle-right'></i>"],
					autoplay           : $autoplay,
					autoplayHoverPause : true,
					lazyLoad           : $lazyload,
				});
			}
		});
	}

	// set same height for every car-carousel items
	function setMinHeight( minheight ) {
		jQuery( '.owl-carousel' ).each( function( i, e ) {
			var oldminheight = minheight;
			jQuery( e ).find( '.item' ).each( function( i, e ) {
				minheight = jQuery(e).height() > minheight ? jQuery(e).height() : minheight;
			});
			jQuery( e ).find( '.car-item' ).css( 'min-height', minheight + 'px' );
			minheight = oldminheight;
		});
	};

}( jQuery ) );
