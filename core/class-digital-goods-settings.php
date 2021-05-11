<?php


class digital_goods_settings

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
        //add_action('admin_init', array($this, 'acb_admin_init'));
    }


/**
 * Register Settings
 */
    public function admin_init()
    {
        add_settings_section("dl_setting_section", "Plugin Data Eraser Section", null, "dl_settings");

        // Server option
        add_settings_field("dl_plugin_type", "Choose Plugin Type", array($this, "plugin_type_indicator"), "dl_settings", "dl_setting_section");
        register_setting("dl_setting_section", "dl_plugin_type");

        // Checkbox
        add_settings_field("dl_plugin_data_eraser", "Erase all data when Uninstall Plugin", array($this, "eraser_checkbox_display"), "dl_settings", "dl_setting_section");
        register_setting("dl_setting_section", "dl_plugin_data_eraser");
    }


    /**
     * Choose Plugin type
     */
    function plugin_type_indicator()
    {
        $html = '<fieldset>';
        $html .= '<label for="dl_plugin_type_server">';
        $html .= '<input type="radio" id="dl_plugin_type_server" name="dl_plugin_type" value="server" ' . checked('server', get_option('dl_plugin_type'), false) . '/>';
        $html .= '<span class="example">Server and Client</span>';
        $html .= '</label><br/>';
        $html .= '<label for="dl_plugin_type_client">';
        $html .= '<input type="radio" id="dl_plugin_type_client" name="dl_plugin_type" value="client" ' . checked('client', get_option('dl_plugin_type'), false) . '/>';
        $html .= '<span class="example">Only Client</span>';
        $html .= '</label>';
        $html .= '</fieldset>';
        echo $html;
    }

    /**
     * Erase data on delete
     */
    function eraser_checkbox_display()
    {
        $html = '<input type="checkbox" id="show_header" name="dl_plugin_data_eraser" value="1" ' . checked(1, get_option('dl_plugin_data_eraser'), false) . '/>';
        echo $html;
    }
}

// if (is_admin())
//     return new digital_goods_settings();