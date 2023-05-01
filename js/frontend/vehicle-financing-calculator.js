/*================================================
[  Table of contents  ]
================================================
:: Document ready functions
	:: Vehicle Make Logos
	:: Categories Show/Hide Sub Items
	:: Vehicle Categories
	:: Financing Calculator
======================================
[ End table content ]
======================================*/
( function( $ ) {
	"use strict";

	jQuery(document).ready(function($) {

		/************************
		:: Financing Calculator
		*************************/

		$( document ).on( 'click', '.do_calculator', function ( event ) {

			if ( typeof vehicle_financing_calculator_js_object === 'undefined' ) {
				return false;
			}

			var form_id = $(this).attr('data-form-id');
			var loan_amount = $('#loan-amount-'+form_id).val();
			var down_payment = $('#down-payment-'+form_id).val();
			var interest_rate = $('#interest-rate-'+form_id).val();
			var period = $('#period-'+form_id).val();
			var currency_symbol = vehicle_financing_calculator_js_object.currency_symbol;
			var currency_placement = vehicle_financing_calculator_js_object.currency_placement;

			var t = down_payment;
			var I = interest_rate;
			var N = period;
			var P = loan_amount;

			var vTempP = String(P).replace(currency_symbol, '').replace(',', '');
			if (!fnisNum(vTempP)) {
				alert( vehicle_financing_calculator_js_object.error_loan_amount );
				document.getElementById('loan-amount-'+form_id).focus();
				return false;
			}

			var vTempT = String(t).replace(currency_symbol, '').replace(',', '');
			if (!fnisNum(vTempT)) {
				alert( vehicle_financing_calculator_js_object.error_down_payment );
				document.getElementById('down-payment-'+form_id).focus();
				return false;
			}

			if (!fnisNum(I)) {
				alert( vehicle_financing_calculator_js_object.error_interest_rate );
				document.getElementById('interest-rate-'+form_id).focus();
				return false;
			}
			if (!fnisNum(N)) {
				alert( vehicle_financing_calculator_js_object.error_payment_count );
				document.getElementById('period-'+form_id).focus();
				return false;
			}

			P = vTempP;
			t = vTempT;
			var X = (P - t);
			var Y = ((I / 100) / 12);
			var z = (Math.pow((1 + ((I / 100) / 12)), -N));
			var a = (X * Y);
			var b = (1 - z);
			var Tot = (a / b);
			var ans2 = Tot.toFixed(2);
			var space = '';

			if ( currency_placement == 'right-with-space' || currency_placement == 'left-with-space' ) {
				space = '&nbsp;';
			}

			if ( currency_placement == 'right' || currency_placement == 'right-with-space' ) {
				document.getElementById('txtPayment-'+form_id).innerHTML = '<bdi>' + ans2 + space + '<span class="currency">' + currency_symbol + '</span></bdi><sup>' + vehicle_financing_calculator_js_object.period + '</sup>';
			} else {
				document.getElementById('txtPayment-'+form_id).innerHTML = '<bdi><span class="currency">' + currency_symbol + '</span>' + space + ans2 + '</bdi><sup>' + vehicle_financing_calculator_js_object.period + '</sup>';
			}
		});

		$( document ).on( 'click', '.do_calculator_clear', function ( event ) {
			var form_id = $(this).attr('data-form-id');
			$('#loan-amount-'+form_id).val('');
			$('#down-payment-'+form_id).val('');
			$('#interest-rate-'+form_id).val('');
			$('#period-'+form_id).val('');
			$('#txtPayment-'+form_id).html('');
		});
	});

	function fnisNum(x) {
		var filter = /(^\d+\.?$)|(^\d*\.\d+$)/;
		if (filter.test(x)) {
			return true;
		}
		return false;
	}

}( jQuery ) );
