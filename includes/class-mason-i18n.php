<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       thinkjoinery.com/about/tripgrass
 * @since      1.0.0
 *
 * @package    Mason
 * @subpackage Mason/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mason
 * @subpackage Mason/includes
 * @author     Joinery <trip@thinkjoinery.com>
 */
class Mason_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mason',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
