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
use Blockvis\Civic\Sip\UserData;

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
	 * Default settings.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array $settings The default settings values.
	 */
	private $settings = [
		'app_id'               => '',
		'secret'               => '',
		'pubkey'               => '',
		'privkey'              => '',
		'wp_user_auth_enabled' => false,
		'show_civic_button'    => false,
	];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Exchanges civic authorization token for user data.
	 *
	 * @since   1.0.0
	 */
	public function civic_auth() {

		// Check the nonce first.
		check_ajax_referer( 'civic', 'nonce' );

		// Retrieve civic member data.
		$user_data = $this->exchange_token( trim( $_POST['token'] ) );

		do_action( 'civic_sip_auth', $user_data );

	}

	/**
	 * Creates new WP user with civic member email.
	 *
	 * @since   1.0.0
	 */
	public function civic_register() {

		// Check the nonce first.
		check_ajax_referer( 'civic', 'nonce' );

		$email = $_POST['email'];

		//var_dump($email); die;
		$username = explode( '@', $email )[0];
		while ( username_exists( $username ) ) {
			$username .= mt_rand( 11, 99 );
		}

		// Attempt to generate the user and get the user id.
		$user_id = wp_create_user( $username, wp_generate_password(), $email );

		// Check if the user was actually created.
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error(
				new WP_Error( 'civic_sip_registration_error', __( 'Civic SIP registration failed.', 'civic-sip' ) )
			);
		}

		// Log in registered user automatically.
		self::wp_login( get_userdata( $user_id ) );

	}

	/**
	 * Returns Civic Auth button markup.
	 *
	 * @since    1.0.0
	 *
	 * @param array $atts
	 *
	 * @return string
	 *
	 */
	public function civic_auth_button( $atts = [] ) {

		// Do not render for logged in users.
		if ( is_user_logged_in() ) {
			return '';
		}

		// Do not render if not all settings provided.
		if ( $this->has_empty_setting() ) {
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

		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/civic-sip-auth-button.php';

		return ob_get_clean();
	}


	/**
	 * Renders Civic Auth button.
	 *
	 * @since    1.1.0
	 *
	 * @return string
	 *
	 */
	public function render_civic_auth_button() {
		echo sprintf( '<p class="civic-auth-button-container">%s</p>', do_shortcode( '[civic-auth]' ) );
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
	public function settings() {
		return array_merge( $this->settings, get_option( $this->plugin_name . '-settings', array() ) );
	}

	/**
	 * Checks whether all settings are filled in.
	 *
	 * @since    1.0.0
	 *
	 * @return bool
	 */
	public function has_empty_setting() {
		return in_array( '', $this->settings(), true );
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
			'url'          => admin_url( 'admin-ajax.php' ),
			'redirect_url' => home_url(),
			'nonce'        => wp_create_nonce( 'civic' ),
		] );

		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_style( $this->plugin_name );

	}

	/**
	 * Logs existing WP user in.
	 *
	 * @since    1.0.0
	 *
	 * @param WP_User $user
	 */
	public static function wp_login( WP_User $user ) {

		wp_set_current_user( $user->ID, $user->user_login );
		wp_set_auth_cookie( $user->ID );
		do_action( 'wp_login', $user->user_login );
		wp_send_json_success( [ 'logged_in' => true ] );

	}

	/**
	 * Implements default Civic QR auth flow.
	 * Attempts to find a match by email address of a WP User. If a match is found, it uses WP API to log that user in.
	 * If no match is found it shows the user a message and gives them the opportunity to confirm registering as a new
	 * user on this WordPress site, or they can cancel.
	 *
	 * @param UserData $user_data Civic user data object.
	 *
	 * @return void
	 */
	public static function sip_auth_handle( UserData $user_data ) {

		$email = $user_data->getByLabel( 'contact.personal.email' )->value();
		/** @var WP_User $user */
		$user = get_user_by( 'email', $email );
		if ( $user === false ) {
			$response = [ 'logged_in' => false];
			if ( get_option( 'users_can_register' ) ) {
				$response['email'] = $email;
			}
			ob_start();
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/civic-sip-registration-modal.php';
			$response['modal'] = ob_get_clean();

			wp_send_json_success( $response );
		}

		self::wp_login( $user );

	}
}
