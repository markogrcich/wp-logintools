<?php
/**
 * Define plugin redirects.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Our redirect class.
 */
class Wplt_Redirects {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Redirect from login form to our custom login page.
		add_action( 'login_form_login', [ $this, 'redirect_from_login_page' ] );
		// Redirect from login form to our custom login page, if there were any authenticate errors.
		add_filter( 'authenticate', [ $this, 'redirect_after_authenticate' ], 101, 1 );
	}

	/**
	 * Redirect from login form to our custom login page.
	 */
	public function redirect_from_login_page() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			// Check if login page is present.
			$login_page = wplt_get_page_url( 'login' );

			// Bail early if login page is not present.
			if ( ! $login_page ) {
				exit;
			}

			// Set redirect url.
			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? sanitize_url( wp_unslash( $_REQUEST['redirect_to'] ) ) : null; // phpcs:ignore

			// If the user is logged in, use different redirect logic.
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user( $redirect_to );
				exit;
			}

			// The rest of the users are logged in to our login page.
			if ( ! empty( $redirect_to ) ) {
				// Add query args to redirect.
				$login_page = add_query_arg( 'redirect_to', $redirect_to, $login_page );
			}
			// Redirect and exit.
			wp_safe_redirect( $login_page );
			exit;
		}
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to An optional redirect_to URL for admin users.
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
		// Define current user.
		$user = wp_get_current_user();

		// Default fallback redirect is our account page.
		$fallback_redirect = ( wplt_get_page_url( 'account' ) ) ? wplt_get_page_url( 'account' ) : home_url();

		// For logged in users, fallback is admin url.
		if ( user_can( $user, 'manage_options' ) ) {
			$fallback_redirect = admin_url();
		}

		// If redirect address is defined, redirect a user to that url, if not, use fallback.
		wp_safe_redirect( wp_validate_redirect( $redirect_to, $fallback_redirect ) );
		exit;
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error $user The signed in user, or the errors that have occurred during login.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	public function redirect_after_authenticate( $user ) {
		// Check if login page is present.
		$login_page = wplt_get_page_url( 'login' );

		// Bail early if login page is not present.
		if ( ! $login_page ) {
			return $user;
		}

		// Check if the earlier authenticate filter functions have found errors.
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			if ( is_wp_error( $user ) ) {
				$error_codes = rawurlencode( base64_encode( wp_json_encode( $user ) ) ); // phpcs:ignore
				$login_url   = add_query_arg( 'wplt_error', $error_codes, $login_page );
				wp_safe_redirect( $login_url );
				exit;
			}
		}

		// Return $user object.
		return $user;
	}
}
new Wplt_Redirects();
