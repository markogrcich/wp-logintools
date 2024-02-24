<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wplt
 * @subpackage Wplt/public
 * @author     Marko Grcic <markogrcich@gmail.com>
 */
class Wplt_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wplt-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wplt-public.js', [ 'jquery' ], $this->version, false );
		/**
		 * Enqueue only on "reset-password" page
		 */
		if ( wplt_is_page( 'reset-password' ) ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'utils' );
			wp_enqueue_script( 'user-profile' );
		}
	}
}
