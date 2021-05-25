<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\WPFrontend;

use Soopno\WPFrontend\FrontendPageHandler;

/**
 * Class ChattingShortcodeService
 *
 * @package Soopno\WPFrontend
 */
class ChattingPageService extends FrontendPageHandler
{
    /**
     * @return string
     */
    public static function chattingHandler()
    {
       
        self::prepareScriptsAndStyles();

        ob_start();
        include SOOPNO_PATH . '/view/frontend/chatting.php';
        $html = ob_get_contents();
        ob_end_clean();

        echo $html;
    }
}
