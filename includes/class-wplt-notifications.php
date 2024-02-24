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
		$message_query_var = get_query_var( 'wplt_query' );

		// Bail early if there is no errors.
		if ( ! $message_query_var ) {
			return;
		}

		$message = wplt_decode_message( $message_query_var );
		$type    = 'notice';
		if ( ! $message ) {
			return;
		}

		// If message is wp_error, change $type to "error".
		if ( is_wp_error( $message ) ) {
			$type = 'error';
		}

		// Print an error message notification.
		wplt_get_template_part(
			'notification',
			null,
			[
				'type'    => $type,
				'message' => $message,
			]
		);
	}
}
new Wplt_Notifications();
