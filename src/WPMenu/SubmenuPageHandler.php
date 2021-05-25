<?php

namespace Soopno\WPMenu;

use Soopno\Services\Settings\SettingsService;
use Soopno\Translations\BackendStrings;
use Soopno\Settings\SettingsPage;


/**
 * Renders menu pages
 */
class SubmenuPageHandler
{
    /** @var SettingsService $settingsService */
    private $settingsService;

    /**
     * SubmenuPageHandler constructor.
     *
     * @param SettingsService $settingsService
     */
    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService; 
    }

    /**
     * Submenu page render function
     *
     * @param $page
     */
    public function render($page)
    {
        //  echo '<pre>';
        //  print_r($page);
        //  echo '<br>.................';
        // print_r($this->settingsService);
        // exit;
        wp_enqueue_script('soopno_polyfill', 'https://polyfill.io/v2/polyfill.js?features=Intl.~locale.en');

        // Enqueue Scripts
        wp_enqueue_script(
            'soopno_chatting_scripts',
            get_site_url() . '/wp-content/plugins/soopno/assets/build/js/main.js', // get_stylesheet_directory_uri() . '/build/index.js',
            ['wp-element'],
            time(),
            true
        );
        // wp_enqueue_script(
        //     'soopno_chatting_scripts',
        //     SOOPNO_URL . 'assets/build/js/single.js',
        //     [],
        //     SOOPNO_VERSION
        // );

        if ($page === 'wpsoopno-locations' || $page === 'wpsoopno-settings') {
            $gmapApiKey = $this->settingsService->getSetting('general', 'gMapApiKey');

            wp_enqueue_script(
                'google_maps_api',
                "https://maps.googleapis.com/maps/api/js?key={$gmapApiKey}&libraries=places"
            );
        }


        // Enqueue Styles
        // wp_enqueue_style(
        //     'soopno_chatting_styles',
        //     SOOPNO_URL . 'public/css/backend/amelia-booking.css',
        //     [],
        //     SOOPNO_VERSION
        // );

        // WordPress enqueue
        wp_enqueue_media();

        wp_localize_script(
            'soopno_chatting_scripts',
            'useWindowVueInAmelia',
            [$this->settingsService->getSetting('general', 'useWindowVueInAmeliaBack') ? '1' : '0']
        );

        // Strings Localization
        switch ($page) {
            case ('wpsoopno-dashboard'):
                wp_localize_script(
                    'soopno_chatting_scripts',
                    'wpAmeliaLabels',
                    array_merge(
                        BackendStrings::getEntityFormStrings(),
                        BackendStrings::getLocationStrings(),
                        BackendStrings::getCommonStrings()
                    )
                );
                break; 
            case ('wpsoopno-livechat'):
                wp_localize_script(
                    'soopno_chatting_scripts',
                    'wpAmeliaLabels',
                    array_merge(
                        BackendStrings::getEntityFormStrings(),
                        BackendStrings::getLocationStrings(),
                        BackendStrings::getCommonStrings()
                    )
                );
                include SOOPNO_PATH . '/view/backend/view.php';

                break; 
            case ('wpsoopno-settings'):
                SettingsPage::render();
                break; 
           
        }

        // Settings Localization
        wp_localize_script(
            'soopno_chatting_scripts',
            'wpSoopnoSettings',
            $this->settingsService->getFrontendSettings()
        );

        wp_localize_script(
            'soopno_chatting_scripts',
            'localeLanguage',
            [SOOPNO_LOCALE]
        );

        
    }
}