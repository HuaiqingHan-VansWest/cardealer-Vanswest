(function($){
	"use strict";

	jQuery( document ).ready(function($) {
		// Check element exists.
		$.fn.exists = function () {
			return this.length > 0;
		};

		if ( $( '.featured-vehicle.featured-vehicle-tooltip' ).exists() ) {
			$( '.featured-vehicle.featured-vehicle-tooltip.featured-vehicle-tooltip-by-admin' ).tooltip({
				// track: true
				classes: {
					"ui-tooltip": "ui-tooltip-featured-vehicle ui-tooltip-featured-vehicle-by-admin",
				},
				position: {
					my: "center bottom",
					at: "right top"
				}
			});
			$( '.featured-vehicle.featured-vehicle-tooltip.featured-vehicle-tooltip-by-seller' ).tooltip({
				// track: true
				classes: {
					"ui-tooltip": "ui-tooltip-featured-vehicle ui-tooltip-featured-vehicle-by-seller",
				},
				position: {
					my: "center bottom",
					at: "right top"
				}
			});


			$('.featured-vehicle.featured-vehicle-tooltip.featured-vehicle-tooltip-by-seller').on('click', function () {
				return confirm( 'This vehicle is set featured by the seller. Are you sure you want to remove it from Featured?' );
			})
		}

	});

})(jQuery);
jQuery(document).ready(function($) {
	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	/* Mega Menu */
	jQuery('#cd_megamenu_wrapper').appendTo( jQuery('#post-body #post-body-content') ).show();

	// Third Party Testing - Tabs
	jQuery( document ).on( 'click', '.cardealer-debug-tab a', function( e ) {

		e.preventDefault();

		if ( ! jQuery( '.cardealer-debug-content-response' ).hasClass( 'hide' ) ) {
			jQuery( '.cardealer-debug-content-response' ).addClass( 'hide' );
		}

		if ( ! jQuery( '.cardealer-mailchimp-debug-content-response' ).hasClass( 'hide' ) ) {
			jQuery( '.cardealer-mailchimp-debug-content-response' ).addClass( 'hide' );
		}

		if ( ! jQuery( '.cardealer-vinquery-debug-content-response' ).hasClass( 'hide' ) ) {
			jQuery( '.cardealer-vinquery-debug-content-response' ).addClass( 'hide' );
		}

		jQuery( '.cardealer-debug-tab a' ).removeClass( 'activelink' );
		jQuery( this ).addClass( 'activelink' );
		var tag_id = jQuery( this ).attr( 'data-tag' );
		cookies.set( 'cardealer_debug_current_tab', tag_id );
		jQuery( '.cardealer-debug-content' ).removeClass( 'active' ).addClass( 'hide' );
		jQuery( '#' + tag_id ).addClass( 'active' ).removeClass( 'hide' );
	});

	// Third Party Testing - Debug Mail
	jQuery( "#cardealer-debug-send-mail" ).submit(function( event ) {
		event.preventDefault();

		var from_email = jQuery( '#debug-from-user-email' ).val();
		var to_email   = jQuery( '#debug-to-user-email' ).val();
		var data       = {'action': 'cardealer_debug_send_mail', 'ajax_nonce': cardealer_admin_js.pgs_mail_debug_nonce };

		if ( from_email ) {
			data.from_email = from_email;
		}

		if ( to_email ) {
			data.to_email = to_email;
		}

		jQuery.ajax({
			url: cardealer_admin_js.ajaxurl,
			type: 'POST',
			dataType: "json",
			data : data,
			beforeSend: function(){
				jQuery( '.cardealer-debug-content' ).addClass( 'loading' );
			},
			success: function( resp ) {
				jQuery( '.cardealer-debug-content-response.success' ).removeClass( 'success' );
				jQuery( '.cardealer-debug-content-response.failed' ).removeClass( 'failed' );
				if ( resp.status ) {
					jQuery( '.cardealer-debug-content-response' ).addClass( 'success' );
					jQuery( '.cardealer-debug-content-response' ).html( resp.msg );
				} else {
					jQuery( '.cardealer-debug-content-response' ).addClass( 'failed' );
					jQuery( '.cardealer-debug-content-response' ).html( resp.msg );
				}
			}
		}).done( function(){
			jQuery( '.cardealer-debug-content-response' ).removeClass( 'hide' );
			jQuery( '.cardealer-debug-content' ).removeClass( 'loading' );
		});
	});

	// Third Party Testing - Debug VINquery
	jQuery( "#cardealer-debug-vinquery" ).submit(function( event ) {
		event.preventDefault();

		var vinnumber = jQuery( '#debug-vinnumber' ).val();
		var data      = {'action': 'cardealer_debug_vinquery', 'ajax_nonce': cardealer_admin_js.pgs_vinquery_debug_nonce };

		if ( vinnumber ) {
			data.vinnumber = vinnumber;
		}

		jQuery.ajax({
			url: cardealer_admin_js.ajaxurl,
			type: 'POST',
			dataType: "json",
			data : data,
			beforeSend: function(){
				jQuery( '.cardealer-debug-content' ).addClass( 'loading' );
			},
			success: function( resp ) {
				jQuery( '.cardealer-vinquery-debug-content-response.success' ).removeClass( 'success' );
				jQuery( '.cardealer-vinquery-debug-content-response.failed' ).removeClass( 'failed' );
				if ( resp.status ) {
					jQuery( '.cardealer-vinquery-debug-content-response' ).addClass( 'success' );
					jQuery( '.cardealer-vinquery-debug-content-response' ).html( resp.msg );
				} else {
					jQuery( '.cardealer-vinquery-debug-content-response' ).addClass( 'failed' );
					jQuery( '.cardealer-vinquery-debug-content-response' ).html( resp.msg );
				}
			}
		}).done( function(){
			jQuery( '.cardealer-vinquery-debug-content-response' ).removeClass( 'hide' );
			jQuery( '.cardealer-debug-content' ).removeClass( 'loading' );
		});
	});

	// Third Party Testing - Debug Mailchimp
	jQuery( document ).on( 'click', '#debug-user-mailchimp', function( event ) {
		event.preventDefault();

		var data      = {'action': 'cardealer_debug_mailchimp', 'ajax_nonce': cardealer_admin_js.pgs_mailchimp_debug_nonce };
		jQuery.ajax({
			url: cardealer_admin_js.ajaxurl,
			type: 'POST',
			dataType: "json",
			data : data,
			beforeSend: function(){
				jQuery( '.cardealer-debug-content' ).addClass( 'loading' );
			},
			success: function( resp ) {
				jQuery( '.cardealer-mailchimp-debug-content-response.success' ).removeClass( 'success' );
				jQuery( '.cardealer-mailchimp-debug-content-response.failed' ).removeClass( 'failed' );
				if ( resp.status ) {
					jQuery( '.cardealer-mailchimp-debug-content-response' ).addClass( 'success' );
					jQuery( '.cardealer-mailchimp-debug-content-response' ).html( resp.msg );
				} else {
					jQuery( '.cardealer-mailchimp-debug-content-response' ).addClass( 'failed' );
					jQuery( '.cardealer-mailchimp-debug-content-response' ).html( resp.msg );
				}
			}
		}).done( function(){
			jQuery( '.cardealer-mailchimp-debug-content-response' ).removeClass( 'hide' );
			jQuery( '.cardealer-debug-content' ).removeClass( 'loading' );
		});
	});

	// Third Party Testing - Debug PDF Generator
	if ( $( '.cardealer-debug-pdf-generator-vehicle' ).exists() && $( '.cardealer-debug-pdf-generator-html-template' ).exists() && $( '#cardealer-debug-pdf-generator-check-pdf' ).exists() ) {
		jQuery( document ).on( 'click', '#cardealer-debug-pdf-generator-check-pdf', function( event ) {
			event.preventDefault();

			var vehicle_id          = $('.cardealer-debug-pdf-generator-vehicle > select').val(),
				template            = $('.cardealer-debug-pdf-generator-html-template > select').val(),
				current_tab_content = $(this).closest('.cardealer-debug-content'),
				response_el         = current_tab_content.find( '.cardealer-debug-response' );

			var data = {
				action: 'cardealer_debug_generate_pdf',
				ajax_nonce: cardealer_admin_js.cardealer_debug_nonce,
				id: vehicle_id,
				pdf_template_title: template,
			};
			jQuery.ajax({
				url: cardealer_admin_js.ajaxurl,
				type: 'POST',
				dataType: "json",
				data : data,
				beforeSend: function(){
					response_el.addClass( 'hide' );
					jQuery( '.cardealer-debug-content' ).addClass( 'loading' );
				},
				success: function( resp ) {
					response_el.removeClass( 'success' );
					response_el.removeClass( 'failed' );

					if ( resp.status ) {
						response_el.addClass( 'success' );
						response_el.html( resp.msg );
					} else {
						response_el.addClass( 'failed' );
						response_el.html( resp.msg );
					}
				}
			}).done( function(){
				response_el.removeClass( 'hide' );
				jQuery( '.cardealer-debug-content' ).removeClass( 'loading' );
			});

		});
	}

});

function show_cd_map_canvas() {
	map = new google.maps.Map(document.getElementById( 'cd-map-canvas' ), {
		center: { lat: -34.397, lng: 150.644 },
		zoom: 8,
	});
}
