<?php
/**
 * Plugin Name:       digital-goods
 * Plugin URI:        https://shapon.me/
 * Description:       Digital Goods licencing System
 * Version:           2.0
 * Author:            Shapon pal
 * Author URI:        https://www.facebook.com/shaponp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       digital-goods
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
define( 'DIGITAL_GOODS_VERSION', '2.0' );

// Const for path root
if (!defined('DPLS_PATH')) {
    define('DPLS_PATH', __DIR__);
}

// Const for uploads path
if (!defined('UPLOADS_PATH')) {
    $uploadDir = wp_upload_dir();
    define('UPLOADS_PATH', $uploadDir['basedir']);
}

// Const for uploads url
if (!defined('UPLOADS_URL')) {
    $uploadUrl = wp_upload_dir();
    define('UPLOADS_URL', set_url_scheme($uploadUrl['baseurl']));
}

// Const for uploads url
if (!defined('UPLOADS_DPLS_FILES_URL')) {
    define('UPLOADS_DPLS_FILES_URL', UPLOADS_URL . '/amelia/files/');
}

// Const for uploads files path
if (!defined('UPLOADS_DPLS_FILES_PATH')) {
    define('UPLOADS_DPLS_FILES_PATH', UPLOADS_PATH . '/amelia/files/');
}

// Const for uploads files path
if (!defined('UPLOADS_DPLS_FILES_PATH_USE')) {
    define('UPLOADS_DPLS_FILES_PATH_USE', true);
}

// Const for URL root
if (!defined('DPLS_URL')) {
    define('DPLS_URL', plugin_dir_url(__FILE__));
}

// Const for URL Actions identifier
if (!defined('DPLS_ACTION_SLUG')) {
    define('DPLS_ACTION_SLUG', 'action=DPLS_api&call=');
}

// Const for URL Actions identifier
if (!defined('DPLS_ACTION_URL')) {
    define('DPLS_ACTION_URL', get_site_url() . '/wp-admin/admin-ajax.php?' . DPLS_ACTION_SLUG);
}

// Const for URL Actions identifier
if (!defined('DPLS_PAGE_URL')) {
    define('DPLS_PAGE_URL', get_site_url() . '/wp-admin/admin.php?page=');
}

// Const for URL Actions identifier
if (!defined('DPLS_LOGIN_URL')) {
    define('DPLS_LOGIN_URL', get_site_url() . '/wp-login.php?redirect_to=');
}

// Const for Amelia version
if (!defined('DPLS_VERSION')) {
    define('DPLS_VERSION', '3.1.2');
}

// Const for site URL
if (!defined('DPLS_SITE_URL')) {
    define('DPLS_SITE_URL', get_site_url());
}

// Const for plugin basename
if (!defined('DPLS_PLUGIN_SLUG')) {
    define('DPLS_PLUGIN_SLUG', plugin_basename(__FILE__));
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-digital-goods-activator.php
 */
function activate_digital_goods() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-digital-goods-activator.php';
	Digital_Goods_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-digital-goods-deactivator.php
 */
function deactivate_digital_goods() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-digital-goods-deactivator.php';
	Digital_Goods_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_digital_goods' );
register_deactivation_hook( __FILE__, 'deactivate_digital_goods' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-digital-goods.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_digital_goods() {

	$plugin = new Digital_Goods();
	$plugin->run();

}
run_digital_goods();