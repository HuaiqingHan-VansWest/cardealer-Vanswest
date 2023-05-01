/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Accordion
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		/*************************
		:: Accordion
		*************************/
		$('.faq-accordion').each(function() {
			var accordion  = $(this),
				all_panels = accordion.find(".accordion-content"),
				all_titles = accordion.find(".accordion-title");

				all_titles.find( 'a' ).removeClass("active");
				all_titles.first().find( 'a' ).addClass("active");
				all_panels.hide();
				all_panels.first().slideDown("easeOutExpo");

				all_titles.find("a").click(function(el){
					var current = $(this).parent().next(".accordion-content");

					all_titles.find( 'a' ).removeClass("active");
					$(this).addClass("active");
					all_panels.not(current).slideUp("easeInExpo");
					current.slideDown("easeOutExpo");
					return false;
				});

		});

	});

}( jQuery ) );
