/*================================================
[  Table of contents  ]
================================================
:: window load functions
	:: Lazyload
	:: Preloader
:: Document ready functions
	:: Video Popup
	:: placeholder
	:: tooltip
	:: Photoswipe popup gallery for car listing page
	:: Social share
	:: NICE SELECT
	:: iOS Modal Fix
	:: back-to-top
	:: Contact Form 7 - Show Hide Fields
	:: Review Stamp Popup
======================================
[ End table content ]
======================================*/

var native_share_available = false;
if ( navigator.canShare ) {
	native_share_available = true;
}

( function( $ ) {
	"use strict";

	jQuery(window).load(function() {
		/* ---------------------------------------------
		 Lazyload
		 --------------------------------------------- */
		cardealer_lazyload();

		/*********************
		:: Preloader
		*********************/
		if( typeof cardealer_options_js == 'undefined' ) {
			jQuery("#load").fadeOut();
			jQuery('#loading').delay(0).fadeOut('slow');
		}
	});

	jQuery(document).ready(function($) {

		/***************************************
		:: Vehicle Buttons
		***************************************/

		$( document ).on( 'click', '.vehicle-button-link.vehicle-button-link-type-js_event', function (e) {
			e.preventDefault(e);

			var eventName = $(this).data('event');
			$( document.body ).trigger( eventName, [this] );
		});

		/****************
		:: Video Popup
		*****************/

		cdhl_video();
		$( document.body ).on( 'cdhl_video_event', function() {
			cdhl_video();
		});

		/*************************
		:: placeholder
		*************************/

		jQuery('[placeholder]').focus(function() {
			var input =jQuery(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
				input.removeClass('placeholder');
			}
		}).blur().parents('form').submit(function() {
			jQuery(this).find('[placeholder]').each(function() {
				var input =jQuery(this);
				if (input.val() == input.attr('placeholder')) {
					input.val('');
				}
			});
		});

		/*************************
		:: tooltip
		*************************/

		cd_data_tooltip();
		$( document.body ).on( 'cd_data_tooltip_event', function() {
			cd_data_tooltip();
		});

		/*************************************************
		:: Photoswipe popup gallery for car listing page
		**************************************************/

		jQuery( document ).on("click", ".psimages", function() {
			var pswpElement = document.querySelectorAll('.pswp')[0];
			var items = [];
			var imgsrc;
			var imgdata;
			var imgurl;

			imgsrc = jQuery(this).closest('.pssrcset').find('.psimages').data('image');
			imgurl=imgsrc.split(',');

			for(var i=0;i<imgurl.length;i++){
				var item = {
					src : imgurl[i],
					w: 1024,
					h: 683
				};
				items.push(item);
			}
			var options = {
				history: false,
				focus: false,
				showAnimationDuration: 0,
				hideAnimationDuration: 0
			};
			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
			gallery.init();
		});

		/****************
		:: Social share
		*****************/
		var filename;
		$('.cardealer-share .cardealer-share-action .cardealer-share-action-link').tooltip({
			// trigger: 'click',
			template: '<div class="tooltip cardealer-share-action-link-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
		});
		$( document ).on( 'click', '.cardealer-share .cardealer-share-action .cardealer-share-action-link', function(e) {
			e.preventDefault();


			var $share_link   = $(this),
				$share_wrap   = $share_link.closest('.cardealer-share'),
				modal_id      = $share_link.attr('data-modal_id'),
				native_params = $share_link.data('native_params');

			var share_image = native_params.image;

			if ( native_share_available && $share_wrap.hasClass('cardealer-share-native-share-enabled') ) {
				// console.log( 'Init native share...' );
				init_native_share( $share_wrap, native_params )
			} else {
				// console.log( 'Init static share...' );
				$('#'+modal_id).modal('show');
			}
		});

		function init_native_share( $share_wrap, native_params ) {
			if ( typeof share_image !== 'undefined' ) {
				get_native_share_image_blob( share_image )
					.then(function(data) {
						// Run this when your request was successful
						var file        = new File([data], filename, {type: data.type});
						var files_array = [file];

						native_params.files = files_array;
						initiate_share_popup( $share_wrap, native_params );
					}).catch(function(err) {
						// Run this when promise was rejected via reject()
						initiate_share_popup( $share_wrap, native_params );
					})
			} else {
				initiate_share_popup( $share_wrap, native_params );
			}
		}

		function get_native_share_image_blob( share_image ) {
			return new Promise(function(resolve, reject) {
				jQuery.ajax({
					url:share_image,
					cache:false,
					xhr:function(){// Seems like the only way to get access to the xhr object
						var xhr = new XMLHttpRequest();
						xhr.responseType= 'blob'
						return xhr;
					},
					success: function(data){
						if ( typeof share_image == 'undefined' ) {
							reject(data);
						} else {
							filename = share_image.split('/').pop();
						}
						// console.log( data );
						resolve(data); // Resolve promise and go to then()
					},
					error:function(err){
						// console.log( err );
						reject(err); // Reject the promise and go to catch()
					}
				});

			});
		}

		function initiate_share_popup( $share_wrap, native_share_data ) {

				delete native_share_data.image;
				var share_data = native_share_data;

				for (const [key, value] of Object.entries(native_share_data)) {
					if ( ! navigator.canShare({ [key]: value }) ) {
						delete native_share_data[key];
					}
				}


				var all_supported = Object.entries(native_share_data).every(([key, value]) => {
					var canshare_stat = navigator.canShare({ [key]: value });

					if ( ! canshare_stat ) {
						// delete share_data[key];
						native_share_data.shift()
					}
					return canshare_stat;
				});

				navigator.share( native_share_data )
					.then(function() {
						// console.log('Successful shared.');
					})
					.catch(function(error) {
						// console.log('Error sharing:', error);
						// window.open($(self).attr('href'), '_blank');
					});

		}

		/***********************
		:: NICE SELECT
		:: For select box design
		************************/

		if ( jQuery( 'select.cdfs-nice-select' ).length > 0 ) {
			jQuery( 'select.cdfs-nice-select' ).niceSelect();
		}

		if ( jQuery( 'select.cd-select-box' ).length > 0 ) {
			jQuery( 'select.cd-select-box' ).niceSelect();
		}

		if ( jQuery( '.woocommerce-ordering select.orderby').length > 0 ) {
			jQuery( '.woocommerce-ordering select.orderby').niceSelect();
		}

		if( jQuery( '.sidebar-widget select:not(.cd-select-box)').length > 0 ) {
			jQuery( '.sidebar-widget select:not(.cd-select-box)').niceSelect();
		}

		if ( jQuery( '.widget.widget-vehicle-categories:not(.sidebar-widget) select.vehicle-categories-dropdown' ).length > 0 ) {
			jQuery( '.widget.widget-vehicle-categories:not(.sidebar-widget) select.vehicle-categories-dropdown' ).niceSelect();
		}

		/***********************
		:: iOS Modal Fix
		************************/
		// Detect ios 11_0_x affected
		// NEED TO BE UPDATED if new versions are affected
		var ua = navigator.userAgent,
		iOS = /iPad|iPhone|iPod/.test(ua),
		iOS11 = /OS 11_0_1|OS 11_0_2|OS 11_0_3/.test(ua);

		// ios 11 bug caret position
		if ( iOS && iOS11 ) {

			// Add CSS class to body
			jQuery("body").addClass("iosmodalFix");

		}

		/*************************
		:: back-to-top
		*************************/

		if ( jQuery('.car-top').length > 0 ) {
			var $scrolltop = jQuery('.car-top');
			$( document ).on( 'scroll', function() {
				if ( jQuery( window ).scrollTop() >= 200 ) {
					$scrolltop.addClass( 'show' );
					$scrolltop.addClass( 'car-down' );
				} else {
					$scrolltop.removeClass( 'show' );
					setTimeout( function(){ $scrolltop.removeClass( 'car-down' ); }, 300 );
				}
			});
			$scrolltop.on( 'click', function () {
				jQuery( 'html,body' ).animate({ scrollTop: 0 }, 800 );
				jQuery( this ).addClass("car-run");
				setTimeout( function(){ $scrolltop.removeClass('car-run'); }, 1000 );
				return false;
			});
		}

		/*************************
		:: Contact Form 7 - Show Hide Fields
		*************************/

		/* Contact Form 7 - Show Hide Fields - Start */
		$( 'form.wpcf7-form' ).each(function () {
			var $form = jQuery( this ); // only add form is its class is "wpcf7-form" and if the form was not previously added
			var cdhl_cf7_show_animation_time = 200;
			var cdhl_cf7_hide_animation_time = 200;
			var cdhl_cf7_show_animation    = {
			  "height": "show",
			  "marginTop": "show",
			  "marginBottom": "show",
			  "paddingTop": "show",
			  "paddingBottom": "show"
			};
			var cdhl_cf7_hide_animation    = {
			  "height": "hide",
			  "marginTop": "hide",
			  "marginBottom": "hide",
			  "paddingTop": "hide",
			  "paddingBottom": "hide"
			};


			if ( $form.has('input[name="cardealer_lead_form"]').length > 0 ) {
				var skip_fields = [];

				if ( $form.has('input[name="joint_application"]').length > 0 ) {

					$form.find('.financial-form.financial-form-join-application').find('input,select,textarea').each(function () {
						skip_fields.push( $(this).attr('name') );
					});
					if ( ! $form.find('input[name="joint_application"]').is(':checked') ) {
						$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify( skip_fields ) );
						$( '.financial-form.financial-form-join-application' ).hide();
					} else {
						$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify([]) );
						$( '.financial-form.financial-form-join-application' ).show();
					}
					$( $form ).on( 'change', 'input[name="joint_application"]', function() {
						var toggle = $(this).is(':checked');
						if ( toggle ) {
							$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify([]) );
							$( '.financial-form.financial-form-join-application' ).animate( cdhl_cf7_show_animation, cdhl_cf7_show_animation_time ); // show with animation
						} else {
							$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify( skip_fields ) );
							$( '.financial-form.financial-form-join-application' ).animate( cdhl_cf7_hide_animation, cdhl_cf7_hide_animation_time ); // hide
						}
					});
				}

				if ( $form.has('input[name="test-drive"]').length > 0 ) {

					$form.find('.schedule-test-drive-fields').find('input,select,textarea').each(function () {
						skip_fields.push( $(this).attr('name') );
					});

					if ( 'No' === $form.find('input[name="test-drive"]:checked').val() ) {
						$( '.schedule-test-drive-fields' ).hide();
						$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify( skip_fields ) );
					} else {
						$( '.schedule-test-drive-fields' ).show();
						$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify([]) );

					}
					$( $form ).on( 'change', 'input[name="test-drive"]', function() {
						var toggle = ( 'Yes' === this.value );
						if ( toggle ) {
							$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify([]) );
							$( '.schedule-test-drive-fields' ).animate( cdhl_cf7_show_animation, cdhl_cf7_show_animation_time ); // show with animation
						} else {
							$( 'input[name="cdhl_skip_fields"]').val( JSON.stringify( skip_fields ) );
							$( '.schedule-test-drive-fields' ).animate( cdhl_cf7_hide_animation, cdhl_cf7_hide_animation_time ); // hide
						}
					});
				}

			}
		});
		/* Contact Form 7 - Show Hide Fields - End */

		/***************************************
		:: Review Stamp Popup
		***************************************/
		if ( jQuery.isFunction( jQuery.fn.magnificPopup ) && $( '.car-vehicle-review-stamps .vehicle-review-stamp-popup' ).length > 0 ) {
			$( '.car-vehicle-review-stamps .vehicle-review-stamp-popup' ).magnificPopup({
				type:'iframe',
				mainClass: 'cd-vehicle-review-popup',
				preloader: true,
			});
		}
	});

	function cardealer_lazyload() {
		if ( jQuery( '.cardealer-lazy-load' ).length > 0 ) {
			jQuery( '.cardealer-lazy-load' ).lazyload();
		}
	}

	// tooltip
	function cd_data_tooltip() {
		if( jQuery('[data-toggle="tooltip"]').length > 0 ){
			jQuery('[data-toggle="tooltip"]').tooltip();
		}
	}

	function cdhl_video() {
		if ( jQuery.isFunction( jQuery.fn.magnificPopup ) ) {
			jQuery( '.popup-youtube, .popup-vimeo, .popup-gmaps, .popup-video' ).magnificPopup({
				disableOn: 300,
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,
				fixedContentPos: false
			});
		}
	}

}( jQuery ) );
