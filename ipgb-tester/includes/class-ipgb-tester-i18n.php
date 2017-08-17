<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/tokkonopapa
 * @since      1.0.0
 *
 * @package    IPGB_Tester
 * @subpackage ipgb-tester/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    IPGB_Tester
 * @subpackage ipgb-tester/includes
 * @author     tokkonopapa <tokkonopapa@yahoo.com>
 */
class IPGB_Tester_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ipgb-tester',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
