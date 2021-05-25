<?php


class digital_goods_menus

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
        $this->plugin_type = get_option('dl_plugin_type');
        add_action('admin_menu', array($this, 'acb_configuration_page'));
        add_action('admin_init', array(new digital_goods_settings(), 'admin_init'));
    }





    /**
     * Add options page
     */
    public function acb_configuration_page()
    {



        add_menu_page(
            __('Automatic LC', 'textdomain'),
            'Automatic LC',
            'manage_options',
            'dpls',
            array($this, 'dpls_server_page'),
            'dashicons-image-filter',
            26
        );
        // This page will be under "Settings"
        // add_options_page(
        //     'Admin Settings',
        //     'Chatbot Configuration',
        //     'manage_options',
        //     'my-setting-admin',
        //     array($this, 'create_admin_page')
        // );
     
        if($this->plugin_type ==="server"):
        add_submenu_page(
            'dpls',
            __('LC Manager', 'textdomain'),
            __('LC Manager', 'textdomain'),
            'manage_options',
            'dpls_lc_manager',
            array($this, 'dpls_lc_manager')
        );
           add_submenu_page(
               'dpls',
               __('Product Note', 'textdomain'),
               __('Product Note', 'textdomain'),
               'manage_options',
               'dpls_note',
               array($this, 'dpls_note')
           );

           add_submenu_page(
               'dpls',
               __('Documentation', 'textdomain'),
               __('Documentation', 'textdomain'),
               'manage_options',
               'dpls_docs',
               array($this, 'dpls_docs')
           );
        endif;

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

    }




    /**
     * Options page callback
     */

    function dpls_lc_manager()
    {
       echo 'dpls_lc_manager';
    }



    /**
     * Options page callback
     */

    function dpls_server_page()
    {
        // Set class property
        // $plugin_type = get_option('dl_plugin_type');
    ?>
<div class="wrap">
    <h1>Licence Server</h1>
    <?php settings_errors(); ?>
    <!--  Show Manage option Page here-->
    <?php 
    if($this->plugin_type ==="server"):
    require_once 'options/dpls_settings.php'; 
    endif;
    ?>
</div>
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
    return new digital_goods_menus();