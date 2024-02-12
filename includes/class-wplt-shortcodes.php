<?php
/**
 * Define our plugin shortcodes.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Our config class.
 */
class Wplt_Shortcodes {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register our shortcodes.
		add_action( 'init', [ $this, 'register_shortcodes' ] );
	}

	/**
	 * Register our shortcodes.
	 */
	public function register_shortcodes() {
		// Get our pages array from config.
		$pages_array = Wplt_Config::get_pages();
		if ( $pages_array && is_array( $pages_array ) ) {
			foreach ( $pages_array as $slug => $page_array ) {
				// Define shortcode and callback names.
				$shortcode_name = 'wplt-' . $slug;
				$callback_name  = 'shortcode_callback_' . str_replace( '-', '_', $slug ); // Remove "-" signs from $slug, so that our callback would be valid.
				// Check if the method exists in this class.
				if ( method_exists( $this, $callback_name ) ) {
					add_shortcode( $shortcode_name, [ $this, $callback_name ] );
				}
			}
		}
	}

	/**
	 * Callback for the account shortcode.
	 */
	public function shortcode_callback_account() {
		ob_start();
		wplt_get_template_part( 'account' );
		return ob_get_clean();
	}

	/**
	 * Callback for the Lost Password shortcode.
	 */
	public function shortcode_callback_lost_password() {
		ob_start();
		wplt_get_template_part( 'lost-password' );
		return ob_get_clean();
	}

	/**
	 * Callback for the login shortcode.
	 */
	public function shortcode_callback_login() {
		ob_start();
		wplt_get_template_part( 'login' );
		return ob_get_clean();
	}

	/**
	 * Callback for the register shortcode.
	 */
	public function shortcode_callback_register() {
		ob_start();
		wplt_get_template_part( 'register' );
		return ob_get_clean();
	}
}
new Wplt_Shortcodes();
