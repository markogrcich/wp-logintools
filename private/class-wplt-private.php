<?php
/**
 * The private functionality of the plugin.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/public
 */

/**
 * The private functionality of the plugin.
 *
 * Defines the plugin name, version
 *
 * @package    Wplt
 * @subpackage Wplt/public
 * @author     Marko Grcic <markogrcich@gmail.com>
 */
class Wplt_Private {

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
	 * Define new query variables for plugin usage.
	 *
	 * @param array $public_query_vars The array of allowed query variable names..
	 */
	public function add_query_vars( $public_query_vars ) {
		$public_query_vars[] = 'wplt_error';
		return $public_query_vars;
	}
}
