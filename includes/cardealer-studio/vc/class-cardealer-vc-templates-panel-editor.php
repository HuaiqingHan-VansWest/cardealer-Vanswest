<?php
/**
 * WPBakery Custom template handler.
 *
 * @package cardealer
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

/**
 * Class Cardealer_Vc_Templates_Panel_Editor
 *
 * @since 1.0
 */
class Cardealer_Vc_Templates_Panel_Editor {
	/**
	 * All templates.
	 *
	 * @since 1.0
	 * @var bool
	 */
	protected $all_templates = false;

	/**
	 * Template types.
	 *
	 * @var string
	 */
	protected $templates_type = 'cd_vc_templates';

	/**
	 * Template categories.
	 *
	 * @var array
	 */
	protected $template_categories = array();

	/**
	 * Templates.
	 *
	 * @var array
	 */
	protected $templates = array();

	/**
	 * Templates.
	 *
	 * @var object
	 */
	protected $importer = null;

	/**
	 * Add ajax hooks, filters.
	 *
	 * @since 1.0
	 */
	public function init() {

		$this->prepare_templates();
		$this->prepare_template_categories();

		// Load importer for image upload
		$this->load_importers();

		add_filter( 'vc_templates_render_category', array( $this, 'renderTemplateBlock' ), 10 );
		add_filter( 'vc_templates_render_template', array( $this, 'renderTemplateWindow' ), 10, 2 );
		add_filter( 'vc_get_all_templates', array( $this, 'addTemplatesTab' ) );

		/**
		 * Ajax methods
		 *  'vc_save_template' -> saving content as template
		 *  'vc_backend_load_template' -> loading template content for backend
		 *  'vc_frontend_load_template' -> loading template content for frontend
		 *  'vc_delete_template' -> deleting template by index
		 */
		add_action( 'vc_templates_render_backend_template', array( $this, 'renderBackendTemplate' ) );
		add_action( 'vc_templates_render_frontend_template', array( $this, 'renderFrontendTemplate' ) );
	}

	/**
	 * Function prepare_templates.
	 *
	 * @return void
	 */
	protected function prepare_templates() {
		$this->templates = $this->get_templates();
	}

	/**
	 * Function prepare_template_categories.
	 *
	 * @return void
	 */
	protected function prepare_template_categories() {
		$template_categories = array();

		foreach ( $this->templates as $template_data ) {
			if ( isset( $template_data['template_category'] ) ) {
				$template_categories[ $template_data['template_category_slug'] ] = $template_data['template_category'];
			}
		}

		asort( $template_categories );

		$this->template_categories = $template_categories;
	}

	/**
	 * Add Templates Tab.
	 *
	 * @param array $data Data array.
	 *
	 * @return array
	 */
	public function addTemplatesTab( $data ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		$new_category = array(
			'category'        => $this->templates_type,
			'category_name'   => esc_html__( 'Cardealer Studio', 'cardealer' ),
			'category_weight' => 1,
			'templates'       => $this->getAllTemplates(),
		);

		$data[] = $new_category;

		return $data;
	}

	/**
	 * Function get_templates.
	 *
	 * @return array
	 */
	public function get_templates() {
		$templates = array();

		// Read Local Directory.
		$templates_directory_path = trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-studio/templates/';
		$templates_directory_url  = trailingslashit( CARDEALER_INC_URL ) . 'cardealer-studio/templates/';
		$handler                  = opendir( $templates_directory_path );

		while ( $handler && false !== ( $directory_name = readdir( $handler ) ) ) {
			if ( in_array( $directory_name, array( '.', '..', 'demo-element' ), true ) ) {
				continue;
			}

			// Check if we have a val;id directory.
			$template_dir = trailingslashit( $templates_directory_path ) . $directory_name;
			if ( ! is_dir( $template_dir ) ) {
				continue;
			}

			// Make sure we have mandatory files.
			$mandatory_files_missing = false;
			foreach ( array( 'thumbnail.jpg', 'config-vc.php' ) as $file ) {
				if ( ! file_exists( $template_dir . DIRECTORY_SEPARATOR . $file ) ) {
					$mandatory_files_missing = true;
					break;
				}
			}

			// Skip if mandatory files not found.
			if ( $mandatory_files_missing ) {
				continue;
			}

			$template_dir_url = trailingslashit( $templates_directory_url ) . $directory_name;
			$template_id      = sanitize_title_with_dashes( $directory_name );
			$template_data    = require_once $template_dir . DIRECTORY_SEPARATOR . 'config-vc.php';

			if (
				! $template_data
				|| ! is_array( $template_data )
				|| ( ! isset( $template_data['name'] ) || empty( $template_data['name'] ) )
				|| ( ! isset( $template_data['content'] ) || empty( $template_data['content'] ) )
			) {
				continue;
			}

			if ( ! isset( $template_data['new'] ) ) {
				$template_data['new'] = false;
			}

			if ( ! isset( $template_data['disabled'] ) ) {
				$template_data['disabled'] = true;
			}

			if ( ! isset( $template_data['template_category'] ) || empty( $template_data['template_category'] ) ) {
				$template_data['template_category'] = esc_html__( 'Misc', 'cardealer' );
			}

			$template_data['template_category_slug'] = sanitize_title( $template_data['template_category'] );
			$template_data['image_path']             = trailingslashit( $template_dir_url ) . 'thumbnail.jpg';

			$templates[ $template_id ] = $template_data;

		}

		return $templates;
	}

