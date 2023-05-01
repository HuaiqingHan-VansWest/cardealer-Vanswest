/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Vehicle Detail - Tabs Accordion (Mobile)
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		/*************************
		:: Vehicle Detail - Tabs Accordion (Mobile)
		*************************/
		if(document.getElementById('tab-accordion')){
			$('.panel-heading a').click(function() {
				$('.panel-heading').removeClass('active');
				if(!$(this).closest('.panel').find('.panel-collapse').hasClass('in'))
					$(this).parents('.panel-heading').addClass('active');
			});
		}
	});

}( jQuery ) );
