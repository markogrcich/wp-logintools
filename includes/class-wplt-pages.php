<?php
/**
 * Define everything related to plugin created pages.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * Our pages class.
 */
class Wplt_Pages {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Add post states to plugin created pages in wp-admin area.
		add_filter( 'display_post_states', [ $this, 'add_post_state' ], 10, 2 );
		// Prevent deletion of the plugin created pages.
		add_action( 'before_delete_post', [ $this, 'prevent_page_deletion' ] );
		add_action( 'wp_trash_post', [ $this, 'prevent_page_deletion' ] );
		// Make sure our pages always remain "published" ensuring they remain accessible to visitors.
		add_filter( 'wp_insert_post_data', [ $this, 'force_published_status' ], 10, 2 );
	}

	/**
	 * Display our custom post states in wp-admin area.
	 *
	 * @param array  $post_states Array with all post states.
	 * @param object $post WP_Post object.
	 */
	public function add_post_state( $post_states, $post ) {
		// Get all pages from config.
		$pages_array = Wplt_Config::get_pages();
		if ( $pages_array && is_array( $pages_array ) ) {
			foreach ( $pages_array as $slug => $page_array ) {
				$page_name = ( isset( $page_array['title'] ) ) ? $page_array['title'] : '';
				if ( ! $page_name ) {
					/**
					 * If page name is not present, assign a default post name.
					 */
					$page_name = apply_filters( 'wptl_pages_default_name', __( 'Login Tools', 'wplt' ) );
				}
				$page_id = (int) get_option( 'wplt-page-' . $slug );
				if ( $post->ID === $page_id ) {
					$post_states[] = $page_name . ' ' . __( 'page', 'wplt' );
				}
			}
		}
		return $post_states;
	}

	/**
	 * Prevent deletion of the plugin created pages.
	 *
	 * @param int $post_id Id of the post being deleted.
	 */
	public function prevent_page_deletion( $post_id ) {
		// Get all pages from config.
		$pages_array = Wplt_Config::get_pages();
		if ( $pages_array && is_array( $pages_array ) ) {
			foreach ( $pages_array as $slug => $page_array ) {
				// Check if our page is protected from modifications or not.
				$protected = ( isset( $page_array['protected'] ) ) ? $page_array['protected'] : false;
				$page_id   = (int) get_option( 'wplt-page-' . $slug );
				// Check if the post being deleted is the protected page.
				if ( $protected && $post_id === $page_id ) {
					wp_die( esc_html( __( "This page can't be deleted, because it's a part of the Login Tools plugin.", 'wplt' ) ) );
				}
			}
		}
	}

	/**
	 * Make sure our pages always remain "published" ensuring they remain accessible to visitors.
	 *
	 * @param array $data An array of slashed, sanitized, and processed post data.
	 * @param array $postarr An array of sanitized (and slashed) but otherwise unmodified post data.
	 */
	public function force_published_status( $data, $postarr ) {
		// Get all pages from config.
		$pages_array = Wplt_Config::get_pages();
		if ( $pages_array && is_array( $pages_array ) ) {
			foreach ( $pages_array as $slug => $page_array ) {
				// Check if our page is protected from modifications or not.
				$protected = ( isset( $page_array['protected'] ) ) ? $page_array['protected'] : false;
				$page_id   = (int) get_option( 'wplt-page-' . $slug );
				// Check if the current post ID is in the array of protected page IDs.
				if ( $protected && $postarr['ID'] === $page_id ) {
					// Force post status to be 'publish'.
					$data['post_status'] = 'publish';
				}
			}
		}
		return $data;
	}
}
new Wplt_Pages();
