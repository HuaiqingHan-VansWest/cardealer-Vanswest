/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Masonry
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		/*************************
		  :: Masonry
		*************************/
		cardealer_load_masonry();
		$( document.body ).on( 'cardealer_load_masonry_event', function() {
			cardealer_load_masonry();
		});
	});

	function cardealer_load_masonry(){
		setTimeout(function(){
			if( jQuery('.blog.masonry-main .masonry').length > 0 ){
				var blog_msry_container = jQuery( '.masonry-main .masonry' );
				new Shuffle( blog_msry_container, {
					itemSelector: '.masonry-item',
				});
			}
		}, 500);

		//other
		var container = jQuery( '.masonry-main .masonry' );
		if( container.length > 0 && (jQuery( '.isotope-2.masonry' ).length > 0) ){
			jQuery(container).each( function( index, el ) {
				var $msnry = jQuery( '.isotope-2.masonry' );
				new Shuffle( $msnry, {
					itemSelector: '.masonry-item',
				});
			});
		}
	}

}( jQuery ) );
