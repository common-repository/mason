<?php
/**
 * Plugin Name:       Mason
 * Plugin URI:        thinkjoinery.com/plugins/mason
 * Description:       Mason extends the Advanced Custom Fields Plugin to manage predefined Flexible Content Blocks in a single directory.
 * Version:           1.5.0
 * Author:            ThinkJoinery
 * Author URI:        thinkjoinery.com/about/tripgrass
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mason
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

function remove_textarea() {
	$removes = get_field('page_and_posts_to_remove_editor', 'option');
	foreach( $removes as $remove){
		$post_type = $remove;
		if( 'posts' == $remove ){
			$post_type ='post';
		}
		if( 'pages' == $remove ){
			$post_type ='page';
		}
	    remove_post_type_support( $post_type, 'editor' );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mason-activator.php
 */
function activate_mason() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mason-activator.php';
	Mason_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mason-deactivator.php
 */
function deactivate_mason() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mason-deactivator.php';
	Mason_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mason' );
register_deactivation_hook( __FILE__, 'deactivate_mason' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mason.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mason_get_params(){
	return [
		'modules_classes' => ' module ' 	// ultimately make this an acf field from settings
	];

}
function run_mason() {
	global $mason_plugin;
	$mason_plugin = new \Mason( mason_get_params() );
	//check for plugin dependeny
	if( function_exists('get_field') ) {
		$mason_plugin->run();
	}
	else{
		$mason_plugin->error("Create Error handler for ACF not installed");
	}
}

	run_mason();
function mason_get_sub_field( $field_name ){
	global $mason_plugin;
	$lang = $mason_plugin->current_language;
	return get_sub_field( $field_name ."_". $lang );
}
function mason_have_rows( $field_name ){
	global $mason_plugin;
	$lang = $mason_plugin->current_language;
	if( have_rows('alternating_block_rows'."_".$lang) ){
		return true;
	}
	else{
		false;
	}

}
function create_key($module=null,$name=null){
	return 'field_' . hash('md5', $module."_block".$name); 	
}
add_action( 'init', 'process_language_form', 1 );
add_action( 'init', 'get_mason_language', 9 );
	function process_language_form() {
	    if( array_key_exists( 'lang_select' , $_POST ) ) {				
			$language = "eng";
			if( array_key_exists( 'mason_lang', $_POST ) ){
				$language = sanitize_text_field($_POST['mason_lang']);
			}
			set_mason_language( $language );
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			wp_redirect($url);
			exit;
	     }
	}
	function set_mason_language( $lang = null ){
		if(!$lang){
			$lang = "eng";
		}
		$expire = time()+60*60*24*30;
		setcookie('mason-lang', $lang, $expire, "/");
	}
	function get_mason_language(){
		if(isset($_COOKIE['mason-lang'])) {
			$lang = sanitize_text_field($_COOKIE['mason-lang']);
		}
		else{
			$lang = "eng";
		}
		return $lang;
	}


function my_acf_save_post( $post_id ) {
    
    // bail early if no ACF data
    if( empty($_POST['acf']) ) {
        
        return;
        
    }
}
//add_action('acf/save_post', 'my_acf_save_post', 1);