<?php
/**
 * Provides a simple login form for use anywhere within WordPress.
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
	'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // phpcs:ignore
	'form_id'        => 'loginform',
	'label_username' => __( 'Username or Email Address', 'wplt' ),
	'label_password' => __( 'Password', 'wplt' ),
	'label_remember' => __( 'Remember Me', 'wplt' ),
	'label_log_in'   => __( 'Log In', 'wplt' ),
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'remember'       => true,
	'value_username' => ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) ? wp_unslash( $_POST['user_login'] ) : '', // phpcs:ignore.
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
		'placeholder_username'          => __( 'Username or Email', 'wplt' ),
		'placeholder_password'          => __( '••••••••', 'wplt' ),
		'class_form'                    => 'wp-login-form',
		'class_username'                => 'input',
		'class_username_label'          => '',
		'class_password'                => 'input',
		'class_password_label'          => '',
		'class_rememberme'              => 'input',
		'class_rememberme_label'        => '',
		'class_button'                  => 'button button-primary',
		'links_nav'                     => true,
		'class_nav_links_nav'           => 'wp-login-links',
		'class_nav_links_register'      => 'wp-login-register',
		'class_nav_links_lost_password' => 'wp-login-lost-password',
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
 * Filters content to display at the top of the login form.
 *
 * The filter evaluates just following the opening form tag element.
 *
 * @param string $content Content to display. Default empty.
 * @param array  $args    Array of login form arguments.
 */
$login_form_top = apply_filters( 'login_form_top', '', $args );

/**
 * Filters content to display in the middle of the login form.
 *
 * The filter evaluates just following the location where the 'login-password'
 * field is displayed.
 *
 * @param string $content Content to display. Default empty.
 * @param array  $args    Array of login form arguments.
 */
$login_form_middle = apply_filters( 'login_form_middle', '', $args );

/**
 * Filters content to display at the bottom of the login form.
 *
 * The filter evaluates just preceding the closing form tag element.
 *
 * @param string $content Content to display. Default empty.
 * @param array  $args    Array of login form arguments.
 */
$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );

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
 * Markup to be printed before the login form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_login_form_before', $args );
?>
<form name="<?php echo esc_attr( $args['form_id'] ); ?>" id="<?php echo esc_attr( $args['form_id'] ); ?>" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post"<?php echo ( $args['class_form'] ) ? 'class="' . esc_attr( $args['class_form'] ) . '"' : ''; ?>>
	<?php echo $login_form_top; // phpcs:ignore ?>
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
			<input type="text" name="log" id="<?php echo esc_attr( $args['id_username'] ); ?>" autocomplete="username" required class="<?php echo esc_attr( $args['class_username'] ); ?>" value="<?php echo esc_attr( $args['value_username'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder_username'] ); ?>" size="20" />
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
		 * Markup to be printed before the password input.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_password_before', $args );
		?>
		<?php if ( $args['containers'] ) : ?>
			<p class="login-password">
		<?php endif; ?>
			<?php if ( $args['label_password'] ) : ?>
				<label for="<?php echo esc_attr( $args['id_password'] ); ?>"<?php echo ( $args['class_password_label'] ) ? 'class="' . esc_attr( $args['class_password_label'] ) . '"' : ''; ?>>
					<?php echo esc_html( $args['label_password'] ); ?>
				</label>
			<?php endif; ?>
			<input type="password" name="pwd" id="<?php echo esc_attr( $args['id_password'] ); ?>" autocomplete="current-password" spellcheck="false" required class="<?php echo esc_attr( $args['class_password'] ); ?>" value="" placeholder="<?php echo esc_attr( $args['placeholder_password'] ); ?>" size="20" />
		<?php if ( $args['containers'] ) : ?>
			</p>
		<?php endif; ?>
		<?php
		/**
		 * Markup to be printed after the password input.
		 *
		 * @param array $args Array of login form arguments.
		 */
		do_action( 'wplt_login_password_after', $args );
		?>

		<?php echo $login_form_middle; // phpcs:ignore ?>
		<?php if ( $args['remember'] ) : ?>
			<?php
			/**
			 * Markup to be printed before the "remember me" input.
			 *
			 * @param array $args Array of login form arguments.
			 */
			do_action( 'wplt_login_rembember_before', $args );
			?>
			<?php if ( $args['containers'] ) : ?>
				<p class="login-remember"><label <?php echo ( $args['class_rememberme_label'] ) ? 'class="' . esc_attr( $args['class_rememberme_label'] ) . '"' : ''; ?>>
			<?php endif; ?>
				<input name="rememberme" type="checkbox" id="<?php echo esc_attr( $args['id_remember'] ); ?>" value="forever"<?php echo ( $args['value_remember'] ? ' checked="checked"' : '' ); ?> <?php echo ( $args['class_rememberme'] ) ? 'class="' . esc_attr( $args['class_rememberme'] ) . '"' : ''; ?>/> <?php echo ( $args['label_remember'] ) ? esc_html( $args['label_remember'] ) : ''; ?>
			<?php if ( $args['containers'] ) : ?>
				</label></p>
			<?php endif; ?>
			<?php
			/**
			 * Markup to be printed after the "remember me" input.
			 *
			 * @param array $args Array of login form arguments.
			 */
			do_action( 'wplt_login_rembember_after', $args );
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

	<?php echo $login_form_bottom; // phpcs:ignore ?>
</form>
<?php
/**
 * Markup to be printed after the login form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_login_form_after', $args );

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
