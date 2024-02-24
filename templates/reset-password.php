<?php
/**
 * Provides a simple reset password form for use anywhere within WordPress.
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
 * If user is already logged in, redirect to home page.
 */
if ( is_user_logged_in() && ! is_admin() ) {
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
	'redirect'     => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // phpcs:ignore
	'form_id'        => 'resetpassform',
	'value_username' => ( isset( $_GET['login'] ) && is_string( $_GET['login'] ) ) ? wp_unslash( $_GET['login'] ) : '', // phpcs:ignore.
	'label_log_in'   => __( 'Save Password', 'wplt' ),
	'id_submit'      => 'wp-submit',
];

/**
 * Add WPLT defaults, and filter it.
 *
 * @param array $wplt_defaults An array of WPLT default login form arguments.
 */
$wplt_defaults = apply_filters(
	'wplt_reset_password_form_defaults',
	[
		'containers'                    => true,
		'label_password1'               => __( 'New password', 'wplt' ),
		'label_password2'               => __( 'Confirm new password', 'wplt' ),
		'id_password1'                  => 'pass1',
		'id_password2'                  => 'pass2',
		'id_password_weak'              => 'pw-weak',
		'placeholder_password1'         => __( '••••••••', 'wplt' ),
		'placeholder_password2'         => __( '••••••••', 'wplt' ),
		'label_password_weak'           => __( 'Confirm use of weak password', 'wplt' ),
		'class_form'                    => 'wp-login-form',
		'class_password1'               => 'input password-input hide-if-no-js',
		'class_password2'               => 'input',
		'class_password_weak'           => 'pw-checkbox',
		'class_password_label1'         => '',
		'class_button'                  => 'button button-primary',
		'class_button_generate_pw'      => 'button wp-generate-pw hide-if-no-js skip-aria-expanded',
		'label_button_generate_pw'      => __( 'Generate Password', 'wplt' ),
		'links_nav'                     => true,
		'class_nav_links_nav'           => 'wp-login-links',
		'class_nav_links_register'      => 'wp-login-register',
		'class_nav_links_lost_password' => 'wp-login-lost-password',
		'reset_password_key'            => ( isset( $_GET['key'] ) && is_string( $_GET['key'] ) ) ? wp_unslash( $_GET['key'] ) : '', // phpcs:ignore.
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

/**
 * If login user and key are not set, redirect to home.
 */
if ( ! $args['value_username'] || ! $args['reset_password_key'] ) {
	wp_safe_redirect(
		apply_filters(
			'wplt_logged_in_redirect',
			home_url( '/' ),
			$page_id
		)
	);
}

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
 * Markup to be printed before the reset password form.
 *
 * @param array $args Array of reset password form arguments.
 */
do_action( 'wplt_reset_password_form_before', $args );
?>
<form name="<?php echo esc_attr( $args['form_id'] ); ?>" id="<?php echo esc_attr( $args['form_id'] ); ?>" action="<?php echo esc_url( network_site_url( 'wp-login.php?action=resetpass', 'login_post' ) ); ?>" method="post"<?php echo ( $args['class_form'] ) ? 'class="' . esc_attr( $args['class_form'] ) . '"' : ''; ?>>
	<?php
	/**
	 * Markup to be printed before the password input.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_password_before', $args );
	?>
	<?php if ( $args['containers'] ) : ?>
		<div class="user-pass1-wrap">
	<?php endif; ?>
		<?php if ( $args['label_password1'] ) : ?>
			<label for="<?php echo esc_attr( $args['id_password1'] ); ?>"<?php echo ( $args['class_password_label1'] ) ? 'class="' . esc_attr( $args['class_password_label1'] ) . '"' : ''; ?>>
				<?php echo esc_html( $args['label_password1'] ); ?>
			</label>
		<?php endif; ?>
		<?php if ( $args['containers'] ) : ?>
			<div class="wp-pwd">
		<?php endif; ?>
				<input type="password" name="pass1" id="<?php echo esc_attr( $args['id_password1'] ); ?>" autocomplete="new-password" data-reveal="1" data-pw="<?php echo esc_attr( wp_generate_password( 16 ) ); ?>" spellcheck="false" aria-describedby="pass-strength-result" required class="<?php echo esc_attr( $args['class_password1'] ); ?>" value="" placeholder="<?php echo esc_attr( $args['placeholder_password1'] ); ?>" size="24" />
				<div id="pass-strength-result" class="hide-if-no-js strong" aria-live="polite">Strong</div>
		<?php if ( $args['containers'] ) : ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * Markup to be printed before the weak password checkbox.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_weak_checkbox_before', $args );
		?>
		<?php if ( $args['containers'] ) : ?>
			<div class="pw-weak">
		<?php endif; ?>
			<input type="checkbox" name="pw_weak" id="<?php echo esc_attr( $args['id_password_weak'] ); ?>" class="<?php echo esc_attr( $args['class_password_weak'] ); ?>" />
			<?php if ( $args['label_password_weak'] ) : ?>
				<label for="<?php echo esc_attr( $args['id_password_weak'] ); ?>"><?php echo wp_kses_post( $args['label_password_weak'] ); ?></label>
			<?php endif; ?>
		<?php if ( $args['containers'] ) : ?>
			</div>
		<?php endif; ?>
		<?php
		/**
		 * Markup to be printed after the weak password checkbox.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_weak_checkbox_after', $args );
		?>

	<?php if ( $args['containers'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Markup to be printed after the password input.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_password_after', $args );
	?>

	<?php
	/**
	 * Markup to be printed before the password input.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_password_before', $args );
	?>
	<?php if ( $args['containers'] ) : ?>
		<div class="user-pass2-wrap">
	<?php endif; ?>
		<?php if ( $args['label_password2'] ) : ?>
			<label for="<?php echo esc_attr( $args['id_password2'] ); ?>"<?php echo ( $args['class_password_label2'] ) ? 'class="' . esc_attr( $args['class_password_label2'] ) . '"' : ''; ?>>
				<?php echo esc_html( $args['label_password2'] ); ?>
			</label>
		<?php endif; ?>
			<input type="password" name="pass2" id="<?php echo esc_attr( $args['id_password2'] ); ?>" autocomplete="new-password" spellcheck="false" class="<?php echo esc_attr( $args['class_password2'] ); ?>" value="" placeholder="<?php echo esc_attr( $args['placeholder_password2'] ); ?>" size="24" />
	<?php if ( $args['containers'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
	/**
	 * Markup to be printed after the password input.
	 *
	 * @param array $args Array of login form arguments.
	 */
	do_action( 'wplt_login_password_after', $args );
	?>

	<?php if ( wp_get_password_hint() ) : ?>
		<?php
		/**
		 * Markup to be printed before the password indicator hint.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_password_hint_before', $args );
		?>
		<?php if ( $args['containers'] ) : ?>
			<p class="description indicator-hint">
		<?php endif; ?>
		<?php echo wp_kses_post( wp_get_password_hint() ); ?>
		<?php if ( $args['containers'] ) : ?>
			</p>
		<?php endif; ?>
		<?php
		/**
		 * Markup to be printed after the password indicator hint.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_password_hint_after', $args );
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
		<p class="submit reset-pass-submit">
	<?php endif; ?>
		<button type="button" class="<?php echo esc_attr( $args['class_button_generate_pw'] ); ?>">
			<?php echo wp_kses_post( $args['label_button_generate_pw'] ); ?>
		</button>
		<button type="submit" name="wp-submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" class="<?php echo esc_attr( $args['class_button'] ); ?>">
			<?php echo wp_kses_post( $args['label_log_in'] ); ?>
		</button>
		<input type="hidden" id="user_login" value="<?php echo esc_attr( $args['value_username'] ); ?>" autocomplete="off">
		<input type="hidden" name="rp_key" value="<?php echo esc_attr( $args['reset_password_key'] ); ?>">
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
 * Markup to be printed after the reset password form.
 *
 * @param array $args Array of reset password form arguments.
 */
do_action( 'wplt_reset_password_form_after', $args );

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
