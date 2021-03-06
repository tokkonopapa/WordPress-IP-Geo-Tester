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

		// Initialize something.
		add_action( 'init', array( $this, 'plugin_init' ) );

	}

	/**
	 * Initialize for the public area.
	 *
	 * @since    1.0.0
	 */
	public function plugin_init() {

		// Examples of short code
		// [ipgb-tester type="0" nopriv="true" ] for public facing pages
		// [ipgb-tester type="0" nopriv="false"] for admin dashboard
		add_shortcode( IPGB_TESTER_SLUG, array( $this, 'process_shortcode' ) );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $this->get_ajax_action();
			add_action( 'wp_ajax_'        . $action,             array( $this, 'ajax_hander_public' ) );
			add_action( 'wp_ajax_'        . $action . '-nopriv', array( $this, 'ajax_hander_public' ) );
			add_action( 'wp_ajax_nopriv_' . $action . '-nopriv', array( $this, 'ajax_hander_public' ) );
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
			'action' => $this->get_ajax_action( TRUE ),
		) );
		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Ajax action name on public facing pages.
	 *
	 * @since    1.0.0
	 */
	private function get_ajax_action( $nopriv = FALSE ) {

		return 'ipgb-tester-admin-ajax' . ($nopriv ? '-nopriv' : '');

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

	/**
	 * Handler of short code.
	 *
	 * @since    1.0.0
	 */
	public function process_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'type' => 0,
			'nopriv' => 'true',
		), $atts );

		switch ( $atts['type'] ) {
		  case 'admin-ajax':
			$link = "<ol>\n";
			// ajax request on post page
			foreach ( array( TRUE, FALSE ) as $nopriv ) {
				$act = $this->get_ajax_action( $nopriv );
				$url = esc_url( admin_url( 'admin-ajax.php?action=' . $act ) );
				$link .= '<li><a href="' . $url . '" title="' . $url . '">' . $url . "</a></li>\n";
			}
			$link .= "</ol>\n";
			return $link;
			break;

		  case 'follow':
		  case 'nofollow':
			$follow = 'nofollow' === $atts['type'] ? ' rel="nofollow"' : '';
			// direct request to plugin area on post page
			$home = esc_url( site_url() );
			$link = <<<EOT
<ol>
    <li><a${follow} href="${home}/wp-admin/admin-ajax.php?action=my-ajax">/wp-admin/admin-ajax-php?action=my-ajax</a>
    <li><a${follow} href="${home}/wp-admin/admin-ajax.php?action=my-ajax&file=../../../wp-config.php">/wp-admin/admin-ajax-php?action=my-ajax&file=../../../wp-config.php</a></li>
    <li><a${follow} href="${home}/wp-content/plugins/ip-geo-block/samples.php">/wp-content/plugins/ip-geo-block/samples.php</a></li>
    <li><a${follow} href="${home}/wp-content/plugins/ip-geo-block/samples.php?file=../../../wp-config.php">/wp-content/plugins/ip-geo-block/samples.php?file=../../../wp-config.php</a></li>
    <li><a${follow} href="${home}/wp-content/plugins/ip-geo-block/samples.php?wp-load=1">/wp-content/plugins/ip-geo-block/samples.php?wp-load=1</a></li>
    <li><a${follow} href="${home}/wp-content/plugins/ip-geo-block/samples.php?wp-load=1&file=../../../wp-config.php">/wp-content/plugins/ip-geo-block/samples.php?wp-load=1&file=../../../wp-config.php</a></li>
</ol>
EOT;
			return $link;
			break;

		  default:
			return NULL;
		}
	}

}