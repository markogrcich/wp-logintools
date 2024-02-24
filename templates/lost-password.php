<?php
/**
 * Provides a simple lost password form for use anywhere within WordPress.
 * Uses simmilar logic as wp_login_form() function, but with additional modifications.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/templates
 */

/**
 * If user is already logged in, redirect to home page.
 */
if ( is_user_logged_in() && ! is_admin() ) {
	wp_safe_redirect(
		apply_filters(
			'wplt_logged_in_redirect',
			home_url( '/' )
		)
	);
}

/**
 * WordPress wp_login_form() defaults.
 */
$defaults = [
	// Default 'redirect' value takes the user back to the request URI.
	'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // phpcs:ignore
	'form_id'        => 'lostpasswordform',
	'label_username' => __( 'Username or Email Address' ),
	'label_log_in'   => __( 'Get New Password' ),
	'id_username'    => 'user_login',
	'id_submit'      => 'wp-submit',
	'value_username' => ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) ? wp_unslash( $_POST['user_login'] ) : '', // phpcs:ignore.
];

/**
 * Add WPLT defaults, and filter it.
 *
 * @param array $wplt_defaults An array of WPLT default login form arguments.
 */
$wplt_defaults = apply_filters(
	'wplt_lost_password_form_defaults',
	[
		'containers'                    => true,
		'placeholder_username'          => __( 'Username or Email', 'wplt' ),
		'class_form'                    => 'wp-login-form',
		'class_username'                => 'input',
		'class_username_label'          => '',
		'class_button'                  => 'button button-primary',
		'links_nav'                     => true,
		'class_nav_links_nav'           => 'wp-login-links',
		'class_nav_links_register'      => 'wp-login-register',
		'class_nav_links_lost_password' => 'wp-login-lost-password',
	]
);

/**
 * Filters the default lost password form output arguments.
 *
 * @see wp_login_form()
 *
 * @param array $defaults An array of default login form arguments.
 */
$args = wp_parse_args( $wplt_defaults, apply_filters( 'login_form_defaults', $defaults ) );

?>

<?php
/**
 * Notifications markup, like login errors and other notifications.
 *
 * @param int Page ID.
 */
do_action( 'wplt_notifications', get_the_ID() );
?>

<?php
/**
 * Markup to be printed before the lost password form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_lost_password_form_before', $args );
?>
	<form name="<?php echo esc_attr( $args['form_id'] ); ?>" id="<?php echo esc_attr( $args['form_id'] ); ?>" action="<?php echo esc_url( network_site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ); ?>" method="post"<?php echo ( $args['class_form'] ) ? 'class="' . esc_attr( $args['class_form'] ) . '"' : ''; ?>>
			<?php
			/**
			 * Markup to be printed before the username input.
			 *
			 * @param array $args Array of login form arguments.
			 */
			do_action( 'wplt_login_username_before', $args );
			?>
			<?php if ( $args['containers'] ) : ?>
				<p class="login-username">
			<?php endif; ?>
				<?php if ( $args['label_username'] ) : ?>
					<label for="<?php echo esc_attr( $args['id_username'] ); ?>"<?php echo ( $args['class_username_label'] ) ? 'class="' . esc_attr( $args['class_username_label'] ) . '"' : ''; ?>>
						<?php echo esc_html( $args['label_username'] ); ?>
					</label>
				<?php endif; ?>
				<input type="text" name="user_login" id="<?php echo esc_attr( $args['id_username'] ); ?>" autocomplete="username" required class="<?php echo esc_attr( $args['class_username'] ); ?>" value="<?php echo esc_attr( $args['value_username'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder_username'] ); ?>" size="20" />
			<?php if ( $args['containers'] ) : ?>
				</p>
			<?php endif; ?>
			<?php
			/**
			 * Markup to be printed after the username input.
			 *
			 * @param array $args Array of login form arguments.
			 */
			do_action( 'wplt_login_username_after', $args );
			?>

			<?php
			/**
			 * Fires inside the lostpassword form tags, before the hidden fields.
			 *
			 * @since 2.1.0
			 */
			do_action( 'lostpassword_form' );
			?>

			<?php
			/**
			 * Markup to be printed before the submit button.
			 *
			 * @param array $args Array of login form arguments.
			 */
			do_action( 'wplt_login_submit_before', $args );
			?>
			<?php if ( $args['containers'] ) : ?>
				<p class="login-submit">
			<?php endif; ?>
				<button type="submit" name="wp-submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" class="<?php echo esc_attr( $args['class_button'] ); ?>">
					<?php echo wp_kses_post( $args['label_log_in'] ); ?>
				</button>
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $args['redirect'] ); ?>" />
			<?php if ( $args['containers'] ) : ?>
				</p>
			<?php endif; ?>
			<?php
			/**
			 * Markup to be printed after the submit button.
			 *
			 * @param array $args Array of login form arguments.
			 */
			do_action( 'wplt_login_submit_after', $args );
			?>
	</form>
<?php
/**
 * Markup to be printed after the login form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_lost_password_form_after', $args );

/**
 * Check if we need to print login nav links after the form.
 */
if ( $args['links_nav'] ) :
	/**
	 * Markup to be printed before the login navigation.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_nav_before', $args );
	/**
	 * Filters the separator used between login form navigation links.
	 *
	 * @param string $login_link_separator The separator used between login form navigation links.
	 */
	$login_link_separator = apply_filters( 'login_link_separator', ' | ' );
	?>
	<nav class="<?php echo esc_attr( $args['class_nav_links_nav'] ); ?>">
		<?php
		if ( get_option( 'users_can_register' ) ) :
			echo wp_kses_post(
				apply_filters(
					'register', // This filter is documented in wp-includes/general-template.php.
					sprintf( '<a class="%1s" href="%2s">%3s</a>', esc_attr( $args['class_nav_links_register'] ), esc_url( wp_registration_url() ), __( 'Register', 'wplt' ) )
				)
			);
			?>
			<?php echo esc_html( $login_link_separator ); ?>
		<?php endif; ?>
		<?php
			echo wp_kses_post(
				apply_filters(
					'lost_password_html_link', // This filter is documented in wp-login.php.
					sprintf( '<a class="%1s" href="%2s">%3s</a>', esc_attr( $args['class_nav_links_lost_password'] ), esc_url( wp_login_url() ), __( 'Login' ) )
				)
			);
		?>
	</nav>
	<?php
	/**
	 * Markup to be printed after the login navigation.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_nav_after', $args );
	?>
	<?php
endif;
