/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Newsletter shortcode
	:: Newsletter mailchimp
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {
		
		/***********************
		:: Newsletter shortcode
		************************/

		// Code for newsletter shortcode to donot submit on enter and make ajax call
		jQuery(document).on('submit', 'form.news-letter-form, form.news-letter', function( event ){
			event.preventDefault();
			jQuery('.newsletter-mailchimp').trigger('click');
		});

		/************************
		:: Newsletter mailchimp
		************************/
		$( document ).on( 'click', '.newsletter-mailchimp', function() {
			var form_id           = jQuery( this ).attr( 'data-form-id' );
			var mailchimp_nonce   = jQuery( 'form#' + form_id + ' .news-nonce').val();
			var news_letter_email = jQuery( 'form#' + form_id + ' .newsletter-email' ).val();
			jQuery.ajax({
				url: cardealer_js.ajaxurl,
				data:'action=mailchimp_singup&mailchimp_nonce='+mailchimp_nonce+'&news_letter_email='+news_letter_email,
				beforeSend: function() {
					jQuery('.spinimg-'+form_id).html('<span class="cd-loader"></span>');
				},
				success: function(msg){
					jQuery('form#'+form_id+' .newsletter-msg').show();
					jQuery('form#'+form_id+' .newsletter-msg').removeClass('error_msg');
					jQuery('form#'+form_id+' .newsletter-msg').html(msg);
					jQuery('#process').css('display','none');
					jQuery('form#'+form_id+' .news_letter_name').val('');
					jQuery('form#'+form_id+' .newsletter-email').val('');
					jQuery('.spinimg-'+form_id).html('');
				},
				error: function(msg){
					jQuery('form#'+form_id+' .newsletter-msg').addClass('error_msg');
					jQuery('form#'+form_id+' .newsletter-msg').html(msg);
					jQuery('form#'+form_id+' .newsletter-msg').show();
					jQuery('#process').css('display','none');
				}
			});
			return false;
		});
	});
}( jQuery ) );
