/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Compare
	:: Code To Add Car in Compare
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		/*********************************
		:: Compare Vehicle
		**********************************/
		var compare_vehicle_ids     = cookies.get( 'cars' );
		var compare_vehicle_ids_str = JSON.stringify( cookies.get( 'cars' ) );

		if ( cookies.get('cars') != null && cookies.get('cars') != '' ) {
			jQuery('.menu-item .menu-item-compare').show();
			var compare_vehicle_ids = $.parseJSON( compare_vehicle_ids_str );
			jQuery('.menu-item .compare-details.count').html( compare_vehicle_ids.length );
		}

		$( document ).on( 'click', '.compare_pgs, .menu-item-compare', function() {
			var carListClick = 0;
			if( $(this).hasClass('compare_pgs') )  {
				var car_id = jQuery(this).data('id');
				var carIds = JSON.stringify([car_id]);
				carListClick = 1;
				$(this).find('i').removeClass('fa-exchange-alt');
				$(this).find('i').addClass('fa-check');

				if( cookies.get('cars') ) {
					carIds = cookies.get('cars');
					if(jQuery.inArray(car_id, carIds) == -1)
						carIds[carIds.length] = car_id;
					carIds = JSON.stringify(carIds);
				}
				destroyCookie('cars');
				cookies.set('cars', carIds);
			}

			compare_vehicle_ids_str = JSON.stringify( cookies.get( 'cars' ) );
			jQuery.ajax({
				url: cardealer_js.ajaxurl,
				type: 'post',
				data:'action=car_compare_action&car_ids=' + compare_vehicle_ids_str,
				beforeSend: function(){
					jQuery('body').append('<div id="comparelist" class="modal" tabindex="-1" role="dialog" aria-hidden="true"></div>');
				},
				success: function(carData){
					var compare_vehicle_ids = $.parseJSON( compare_vehicle_ids_str );
					jQuery('.menu-item .compare-details.count').html( compare_vehicle_ids.length );

					// Menu display
					if ( jQuery('.menu-item-compare').is(':hidden') && compare_vehicle_ids.length > 0 ) jQuery('.menu-item-compare').show();

					// product compare click
					if ( compare_vehicle_ids.length < 2 && carListClick == 1 ) {
						return;
					}

					// Model PopUp
					jQuery("#comparelist").html( carData );
					$('div#sortable').css('width', ($('#sortable .compare-list').length * $('.compare-list').width()) + ' !important');
					jQuery('#comparelist').modal('show');

					// For sorting feature
					if( jQuery( window ).width() > 480 ) {
						jQuery( '#sortable' ).sortable();
						jQuery( '#sortable' ).disableSelection();
					}
				},
				error: function(carData){
					alert( cardealer_js.compare_load_error_msg );
				},
			});
		});

		// Set equal height for every row
		jQuery(document).on('shown.bs.modal', '#comparelist', function(e){
			cs_compare_popup_row_height();
		});

		jQuery(document).on('hidden.bs.modal', '#comparelist',function(e){
			$('.compare_pgs i.fa-spinner').parent().addClass('compared_pgs');
		});

		/*********************************
		:: Compare Vehicle - Legacy
		**********************************/
		// Remove item from Compare
		$( document ).on('click', '.cardealer-vehicle-compare-list-column .compare-remove-column', function() {
			var carID       = $(this).data('car_id'),
				compare_vehicle_ids = cookies.get('cars');

			// remove item from cookie
			compare_vehicle_ids.splice( $.inArray( carID, compare_vehicle_ids ), 1 );
			cookies.set('cars', JSON.stringify( compare_vehicle_ids ) );

			// Remove element
			$('.table-scroll').find(".compare-list[data-id='" + carID + "']").remove();
			$('li').find("a[data-id='" + carID + "']").addClass('compare_pgs');
			$('li').find("a[data-id='" + carID + "']").removeClass('compared_pgs');
			$('li').find("a[data-id='" + carID + "']").find('i').addClass('fa-exchange-alt');
			$('li').find("a[data-id='" + carID + "']").find('i').removeClass('fa-check');
			compare_vehicle_ids = cookies.get('cars');
			jQuery('.menu-item .compare-details.count').html( compare_vehicle_ids.length);
			if ( compare_vehicle_ids.length < 1 ) {
				$('#comparelist').modal('hide');
				jQuery('.menu-item-compare').hide();
			}else {
				$('#sortable').css('width', ( compare_vehicle_ids.length * $('.compare-list').width()));
			}
		});

		function cs_compare_popup_row_height() {

			jQuery( '.cardealer-vehicle-compare-list-header .cardealer-vehicle-compare-list-title' ).each( function( inner_index ) {
				jQuery(this).removeAttr( 'style' );
			});

			jQuery( '.cardealer-vehicle-compare-list-column' ).each( function( index ) {
				jQuery( this ).find( '.cardealer-vehicle-compare-list-row' ).each( function( inner_index ) {
					jQuery(this).removeAttr( 'style' );
				});
			});

			var height = [];
			jQuery( '.cardealer-vehicle-compare-list-column' ).each( function( index ) {
				var inner_height = [];
				jQuery( this ).find( '.cardealer-vehicle-compare-list-row' ).each( function( inner_index ) {
					inner_height.push( jQuery(this).height() );
				});
				height.push( inner_height );
			});

			var max_height = [];
			for ( var i = 0; i < height.length; i++ ) {
				var mh;
				var pre_index;

				if ( i !== 0 ) {
					pre_index = i - 1;
				}

				for ( var j = 0; j < height[i].length; j++ ) {
					if ( i !== 0 ) {
						if ( height[i][j] > max_height[j] ) {
							max_height[j] = height[i][j];
						}
					} else {
						max_height.push( height[i][j] );
					}
				}
			}

			jQuery( '.cardealer-vehicle-compare-list-header .cardealer-vehicle-compare-list-title' ).each( function( inner_index ) {
				jQuery(this).height( max_height[inner_index] );
			});

			jQuery( '.cardealer-vehicle-compare-list-column' ).each( function( index ) {
				jQuery( this ).find( '.cardealer-vehicle-compare-list-row' ).each( function( inner_index ) {
					jQuery(this).height( max_height[inner_index] );
				});
			});
		}

		/*********************************
		:: Code To Add Car in Compare
		**********************************/
		function destroyCookie(cname) {
			var date = new Date();
			var expires = "; expires=" + date.toGMTString();
			date.setTime(date.getTime() - (1 * 24 * 60 * 60 * 1000));
			document.cookie = cname + "=1" + expires;
		}
	});
}( jQuery ) );
