<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blockvis.com
 * @since      1.0.0
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/admin
 * @author     Blockvis <blockvis@blockvis.com>
 */
class Civic_Sip_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}

	/**
	 * @since 1.0.0
	 */
	public function register_settings_page() {

		add_menu_page(
			__( 'Civic QR Auth', 'civic-sip' ),
			__( 'Civic QR Auth', 'civic-sip' ),
			'manage_options',
			'civic-qr-auth',
			[ $this, 'display_settings_page' ],
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+Cjxzdmcgd2lkdGg9IjY0MCIgaGVpZ2h0PSI0ODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogPCEtLSBDcmVhdGVkIHdpdGggU1ZHLWVkaXQgLSBodHRwOi8vc3ZnLWVkaXQuZ29vZ2xlY29kZS5jb20vIC0tPgogPGRlZnM+CiAgPHN5bWJvbCBpZD0ic3ZnXzEiIHZpZXdCb3g9IjAgMCAxNjAgMTYwIiBoZWlnaHQ9IjE2MCIgd2lkdGg9IjE2MCIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CiAgIDxwYXRoIGQ9Im04OC4yMjkzNzgsODYuMjI3NDMyYzYuMjMwMTk0LC0zLjA0NzkzNSAxMC41MjA2MjIsLTkuNDQ4OTQ0IDEwLjUyMDYyMiwtMTYuODUyMzcxYzAsLTEwLjM1NTYyMSAtOC4zOTQzNzksLTE4Ljc1IC0xOC43NSwtMTguNzVjLTEwLjM1NTYyMSwwIC0xOC43NSw4LjM5NDM3OSAtMTguNzUsMTguNzVjMCw3LjQwMzY3MSA0LjI5MDcxOCwxMy44MDQ4NTUgMTAuNTIxMjQ4LDE2Ljg1MjY3NmwwLDIzLjE0NzI2M2wxNi40NTgxMywwbDAsLTIzLjE0NzU2OHptLTguMjI5Mzc4LDUzLjc3MjU2OGMtMzMuMDg0MzcsMCAtNjAsLTI2LjkxNTYyNyAtNjAsLTYwYzAsLTMzLjA4NDM3IDI2LjkxNTYzLC02MCA2MCwtNjBjMjYuOTk4NzQ5LDAgNDkuODg2MjQ2LDE3LjkyNjI1IDU3LjM5NDM3OSw0Mi41bDIwLjY4MDYxOCwwYy03Ljk4MTg3MywtMzUuNzY0OTk5IC0zOS45MDQzNzMsLTYyLjUgLTc4LjA3NDk5NywtNjIuNWMtNDQuMTgzMTIxLDAgLTgwLDM1LjgxNzUwMSAtODAsODBjMCw0NC4xODI1MDMgMzUuODE2ODc5LDgwIDgwLDgwYzM4LjE3MDYyNCwwIDcwLjA5MzEyNCwtMjYuNzM1MDAxIDc4LjA3NDk5NywtNjIuNWwtMjAuNjgwNjE4LDBjLTcuNTA4MTMzLDI0LjU3Mzc1MyAtMzAuMzk1NjMsNDIuNSAtNTcuMzk0Mzc5LDQyLjV6Ii8+CiAgPC9zeW1ib2w+CiA8L2RlZnM+CiA8Zz4KICA8dGl0bGU+TGF5ZXIgMTwvdGl0bGU+CiAgPHVzZSBmaWxsPSIjZmZmZmZmIiB4PSIwIiB5PSIwIiB4bGluazpocmVmPSIjc3ZnXzEiIGlkPSJzdmdfMiIvPgogIDxnIGlkPSJzdmdfMyIvPgogPC9nPgo8L3N2Zz4=',
			90
		);

	}

	/**
	 * @since 1.0.0
	 */
	public function display_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/civic-sip-admin-display.php';

	}

	/**
	 * @since 1.0.0
	 */
	public function register_settings() {

		register_setting(
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings'
		);


		add_settings_section(
			$this->plugin_name . '-settings-section',
			__( 'Settings', 'civic-sip' ),
			[ $this, 'add_settings_section' ],
			$this->plugin_name . '-settings'
		);

		add_settings_field(
			'app_id',
			__( 'App ID', 'civic-sip' ),
			[ $this, 'add_settings_field_input_text' ],
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			[
				'value_for' => 'app_id',
			]
		);

		add_settings_field(
			'pubkey',
			__( 'Public Signing Key ', 'civic-sip' ),
			[ $this, 'add_settings_field_input_text' ],
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			[
				'value_for' => 'pubkey',
			]
		);

		add_settings_field(
			'privkey',
			__( 'Private Signing Key', 'civic-sip' ),
			[ $this, 'add_settings_field_input_text' ],
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			[
				'value_for' => 'privkey',
			]
		);

		add_settings_field(
			'secret',
			__( 'Secret', 'civic-sip' ),
			[ $this, 'add_settings_field_input_text' ],
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			[
				'value_for' => 'secret',
			]
		);
		add_settings_field(
			'wp_user_auth_enabled',
			__( 'Enable WP User Authentication', 'civic-sip' ),
			[ $this, 'add_settings_field_single_checkbox' ],
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			[
				'value_for' => 'wp_user_auth_enabled',
			]
		);

	}

	/**
	 * @since 1.0.0
	 */
	public function add_settings_field_input_text( $args ) {
		$field_id = $args['value_for'];
		$options  = get_option( $this->plugin_name . '-settings' );
		$option   = isset( $options[ $field_id ] ) ? $options[ $field_id ] : '';
		?>
        <input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
               id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
               value="<?php echo esc_attr( $option ); ?>" class="regular-text"/>
		<?php
	}

	/**
	 * @since 1.0.0
	 */
	public function add_settings_field_single_checkbox( $args ) {

		$field_id = $args['value_for'];
		$options  = get_option( $this->plugin_name . '-settings' );
		$option   = isset( $options[ $field_id ] ) ? $options[ $field_id ] : 0;

		?>

        <label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
            <input type="checkbox"
                   name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
                   id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
				<?php checked( 1, $option, 1 ); ?>
                   value="1"/>
        </label>

		<?php

	}

	/**
	 * @since 1.0.0
	 */
	public function add_settings_section() {
		?>
        <p class="description">
			<?php echo esc_html__( 'These values are obtained through our Partner Developer Portal once you have registered as a partner.', 'civic-sip' ); ?>
        </p>
        <p class="description">
			<?php echo esc_html__( 'Please follow this link to register:', 'civic-sip' ); ?>
            <a href="https://www.civic.com/secure-identity-platform"
               target="_blank"><?php echo esc_html__( 'Civic SIP', 'civic-sip' ); ?></a>
        </p>
		<?php
	}

}
