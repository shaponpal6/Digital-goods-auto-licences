<?php

namespace Soopno\WPMenu;

use Soopno\Services\Settings\SettingsService;
use Soopno\Translations\BackendStrings;

/**
 * Class Menu
 */
class Menu
{
    /** @var SettingsService $settingsService */
    private $settingsService;

    /**
     * Menu constructor.
     *
     * 
     */
    public function __construct(SettingsService $settingsService)
    {
       $this->settingsService = $settingsService;
    }

    /**
     * @return array
     */
    public function __invoke()
    {

        $defaultPages = [
            [
                'parentSlug' => 'soopno',
                'pageTitle'  => 'Dashboard',
                'menuTitle'  => BackendStrings::getDashboardStrings()['dashboard'],
                'capability' => 'manage_options',
                'menuSlug'   => 'wpsoopno-dashboard',
            ],
            [
                'parentSlug' => 'soopno',
                'pageTitle'  => 'Live Chat',
                'menuTitle'  => BackendStrings::getLiveChatStrings()['livechat'],
                'capability' => 'manage_options',
                'menuSlug'   => 'wpsoopno-livechat',
            ],
            [
                'parentSlug' => 'soopno',
                'pageTitle'  => 'FAQ',
                'menuTitle'  => BackendStrings::getSettingsStrings()['settings'],
                'capability' => 'manage_options',
                'menuSlug'   => 'wpsoopno-settings',
            ],
            [
                'parentSlug' => 'soopno',
                'pageTitle'  => 'Settings',
                'menuTitle'  => BackendStrings::getCommonStrings()['events'],
                'capability' => 'manage_options',
                'menuSlug'   => 'wpsoopno-events',
            ]
        ];

     
        return $defaultPages;
    }
}