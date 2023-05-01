/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Custom filters sortcode
	:: Magnific popup
	:: Potenza custom menu
	:: YouTube
	:: Vimeo
	:: Counter
	:: Search filter box
	:: Vehicles Search
	:: Video Slider
	:: Vehicles Conditions Tabs
	:: Vehicles By Type
	:: Dealer List
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	// Dirty trick;
	if ( window.location.href.indexOf('?') !== -1 && '' === window.location.search ) {
		let currrent_url = window.location.toString();
		window.history.pushState(null, null, currrent_url.slice( 0, -1 ) );
	}

	jQuery(window).load(function() {

		/*********************************
		:: Timeline inline css
		**********************************/
		$( '.timeline-item' ).on({
			mouseenter: function () {
				var hover_styles = $( this ).attr( 'data-hover_color' );
				$( this ).find( '.timeline-title' ).css( 'color', hover_styles );
			},
			mouseleave: function () {
				var pre_hover_color = $( this ).attr( 'data-pre_hover_color' );
				$( this ).find( '.timeline-title' ).css( 'color', pre_hover_color );
			}
		});

		/*********************************
		:: Multi tab shortcode
		**********************************/
		setTimeout(function(){
			cardealer_isotope();
		},500);

		$( document.body ).on( 'cdhl_multi_tab_event', function() {
			cardealer_isotope();
		});
	});

	function initMap() {
		$( '.cdfs-dealers-search-field.cdfs-dealers-search-location' ).each( function (i, el) {
			const location_wrap = el,
				location_search = $( location_wrap ).find('.cdfs-dealers-search-loc_search'),
				location_lat    = $( location_wrap ).find('.cdfs-dealers-search-loc_lat'),
				location_lng    = $( location_wrap ).find('.cdfs-dealers-search-loc_lng');

				const autocomplete = new google.maps.places.Autocomplete( location_search[0] );
				autocomplete.addListener("place_changed", function () {
					const place = autocomplete.getPlace();

					if ( ! place.geometry || ! place.geometry.location ) {
						// User entered the name of a Place that was not suggested and
						// pressed the Enter key, or the Place Details request failed.
						window.alert("Not a valid input. Please select a valid location.: '" + place.name + "'");
						return;
					}

					// If the place has a geometry, then get lat and lng.
					$( location_lat ).val( place.geometry.location.lat() );
					$( location_lng ).val( place.geometry.location.lng() );
				});

				// Clear lat/lng when search changed.
				// location_search.addEventListener("keydown", function () {
				$( location_search ).bind( 'keydown', function() {
					if ( 'ArrowDown' !== event.key && 'ArrowUp' !== event.key && 'ArrowLeft' !== event.key && 'ArrowRight' !== event.key && 'Home' !== event.key && 'End' !== event.key && 'Control' !== event.key && 'Shift' !== event.key && 'Alt' !== event.key ) {
						$( location_lat ).val( '' );
						$( location_lng ).val( '' );
					}
					if ( 'Enter' === event.key ) {
						event.preventDefault();
					}
				});

		});

	}
	google.maps.event.addDomListener(window, 'load', initMap);

	jQuery(document).ready(function($) {

		/*************************
		:: Custom filters sortcode
		*************************/

		if ( $( '.year-range-slider-wrapper' ).length > 0 ) {
			$( '.year-range-slider-wrapper' ).each( function( index, el ) {
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

				if ( shortcode_js_object.min_year ) {
					pgs_current_min_year = parseFloat( shortcode_js_object.min_year, 10 );
				}
				if ( shortcode_js_object.max_year ) {
					pgs_current_max_year = parseFloat( shortcode_js_object.max_year, 10 );
				}
				if ( jQuery.isFunction( jQuery.fn.slider ) ) {
					jQuery( year_slider_el ).slider({
						range: true,
						min: pgs_year_range_min,
						max: pgs_year_range_max,
						values: [pgs_current_min_year, pgs_current_max_year],
						create: function() {
							$( year_slider_min_el ).val( pgs_current_min_year );
							$( year_slider_max_el ).val( pgs_current_max_year );
							$( year_slider_years_el ).val( pgs_current_min_year + " - " + pgs_current_max_year );
							jQuery( document.body ).trigger( 'year_range_slider_updated', [ pgs_current_min_year, pgs_current_max_year ] );
							jQuery( document.body ).trigger( 'pgs_year_range_slider_create', [ pgs_current_min_year, pgs_current_max_year ] );
						},
						slide: function( event, ui ) {
							var min = ui.values[0],
								max = ui.values[1];
							$( year_slider_min_el ).val( min );
							$( year_slider_max_el ).val( max );
							$( year_slider_years_el ).val( min + " - " + max );
							jQuery( document.body ).trigger( 'year_range_slider_updated', [ min, max ] );
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
								$( document.body ).trigger( 'do_cfb_ajax_call_event', [ this, args ] );
							}
						}
					});
				}
			});
		}

		if ( $( '.price_slider_wrapper' ).length > 0 ) {
			$( '.price_slider_wrapper' ).each( function( index, el ) {
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

				if ( shortcode_js_object.min_price ) {
					pgs_current_min_price = parseInt( shortcode_js_object.min_price, 10 );
				}
				if ( shortcode_js_object.max_price ) {
					pgs_current_max_price = parseInt( shortcode_js_object.max_price, 10 );
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

		$(document).on( 'change','.custom-filters-box', function() {

			var year_slider_min = $( this ).parents( '.cdhl_custom-filters_wrapper' ).find( '.pgs-year-range-min' );
			var year_slider_max = $( this ).parents( '.cdhl_custom-filters_wrapper' ).find( '.pgs-year-range-max' );
			
			var args   = [];

			args['year_range_values'] = [];
			args['year_range_values']['pgs_year_range_min']   = $( year_slider_min ).val();
			args['year_range_values']['pgs_year_range_max']   = $( year_slider_max ).val();
			args['year_range_values']['default_year_min_val'] = $( year_slider_min ).data( 'yearmin' );
			args['year_range_values']['default_year_max_val'] = $( year_slider_max ).data( 'yearmax' );

			$( document.body ).trigger( 'do_cfb_ajax_call_event', [ jQuery( this ), args ] );
		});

		$( document.body ).on( 'do_cfb_ajax_call_event', function( e, $this, args ) {

			var cfb_ajax_paramete = get_cfb_ajax_parameter_with_cfb_type( $this, args );
			do_cfb_ajax_call( cfb_ajax_paramete['form_data'], cfb_ajax_paramete['col_class'], cfb_ajax_paramete['selected_attr'], cfb_ajax_paramete['sel_obj'], cfb_ajax_paramete['current_selected_attr'], cfb_ajax_paramete['uid'] );
		});

		jQuery(document).on( 'click', '.cfb-submit-btn', function (e) {
			e.preventDefault();
			get_cfb_form_field( this );
		});

		/*************************
		:: Magnific popup
		*************************/

		jQuery(document).ready(function($) {
			$('.pgs-popup-btn').magnificPopup({
				type:'inline',
				midClick: true,
				callbacks: {
					open: function() {
						$( document.body ).trigger( 'cardealer_owl_event' );
					},
				}
			});
		});

		/*********************************
		:: Potenza custom menu
		**********************************/

		$( document ).on( 'click', '.potenza-custom-menu.horizontal li a', function( e ) {
			e.preventDefault();

			var full_url = this.href;
			var parts    = full_url.split("#");
			var target   = parts[1];

			if ( typeof target === 'undefined' || ! $( '#' + target ).length > 0 ) {
				window.location.href = full_url;
				return;
			}

			var gap = 75;
				$( 'html,body' ).stop().animate({
					scrollTop:  $( '#' + target ).offset().top - gap
				}, 600 );
				$( '.potenza-custom-menu.horizontal li' ).removeClass( 'active' );
				$( this ).parent().addClass( 'active' );
			return false;
		});

		if ( $( '.potenza-custom-menu.horizontal' )[0] ) {
			var x = $(".potenza-custom-menu.horizontal").offset().top;
			$(window).scroll(function () {
				if (!$('.potenza-custom-menu.horizontal').hasClass('no-sticky')) {
					if ($(this).scrollTop() > x) {
						$('.potenza-custom-menu.horizontal').addClass('sticky');
					}
					else {
						$('.potenza-custom-menu.horizontal').removeClass('sticky');
					}
				}
				var scrollPos = $(document).scrollTop() + 80;
				$('.potenza-custom-menu.horizontal li a').each(function () {

					var currLink  = $(this);
					var urlString = currLink.attr("href");

					if( typeof urlString != "undefined" ){
						var currurl = urlString.split('#');
						if ( currurl[1] ) {
							var refElement = jQuery( '#' + currurl[1].replace(/[^a-z0-9\s]/gi, '' ) );
							if ( refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos ) {
								$('.potenza-custom-menu.horizontal li').removeClass("active");
								currLink.parent().addClass("active");
							}
							else{
								currLink.parent().removeClass("active");
							}
						}
					}
				});
			});
		}

		/**************
		:: youtube
		***************/
		jQuery.extend(true,jQuery.magnificPopup.defaults, {
			iframe: {
				patterns: {
					youtube: {
						index: 'youtu',
						id: function(url) {

							var m = url.match( /^.*(?:youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/ ),
								$start = 0;

							if ( !m || !m[1] ) return null;

								if(url.indexOf('t=') != - 1){

									var $split = url.split('t=');
									var hms = $split[1].replace('h',':').replace('m',':').replace('s','');
									var a = hms.split(':');

									if (a.length == 1){

										$start = a[0];

									} else if (a.length == 2){

										$start = (+a[0]) * 60 + (+a[1]);

									} else if (a.length == 3){

										$start = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);

									}
								}

								var suffix = '?autoplay=1';

								if( $start > 0 ){

									suffix = '?start=' + $start + '&autoplay=1';
								}

							return m[1] + suffix;
						},
						src: 'https://www.youtube.com/embed/%id%'
					}
				}
			}
		});

		/**************
		:: Vimeo
		***************/
		jQuery.extend(true,jQuery.magnificPopup.defaults, {
			iframe: {
				patterns: {
					vimeo: {
						index: 'vimeo.com/',
						id: '/',
						src: 'https://player.vimeo.com/video/%id%?autoplay=1'
					},
				}
			}
		});

		/*************
		:: counter
		**************/

		window.addEventListener( 'scroll',() => {
			cdhl_counter();
		});

		cdhl_counter();
		$( document.body ).on( 'cdhl_counter_event', function() {
			cdhl_counter();
		});

		$( document.body ).on({
			'touchmove': function(e) { 
				cdhl_counter();
			}
		});

		/*************
		:: Search filter box
		**************/
		jQuery( document ).on( 'change', '.search-filters-box', function(){
			var $this             = jQuery(this);
			var attributes        = {};
			var taxAttrs          = {};
			var total_vehicles    = 0;
			var tab_condition     = $this.attr('data-tid');
			var parent            = $this.parents('.vehicle-search-section').addClass('parentsssss');
			var matching_vehicles = jQuery(this).parents('.cardealer-tabcontent').find('p.matching-vehicles');

			jQuery( this ).parents( '.parentsssss' ).find( '.search-filters-box.' + tab_condition ).each( function(){
				var tid = jQuery(this).attr( 'data-id' );
				if ( tid ) {
					attributes[tid] = jQuery(this).parents('.cardealer-tabcontent').find('select[data-id='+tid+']');
				}
			});

			jQuery.each( attributes, function( taxonomy, attr){
				taxAttrs[taxonomy] = jQuery(attr).val();
			});

			var paramData = {
				action: 'cdhl_get_search_attr',
				tax_data: taxAttrs,
				term_tax: $this.data('id'),
				term_value: $this.val(),
				condition: $this.parents('.cardealer-tabcontent').data('condition')
			};
			jQuery.ajax({
				url : cardealer_js.ajaxurl,
				type:'post',
				dataType:'json',
				data: paramData,
				beforeSend: function(){
					jQuery(parent).find('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
					jQuery(parent).find('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
					jQuery(parent).find('.search-filters-box').prop('disabled',true);
					jQuery(parent).find('.csb-submit-btn').prop('disabled',true);
				},
				success: function(response){
					if( response.status == true ){
						if( response.attr_array.length > 0 ){
							jQuery(response.attr_array).each( function(index, attr){
								jQuery(attributes[attr.taxonomy]).html(jQuery("<option />").val('').text(attr.tax_label));
								total_vehicles = attr.vehicles_matched;
								jQuery(attr.tax_terms).each( function(index, terms){
									if( index == 0 && taxAttrs[attr.taxonomy] != '' ){
										jQuery(attributes[attr.taxonomy]).append(jQuery("<option />").attr('selected','selected').val(terms.slug).text(terms.name));
									} else {
										jQuery(attributes[attr.taxonomy]).append(jQuery("<option />").val(terms.slug).text(terms.name));
									}
								});
								jQuery(attributes[attr.taxonomy]).prop('disabled',false);
								jQuery(attributes[attr.taxonomy]).niceSelect('update');
							});
							matching_vehicles.html(total_vehicles);
						}
					}
				},
				complete: function(){
					jQuery(parent).find('.filter-loader').html('');
					jQuery(parent).find('.pagination-loader').html('');
					jQuery(parent).find('.search-filters-box').prop('disabled',false);
					jQuery(parent).find('.csb-submit-btn').prop('disabled',false);
				},
				error: function(){
					alert( shortcode_js_object.error_msg );
				}
			});
		});

		/********************************
		:: Vehicles Search
		********************************/

		cdhl_vehicles_search();
		$( document.body ).on( 'cdhl_vehicles_search_event', function() {
			cdhl_vehicles_search();
		});

		/********************************
		:: Video Slider
		********************************/

		cdhl_video_slider();
		$( document.body ).on( 'cdhl_video_slider_event', function() {
			cdhl_video_slider();
		});

		//bind our event here, it gets the current slide and pauses the video before each slide changes.
		jQuery(".sliderMain").on("beforeChange", function(event, slick, currentSlide) {
			var slideType, player, command;
			currentSlide = jQuery(slick.$slider).find(".slick-current");

			//determine which type of slide this, via a class on the slide container. This reads the second class, you could change this to get a data attribute or something similar if you don't want to use classes.
			slideType = currentSlide.attr("class").split(" ")[1];

			//get the iframe inside this slide.
			player = currentSlide.find("iframe").get(0);

			if (slideType == "vimeo") {
				command = {
					"method": "pause",
					"value": "true"
				};
			} else {
				command = {
					"event": "command",
					"func": "pauseVideo"
				};
			}

			//check if the player exists.
			if (player != undefined) {
				//post our command to the iframe.
				player.contentWindow.postMessage(JSON.stringify(command), "*");
			}
		});
		// video slider shortcode scripts ends

		/********************************
		:: Vehicles Conditions Tabs
		********************************/

		$( document ).on( 'click', '.cardealer-tabs li[data-tabs]', function( e ) {
			var this_parent  = $( this ).parents( '.cardealer-tabs' );
			var $tabsnav     = this_parent.find( '.tabs li' );
			var cur          = $tabsnav.index( this );
			var elm          = this_parent.find( '.cardealer-tabcontent:eq(' + cur + ')' );

			$tabsnav.removeClass( 'active' );
			this_parent.find( '.cardealer-tabcontent' ).each( function(){
				$( this ).hide();
			});

			var tab = jQuery( this ).data( 'tabs' );
			jQuery( this ).addClass( 'active' );
			jQuery( '#' + tab ).show();

			elm.addClass( 'pulse' );
			setTimeout( function() {
				elm.removeClass( 'pulse' );
			}, 220 );
		});

		$( document.body ).on( 'cdhl_vehicles_conditions_tabs_event', function() {
			$( document.body ).trigger( 'cardealer_owl_event' );
		});

		/********************************
		:: Dealer List
		********************************/
		if ( $( '.cdfs-dealers-wrapper' ).exists() ) {
			$( '.cdfs-dealers-wrapper' ).each( function( index, el ) {

				var dealers_wrapper = this,
					dealers_form    = $( dealers_wrapper ).find( '.cdfs-dealers-search-wrapper .cdfs-dealers-search-form' ),
					all_filters     = $( dealers_form ).find( '.cdfs-dealers-search-field.cdfs-dealers-search-filter select' ),
					search_btn      = $( dealers_form ).find( '#cdfs-dealers-search-search' ),
					reset_btn      = $( dealers_form ).find( '#cdfs-dealers-search-reset' );

					$( reset_btn ).on( 'click', function() {

						// $( dealers_form ).trigger( 'reset' );
						$( dealers_form ).find( '.cdfs-dealers-search-field.cdfs-dealers-search-select select' ).each( function (i, el) {
							$( el ).find( ':selected' ).attr( 'selected', false );
							$( el ).find( 'option:eq(0)' ).attr( 'selected', true );
						});

						// $( dealers_form ).find( '.cdfs-dealers-search-field.cdfs-dealers-search-select select option:eq(1)').prop('selected', true)
						$( dealers_form ).find( '.cdfs-dealers-search-field.cdfs-dealers-search-text input' ).val( '' );
						// $(':input','#myform')
							// .not(':button, :submit, :reset, :hidden')
							// .val( '' )
							// .prop( 'checked', false )
							// .prop( 'selected', false );
						$( dealers_form ).submit();
					});

				$( dealers_form ).submit( function( event ) {

					$( all_filters ).each( function (i, el) {
						if ( '' === $( el ).find(":selected").val() ) {
							$(el).attr( 'disabled', true );
						}
					});
					$( dealers_form ).find( '.cdfs-dealers-search-field.cdfs-dealers-search-orderby select' ).each( function (i, el) {
						if ( '' === $( el ).find(":selected").val() ) {
							$(el).attr( 'disabled', true );
						}
					});
					$( dealers_form ).find( '.cdfs-dealers-search-field.cdfs-dealers-search-text input, .cdfs-dealers-search-field.cdfs-dealers-search-text hidden' ).each( function (i, el) {
						if ( '' === $( el ).val() ) {
							$(el).attr( 'disabled', true );
						}
					});
					// if ( 'Enter' === event.key ) {
						// event.preventDefault();
					// }
				});

			});
		}

	});

	function cardealer_isotope() {
		var $isotope_wrapper = jQuery( '.isotope-wrapper' );
		if ( $isotope_wrapper.length > 0 ) {
			$isotope_wrapper.each( function() {
				var cptshuffle;
				var $unique_class      = jQuery( this ).attr( 'data-unique_class' );
				var $isotope_container = jQuery( '.' + $unique_class + ' .filter-container' );
				var $filters_container = jQuery( '[data-unique_class="' + $unique_class + '"]' ).find( '.isotope-filters' );
				var $layout            = jQuery( '[data-unique_class="' + $unique_class + '"]' ).attr( 'data-layout' );
				cptshuffle = new Shuffle( $isotope_container, {
					itemSelector: '.' + $layout + '-item',
					easing: 'ease-out',
				});

				$filters_container.on(
					'click',
					'button',
					function() {
						var filterValue = parseInt( jQuery( this ).attr( 'data-filter' ) );
						cptshuffle.filter( filterValue );
					}
				);

				$filters_container.each(
					function( i, buttonGroup ) {
						var $filters_buttongroup = jQuery( buttonGroup );
						$filters_buttongroup.on(
							'click',
							'button',
							function() {
								$filters_buttongroup.find( '.active' ).removeClass( 'active' );
								jQuery( this ).addClass( 'active' );
							}
						);
					}
				);
			});

			jQuery('.isotope-filters button.active').trigger('click');
		}
	}

	/*************************
	:: counter
	*************************/

	var counterRun = function() {
		if ( $( '.counter .timer' ).length > 0 ) {
			$( '.counter .timer' ).each(
				function () {
					if ( ! $( this ).hasClass( 'counter-animated' )) {
						var elementPos  = $( this ).offset().top;
						var topOfWindow = $( window ).scrollTop();
						topOfWindow     = topOfWindow + $( window ).height() - 30;
						var $elem       = $( this );
						var counter     = parseInt( $( this ).attr( 'data-to' ) );
						var speed       = parseInt( $( this ).attr( 'data-speed' ) );

						if ( ! speed ) {
							speed = 1500;
						}

						if ( elementPos < topOfWindow ) {
							$( this ).prop( 'Counter', 0 ).animate(
								{
									Counter: counter
								},
								{
									duration: speed,
									easing: 'swing',
									step: function (now) {
										$( this ).text( Math.ceil( now ) );
										if ( ! $elem.hasClass( 'counter-animated' ) ) {
											$elem.addClass( 'counter-animated' );
										}
									}
								}
							);
						}
					}
				}
			);
		}		
	};

	function cdhl_counter(){
		// Show animated counter
		counterRun();
	}

	// Code for search filter box
	function cdhl_vehicles_search(){

		if ( jQuery( 'select.cd-select-box' ).length ) {
			jQuery( 'select.cd-select-box' ).niceSelect();
		}

		jQuery( 'select.search-filters-box' ).prop( 'selectedIndex', 0 );
		jQuery( 'select.search-filters-box' ).niceSelect( 'update' );
		jQuery( '.cardealer-tabs input.vehicle_location' ).val( '' );

		// Submit filters
		jQuery( '.vehicle-search-section form' ).each (function( index ) {
			var search_form = jQuery( this ),
				search_btn  = jQuery( search_form ).find( '.csb-submit-btn' );

			jQuery( search_btn ).on( 'click', function(){
				jQuery( search_form ).trigger( 'submit' );
			});

			jQuery( search_form ).on( 'submit', function(e){
				jQuery( search_form ).find( 'input, select' ).each( function() {
					if ( jQuery( this ).val() == '' ) {
						jQuery( this ).attr( 'disabled', 'disabled' );
					}
				});
				jQuery( search_form ).attr( 'action', shortcode_js_object.cars_form_url );
				jQuery( search_form ).unbind( 'submit' ).submit();
			});
		});
	}

	function cdhl_video_slider(){
		if ( jQuery( '.sliderMain' ).length > 0 ) {

			var slick_rtl;
			if ( shortcode_js_object.is_rtl ) {
				slick_rtl = true;
			} else {
				slick_rtl = false;
			}

			if ( ! jQuery( '.sliderMain' ).hasClass( 'slick-initialized' ) ) {
				jQuery( '.sliderMain' ).slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false,
					fade: true,
					asNavFor: '.sliderSidebar',
					autoplay: false,
					rtl: slick_rtl,
					autoplaySpeed: 3000
				});
			}

			if ( ! jQuery( '.sliderSidebar' ).hasClass( 'slick-initialized' ) ) {
				jQuery( '.sliderSidebar' ).slick({
					slidesToShow: 5,
					slidesToScroll: 1,
					rtl: slick_rtl,
					asNavFor: '.sliderMain',
					dots: false,
					centerMode: false,
					focusOnSelect: true,
					vertical: false,
					arrows: true,
					responsive: [{
						breakpoint: 980, // tablet breakpoint
						settings: {
							slidesToShow: 4,
							slidesToScroll: 4
						}
					},
					{
						breakpoint: 480, // mobile breakpoint
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3
						}
					}]
				});
			}
		}
	}

	function get_cfb_ajax_parameter_with_cfb_type($this, args) {

		var filter_wrapper      = $($this).closest('.cdhl_custom-filters_wrapper');
		var parameters_arr      = {};
		var selectedattr        = [];
		var selobj              = {};
		var formdata            = '';
		var uid                 = '';
		var tid                 = '';
		var currentselectedattr = jQuery($this).attr('data-id');

		filter_wrapper.find('.custom-filters-box').each(function(){
			tid = jQuery(this).attr('data-id');
			var sel_val = jQuery(this).val();
			if(tid){
				uid = jQuery(this).attr('data-uid');
				selectedattr.push(tid);
				if(sel_val != ""){
					formdata += "&"+tid+"="+sel_val;
					selobj[tid] = sel_val;
				}
			}
		});

		var col_class ='4';
		var formId    = jQuery($this).closest(".col-6").attr('id');
		var box_type  = jQuery($this).hasClass('col-6');

		if(box_type) {
			col_class = '6';
		}

		if ( 'undefined' !== typeof(args) && args !== null ) {
			if( shortcode_js_object.is_year_range_active && 'year_range_values' in args ){

				var pgs_year_range_min   = args['year_range_values']['pgs_year_range_min'];
				var pgs_year_range_max   = args['year_range_values']['pgs_year_range_max'];
				var default_year_min_val = args['year_range_values']['default_year_min_val'];
				var default_year_max_val = args['year_range_values']['default_year_max_val'];

				if ( default_year_min_val != pgs_year_range_min || pgs_year_range_max != default_year_max_val ) {
					formdata += "&min_year="+pgs_year_range_min;
					formdata += "&max_year="+pgs_year_range_max;
					currentselectedattr = 'car_year';
				}
			}
		}

		formdata += "&current_attr="+currentselectedattr;

		parameters_arr['selected_attr'] = selectedattr;
		parameters_arr['sel_obj']       = selobj;
		parameters_arr['form_data']     = formdata;
		parameters_arr['uid']           = uid;
		parameters_arr['tid']           = tid;
		parameters_arr['col_class']     = col_class;
		parameters_arr['current_selected_attr'] = currentselectedattr;
		
		return parameters_arr;
	}

	/**
	 * This function only used in custom filter box widget
	 */
	function do_cfb_ajax_call( form_data, col_class, selected_attr, sel_obj, current_selected_attr, uid ) {
		var empty_select_opt_label = jQuery('.search-block').attr('data-empty-lbl');
		var select_empty_opt_label;

		if(empty_select_opt_label){
			select_empty_opt_label = empty_select_opt_label;
		} else {
			select_empty_opt_label = '--Select--';
		}
		jQuery.ajax({
			url: cardealer_js.ajaxurl,
			type: 'post',
			dataType: 'json',
			data:'action=cardealer_cars_filter_query'+form_data+'&cfb=yes&box_type='+col_class+'&selected_attr='+selected_attr+'&query_nonce='+shortcode_js_object.cardealer_cars_filter_query_nonce,
			beforeSend: function(){
				jQuery('.filter-loader').html('<span class="filter-loader"><i class="cd-loader"></i></span>');
				jQuery('.pagination-loader').html('<span class="pagination-loader"><i class="cd-loader"></i></span>');
				jQuery('.custom-filters-box').prop('disabled',true);
				jQuery('.cfb-submit-btn').prop('disabled',true);
			},
			success: function(response){
				if(response.status == "success"){
					if(typeof response.all_filters == "object") {

						// console.log( sel_obj );
						// console.log( selected_attr );

						// Reset the filters
						var reset_options    = "<option value=''>"+select_empty_opt_label+"</option>";
						jQuery( '.cdhl_custom-filters_wrapper .search-block' ).find( 'select[data-uid="' + uid + '"]' ).each( function() {
							var data_id = jQuery( this ).attr( 'data-id' );
							if ( data_id != 'car_mileage' ) {
								jQuery( this ).html( reset_options );
							}
						});

						jQuery.each(response.all_filters, function(key, value) {
							var new_options    = "<option value=''>"+select_empty_opt_label+"</option>";
							if (typeof value == "object") {
								jQuery.each(value, function (value_key, value_value) {
									jQuery.each(value_value, function (new_value_key, new_value_value) {
										var selected_val='';
										jQuery.each(sel_obj, function (sel_obj_key, sel_obj_value) {
											if(sel_obj_key == key){
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
								jQuery('#sort_'+key+'_'+uid).html(new_options);
							}
						});
					}
					jQuery('.filter-loader').html('');
					jQuery('.pagination-loader').html('');
					jQuery('.custom-filters-box').prop('disabled',false);
					jQuery('.cfb-submit-btn').prop('disabled',false);
					jQuery('select').niceSelect('update');
					$( document.body ).trigger( 'cd_data_tooltip_event' );

					// Lazyload
					if( jQuery('section.lazyload').length > 0 ){
						if(typeof response.tot_result != 'undefined' && response.tot_result < 1){
							jQuery('.all-cars-list-arch').attr('data-records', 0);
						} else {
							jQuery('.all-cars-list-arch').removeAttr('data-records');
						}
					}

				}
			},
			error: function(msg){
				alert( shortcode_js_object.error_msg );
				jQuery('.filter-loader').html('');
				jQuery('.pagination-loader').html('');
			}
		});
	}

	function get_cfb_form_field( $this ) {
		var form_data = '';
		var form_data_ajax;
		var pgs_min_price
		var pgs_max_price

		var filter_wrapper = $($this).closest('.cdhl_custom-filters_wrapper');

		filter_wrapper.find('select.custom-filters-box').each(function(){
			var tid = jQuery(this).attr('data-id');
			var sel_val = jQuery(this).val();

			if(sel_val != ""){
				form_data += '<input type="text" name="'+tid+'" value="' + sel_val + '" />';
			}
		});

		if ( filter_wrapper.find('.price_slider_wrapper').length > 0 ) {
			pgs_min_price = filter_wrapper.find('.pgs-price-slider-min').first().val();
			pgs_max_price = filter_wrapper.find('.pgs-price-slider-max').first().val();

			if ( typeof pgs_min_price != 'undefined' ) {
				form_data += '<input type="text" name="min_price" value="' + pgs_min_price + '" />';
			}
			
			if ( typeof pgs_max_price != 'undefined' ) {
				form_data += '<input type="text" name="max_price" value="' + pgs_max_price + '" />';
			}
		}

		if ( filter_wrapper.find('.year-range-slider-wrapper').length > 0 ) {
			var pgs_year_range_min = filter_wrapper.find('.pgs-year-range-min').first().val();
			var pgs_year_range_max = filter_wrapper.find('.pgs-year-range-max').first().val();
			
			if ( typeof pgs_year_range_min != 'undefined' ) {
				form_data += '<input type="text" name="min_year" value="' + pgs_year_range_min + '" />';
			}
			
			if ( typeof pgs_year_range_max != 'undefined' ) {
				form_data += '<input type="text" name="max_year" value="' + pgs_year_range_max + '" />';
			}
		}

		jQuery('<form>', {
			"id": "getCarsData",
			"html": form_data,
			"action": cardealer_obj.cars_url
		}).appendTo(document.body).submit();
	}

	function price_slider_display_values( min, max, dealer_slider_amt_el ) {
		var currency_symbol        = shortcode_js_object.currency_symbol;
		
		if ( min ) {
			min = cardealer_addCommas(min);
		}
		
		if ( max ) {
			max = cardealer_addCommas(max);
		}

		switch( shortcode_js_object.currency_pos ) {
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
	
	function cardealer_addCommas(nStr){
		return cardealer_number_format(nStr, parseInt(shortcode_js_object.decimal_places), shortcode_js_object.decimal_separator_symbol, shortcode_js_object.thousand_seperator_symbol);
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

}( jQuery ) );
