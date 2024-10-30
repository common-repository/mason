<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The public-facing functionality of the plugin.
 *
 * @link       thinkjoinery.com/about/tripgrass
 * @since      1.0.0
 *
 * @package    Mason
 * @subpackage Mason/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mason
 * @subpackage Mason/public
 * @author     Joinery <trip@thinkjoinery.com>
 */
class Mason_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The languages of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $languages    [ 'name' => 'slug' ]
	 */
	private $languages;

	/**
	 * The containers of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $containers    [ ['container_name' => '', 'container_slug' => ''] ]
	 */
	private $containers;

	/**
	 * The moduless of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $modules    [ 'module_name' ]
	 */
	private $module_names;

	/**
	 * The moduless of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $module_classes    
	 */
	private $module_classes;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $languages, $containers, $module_names, $module_classes ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->languages = $languages;
		$this->containers = $containers;
		$this->module_names = $module_names;
		$this->module_classes = $module_classes;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mason_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mason_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dest/css/main.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mason_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mason_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dest/js/app.min.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */
	public function register_shortcodes() {
		add_shortcode( 'mason_build_blocks', array( $this, 'get_all_blocks' ) );
		add_shortcode( 'mason_show_language_toggle', array( $this, 'show_language_toggle' ) );
	} // register_shortcodes()

	/**
	 * 
	 *
	 * @param   array	$params		
	 *
	 * @uses	
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function get_mapped_classes( $block_type, $acf_incr ){
		$all_meta = get_post_custom();
		$mapped_classes = "";
		if(is_array($all_meta)){
			foreach( $all_meta as $meta_value => $arr ){
	//			if( preg_match( '/^content_blocks_([0-9]+)_class_map/', $meta_value ) && 
				if( preg_match( '/^content_blocks_'.$acf_incr.'_class_map/', $meta_value ) && 
					preg_match( '/'.$block_type.'/', $meta_value ) ){
					foreach( $arr as $value ){
						if( is_array( @unserialize( $value ) ) ){
							foreach( unserialize($value) as $val ){
								$mapped_classes .= " ".$val;
							}
						}
						else{
							$mapped_classes .= " ".$value;
						}
					}
				}
			}
		}
		return $mapped_classes;
	}

	/**
	 * 
	 *
	 * @param   array	$params		
	 *
	 * @uses	
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function get_dir_name_from_class($class_name){
		foreach( $this->module_names as $module){
			if( $module['class'] == $class_name ){
				return $module['dir_name'];
			}
		}
		return false;
	}

	/**
	 * Processes shortcode mason_build_blocks
	 *
	 * @param   array	$atts		The attributes from the shortcode
	 *
	 * @uses	
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function get_all_blocks( $atts = array() ) {
		ob_start();
		$block_output = "";
		$option = "";
		if( array_key_exists('is_option', $atts) && $atts['is_option'] ){
			$option = 'option';
		}
		$container_name = $atts['container']."_blocks";
		if( have_rows($container_name , $option ) ):
			global $i;
			$i = 0;
			while ( have_rows($container_name , $option) ): 
				the_row();

				$block_type = preg_replace( '/_block/','',get_row_layout());
				if( array_key_exists($block_type, $this->module_names) || preg_match ('/global_/',$block_type)){
					if(preg_match ('/global_/',$block_type)){
						$template_path = "";
					}
					else{
						$template_path = $this->module_names[$block_type]['template_path'];
					}
					$params = array(
						'type'=>$block_type,
						'template_path' => $template_path
					);
					global $args;
					$args = array();
					$custom_classes = "";
					$color_class = " ";
					$user_func_args = null;
					if( preg_match ('/global_/',$block_type) ){
						$block_type = "global";

						$user_func_args = array( 
							'container_name' => $container_name,
							'container_type' => preg_replace('/_blocks/','',$container_name)
						);
					}

					$args = call_user_func('build_' . $block_type . '_layout', $user_func_args); // if is global, will call build_global_layout in global_acf_def.php

					if( 'global' == $block_type ){
						$params['type'] = $global_block_type = $args['global_type']; // sets the module type the global uses
						$params['template_path'] = $this->module_names[$global_block_type]['template_path'];
						// globals are stored in wp_options as 'options_content_blocks_{$global_key}_images_0_image' 
						$params['global_key'] = get_sub_field('global_block_predefined_block');
	 					$custom_classes .= get_option('options_content_blocks_'.$params['global_key'].'_custom_classes_'.$args['global_type']);
						if( get_option('options_content_blocks_'.$params['global_key'].'_'.preg_replace('/-/','_',$args['global_type']).'_include_global_classes')){
							$custom_classes .= get_field('global_classes','option');
						}
					}	
					if( count( $args ) > 0 ){
						if( $block_type != 'media' ){
							$args['id'] = $block_type.'_block_'.$i;
						}
						$args['acf_incr'] = $i;
						$params['args'] = $args;
						$params['classes'] = $custom_classes . $color_class;
						$block_output .= $this->get_block( $params );
					}
					$i++;
				}
			endwhile;
		endif;	
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	} // get_all_blocks()

	/**
	 * 
	 *
	 * @param   array	$params		
	 *
	 * @uses	
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function get_block( $params ){
		if( $block_type = $params['type'] ){
			$args = array();
			if( array_key_exists('args', $params) ){
				$args = $params['args'];
			}
			if( array_key_exists( 'classes', $params ) ){
				if( array_key_exists( 'global_type', $args ) ){
					$block_function = preg_replace('/-/','_',$block_type);
					$mapped_classes = $this->get_global_mapped_classes( $block_function, $params['global_key'] );
				}
				else{
					$mapped_classes = $this->get_mapped_classes( $block_type, $args['acf_incr'] );
				}
				$args_classes = "";
				if(array_key_exists('args', $params) && array_key_exists('classes', $params['args'])){
					$args_classes = $params['args']['classes'];
				}
				$custom_classes = $params['classes'] . " " .$mapped_classes . " " . $args_classes;
			}
			$style_output = "";
			if( array_key_exists( 'module_styles', $args ) ){
				$style_array = [];
				foreach($args['module_styles'] as $styleKey => $styleVal){
					$style_array[] = $styleKey . ":"  . $styleVal . " !important;";
				}
				$style_output = implode(" ", $style_array);
			}
			$id = "";
			if( array_key_exists( 'id', $args ) ){
				$id = "id='".$args['id']."'";
			}
			$block_class = preg_replace('/_/','-',$block_type);
			$block_dir_name = $this->get_dir_name_from_class($block_class);
			$root = $params['template_path'];
			$path =  $root.'/module-view.php';

			echo "<div style='" . $style_output . "' class='block-".$block_type . $this->module_classes ." ". $custom_classes ."' ". $id . "><div class='layout-content'>";
			require( $path );
			echo "</div><!--/layout-content--></div><!--/block-->";
			unset($args);
		}
	}

	/**
	 * 
	 *
	 * @param   array	$params		
	 *
	 * @uses	
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	function get_class_maps_field( $module_info ){
		$module = $module_info['func_name'];
		// add global classes
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
	 * 
	 *
	 * @param   array	$params		
	 *
	 * @uses	
	 *
	 * @return	mixed	$output		Output of the buffer
	 */
	public function get_global_mapped_classes( $block_type, $acf_incr ){
		$mapped_classes = "";
		$module_info = $this->module_names[$block_type];
		$module_mapping = $this->get_class_maps_field( $module_info );
		$mapped_classes = "";
		foreach( $module_mapping as $map ){
			$test = get_option('options_content_blocks_'.$acf_incr.'_'.$map['name']);
			if( is_array( $test ) ){
				foreach( $test as $v ){
					$mapped_classes .= " ".$v;
				}
			}
		}
		return $mapped_classes;
	}
	public function show_language_toggle(){
		$language = get_mason_language();
		echo $language;
		if( count($this->languages)>1 ){
			ob_start();
		    echo '<form id="lang-toggle" action="" method="post">
		    	<select name="mason_lang">';

		    foreach($this->languages as $name => $slug ){
		    	$selected = "";
		    	if( $language == $slug ){
		    		$selected = " selected";
		    	}
		        echo '<option value="'.$slug.'" '.$selected.'>'.$name.'</option>';
			}
			echo '</select><br>
		        <input type="hidden" name="lang_select" value="lang_select">
		    </form>';
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
    }
}
