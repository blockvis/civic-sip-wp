=== Civic SIP ===
Contributors: blockvis
Donate link: https://blockvis.com
Tags: civic, civic sip, authorization, auth, log in, login, blockchain
Requires PHP: 5.6
Requires at least: 4.0
Tested up to: 4.8.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Civic Secure Identity Platform (SIP) authorization plugin. Sign in to your blog using Civic Mobile App.

== Description ==

Allow users to securely login and register for your site using the <a href="https://www.civic.com/secure-identity-platform">Civic Secure Identity Platform</a>. To get started, you will need to register with the <a href="https://www.civic.com/app">Civic app</a> and then use the app to log into the <a href="https://sip-partners.civic.com/">developer portal</a> to obtain your App ID and API keys.

Note: This plugin requires that the php-gmp extension is installed on your WordPress server.

== Installation ==

1. Unzip the archive to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Set app id, private key, public key and secret in Civic QR Auth settings page.
4. Place `[civic-auth]` shortcode in your templates.

== Screenshots ==

1. Settings page.

== Changelog ==

= 1.0.0 =

* Initial release.
