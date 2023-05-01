/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Audio Video
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery( document ).ready( function( $ ) {

		/**************
		:: Audio Video
		***************/

		if ( jQuery( '.audio-video' ).length > 0 ) {
			jQuery( 'audio, video' ).mediaelementplayer();
		}
	});

}( jQuery ) );
