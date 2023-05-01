/*================================================
[  Table of contents  ]
================================================
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	jQuery(document).ready( function( $ ) {

		var form_data = '';
		$(document).on( 'change', '.select-sort-filters', function() {

			var current_clicked_attr  = $(this).attr('data-id');
			var current_clicked_value = $(this).val();
			var form_data             = '';
			var buil_data             = '';

			$( 'select.sort_' + $(this).attr('data-id') ).val( $(this).val() );
			jQuery('select').niceSelect('update');

			form_data += get_form_field( this, cd_get_slider_filter_var() );
			if ( current_clicked_value != '' ) {
				var current_value = current_clicked_value;
				form_data +="&current_value="+current_value;
				form_data +="&current_attr="+current_clicked_attr;
			}

			do_ajax_call( form_data );
		});

		$( document.body ).on( 'cardealer_filter_ajax_done', function(e , response ) {
			cardealer_filter_generate_filter_stripe();

			if ( response.hasOwnProperty('vehicle_make') && $.trim( response.vehicle_make ) ) {
				jQuery.each( response.vehicle_make, function( key, value ) {
					$( '#' + key + ' .cardealer-make-logos-wrap' ).html( value );
				} );
			}
		});

		/********************************************
		:: Search cars with autocomplte for listing
		*********************************************/

		/*
		if(document.getElementById('pgs_cars_search')){
			$( '#pgs_cars_search' ).autocomplete({
				search: function(event, ui) {
					jQuery('.auto-compalte-list ul').empty();
				},
				source: function( request, response ) {
					var $this    = $(this);
					var $element = $(this.element);
					var jqXHR    = $element.data('jqXHR');
						console.log( jqXHR );
					if ( typeof jqXHR !== 'undefined' ) {
						console.log( jqXHR.readyState  );
						jqXHR.abort();
					}

					$element.data('jqXHR', $.ajax({
						url: cardealer_js.ajaxurl,
						type: 'POST',
						dataType: "json",
						data: {
							'action': 'pgs_cars_list_search_auto_compalte',
							'search': request.term,
							'ajax_nonce': vehicle_filter_js_object.pgs_cars_list_search_auto_compalte_nonce
						},
						success: function( resp ) {

							// This callback is called when the request completes regardless of success or failure.
							$this.removeData('jqXHR');

							// Dismiss the search animation.
							$element.removeClass( 'ui-autocomplete-loading' );

							// Send response.
							console.log( resp );
							response( jQuery.map( resp, function( result ) {
								return {
									status: result.status,
									image: result.image,
									title: result.title,
									link_url: result.link_url,
									msg: result.msg
								};
							}));
						}
					}));
				},
				minLength: 2,
			}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {

				var html = '';
				if(item.status){
					html += '<a href="'+item.link_url+'">';
					html += '<div class="search-item-container">';
					if(item.image){
						html += item.image;
					}
					html += item.title;
					html += '</div>';
					html += '</a>';
				} else {
					html += item.msg;
				}
				return jQuery( "<li></li>" )
					.data( "ui-autocomplete-item", item )
					.append(html)
					.appendTo(jQuery('.auto-compalte-list ul'));
			};

			$( '#pgs_cars_search' ).blur(function() {
				var $this = $(this);
				var jqXHR = $(this).data('jqXHR');
				if ( typeof jqXHR !== 'undefined' ) {
					jqXHR.abort();
				}
				$this.removeData('jqXHR');
				$('.auto-compalte-list ul').empty();
			});
		}
		*/

		/********************************************
		:: Search for car listing page with full width
		*********************************************/

		/*
		jQuery('#pgs_cars_search_btn').on("click", function () {
			jQuery('#pgs_cars_search').val('');
			jQuery('.pgs_cars_search').toggle();
			jQuery('.auto-compalte-list ul').empty();
			return false;
		});
		*/

		$('#pgs_cars_pp_sold,#pgs_cars_orderby_sold').on('change',function(){
			var form_data = get_sold_filter_fields(this);
			$(form_data).appendTo('.sold-filter-frm');
			$('.sold-filter-frm').submit();
		});

		$('#pgs_cars_order_sold,#pgs_price_filter_btn-sold,.catlog-layout-sold').on('click',function(e){
			e.preventDefault();
			var form_data = get_sold_filter_fields(this);
			$(form_data).appendTo('.sold-filter-frm');
			$('.sold-filter-frm').submit();
		});

		dealer_price_filter();
		dealer_year_range_filter();

		if(typeof vehicle_filter_js_object != 'undefined' && vehicle_filter_js_object.cars_filter_with == 'yes'){
			/*
			* With ajax post dynamic filters
			*/
			$(document).on('click','#submit_all_filters',function(){
				form_data = get_form_field(this);
				do_ajax_call(form_data);
			});

			$(document).on( 'click', '.pgs-price-filter-btn, .year-range-filter-btn, #pgs_cars_search_btn, #pgs_cars_order, .catlog-layout', function(){
				form_data = get_form_field(this, cd_get_slider_filter_var());
				if ( ! $(this).hasClass( 'catlog-layout' ) ) {
					do_ajax_call( form_data );
				}
			});

			/*
			// DMK check
			$('#pgs_cars_search').keypress(function(e){
				if(e.which == 13){//Enter key pressed
					$('#pgs_cars_search_btn').click();
				}
			});
			*/

			$('#pgs_cars_pp,#pgs_cars_orderby').on('change',function(){
				form_data = get_form_field( this, cd_get_slider_filter_var() );
				do_ajax_call( form_data );
			});

			$(document).on('click','#cars-pagination-nav ul li a',function(){
				var page_link = $(this).text();
				var prev = $(this).hasClass('prev');
				var next = $(this).hasClass('next');
				form_data = get_form_field( this, cd_get_slider_filter_var() );
				if(prev){
					$('.pagination-nav ul li span').each(function(){

						if($(this).hasClass('current')){
							var cuernt_page = $(this).text();
							page_link = parseInt(cuernt_page)-1;
						}
					});
				}
				if(next){
					$('.pagination-nav ul li span').each(function(){
						if($(this).hasClass('current')){
							var cuernt_page = $(this).text();
							page_link = parseInt(cuernt_page)+1;
						}
					});
				}

				jQuery.ajax({
					url: cardealer_js.ajaxurl,
					type: 'post',
					dataType: 'json',
					data:'action=cardealer_cars_filter_query&paged='+page_link+form_data+'&query_nonce='+vehicle_filter_js_object.cardealer_cars_filter_query_nonce,
					beforeSend: function(){
						jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
						jQuery('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
						if( jQuery('.cars-top-filters-box').length ){
							jQuery('.cars-top-filters-box').append('<span class="filter-loader"><i class="cd-loader"></i></span>');
						}
						jQuery('.select-sort-filters').prop('disabled',true);
						jQuery('#submit_all_filters').prop('disabled',true);
						if ( $( '.vehicle-listing.vehicle-listing-featured' ).hasClass('owl-carousel') ) {
							$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).removeClass('owl-loaded');
						}
					},
					success: function(response){
						set_result_filters(response);
						jQuery('.filter-loader').html('');
						jQuery('.pagination-loader').html('');
						if( jQuery('.cars-top-filters-box .filter-loader').length ){
							jQuery('.cars-top-filters-box .filter-loader').remove();
						}
						jQuery('.vehicle-listing.vehicle-listing-main').html(response.data_html);
						if ( response.featured_cars_count > 0 ) {
							if ( $( '.vehicle-listing.vehicle-listing-featured' ).hasClass('owl-carousel') ) {
								$( '.featured-vehicles-listing-wrapper' ).slideDown( 'fast' );
								$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).trigger('replace.owl.carousel', response.featured_cars).trigger('refresh.owl.carousel');
								// $( document.body ).trigger( 'cardealer_set_layout_height_event' );

								$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).on( 'refreshed.owl.carousel', function(event) {
									$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).addClass('owl-loaded');
									// $( '.featured-vehicles-listing-wrapper' ).show();
								});
							} else {
								$( '.vehicle-listing.vehicle-listing-featured' ).html(response.featured_cars);
								$( '.featured-vehicles-listing-wrapper' ).slideDown( 'fast' );
							}
						} else {
							$( '.featured-vehicles-listing-wrapper' ).hide();
						}
						$('#cars-pagination-nav').html(response.pagination_html);
						$('.cars-order').html(response.order_html);
						jQuery('.select-sort-filters').prop('disabled',false);
						jQuery('#submit_all_filters').prop('disabled',false);
						$( document.body ).trigger( 'cardealer_set_layout_height_event' );
						// masonry style
						if( jQuery('.masonry-main .all-cars-list-arch.masonry').length > 0 ){
							setTimeout(function(){
								$( document.body ).trigger( 'cardealer_load_masonry_event' ); // Reload shuffle masonry
							}, 1000);
						}
						jQuery('select').niceSelect('update');
						$( document.body ).trigger( 'cd_data_tooltip_event' );
						window.history.pushState(null, null, vehicle_filter_js_object.cars_form_url+'?paged='+page_link+form_data);
						if( jQuery( '.cars-top-filters-box' ).length ){
							jQuery("html, body").animate( { scrollTop: jQuery('.cars-top-filters-box').offset().top }, 200 );
						}
					},
					error: function(msg){
						alert( vehicle_filter_js_object.error_msg );
						jQuery('.filter-loader').html('');
						jQuery('.pagination-loader').html('');
					}
				});
				return false;
			});

			$(document).on('click','#reset_filters', function( event ){
				event.preventDefault();

				var reset_filters_btn          = this,
					reset_filters_form_wrapper = $(this).closest('.listing_sort');

				var get_id    = $(this).attr('id');
				var form_data = '';
				var sort_by   = vehicle_filter_js_object.default_sort_by;//default dropdown sort option
				$('.select-sort-filters').each(function(){
					var sel_val = $(this).val('');
				});
				$('#pgs_cars_search').val('');
				$('#pgs_cars_pp').val($("#pgs_cars_pp option:first").val());
				$('#pgs_cars_orderby').val(sort_by);

				$('.price_slider_wrapper').each(function( index, el ) {
					var price_slider_wrap      = this,
						price_slider_el        = $( price_slider_wrap ).find('.slider-range'),
						price_slider_prices_el = $( price_slider_wrap ).find('.dealer-slider-amount'),
						price_slider_min_el    = $( price_slider_wrap ).find('.pgs-price-slider-min'),
						price_slider_max_el    = $( price_slider_wrap ).find('.pgs-price-slider-max'),
						price_slider_options   = $( price_slider_el ).slider('option');

					$( price_slider_el ).slider( 'values', [price_slider_options.min, price_slider_options.max] );
					$( price_slider_min_el ).val( price_slider_options.min );
					$( price_slider_max_el ).val( price_slider_options.max );
					price_slider_display_values( price_slider_options.min, price_slider_options.max, price_slider_prices_el );
				});

				if ( vehicle_filter_js_object.is_year_range_active ) {
					$('.slider-year-range').each(function(){
						var year_slider_el       = this,
							year_slider_wrap     = $( year_slider_el ).closest('.year-range-slider-wrapper'),
							year_slider_years_el = $( year_slider_wrap ).find('.dealer-slider-year-range'),
							year_slider_min_el   = $( year_slider_wrap ).find( '.pgs-year-range-min' ),
							year_slider_max_el   = $( year_slider_wrap ).find( '.pgs-year-range-max' ),
							year_slider_options  = $( year_slider_el ).slider('option');

						$( this ).slider( 'values', [year_slider_options.min, year_slider_options.max] );
						$( year_slider_min_el ).val( year_slider_options.min );
						$( year_slider_max_el ).val( year_slider_options.max );
						year_range_display_values( year_slider_options.min, year_slider_options.max, year_slider_years_el );
					});
				}

				form_data = get_form_field(this);
				do_ajax_call(form_data);
			});
		} else {
			$(document).on('click','#submit_all_filters',function(){
				get_form_field(this);
			});

			$('.pgs-price-filter-btn,#pgs_cars_search_btn,#pgs_cars_order,.catlog-layout').on('click',function(){
				const args = [];

				if ( $(this).hasClass('pgs-price-filter-btn') ) {
					let price_slider_wrap   = $(this).closest('.price_slider_wrapper'),
						price_slider_min_el = $( price_slider_wrap ).find('.pgs-price-slider-min'),
						price_slider_max_el = $( price_slider_wrap ).find('.pgs-price-slider-max');

					args['price_range_values'] = [];
					args['price_range_values']['pgs_min_price']   = $( price_slider_min_el ).val();
					args['price_range_values']['pgs_max_price']   = $( price_slider_max_el ).val();
					args['price_range_values']['default_min_val'] = $( price_slider_min_el ).attr('data-min');
					args['price_range_values']['default_max_val'] = $( price_slider_max_el ).attr('data-max');
				}

				get_form_field(this, args);
			});

			$('#pgs_cars_search').keypress(function(e){
				if(e.which == 13){//Enter key pressed
					$('#pgs_cars_search_btn').click();
				}
			});

			$( '#pgs_cars_pp, #pgs_cars_orderby' ).on( 'change', function() {
				get_form_field( this, cd_get_slider_filter_var() );
			});

			$(document).on('click', '#reset_filters', function( event ){
				event.preventDefault();

				var get_id    = $(this).attr('id');
				var form_data = '';

				$('.select-sort-filters').each(function(){
					var sel_val = $(this).val('');
				});
				$('#pgs_cars_search').val('');
				$('<form>', {
					"id": "getCarsData",
					"html": form_data,
					"action": vehicle_filter_js_object.cars_form_url
				}).appendTo(document.body).submit();
			});
		}

		if( ! jQuery('#dealer-slider-year-range').hasClass('dealer-slider-year-range') ){
			jQuery('#dealer-slider-year-range').addClass('dealer-slider-year-range');
		}

		$( document.body ).on( 'cdhl_custom_filters_event', function() {
			if ( jQuery('select.cd-select-box').length ) {
				jQuery('select.cd-select-box').niceSelect();
			}
			dealer_price_filter();
			dealer_year_range_filter();
		});

		// remove a filter
		$( 'ul.stripe-item' ).on( 'click', 'li', function(e){

			e.preventDefault();

			var data_type = $( this ).attr( 'data-type' );

			if ( data_type ) {
				$( this ).fadeOut( 400, function() {
					$( 'ul.stripe-item' ).find( "[data-type='" + data_type + "']").remove();
					$( '.sort_' + data_type ).val( '' );
					$( '.vehicle-location-input' ).val('');
					form_data = get_form_field( this, cd_get_slider_filter_var() );
					do_ajax_call( form_data );
				});
			}
		});
	});

	function set_result_filters( response ) {

		var sel_obj     = {};
		var all_filters = {};

		jQuery( '.select-sort-filters' ).each(function(){
			var tid     = jQuery( this ).attr( 'data-id' );
			var sel_val = jQuery(this).val();
			if ( sel_val != "" ) {
				sel_obj[tid] = sel_val;
			}
		});

		if ( 'all_filters' in response && typeof response.all_filters == 'object' ) {
			all_filters = response.all_filters;
		}

		if ( $('.sort-filters').exists() ) {
			$('.sort-filters').each(function (i, el) {
				var $filters = $( el );

				$filters.find('select.cd-select-box').each(function (i, el) {
					var filter_field   = $( this ),
						filter_id      = filter_field.attr('data-id'),
						filter_label   = filter_field.attr('data-tax'),
						filter_options = '<option value="">' + filter_label + '</option>',
						filter_value   = filter_field.val(),
						selected_val   = '';

					if ( filter_id in all_filters ) {
						$.each( all_filters[ filter_id ], function( index, items ) {
							$.each( items, function( item_k, item_v ) {
								if ( item_k == filter_value ) {
									selected_val = 'selected="selected"';
								}
								filter_options += '<option value="' + item_k + '" ' + selected_val + '>' + item_v + '</option>';
							});
						});
					}
					if ( filter_id !== 'car_mileage' ) {
						filter_field.html( filter_options );
					}
				});
			});
		}


		/*
		if( typeof response.all_filters == "object" ) {
			jQuery.each( response.all_filters, function( key, value ) {
				var tax_label   = jQuery( '.sort_' + key ).attr('data-tax');
				var new_options = "<option value=''>" + tax_label + "</option>";
				if ( typeof value == "object" ) {
					jQuery.each( value, function ( value_key, value_value ) {
						jQuery.each( value_value, function ( new_value_key, new_value_value ) {
							var selected_val = '';
							var selopkey     = '';
							jQuery.each( sel_obj, function (sel_obj_key, sel_obj_value) {
								if( sel_obj_key == key ) {
									selected_val = "selected='selected'";
								}
							});
							if(key != "car_mileage"){
								new_options += "<option value='" + new_value_key + "' "+selected_val+">" + new_value_value + "</option>";
							}
						});
					});
				}

				if ( key != "car_mileage" ) {
					jQuery( '.sort_' + key ).each( function() {
						jQuery( this ).html( new_options );
					});
				}
			});
		}
		*/
	}

	function get_query_parameters( str ) {
		return str.toString().replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
	}

	/*
	 * Get data using ajax method
	 */
	function do_ajax_call(form_data){
		var make_widgets  = {},
			form_data_new = form_data;

		// Prepapre make_widget data.
		if ( $( '.widget.widget-vehicle-make-logos' ).length > 0 ) {
			$( '.widget.widget-vehicle-make-logos' ).each(function( index ) {
				make_widgets[ $(this).attr('id') ] = $( this ).find('.cardealer-make-logos-wrap').data('widget_params');
			});
		}

		form_data_new = get_query_parameters( 'action=cardealer_cars_filter_query'+form_data_new+'&query_nonce='+vehicle_filter_js_object.cardealer_cars_filter_query_nonce );
		form_data_new.make_widgets = make_widgets;

		if(typeof vehicle_filter_js_object != 'undefined' && vehicle_filter_js_object.cars_filter_with == 'yes'){
			jQuery.ajax({
				url: cardealer_js.ajaxurl,
				type: 'post',
				dataType: 'json',
				data:form_data_new,
				beforeSend: function(){
					jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
					jQuery('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
					if( jQuery('.cars-top-filters-box').length ){
						jQuery('.cars-top-filters-box').append('<span class="filter-loader"><i class="cd-loader"></i></span>');
					}
					jQuery('.select-sort-filters').prop('disabled',true);
					jQuery('#submit_all_filters').prop('disabled',true);
					if ( $( '.vehicle-listing.vehicle-listing-featured' ).hasClass('owl-carousel') ) {
						$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).removeClass('owl-loaded');
					}
				},
				success: function(response){

					// Lazyload
					var cars_pp = '';
					if( jQuery('section.lazyload').length > 0 ) {
						if ( jQuery('#pgs_cars_pp').length > 0 ) {
							cars_pp = response.tot_result;
							jQuery('#pgs_cars_pp').val( cars_pp );
						}

						if(typeof response.tot_result != 'undefined' && response.tot_result < 1){
							jQuery('.all-cars-list-arch').attr('data-records', 0);
						} else {
							if( typeof response.found_posts != 'undefined' && typeof response.tot_result != 'undefined' && response.tot_result >= response.found_posts ) {
								jQuery('.all-cars-list-arch').attr('data-records', -2);
							} else {
								jQuery('.all-cars-list-arch').removeAttr('data-records');
							}
						}
					}

					jQuery('.vehicle-listing.vehicle-listing-main').html(response.data_html);
					if ( response.featured_cars_count > 0 ) {
						if ( $( '.vehicle-listing.vehicle-listing-featured' ).hasClass('owl-carousel') ) {
							$( '.featured-vehicles-listing-wrapper' ).slideDown( 'fast' );
							$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).trigger('replace.owl.carousel', response.featured_cars).trigger('refresh.owl.carousel');
							// $( document.body ).trigger( 'cardealer_set_layout_height_event' );

							$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).on( 'refreshed.owl.carousel', function(event) {
								$( '.vehicle-listing.vehicle-listing-featured.owl-carousel' ).addClass('owl-loaded');
								// $( '.featured-vehicles-listing-wrapper' ).show();
							});
						} else {
							$( '.vehicle-listing.vehicle-listing-featured' ).html(response.featured_cars);
							$( '.featured-vehicles-listing-wrapper' ).slideDown( 'fast' );
						}
					} else {
						$( '.featured-vehicles-listing-wrapper' ).hide();
					}
					jQuery('.pagination-nav').html(response.pagination_html);
					jQuery('.cars-order').html(response.order_html);
					set_result_filters(response);
					jQuery('.select-sort-filters').prop('disabled',false);
					jQuery('#submit_all_filters').prop('disabled',false);
					jQuery('.filter-loader').html('');
					if( jQuery('.cars-top-filters-box .filter-loader').length ){
						jQuery('.cars-top-filters-box .filter-loader').remove();
					}
					jQuery('.pagination-loader').html('');
					jQuery('.number_of_listings').html(response.tot_result_filter);
					$(window).data('records_processed', response.tot_result);
					$( document.body ).trigger( 'cardealer_set_layout_height_event' );
					// masonry style
					if( jQuery('.masonry-main .all-cars-list-arch.masonry').length > 0 ){
						setTimeout(function(){
							$( document.body ).trigger( 'cardealer_load_masonry_event' ); // Reload shuffle masonry
						}, 1000);
					}

					jQuery('select').niceSelect('update');
					$( document.body ).trigger( 'cd_data_tooltip_event' );


					var form_data_params = new URLSearchParams( form_data ),
						cars_form_addr   = new URL( vehicle_filter_js_object.cars_form_url );

					// Add cars_pp.
					if ( cars_pp ) {
						form_data_params.set( 'cars_pp', cars_pp );
					}

					// Remove vehicle_cat from params on vehicle_cat page.
					if ( vehicle_filter_js_object.is_vehicle_cat ) {
						form_data_params.delete( 'vehicle_cat' );
					}

					// Get current URL params.
					let cars_form_addr_params = new URLSearchParams( cars_form_addr.search );

					// Merge current URL params and form data params.
					let cars_form_params = new URLSearchParams({
					  ...Object.fromEntries( cars_form_addr_params ),
					  ...Object.fromEntries( form_data_params )
					});

					cars_form_addr.search = cars_form_params.toString();

					window.history.pushState( null, null, cars_form_addr );
					if( jQuery( '.cars-top-filters-box' ).length ){
						jQuery("html, body").animate( { scrollTop: jQuery('.cars-top-filters-box').offset().top }, 200 );
					}
					$( document.body ).trigger( 'cardealer_filter_ajax_done', [ response ] );
				},
				error: function(msg){
					alert( vehicle_filter_js_object.error_msg );
					jQuery('.filter-loader').html('');
					jQuery('.pagination-loader').html('');
				}
			});
		}
		return false;
	}

	function cardealer_filter_generate_filter_stripe() {
		var serch_filter = '';
		var selected_filter = [];

		$( 'select.select-sort-filters' ).each( function() {
			var tid      = jQuery(this).attr('data-id');
			var sel_val  = jQuery(this).val();
			var sel_txt  = jQuery(this).attr('data-tax');
			var sel_term = jQuery(this).find('option:selected').html();
			if ( sel_val != '' ) {

				if ( jQuery.inArray( tid, selected_filter ) == -1 ) {
					selected_filter.push( tid );

					var sel_term_safe = $('<textarea />').html( sel_term ).text();
						sel_term_safe = $('<textarea />').html( sel_term_safe ).text();
					serch_filter += '<li class="stripe-single-item stripe-item-'+tid+'" data-type="'+tid+'" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> '+sel_txt+' :  <span data-key="'+sel_val+'">'+sel_term_safe+'</span></a></li>';
				}
			}
		});

		$('.stripe-item').html( serch_filter );
	}

	/*
	 * Create dynamically form fields for filtering
	 */
	function get_form_field( $this, args ){
		var get_id          = jQuery($this).attr('id');
		var lay_style       = "view-grid";
		var form_data       = '';
		var form_data_ajax  = '';
		var serch_filter    = '';
		var selected_filter = [];

		jQuery( '.select-sort-filters' ).each(function(){
			var tid      = jQuery(this).attr('data-id');
			var sel_val  = jQuery(this).val();
			var sel_txt  = jQuery(this).attr('data-tax');
			var sel_term = jQuery(this).find('option:selected').html();
			if ( sel_val != "" ) {
				if ( jQuery.inArray( tid, selected_filter ) == -1 ) {

					selected_filter.push( tid );

					var sel_term_safe = $('<textarea />').html( sel_term ).text();
						sel_term_safe = $('<textarea />').html( sel_term_safe ).text();
					form_data += '<input type="text" name="'+tid+'" value="' + sel_val + '" />';
					form_data_ajax += "&"+tid+"="+sel_val;
					serch_filter += '<li class="stripe-single-item stripe-item-'+tid+'" data-type="'+tid+'" ><a href="javascript:void(0)"><i class="far fa-times-circle"></i> '+sel_txt+' :  <span data-key="'+sel_val+'">'+sel_term_safe+'</span></a></li>';
				}
			}
		});

		jQuery( '.stripe-item' ).html( serch_filter );

		var pgs_cars_pp = jQuery('#pgs_cars_pp').val();
		if(pgs_cars_pp){
			form_data += '<input type="text" name="cars_pp" value="' + pgs_cars_pp + '" />';
			form_data_ajax += "&cars_pp="+pgs_cars_pp;
		}

		var cars_orderby = jQuery('#pgs_cars_orderby').val();
		if(cars_orderby){
			form_data += '<input type="text" name="cars_orderby" value="' + cars_orderby + '" />';
			form_data_ajax += "&cars_orderby="+cars_orderby;
		}

		if ( 'undefined' !== typeof(args) && args !== null ) {

			if ( 'price_range_values' in args ) {
				var pgs_min_price   = args['price_range_values']['pgs_min_price'];
				var pgs_max_price   = args['price_range_values']['pgs_max_price'];
				var default_min_val = args['price_range_values']['default_min_val'];
				var default_max_val = args['price_range_values']['default_max_val'];

				if(default_min_val != pgs_min_price || pgs_max_price != default_max_val){
					form_data += '<input type="text" name="min_price" value="' + pgs_min_price + '" />';
					form_data += '<input type="text" name="max_price" value="' + pgs_max_price + '" />';
					form_data_ajax += "&min_price="+pgs_min_price;
					form_data_ajax += "&max_price="+pgs_max_price;
				}
			}

			//check is active year range slider
			if( vehicle_filter_js_object.is_year_range_active && 'year_range_values' in args ){

				var pgs_year_range_min   = args['year_range_values']['pgs_year_range_min'];
				var pgs_year_range_max   = args['year_range_values']['pgs_year_range_max'];
				var default_year_min_val = args['year_range_values']['default_year_min_val'];
				var default_year_max_val = args['year_range_values']['default_year_max_val'];

				if(default_year_min_val != pgs_year_range_min || pgs_year_range_max != default_year_max_val){
					form_data += '<input type="text" name="min_year" value="' + pgs_year_range_min + '" />';
					form_data += '<input type="text" name="max_year" value="' + pgs_year_range_max + '" />';
					form_data_ajax += "&min_year="+pgs_year_range_min;
					form_data_ajax += "&max_year="+pgs_year_range_max;
				}
			}
		}

		var cars_order = '';
		if(get_id == "pgs_cars_order"){
			cars_order = jQuery($this).attr('data-order');
			if(cars_order && cars_order != "" && cars_order != 'undefined'){
				form_data += '<input type="text" name="cars_order" value="' + cars_order + '" />';
				form_data_ajax += "&cars_order="+cars_order;
			}
		} else {
			cars_order = jQuery('#pgs_cars_order').attr('data-current_order');
			if(cars_order && cars_order != "" && cars_order != 'undefined'){
				form_data += '<input type="text" name="cars_order" value="' + cars_order + '" />';
				form_data_ajax += "&cars_order="+cars_order;
			}
		}

		if(jQuery($this).hasClass('catlog-layout')){
			lay_style = jQuery($this).attr('data-id');
			cookies.set( 'lay_style' , lay_style);

			form_data += '<input type="text" name="lay_style"  value="'+lay_style+'"/>';
			var cn_paged = null;
			jQuery('.pagination-nav ul li span').each(function(){
				if(jQuery(this).hasClass('current')){
					var cuernt_page = jQuery(this).text();
					if(cuernt_page != null){
						cn_paged = parseInt(cuernt_page);
					}
				}
			});

			if ( typeof vehicle_filter_js_object.cdfs_dashboard === 'undefined' ) {
				form_data += '<input type="text" name="paged" value="' + cn_paged + '" />';
			}

		} else {
			//if cookies not set then get default option value
			var lay_style;
			if(vehicle_filter_js_object.lay_style != ''){
				lay_style = vehicle_filter_js_object.lay_style;
			}

			var laystyle = cookies.get('lay_style');
			if(laystyle != null){
				lay_style = laystyle;
			}

			form_data += '<input type="text" name="lay_style"  value="'+lay_style+'"/>';
			form_data_ajax += "&lay_style="+lay_style;
		}

		var pgs_cars_search = jQuery('#pgs_cars_search').val();

		if(pgs_cars_search && pgs_cars_search != '' && pgs_cars_search != 'undefined'){
			form_data += '<input type="search" name="s" value="' + pgs_cars_search + '" />';
			form_data += '<input type="hidden" name="post_type" value="cars" />';
			form_data_ajax += "&s="+pgs_cars_search;
			form_data_ajax += "&post_type=cars";
		}

		if(vehicle_filter_js_object.is_vehicle_cat){
			if ( typeof vehicle_filter_js_object != 'undefined' && vehicle_filter_js_object.cars_filter_with == 'no' ) {
				form_data += '<input type="hidden" name="is_vehicle_cat" value="yes" />';
			}
			form_data_ajax += "&is_vehicle_cat=yes";
			form_data_ajax += "&vehicle_cat="+vehicle_filter_js_object.vehicle_cat;
		}

		var vehicle_location = jQuery('.vehicle-location-input').val();
		if(vehicle_location){
			form_data_ajax += "&vehicle_location="+vehicle_location;
			form_data += '<input type="hidden" name="vehicle_location" value="' + vehicle_location + '" />';
		}

		if(typeof vehicle_filter_js_object != 'undefined' && vehicle_filter_js_object.cars_filter_with == 'yes'){
			if(jQuery($this).hasClass('catlog-layout')){
				jQuery('<form>', {
					"id": "getCarsData",
					"html": form_data,
					"action": vehicle_filter_js_object.cars_form_url
				}).appendTo(document.body).submit();
			} else {
				return form_data_ajax;
			}
		} else {

			var cars_form_url_patams = getQueryParams( vehicle_filter_js_object.cars_form_url );
			if ( Object.keys( cars_form_url_patams ).length > 0 ) {
				Object.keys( cars_form_url_patams ).forEach(function(key) {
					form_data = '<input type="text" name="' + key + '" value="' + cars_form_url_patams[ key ] + '" />' + form_data;
				});
			}

			jQuery('<form>', {
				"id": "getCarsData",
				"html": form_data,
				"action": vehicle_filter_js_object.cars_form_url
			}).appendTo(document.body).submit();
		}
	}

	function getQueryParams( url ) {
		// Initialize an empty object
		let result = {};

		// get URL query string
		if ( url.indexOf('?') !== -1 ) {
			// remove the '?' character
			let queryParamArray = url.substring(url.indexOf('?') + 1).split('&');

			// iterate over parameter array
			queryParamArray.forEach(function(queryParam) {
				// split the query parameter over '='
				let item = queryParam.split("=");
				result[item[0]] = decodeURIComponent(item[1]);
			});
		}

		return result;
	}

	function price_slider_display_values( min, max, dealer_slider_amt_el ) {
		var currency_symbol = vehicle_filter_js_object.currency_symbol;

		if ( min ) {
			min = cardealer_addCommas(min);
		}

		if ( max ) {
			max = cardealer_addCommas(max);
		}

		switch( vehicle_filter_js_object.currency_pos ){
				case 'left':
					$(dealer_slider_amt_el).html( '<bdi><span class="currency">' + currency_symbol + '</span>' + min + '</bdi> - <bdi><span class="currency">' + currency_symbol + '</span>' + max + '</bdi>' );

				break;
				case 'left-with-space':
					$(dealer_slider_amt_el).html( '<bdi><span class="currency">' + currency_symbol + '</span>&nbsp;' + min + '</bdi> - <bdi><span class="currency">' + currency_symbol + '</span>&nbsp;' + max + '</bdi>' );

				break;
				case 'right-with-space':
					$(dealer_slider_amt_el).html( '<bdi>' + min + '&nbsp;<span class="currency">' + currency_symbol + '</span></bdi> - <bdi>' + max + '&nbsp;<span class="currency">' + currency_symbol + '</span></bdi>' );
				break;
				default:
					$(dealer_slider_amt_el).html( '<bdi>' + min + '<span class="currency">' + currency_symbol + '</span></bdi> - <bdi>' + max + '<span class="currency">' + currency_symbol + '</span></bdi>' );
			}
		jQuery( document.body ).trigger( 'price_slider_updated', [ min, max ] );
	};

	function year_range_display_values( min, max, year_slider_years_el ) {
		$( year_slider_years_el ).val( min + " - " + max);
		jQuery( document.body ).trigger( 'year_range_slider_updated', [ min, max ] );
	};

	function dealer_year_range_filter(){
		if ( typeof vehicle_filter_js_object === 'undefined' ) {
			return false;
		}
		$( '.year-range-slider-wrapper' ).each(function( index, el ) {
			var year_slider_wrap     = this,
				year_slider_location = $( year_slider_wrap ).data( 'range-location' ),
				year_slider_el       = $( year_slider_wrap ).find('.slider-year-range'),
				year_slider_years_el = $( year_slider_wrap ).find('.dealer-slider-year-range'),
				year_slider_min_el   = $( year_slider_wrap ).find('.pgs-year-range-min'),
				year_slider_max_el   = $( year_slider_wrap ).find('.pgs-year-range-max'),
				pgs_year_range_min   = $( year_slider_min_el ).data( 'yearmin' ),
				pgs_year_range_max   = $( year_slider_max_el ).data( 'yearmax' ),
				pgs_current_min_year = parseInt( pgs_year_range_min, 10 ),
				pgs_current_max_year = parseInt( pgs_year_range_max, 10 );

			if ( vehicle_filter_js_object.min_year ) {
				pgs_current_min_year = parseFloat( vehicle_filter_js_object.min_year, 10 );
			}
			if ( vehicle_filter_js_object.max_year ) {
				pgs_current_max_year = parseFloat( vehicle_filter_js_object.max_year, 10 );
			}
			if ( jQuery.isFunction(jQuery.fn.slider) ) {
				jQuery( year_slider_el ).slider({
					range: true,
					min: pgs_year_range_min,
					max: pgs_year_range_max,
					values: [pgs_current_min_year, pgs_current_max_year],
					create: function() {
						$( year_slider_min_el ).val( pgs_current_min_year );
						$( year_slider_max_el ).val( pgs_current_max_year );
						year_range_display_values( pgs_current_min_year, pgs_current_max_year, year_slider_years_el );
						jQuery( document.body ).trigger( 'pgs_year_range_slider_create', [ pgs_current_min_year, pgs_current_max_year ] );
					},
					slide: function( event, ui ) {
						var min = ui.values[0],
							max = ui.values[1];
						$( year_slider_min_el ).val( min );
						$( year_slider_max_el ).val( max );

						year_range_display_values( min, max, year_slider_years_el );

						$( '.year-range-slider-wrapper' ).not( year_slider_wrap ).each( function( index ) {

							$( $( this ).find('.pgs-year-range-min') ).val( min );
							$( $( this ).find('.pgs-year-range-max') ).val( max );

							year_range_display_values( min, max, $( this ).find( '.dealer-slider-year-range' ) );

							jQuery( $( this ).find( '.slider-year-range' ) ).slider({
								range: true,
								min: pgs_year_range_min,
								max: pgs_year_range_max,
								values: [min, max],
							});
						});

						jQuery( document.body ).trigger( 'pgs_year_range_slider_slide', [ ui.values[0], ui.values[1] ] );
					},
					change: function( event, ui ) {
						jQuery( document.body ).trigger( 'pgs_year_range_slider_change', [ ui.values[0], ui.values[1] ] );
					},
					stop: function( event, ui ) {
						if ( 'widgets' !== year_slider_location ) {
							var is_cfb = jQuery(this).attr('data-cfb');
							var args   = [];

							args['year_range_values'] = [];
							args['year_range_values']['pgs_year_range_min']   = $( year_slider_min_el ).val();
							args['year_range_values']['pgs_year_range_max']   = $( year_slider_max_el ).val();
							args['year_range_values']['default_year_min_val'] = pgs_year_range_min;
							args['year_range_values']['default_year_max_val'] = pgs_year_range_max;

							let price_slider_wrap   = $('.price_slider_wrapper').first(),
								price_slider_min_el = $( price_slider_wrap ).find('.pgs-price-slider-min'),
								price_slider_max_el = $( price_slider_wrap ).find('.pgs-price-slider-max');

							args['price_range_values'] = [];
							args['price_range_values']['pgs_min_price']   = $( price_slider_min_el ).val();
							args['price_range_values']['pgs_max_price']   = $( price_slider_max_el ).val();
							args['price_range_values']['default_min_val'] = $( price_slider_min_el ).attr('data-min');
							args['price_range_values']['default_max_val'] = $( price_slider_max_el ).attr('data-max');

							if ( 'yes' === is_cfb ) {
								$( document.body ).trigger( 'do_cfb_ajax_call_event', this, args );
							} else {
								var form_data = get_form_field( this, args );
								do_ajax_call( form_data );
							}
						}
					}
				});
			}
		});
	}

	function dealer_price_filter(){

		if ( typeof vehicle_filter_js_object === 'undefined' ) {
			return false;
		}

		$('.price_slider_wrapper').each(function( index, el ) {
			var price_slider_wrap     = this,
				price_slider_el       = $( price_slider_wrap ).find('.slider-range'),
				dealer_slider_amt_el  = $( price_slider_wrap ).find('.dealer-slider-amount'),
				price_slider_min_el   = $( price_slider_wrap ).find('.pgs-price-slider-min'),
				price_slider_max_el   = $( price_slider_wrap ).find('.pgs-price-slider-max'),
				pgs_min_price         = $( price_slider_min_el ).data( 'min' ),
				pgs_max_price         = $( price_slider_max_el ).data( 'max' ),
				pgs_current_min_price = parseInt( pgs_min_price, 10 ),
				pgs_current_max_price = parseInt( pgs_max_price, 10 ),
				pgs_price_range_step  = jQuery(pgs_max_price).data('step'),
				range_step            = ( pgs_price_range_step ) ? pgs_price_range_step : 100;

			if ( vehicle_filter_js_object.min_price ) {
				pgs_current_min_price = parseInt( vehicle_filter_js_object.min_price, 10 );
			}
			if ( vehicle_filter_js_object.max_price ) {
				pgs_current_max_price = parseInt( vehicle_filter_js_object.max_price, 10 );
			}

			if ( jQuery.isFunction(jQuery.fn.slider) ) {
				jQuery(price_slider_el).slider({
					range: true,
					min: pgs_min_price,
					max: pgs_max_price,
					values: [pgs_current_min_price, pgs_current_max_price],
					step: range_step,
					create: function() {
						$( price_slider_min_el ).val( pgs_current_min_price );
						$( price_slider_max_el ).val( pgs_current_max_price );
						price_slider_display_values( pgs_current_min_price, pgs_current_max_price, dealer_slider_amt_el );
						$( document.body ).trigger( 'pgs_price_slider_create', [ pgs_current_min_price, pgs_current_max_price ] );
					},
					slide: function( event, ui ) {
						var min = ui.values[0],
							max = ui.values[1];
						$( price_slider_min_el ).val( min );
						$( price_slider_max_el ).val( max );
						price_slider_display_values( min, max, dealer_slider_amt_el );
						$( document.body ).trigger( 'pgs_price_slider_slide', [ ui.values[0], ui.values[1] ] );
					},
					change: function( event, ui ) {
						$( document.body ).trigger( 'pgs_price_slider_change', [ ui.values[0], ui.values[1] ] );
					}
				});
			}
		});
	}

	function cardealer_addCommas(nStr){
		return cardealer_number_format(nStr, parseInt(vehicle_filter_js_object.decimal_places), vehicle_filter_js_object.decimal_separator_symbol, vehicle_filter_js_object.thousand_seperator_symbol);
	}

	function cardealer_number_format(number, decimals, decPoint, thousandsSep){
		decimals = decimals || 0;
		number = parseFloat(number);

		if(!decPoint || !thousandsSep){
			decPoint = '.';
			thousandsSep = ',';
		}

		var roundedNumber = Math.round( Math.abs( number ) * ('1e' + decimals) ) + '';
		var numbersString = decimals ? roundedNumber.slice(0, decimals * -1) : roundedNumber;
		var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
		var formattedNumber = "";

		while(numbersString.length > 3){
			formattedNumber = thousandsSep + numbersString.slice(-3) + formattedNumber;
			numbersString = numbersString.slice(0,-3);
		}
		return (number < 0 ? '-' : '') + numbersString + formattedNumber + (decimalsString ? (decPoint + decimalsString) : '');
	}

	/**
	 * get cars sold page template filters fields function
	 */
	function get_sold_filter_fields($this){

		var form_data = '';
		var order_sold = '';
		if($($this).hasClass('cars-order-sold')){
			order_sold = $($this).attr('data-order');
		} else {
			order_sold = $('#pgs_cars_order_sold').attr('data-current_order');
		}
		form_data += '<input type="hidden" name="cars_order"  value="'+order_sold+'"/>';

		var sold_layout = '';

		if($($this).hasClass('catlog-layout-sold')){
			sold_layout = $($this).attr('data-id');
		} else {
			var sts = jQuery('.view-grid-sold').attr('data-sts');
			if(sts == 'act'){
				sold_layout = 'view-grid';
			} else {
				sold_layout = 'view-list';
			}
		}
		form_data += '<input type="hidden" name="lay_style"  value="'+sold_layout+'"/>';
		return form_data;
	}

	function updateQueryStringParameter( uri, key, value ) {
		var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
		var separator = uri.indexOf('?') !== -1 ? "&" : "?";

		if (uri.match(re)) {
			return uri.replace(re, '$1' + key + "=" + value + '$2');
		} else {
			return uri + separator + key + "=" + value;
		}
	}

	function cd_get_slider_filter_var() {
		const args = [];

		if ( $('.price_slider_wrapper').length > 0 ) {
			var price_slider_wrap   = $('.price_slider_wrapper').first();
			var price_slider_min_el = $( price_slider_wrap ).find( '.pgs-price-slider-min' );
			var price_slider_max_el = $( price_slider_wrap ).find('.pgs-price-slider-max');

			args['price_range_values'] = [];
			args['price_range_values']['pgs_min_price']   = $( price_slider_min_el ).val();
			args['price_range_values']['pgs_max_price']   = $( price_slider_max_el ).val();
			args['price_range_values']['default_min_val'] = $( price_slider_min_el ).attr('data-min');
			args['price_range_values']['default_max_val'] = $( price_slider_max_el ).attr('data-max');
		}

		if ( $('.year-range-slider-wrapper').length > 0 ) {
			var year_slider_wrap   = $('.year-range-slider-wrapper').first();
			var year_slider_min_el = $( year_slider_wrap ).find( '.pgs-year-range-min' );
			var year_slider_max_el = $( year_slider_wrap ).find('.pgs-year-range-max');

			args['year_range_values'] = [];
			args['year_range_values']['pgs_year_range_min']   = $( year_slider_min_el ).val();
			args['year_range_values']['pgs_year_range_max']   = $( year_slider_max_el ).val();
			args['year_range_values']['default_year_min_val'] = $( year_slider_min_el ).attr('data-yearmin');
			args['year_range_values']['default_year_max_val'] = $( year_slider_max_el ).attr('data-yearmax');
		}

		return args;
	}

}( jQuery ) );
