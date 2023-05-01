var EnvatoWizard = (function($){

    var t;
	
    // callbacks from form button clicks.
    var callbacks = {
        install_plugins: function(btn){
            var plugins = new PluginManager();
            plugins.init(btn);
        },
        install_content: function(btn){
            // window.location.href = $(btn).attr('href') + '&version=' + $('#import-id').val();
			var content = new ContentManager();
            content.init(btn);
        },
        after_plugins: function(btn){
            window.location.href = $(btn).attr('href');
        },
    };

    function window_loaded(){
        // init button clicks:
        $('.button-next').on( 'click', function(e) {
            var loading_button = dtbaker_loading_button(this);
            if(!loading_button){
                return false;
            }
            if($(this).data('callback') && typeof callbacks[$(this).data('callback')] != 'undefined'){
                // we have to process a callback before continue with form submission
                callbacks[$(this).data('callback')](this);
                return false;
            }else{
                loading_content();
                return true;
            }
        });
		
        $('.button-upload').on( 'click', function(e) {
            e.preventDefault();
            renderMediaUploader();
        });
		
        $('.theme-presets a').on( 'click', function(e) {
            e.preventDefault();
            var $ul = $(this).parents('ul').first();
            $ul.find('.current').removeClass('current');
            var $li = $(this).parents('li').first();
            $li.addClass('current');
            var newcolor = $(this).data('style');
            $('#new_style').val(newcolor);
            return false;
        });

        $('.sample-content').on('click', function() {
			if($(this).hasClass('cd-imported') ){
				return;
			}
            $('.sample-content').removeClass('sample-content-active');
            $(this).addClass('sample-content-active');
            $('#import-id').val($(this).data('version'));
        });
    }

    function loading_content(){
        $('.envato-setup-content').block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
    }

    function PluginManager(){

        var complete;
        var items_completed = 0;
        var current_item = '';
        var $current_node;
        var current_item_hash = '';

        function ajax_callback(response){
            if(typeof response == 'object' && typeof response.message != 'undefined'){
                $current_node.find('span.status').text(response.message);
                if(typeof response.url != 'undefined'){
                    // we have an ajax url action to perform.

                    if(response.hash == current_item_hash){
                        $current_node.find('span.status').text("failed");
                        find_next();
                    }else {
                        current_item_hash = response.hash;
                        jQuery.post(response.url, response, function(response2) {
                            process_current();
                            $current_node.find('span.status').text(response.message + envato_setup_params.verify_text);
                        }).fail(ajax_callback);
                    }

                }else if(typeof response.done != 'undefined'){
                    // finished processing this plugin, move onto next
                    $current_node.find('span.status').addClass('green-success');
                    find_next();
                }else{
                    // error processing this plugin
                    find_next();
                }
            }else{
                // error - try again with next plugin
                $current_node.find('span.status').addClass('green-success').text("Success");
                find_next();
            }
        }
        function process_current(){
            if(current_item){
                // query our ajax handler to get the ajax to send to TGM
                // if we don't get a reply we can assume everything worked and continue onto the next one.
                jQuery.post(envato_setup_params.ajaxurl, {
                    action: 'envato_setup_plugins',
                    wpnonce: envato_setup_params.wpnonce,
                    slug: current_item
                }, ajax_callback).fail(ajax_callback);
            }
        }
        function find_next(){
            var do_next = false;
            if($current_node){
                if(!$current_node.data('done_item')){
                    items_completed++;
                    $current_node.data('done_item',1);
                }
                $current_node.find('.spinner').css('visibility','hidden');
            }
            var $li = $('.envato-wizard-plugins li.plugin-to-install').has('input:checked').addClass('installing');
            $('.envato-wizard-plugins li').find('input').prop('disabled', true);
            $li.each(function(){
                if(current_item == '' || do_next){
                    current_item = $(this).data('slug');
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                }else if($(this).data('slug') == current_item){
                    do_next = true;
                }
            });
            if(items_completed >= $li.length){
                // finished all plugins!
                complete();
                $li.removeClass('installing');
            }

        }
		
        return {
            init: function(btn){
                complete = function(){
                    loading_content();
                    window.location.href=btn.href;
                };
                find_next();
            }
        }
    }
	
	function ContentManager(){
		/*
		var complete;
        var items_completed = 0;
        var current_item = '';
        var $current_node;
        var current_item_hash = '';
		*/
		
		function import_demo_content( btn ) {
			var version            = $('#import-id').val(),
				sample_import_nonce= $('#sample_import_nonce').val(),
				importSection      = $('.envato-setup-content');
				
				// If all demos imported
				if(version == null || version == ""){
					// loading_content();
					$('.envato-setup-content').block({
						message: 'All demos already imported..! moving to next step.',
						overlayCSS: {
							background: '#000',
							padding: '15px',
							opacity: 0.6
						},
						css: {
							border: 'none',
							padding: '15px',
							backgroundColor: '#8cc051',
							'border-radius': '10px',
							'-webkit-border-radius': '10px',
							'-moz-border-radius': '10px',
							color: '#fff'
						},
					});
					setTimeout(function(){ 
						window.location.href=btn.href;
					}, 5000);
				} else {
					// loading_content();
					$('.envato-setup-content').block({
						message: 'Processing request... Please Wait!',
						overlayCSS: {
							background: '#000',
							padding: '15px',
							opacity: 0.6
						},
						css: {
							border: 'none',
							padding: '15px',
							backgroundColor: '#8cc051',
							'border-radius': '10px',
							'-webkit-border-radius': '10px',
							'-moz-border-radius': '10px',
							color: '#fff'
						},
					});
				}				
				
            if( version == '' ) {
                $(btn).data('callback', 'after_plugins').data('done-loading', 'no').removeClass('dtbaker_loading_button_current');
                return;
            }

            importSection.addClass('import-process');
			
			$.ajax({
				method: "POST",
				url: envato_setup_params.ajaxurl,
				data: {
					action:'theme_import_sample',
					sample_id: version,
					sample_import_nonce: sample_import_nonce,
					action_source: 'wizard',
					type: 'version'
				},
				success: function(response){
					// importSection.prepend('<div class="import-results">' + response.message + '</div>');
					$(btn).data('callback', 'after_plugins').data('done-loading', 'no').removeClass('dtbaker_loading_button_current');
				},
				complete: function(){
					importSection.removeClass('import-process');
					$('.envato-setup-content').unblock();
					jQuery('.envato-setup-content').block({
						message: 'Process complete!<br>Proceeding to next step... please wait!',
						// timeout: 5000,
						overlayCSS: {
							background: '#000',
							padding: '15px',
							opacity: 0.6
						},
						css: {
							border: 'none',
							padding: '15px',
							backgroundColor: '#8cc051',
							'-webkit-border-radius': '10px',
							'-moz-border-radius': '10px',
							color: '#fff'
						},
						onUnblock: function(){
							// alert('Restarted');
						},
					});
					
					// Next step after 5 seconds
					setTimeout( window.location.href=btn.href, 5000);
					complete();
				}
			});
        }
		
        return {
            init: function(btn){				
                $('.envato-setup-pages').addClass('installing');
                $('.envato-setup-pages').find('input').prop("disabled", true);
                complete = function(){					
					// $('.envato-setup-content').unblock();
                    // window.location.href=btn.href;
                };				
                
				import_demo_content(btn);
            }
        }
    }

    /**
     * Callback function for the 'click' event of the 'Set Footer Image'
     * anchor in its meta box.
     *
     * Displays the media uploader for selecting an image.
     *
     * @since 0.1.0
     */
    function renderMediaUploader() {
        'use strict';

        var file_frame, attachment;

        if ( undefined !== file_frame ) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Upload Logo',//jQuery( this ).data( 'uploader_title' ),
            button: {
                text: 'Select Logo' //jQuery( this ).data( 'uploader_button_text' )
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            jQuery('.site-logo').attr('src',attachment.url);
            jQuery('#new_logo_id').val(attachment.id);
            // Do something with attachment.id and/or attachment.url here
        });
        // Now display the actual file_frame
        file_frame.open();

    }

    function dtbaker_loading_button(btn){

        var $button = jQuery(btn);
        if($button.data('done-loading') == 'yes')return false;
        var existing_text = $button.text();
        var existing_width = $button.outerWidth();
        var loading_text = '⡀⡀⡀⡀⡀⡀⡀⡀⡀⡀⠄⠂⠁⠁⠂⠄';
        var completed = false;

        //$button.css('width',existing_width);
        $button.addClass('dtbaker_loading_button_current').addClass('disabled');
		$button.append('<span class="btn-loader"></span>');
        var _modifier = $button.is('input') || $button.is('button') ? 'val' : 'text';
        //$button[_modifier](loading_text);
        //$button.attr('disabled',true);
        $button.data('done-loading','yes');

        return {
            done: function(){
                completed = true;
                $button[_modifier](existing_text);
				$button.find('.btn-loader').remove();
                $button.removeClass('dtbaker_loading_button_current').removeClass('disabled');
                $button.attr('disabled',false);
            }
        }

    }

    return {
        init: function(){
            t = this;
            $(window_loaded);
        },
        callback: function(func){            
        }
    }

})(jQuery);


EnvatoWizard.init();