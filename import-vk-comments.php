<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://showspy.ru
 * @since             1.0.0
 * @package           Import_Vk_Comments
 *
 * @wordpress-plugin
 * Plugin Name:       Import Vk Comments
 * Plugin URI:        https://github.com/denand-team/import-vk-comments
 * Description:       Плагин импортирует комментарии из виджета комментариев ВК в WordPress.
 * Version:           1.0.0
 * Author:            DenAnd
 * Author URI:        https://showspy.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       import-vk-comments
 * Domain Path:       /languages
 */

// Если файл загружен напрямую, отменить.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Текущая версия плагина
 */
define( 'IMPORT_VK_COMMENTS_VERSION', '1.0.0' );

/**
 * Активация плагина
 * Документация по функции includes/class-import-vk-comments-activator.php
 */
function activate_import_vk_comments() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-import-vk-comments-activator.php';
	Import_Vk_Comments_Activator::activate();
}

/**
 * Деактивация плагина
 * Документация по функции includes/class-import-vk-comments-deactivator.php
 */
function deactivate_import_vk_comments() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-import-vk-comments-deactivator.php';
	Import_Vk_Comments_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_import_vk_comments' );
register_deactivation_hook( __FILE__, 'deactivate_import_vk_comments' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-import-vk-comments.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_import_vk_comments() {

	$plugin = new Import_Vk_Comments();
	$plugin->run();

}
run_import_vk_comments();

// Add admin page
if ( is_admin() ) {
	require_once ('admin/class-import-vk-comments-admin.php');
}