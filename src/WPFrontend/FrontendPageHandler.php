<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\WPFrontend;

// use AmeliaBooking\Application\Services\CustomField\CustomFieldApplicationService;
use Soopno\Settings\SettingsStorage;
use Soopno\Services\Settings\SettingsService;
use Soopno\Translations\FrontendStrings;

/**
 * Class FrontendPageHandler
 *
 * @package AmeliaBooking\Infrastructure\WP\ShortcodeService
 */
class FrontendPageHandler
{
    public static $counter = 0;

    /**
     * Prepare scripts and styles
     */
    public static function prepareScriptsAndStyles()
    {
        self::$counter++;

        $settingsService = new SettingsService(new SettingsStorage());

        // Enqueue Scripts
        if ($settingsService->getSetting('payments', 'payPal')['enabled'] === true) {
            wp_enqueue_script('amelia_paypal_script', 'https://www.paypalobjects.com/api/checkout.js');
        }

        wp_enqueue_script('amelia_polyfill', 'https://polyfill.io/v2/polyfill.js?features=Intl.~locale.en');

        // Fix for Divi theme.
        // Don't enqueue script if it's activated Divi Visual Page Builder
        if (empty($_GET['et_fb'])) {
            wp_enqueue_script(
                'soopno_chatting_scripts',
                SOOPNO_URL . 'public/js/frontend/soopno-chatting.js',
                [],
                SOOPNO_VERSION
            );
        }

        if ($settingsService->getSetting('payments', 'stripe')['enabled'] === true) {
            wp_enqueue_script('amelia_stripe_js', 'https://js.stripe.com/v3/');
        }

        // Enqueue Styles
        wp_enqueue_style(
            'amelia_booking_styles_vendor',
            AMELIA_URL . 'public/css/frontend/vendor.css',
            [],
            AMELIA_VERSION
        );

        wp_enqueue_style(
            'amelia_booking_styles',
            UPLOADS_URL . '/amelia/css/amelia-booking.' .
            $settingsService->getSetting('customization', 'hash') . '.css',
            [],
            AMELIA_VERSION
        );

        // Underscore
        wp_enqueue_script('undescore', includes_url('js') . '/underscore.min.js');

        // Strings Localization
        wp_localize_script(
            'amelia_booking_scripts',
            'wpAmeliaLabels',
            FrontendStrings::getAllStrings()
        );

        // Settings Localization
        wp_localize_script(
            'amelia_booking_scripts',
            'wpAmeliaSettings',
            $settingsService->getFrontendSettings()
        );

        // AJAX URLs
        wp_localize_script(
            'amelia_booking_scripts',
            'wpAmeliaUrls',
            [
                'wpAmeliaUseUploadsAmeliaPath' => UPLOADS_AMELIA_FILES_PATH_USE,
                'wpAmeliaPluginURL'     => AMELIA_URL,
                'wpAmeliaPluginAjaxURL' => AMELIA_ACTION_URL
            ]
        );

        wp_localize_script(
            'amelia_booking_scripts',
            'localeLanguage',
            [AMELIA_LOCALE]
        );

        $localeSubstitutes = $settingsService->getSetting('general', 'calendarLocaleSubstitutes');

        if (isset($localeSubstitutes[AMELIA_LOCALE])) {
            wp_localize_script(
                'amelia_booking_scripts',
                'ameliaCalendarLocale',
                [$localeSubstitutes[AMELIA_LOCALE]]
            );
        }

        wp_localize_script(
            'amelia_booking_scripts',
            'useWindowVueInAmelia',
            [$settingsService->getSetting('general', 'useWindowVueInAmelia') ? '1' : '0']
        );

        // wp_localize_script(
        //     'amelia_booking_scripts',
        //     'fileUploadExtensions',
        //     array_keys(CustomFieldApplicationService::$allowedUploadedFileExtensions)
        // );
    }
}
