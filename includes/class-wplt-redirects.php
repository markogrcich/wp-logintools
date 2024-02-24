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
		add_filter( 'authenticate', [ $this, 'redirect_after_login_authenticate' ], 101, 1 );
		// Redirects the user to our custom "Forgot your password?" page.
		add_action( 'login_form_lostpassword', [ $this, 'redirect_from_lostpassword' ] );
		// On "lost password" page submit.
		add_action( 'login_form_lostpassword', [ $this, 'lostpassword_page_submit' ] );
		// Redirect to our custom "reset-password" page.
		add_action( 'login_form_rp', [ $this, 'redirect_from_password_reset' ] );
		add_action( 'login_form_resetpass', [ $this, 'redirect_from_password_reset' ] );
		// Resets the user's password if the password reset form was submitted.
		// add_action( 'login_form_rp', array( $this, 'reset_password_page_submit' ) );
		// add_action( 'login_form_resetpass', array( $this, 'reset_password_page_submit' ) );
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
	public function redirect_after_login_authenticate( $user ) {
		// Check if login page is present.
		$login_page = wplt_get_page_url( 'login' );

		// Bail early if login page is not present.
		if ( ! $login_page ) {
			return $user;
		}

		// Check if the earlier authenticate filter functions have found errors.
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			if ( is_wp_error( $user ) ) {
				$error_codes = wplt_encode_message( $user );
				$login_url   = add_query_arg( 'wplt_query', $error_codes, $login_page );
				wp_safe_redirect( $login_url );
				exit;
			}
		}

		// Return $user object.
		return $user;
	}

	/**
	 * Redirects the user to our custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_from_lostpassword() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			// Check if lost password page page is present.
			$password_page = wplt_get_page_url( 'lost-password' );

			// Bail early if login page is not present.
			if ( ! $password_page ) {
				exit;
			}

			// Set redirect url.
			$redirect_to = isset( $_REQUEST['redirect_to'] ) ? sanitize_url( wp_unslash( $_REQUEST['redirect_to'] ) ) : null; // phpcs:ignore

			// Redirect logged in user.
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user( $redirect_to );
				exit;
			}

			// Redirect to our custom "lost-password" page.
			wp_safe_redirect( $password_page );
			exit;
		}
	}

	/**
	 * On "lost password" page submit.
	 */
	public function lostpassword_page_submit() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			// Check if lost password page page is present.
			$password_page = wplt_get_page_url( 'lost-password' );

			// Check if login page is present.
			$login_page = wplt_get_page_url( 'login' );

			// Bail early if login or "lost password" page is not present.
			if ( ! $password_page || ! $login_page ) {
				exit;
			}

			// Check the status of "retrive password".
			$retrive_status = retrieve_password();
			if ( is_wp_error( $retrive_status ) ) {
				// Errors are found.
				$message      = wplt_encode_message( $retrive_status );
				$redirect_url = add_query_arg( 'wplt_query', $message, $password_page );
			} else {
				// Email sent, redirect to login page.
				$message      = wplt_encode_message( __( 'Check your email for a link to reset your password', 'wplt' ) );
				$redirect_url = add_query_arg( 'wplt_query', $message, $login_page );
			}

			// Redirect the user to the proper page.
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Redirect to our custom "reset-password" page.
	 */
	public function redirect_from_password_reset() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {

			// Check for $_REQUEST params.
			$reset_pass_key = ( isset( $_REQUEST['key'] ) ) ? $_REQUEST['key'] : false; // phpcs:ignore
			$user_name      = ( isset( $_REQUEST['login'] ) ) ? $_REQUEST['login'] : false; // phpcs:ignore

			// Check if reset password page page is present.
			$password_reset_page = wplt_get_page_url( 'reset-password' );

			// Check if lost password page page is present.
			$password_page = wplt_get_page_url( 'lost-password' );

			// Check if login page is present.
			$login_page = wplt_get_page_url( 'login' );

			// Bail early if login or "reset password" page is not present.
			if ( ! $password_reset_page || ! $password_page || ! $login_page ) {
				exit;
			}

			// Verify key / login combo.
			$user = check_password_reset_key( $reset_pass_key, $user_name );
			if ( ! $user || is_wp_error( $user ) ) {
				// Errors are found.
				$message      = wplt_encode_message( $user );
				$redirect_url = add_query_arg( 'wplt_query', $message, $password_page );
				exit;
			}

			// Errors are not found, redirect to our custom page.
			$redirect_url = add_query_arg(
				[
					'login' => $user_name,
					'key'   => $reset_pass_key,
				],
				$password_reset_page
			);
			wp_safe_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function reset_password_page_submit() {

	}
}
new Wplt_Redirects();
