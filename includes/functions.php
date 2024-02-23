<?php
/**
 * Plugin functions.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

if ( ! function_exists( 'wplt_is_page' ) ) {
	/**
	 * Check if we are on any of our login pages.
	 *
	 * @param string $page_type Type of the page you require properties of. Default values are "login", "register", "lost-password" or "account".
	 * @param mixed  $page_id Optional page id. Can be a intiger or a string. Will use get_the_ID() if not provided.
	 */
	function wplt_is_page( $page_type = '', $page_id = null ) {
		// Get page id, if not provided.
		if ( ! $page_id ) {
			$page_id = get_the_ID();
		}
		// Skip if the page is not defined.
		$allowed_pages_array = Wplt_Config::get_pages();
		if ( ! array_key_exists( $page_type, $allowed_pages_array ) ) {
			return false;
		}
		// Extract page id from options.
		$option_slug    = $page_type;
		$stored_page_id = (int) get_option( 'wplt-page-' . $option_slug );
		if ( ! $stored_page_id ) {
			return false;
		}
		if ( intval( $page_id ) === $stored_page_id ) {
			return true;
		}
		return false;
	}
}
if ( ! function_exists( 'wplt_get_page_url' ) ) {
	/**
	 * Get our login pages url's.
	 *
	 * @param string $page_type Type of the page you require properties of. Default values are "login", "register", "lost-password" or "account".
	 */
	function wplt_get_page_url( $page_type = '' ) {
		// Skip if the page is not defined.
		$allowed_pages_array = Wplt_Config::get_pages();
		if ( ! array_key_exists( $page_type, $allowed_pages_array ) ) {
			return false;
		}
		// Extract page id from options.
		$option_slug    = $page_type;
		$stored_page_id = (int) get_option( 'wplt-page-' . $option_slug );
		if ( ! $stored_page_id ) {
			return false;
		}
		return get_permalink( $stored_page_id );
	}
}
if ( ! function_exists( 'wplt_get_page_id' ) ) {
	/**
	 * Get our login pages ID's.
	 *
	 * @param string $page_type Type of the page you require properties of. Default values are "login", "register", "lost-password" or "account".
	 */
	function wplt_get_page_id( $page_type = '' ) {
		// Skip if the page is not defined.
		$allowed_pages_array = Wplt_Config::get_pages();
		if ( ! array_key_exists( $page_type, $allowed_pages_array ) ) {
			return false;
		}
		// Extract page id from options.
		$option_slug = $page_type;
		return (int) get_option( 'wplt-page-' . $option_slug );
	}
}

if ( ! function_exists( 'wplt_get_template_part' ) ) {
	/**
	 * Loads a template part into the current page with support for passing arguments.
	 *
	 * This function attempts to load a specified template part from the currently active theme's directory.
	 * It first looks for the template within a subdirectory named after the plugin folder in the active theme's root,
	 * allowing for theme overrides of plugin templates. If the template is not found in the theme,
	 * it falls back to the plugin's own 'templates' directory.
	 *
	 * The function supports specifying a slug and an optional name to define the template part,
	 * mimicking the WordPress `get_template_part()` function. Additionally, it allows for passing an associative
	 * array of arguments (`$args`) to the template, making it accessible as individual variables within the template scope.
	 *
	 * @param string      $slug The slug name for the generic template.
	 * @param string|null $name Optional. The name of the specialized template.
	 * @param array       $args Optional. An associative array of variables to be extracted and made available in the template's scope.
	 *
	 * @example Usage:
	 *          wplt_get_template_part( 'login', 'form', array( 'message' => 'Please log in.' ) );
	 *          This will look for 'login-form.php' first in the active theme's 'wp-logintools' subdirectory,
	 *          then in the plugin's 'templates' directory, and pass the 'message' variable to the template.
	 *
	 * Note: To ensure safe variable names in the `$args` array, avoid using names that might conflict with
	 * global variables or WordPress default variables.
	 */
	function wplt_get_template_part( $slug, $name = null, $args = array() ) {
		// Ensure $args is an array.
		if ( ! is_array( $args ) ) {
			$args = array();
		}

		// Set global args variable to make it accessible inside the template.
		if ( $args ) {
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			extract( $args );
		}

		// Generate the file path suffix.
		$template_suffix = $slug . ( ( $name ) ? "-{$name}" : '' ) . '.php';

		// Dynamically get the plugin directory name.
		$plugin_directory = basename( dirname( __DIR__, 1 ) );

		// Define the path in the theme directory.
		$theme_template_path = get_stylesheet_directory() . '/' . $plugin_directory . '/' . $template_suffix;

		// Define the default path in the plugin directory
		// Adjust the plugin_dir_path as needed to correctly point to your plugin's root directory.
		$plugin_template_path = WP_PLUGIN_DIR . '/' . $plugin_directory . '/templates/' . $template_suffix;

		// Check if the template exists in the theme.
		if ( file_exists( $theme_template_path ) ) {
			require $theme_template_path;
		} elseif ( file_exists( $plugin_template_path ) ) {
			// If not found in the theme, load it from the plugin.
			require $plugin_template_path;
		}
	}
}

/**
 * Encode WP Error for usage in query vars.
 *
 * @param object $error WP_Error object.
 */
function wplt_encode_error( $error = null ) {
	// Bail early.
	if ( ! $error || ! is_wp_error( $error ) ) {
		return false;
	}
	return rawurlencode( base64_encode( wp_json_encode( $error ) ) ); // phpcs:ignore
}

/**
 * Decode WP Error from a query var.
 *
 * @param string $error String representing coded error.
 */
function wplt_decode_error( $error = null ) {
	// Bail early.
	if ( ! $error ) {
		return false;
	}
	$error_decoded = json_decode( base64_decode( rawurldecode( $error ) ) ); // phpcs:ignore
	if ( $error_decoded && is_object( $error_decoded ) ) {
		// Count data.
		$i = 0;
		// Start by creating an empty WP_Error object.
		$wp_error = new WP_Error();
		// Check if there are any errors to process.
		if ( property_exists( $error_decoded, 'errors' ) && ! empty( $error_decoded->errors ) ) {
			foreach ( $error_decoded->errors as $code => $messages ) {
				foreach ( $messages as $message ) {
					// Add each error code and message to the WP_Error object.
					$wp_error->add( $code, $message );
					// Data was added.
					++$i;
				}
			}
		}

		// If object contains error data.
		if ( property_exists( $error_decoded, 'error_data' ) && ! empty( $error_decoded->error_data ) ) {
			foreach ( $error_decoded->error_data as $code => $data ) {
				// Here you would associate your error data with an existing error code.
				// This assumes you have a mechanism to match error_data keys to your error codes.
				$wp_error->add_data( $data, $code );
				// Data was added.
				++$i;
			}
		}
		// If data was added, return an error.
		if ( $i ) {
			return $wp_error;
		}
	}
	return false;
}

if ( ! function_exists( 'wplt_show_message' ) ) {
	/**
	 * Displays the given error message.
	 *
	 * @since 2.1.0
	 *
	 * @param string|WP_Error $message Sting or an error representing a message to show.
	 */
	function wplt_show_message( $message ) {
		if ( is_wp_error( $message ) ) {
			if ( $message->get_error_data() && is_string( $message->get_error_data() ) ) {
				$message = $message->get_error_message() . ': ' . $message->get_error_data();
			} else {
				$message = $message->get_error_message();
			}
		}
		echo "<p>$message</p>\n"; // phpcs:ignore
	}
}
