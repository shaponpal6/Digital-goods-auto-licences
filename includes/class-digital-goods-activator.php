<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wpartificial.com/
 * @since      1.0.0
 *
 * @package    Digital_Goods
 * @subpackage Digital_Goods/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Digital_Goods
 * @subpackage Digital_Goods/includes
 * @author     Shapon pal <wpartificial@gmail.com>
 */
class Digital_Goods_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        global $wpdb;

        $digital_licences = $wpdb->prefix . "digital_licences";
        $dl_order_log = $wpdb->prefix . "dl_order_log";

        $charset_collate = $wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE $digital_licences (
                 `id` int(10) NOT NULL,
                  `product_id` int(10) NOT NULL,
                  `licence` varchar(100) NOT NULL,
                  `sold` int(10) NOT NULL,
                  `total` int(10) NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        $sql2 = "CREATE TABLE $dl_order_log (
                 `id` int(10) NOT NULL,
                  `order_id` varchar(100) NOT NULL,
                  `product_id` int(10) NOT NULL,
                  `licence` varchar(100) NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );
        dbDelta( $sql2 );

	}

}
