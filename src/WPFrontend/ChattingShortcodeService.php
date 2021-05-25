<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\WPFrontend;

/**
 * Class ChattingShortcodeService
 *
 * @package Soopno\WPFrontend
 */
class ChattingShortcodeService extends FrontendPageHandler
{
    /**
     * @return string
     */
    public static function shortcodeHandler($atts)
    {
        $atts = shortcode_atts(
            [
                'trigger'  => '',
                'show'     => '',
                'category' => null,
                'service'  => null,
                'employee' => null,
                'location' => null,
                'counter'  => self::$counter
            ],
            $atts
        );

        self::prepareScriptsAndStyles();

        ob_start();
        include SOOPNO_PATH . '/view/frontend/booking.inc.php';
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
