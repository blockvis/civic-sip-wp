<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://blockvis.com
 * @since      1.0.0
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/public
 * @author     Blockvis <blockvis@blockvis.com>
 */
class Civic_Sip_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Create Shortcode for Civic Auth button.
	 *
	 * @since    1.0.0
	 */
	public function register_civic_auth_shortcode() {

		$settings = get_option($this->plugin_name . '-settings');
		wp_localize_script($this->plugin_name, 'settings', [
			'appId' => !empty($settings['app_id']) ? $settings['app_id'] : ''
		]);

		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_style( $this->plugin_name );

		// todo: translate
		return '<button class="js-signup">Login</button>';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( 'civic-modal', 'https://hosted-sip.civic.com/css/civic-modal.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/civic-sip-public.css', array( 'civic-modal' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script('civic-sip-hosted','https://hosted-sip.civic.com/js/civic.sip.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/civic-sip-public.js', array( 'civic-sip-hosted' ), $this->version, false );

	}

}
