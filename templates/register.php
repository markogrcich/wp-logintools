<?php
/**
 * Provides a simple registration form for use anywhere within WordPress.
 * Uses simmilar logic as wp_login_form() function, but with additional modifications.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/templates
 */

// Get page id, to be used later on.
$page_id = get_the_ID();

/**
 * If user is already logged in or registration is disabled, redirect to home page.
 */
if ( ( is_user_logged_in() && ! is_admin() ) || ! get_option( 'users_can_register' ) ) {
	wp_safe_redirect(
		apply_filters(
			'wplt_logged_in_redirect',
			home_url( '/' ),
			$page_id
		)
	);
}

/**
 * WordPress wp_login_form() defaults.
 */
$defaults = [
	// Default 'redirect' value takes the user back to the request URI.
	'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // phpcs:ignore
	'form_id'        => 'registerform',
	'label_username' => __( 'Username', 'wplt' ),
	'label_password' => __( 'Password', 'wplt' ),
	'label_remember' => __( 'Remember Me', 'wplt' ),
	'label_log_in'   => __( 'Register', 'wplt' ),
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'remember'       => true,
	'value_username' => ( isset( $_GET['user_login'] ) && is_string( $_GET['user_login'] ) ) ? wp_unslash( $_GET['user_login'] ) : '', // phpcs:ignore.
	// Set 'value_remember' to true to default the "Remember me" checkbox to checked.
	'value_remember' => false,
];

/**
 * Add WPLT defaults, and filter it.
 *
 * @param array $wplt_defaults An array of WPLT default login form arguments.
 */
$wplt_defaults = apply_filters(
	'wplt_login_form_defaults',
	[
		'containers'                    => true,
		'placeholder_username'          => __( 'Username', 'wplt' ),
		'label_email'                   => __( 'Email', 'wplt' ),
		'id_email'                      => __( 'Email', 'wplt' ),
		'value_email'                   => '',
		'class_email'                   => 'input',
		'class_email_label'             => '',
		'placeholder_email'             => __( 'Your email address', 'wplt' ),
		'placeholder_password'          => __( '••••••••', 'wplt' ),
		'class_form'                    => 'wp-login-form',
		'class_username'                => 'input',
		'class_username_label'          => '',
		'class_password'                => 'input',
		'class_password_label'          => '',
		'class_rememberme'              => 'input',
		'class_rememberme_label'        => '',
		'label_confirmation'            => __( 'Registration confirmation will be emailed to you.', 'wplt' ),
		'class_button'                  => 'button button-primary',
		'links_nav'                     => true,
		'class_nav_links_nav'           => 'wp-login-links',
		'class_nav_links_register'      => 'wp-login-register',
		'class_nav_links_lost_password' => 'wp-login-lost-password',
		'class_nav_links_login'         => 'wp-login-log-in',
		'label_nav_links_login'         => __( 'Log In', 'wplt' ),
	]
);

/**
 * Filters the default login form output arguments.
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
 * @param int $page_id Page ID.
 */
do_action( 'wplt_notifications', $page_id );
?>

<?php
/**
 * Markup to be printed before the register form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_register_form_before', $args );
?>
<form name="<?php echo esc_attr( $args['form_id'] ); ?>" id="<?php echo esc_attr( $args['form_id'] ); ?>" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post"<?php echo ( $args['class_form'] ) ? 'class="' . esc_attr( $args['class_form'] ) . '"' : ''; ?> >
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
		<input type="text" name="user_login" id="<?php echo esc_attr( $args['id_username'] ); ?>" autocomplete="username" required class="<?php echo esc_attr( $args['class_username'] ); ?>" value="<?php echo esc_attr( $args['value_username'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder_username'] ); ?>" autocapitalize="off" size="20" />
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
	 * Markup to be printed before the email input.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_email_before', $args );
	?>
	<?php if ( $args['containers'] ) : ?>
		<p class="login-email">
	<?php endif; ?>
		<?php if ( $args['label_email'] ) : ?>
			<label for="<?php echo esc_attr( $args['id_email'] ); ?>"<?php echo ( $args['class_email_label'] ) ? 'class="' . esc_attr( $args['class_email_label'] ) . '"' : ''; ?>>
				<?php echo esc_html( $args['label_email'] ); ?>
			</label>
		<?php endif; ?>
		<input type="email" name="user_email" id="<?php echo esc_attr( $args['id_email'] ); ?>" autocomplete="email" required class="<?php echo esc_attr( $args['class_username'] ); ?>" value="<?php echo esc_attr( $args['value_email'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder_email'] ); ?>" size="25" />
	<?php if ( $args['containers'] ) : ?>
		</p>
	<?php endif; ?>
	<?php
	/**
	 * Markup to be printed after the email input.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_email_after', $args );
	?>

	<?php
	/**
	 * Fires following the 'Email' field in the user registration form.
	 */
	do_action( 'register_form' );
	?>

	<?php if ( $args['label_confirmation'] ) : ?>
		<?php
		/**
		 * Markup to be printed before the confirmation message.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_confirmation_before', $args );
		?>
		<?php if ( $args['containers'] ) : ?>
			<p id="reg_passmail">
		<?php endif; ?>
				<?php echo wp_kses_post( $args['label_confirmation'] ); ?>
		<?php if ( $args['containers'] ) : ?>
			</p>
		<?php endif; ?>
		<?php
		/**
		 * Markup to be printed after the confirmation message.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_confirmation_after', $args );
		?>
	<?php endif; ?>

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
		<?php wp_nonce_field( 'wplt_register', 'security', false ); ?>
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
 * Markup to be printed after the register form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_register_form_after', $args );

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
		<a class="<?php echo esc_attr( $args['class_nav_links_login'] ); ?>" href="<?php echo esc_url( wp_login_url() ); ?>"><?php echo wp_kses_post( $args['label_nav_links_login'] ); ?></a>
		<?php
			echo wp_kses_post(
				apply_filters(
					'lost_password_html_link', // This filter is documented in wp-login.php.
					sprintf( '<a class="%1s" href="%2s">%3s</a>', esc_attr( $args['class_nav_links_lost_password'] ), esc_url( wp_lostpassword_url() ), __( 'Lost your password?' ) )
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
