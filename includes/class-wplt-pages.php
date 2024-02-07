<?php
/**
 * Define everything related to plugin created pages.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Our pages class.
 *
 * @since      1.0.0
 * @package    Wplt
 * @subpackage Wplt/includes
 * @author     Marko Grcic <markogrcich@gmail.com>
 */
class Wplt_Pages {
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
			'login'         => [
				'title'   => __( 'Login', 'wplt' ), // Page title.
				'content' => '[wplt-login]', // Page content.
				'slug'    => 'wptl-login', // Page slug.
			],
			'register'      => [
				'title'   => __( 'Register', 'wplt' ),
				'content' => '[wplt-register]',
				'slug'    => 'wptl-register',
			],
			'lost-password' => [
				'title'   => __( 'Lost Password', 'wplt' ),
				'content' => '[wplt-register]',
				'slug'    => 'wptl-lost-password',
			],
			'account'       => [
				'title'   => __( 'My Account', 'wplt' ),
				'content' => '[wplt-register]',
				'slug'    => 'wptl-account',
			],
		];
		/**
		 * Return filtered pages array, so this area can be extended by plugins and themes.
		 */
		return apply_filters( 'wptl_pages_setup_defaults', $defaults );
	}
}
new Wplt_Pages();
