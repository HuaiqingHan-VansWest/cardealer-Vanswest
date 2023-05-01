/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Vehicle Make Logos
	:: Categories Show/Hide Sub Items
	:: Vehicle Categories
	:: Financing Calculator
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery( document ).ready( function( $ ) {
		
		/**********************************
		:: Vehicle Make Logos
		**********************************/

		if ( $('.cardealer-make-logos').hasClass('vehicle-archive-page') && $( '.sort-filters select[name="car_make"]' ).length > 0 ) {
			$( document ).on( 'click', ".cardealer-make-logos .cardealer-make-logo a", function(e){
				e.preventDefault();

				var car_make_link  = $( this ),
					car_makes_wrap = car_make_link.closest('.cardealer-make-logos'),
					car_make_wrap  = car_make_link.closest('.cardealer-make-logo'),
					tax_name       = car_make_link.data('tax_name'),
					tax_slug       = car_make_link.data('tax_slug');

				car_makes_wrap.find('.cardealer-make-logo').removeClass('active');
				car_make_wrap.addClass('active');

				$( '.sort-filters select[name="car_make"]' ).val(tax_slug).trigger('change');
			});
		}

		/**********************************
		:: Categories Show/Hide Sub Items
		**********************************/

		$('.cat-menu .sub-menu').hide();
		$(".cat-menu .category-open-close-icon").click(function(e){
			e.preventDefault();
			$(this).closest('li').children("ul.cat-sub-menu").toggle('slow');
		});

		/**********************************
		:: Vehicle Categories
		**********************************/

		if ( $( '.widget.widget-vehicle-categories select.vehicle-categories-dropdown' ).length > 0 ) {
			$( document ).on( 'change', '.widget.widget-vehicle-categories select.vehicle-categories-dropdown', function ( event ) {
				if ( $(this).val() != '' ) {
					window.location.href = $(this).find(':selected').data('uri');
				}
			} );
		}
	});

}( jQuery ) );
