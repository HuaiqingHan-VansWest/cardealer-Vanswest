/*================================================
[  Table of contents  ]
================================================
:: window load functions
	:: Goole captcha inti
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	var recaptcha1;
	var recaptcha2;
	var recaptcha3;
	var recaptcha4;
	var recaptcha5;
	var recaptcha6;

	jQuery(window).load(function() {
		/********************
		:: Goole captcha inti
		********************/
		if (typeof goole_captcha_api_obj !== "undefined") {
			//Render the recaptcha1 on the element with ID "recaptcha1"
			if( document.getElementById("recaptcha1") ){
				recaptcha1 = grecaptcha.render('recaptcha1', {
					'sitekey' : goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
					'theme' : 'light'
				});
			}
			if( document.getElementById("recaptcha2") ){
				//Render the recaptcha2 on the element with ID "recaptcha2"
				recaptcha2 = grecaptcha.render('recaptcha2', {
					'sitekey' : goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
					'theme' : 'light'
				});
			}
			if( document.getElementById("recaptcha3") ){
				recaptcha3 = grecaptcha.render('recaptcha3', {
					'sitekey' : goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
					'theme' : 'light'
				});
			}
			if( document.getElementById("recaptcha4") ){
				recaptcha4 = grecaptcha.render('recaptcha4', {
					'sitekey' : goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
					'theme' : 'light'
				});
			}
			if( document.getElementById("recaptcha5") ){
				recaptcha5 = grecaptcha.render('recaptcha5', {
					'sitekey' : goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
					'theme' : 'light'
				});
			}
			if( document.getElementById("recaptcha6") ){
				// Inquiry Widget
				recaptcha6 = grecaptcha.render('recaptcha6', {
					'sitekey' : goole_captcha_api_obj.google_captcha_site_key, //Replace this with your Site key
					'theme' : 'light'
				});
			}
		}
	});

}( jQuery ) );
