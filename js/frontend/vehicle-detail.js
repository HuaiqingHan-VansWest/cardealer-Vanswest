/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Vehicle Detail Page Print Button
	:: Vehicle Detail Page - Nav Button Event
	:: Make An Offer Form [ CarDetail Page ]
	:: Schedule Test Drive Form [ CarDetail Page ]
	:: Car EMAIL TO A FRIEND Form [ CarDetail Page ]
	:: Car Inquiry Form [ CarDetail Page ]
	:: Financial Form [ CarDetail Page ]
	:: Slick slider
	:: Photoswipe popup
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	jQuery(document).ready(function($) {

		/***************************************
		:: Vehicle Buttons
		***************************************/

		$( document ).on( 'cardealer-vehicle-button-print', function( event, el ) {
			window.print();
		});

		/***************************************
		:: Vehicle Detail Page - Nav Button Event
		***************************************/
		$(".dealer-form-btn").on('click',function(){
			$(".reset_css input").css({"border":"none"});
			$(".reset_css textarea").css({"border":"none"});
		});

		/*******************************************
		:: Make An Offer Form [ CarDetail Page ]
		*******************************************/
		jQuery('#make_an_offer_test_request').click(function(e){
			e.preventDefault();
			var current_form = jQuery(this).parents('form'),
				formId       = current_form.attr('id'),
				textArray = [];

			jQuery('form#'+formId).find('input.cdhl_validate').each( function(i){
				textArray[i] = jQuery(this).attr('name');
			});

			var sts = do_validate_field(textArray,formId);

			if(!sts){
				var submit_btn_ID = jQuery(this).attr('id');
				jQuery.ajax({
					url: vehicle_detail_js.ajaxurl,
					method: "POST",
					data: jQuery('form#make_an_offer_test_form').serialize(),
					dataType:'json',
					beforeSend: function() {
						jQuery('.make_an_offer_test_spinimg').html('<span class="cd-loader"></span>');
						jQuery('#'+submit_btn_ID).prop("disabled", "disabled");
					},
					success: function(responseObj){
						jQuery('form#make_an_offer_test_form .make_an_offer_test_msg').show();
						jQuery('form#make_an_offer_test_form .make_an_offer_test_msg').html(responseObj.msg).delay(5000).fadeOut('slow');
						if ( responseObj && responseObj.success ) {
							current_form[0].reset();
						}
						jQuery('.check').attr('checked',true);
						if (typeof grecaptcha !== "undefined" && typeof recaptcha2 !== "undefined" ){
							grecaptcha.reset(recaptcha2);
						}
						jQuery('#'+submit_btn_ID).removeAttr("disabled");
						jQuery('.make_an_offer_test_spinimg').html('');
					}
				});
			}
		});

		/**********************************************
		:: Schedule Test Drive Form [ CarDetail Page ]
		***********************************************/
		if ( $( '.pgs-input-field-type-date' ).exists() ) {
			$('.pgs-input-field-type-date').each(function (i, el) {
				var datepicker_settings = {
					dateFormat: 'mm-dd-yy',
				};
				if ( el.dataset.show_datepicker && 'yes' === el.dataset.show_datepicker ) {
					if ( el.dataset.date_format ) {
						datepicker_settings.dateFormat = el.dataset.date_format;
					}
					$( el ).datepicker( datepicker_settings );
				}
			});
		}
		$( ".date-time" ).keydown(function(event) {
			event.preventDefault();
		});

		// SHOW DATE AND TIME FIELD ONLY IF TEST DRIVE IS CHECKED
		jQuery('#schedule_test_form input[name=test_drive]').click( function(){
			if(jQuery(this).val() == 'no')
				jQuery('.show_test_drive').css('display', 'none');
			else
				jQuery('.show_test_drive').css('display', 'block');
		});

		// TIME PICKER FOR SCHEDULE TIME FIELD
		jQuery('.time').timepicker({ 'timeFormat': 'H:i:s'});
		jQuery('#schedule_test_request').click(function(e){
			e.preventDefault();
			var current_form = jQuery(this).parents('form'),
				formId       = current_form.attr('id'),
				textArray    = [];

				jQuery('form#'+formId).find('input').css({"border":"none"});

			jQuery('form#'+formId).find('input.cdhl_validate').each( function(i){
				textArray[i] = jQuery(this).attr('name');
			});

			if(jQuery('input[name=test_drive]:checked').val()=="no"){
				textArray.splice(jQuery.inArray("date", textArray),1);
				textArray.splice(jQuery.inArray("time", textArray),1);
			}

			var sts = do_validate_field(textArray,formId);

			if(!sts){
				var submit_btn_ID = jQuery(this).attr('id');
				jQuery.ajax({
					url: vehicle_detail_js.ajaxurl,
					method: "POST",
					data: jQuery('form#schedule_test_form').serialize(),
					dataType:'json',
					beforeSend: function() {
						jQuery('.schedule_test_spinimg').html('<span class="cd-loader"></span>');
						jQuery('#'+submit_btn_ID).prop("disabled", "disabled");
					},
					success: function(responseObj){
						jQuery('form#schedule_test_form .schedule_test_msg').show();
						jQuery('form#schedule_test_form .schedule_test_msg').html(responseObj.msg).delay(5000).fadeOut('slow');
						if ( responseObj && responseObj.success ) {
							current_form[0].reset();
							jQuery('.check').attr('checked',true);
						}
						if (typeof grecaptcha !== "undefined" && typeof recaptcha3 !== "undefined" ){
							grecaptcha.reset(recaptcha3);
						}
						jQuery('#'+submit_btn_ID).removeAttr("disabled");
						jQuery('.schedule_test_spinimg').html('');
					}
				});
			}
		});

		/************************************************
		:: Car EMAIL TO A FRIEND Form [ CarDetail Page ]
		*************************************************/
		jQuery(document).on('click','#submit_friend_frm',function(e){
			e.preventDefault();
			var current_form = jQuery(this).parents('form'),
				formId       = current_form.attr('id'),
				textArray    = [];

			jQuery('form#'+formId).find('input.cdhl_validate').each( function(i){
				textArray[i] = jQuery(this).attr('name');
			});

			var sts = do_validate_field(textArray,formId);
			if(!sts){
				var submit_btn_ID = jQuery(this).attr('id');
				jQuery.ajax({
					url: vehicle_detail_js.ajaxurl,
					method: "POST",
					data: jQuery('form#'+formId).serialize(),
					dataType:'json',
					beforeSend: function() {
						jQuery('.spinimg').html('<span class="cd-loader"></span>');
						jQuery('#'+submit_btn_ID).prop("disabled", "disabled");
					},
					success: function(responseObj){
						jQuery('.spinimg').html('');
						jQuery('#'+submit_btn_ID).removeAttr("disabled");
						jQuery('form#'+formId+' .friend-frm-msg').show();
						jQuery('form#'+formId+' .friend-frm-msg').html(responseObj.msg).delay(5000).fadeOut('slow');
						if ( responseObj && responseObj.success ) {
							current_form[0].reset();
						}
						jQuery('.check').attr('checked',true);
						if (typeof grecaptcha !== "undefined" && typeof recaptcha4 !== "undefined" ){
							grecaptcha.reset(recaptcha4);
						}
					}
				});
			}
		});

		/*********************************************
		:: Car Inquiry Form [ CarDetail Page ]
		**********************************************/

		jQuery(document).on('click','#submit_request, #submit-inquiry',function(e){
			e.preventDefault();
			var current_form = jQuery(this).parents('form'),
				formId       = current_form.attr('id'),
				textArray = [];

			jQuery('form#'+formId).find('input').css({"border":"none"});
			jQuery('form#'+formId).find('input.cdhl_validate').each( function(i){
				textArray[i] = jQuery(this).attr('name');
			});

			// ENABLE / DISABLE REQUIRED ON PHONE / EMAIL BASED ON PREFERED CONTACT SELECTED
			var sts = do_validate_field(textArray,formId);
			if(!sts){
				var submit_btn_ID = jQuery(this).attr('id');
				jQuery.ajax({
					url: vehicle_detail_js.ajaxurl,
					method: "POST",
					dataType:'json',
					data: jQuery('form#'+formId).serialize(),
					beforeSend: function() {
						jQuery('.spinimg').html('<span class="cd-loader"></span>');
						jQuery('#'+submit_btn_ID).prop("disabled", "disabled");
					},
					success: function(responseObj){
						jQuery('form#'+formId+' .inquiry-msg').show();
						jQuery('#'+submit_btn_ID).removeAttr("disabled");
						jQuery('.spinimg').html('');
						jQuery('form#'+formId+' .inquiry-msg').html(responseObj.msg).delay(5000).fadeOut('slow');
						if ( responseObj && responseObj.success ) {
							current_form[0].reset();
						}
						jQuery('.check').attr('checked',true);
						if (typeof grecaptcha !== "undefined" && typeof recaptcha1 !== "undefined" && typeof recaptcha6 !== "undefined" ){
							grecaptcha.reset(recaptcha1);
							grecaptcha.reset(recaptcha6);
						}
					}
				});
			}
		});

		/**************************************
		:: Financial Form [ CarDetail Page ]
		***************************************/
		jQuery("#personal_application").css("display","none");
		if( $('#joint_application').is(':checked') ) {
			$('#personal_application').show();
		}

		jQuery('#joint_application').change(function() {
			if( jQuery(this).is(':checked')) {
				jQuery("#personal_application").show();
			} else {
				jQuery("#personal_application").hide();
			}
		});
		jQuery('#financial_form_request').click(function(e){
			e.preventDefault();
			var current_form = jQuery(this).parents('form'),
				formId       = current_form.attr('id'),
				financial = [];

			jQuery('form#'+formId).find('input.cdhl_validate').each( function(i){
				financial[i] = jQuery(this).attr('name');
			});

			var Selectfinancial = [];
			jQuery('form#'+formId).find('select.cdhl_sel_validate').each( function(i){
				Selectfinancial[i] = jQuery(this).attr('name');
			});

			var joint = [];
			jQuery('form#'+formId).find('input.cdhl_validate_joint').each( function(i){
				joint[i] = jQuery(this).attr('name');
			});

			var joint;
			jQuery('form#'+formId).find('input.cdhl_validate_joint').each( function(i){
				joint[i] = jQuery(this).attr('name');
			});

			var selectjoint = [];
			jQuery('form#'+formId).find('select.cdhl_sel_validate_joint').each( function(i){
				selectjoint[i] = jQuery(this).attr('name');
			});

			var SelectArray=[];
			var textArray = [];


			if(jQuery("#joint_application").is(':checked'))
				textArray = financial.concat(joint);
			else
				textArray=financial;

			if(jQuery("#joint_application").is(':checked'))
				SelectArray = Selectfinancial.concat(selectjoint);
			else
				SelectArray=Selectfinancial;

			var sts = do_validate_field(textArray,formId,SelectArray);
			if(!sts){
				var submit_btn_ID = jQuery(this).attr('id');
				jQuery.ajax({
					url: vehicle_detail_js.ajaxurl,
					method: "POST",
					dataType:'json',
					data: jQuery('form#financial_form').serialize(),
					beforeSend: function() {
						jQuery('.financial_form_spining').html('<span class="cd-loader"></span>');
						jQuery('#'+submit_btn_ID).prop("disabled", "disabled");
					},
					success: function(responseObj){
						jQuery('.financial_form_spining').html('');
						jQuery('#'+submit_btn_ID).removeAttr("disabled");
						jQuery('form#financial_form .financial_form_msg').show();
						jQuery('form#financial_form .financial_form_msg').html(responseObj.msg).delay(5000).fadeOut('slow');
						if ( responseObj && responseObj.success ) {
							current_form[0].reset();
							jQuery('.check').attr('checked',true);
							jQuery('select').prop('selectedIndex',0);
							jQuery('select').niceSelect('update');
						}
						if (typeof grecaptcha !== "undefined" && typeof recaptcha5 !== "undefined" ){
							grecaptcha.reset(recaptcha5);
						}
					}
				});
			}
		});

		/*********************
		:: Slick slider
		*********************/

		cars_image_gallery();
		$( document.body ).on( 'cdhl_vehicle_gallery_event', function() {
			cars_image_gallery();
		});

		/*************************************************
		:: Photoswipe popup gallery for car detail page
		**************************************************/
		jQuery( document ).on("click", ".ps-car-listing", function() {
			var pswpElement = document.querySelectorAll('.pswp')[0];
			var items = [];
			var newitems = [];
			var psitems = [];
			var curid = this.id;

			jQuery( "figure" ).each(function() {
				if(!jQuery(this).closest('.slick-cloned').length){
					var url        = jQuery(this).find('.ps-car-listing').attr('src'),
						img_src    = jQuery(this).find('.ps-car-listing').attr('data-src'),
						img_width  = jQuery(this).find('.ps-car-listing').attr('data-width'),
						img_height = jQuery(this).find('.ps-car-listing').attr('data-height');

					// Imagify plugin support when picture tag added
					if ( ! url ) {
						url = jQuery( this ).find( 'img' ).attr( 'src' );
					}

					if ( ! img_src ) {
						img_src = jQuery( this ).find( 'img' ).attr( 'data-src' );
					}

					if ( ! img_width ) {
						img_width = jQuery( this ).find('img').attr( 'data-width' );
					}

					if ( ! img_height ) {
						img_height = jQuery( this ).find( 'img' ).attr( 'data-height' );
					}

					var id = jQuery(this).find('.ps-car-listing').attr('id');
					var item = {
						src : (typeof img_src !== 'undefined' && img_src!="") ? img_src : url,
						id  : id,
						w: (typeof img_width !== 'undefined' && img_width!='')? img_width : 1051,
						h: (typeof img_height !== 'undefined' && img_height!='')? img_height : 662
					};
					items.push(item);
				}
			});
			items.forEach(function(element, i) {
				if(curid == element.id){
					newitems = items.concat(items.splice(0,i));
				}
			});
			items.forEach( function (i) {
				if(newitems.indexOf(i) < 0) {
					newitems.push(i);
				}
			});
			var options = {
				history: false,
				focus: false,
				showAnimationDuration: 0,
				hideAnimationDuration: 0
			};
			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, newitems, options);
			gallery.init();
		});

	});

	function do_validate_field(textArray,formId, SelectArray){
		var validationStr = false;

		// code for privacy and terms
		if(jQuery('.validation_error').length){
			jQuery('.validation_error').css("border", "none");
		}
		if( jQuery('form#'+formId).find('input:checkbox[name=cdhl_terms_privacy]').length > 0 ){
			var checkbox_field = jQuery('form#'+formId).find('input:checkbox[name=cdhl_terms_privacy]:checked').val();
			if (checkbox_field == null || checkbox_field == "" ) {
				validationStr = true;
				jQuery('form#'+formId).find('input:checkbox[name=cdhl_terms_privacy]').parent().parent('div').addClass('validation_error').css({"border-style":"solid","border-width":"1px 1px 1px 1px","border-color":"red"});
			}
		}

		for (var n = 0; n < textArray.length; n++) {
			var str = textArray[n];
			jQuery('form#'+formId).find('input[name='+str+']').css({"border":"none"});
			var field_val = jQuery('form#'+formId).find('input[name='+str+']').val();
			if (field_val == "") {
				validationStr = true;
				jQuery('form#'+formId).find('input[name='+str+']').css({"border-style":"solid","border-width":"1px 1px 1px 1px","border-color":"red"});
			}

			if( jQuery("input[name="+str+"]").hasClass('cardealer_mail') ) {
				var varTestMailExp=/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				var varEmail = field_val;
				if (varEmail && varEmail.search(varTestMailExp) == -1) {
					validationStr = true;
					jQuery('form#'+formId).find('input[name='+str+']').css({"border-style":"solid","border-width":"1px 1px 1px 1px","border-color":"red"});
				}
			}
		}
		if (typeof SelectArray != 'undefined' ) {
			if(SelectArray)
			{
				for (var n = 0; n < SelectArray.length; n++) {
					str = SelectArray[n];
					jQuery('form#'+formId).find('select[name='+str+']').next('.nice-select').css({"border-color":"#e3e3e3"});

					var field_val = jQuery('form#'+formId).find('select[name='+str+']').val();
					if (field_val == "") {
						validationStr = true;
						jQuery('form#'+formId).find('select[name='+str+']').next('.nice-select').css({"border-style":"solid","border-width":"1px 1px 1px 1px","border-color":"red"});
					}
				}
			}
		}
		return validationStr;
	}

	function cars_image_gallery() {

		$( '.cars-image-gallery' ).each( function( index ) {
			if ( $(this).find( '.slider-for' ).length > 0 ) {
				$(this).find( '.slider-for' ).slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: true,
					adaptiveHeight: true,
					asNavFor: $(this).next( '.slider-nav' )
				});
			}
			if ( $(this).parents( '.slider-slick' ).find( '.slider-nav' ).length > 0 ) {
				$(this).parents( '.slider-slick' ).find( '.slider-nav' ).slick({
					slidesToShow: 5,
					slidesToScroll: 1,
					asNavFor: $(this).find( '.slider-for' ),
					dots: false,
					focusOnSelect: true,
					responsive: [
						{
						  breakpoint: 1024,
						  settings: {
							slidesToShow: 4,
							slidesToScroll: 4
						  }
						},
						{
						  breakpoint: 600,
						  settings: {
							slidesToShow: 3,
							slidesToScroll: 3
						  }
						},
						{
						  breakpoint: 480,
						  settings: {
							slidesToShow: 3,
							slidesToScroll: 3
						  }
						}
					  ]
				});
			}
			if ( $(this).find( '.slider-for-full' ).length > 0 ) {
				$(this).find( '.slider-for-full' ).slick({
					slidesToShow: 3,
					slidesToScroll: 1,
					arrows: true,
					adaptiveHeight: true,
					responsive: [
						{
							breakpoint: 993,
							settings: {
								slidesToShow: 2
							}
						},
						{
							breakpoint: 576,
							settings: {
								slidesToShow: 1
							}
						}
					 ]
				});
			}
		});
	}

}( jQuery ) );
