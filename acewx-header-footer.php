<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://acewebx.com
 * @since             1.0.0
 * @package           Acewx_Header_Footer
 *
 * @wordpress-plugin
 * Plugin Name:       Ace Header Footer
 * Plugin URI:        https://acewx-header-footer
 * Description:       A lightweight and flexible Header & Footer Addon for Elementor that lets you design custom headers and footers using Elementor—no coding required.
 * Version:           1.0.0
 * Author:            AceWebx Team
 * Author URI:        https://acewebx.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acewx-header-footer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ACEWX_HEADER_FOOTER_VERSION', '1.0.0' );
define( 'ACEWX_HEADER_FOOTER_FILE', __FILE__ );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acewx-header-footer-activator.php
 */
function acewx_activate_header_footer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acewx-header-footer-activator.php';
	Acewx_Header_Footer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acewx-header-footer-deactivator.php
 */
function acewx_deactivate_header_footer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acewx-header-footer-deactivator.php';
	Acewx_Header_Footer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'acewx_activate_header_footer' );
register_deactivation_hook( __FILE__, 'acewx_deactivate_header_footer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-acewx-header-footer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function acewx_run_header_footer() {

	$plugin = new Acewx_Header_Footer();
	$plugin->run();

}
acewx_run_header_footer();