	/**
	 * Function renderTemplateBlock.
	 *
	 * @param array $category Category.
	 * @return array
	 */
	public function renderTemplateBlock( $category ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		if ( $this->templates_type === $category['category'] ) {

			$category['output']  = '<div class="vc_col-md-2 cd-vc-sorting-container">';
			$category['output'] .= $this->get_template_categories();
			$category['output'] .= '</div>';

			$category['output'] .= '
			<div class="vc_column vc_col-sm-12 cd-vc-templates-container">
				<div class="vc_ui-template-list vc_templates-list-default_templates vc_ui-list-bar" data-vc-action="collapseAll">';
			if ( ! empty( $category['templates'] ) ) {
				foreach ( $category['templates'] as $template ) {
					$category['output'] .= $this->renderTemplateListItem( $template );
				}
			}
			$category['output'] .= '
			</div>
		</div>';

		}

		return $category;
	}

	/**
	 * Function get_template_categories.
	 *
	 * @return string
	 */
	protected function get_template_categories() {

		$output = '';

		$categories = $this->template_categories;

		$output         .= '<div class="sortable_templates">';
			$output     .= '<ul>';
				$output .= '<li class="active" data-sort="all">' . esc_html__( 'All', 'cardealer' ) . ' <span class="count">0</span></li>';

		if ( $categories && is_array( $categories ) && ! empty( $categories ) ) {
			foreach ( $categories as $key => $value ) {
				$output .= '<li data-sort="' . $key . '">' . $value . ' <span class="count">0</span></li>';
			}
		}

			$output .= '</ul>';
		$output     .= '</div>';

		return $output;

	}

	/**
	 * Output rendered template in new panel dialog.
	 *
	 * @since 1.0
	 *
	 * @param string $template_name Template name.
	 * @param array  $template_data Template data.
	 *
	 * @return string
	 */
	function renderTemplateWindow( $template_name, $template_data ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		if ( $this->templates_type === $template_data['type'] ) {
			return $this->renderTemplateWindowCsVcTemplates( $template_name, $template_data );
		}

		return $template_name;
	}

