<?php
/**
 * Define plugin notifications, like error messages, etc.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Our notifications class.
 */
class Wplt_Notifications {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Handle login page notifications.
		add_action( 'wplt_notifications', [ $this, 'login_page_notifications' ] );
	}

	/**
	 * Handle login page notifications.
	 */
	public function login_page_notifications() {
		// Get errors.
		$errors_query_var = get_query_var( 'wplt_error' );

		// Bail early if there is no errors.
		if ( ! $errors_query_var ) {
			return;
		}

		$error_message = wplt_decode_error( $errors_query_var );
		if ( ! is_wp_error( $error_message ) ) {
			return;
		}

		// Print an error message notification.
		wplt_get_template_part(
			'notification',
			null,
			[
				'type'    => 'error',
				'message' => $error_message,
			]
		);
	}
}
new Wplt_Notifications();
