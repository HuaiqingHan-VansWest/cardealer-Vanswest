// Ref: https://github.com/elementor/elementor/blob/v3.4.8/assets/dev/js/admin/admin-feedback.js
/* global jQuery */
( function( $, window ) {
	'use strict';

	var PGS_Library = {
		initConstants: function() {
			this.pgs_library_obj_name          = pgs_library_data.obj_name;
			this.pgs_library_obj               = window[ this.pgs_library_obj_name ];
			this.pgs_library_btn_class         = this.pgs_library_obj.pgs_library_btn_class;
			this.pgs_library_btn_title         = this.pgs_library_obj.pgs_library_btn_title;
			this.pgs_library_btn_logo          = this.pgs_library_obj.pgs_library_btn_logo;
			this.pgs_library_mdl_icon          = this.pgs_library_obj.pgs_library_mdl_icon;
			this.pgs_library_mdl_icon_bg_color = this.pgs_library_obj.pgs_library_mdl_icon_bg_color;
			this.pgs_library_mdl_title         = this.pgs_library_obj.pgs_library_mdl_title;
			this.template_data                 = [];
			this.template_data_error           = false;
			this.compare_versions              = window.elementor.helpers.compareVersions;
			this.templates_hash                = [];
		},
		bindEvents: function() {
			var self  = this,
				event = ( this.compare_versions( window.elementor.config.version, '2.8.5', '>' ) ) ? 'document:loaded' : 'preview:loaded';

			$( 'body' ).on( 'click', '.pgs-library-template .elementor-template-library-template-insert', function( event ) {
				event.preventDefault();
				var el = this;
				self.insertTemplate( el, event );
			});

			$( 'body' ).on( 'click', '.elementor-templates-modal__header__close', function( event ) {
				event.preventDefault();
				self.getModal().hide();
			});

			// Caregory Filter.
			$( 'body' ).on( 'change', '#elementor-template-library-filter-subtype', function( event ) {
				var cat = $(this).val();
				$('.elementor-template-library-template.pgs-library-template').removeClass('cat-hidden');
				'all' != cat && $('.elementor-template-library-template.pgs-library-template').not( self.templates_hash[ cat ] ).addClass('cat-hidden');

			});

			// Template search.
			$( 'body' ).on( 'keyup', '#elementor-template-library-filter-text', function( event ) {
				var val = $(this).val();

				val.length ? self.searchByName( val ) : self.clearSearch();
			});

			$( 'body' ).on( 'click', '#elementor-template-library-header-sync', function( event ) {
				event.preventDefault();
				self.syncLibrary( {
					show_templates: true,
					resync: true,
				});
			});

			window.elementor.on( event, function() {
				self.initTemplateLibrary();
				self.syncLibrary();
			});

		},
		searchByName: function( val ) {
			var self = this;

			$('.elementor-template-library-template.pgs-library-template').removeClass('search-hidden');
			var searched_els = $('.elementor-template-library-template.pgs-library-template[data-template_id*="' + this.slugify( val ) + '"]');
			$('.elementor-template-library-template.pgs-library-template').not( searched_els ).addClass('search-hidden');
		},
		clearSearch: function() {
			var self = this;

			$('.elementor-template-library-template.pgs-library-template').removeClass('search-hidden');
		},
		slugify: function( text ) {
			var self = this;

			return text.toLowerCase().replace(/[^\w ]+/g, "").replace(/ +/g, "-");
		},
		initTemplateLibrary: function() {
			var self = this;

			self.insertButton();

			window.elementor.$previewContents.find( '.' + this.pgs_library_btn_class ).on('click', function( event ) {
				event.preventDefault();
				self.showModal();
			});
		},
		insertButton: function() {
			var self = this;

			if ( window.elementor.$previewContents.find( '.' + this.pgs_library_btn_class ).length > 0 ) {
				return;
			}

			var btn_img = $('<img />', {
				src: self.pgs_library_btn_logo,
				class: self.pgs_library_btn_class + '-img',
				alt: self.pgs_library_btn_title
			});

			var btn_el = $('<div>')
				.addClass( 'elementor-add-section-area-button ' + self.pgs_library_btn_class )
				.attr( 'title', self.pgs_library_btn_title )
				.append( btn_img );

			window.elementor.$previewContents.find( '.elementor-add-new-section .elementor-add-template-button' ).after( btn_el );
		},
		showModal: function() {
			var self = this;
			self.getModal().show();
		},
		hideModal: function() {
			var self = this;
			self.getModal().hide();
		},
		getHeaderTemplates: function() {
			var self = this;

			var header = wp.template( 'pgses-header-templates' );
			return header({
				'closeType': 'normal',
				'icon': self.pgs_library_mdl_icon,
				'icon_bg_color': self.pgs_library_mdl_icon_bg_color,
				'title': self.pgs_library_mdl_title,
			});
		},
		getHeaderTemplatePreview: function( args ) {
			var self = this;

			var header = wp.template( 'pgses-header-template-preview' );
			return header({
				'closeType': 'normal',
				'tmpl_id': args.tmpl_id,
			});
		},
		setModalHeader: function( content ) {
			var self = this;
			self.getModal().setHeaderMessage( content );
		},
		setModalContent: function( content ) {
			var self = this;
			self.getModal().setMessage( content );
		},
		getLoader: function() {
			var self = this;

			var loader = wp.template( 'pgses-loader' );
			return loader({});
		},
		showLoader: function() {
			var self = this;

			var message = self.getModal().getElements('message');
			message.find('.dialog-loading.dialog-lightbox-loading').show();
		},
		hideLoader: function() {
			var self = this;

			var message = self.getModal().getElements('message');
			message.find('.dialog-loading.dialog-lightbox-loading').hide();
		},
		removeTemplates: function() {
			var self = this;

			var message = self.getModal().getElements('message');
			message.find( '#elementor-template-library-templates' ).remove();
		},
		showTemplates: function() {
			var self = this;

			self.removeTemplates();
			self.hideLoader();

			if ( false !== self.template_data_error ) {
				var sync_error_msg      = wp.template('pgses-sync-error');
				var sync_error_msg_html = sync_error_msg({
					'error_message': self.template_data_error,
				});
				self.templates_hash = [];
			} else {
				var templates      = wp.template('pgses-templates');
				var templates_html = templates( self.template_data );

				var message = self.getModal().getElements('message');
				message.append( templates_html );

				// Prepare data for category filter.
				message.find( '#elementor-template-library-templates #elementor-template-library-templates-container > .elementor-template-library-template.pgs-library-template' ).each( function (i, div ) {
					$.each( $( div ).attr('data-template_cat').split(','), function ( i, el ) {
						if ( ! ( el in self.templates_hash ) ) {
							self.templates_hash[ el ] = [];
						}
						self.templates_hash[ el ].push( div );
					});
				});
			}
		},
		previewTemplate: function() {
			var self = this;
		},
		insertTemplate: function( el, event ) {
			var self           = this,
				template_id    = $( el ).data( 'template_id' ),
				editor_post_id = window.elementor.config.document.id;

			$.ajax({
				url: self.pgs_library_obj.ajax_url,
				method: 'POST',
				data: {
					action: 'pgs_library_get_template',
					template_id: template_id,
					editor_post_id: editor_post_id,
					security: self.pgs_library_obj.ajax_nonce,
				},
				dataType: 'json',
				beforeSend: function( xhr, settings ) {
					self.removeTemplates();
					self.showLoader();
				},
				success: function( response, status, xhr ) {
					if ( response ) {
						// console.log( response.data.content );
						elementor.getPreviewView().addChildModel( response.data.content );
						self.hideModal();
						$e.internal('document/save/set-is-modified', {
							status: true
						});
						// self.template_data       = response.data;
						// self.template_data_error = false;
					} else {
						// self.template_data_error = ( resync ) ? translations.library_sync_error : translations.library_fetch_error;
						// self.template_data       = [];
					}
				},
				error: function( xhr, status, errorthrown ) {
					// self.template_data_error = ( resync ) ? translations.library_sync_error : translations.library_fetch_error;
					// self.template_data       = [];
				},
				complete: function( xhr, status ) {
					// $( document.body ).trigger( 'pgs_library_sync_complete' );
					// if ( show_templates ) {
						// self.showTemplates();
					// }
				}
			});
		},
		syncLibrary: function( args = {} ) {
			var self = this;

			var resync         = ( 'resync' in args ) ? args.resync : false;
			var show_templates = ( 'show_templates' in args ) ? args.show_templates : false;

			var translations = self.pgs_library_obj.translations;

			$.ajax({
				url: self.pgs_library_obj.ajax_url,
				method: 'POST',
				data: {
					action: 'pgs_library_sync_library',
					security: self.pgs_library_obj.ajax_nonce,
				},
				dataType: 'json',
				beforeSend: function(){
					if ( resync ) {
						$( '#elementor-template-library-header-sync' ).find('.eicon-sync').addClass('eicon-animation-spin');
					}
				},
				success: function( response, status, xhr ) {
					/*
					if ( response ) {
						console.log( response );
						var preview_header_content = self.getHeaderTemplatePreview({
							'tmpl_id': 'xyzxyz',
						});
						self.setModalHeader( preview_header_content );
					}
					*/
					if ( response ) {
						self.template_data       = response.data;
						self.template_data_error = false;
					} else {
						self.template_data_error = ( resync ) ? translations.library_sync_error : translations.library_fetch_error;
						self.template_data       = [];
					}
				},
				error: function( xhr, status, errorthrown ) {
					self.template_data_error = ( resync ) ? translations.library_sync_error : translations.library_fetch_error;
					self.template_data       = [];
				},
				complete: function( xhr, status ) {
					if ( resync ) {
						$( '#elementor-template-library-header-sync' ).find('.eicon-sync').removeClass('eicon-animation-spin');
					}
					$( document.body ).trigger( 'pgs_library_sync_complete' );
					if ( show_templates ) {
						self.showTemplates();
					}
				}
			});
		},
		initModal: function() {
			var self = this,
				modal;

			var translations = self.pgs_library_obj.translations;

			self.getModal = function() {
				if ( ! modal ) {
					modal = elementorCommon.dialogsManager.createWidget( 'lightbox', {
						id: 'pgs-library-template-library-modal',
						className: 'elementor-templates-modal pgs-library-modal',
						closeButton: false,
						draggable: false,

						headerMessage: self.getHeaderTemplates(),
						message: self.getLoader(),
						hide: {
							onOutsideClick: true,
							onEscKeyPress: true
						},
						position: {
							my: 'center',
							at: 'center',
						},
						onReady: function() {
						},
						onShow: function() {
							self.showLoader();
							if ( self.template_data.length === 0 ) {
								self.syncLibrary( {
									show_templates: true,
								});
							} else {
								self.showTemplates();
							}
						},
						onHide: function() {
							var header_content = self.getHeaderTemplates();
							self.setModalHeader( header_content );
						},
					} );
				}

				return modal;
			};
		},
		init: function() {
			// console.clear();
			this.initConstants();
			this.initModal();
			this.bindEvents();
		},
	};
	$( function() {
		PGS_Library.init();
	} );
})( jQuery, window );
