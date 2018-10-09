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

		// Initialize something.
		add_action( 'init', array( $this, 'admin_init' ) );

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
	private function get_admin_action( $type ) {
		return implode( '-', array_merge( array( IPGB_TESTER_SLUG ), is_array( $type ) ? $type : array( $type ) ) );
	}

	/**
	 * Initialize for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $this->get_admin_action( array( 'admin', 'post' ) );
			add_filter( 'admin_post_'        . $action,             array( $this, 'admin_post' ) );
			add_filter( 'admin_post_'        . $action . '-nopriv', array( $this, 'admin_post' ) );
			add_filter( 'admin_post_nopriv_' . $action . '-nopriv', array( $this, 'admin_post' ) );
		}
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
	 * Add plugin meta links
	 *
	 */
	public function add_plugin_meta_links( $links, $file ) {
		if ( $file === IPGB_TESTER_BASE ) {
			$title = __( 'Contribute at GitHub', 'ip-geo-block' );
			array_push(
				$links,
				"<a href=\"http://www.ipgeoblock.com\" title=\"$title\" target=_blank>$title</a>"
			);
		}

		return $links;
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=' . IPGB_TESTER_SLUG ) ) . '">' . __( 'Settings' ) . '</a>'
			),
			$links
		);
	}

	/**
	 * Register the admin menu.
	 *
	 * @since    1.0.0
	 */
	public function setup_admin_page() {
		add_filter( 'plugin_row_meta',                         array( $this, 'add_plugin_meta_links' ), 10, 2 );
		add_filter( 'plugin_action_links_' . IPGB_TESTER_BASE, array( $this, 'add_action_links'      ), 10, 1 );

		// Add a settings page for this plugin to the Settings menu.
		$hook = add_options_page(
			__( 'IPGB Tester', 'ipgb-tester' ),
			__( 'IPGB Tester', 'ipgb-tester' ),
			'manage_options',
			IPGB_TESTER_SLUG,
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
	private function register_page_items() {
		register_setting(
			$option_slug = IPGB_TESTER_SLUG,
			$option_name = IPGB_TESTER_SLUG . '-options',
			array( $this, 'sanitize_options' )
		);

		add_settings_section(
			$section = $option_slug . '-section1',
			__( 'Test of click event', 'ip-geo-tester' ),
			NULL,
			$option_slug
		);

		$external = '//www.ipgeoblock.com/codex/referer-checker.html';
		$redirect = add_query_arg( 'page', 'ipgb-tester', admin_url( 'options-general.php' ) );
		$redirect = add_query_arg( array(
			'action' => 'logout',
			'redirect_to' => urlencode( $redirect ),
		), wp_logout_url() );

		$patterns = array(
			'Ancor tag on admin dashboard' => array(
				'<a                            class="button button-secondary" onClick="alert(1)">&lt;a onclick="&ctdot;"&gt;</a>',
				'<a href=""                    class="button button-secondary" onClick="alert(1)">&lt;a href="" onClick="&ctdot;"&gt;</a>',
				'<a href="?"                   class="button button-secondary" onClick="alert(1)">&lt;a href="?" onClick="&ctdot;"&gt;</a>',
				'<a href="#"                   class="button button-secondary" onClick="alert(1)">&lt;a href="#" onClick="&ctdot;"&gt;</a>',
				'<a href="../"                 class="button button-secondary" onClick="alert(1)">&lt;a href="../" onClick="&ctdot;"&gt;</a>',
				'<a href="javascript:alert(2)" class="button button-secondary" onClick="alert(1)">&lt;a href="javascript:&ctdot;" onClick="&ctdot;"&gt;</a>',
				'<a href="' . $external . '"   class="button button-secondary">&lt;a href="//:example.com/"&gt;</a>',
				'<a href="' . $external . '"   class="button button-secondary" onClick="return window.confirm(\'Ready?\')">&lt;a href="//:example.com/" onClick="&ctdot;"&gt;</a>',
				'<a href="' . $redirect . '"   class="button button-secondary">Logout with redirection after login</a>',
			),
			'Form tag on admin dashboard' => array(
				'<form method="GET"  action=""><input class="button button-secondary" value="&lt;form method=&quot;GET&quot;  action=&quot;&quot;&gt;" type="submit" /></form>',
				'<form method="POST" action=""><input class="button button-secondary" value="&lt;form method=&quot;POST&quot; action=&quot;&quot;&gt;" type="submit" /></form>',
				'<form method="GET">           <input class="button button-secondary" value="&lt;form method=&quot;GET&quot;&gt;"                      type="submit" /></form>',
				'<form method="POST">          <input class="button button-secondary" value="&lt;form method=&quot;POST&quot;&gt;"                     type="submit" /></form>',
			),
			'No follow with a specific action' => array(
				'<a href="?action=x" class="button button-secondary" rel="nofollow">&lt;a href="?action=&ctdot;" rel="nofollow"&gt;</a>',
			),
			'Shortcode on post page' => array(
				'<code>[ipgb-tester type="0" nopriv="true" ]</code> ... ajax request for non logged-in user',
				'<code>[ipgb-tester type="0" nopriv="false"]</code> ... ajax request for logged-in user',
				'<code>[ipgb-tester type="1"]</code> ... direct request to plugin area for logged-in user',
			),
		);

		foreach ( $patterns as $name => $pattern ) {
			$html = '<ol>';
			foreach ( $pattern as $val ) {
				$html .= '<li>' . $val . '</li>';
			}
			$html .= '</ol>';

			add_settings_field(
				$option_name . '-' . $name,
				$name,
				array( $this, 'render_field' ),
				$option_slug,
				$section,
				array(
					'type'   => 'html',
					'value'  => $html,
				)
			);
		}

	}

	/**
	 * Render the option page.
	 *
	 * @since    1.0.0
	 */
	public function render_admin_page() {
		$this->register_page_items();
?>
<div class="wrap">
	<h2>IP Geo Tester</h2>
	<!--<form method="post" action="<?php echo 'options-general.php'; ?>">-->
<?php
		settings_fields( IPGB_TESTER_SLUG );
		do_settings_sections( IPGB_TESTER_SLUG );
//		submit_button(); // @since 3.1
?>
	<!--</form>-->
</div>
<?php
	}

}