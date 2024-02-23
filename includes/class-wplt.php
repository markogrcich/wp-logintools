<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://grcicmarko.com
 * @since      1.0.0
 *
 * @package    Wplt
 * @subpackage Wplt/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wplt
 * @subpackage Wplt/includes
 * @author     Marko Grcic <markogrcich@gmail.com>
 */
class Wplt {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wplt_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Define plugin version.
		if ( defined( 'WPLT_VERSION' ) ) {
			$this->version = WPLT_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		// Define plugin name.
		$this->plugin_name = 'wplt';

		// Define plugin directory.
		if ( ! defined( 'WPLT_DIR' ) ) {
			define( 'WPLT_DIR', plugin_dir_path( __DIR__ ) );
		}

		// Load dependencies.
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_filters();
		$this->define_public_hooks();

		// Debug purposes.
		add_action( 'wp_footer', [ $this, 'test' ] );
	}

	/**
	 * Debug purposes
	 */
	public function test() {

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Get plugin config class.
		 */
		require_once WPLT_DIR . 'includes/class-wplt-config.php';

		/**
		 * Plugin pages class.
		 */
		require_once WPLT_DIR . 'includes/class-wplt-pages.php';

		/**
		 * Plugin functions.
		 */
		require_once WPLT_DIR . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once WPLT_DIR . 'includes/class-wplt-loader.php';

		/**
		 * Manage plugin redirects
		 */
		require_once WPLT_DIR . 'includes/class-wplt-redirects.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once WPLT_DIR . 'includes/class-wplt-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once WPLT_DIR . 'admin/class-wplt-admin.php';

		/**
		 * The class responsible for defining all actions that occur in private side
		 * of the site.
		 */
		require_once WPLT_DIR . 'private/class-wplt-private.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once WPLT_DIR . 'public/class-wplt-public.php';

		/**
		 * The class responsible for registering and rendering our shortcodes.
		 */
		require_once WPLT_DIR . 'includes/class-wplt-shortcodes.php';

		/**
		 * The class responsible for displaying notifications, like errors, login messages, etc.
		 */
		require_once WPLT_DIR . 'includes/class-wplt-notifications.php';

		$this->loader = new Wplt_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wplt_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wplt_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wplt_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wplt_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/**
	 * Register plugins filter functionalities.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_filters() {

		$plugin_private = new Wplt_Private( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'query_vars', $plugin_private, 'add_query_vars' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wplt_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
