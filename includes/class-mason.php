<?php

if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       thinkjoinery.com/about/tripgrass
 * @since      1.0.0
 *
 * @package    Mason
 * @subpackage Mason/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mason
 * @subpackage Mason/includes
 * @author     Joinery <trip@thinkjoinery.com>
 */
class Mason {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mason_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $params = [] ) {
		$this->version = '1.0.0';
		$this->root = preg_replace( '#\includes#', '', dirname( __FILE__ ));
		$this->plugin_name = 'mason';
		$this->output_messages = 0; 
		$this->use_global_modules = 1;
		$lang_array = [
			'eng' => 'English',
			'esp' => 'Espanol',
			'fra' => 'Francais'
		];
		if( function_exists('get_field') ) {
			$include_languages = get_field('include_multiple_languages','option');
			$this->languages = [
				"English" => "eng"
			];
			if( $include_languages ){
				$languages = get_field('included_languages','option');
				if( is_array($languages) && count($languages ) > 0 ){
					$i = 0;
					foreach( $languages as $lang ){
						if( !$i ){
							$first_lang = $lang;
							$i++;
						}
						$label = $lang_array[$lang];
						$this->languages[$label] = $lang;		
					}
				}
			}
			$this->current_language = get_mason_language();
			if( !$this->current_language ){
				$this->current_language = $first_lang;
				if( !$this->current_language ){
					$this->current_language = 'eng';
				}
			}
			$this->module_classes = $params['modules_classes'];

			$this->get_modules();
			$this->build_layouts();

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}
	}

	public function acf_load_custom_post_types( $field ) {
	    // reset choices
	    $field['choices'] = [
	    	'pages' => 'Pages',
	    	'posts' => 'Posts'
	    ];

		$args = array( '_builtin' => false );
		$post_types = get_post_types( $args, 'object' ); 
		foreach( $post_types as $key => $cpt){
			if('acf-field' != $key && 'acf-field-group' != $key){
				$slug = $cpt->name;
				$label = $cpt->label;
				$field['choices'][$slug] = $label;
			}
		}
	    // return the field
	    return $field;
	    
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mason_Loader. Orchestrates the hooks of the plugin.
	 * - Mason_i18n. Defines internationalization functionality.
	 * - Mason_Admin. Defines all hooks for the admin area.
	 * - Mason_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * responsible for creating the bas acf fields in settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/mapped-classes-acf-def.php';


		/**
		 * responsible for 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/global-acf-def.php';
		/**
		 * responsible for 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/flexible-content-acf-def.php';


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mason-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mason-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mason-admin.php';


		/**
		 * responsible for 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/acf-options-pages.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mason-public.php';

		/**
		 * responsible for creating the base plugin admin settings.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/settings-acf-def.php';

		/**
		 * responsible for
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/module-templates.php';

		$this->loader = new Mason_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mason_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mason_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mason_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		add_filter('acf/load_field/name=page_and_posts_to_remove_editor',  array( $this,'acf_load_custom_post_types'));

		add_filter('acf/load_field/name=included_modules', array( $this,'acf_load_all_modules'));

		add_filter('acf/load_field/name=_locations', array( $this,'load_locations'));
		if( get_field('remove_default_content_editor','option') ){
				add_action('init', 'remove_textarea');
		};

	}

	public function load_locations( $field ){
	    $field['choices'] = [
				'page' => 'Show on All Pages',
				'post' => 'Show on All Posts',
				'menus' => 'Show on Menus Option Page',
				'footer' => 'Show on Footer',
				'header' => 'Show on Headers',
	    ];

		$args = array( '_builtin' => false );
		$post_types = get_post_types( $args, 'object' ); 
		//print_r($post_types);
		foreach( $post_types as $key => $cpt){
			if('acf-field' != $key && 'acf-field-group' != $key){
				$slug = $cpt->name;
				$label = $cpt->label;
				$field['choices'][$slug] = "Show on " . $label;
			}
		}
		$page_ids= get_all_page_ids();
		foreach($page_ids as $id){
			$field['choices']['mason-page-title-' . $id] = "On Page: " . get_the_title($id);
		}
		$params = get_field('acf_field_locations','option');
		if( is_array($params) && count($params)> 0 ){
			foreach( $params as $param){
				$slug = $param['value'];
				$field['choices']['mason-cpt-'.$slug] = "Show on (extra) " . $param['label'];
			}
		}
	    // return the field	    
	    return $field;	
	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function message($message) {
		if($this->output_errors){
			echo $message;
		}
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Mason_Public( $this->get_plugin_name(), $this->get_version(), $this->languages, $this->containers, $this->module_names, $this->module_classes);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
	}

	/**
	 * Get all Containers
	 * previosuly get_modules()
	 * NOTE: ACF get_field option was breaking admin; used native get_option method instead
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_containers(){
		$count = get_option('options_mason_module_containers');
		$this->containers = [];
		if( $count > 0){
			$i =0;
			while($i < $count){
				$this->containers[] = [
					'container_name' => get_option('options_mason_module_containers_'.$i.'_container_name'),
					'container_slug' => get_option('options_mason_module_containers_'.$i.'_container_slug'),
					'container_modules' => get_option('options_mason_module_containers_'.$i.'_included_modules'),
					'container_locations' => get_option('options_mason_module_containers_'.$i.'__locations')
				];
				$i++;
			}
		}
		return $this->containers;
	}

	/**
	 * Get all ...?
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_class_maps_field( $module_info ){
		$module = $module_info['func_name'];
		$class_map_modules = get_field('class_map_modules','option');
		$fields = [];
		if( is_array( $class_map_modules ) ){
			foreach( $class_map_modules as $cmm ){
				if( in_array( $module, $cmm['module_type'] ) ){
					if( is_array( $cmm['class_groups'] ) ){
						foreach( $cmm['class_groups'] as $class_group ){
							if( $class_group['allow_multiple_choices'] == 1 ){
								$type = 'checkbox';
							}
							else{
								//$type = 'radio';
								$type = 'checkbox';
							}
							$choices = [];
							foreach( $class_group['class_maps'] as $cmap){
								$key = $cmap['selector'];
								$choices[$key] = $cmap['class_name']; 
							}
							$fields[] = array (
								'key' => 'field_5760969cb4592'.$class_group['group_name'].$module,
								'label' => $class_group['group_name'],
								'name' => 'class_map_'.$class_group['group_name'].$module,
								'type' => $type,
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => $choices,
								'default_value' => array (
								),
								'layout' => 'vertical',
								'toggle' => 0,
							);
						}
					}
				}
			}
		}
		return $fields;
	}

	/**
	 * Add Globally required ACF fields and definitions
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_additional_fields( $module_info ){
		$module = $module_info['func_name'];
		$module_mapping = $this->get_class_maps_field( $module_info );
		$this->additional_fields = [];
		foreach( $module_mapping as $field ){
			$this->additional_fields[] = $field;
		}
		return $this->additional_fields;
	}

	/**
	 * s
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_title_field( $module){
		$field = array (
				'key' => 'field_56951fdc7c2f6'.$module,
				'label' => 'Block Name',
				'name' => $module.'_block_name',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'readonly' => 0,
				'disabled' => 0,
			);
		return $field;
	}

	/**
	 * s
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_styling_field( $module){
		$styling_file = get_theme_file_path() . '/mason-modules/global-styling-acf.php';
		if(file_exists($styling_file)){
			require_once($styling_file);
			$styling_fields = get_styling_fields( $module );

			

		return $styling_fields;
		}
 //			return $this->modules;

		
	}

	/**
	 * Build Layouts of all modules.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function build_layouts() {
		if(property_exists($this, 'modules')){
			foreach( $this->modules as $key => $module_name ){
				$module_layout = array();
				// find override
				if( $child_theme_uri = get_field('child_theme_uri','option') ){
					$override_filename = $child_theme_uri . '/mason-modules/' . $module_name . '/functions.php';
				}
				else{
					$override_filename = get_theme_file_path() . '/mason-modules/' . $module_name . '/functions.php';
				}
				$default_filename = $this->root . '/includes/modules/' . $module_name . '/functions.php';
				$use_default = 1;
				if( file_exists ( $override_filename )){
					$filename = $override_filename;
					$use_default = 0;
					$template_path = get_theme_file_path() . "/mason-modules/".$module_name;
				}
				else{
					$template_path = $this->root . '/includes/modules/'.$module_name;
					$filename = $default_filename;
				}
				// module's acf layout is built and added to $mason->layouts[] in the following require
				if( file_exists ( $filename )){
					require $filename;
					$acf_filename = $template_path . '/module_layout_acf_def.php';
					if( file_exists ( $acf_filename )){
						require $acf_filename;
					}

					$this->module_names[$module_name] = array(
						'class' => preg_replace('/_/','-',$module_name),
						//'key' => $this->module_layout['key'],
						'func_name' => preg_replace('/-/','_',$module_name),
						'dir_name' => $module_name,
						'use_default' => $use_default,
						'template_path' => $template_path
					);
					$additional_fields = $this->get_additional_fields( $this->module_names[$module_name] );
					foreach( $additional_fields as $field ){
						$module_layout['sub_fields'][] = $field;
					}
					$sub_fields = [];
					if(array_key_exists('sub_fields', $module_layout)){
						$sub_fields = $module_layout['sub_fields'];
					}
					$module_layout['sub_fields'] = [];
					$lang_subs = [];
					$new_fields = [];
					if(property_exists($this, 'languages')){
						foreach( $this->languages as $name => $slug ){
							// setup tabs if languages is more than english
							if( !(1 == count($this->languages) && 'eng' == $slug  )){
								$module_layout['sub_fields'][] = array (
									'key' => create_key('lang',$module_name.$slug),
									'label' => $name,
									'name' => '',
									'type' => 'tab',
									'instructions' => '',
									'required' => 0,
									'conditional_logic' => 0,
									'wrapper' => array (
										'width' => '',
										'class' => '',
										'id' => '',
									),
									'placement' => 'top',
									'endpoint' => 0,
								);
							}
							foreach( $sub_fields as $field ){
								if( array_key_exists('conditional_logic', $field) && is_array($field['conditional_logic']) && count($field['conditional_logic'])>0){
								//print_r($field['conditional_logic']);
									foreach($field['conditional_logic'] as $first_i => $conditional){
										foreach($conditional as $second_i => $con ){
//											print_r($con);
											if(is_array($con) && array_key_exists('field',$con)){
												$field['conditional_logic'][$first_i][$second_i]['field'] = $con['field']."_".$slug;
											}
										}
									}

								}
								$field['key'] = $field['key'] . "_" . $slug;
								$field['name'] = $field['name'] . "_" . $slug;
								//print_r($field);
								$module_layout['sub_fields'][] = $field;
							}
						}
					}
					// adds title field to ALL modules for Global Select
					array_unshift( $module_layout['sub_fields'], $this->get_title_field( preg_replace('/-/','_',$module_name) ));
					$styling_fields = $this->get_styling_field( preg_replace('/-/','_',$module_name));
					if($styling_fields){
						$reversed_styling_fields = array_reverse($styling_fields,true);
						foreach($reversed_styling_fields as $styling_field){
							array_unshift( $module_layout['sub_fields'], $styling_field );
						}
					}
					$this->layouts[$module_name] = $module_layout;
				}
			}
		}
	}

	private function create_key($module=null,$name=null){
		return 'field_' . hash('md5', $module."_block".$name); 	
	}

	private function create_container( $container ){
		$name = $container['container_name'];
		$container_slug = $container['container_slug'];
		$locations = $container['container_locations'];
		$remaining_locations = $locations;
		$container_loc = [];

		$args = array( '_builtin' => false );
		$post_types = get_post_types( $args, 'object' ); 
		$cpts = [];
		foreach( $post_types as $key => $cpt){
			if('acf-field' != $key && 'acf-field-group' != $key){
				$slug = $cpt->name;
				$label = $cpt->label;
				$cpts[$slug] = $slug;
			}
		}
		$params = get_field('acf_field_locations','option');

		if( is_array($locations) ){
			if( in_array ('page', $locations ) ){
				$unset_key = array_search('page',$remaining_locations);
				unset($remaining_locations[$unset_key]);
				$container_loc[] =
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'page',
						),
					);
			}
			if( in_array ('post', $locations ) ){
				$unset_key = array_search('post',$remaining_locations);
				unset($remaining_locations[$unset_key]);
				$container_loc[] =
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'post',
						),
					);
			}
			if( in_array ('menus', $locations ) ){
				$unset_key = array_search('menus',$remaining_locations);
				unset($remaining_locations[$unset_key]);

				$container_loc[] =
					array (
						array (
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'menus',
						),
					);
			}
			if( in_array ('footer', $locations ) ){
				$unset_key = array_search('footer',$remaining_locations);
				unset($remaining_locations[$unset_key]);

				$container_loc[] =
					array (
						array (
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'footer',
						),
					);
			}
			if( in_array ('header', $locations ) ){
				$unset_key = array_search('header',$remaining_locations);
				unset($remaining_locations[$unset_key]);

				$container_loc[] =
					array (
						array (
							'param' => 'options_page',
							'operator' => '==',
							'value' => 'header',
						),
					);
			}
			if( in_array ('two-column', $locations ) ){
				$unset_key = array_search('two-column',$remaining_locations);
				unset($remaining_locations[$unset_key]);

				$container_loc[] =
						array(
							array(
								'param' => 'page_template',
								'operator' => '==',
								'value' => 'page-two-column.php',
							),
						);
					}
			foreach($remaining_locations as $key => $slug){
				if(in_array($slug, $cpts)){
					$container_loc[] =
						array (
							array (
								'param' => 'post_type',
								'operator' => '==',
								'value' => $slug,
							),
						);
				}
				else{
					if(preg_match('/mason-page-title/', $slug)){
						$page_id = preg_replace('/mason-page-title-/','', $slug);
						$container_loc[] =
							array (
								array (
									'param' => 'page',
									'operator' => '==',
									'value' => $page_id,
								),
							);

					}
					if(preg_match('/mason-cpt/', $slug)){
						$custom_slug = preg_replace('/mason-cpt-/','',$slug);
						if( is_array( $params) &&  count( $params) > 0 ){
							foreach( $params as $param ){
								if($custom_slug == $param['value'] ){
									$container_loc[] =
										array (
											array (
												'param' => $param['param'],
												'operator' => $param['operator'],
												'value' => $param['value'],
											),
										);

								}
							}
						}
					}
				}

			}
		}
		$container_loc[] =
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'global-modules',
				),
			);
		$container_layouts = [];
		if( is_array( $container['container_modules'] ) ){
			foreach( $container['container_modules'] as $key => $module_name ){
//				if( in_array( preg_replace('/_block/','',$module_name), $included_modules ) ){
					if(array_key_exists($module_name, $this->layouts)){
						$container_layouts[] = $this->layouts[$module_name];
					}
//				} 
			}
		}
		// include global module builder
		if( $this->use_global_modules ){
//			require_once get_theme_file_path() . '/includes/modules/global_acf_def.php';
			// only register the global modules on the Options Page and for the front end
			if( ( array_key_exists( 'page', $_GET ) && $_GET['page'] != 'global-modules' ) || !array_key_exists( 'page', $_GET ) ){
				$container_layouts[] = $this->create_global($container);
			}
		}
		$this->containers[$container_slug] = array (
			'key' => create_key('group',$container_slug),
			'title' => $name,
			'fields' => array (
				array (
					'key' => create_key('block',$container_slug),
					'label' => $name . " Blocks",
					'name' => $container_slug . '_blocks',
					'type' => 'flexible_content',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'button_label' => 'Add a Content Block',
					'min' => '',
					'max' => '',
					'layouts' => $container_layouts,
				),
			),
			'location' => $container_loc,
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		);

		acf_add_local_field_group( $this->containers[$container_slug] );
	}

	private function create_global( $container ){
		$slug = $container['container_slug'];
		$choices = load_global_blocks($slug);
		return	array (
						'key' => create_key('global_block',$slug),
						'name' => 'global_block_'.$slug,
						'label' => 'Predefined Blocks',
						'display' => 'block',
						'sub_fields' => array (
							array (
								'key' => 	create_key('global_block_predefined_block', $slug),
								'label' => 'Choose your predefined Block',
								'name' => 'global_block_predefined_block_'.$slug,
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'choices' => $choices,
								'default_value' => array (
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'placeholder' => '',
								'disabled' => 0,
								'readonly' => 0,
							),
						),
						'min' => '',
						'max' => '',
					);
	}

	public function load_containers(){
		  $field['choices'] = [

							'page' => 'Show on All Pagesssssssssssssss',
							'post' => 'Show on All Posts',
							'menus' => 'Show on Menus Option Page',
							'footer' => 'Show on Footer',
							'header' => 'Show on Header'
	    ];

		$args = array( '_builtin' => false );
		$post_types = get_post_types( $args, 'object' ); 
		//print_r($post_types);
		foreach( $post_types as $key => $cpt){
			if('acf-field' != $key && 'acf-field-group' != $key){
				$slug = $cpt->name;
				$label = $cpt->label;
				$field['choices'][$slug] = $label;
			}
		}
	    // return the field
	    return $field;
	}
	public function final_crap(){
			// all module layouts are added to the $pilot->flexible_content layouts key in the following require
		//require dirname(__FILE__) . '/flexible-content-acf-def.php';
		//$this->module_containers = get_field('module_containers','option');)
		//require_once get_theme_file_path() . '/includes/modules/global_acf_def.php';
		if( is_array( $this->containers ) ){
			foreach($this->containers as $container){
//print_r($container);
//echo 'included_modules'.$container['container_slug']."*****";
				$this->create_container($container);
				if( $this->use_global_modules ){
					// only register the global modules on the Options Page and for the front end
					if( ( array_key_exists( 'page', $_GET ) && $_GET['page'] != 'global-modules' ) || !array_key_exists( 'page', $_GET ) ){
						$this->layouts[] = $this->create_global( $container );
					}
				}	
			}
		}
	//TEMPLATES!!!!!!!!!!!!!!!!!!!!
	   $pages = get_field('pages','option');
		if( is_array( $pages ) ){
			foreach($pages as $page){
				$container = [
					'container_slug' => $page['page_slug'],
					'container_name' => $page['page_name'],
					'container' => $page['page_container'],
				];
				add_filter('acf/load_field/name=included_modules'.$container['container_slug'], 'acf_load_all_modules');
				$this->create_template($container);
				if( $this->use_global_modules ){
					// only register the global modules on the Options Page and for the front end
					if( ( array_key_exists( 'page', $_GET ) && $_GET['page'] != 'global-modules' ) || !array_key_exists( 'page', $_GET ) ){
						$this->layouts[] = $this->create_global( $container );
					}
				}
				
			}
		}
	}

	public function create_template( $container ){
		$name = $container['container_name'];
		$slug = $container['container_slug'];
		$container_name = $container['container'];
		if( function_exists('acf_add_options_sub_page') ) {
		    acf_add_options_sub_page(
		        array(
		            'page_title'    => $name,
		            'menu_title'    => "Template : " . $name,
		            'menu_slug'     => $slug,
		            'capability'    => 'edit_posts',
		            'parent_slug'   => 'template-settings',
		            'icon_url'      => false
		        )
		    );
		}
// LOCATIONS ARE THE PAGE->SLUGS ABOVE!!!!!!!!!
		$container_loc[] = array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => $slug,
			),
		);
		foreach( $this->containers as $key => $container_definition ){
			if( is_numeric($key)){
				if( $container_name == $container_definition['container_slug']){
					$included_modules = $container_definition['container_modules'];
				}
			}
		}

		$all_containers = get_field('mason_module_containers','option');
		$container_layouts = [];
		if( is_array( $included_modules ) ){
			foreach( $this->layouts as $key => $layout ){
				if( in_array( preg_replace('/_block/','',$layout['name']), $included_modules ) ){
					$container_layouts[] = $layout;
				} 
			}
		}


		// include global module builder
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/global-acf-def.php';
			// only register the global modules on the Options Page and for the front end
			if( ( array_key_exists( 'page', $_GET ) && $_GET['page'] != 'global-modules' ) || !array_key_exists( 'page', $_GET ) ){
				$container_arr =[
					'container_slug' => $container['container']
				]; 
				$container_layouts[] = $this->create_global($container_arr);
			}
		$this->containers[$slug] = array (
			'key' => create_key('group',$slug),
			'title' => $name,
			'fields' => array (
				array(
			'key' => 'field_5b019ea5f34f6'.$slug,
			'label' => '',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '<input type="hidden" id="option-name" value="'.$slug.'"><input type="hidden" id="pilot-template-option-title" placeholder="Page Title *required"><br><br><button id="new_page" type="submit">Create a New Page from the '. $name .' Template</button>',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
				array (
					'key' => create_key('block',$slug),
					'label' => $name . " Blocks",
					'name' => $slug . '_blocks',
					'type' => 'flexible_content',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'button_label' => 'Add a Content Block',
					'min' => '',
					'max' => '',
					'layouts' => $container_layouts,
				),
			),
			'location' => $container_loc,
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		);
		acf_add_local_field_group( $this->containers[$slug] );

	}
	public function acf_load_all_modules( $field ) {
		$field['choices'] = array();
		foreach( $this->modules as $module ){
			$field['choices'][ $module ] = $module;
		}
		return $field;
	    
	}	
//	add_filter('acf/load_field/name=module_type', 'acf_load_all_modules');

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		// joinery:::
		if( is_admin() ){
			$this->final_crap();
//			add_action( 'admin_menu', array($this,'final_crap') ); this was commented out on 6/2/2020 in order to get the post_object to work in admin. Not sure why it was different from front end.
		}
		else{
			$this->final_crap();

		}
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mason_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Output Plugin error messages for development
	 *
	 * @since     1.0.0
	 * @return    string     string of error message
	 */
	public function error($message) {
		if($this->output_messages){
			echo $message;
		}
	}	

	/**
	 * Retrieve the modules included in the modules directories of the plugin and the theme.
	 *
	 * @since     1.0.0
	 * @return    array     Array of module names as string
	 */
	public function get_modules(){
			$default_path = $this->root . "/includes/modules";
			$dirs = glob($default_path . '/*' , GLOB_ONLYDIR);
			$this->modules = array();
			/**
				Iterate over the module directories automatically included in the plugin.
				Take the directory name as the module slug
				TO DO: iterate over the theme folder to find overrides
					$module will have to have a value that identifies it as "root" or override
			*/
			foreach( $dirs as $dir ){
				$pos = strrpos($dir, '/') + 1;
				$module = substr($dir,$pos);
				if( is_string($module) ){
					$this->modules[] = $module;
				}
			}
			$override_path = get_theme_file_path() . '/mason-modules';
			$override_dirs = glob($override_path . '/*' , GLOB_ONLYDIR);
			if( count($override_dirs)>0 ){
				foreach( $override_dirs as $dir ){
					$pos = strrpos($dir, '/') + 1;
					$module = substr($dir,$pos);
					if( is_string($module) ){
						if(!in_array($module,$this->modules)){
							$this->modules[] = $module;
						}
					}
				}
			}
 			return $this->modules;
		}
	}
