/*================================================
[  Table of contents  ]
================================================
:: window load functions
	:: Grid list cookie
:: Document ready functions
	:: Lazy load vehicles
	:: Off-canvas
	:: Cars view layout
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	// Function to check if ajax running.
	$(function() {
		window.ajax_loading = false;
		$.hasAjaxRunning = function() {
			return window.ajax_loading;
		};
		$(document).ajaxStart(function() {
			window.ajax_loading = true;
		});
		$(document).ajaxStop(function() {
			window.ajax_loading = false;
		});
	});

	jQuery(document).ready(function($) {

		set_layout_height();
		$( window ).resize(function() {
			$( document.body ).trigger( 'cardealer_set_layout_height_event' );
			$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).trigger('resized.owl.carousel');
		});

		$( document.body ).on( 'cardealer_set_layout_height_event', function() {
			set_layout_height();
		});

		if ( jQuery(".listing-sidebar-sticky").length > 0 ) {
			var outer_width = $( '.listing-sidebar-sticky' ).outerWidth();
			var initial_Pos = $( '.car-listing-masonry-main' ).offset().top;
			var additional_height = 0;

			$(window).scroll(function () {
				if(window.matchMedia('(min-width: 768px)').matches) {
					if ( $( '#wpadminbar' ).length > 0 ) {
						additional_height = $( '#wpadminbar' ).outerHeight();
					}

					if ( $( '.menu-inner' ).length > 0 ) {
						if ( $( '#menu-1' ).length > 0 && $( '#menu-1' ).hasClass( 'desktopTopFixed' ) ) {
							additional_height = additional_height + $( '.menu-inner' ).outerHeight();
						}
					}

					var end_pos     = initial_Pos + $( '.car-listing-masonry-main' ).outerHeight();
					var topOfWindow = $( window ).scrollTop() + additional_height;
					var endOfWindow = topOfWindow + $( window ).height();
					var top_height  = additional_height;

					if ( topOfWindow > initial_Pos ) {
						$( '.listing-sidebar-sticky' ).addClass( 'is_stuck' );
						$( '.listing-sidebar-sticky' ).css( 'max-width', outer_width + 'px' );
						$( '.listing-sidebar-sticky' ).css( 'top', top_height );
						$( '.listing-sidebar-sticky' ).css( 'position', 'fixed' );
					}
					if ( ( topOfWindow < initial_Pos ) || ( end_pos < endOfWindow ) ) {
						$( '.listing-sidebar-sticky' ).removeClass( 'is_stuck' );
						$( '.listing-sidebar-sticky' ).css('max-width', "");
						$( '.listing-sidebar-sticky' ).css('top', "");
						$( '.listing-sidebar-sticky' ).css('position', "");
					}
				}
			});
		}

		/**********************
		:: Lazy load vehicles
		***********************/

		if ( jQuery( '#cd-scroll-to' ).length > 0 ) {

			var screen_height = jQuery(window).height(); // Screen height
			jQuery(document).on( 'scroll', function() {

				var element_position = jQuery('#cd-scroll-to').offset().top; // Botton of the vehicle listing
				var current_scroll   = screen_height + $(window).scrollTop(); // Current scroll position

				if ( $.hasAjaxRunning() ) {
					return;
				}

				var records = jQuery('.all-cars-list-arch').attr('data-records');

				if( records !== null && ( records == 0 || records == -2 ) ){
					$(window).data('records_processed', 0);
					$(window).data('paged', 2);
					jQuery(".all-cars-list-arch").attr('data-paged', 2);
					return;
				}

				if (! $(window).data('paged')){
					$(window).data('paged', 2);
				}
				if (! $(window).data('records_processed')){
					$(window).data('records_processed', 0);
				}

				if ( current_scroll > element_position ) {

					var currentRequest = null;
					var query_params = cd_getUrlVars();
					if( jQuery(".cars-total-vehicles ul.stripe-item li").length > 0 ){
						var filter_att = [];
						var query_params_obj = JSON.parse(query_params);
						jQuery(".cars-total-vehicles ul.stripe-item li").each(function(){
							var filterAttr = jQuery(this).attr('data-type');
							if( typeof query_params_obj.filterAttr == 'undefined'){
								var filterAttr = jQuery(this).attr('data-type');
								var obj = {};
								obj[filterAttr] = jQuery(this).find('span').attr('data-key');
								jQuery.extend(query_params_obj, obj);
							}

						});
						query_params = JSON.stringify(query_params_obj);
					}

					var paged = $(window).data('paged');
					var data = {
						'action' : 'cardealer_load_more_vehicles',
						'filter_vars' : query_params,
						'paged' : $(window).data('paged'),
						'records_processed' : $(window).data('records_processed'),
						'ajax_nonce': vehicle_inventory_js_object.load_more_vehicles_nonce
					};
					currentRequest = jQuery.ajax({
						url: cardealer_js.ajaxurl,
						type: 'post',
						dataType: 'json',
						data: data,
						beforeSend: function(){
							jQuery(".vehicle-listing.vehicle-listing-main").after('<div class="col-md-12 text-center cd-inv-loader"><span class="cd-loader"></span><div>');
							if(currentRequest != null) {
								currentRequest.abort();
							}

							jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
							if( jQuery('.cars-top-filters-box').length > 0 ) {
								jQuery('.cars-top-filters-box').append('<span class="filter-loader"><i class="cd-loader"></i></span>');
							}
							jQuery('.select-sort-filters').prop('disabled',true);
							if ( jQuery( '#submit_all_filters' ).length > 0 ) {
								jQuery('#submit_all_filters').prop('disabled',true);
							}
						},
						success: function(response){
							jQuery(".cd-inv-loader").remove();
							if(response.status == 2){
								if( jQuery(".load-status").length == 0 ){
									jQuery(".car-listing-masonry-main .all-cars-list-arch").after(response.data_html);
								}
								jQuery(".load-status").fadeIn(2000);
								jQuery(".load-status").fadeOut(2000);
							} else {
								jQuery(".car-listing-masonry-main .all-cars-list-arch").append(response.data_html);
								setTimeout(function(){
									$( document.body ).trigger( 'cardealer_load_masonry_event' ); // Reload shuffle masonry
								}, 1000);
							}

							jQuery('.select-sort-filters').prop('disabled',false);
							if ( jQuery( '#submit_all_filters' ).length > 0 ) {
								jQuery('#submit_all_filters').prop('disabled',false);
							}

							if ( jQuery('.cars-top-filters-box .filter-loader').length > 0 ) {
								jQuery('.cars-top-filters-box .filter-loader').remove();
							}
							jQuery('.filter-loader').html('');

							jQuery(".car-listing-masonry-main .all-cars-list-arch").attr('data-paged', response.paged);

							$(window).data('paged', response.paged);
							$(window).data('records_processed', response.records_processed);

							var cars_pp = $(window).data('records_processed');
							var filterURL = '?cars_pp='+cars_pp;
							jQuery.each(JSON.parse(query_params), function(index, value){
								if(index != 'cars_pp'){
									filterURL += '&' + index + '=' + value;
								}
							});

							var pgs_min_price = jQuery('#pgs_min_price').val();
							var pgs_max_price = jQuery('#pgs_max_price').val();
							if (typeof pgs_max_price !== typeof undefined && pgs_max_price !== false && typeof pgs_min_price !== typeof undefined && pgs_min_price !== false) {
								filterURL += '&max_price' + '=' + pgs_max_price;
								filterURL += '&min_price' + '=' + pgs_min_price;
							}

							if( jQuery('.all-cars-list-arch').attr('data-records') == -1 ){
								jQuery('.all-cars-list-arch').attr('data-records', -2);
							} else if( jQuery('.all-cars-list-arch').attr('data-records') >= response.records_processed ){
								jQuery('.all-cars-list-arch').attr('data-records', -1);
							} else {
								jQuery('.all-cars-list-arch').attr('data-records', cars_pp);
							}

							// add value in per page select drop down if not exist
							if($("#pgs_cars_pp option[value='"+cars_pp+"']").length == 0){
								jQuery("#pgs_cars_pp").append("<option value="+ cars_pp +">"+ cars_pp + "</option>");
							}
							$('#pgs_cars_pp option').filter(function() { return $.trim( $(this).text() ) == cars_pp; }).attr('selected',true);
							jQuery('select#pgs_cars_pp').niceSelect('update');
							window.history.pushState(null, null, vehicle_inventory_js_object.cars_form_url+filterURL);
							$( document.body ).trigger( 'cd_data_tooltip_event' );
						},
						error: function(msg){
							alert( vehicle_inventory_js_object.error_msg );
						}
					});
				}
			});
		}

		jQuery('section.lazyload .cars_filters h6 a').on('click', function(){
			if( jQuery(this).attr('aria-expanded') == 'false' ){
				jQuery(this).find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
			} else {
				jQuery(this).find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
			}
		});

		/***************
		:: Off-canvas
		****************/

		$( document ).on( 'click', '.off-canvas-toggle a, .cardealer-offcanvas .cardealer-offcanvas-close-btn', function (e) {
			e.preventDefault();
			cardealer_offcanvas_toggle();
		});
		$( document ).on( "mousedown", '.cardealer-offcanvas-overlay', function() {
			$( document.body ).trigger( 'cardealer_offcanvas_close' );
		});
		$( document ).on( "cardealer_offcanvas_toggle", function() {
			cardealer_offcanvas_toggle();
		});
		$( document ).on( "cardealer_offcanvas_close", function() {
			$( 'body' ).removeClass( 'cardealer-offcanvas-open' );
			$( '.cardealer-offcanvas, .cardealer-offcanvas-overlay' ).removeClass( 'is-open' ).addClass( 'is-closed' );
			$( document.body ).trigger( 'cardealer_offcanvas_closed' );
		});

		/*******************
		:: Cars view layout
		********************/

		$('.view-list').on('click',function(){
			$('.view-grid').removeClass('sel-active');
			$('.view-list').addClass('sel-active');
			$("div.cars-loop").fadeOut(300, function() {
				$(this).addClass("cars-list").fadeIn(300);
			});
			cookies.set('cars_grid', 'no');
		});

		$('.view-grid').on('click',function(){
			$( '.view-list' ).removeClass('sel-active');
			$( '.view-grid' ).addClass('sel-active');
			$("div.cars-loop").fadeOut(300, function() {
				$(this).removeClass("cars-list").fadeIn(300);
			});
			cookies.set('cars_grid', 'yes');
		});

		if ( $( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).exists() ) {
			// console.log('---------------------------');
			$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).on( 'refreshed.owl.carousel', function(event) {
				$( document.body ).trigger( 'cardealer_set_layout_height_event' );
				$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).addClass('owl-loaded');
				// console.log( '=========================== refreshed.owl.carousel' );
			});
			$('.vehicle-listing.vehicle-listing-featured.owl-carousel').on('initialized.owl.carousel', function(event) {
				$( document.body ).trigger( 'cardealer_set_layout_height_event' );
				// console.log( '=========================== initialized.owl.carousel' );
			});
			$('.vehicle-listing.vehicle-listing-featured.owl-carousel').on('resized.owl.carousel', function(event) {
				$( document.body ).trigger( 'cardealer_set_layout_height_event' );
				// console.log( '=========================== resized.owl.carousel' );
			});
		}

	});

	function cardealer_offcanvas_toggle() {
		$( 'body' ).toggleClass( 'cardealer-offcanvas-open' );
		$( '.cardealer-offcanvas, .cardealer-offcanvas-overlay' ).toggleClass( ['is-open', 'is-closed'] );
		$( document.body ).trigger( 'cardealer_offcanvas_toggled' );
	}

	function cd_getUrlVars(){
		var vars = {};
		var hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

		vars['cars_orderby'] = jQuery('#pgs_cars_orderby').val();
		vars['cars_order'] = jQuery('#pgs_cars_order').data('current_order');
		for(var i = 0; i < hashes.length; i++){
			hash = hashes[i].split('=');
			vars[hash[0]] = hash[1];
		}
		return JSON.stringify(vars);
	}

	function set_layout_height_old() {
		if( jQuery("section.product-listing.default .car-item").length > 0 && jQuery('.masonry-main .all-cars-list-arch.masonry').length == 0) {
			// Select and loop the container element of the elements you want to equalise
			var highestBox = 0;

			// Reset height of elements
			$( '.car-item' ).height('');

			// Select and loop the elements you want to equalise
			jQuery('.car-item').each(function(){
				// If this box is higher than the cached highest then store it
				if(jQuery(this).height() > highestBox) {
					highestBox = jQuery(this).height();
				}
			});
			/* Set the height of all those children to whichever was highest*/
			if( jQuery("body").hasClass("page-template-sold-cars") || jQuery('.all-cars-list-arch').length > 0 ) {
				jQuery('.car-item').height(highestBox);
			}
		}
	}

	function set_layout_height() {
		if ( $('div.vehicle-listing:not(.masonry)').length > 0 ) {
			$('div.vehicle-listing:not(.masonry)').each(function(){
				// Select and loop the container element of the elements you want to equalise
				var highestBox = 0;
				var car_items  = $(this).find('.car-item');

				// Reset height of elements
				car_items.height('');

				// Select and loop the elements you want to equalise
				car_items.each(function(){
					// If this box is higher than the cached highest then store it
					if(jQuery(this).height() > highestBox) {
						highestBox = jQuery(this).height();
					}
				});
				car_items.height(highestBox);
			});
		}
	}

}( jQuery ) );
