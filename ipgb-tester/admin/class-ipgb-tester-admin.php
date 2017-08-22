<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/tokkonopapa
 * @since      1.0.0
 *
 * @package    IPGB_Tester
 * @subpackage ipgb-tester/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    IPGB_Tester
 * @subpackage ipgb-tester/admin
 * @author     tokkonopapa <tokkonopapa@yahoo.com>
 */
class IPGB_Tester_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'setup_admin_page' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ipgb-tester-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ipgb-tester-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript and Stylesheets only for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_admin_assets() {
	}

	/**
	 * Ajax action name on public facing pages.
	 *
	 * @since    1.0.0
	 */
	private function get_post_action() {

		return 'ipgb-tester-admin-post';

	}

	/**
	 * Initialize for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {

		$action = $this->get_post_action();
		add_filter( 'admin_post_'        . $action, array( $this, 'admin_post' ) );
		add_filter( 'admin_post_nopriv_' . $action, array( $this, 'admin_post' ) );

	}

	/**
	 * Handler of admin post.
	 *
	 * @since    1.0.0
	 */
	public function admin_post() {

		wp_send_json( NULL, 200 ); // @since 3.5.0

	}

	/**
	 * Register the admin menu.
	 *
	 * @since    1.0.0
	 */
	public function setup_admin_page() {

		// Add a settings page for this plugin to the Settings menu.
		$hook = add_options_page(
			__( 'IP Geo Tester', 'ip-geo-tester' ),
			__( 'IP Geo Tester', 'ip-geo-tester' ),
			'manage_options',
			'ip-geo-tester',
			array( $this, 'render_admin_page' )
		);
 
		// If successful, load admin assets only on this page.
		if ( ! empty( $hook ) ) {
			add_action( "load-$hook", array( $this, 'enqueue_admin_assets' ) );
		}

	}

	/**
	 * Render the field items
	 *
	 * @since    1.0.0
	 */
	public function render_field( $args ) {

		switch ( $args['type'] ) {
		  case 'html':
			echo "\n", $args['value'], "\n"; // must be sanitized at caller
			break;
		}

	}

	/**
	 * Render the field items
	 *
	 * @since    1.0.0
	 */
	public function sanitize_options( $options ) {

        return $options;

	}

	/**
	 * Register items on the admin page.
	 *
	 * @since    1.0.0
	 */
	private function register_admin_items() {

		register_setting(
			$option_slug = 'ip-geo-tester',
			$option_name = 'ip-geo-tester-options',
			array( $this, 'sanitize_options' )
		);

		$section = $option_slug . '-section1';

		add_settings_section(
			$section,
			__( 'Anchor tag test', 'ip-geo-tester' ),
			NULL,
			$option_slug
		);

		$field = 'test1';
		add_settings_field(
			$option_name . '-' . $field,
			'Test 1',
			array( $this, 'render_field' ),
			$option_slug,
			$section,
			array(
				'type'   => 'html',
				'option' => $option_name,
				'field'  => $field,
				'value'  => '<a class="button button-secondary" onclick="alert(1)">Link with onclick without href</a>',
			)
		);

		$field = 'test2';
		add_settings_field(
			$option_name . '-' . $field,
			'Test 2',
			array( $this, 'render_field' ),
			$option_slug,
			$section,
			array(
				'type'   => 'html',
				'option' => $option_name,
				'field'  => $field,
				'value'  => '<a href="//example.com/" class="button button-secondary" onclick="wondow.location = this.href">Link with onclick to external</a>',
			)
		);

	}

	/**
	 * Render the option page.
	 *
	 * @since    1.0.0
	 */
	public function render_admin_page() {

		$this->register_admin_items();
?>
<div class="wrap">
	<h2>IP Geo Tester</h2>
	<form method="post" action="<?php echo 'option-general.php'; ?>">
<?php
		settings_fields( 'ip-geo-tester' );
		do_settings_sections( 'ip-geo-tester' );
//		submit_button(); // @since 3.1
?>
	</form>
</div>
<?php

	}
    
}
