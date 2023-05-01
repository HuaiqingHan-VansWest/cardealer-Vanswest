'use strict';

(function ($, window) {

	$(window).on('elementor:init', function() {
		// init_controls();
		// bind_events();
		init_template_library();
	});

	var pgs_el_studio_obj_name = pgs_el_studio_data.obj_name,
		pgs_el_studio_obj      = window[pgs_el_studio_obj_name],
		elm_studio_btn_class   = pgs_el_studio_obj.elm_studio_btn_class,
		elm_studio_btn_title   = pgs_el_studio_obj.elm_studio_btn_title,
		elm_studio_btn_logo    = pgs_el_studio_obj.elm_studio_btn_logo,
		template_data,
		template_data_loaded = false;
	var $modal;

	function init_template_library() {

		var event = 'preview:loaded',
			compareVersions = window.elementor.helpers.compareVersions;

		if ( compareVersions( window.elementor.config.version, '2.8.5', '>' ) ) {
			event = 'document:loaded';
		}

		elementor.on( event, function () {

			if ( elementor.$previewContents.find( '.' + elm_studio_btn_class ).length > 0 ) {
				return;
			}

			var btn_img_el = $('<img />', {
			   src: elm_studio_btn_logo,
			   class: elm_studio_btn_class + '-img',
			   alt: elm_studio_btn_title
			});

			// var btn_el = document.createElement( 'div' );
			var btn_el = $('<div>');
			$( btn_el )
				.addClass( 'elementor-add-section-area-button ' + elm_studio_btn_class )
				.attr( 'title', elm_studio_btn_title )
				.append( btn_img_el );

			window.elementor.$previewContents.find('.elementor-add-new-section .elementor-add-template-button')
				.after( btn_el );

			console.log( window.elementor.$previewContents.find( '.' + elm_studio_btn_class ) );
			// Call modal.
			window.elementor.$previewContents.find( '.' + elm_studio_btn_class ).on('click', function () {
				// $e.run('wunderwp-library/open');

				if ($modal) {
					$modal.show();
					return;
				}

				var modalOptions = {
					className: 'elementor-templates-modal',
					closeButton: false,
					draggable: false,
					hide: {
						onOutsideClick: true,
						onEscKeyPress: true
					}
				  };

				$modal = elementorCommon.dialogsManager.createWidget('lightbox', modalOptions);
				$modal.show();

				loadTemplates();

			});

			// Load items.
			function loadTemplates() {
				showLoader();

				$.ajax({
					url     : pgs_el_studio_obj.ajax_url,
					method  : 'GET',
					data    : {
						action : 'woodmart_load_templates',
						builder: 'elementor'
					},
					dataType: 'json',
					success : function(response) {
						if (response && response.elements) {
							var itemTemplate = wp.template('elementor-xts-library-modal-item');
							var itemOrderTemplate = wp.template('elementor-xts-library-modal-order');
							response.elements = response.elements.reverse();
							$(itemTemplate(response)).appendTo($('#xts-library-modal #elementor-template-library-templates-container'));
							$(itemOrderTemplate(response)).appendTo($('#xts-library-modal #elementor-template-library-filter-toolbar-remote'));
							importTemplate();
							hideLoader();
						} else {
							$('<div class="xts-notice xts-error">The library can\'t be loaded from the server.</div>').appendTo($('#xts-library-modal #elementor-template-library-templates-container'));
							hideLoader();
						}
					},
					error   : function() {
						$('<div class="xts-notice xts-error">The library can\'t be loaded from the server.</div>').appendTo($('#xts-library-modal #elementor-template-library-templates-container'));
						hideLoader();
					}
				});
			}

			// Loader
			function showLoader() {
				$('#xts-library-modal #elementor-template-library-templates').hide();
				$('#xts-library-modal .elementor-loader-wrapper').show();
			}

			function hideLoader() {
				$('#xts-library-modal #elementor-template-library-templates').show();
				$('#xts-library-modal .elementor-loader-wrapper').hide();
			}

			function activateUpdateButton() {
				$('#elementor-panel-saver-button-publish').toggleClass('elementor-disabled');
				$('#elementor-panel-saver-button-save-options').toggleClass('elementor-disabled');
			}

			// Import.
			function importTemplate() {
				$('#xts-library-modal .elementor-template-library-template-insert').on('click', function() {
					showLoader();

					return elementorCommon.ajax.addRequest('get_template_data', {
						data   : {
							source            : 'xts',
							edit_mode         : true,
							display           : true,
							template_id       : $(this).data('id'),
							with_page_settings: false
						},
						success: function success(data) {
							if (data && data.content) {
								elementor.getPreviewView().addChildModel(data.content);
								$modal.hide();
								setTimeout(function() {
									hideLoader();
								}, 2000);
								activateUpdateButton();
							} else {
								$('<div class="xts-notice xts-error">The element can\'t be loaded from the server.</div>').prependTo($('#xts-library-modal #elementor-template-library-templates-container'));
								hideLoader();
							}
						},
						error  : function() {
							$('<div class="xts-notice xts-error">The element can\'t be loaded from the server.</div>').prependTo($('#xts-library-modal #elementor-template-library-templates-container'));
							hideLoader();
						}
					});
				});

				// Close button.
				$('#xts-library-modal .elementor-templates-modal__header__close').on('click', function() {
					$modal.hide();
					hideLoader();
				});

				// Search.
				$('#xts-library-modal #elementor-template-library-filter-text').on('keyup', function() {
					var val = $(this).val().toLowerCase();

					$('#xts-library-modal').find('.elementor-template-library-template-block').each(function() {
						var $this = $(this);
						var title = $this.data('title').toLowerCase();
						var slug = $this.data('slug').toLowerCase();

						if (title.indexOf(val) > -1 || slug.indexOf(val) > -1) {
							$this.show();
						} else {
							$this.hide();
						}
					});
				});

				// Filters.
				$('#xts-library-modal #elementor-template-library-filter-subtype').on('change', function() {
					var val = $(this).val();

					$('#xts-library-modal').find('.elementor-template-library-template-block').each(function() {
						var $this = $(this);
						var tag = $this.data('tag').toLowerCase();

						if (tag.indexOf(val) > -1 || 'all' === val) {
							$this.show();
						} else {
							$this.hide();
						}
					});
				});
			}

		});
	}

	var PGS_Elm_Studio = {
		foo:"Point",
		initModal: function() {
			console.log(this.foo);
			this.foo = 'aaaa';
			console.log(this.foo);
		},
		init: function() {
			this.initModal();
			// this.cacheElements();
			// this.bindEvents();
		}
	};
	PGS_Elm_Studio.init();

})(jQuery, window);
