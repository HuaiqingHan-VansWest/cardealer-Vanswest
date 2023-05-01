// Ref: https://github.com/elementor/elementor/blob/v3.4.8/assets/dev/js/admin/admin-feedback.js
/* global jQuery */
( function( $, window ) {
	'use strict';

	var PGS_Elm_Studio = {
		initConstants: function() {
			this.pgs_el_studio_obj_name = pgs_el_studio_data.obj_name;
			this.pgs_el_studio_obj      = window[ this.pgs_el_studio_obj_name ];
			this.elm_studio_btn_class   = this.pgs_el_studio_obj.elm_studio_btn_class;
			this.elm_studio_btn_title   = this.pgs_el_studio_obj.elm_studio_btn_title;
			this.elm_studio_btn_logo    = this.pgs_el_studio_obj.elm_studio_btn_logo;
			this.template_data          = [];
			this.template_data_loaded   = false;
			this.compare_versions       = window.elementor.helpers.compareVersions;
			this.preview_contents       = window.elementor.$previewContents;
			this.modal                  = false;
		},
		cacheElements: function() {
			this.cache = {
				/*
				$deactivateLink: $( '#the-list' ).find( '[data-slug="elementor"] span.deactivate a' ),
				// $dialogHeader: $( '#elementor-deactivate-feedback-dialog-header' ),
				// $dialogForm: $( '#elementor-deactivate-feedback-dialog-form' ),
				*/
			};
		},
		bindEvents: function() {
			var self = this,
				event = ( this.compare_versions( window.elementor.config.version, '2.8.5', '>' ) ) ? 'document:loaded' : 'preview:loaded';

			window.elementor.on( event, function() {
				self.initTemplateLibrary();
			});
		},
		/*
		deactivate: function() {
			location.href = this.cache.$deactivateLink.attr( 'href' );
		},
		*/
		initTemplateLibrary: function() {
			var self = this;
			if ( window.elementor.$previewContents.find( '.' + this.elm_studio_btn_class ).length > 0 ) {
				return;
			}

			var btn_img_el = $('<img />', {
			   src: this.elm_studio_btn_logo,
			   class: this.elm_studio_btn_class + '-img',
			   alt: this.elm_studio_btn_title
			});

			var btn_el = $('<div>');
			$( btn_el )
				.addClass( 'elementor-add-section-area-button ' + this.elm_studio_btn_class )
				.attr( 'title', this.elm_studio_btn_title )
				.append( btn_img_el );

			window.elementor.$previewContents.find( '.elementor-add-new-section .elementor-add-template-button' )
				.after( btn_el );

			window.elementor.$previewContents.find( '.' + this.elm_studio_btn_class ).on('click', function( event ) {
				event.preventDefault();
				self.modal.show();
			});
		},
		getModal: function() {
			var self = this,
				modal;

			var translations = this.pgs_el_studio_obj.translations;
			console.log( this.modal );

			// this.getModal = function() {
				if ( ! this.modal ) {
					console.log( this.modal );
					console.log( 'defining modal' );

					this.modal = elementorCommon.dialogsManager.createWidget( 'lightbox', {
						id: 'elementor-template-library-modal',
						className: 'elementor-templates-modal',
						closeButton: false,
						draggable: false,

						// headerMessage: self.cache.$dialogHeader,
						// message: self.cache.$dialogForm,
						hide: {
							onOutsideClick: true,
							onEscKeyPress: true
						},
						position: {
							my: 'center',
							at: 'center',
						},
						onReady: function() {
							DialogsManager.getWidgetType( 'lightbox' ).prototype.onReady.apply( this, arguments );
							this.addButton( {
								name: 'submit',
								text: translations.submit_n_deactivate,
								callback: self.sendFeedback.bind( self ),
							} );

							/*
							if ( ! elementorAdmin.config.feedback.is_tracker_opted_in ) {
								this.addButton( {
									name: 'skip',
									text: translations.skip_n_deactivate,
									callback: function() {
										self.deactivate();
									},
								} );
							}
							*/
						},
						onShow: function() {
							var $dialogModal = $( '#elementor-deactivate-feedback-modal' ),
								radioSelector = '.elementor-deactivate-feedback-dialog-input';
							$dialogModal.find( radioSelector ).on( 'change', function() {
								$dialogModal.attr( 'data-feedback-selected', $( this ).val() );
							} );
							$dialogModal.find( radioSelector + ':checked' ).trigger( 'change' );
						},
					} );
					console.log( this.modal );
				}
				console.log( this.modal );

				return this.modal;
			// };
		},
		sendFeedback: function() {
			var self = this,
				formData = self.cache.$dialogForm.serialize();
			self.getModal().getElements( 'submit' ).text( '' ).addClass( 'elementor-loading' );
			$.post( ajaxurl, formData, this.deactivate.bind( this ) );
		},
		init: function() {
			console.clear();
			this.initConstants();
			this.getModal();
			// this.initModal();
			this.cacheElements();
			this.bindEvents();
		},
	};
	$( function() {
		PGS_Elm_Studio.init();
	} );
})( jQuery, window );
