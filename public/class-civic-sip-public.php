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

use Blockvis\Civic\Sip\AppConfig;
use Blockvis\Civic\Sip\Client;

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
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @var array
	 */
	private $settings = [
		'app_id'               => '',
		'secret'               => '',
		'pubkey'               => '',
		'privkey'              => '',
		'wp_user_auth_enabled' => '',
	];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * @since   1.0.0
	 */
	public function civic_auth() {

		// Check the nonce first.
		check_ajax_referer( 'civic', 'nonce' );

		// Retrieve civic member data.
		$user_data = $this->exchange_token( trim( $_POST['token'] ) );

		if ( ! $this->settings()['wp_user_auth_enabled'] ) {
			do_action( 'civic_auth', $user_data );
			wp_send_json_success( [ 'logged_in' => true ] );
		}

		$email = $user_data->getByLabel( 'contact.personal.email' );
		/** @var WP_User $user */
		$user = get_user_by( 'email', $email->value() );
		if ($user === false) {
			$username = explode( '@', $email->value() )[0];
			while (username_exists( $username )) {
				$username .= mt_rand(11, 99);
			}

			// now attempt to generate the user and get the user id:
			$user_id = wp_create_user( $username , wp_generate_password(), $email->value() );

			// check if the user was actually created:
			if (is_wp_error($user_id)) {
				wp_send_json_error( new WP_Error( 'civic_sip_registration_error', __( 'Civic SIP registration failed.' ) ) );
			}

			$user = get_userdata( $user_id );
		}

		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login );
		wp_send_json_success( [ 'logged_in' => true ] );

		wp_send_json_success( [ 'logged_in' => false ] );
	}

	/**
	 * Renders Shortcode for Civic Auth button.
	 *
	 * @since    1.0.0
	 *
	 * @param array $atts
	 *
	 * @return string
	 *
	 */
	public function render_civic_auth_shortcode( $atts = [] ) {

		// Do not render for logged in users.
		if ( is_user_logged_in() ) {
			return '';
		}

		// Enqueue required assets only when shortcode is used.
		$this->enqueue_shortcode_assets();

		// Normalize attribute keys, lowercase.
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		// Override default attributes with user attributes.
		$atts = shortcode_atts( [
			'class' => '',
		], $atts );

		$html = '<button class="js-civic-signup ' . esc_attr( $atts['class'] ) . '">';
		$html .= esc_html__( 'Login with Civic', 'civic-sip' );
		$html .= '</button>';

		return $html;
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

		wp_register_script( 'civic-sip-hosted', 'https://hosted-sip.civic.com/js/civic.sip.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/civic-sip-public.js', array( 'civic-sip-hosted' ), $this->version, false );

	}

	/**
	 * Exchanges authorization code for requested user data.
	 *
	 * @since    1.0.0
	 *
	 * @param string $token
	 *
	 * @return \Blockvis\Civic\Sip\UserData
	 */
	private function exchange_token( $token ) {

		$settings = $this->settings();
		$client   = new Client(
			new AppConfig( $settings['app_id'], $settings['secret'], $settings['privkey'] ),
			new \GuzzleHttp\Client()
		);

		try {
			$user_data = $client->exchangeToken( $token );
		} catch ( Exception $e ) {
			wp_send_json_error( new WP_Error( 'civic_sip_request_error', __( 'Civic SIP authorization failed.', 'civic-sip' ) ) );
		}

		$email = $user_data->getByLabel( 'contact.personal.email' );
		if ( empty( $email ) || ! $email->isValid() ) {
			wp_send_json_error( new WP_Error( 'civic_sip_invalid_email', __( 'Invalid civic member email.', 'civic-sip' ) ) );
		}

		return $user_data;
	}

	/**
	 * Returns plugin settings.
	 *
	 * @since    1.0.0
	 *
	 * @return array
	 */
	private function settings() {

		return array_merge( $this->settings, get_option( $this->plugin_name . '-settings' ) );
	}

	/**
	 * Adds scripts and styles required for shortcode render.
	 *
	 * @since    1.0.0
	 */
	private function enqueue_shortcode_assets() {
		$settings = $this->settings();

		// Civic App details.
		wp_localize_script( $this->plugin_name, 'civic_app', [
			'id' => ! empty( $settings['app_id'] ) ? $settings['app_id'] : '',
		] );

		// Civic auth AJAX endpoint params.
		wp_localize_script( $this->plugin_name, 'civic_ajax', [
			'action'       => 'civic_auth',
			'url'          => admin_url( 'admin-ajax.php' ),
			'redirect_url' => home_url(),
			'nonce'        => wp_create_nonce( 'civic' ),
		] );

		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_style( $this->plugin_name );
	}
}
