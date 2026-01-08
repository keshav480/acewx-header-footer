<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/includes
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
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/includes
 * @author     AceWebx Team <Acewebx@gmail.com>
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Acewx_Header_Footer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Acewx_Header_Footer_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'ACEWX_HEADER_FOOTER_VERSION' ) ) {
			$this->version = ACEWX_HEADER_FOOTER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'acewx-header-footer';

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
	 * - Acewx_Header_Footer_Loader. Orchestrates the hooks of the plugin.
	 * - Acewx_Header_Footer_i18n. Defines internationalization functionality.
	 * - Acewx_Header_Footer_Admin. Defines all hooks for the admin area.
	 * - Acewx_Header_Footer_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-acewx-header-footer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-acewx-header-footer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-acewx-header-footer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-acewx-header-footer-public.php';

		$this->loader = new Acewx_Header_Footer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Acewx_Header_Footer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Acewx_Header_Footer_i18n();

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

		$plugin_admin = new Acewx_Header_Footer_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init',$plugin_admin, 'check_elementor_dependency' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_header_footer_cpt' );
		$this->loader->add_action( 'save_post_acewx_header_footer', $plugin_admin, 'acewx_set_default_elementor_layout', 10, 3 );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'acewx_header_footer_metabox');
		$this->loader->add_action( 'save_post', $plugin_admin, 'acewx_save_header_conditions_metabox');
		$this->loader->add_action( 'manage_acewx_header_footer_posts_columns', $plugin_admin, 'acewx_template_column');
		$this->loader->add_action( 'manage_acewx_header_footer_posts_custom_column', $plugin_admin, 'acewx_template_column_data',10,2);
		$this->loader->add_action( 'wp_ajax_acewx_toggle_hf_status', $plugin_admin, 'acewx_toggle_hf_status' );
				
		$this->loader->add_action( 'elementor/widgets/register',$plugin_admin, 'register_custom_elementor_widgets' );
		$this->loader->add_action( 'after_setup_theme', $plugin_admin, 'custom_plugin_register_nav_menu' );
		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Acewx_Header_Footer_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_body_open', $plugin_public, 'replace_header', 0 );
		$this->loader->add_action( 'get_header', $plugin_public, 'acewx_default_header');
		$this->loader->add_action( 'get_footer',  $plugin_public, 'acewx_default_footer');
		$this->loader->add_action( 'wp_footer',  $plugin_public, 'acewx_replace_footer');
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
	 * @return    Acewx_Header_Footer_Loader    Orchestrates the hooks of the plugin.
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
