<?php
/**
 * Because of how the actions and filters are organized in the WordPress password reset code,
 * we'll have to rewrite some of the code to be able to complete the customization
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Our function rewrites class.
 */
class Wplt_Function_Rewrites {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Resets the user's password if the password reset form was submitted.
		add_action( 'login_form_rp', [ $this, 'reset_password_page_submit' ] );
		add_action( 'login_form_resetpass', [ $this, 'reset_password_page_submit' ] );
	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 * Tries to use as much of default WordPress functionalities as possible.
	 */
	public function reset_password_page_submit() {
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			// Check if login page is present. If not, use home page instead.
			$login_page = ( wplt_get_page_url( 'login' ) ) ? wplt_get_page_url( 'login' ) : home_url( '/' );

			// Check if reset password page page is present. If not, use home page instead.
			$password_reset_page = ( wplt_get_page_url( 'reset-password' ) ) ? wplt_get_page_url( 'reset-password' ) : home_url( '' );

			// Check for $_POST params.
			$reset_pass_key = ( isset( $_POST['rp_key'] ) ) ? $_POST['rp_key'] : false; // phpcs:ignore.
			$user_name      = ( isset( $_POST['rp_user'] ) ) ? $_POST['rp_user'] : false; // phpcs:ignore
			$pass1          = ( isset( $_POST['pass1'] ) ) ? $_POST['pass1'] : false; // phpcs:ignore
			$pass2          = ( isset( $_POST['pass2'] ) ) ? $_POST['pass2'] : false; // phpcs:ignore

			// Set up a new wp_error, to use later on.
			$wp_error = new WP_Error();

			// Check password reset key.
			$user = check_password_reset_key( $reset_pass_key, $user_name );
			if ( ! $user || is_wp_error( $user ) ) {
				// Errors are found.
				$message      = wplt_encode_message( $user );
				$redirect_url = add_query_arg( 'wplt_query', $message, $login_page );
				wp_safe_redirect( $redirect_url );
				exit;
			}

			// Check if any of password fields is empty.
			if ( ! $pass1 || ! $pass2 ) {
				$wp_error->add( 'password_reset_empty_fields', __( 'Your password can not be empty.', 'wplt' ) );
				$message      = wplt_encode_message( $wp_error );
				$redirect_url = add_query_arg(
					[
						'key'        => $reset_pass_key,
						'login'      => $user_name,
						'wplt_query' => $message,
					],
					$password_reset_page
				);
				wp_safe_redirect( $redirect_url );
				exit;
			}

			// Check if password is one or all empty spaces.
			if ( ! empty( $pass1 ) ) {
				$pass1 = trim( $pass1 );
				if ( empty( $pass1 ) ) {
					$wp_error->add( 'password_reset_empty_space', __( 'The password cannot be a space or all spaces.', 'wplt' ) );
					$message      = wplt_encode_message( $wp_error );
					$redirect_url = add_query_arg(
						[
							'key'        => $reset_pass_key,
							'login'      => $user_name,
							'wplt_query' => $message,
						],
						$password_reset_page
					);
					wp_safe_redirect( $redirect_url );
					exit;
				}
			}

			if ( $pass1 ) {
				if ( $pass1 !== $pass2 ) {
					// Passwords don't match.
					$wp_error->add( 'password_reset_mismatch', __( '<strong>Error:</strong> The passwords do not match.', 'wplt' ) );
					$message      = wplt_encode_message( $wp_error );
					$redirect_url = add_query_arg(
						[
							'key'        => $reset_pass_key,
							'login'      => $user_name,
							'wplt_query' => $message,
						],
						$password_reset_page
					);
					wp_safe_redirect( $redirect_url );
					exit;
				}

				/**
				 * Fires before the password reset procedure is validated.
				 *
				 * @since 3.5.0
				 *
				 * @param WP_Error         $errors WP Error object.
				 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
				 */
				do_action( 'validate_password_reset', $wp_error, $user );

				// One last check, to see if there are any errors left.
				if ( $wp_error->has_errors() ) {
					$message      = wplt_encode_message( $wp_error );
					$redirect_url = add_query_arg(
						[
							'key'        => $reset_pass_key,
							'login'      => $user_name,
							'wplt_query' => $message,
						],
						$password_reset_page
					);
					wp_safe_redirect( $redirect_url );
					exit;
				}

				/**
				 * Every previos check has passed. We can now reset the password.
				 */
				reset_password( $user, $pass1 );
				$message      = wplt_encode_message( __( 'Your password has been reset. You can now login.', 'wplt' ) );
				$redirect_url = add_query_arg(
					[
						'user_login' => $user_name,
						'wplt_query' => $message,
					],
					$login_page
				);
				wp_safe_redirect( $redirect_url );
				exit;
			} else {
				$wp_error->add( 'password_reset_error', __( '<strong>Error:</strong> Invalid request.', 'wplt' ) );
				wp_die( $wp_error ); // phpcs:ignore
			}
			exit;
		}
	}
}
new Wplt_Function_Rewrites();
