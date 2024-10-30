<?php
/*
Plugin Name: INVOX Call Tracking
Description: Adds the INVOX Call Tracking JavaScript tag to the active theme.
Version: 1.0
Stable tag: 1.0
Author: invox
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register the plugin settings page
function invox_tracking_plugin_menu() {
    add_options_page('INVOX Call Tracking Settings', 'INVOX Call Tracking', 'manage_options', 'invox-tracking-settings', 'invox_tracking_settings_page');
}
add_action('admin_menu', 'invox_tracking_plugin_menu');

// Create the plugin settings page
function invox_tracking_settings_page() {
    ?>
    <div class="wrap">
        <h2>INVOX Call Tracking Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('invox-tracking-settings-group'); ?>
            <?php do_settings_sections('invox-tracking-settings-group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Version (v)</th>
                    <td><input type="text" name="invox_version" value="<?php echo esc_attr(get_option('invox_version')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register and sanitize the plugin settings
function invox_tracking_register_settings() {
    register_setting('invox-tracking-settings-group', 'invox_version');
    // Check if 'invox_version' option exists, and if not, set a default value of '1'
    if (get_option('invox_version') === false) {
        update_option('invox_version', '1');
    }
}
add_action('admin_init', 'invox_tracking_register_settings');

// Enqueue the JavaScript tag in the active theme
function invox_enqueue_tracking_script() {
    $cid = get_option('invox_cid');
    $version = get_option('invox_version');
    if (!empty($version)) {
        $script_url = "//app.invox.eu/invox_tracking.js?v=$version";
        wp_enqueue_script('invox-tracking-script', $script_url, array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'invox_enqueue_tracking_script');
