<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://wpartificial.com/
 * @since      1.0.0
 *
 * @package    Digital_Goods
 * @subpackage Digital_Goods/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Digital_Goods
 * @subpackage Digital_Goods/includes
 * @author     Shapon pal <wpartificial@gmail.com>
 */
class Digital_Goods_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$dl_plugin_data_eraser = get_option('dl_plugin_data_eraser');
		if(!!$dl_plugin_data_eraser){
			global $wpdb;
			$digital_licences = $wpdb->prefix . "digital_licences";
			$dl_order_log = $wpdb->prefix . "dl_order_log";

			$sql1 = "DROP TABLE IF EXISTS $digital_licences";
			$sql2 = "DROP TABLE IF EXISTS $dl_order_log";

			$wpdb->query($sql1 );
			$wpdb->query($sql2 );

			if ( ! $cache_plugins = wp_cache_get('plugins', 'plugins') )
			$cache_plugins = array();

			if ( isset($cache_plugins[ $plugin_folder ]) )
			return $cache_plugins[ $plugin_folder ];
		}

	}

}
