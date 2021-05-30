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
        $charset_collate = $wpdb->get_charset_collate();

        $table1 = $wpdb->prefix . "dpls_license_lookup";
        $table2 = $wpdb->prefix . "dpls_license_items";
        $table3 = $wpdb->prefix . "dpls_license_relationships";

        
        $table4 = $wpdb->prefix . "dpls_vendor_lookup";
        $table5 = $wpdb->prefix . "dpls_vendor_domains";
        $table6 = $wpdb->prefix . "dpls_vendor_domains_relationships";
        
        $table7 = $wpdb->prefix . "dpls_vendor_license_relationships";
        
        $table8 = $wpdb->prefix . "dpls_templates"; #Type: email || procedure
        //$table9 = $wpdb->prefix . "dpls_procedure_templates";
        
        $table9 = $wpdb->prefix . "dpls_license_order_log";

        

        $digital_licences = $wpdb->prefix . "digital_licences";
        $dl_order_log = $wpdb->prefix . "dl_order_log";


        // dpls_license_lookup
        $sql1 = "CREATE TABLE $table1 ( 
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `title` varchar(100) NOT NULL,
                  `content` text NOT NULL,
                  `license_type` varchar(20) NOT NULL, # serialize || Rendom
                  `product_type` varchar(20) NOT NULL, #single || Variation
                  `product_id` int(20) NOT NULL,
                  `variation_id` int(20) NOT NULL,
                  `vendor_license_type` varchar(20) NOT NULL, #master || unique
                  `vendor_relation_id` int(10) NOT NULL,  #table id
                  `email_template_type` varchar(20) NOT NULL, #master || unique
                  `email_template_id` int(10) NOT NULL, #template table id
                  `status` varchar(20) NOT NULL, #active || Inactive
                  `author` int(10) NOT NULL,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        // dpls_license_item        
        $sql2 = "CREATE TABLE $table2 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `title` varchar(100) NOT NULL,
                  `license_type` varchar(20) NOT NULL,
                  `license_text` text NOT NULL,
                  `login_id` varchar(255) NOT NULL,
                  `login_password` varchar(255) NOT NULL,
                  `download_link` text NOT NULL,
                  `procedure_template_id` int(10) NOT NULL, # template table id
                  `sold_item` int(20) NOT NULL,
                  `maximun_item` int(20) NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        // dpls_license_relationships        
        $sql3 = "CREATE TABLE $table3 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `license_lookup_id` int(20) NOT NULL, 
                  `license_item_id` int(20) NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";



        
        /**
         * Vendor domain relationship
         */

        // dpls_vendor_lookup
        $sql4 = "CREATE TABLE $table4 ( 
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `title` varchar(100) NOT NULL,
                  `content` text NOT NULL,
                  `license_type` varchar(20) NOT NULL, # unlimited || limit
                  `license_token` varchar(200) NOT NULL, # unlimited || limit
                  `secret_key` varchar(200) NOT NULL, # int
                  `status` varchar(20) NOT NULL, #active || Inactive
                  `vendor_id` int(10) NOT NULL,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        // dpls_vendor_domain        
        $sql5 = "CREATE TABLE $table5 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `domain` varchar(100) NOT NULL,
                  `license_type` varchar(20) NOT NULL, #unlimited || limit
                  `license_limit` int(20) NOT NULL, #int
                  `status` varchar(20) NOT NULL, #active || Inactive
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        // dpls_vendor_domain_relationships        
        $sql6 = "CREATE TABLE $table6 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `vendor_lookup_id` int(20) NOT NULL, 
                  `vendor_domain_id` int(20) NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";


        /**
         * dpls_vendor_license_relationships
         */   

        // dpls_vendor_license_relationships        
        $sql7 = "CREATE TABLE $table7 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `license_lookup_id` int(20) NOT NULL, 
                  `vendor_lookup_id` int(20) NOT NULL,
                  PRIMARY KEY  (id)
                ) $charset_collate;";




        /**
         * dpls_email_templates
         */

        $sql8 = "CREATE TABLE $table8 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `title` varchar(200) NOT NULL,
                  `template_type` varchar(200) NOT NULL, # email_template || procedure_template
                  `subject` varchar(200) NOT NULL,
                  `content` text NOT NULL,
                  `status` varchar(20) NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";


        /**
         * dpls_license_order_log
         */
        $sql9 = "CREATE TABLE $table9 (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `license_lookup_id` int(10) NOT NULL,
                  `license_item_id` int(10) NOT NULL,
                  `vendor_lookup_id` int(10) NOT NULL,
                  `vendor_domain_id` int(10) NOT NULL,
                  `order_id` varchar(100) NOT NULL,
                  `product_id` int(10) NOT NULL,
                  `user_id` int(10) NOT NULL,
                  `status` varchar(20) NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";



        $sql10 = "CREATE TABLE $digital_licences (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `product_id` int(10) NOT NULL,
                  `type` varchar(20) NOT NULL,
                  `licence` varchar(100) NOT NULL,
                  `login_id` varchar(100) NOT NULL,
                  `login_password` varchar(100) NOT NULL,
                  `download_link` text NOT NULL,
                  `sold` int(10) NOT NULL,
                  `total` int(10) NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        $sql11 = "CREATE TABLE $dl_order_log (
                  `id` mediumint(8) unsigned NOT NULL auto_increment,
                  `row_id` int(10) NOT NULL,
                  `order_id` varchar(100) NOT NULL,
                  `product_id` int(10) NOT NULL,
                  `user_id` int(10) NOT NULL,
                  `type` varchar(20) NOT NULL,
                  `licence` varchar(100) NOT NULL,
                  `login_id` varchar(100) NOT NULL,
                  `login_password` varchar(100) NOT NULL,
                  `download_link` text NOT NULL,
                  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY  (id)
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );
        dbDelta( $sql2 );
        dbDelta( $sql3 );
        dbDelta( $sql4 );
        dbDelta( $sql5 );
        dbDelta( $sql6 );
        dbDelta( $sql7 );
        dbDelta( $sql8 );
        dbDelta( $sql9 );
        dbDelta( $sql10 );
        dbDelta( $sql11 );

	}

}