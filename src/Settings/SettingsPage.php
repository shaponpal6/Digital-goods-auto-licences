<?php
/**
 * Mr. Assistant Settings API wrapper class.
 * This SettingsAPI class will Dynamically generate custom settings fields.
 *
 * @category   SettingsAPI
 * @package    WordPress
 * @subpackage Mr_Assistant
 * @author     Shapon pal <helpmrassistant@gmail.com>
 * @Version    1.0
 */
namespace Soopno\Settings;
use Soopno\Services\Settings\WPSettingsAPI;
use Soopno\Entity\Settings\SettingsSections;
use Soopno\Entity\Settings\SettingsFields;

/**
 * Class SettingsAPI - dynamically generate custom settings fields
 */
class SettingsPage{

    /** @var SettingsService $settingsService */
    private $settingsService;

    /**
     * SubmenuPageHandler constructor.
     *
     */
    public function __construct()
    {
        $this->settingsService = null; 
    }

    /**
     * @param $settingCategoryKey
     * @param $settingKey
     *
     * @return mixed
     */
    public static function render()
    {
        
        $settingsService = new WPSettingsAPI(); 
        $settingsService->mrSectionsInit(SettingsSections::toArray());
        $settingsService->mrFieldsInit(SettingsFields::toArray());
        $settingsService->mrAdminInitialize();


        echo '<div class="cwv-chat-wraper">';
			do_action('cwv_chat_before_settings');
            echo '<div class="cwv-chat-settings">';
            $settingsService->mrSettingTabs();
			$settingsService->mrSettingForms();
            echo '</div>';
			do_action('cwv_chat_after_settings');
            echo '</div>';
    }
        
    
}