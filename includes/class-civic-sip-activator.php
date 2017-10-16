<?php

/**
 * Fired during plugin activation
 *
 * @link       https://blockvis.com
 * @since      1.0.0
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Civic_Sip
 * @subpackage Civic_Sip/includes
 * @author     Blockvis <blockvis@blockvis.com>
 */
class Civic_Sip_Activator {

	/**
	 * Activates the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Enable WP user auth flow upon first activation.
		if ( get_option( 'civic-sip-settings' ) === false ) {
			update_option( 'civic-sip-settings', [ 'wp_user_auth_enabled' => 1, 'wp_user_registration_enabled' => 1 ] );
		}
	}

}
