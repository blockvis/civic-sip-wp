<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://blockvis.com
 * @since      1.0.0
 *
 * @package    Civic_Sip
 * @subpackage Civic_Sip/admin/partials
 */
?>

<div id="wrap">
    <form method="post" action="options.php">
		<?php
		    settings_fields( 'civic-sip-settings' );
		    do_settings_sections( 'civic-sip-settings' );
		    submit_button();
		?>
    </form>
</div>
