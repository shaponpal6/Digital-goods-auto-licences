<?php


class digital_goods_executor

{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'acb_configuration_page'));
        //add_action('admin_init', array($this, 'acb_admin_init'));
    }

    /**
     * Add options page
     */
    public function acb_configuration_page()
    {
        add_menu_page(
            __('Digital Licencing', 'textdomain'),
            'Digital Licencing',
            'manage_options',
            'dpls',
            array($this, 'dpls_order_log'),
            'dashicons-image-filter',
            26
        );
        // This page will be under "Settings"
//        add_options_page(
//            'Admin Settings',
//            'Chatbot Configuration',
//            'manage_options',
//            'my-setting-admin',
//            array($this, 'create_admin_page')
//        );
        add_submenu_page(
            'dpls',
            __('Order Log', 'textdomain'),
            __('Order Log', 'textdomain'),
            'manage_options',
            'dpls_order_log',
            array($this, 'dpls_order_log')
        );
//        add_submenu_page(
//            'dpls',
//            __('Product Note', 'textdomain'),
//            __('Product Note', 'textdomain'),
//            'manage_options',
//            'dpls_note',
//            array($this, 'dpls_note')
//        );
//
//        add_submenu_page(
//            'dpls',
//            __('Documentation', 'textdomain'),
//            __('Documentation', 'textdomain'),
//            'manage_options',
//            'dpls_docs',
//            array($this, 'dpls_docs')
//        );

    }




    /**
     * Options page callback
     */

    function dpls_order_log()
    {
        // Set class property
//        $this->options = get_option('acb_config_options');
        ?>
        <div class="wrap">
            <h1>Order Log</h1>
            <?php settings_errors(); ?>
            <!--  Show Manage option Page here-->
            <?php require_once 'options/dpls_order_log.php'; ?>
        </div>
        <?php
    }

    /**
     * Options page callback
     */

    function dpls_note()
    {
        // Set class property
//        $this->options = get_option('acb_config_options');
        ?>
        <div class="wrap">
            <h1>Default Product Note</h1>
            <?php settings_errors(); ?>
            <!--  Show Manage option Page here-->
            <?php require_once 'options/dpls_note.php'; ?>
        </div>
        <?php
    }

    /**
     * Options page callback
     */

    function dpls_docs()
    {
        // Set class property
//        $this->options = get_option('acb_config_options');
        ?>
        <div class="wrap">
            <h1>Documentation</h1>
            <?php settings_errors(); ?>
            <!--  Show Manage option Page here-->
            <?php require_once 'options/dpls_docs.php'; ?>
        </div>
        <?php
    }


}

if (is_admin())
    return new digital_goods_executor();