	/**
	 * Function renderTemplateWindowCsVcTemplates.
	 *
	 * @since 1.0
	 *
	 * @param string $template_name Template name.
	 * @param array  $template_data Template data.
	 *
	 * @return string
	 */
	public function renderTemplateWindowCsVcTemplates( $template_name, $template_data ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		// @codingStandardsIgnoreStart
		ob_start();
		$template_id            = esc_attr( $template_data['unique_id'] );
		$template_id_hash       = md5( $template_id ); // needed for jquery target for TTA
		$template_name          = esc_html( $template_name );
		$preview_template_title = esc_attr__( 'Preview template', 'cardealer' );
		$add_template_title     = esc_attr__( 'Add template', 'cardealer' );

		echo <<<HTML
		<button type="button" class="vc_ui-list-bar-item-trigger" title="$add_template_title"
			data-template-handler=""
			data-vc-ui-element="template-title">$template_name</button>
		<div class="vc_ui-list-bar-item-actions">
			<button type="button" class="vc_general vc_ui-control-button" title="$add_template_title"
					data-template-handler="">
				<i class="vc-composer-icon"></i>
			</button>
		</div>
HTML;

		return ob_get_clean();
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Function renderFrontendTemplate.
	 *
	 * @since 1.0
	 * vc_filter: vc_templates_render_frontend_template - called when unknown template received to render in frontend.
	 */
	function renderFrontendTemplate() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie()->part( 'templates' )->can()->validateDie();

		add_filter( 'vc_frontend_template_the_content', array( $this, 'frontendDoTemplatesShortcodes' ) );

		$template_id   = vc_post_param( 'template_unique_id' );
		$template_type = vc_post_param( 'template_type' );

		add_action( 'wp_print_scripts', array( $this, 'addFrontendTemplatesShortcodesCustomCss' ) );

		if ( '' === $template_id ) {
			wp_die( 'Error: Vc_Templates_Panel_Editor::renderFrontendTemplate:1' );
		}

		WPBMap::addAllMappedShortcodes();
		if ( $this->templates_type === $template_type ) {
			$this->renderFrontendDefaultTemplate();
		} else {
			echo apply_filters( 'vc_templates_render_frontend_template', $template_id, $template_type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		wp_die(); // no needs to do anything more. optimization.
	}

	/**
	 * Load frontend default template content by index.
	 *
	 * @since 1.0
	 */
	public function renderFrontendDefaultTemplate() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		$template_index = vc_post_param( 'template_unique_id' );
		$data           = $this->getDefaultTemplate( $template_index );
		$data_content   = isset( $data['content'] ) ? $data['content'] : '';

		// Replace the images
		if ( isset( $data['config'] ) && $data['config'] ) {
			$data_content = $this->replace_media( $data );
		}

		if ( ! $data ) {
			wp_die( 'Error: Vc_Templates_Panel_Editor::renderFrontendDefaultTemplate:1' );
		}

		vc_frontend_editor()->setTemplateContent( trim( $data_content ) );
		vc_frontend_editor()->enqueueRequired();
		vc_include_template(
			'editors/frontend_template.tpl.php',
			array(
				'editor' => vc_frontend_editor(),
			)
		);
		wp_die();
	}

	/**
	 * Calls do_shortcode for templates.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function frontendDoTemplatesShortcodes( $content ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		return do_shortcode( $content );
	}

	/**
	 * Add custom css from shortcodes from template for template editor.
	 * Used by action 'wp_print_scripts'.
	 *
	 * @todo move to autoload or else some where.
	 * @since 1.0
	 */
	public function addFrontendTemplatesShortcodesCustomCss() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		$output                = '';
		$shortcodes_custom_css = '';
		$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( vc_frontend_editor()->getTemplateContent() );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
			$output               .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			$output               .= $shortcodes_custom_css;
			$output               .= '</style>';
		}
		echo <<<HTML
$output
HTML;
	}

	/**
	 * Loading Any templates Shortcodes for backend by string $template_id from AJAX
	 *
	 * @since 1.0
	 * vc_filter: vc_templates_render_backend_template - called when unknown template requested to render in backend
	 */
	public function renderBackendTemplate() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		$template_id   = vc_post_param( 'template_unique_id' );
		$template_type = vc_post_param( 'template_type' );

		if ( ! isset( $template_id, $template_type ) || '' === $template_id || '' === $template_type ) {
			wp_die( 'Error: Cardealer_Vc_Templates_Panel_Editor::renderBackendTemplate:1' );
		}

		WPBMap::addAllMappedShortcodes();

		if ( $this->templates_type === $template_type ) {
			$this->getBackendDefaultTemplate();
			wp_die();
		} else {
			echo apply_filters( 'vc_templates_render_backend_template', $template_id, $template_type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die();
		}

		wp_die();
	}

	/**
	 * Function to get all templates for display
	 *  - with image (optional preview image)
	 *  - with unique_id (required for do something for rendering.. )
	 *  - with name (required for display? )
	 *  - with type (required for requesting data in server)
	 *  - with category key (optional/required for filtering), if no category provided it will be displayed only in
	 * "All" category type vc_filter: vc_get_user_templates - hook to override "user My Templates" vc_filter:
	 * vc_get_all_templates - hook for override return array(all templates), hook to add/modify/remove more templates,
	 *  - this depends only to displaying in panel window (more layouts)
	 *
	 * @since 1.0
	 * @return array - all templates with name/unique_id/category_key(optional)/image
	 */
	public function getAllTemplates() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		$data               = array();
		$category_templates = array();

		foreach ( $this->templates as $template_id => $template_data ) {

			$category_templates[ $template_id ] = array(
				'unique_id'              => $template_id,
				'name'                   => $template_data['name'],
				'type'                   => $this->templates_type,
				'new'                    => isset( $template_data['new'] ) ? $template_data['new'] : false,
				'image'                  => isset( $template_data['image_path'] ) ? $template_data['image_path'] : false,
				'image_original'         => isset( $template_data['image_path_original'] ) ? $template_data['image_path_original'] : false,
				'custom_class'           => isset( $template_data['custom_class'] ) ? $template_data['custom_class'] : false,
				'template_category'      => isset( $template_data['template_category'] ) ? $template_data['template_category'] : false,
				'template_category_slug' => isset( $template_data['template_category_slug'] ) ? $template_data['template_category_slug'] : false,
				'template_cat_class'     => isset( $template_data['template_category_slug'] ) ? $template_data['template_category_slug'] : false,
				'content'                => isset( $template_data['content'] ) ? $template_data['content'] : false,
				'config'                 => isset( $template_data['config'] ) ? $template_data['config'] : false,
			);
		}

		if ( ! empty( $category_templates ) ) {
			$data = $category_templates;
		}

		return $data;
	}

	/**
	 * Load default templates list and initialize variable
	 * To modify you should use add_filter('vc_load_default_templates','your_custom_function');
	 * Argument is array of templates data like:
	 *      array(
	 *          array(
	 *              'name'=>__('My custom template','my_plugin'),
	 *              'image_path'=> preg_replace( '/\s/', '%20', plugins_url( 'images/my_image.png', __FILE__ ) ), //
	 * always use preg replace to be sure that "space" will not break logic
	 *              'custom_class'=>'my_custom_class', // if needed
	 *              'content'=>'[my_shortcode]yeah[/my_shortcode]', // Use HEREDoc better to escape all single-quotes
	 * and double quotes
	 *          ),
	 *          ...
	 *      );
	 * Also see filters 'vc_load_default_templates_panels' and 'vc_load_default_templates_welcome_block' to modify
	 * templates in panels tab and/or in welcome block. vc_filter: vc_load_default_templates - filter to override
	 * default templates array
	 *
	 * @since 1.0
	 * @return array
	 */
	public function loadDefaultTemplates() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		if ( ! is_array( $this->all_templates ) ) {
			$this->all_templates = $this->getAllTemplates();
		}

		return $this->all_templates;
	}

	/**
	 * Get default template data by template index in array.
	 *
	 * @since 1.0
	 *
	 * @param number $template_index Template index.
	 *
	 * @return array|bool
	 */
	public function getDefaultTemplate( $template_index ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		$this->loadDefaultTemplates();
		if ( ! is_array( $this->all_templates ) || ! isset( $this->all_templates[ $template_index ] ) ) {
			return false;
		}

		return $this->all_templates[ $template_index ];
	}

	/**
	 * Load default template content by index from ajax.
	 *
	 * @since 1.0
	 *
	 * @param bool $return | should function return data or not.
	 * @return string
	 */
	public function getBackendDefaultTemplate( $return = false ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		$data_new_content = '';
		$replace_with     = array();
		$template_index   = vc_request_param( 'template_unique_id' );
		$data             = $this->getDefaultTemplate( $template_index );
		$data_content     = isset( $data['content'] ) ? $data['content'] : '';

		// Replace the images
		if ( isset( $data['config'] ) && $data['config'] ) {
			$data_content = $this->replace_media( $data );
		}

		if ( ! $data ) {
			wp_die( 'Error: Cardealer_Vc_Templates_Panel_Editor::getBackendDefaultTemplate:1' );
		}
		if ( $return ) {
			return trim( $data_content );
		} else {
			echo trim( $data_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			wp_die();
		}
	}

	public function replace_media( $data ) {

		$replace_with = array();
		$data_content = isset( $data['content'] ) ? $data['content'] : '';
		if ( isset( $data['config']['images'] ) && $data['config']['images'] ) {
			$image_ids = $data['config']['images'];
			foreach ( $image_ids as $image_key => $image_url ) {
				$media_imgs = get_option( 'cd-studio-imported-images' );
				if ( ! $media_imgs || ! is_array( $media_imgs ) ) {
					$media_imgs = array();
				}

				$key_id = array_search( $image_url, $media_imgs );
				if ( ! $key_id || ( $key_id && ( ! get_post_status( $key_id ) || ( get_post_status( $key_id ) && 'attachment' != get_post_type( $key_id ) ) ) ) ) {
					$img_id = $this->add_media( $image_url );
					if ( $img_id ) {
						if ( isset( $media_imgs[$key_id] ) ){
							unset( $media_imgs[$key_id] );
						}

						$replace_with[ '{{' . $image_key . '}}' ] = $img_id;
						$media_imgs[$img_id] = $image_url;
						update_option( 'cd-studio-imported-images', $media_imgs );
					}
				} else {
					$replace_with[ '{{' . $image_key . '}}' ] = $key_id;
					$media_imgs[$key_id] = $image_url;
					update_option( 'cd-studio-imported-images', $media_imgs );
				}
			}

			if ( $replace_with && $data_content ) {
				$data_content = str_replace( array_keys( $replace_with ), $replace_with, $data_content );
			}
		}

		return $data_content;
	}

	public function add_media( $src ) {

		$postdata = array();

		$media_id = $this->importer->process_attachment($postdata, $src);
		if( is_wp_error($media_id) || ! $media_id ) return false;

		return $media_id;
	}

	/**
	 * Function renderTemplateListItem.
	 *
	 * @param array $template Template.
	 * @return string
	 */
	public function renderTemplateListItem( $template ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		$template_id             = esc_attr( $template['unique_id'] );
		$template_id_hash        = md5( $template_id ); // needed for jquery target for TTA.
		$name                    = isset( $template['name'] ) ? esc_html( $template['name'] ) : esc_html__( 'No title', 'cardealer' );
		$template_name           = esc_html( $name );
		$template_name_lower     = esc_attr( vc_slugify( $template_name ) );
		$new                     = esc_attr( isset( $template['new'] ) ? $template['new'] : '' );
		$template_type           = esc_attr( isset( $template['type'] ) ? $template['type'] : 'custom' );
		$custom_class            = esc_attr( isset( $template['custom_class'] ) ? ' ' . $template['custom_class'] : '' );
		$template_image          = esc_attr( isset( $template['image'] ) ? $template['image'] : '' );
		$template_image_original = esc_attr( isset( $template['image_original'] ) ? $template['image_original'] : '' );
		$template_category       = esc_attr( isset( $template['template_category'] ) ? $template['template_category'] : '' );
		$template_cat_class      = esc_attr( isset( $template['template_cat_class'] ) ? $template['template_cat_class'] : '' );

		$output  = <<<HTML
					<div class="vc_ui-template vc_templates-template-type-default_templates {$template_cat_class}{$custom_class}"
						data-template_id="$template_id"
						data-template_id_hash="$template_id_hash"
						data-category="$template_type"
						data-template_unique_id="$template_id"
						data-template_name="$template_name_lower"
						data-template_type="$template_type"
						data-vc-content=".vc_ui-template-content">
						<div class="vc_ui-list-bar-item">
HTML;
		$output .= '<div class="cd-vc-template-preview">';
		if ( $new ) {
			$output .= '<span class="cd-vc-badge-new">New</span>';
		}
		$output .= '<img class="lazy" src="" data-src="' . esc_url( $template_image ) . '" data-src-original="' . esc_url( $template_image_original ) . '" alt="' . esc_attr( $name ) . '" width="300" height="200" /></div>';
		$output .= apply_filters( 'vc_templates_render_template', $name, $template );
		$output .= '<span class="cd-vc-template-categories">' . esc_html( $template_category ) . '</span>';
		$output .= <<<HTML
						</div>
						<div class="vc_ui-template-content" data-js-content>
						</div>
					</div>
HTML;

		return $output;
	}

	/**
	 * Function getOptionName.
	 *
	 * @return string
	 */
	public function getOptionName() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
		return $this->option_name;
	}

	public function load_importers() {

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';
		// For front editor
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$importer_error = false;

		// check if wp_importer, the base importer class is available, otherwise include it
		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) {
				require_once( $class_wp_importer );
			} else {
				$importer_error = true;
			}
		}

		if ( defined( 'CDHL_PATH' ) ) {
			/** Functions missing in older WordPress versions. */
			require_once trailingslashit( CDHL_PATH ) . 'includes/importer/compat.php';

			/** WXR_Parser class */
			require_once trailingslashit( CDHL_PATH ) . 'includes/importer/parsers/class-cdhl-wxr-parser.php';

			/** WXR_Parser_SimpleXML class */
			require_once trailingslashit( CDHL_PATH ) . 'includes/importer/parsers/class-cdhl-wxr-parser-simplexml.php';

			/** WXR_Parser_XML class */
			require_once trailingslashit( CDHL_PATH ) . 'includes/importer/parsers/class-cdhl-wxr-parser-xml.php';

			/** WXR_Parser_Regex class */
			require_once trailingslashit( CDHL_PATH ) . 'includes/importer/parsers/class-cdhl-wxr-parser-regex.php';

			/** WP_Import class */
			require_once trailingslashit( CDHL_PATH ) . 'includes/importer/class-cdhl-wp-import.php';
		} else {
			return false;
		}

		if ( $importer_error !== false ) {
			return false;
		}

		if ( class_exists( 'WP_Importer' ) && class_exists( 'Cardealer_Helper_WP_Import' ) ) {
			$this->importer                    = new Cardealer_Helper_WP_Import();
			$this->importer->fetch_attachments = true;
		} else {
			return false;
		}
	}
}
$cardealer_vc_templates = new Cardealer_Vc_Templates_Panel_Editor();
$cardealer_vc_templates->init();
