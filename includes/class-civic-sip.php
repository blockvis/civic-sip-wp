<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://blockvis.com
 * @since      1.0.0
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/includes
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
 * @package    Civic_Sip
 * @subpackage Civic_Sip/includes
 * @author     Blockvis <blockvis@blockvis.com>
 */
class Civic_Sip {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Civic_Sip_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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
		if ( defined( 'PLUGIN_VERSION' ) ) {
			$this->version = PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'civic-sip';

		$this->load_dependencies();
		$this->set_locale();
        if ( $this->check_requirements() ) {
            $this->define_admin_hooks();
            $this->define_public_hooks();
        }

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Civic_Sip_Loader. Orchestrates the hooks of the plugin.
	 * - Civic_Sip_i18n. Defines internationalization functionality.
	 * - Civic_Sip_Admin. Defines all hooks for the admin area.
	 * - Civic_Sip_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Composer autoload for external dependencies.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-civic-sip-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-civic-sip-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-civic-sip-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-civic-sip-public.php';

		$this->loader = new Civic_Sip_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Civic_Sip_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Civic_Sip_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

    /**
     * Checks system requirements. Returns true if can run the plugin.
     *
     * @return bool
     */
	private function check_requirements() {

	    // Check against minimum PHP and WordPress version
        if ( version_compare( PHP_VERSION, '5.6', '<' )
             || version_compare(  get_bloginfo( 'version' ), '4.0', '<' ) ) {
            $plugin_admin = new Civic_Sip_Admin( $this->get_plugin_name(), $this->get_version() );
            $this->loader->add_action( 'admin_init', $plugin_admin, 'deactivate' );
            $this->loader->add_action( 'admin_notices', $plugin_admin, 'version_notice' );

            return false;
        }

        // Make sure GMP extension is installed and enabled
        if ( !extension_loaded('gmp') ) {
            $plugin_admin = new Civic_Sip_Admin( $this->get_plugin_name(), $this->get_version() );
            $this->loader->add_action( 'admin_init', $plugin_admin, 'deactivate' );
            $this->loader->add_action( 'admin_notices', $plugin_admin, 'gmp_notice' );

            return false;
        }

        return true;
    }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Civic_Sip_Admin( $this->get_plugin_name(), $this->get_version() );

		// Add settings hooks
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Civic_Sip_Public( $this->get_plugin_name(), $this->get_version() );

		if ( is_admin() ) {
			$this->loader->add_action( 'wp_ajax_nopriv_civic_auth', $plugin_public, 'civic_auth' );
			$this->loader->add_action( 'wp_ajax_nopriv_civic_register', $plugin_public, 'civic_register' );
			if ( $plugin_public->settings()['wp_user_auth_enabled'] ) {
				$this->loader->add_action( 'civic_sip_auth', Civic_Sip_Public::class, 'sip_auth_handle', 100 );
			}
		}

		// Register plugin styles and scripts.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Register 'civic-auth' shortcode.
		$this->loader->add_shortcode( 'civic-auth', $plugin_public, 'civic_auth_button' );

		// Add auth button to WP login/registration forms.
		$this->loader->add_action( 'login_form', $plugin_public, 'render_civic_auth_button' );
		if ( $plugin_public->settings()['wp_user_registration_enabled'] ) {
			$this->loader->add_action( 'register_form', $plugin_public, 'render_civic_auth_button' );
		}
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
	 * @return    Civic_Sip_Loader    Orchestrates the hooks of the plugin.
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
