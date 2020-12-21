<?php

/**
 * Fired during plugin activation
 *
 * @link       https://showspy.ru
 * @since      1.0.0
 *
 * @package    Import_Vk_Comments
 * @subpackage Import_Vk_Comments/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Import_Vk_Comments
 * @subpackage Import_Vk_Comments/includes
 * @author     DenAnd <denandteam@gmail.com>
 */
class Import_Vk_Comments_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option('ivc_access_token', '');
		add_option('ivc_client_id', '');
		add_option('ivc_pages', '');
	}

}
