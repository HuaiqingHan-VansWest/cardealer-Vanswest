/*================================================
[  Table of contents  ]
================================================
:: Fix for Visual Composer RTL Resize Issue
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	/**************************************************
	:: Fix for Visual Composer RTL Resize Issue
	TODO: Attach this function to jQuery/Window	to make it available globally
	Check this : http://stackoverflow.com/questions/2223305/how-can-i-make-a-function-defined-in-jquery-ready-available-globally
	**************************************************/
	if( jQuery('html').attr('dir') == 'rtl' ){

		jQuery(window).load(function() {
			cardealer_vc_rtl_fullwidthrow();
		});

		$( window ).resize(function() {
			cardealer_vc_rtl_fullwidthrow();
		});

	}

	function cardealer_vc_rtl_fullwidthrow() {
		if( jQuery('html').attr('dir') == 'rtl' ){
			var $elements = jQuery('[data-vc-full-width="true"]');
			jQuery.each($elements, function(key, item) {
				var $el = jQuery(this);
				$el.addClass("vc_hidden");
				var $el_full = $el.next(".vc_row-full-width");
				if ($el_full.length || ($el_full = $el.parent().next(".vc_row-full-width")), $el_full.length) {
					var el_margin_left = parseInt($el.css("margin-left"), 10);
					var el_margin_right = parseInt($el.css("margin-right"), 10);
					var offset = 0 - $el_full.offset().left - el_margin_left;
					var width = jQuery(window).width();
					$el.css({
						left: 'auto',
						right: offset,
						width: width,
					});
				}
				$el.attr("data-vc-full-width-init", "true"), $el.removeClass("vc_hidden");
			});
		}
	}

}( jQuery ) );
