/*================================================
[  Table of contents  ]
================================================
:: Menu Height
:: Document ready functions
	:: Search
	:: mega menu
======================================
[ End table content ]
======================================*/

( function( $ ) {
	"use strict";

	/*******************
	:: Menu Height
	*******************/

	jQuery(document).ready(function($) {

		/**************************************
		:: Search cars with autocomplte
		***************************************/

		// search-3
		if ( $('.cd-search-wrap').size() > 0 ) {
			$('.cd-search-wrap').each(function(i,el) {
				var search_wrap       = $(this),
					search_input      = search_wrap.find('.cd-search-autocomplete-input'),
					seach_type        = search_input.data('seach_type'),
					search_open_btn   = search_wrap.find('.search-open-btn'),
					search_submit_btn = search_wrap.find('.cd-search-submit'),
					autocomplete_wrap = search_wrap.find('.cd-search-autocomplete'),
					autocomplete_ul   = autocomplete_wrap.find('.cd-search-autocomplete-list'),
					search_min_length = 2;

				// Header search open.
				if ( search_open_btn.length > 0 ); {
					search_open_btn.on('click', function () {
						if ( ! search_wrap.hasClass('search-open') ) {
							search_wrap.addClass('search-open');
						} else {
							search_wrap.removeClass('search-open');
						}
						autocomplete_ul.empty();
						autocomplete_ul.removeClass('has-autocomplete-data');
						search_input.val('');
						return false;
					});
				}


				search_input.on('keypress', function (e) {
					if ( e.which == 13 ){ //Enter key pressed.
						search_submit_btn.click();
					}
				});

				// On submit button click.
				if ( search_submit_btn.length > 0 ); {
					search_submit_btn.on('click', function () {
						autocomplete_ul.empty();
						autocomplete_ul.removeClass('has-autocomplete-data');
					});
				}

				$(document).click(function (e){
					if ( ! search_wrap.is( e.target ) && search_wrap.has( e.target ).length === 0 ) {
						if ( search_wrap.hasClass('menu-search-wrap') && search_wrap.hasClass('search-open') ) {
							search_wrap.removeClass('search-open');
						}

						if ( autocomplete_ul.hasClass('has-autocomplete-data') ) {
							autocomplete_ul.empty();
							autocomplete_ul.removeClass('has-autocomplete-data');
							// search_input.val('');
						}
					}
				});

				search_input.on('input', function() {
					var search_input_value = this.value;
					if ( search_input_value.length < search_min_length ) {
						autocomplete_ul.empty();
						autocomplete_ul.removeClass('has-autocomplete-data');
					}
					// $(".dDimension:contains('" + dInput + "')").css("display","block");
				});

				var search_input_autocomplete = search_input.autocomplete({
					minLength: search_min_length,
					search: function(event, ui) {
						autocomplete_ul.empty();
						autocomplete_ul.removeClass('has-autocomplete-data');
						// search_input.val('');
					},
					source: function( request, response ) {
						jQuery.ajax({
							url: cardealer_js.ajaxurl,
							type: 'POST',
							dataType: "json",
							data: {
								'action': 'pgs_auto_complate_search',
								'ajax_nonce': cardealer_js.pgs_auto_complate_search_nonce,
								'search': request.term,
								'seach_type': seach_type,
							},
							beforeSend: function(){
							},
							success: function( resp ) {
								response( jQuery.map( resp, function( result ) {
									var return_data = {
										status: result.status,
										image: result.image,
										title: result.title,
										link_url: result.link_url,
										msg: result.msg
									};
									return return_data;
								}));
							}
						}).done( function(){
							// jQuery('#menu-searchform').find('i.fa-search').show();
							// jQuery('#menu-searchform').find('span.cd-loader').remove();
						});
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
					autocomplete_ul.addClass('has-autocomplete-data');
					return jQuery( "<li></li>" )
						.data( "ui-autocomplete-item", item )
						.append(html)
						.appendTo( autocomplete_ul );
				};
			});
		}

		/*************************
		:: mega menu
		*************************/
		// Sticky Top bar setting
		var screen_width = screen.width;
		jQuery(document).scroll(function() {
			if( cardealer_js.sticky_topbar == true ) {
				var sticky = jQuery('.topbar'),
				scroll = jQuery(window).scrollTop();
				if (scroll >= 250 && screen_width > 992){
					sticky.addClass('topbar_fixed');
				} else {
					sticky.removeClass('topbar_fixed');
				}
			}
		});

		var $mobile_sticky_status = (cardealer_js.sticky_header_mobile == true)? true: false;
		var $desktop_sticky_status = (cardealer_js.sticky_header_desktop == true)? true: false;
		jQuery('#menu-1').megaMenu({
			// DESKTOP MODE SETTINGS
			logo_align          : 'left',		// align the logo left or right. options (left) or (right)
			links_align         : 'left',      	// align the links left or right. options (left) or (right)
			socialBar_align     : 'left',     	// align the socialBar left or right. options (left) or (right)
			searchBar_align     : 'right',    	// align the search bar left or right. options (left) or (right)
			trigger             : 'hover',    	// show drop down using click or hover. options (hover) or (click)
			effect              : 'fade',     	// drop down effects. options (fade), (scale), (expand-top), (expand-bottom), (expand-left), (expand-right)
			effect_speed        : 400,        	// drop down show speed in milliseconds
			sibling             : true,       	// hide the others showing drop downs if this option true. this option works on if the trigger option is "click". options (true) or (false)
			outside_click_close : true,       	// hide the showing drop downs when user click outside the menu. this option works if the trigger option is "click". options (true) or (false)
			top_fixed           : false,      	// fixed the menu top of the screen. options (true) or (false)
			sticky_header       : $desktop_sticky_status,// menu fixed on top when scroll down down. options (true) or (false)
			sticky_header_height: 250,  		// sticky header height top of the screen. activate sticky header when meet the height. option change the height in px value.
			menu_position       : 'horizontal', // change the menu position. options (horizontal), (vertical-left) or (vertical-right)
			full_width          : false,        // make menu full width. options (true) or (false)
			// MOBILE MODE SETTINGS
			mobile_settings     : {
				collapse            : true,     // collapse the menu on click. options (true) or (false)
				sibling             : true,     // hide the others showing drop downs when click on current drop down. options (true) or (false)
				scrollBar           : true,     // enable the scroll bar. options (true) or (false)
				scrollBar_height    : 400,      // scroll bar height in px value. this option works if the scrollBar option true.
				top_fixed           : false,    // fixed menu top of the screen. options (true) or (false)
				sticky_header       : $mobile_sticky_status,     // menu fixed on top when scroll down down. options (true) or (false)
				sticky_header_height: 200       // sticky header height top of the screen. activate sticky header when meet the height. option change the height in px value.
			}
		});

		if(document.getElementById('mega-menu-wrap-primary-menu')){
			jQuery('.menu-mobile-collapse-trigger').hide();
		}

	});

}( jQuery ) );
