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
        add_action('admin_init', array($this, 'acb_admin_init'));
    }

    function acb_admin_init() { 
        add_settings_section("dl_setting_section", "Plugin Data Eraser Section", null, "dl_settings");
        add_settings_field("dl_plugin_data_eraser", "Erase all data when Uninstall Plugin", array($this, "eraser_checkbox_display"), "dl_settings", "dl_setting_section");  
        register_setting("dl_setting_section", "dl_plugin_data_eraser");
        
    }

    function eraser_checkbox_display()
    {
        
    ?>
            <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
            <input type="checkbox" name="dl_plugin_data_eraser" value="1" <?php checked(1, get_option('dl_plugin_data_eraser'), true); ?> />
    <?php
    }


    function dlSetting_page()
    {
    ?>
        <div class="wrap">
            <h1>Digital Licencing Settings Page</h1>
            <p>Digital Licencing Settings Page</p>
    
            <form method="post" action="options.php">
                <?php
                settings_fields("dl_setting_section");
    
                do_settings_sections("dl_settings");
                    
                submit_button();
                ?>
            </form>
        </div>
    <?php
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
          add_submenu_page(
            'dpls', 
            __('Data Eraser', 'textdomain'),
            __('Data Eraser', 'textdomain'),
             "manage_options", 
             "dl_settings", 
             array($this, "dlSetting_page")
            );
        // add_submenu_page(
        //     'dpls',
        //     __('Admin Settings', 'textdomain'),
        //     __('Admin Settings', 'textdomain'),
        //     'manage_options',
        //     'dpls_settings',
        //     array($this, 'dpls_settings')
        // );
    //    add_submenu_page(
    //        'dpls',
    //        __('Product Note', 'textdomain'),
    //        __('Product Note', 'textdomain'),
    //        'manage_options',
    //        'dpls_note',
    //        array($this, 'dpls_note')
    //    );

    //    add_submenu_page(
    //        'dpls',
    //        __('Documentation', 'textdomain'),
    //        __('Documentation', 'textdomain'),
    //        'manage_options',
    //        'dpls_docs',
    //        array($this, 'dpls_docs')
    //    );

    }




    /**
     * Options page callback
     */

    function dpls_settings()
    {
        // Set class property
//        $this->options = get_option('acb_config_options');
        ?>
        <div class="wrap">
            <h1>Settings</h1>
            <?php settings_errors(); ?>
            <!--  Show Manage option Page here-->
            <?php require_once 'options/dpls_settings.php'; ?>
        </div>
        <?php
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