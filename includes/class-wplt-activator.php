<?php
/**
 * Fired during plugin activation
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wplt
 * @subpackage Wplt/includes
 * @author     Marko Grcic <markogrcich@gmail.com>
 */
class Wplt_Activator {

	/**
	 * Run on plugin activation
	 *
	 * Create pages and update options on plugin activation.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Create required plugin pages.
		if ( ! class_exists( 'Wplt_Config' ) ) {
			require_once plugin_dir_path( __DIR__ ) . 'includes/class-wplt-config.php';
		}
		/**
		 * Create pages by looping trough our pages array, and using
		 * wp_insert_post() function.
		 */
		$pages_array = Wplt_Config::get_pages();
		if ( $pages_array && is_array( $pages_array ) && ! empty( array_filter( $pages_array ) ) ) {
			foreach ( $pages_array as $slug => $page_array ) {
				// Define page args.
				$args = [
					'post_type'    => 'page',
					'post_title'   => ( isset( $page_array['title'] ) ) ? $page_array['title'] : '',
					'post_content' => ( isset( $page_array['content'] ) ) ? $page_array['content'] : '',
					'post_status'  => 'publish',
				];
				// Insert the page into the database.
				$page_id = wp_insert_post( $args );
				// Add page id to site options.
				if ( $page_id && ! is_wp_error( $page_id ) ) {
					update_option( 'wplt-page-' . $slug, $page_id );
				}
			}
		}
	}
}
