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

/**
 * WordPress wp_login_form() defaults.
 */
$defaults = [
	// Default 'redirect' value takes the user back to the request URI.
	'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // phpcs:ignore
	'form_id'        => 'loginform',
	'label_username' => __( 'Username or Email Address' ),
	'label_password' => __( 'Password' ),
	'label_remember' => __( 'Remember Me' ),
	'label_log_in'   => __( 'Log In' ),
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'remember'       => true,
	'value_username' => '',
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
		'containers'             => true,
		'placeholder_username'   => __( 'Username or Email', 'wplt' ),
		'placeholder_password'   => __( '••••••••', 'wplt' ),
		'class_username'         => 'input',
		'class_username_label'   => '',
		'class_password'         => 'input',
		'class_password_label'   => '',
		'class_rememberme'       => 'input',
		'class_rememberme_label' => '',
		'class_button'           => 'button button-primary',
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
 * Markup to be printed before the login form.
 *
 * @param array $args Array of login form arguments.
 */
do_action( 'wplt_login_form_before', $args );
?>
	<form name="<?php echo esc_attr( $args['form_id'] ); ?>" id="<?php echo esc_attr( $args['form_id'] ); ?>" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
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
				<input type="text" name="log" id="<?php echo esc_attr( $args['id_username'] ); ?>" autocomplete="username" class="<?php echo esc_attr( $args['class_username'] ); ?>" value="<?php echo esc_attr( $args['value_username'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder_username'] ); ?>" size="20" />
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
				<input type="password" name="pwd" id="<?php echo esc_attr( $args['id_password'] ); ?>" autocomplete="current-password" spellcheck="false" class="<?php echo esc_attr( $args['class_password'] ); ?>" value="" placeholder="<?php echo esc_attr( $args['placeholder_password'] ); ?>" size="20" />
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
