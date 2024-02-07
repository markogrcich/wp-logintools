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
