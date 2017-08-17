<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/tokkonopapa
 * @since      1.0.0
 *
 * @package    IPGB_Tester
 * @subpackage ipgb-tester/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    IPGB_Tester
 * @subpackage ipgb-tester/public
 * @author     tokkonopapa <tokkonopapa@yahoo.com>
 */
class IPGB_Tester_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			add_action( 'wp_ajax_ipgb-tester',        array( $this, 'ajax_hander_public' ) );
			add_action( 'wp_ajax_nopriv_ipgb-tester', array( $this, 'ajax_hander_public' ) );
		}

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
		 * defined in IPGB_Tester_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The IPGB_Tester_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ipgb-tester-public.css', array(), $this->version, 'all' );

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
		 * defined in IPGB_Tester_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The IPGB_Tester_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ipgb-tester-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'IPGB_TESTER', array(
			'url' => admin_url() . 'admin-ajax.php',
			'action' => 'ipgb-tester',
		) );
		wp_enqueue_script( $this->plugin_name );

	}


	/**
	 * Ajax handler on public facing pages.
	 *
	 * @since    1.0.0
	 */
	public function ajax_hander_public() {

		if ( function_exists( 'debug_log' ) ) {
//			debug_log( 'ajax_hander_public' );
		}

		wp_send_json( NULL, 200 ); // @since 3.5.0

	}

}
