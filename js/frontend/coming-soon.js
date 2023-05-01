/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Coming soon
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		/***************
		:: Coming Soon
		****************/
		if ( $( '.countdown' ).length > 0 && $('.countdown').attr( 'data-countdown-date' ).length > 0 ) {
			var $countdown_date = $( '.countdown' ).data( 'countdown-date' );
			$( '.countdown' ).downCount({
				date: $countdown_date,
			});
		}
	});
}( jQuery ) );
