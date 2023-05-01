( function( $, window ) {
	'use strict';

	// Check element exists.
	$.fn.exists = function () {
		return this.length > 0;
	};

	if ( $( '.cardealer_templates-actions' ).exists() ) {
		$( '.cardealer_templates-actions' ).insertAfter('body.post-type-cardealer_template .wrap > .wp-heading-inline').css('display', 'inline-block' );
	}

	var $new_template_modal_el = $('#cdtmpl-modal'),
		$new_template_form     = $('#cdtmpl-modal-form'),
		$cdtmpl_close_icon     = $('#cdtmpl-modal-close-icon'),
		$cdtmpl_submit_button  = $('#cdtmpl-create-template-button'),
		$cdtmpl_close_button   = $('#cdtmpl-close-button'),
		builder_type           = '',
		template_type          = '',
		options_selected       = false,
		templates              = {};

	// Undescroes Templates
	var predefined_templates = wp.template( 'cardealer_templates--predefined-templates' );

	var predefined_templates_not_found         = wp.template( 'cardealer_templates--predefined-template--not-found' );
	var predefined_templates_not_found_content = predefined_templates_not_found({
		});

	var predefined_templates_select_option         = wp.template( 'cardealer_templates--predefined-template--select-option' );
	var predefined_templates_select_option_content = predefined_templates_select_option({
		});

	var predefined_templates_loader         = wp.template( 'cardealer_templates--predefined-template--loader' );
	var predefined_templates_loader_content = predefined_templates_loader({
		});

	// Initiate add template modal.
	var cardealer_templates_modal = new bootstrap.Modal( $new_template_modal_el, {
		keyboard: false,
		backdrop: 'static',
	});

	// Add class to bootstrap modal backdrop.
	$new_template_modal_el.on('show.bs.modal, shown.bs.modal', function (event) {
		$('.modal-backdrop').addClass('cardealer-modal-backdrop');
	});

	$new_template_modal_el.on('shown.bs.modal', function (event) {
		display_loader();
		update_options();
		display_templates();
	});
	$new_template_modal_el.on('hide.bs.modal', function (event) {
		builder_type     = '';
		template_type    = '';
		options_selected = false;
	});

	// Open modal.
	jQuery( document ).on( 'click', '.cardealer_templates-action-new', function( event ) {
		event.preventDefault();
		cardealer_templates_modal.show();
	});

	// Check/uncheck predefined template.
	jQuery( 'body' ).on( 'click', '.predefined-template-label', function() {
		var label_for = jQuery(this).data('for');
		var radio_el  = jQuery('#' + label_for );

		if( jQuery(radio_el).is(':checked') ) {
			jQuery(radio_el).prop( "checked", false );
		} else {
			jQuery(radio_el).prop( "checked", true );
		}
	});

	$new_template_modal_el.on('change', '#cdtmpl-builder-type, #cdtmpl-template-type', function( el ) {
		display_loader();
		update_options()
		display_templates();
	});

	function update_options() {
		builder_type  = $new_template_modal_el.find( '#cdtmpl-builder-type' ).val();
		template_type = $new_template_modal_el.find( '#cdtmpl-template-type' ).val();
		if (
			( builder_type !== null && builder_type !== '' )
			&& ( template_type !== null && template_type !== '' )
		) {
			options_selected = true;
			templates        = get_templates( builder_type, template_type );
		} else {
			options_selected = false;
			templates        = {};
		}
	}


	function get_templates( builder_type, template_type ) {
		var templates = cardealer_predefined_templates.filter(function(element, index, array) {
			return element.builder_type == builder_type && element.template_type == template_type;
		}, template_type);
		return templates;
	}


	function display_loader() {
		$new_template_modal_el.find('.predefined-templates-wrapper .predefined-templates-content').html( predefined_templates_loader_content );
	}

	function display_templates() {
		var predefined_templates_content = predefined_templates({
			'templates': templates,
		});

		// predefined_templates_not_found_content
		// predefined_templates_select_option_content
		if ( templates.length > 0 ) {
			$new_template_modal_el.find('.predefined-templates-wrapper .predefined-templates-content').html( predefined_templates_content );
			$cdtmpl_submit_button.prop( 'disabled', false );
		} else {
			if ( options_selected ) {
				$new_template_modal_el.find('.predefined-templates-wrapper .predefined-templates-content').html( predefined_templates_not_found_content );
				$cdtmpl_submit_button.prop( 'disabled', true );
			} else {
				$new_template_modal_el.find('.predefined-templates-wrapper .predefined-templates-content').html( predefined_templates_select_option_content );
				$cdtmpl_submit_button.prop( 'disabled', true );
			}
		}
	}

	$new_template_form.submit(function(event) {
		event.preventDefault(); //this will prevent the default submit

		// Disable close icon and button.
		$cdtmpl_close_icon.prop( 'disabled', true );
		$cdtmpl_close_button.prop( 'disabled', true );

		$cdtmpl_submit_button.prop( 'disabled', true ).html( '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + $cdtmpl_submit_button.html() );

		// your code here (But not asynchronous code such as Ajax because it does not wait for a response and move to the next line.)
		$(this).unbind('submit').submit(); // continue the submit unbind preventDefault
	})


})( jQuery, window );
