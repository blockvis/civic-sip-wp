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

Note: This plugin requires that the <a href="http://php.net/manual/en/book.gmp.php">php-gmp extension</a> is installed on your WordPress server. PHP version 5.6 or 7.x is required.

== Installation ==

1. Unzip the archive to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Set app id, private key, public key and secret in Civic QR Auth settings page.
4. Place `[civic-auth]` shortcode in your templates.

== Screenshots ==

1. Settings page.
2. Login form

== Changelog ==

= 1.1.1 =

* Fix: Civic modal pop up is not showing when Enter is pressed.

= 1.1.0 =

* Show/hide "Sign in With Civic" button option added.
* Login flow now respects "Anyone can register" setting.
* Added system requirements check for PHP and WP version. Also php-gmp extension is checked.

= 1.0.0 =

* Initial release.
