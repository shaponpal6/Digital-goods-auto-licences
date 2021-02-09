<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://wpartificial.com/
 * @since      1.0.0
 *
 * @package    Digital_Goods
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$dl_plugin_data_eraser = get_option('dl_plugin_data_eraser');

if(!!$dl_plugin_data_eraser){
	global $wpdb;

	$digital_licences = $wpdb->prefix . "digital_licences";
	$dl_order_log = $wpdb->prefix . "dl_order_log";

	$sql1 = "DROP TABLE IF EXISTS $digital_licences";
	$sql2 = "DROP TABLE IF EXISTS $dl_order_log";

	$wpdb->query($sql1 );
	$wpdb->query($sql2 );
}




