<?php

/**
 * The file that defines the core plugin class
 *
 * @link       http://exxica.com
 * @since      1.0.0
 *
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Exxica_Social_Marketing
 * @subpackage Exxica_Social_Marketing/admin
 * @author     Gaute Rønningen <gaute@exxica.com>
 */
class Exxica_Social_Marketing {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Exxica_Social_Marketing_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'exxica-social-marketing';
		$this->version = '1.1.7.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Exxica_Social_Marketing_Loader. Orchestrates the hooks of the plugin.
	 * - Exxica_Social_Marketing_i18n. Defines internationalization functionality.
	 * - Exxica_Social_Marketing_Admin. Defines all hooks for the dashboard.
	 * - Exxica_Social_Marketing_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exxica-social-marketing-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exxica-social-marketing-i18n.php';

		
		/**
		 * Exxica Handlers
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-exxica-social-marketing-handlers.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-exxica-social-marketing-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-exxica-social-marketing-html-output.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-exxica-social-marketing-status-update.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-exxica-social-marketing-handlers.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-exxica-social-marketing-public.php';

		$this->loader = new Exxica_Social_Marketing_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Exxica_Social_Marketing_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Exxica_Social_Marketing_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @since 	 1.1.2 			Added handlers.
	 * @access   private
	 */
	private function define_admin_hooks() {
		// Exxica HTML
		$html_admin = new Exxica_Social_Marketing_Admin_Html_Output( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_footer', $html_admin, 'add_esm_edit' );

		// Exxica Handler
		$handlers_admin = new Exxica_Social_Marketing_Handlers( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $handlers_admin, 'load_handlers' );
		
		$this->loader->add_action( 'wp_ajax_create_channel_data', $handlers_admin, 'create_channel_data' );
		$this->loader->add_action( 'wp_ajax_destroy_channel_data', $handlers_admin, 'destroy_channel_data' );
		
		$this->loader->add_action( 'wp_ajax_update_standard_channel', $handlers_admin, 'update_standard_channel' );

		$this->loader->add_action( 'wp_ajax_save_license_data', $handlers_admin, 'save_license_data' );

		$this->loader->add_action( 'wp_ajax_factory_reset', $handlers_admin, 'factory_reset' );
		
		$this->loader->add_action( 'wp_ajax_update_overview_data', $handlers_admin, 'update_overview_data' );
		$this->loader->add_action( 'wp_ajax_destroy_overview_data', $handlers_admin, 'destroy_overview_data' );

		$this->loader->add_action( 'wp_ajax_create_post_data', $handlers_admin, 'create_post_data' );
		$this->loader->add_action( 'wp_ajax_update_post_data', $handlers_admin, 'update_post_data' );
		$this->loader->add_action( 'wp_ajax_destroy_post_data', $handlers_admin, 'destroy_post_data' );

		// Admin
		$plugin_admin = new Exxica_Social_Marketing_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'exxica_help' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'set_default_values' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'create_nav_menu' );
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'create_admin_dashboard_widget' );
		$this->loader->add_action( 'media_buttons_context', $plugin_admin, 'add_custom_media_buttons' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// Public
		$plugin_public = new Exxica_Social_Marketing_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Exxica_Social_Marketing_Loader    Orchestrates the hooks of the plugin.
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
