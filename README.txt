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

The latest source code and development progress is available on <a href="https://github.com/blockvis/civic-sip-wp">GitHub</a>.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/civic-sip` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Set app id, private key, public key and secret in 'Civic QR Auth' settings page.

== Frequently Asked Questions ==

= How to display "Sign In With Civic" button on WP login/registration page? =
The "Sign In With Civic" button will be displayed on the login and registration pages after the plugin is activated. You can hide the button by switching off the "Show Civic Sign In Button" setting.

= How to display "Sign In With Civic" button on other pages? =
You can place `[civic-auth]` shortcode in your templates to display "Sign In With Civic" button.

= How could I implement custom authentication workflow? =
If you see some important functionality is missing, feel free to open an issue on <a href="https://github.com/blockvis/civic-sip-wp/issues">GitHub</a>. Otherwise you can implement your custom authentication workflow by hooking to the `civic_sip_auth` action. Remember to disable default workflow by switching off "Enable WP User Authentication" setting first. In this scenario requested scope data will be passed directly to your callback function.

== Screenshots ==

1. Settings page.
2. Login form.

== Changelog ==

= 1.1.3 =
* Auth cookies are cleared before login.
* 3rd argument added to `wp_login` action call.

= 1.1.2 =
* The intended URL is respected now. User is redirected to desired page after successful login.
* The login flow now respects 'Remember me' checkbox state and sets the corresponding cookie expiration correctly.

= 1.1.1 =
* Civic modal pop up is not showing when Enter is pressed.

= 1.1.0 =
* Show/hide "Sign in With Civic" button option added.
* Login flow now respects "Anyone can register" setting.
* Added system requirements check for PHP and WP version. Also php-gmp extension is checked.

= 1.0.0 =
* Initial release.
