<?php
/**
 * Define plugin configuration
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
class Wplt_Config {
	/**
	 * Constructor.
	 */
	public function __construct() {}

	/**
	 * Define what pages does plugin need to create, in order for it to work properly.
	 */
	public static function get_pages() {
		/**
		 * Default pages to be created.
		 */
		$defaults = [
			'login'          => [
				'title'     => __( 'Login', 'wplt' ), // Page title.
				'content'   => '[wplt-login]', // Page content. Shortcode must be in a format of [wptl- ' . $array_key . ' ].
				'protected' => true, // Is the page protected from moving to trash, or changing the post status.
			],
			'register'       => [
				'title'     => __( 'Register', 'wplt' ),
				'content'   => '[wplt-register]',
				'protected' => true,
			],
			'lost-password'  => [
				'title'     => __( 'Lost Password', 'wplt' ),
				'content'   => '[wplt-lost-password]',
				'protected' => true,
			],
			'reset-password' => [
				'title'     => __( 'Reset Password', 'wplt' ),
				'content'   => '[wplt-reset-password]',
				'protected' => true,
			],
			'account'        => [
				'title'     => __( 'My Account', 'wplt' ),
				'content'   => '[wplt-account]',
				'protected' => true,
			],
		];
		/**
		 * Return merged pages array, so this area can be extended by plugins and themes.
		 */
		return array_merge( $defaults, apply_filters( 'wptl_config_additional_pages', [] ) );
	}
}
new Wplt_Config();
