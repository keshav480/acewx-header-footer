<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Fired during plugin activation
 *
 * @link       https://acewebx.com
 * @since      1.0.0
 *
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Acewx_Header_Footer
 * @subpackage Acewx_Header_Footer/includes
 * @author     AceWebx Team <Acewebx@gmail.com>
 */
class Acewx_Header_Footer_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
			// if ( ! did_action( 'elementor/loaded' ) ) {
			// deactivate_plugins( plugin_basename( __FILE__ ) );
			// wp_die(
			// 	'This plugin requires <strong>Elementor</strong> to be activated.',
			// 	'Plugin Dependency Check',
			// 	array( 'back_link' => true )
			// );
		// }
	}

}
