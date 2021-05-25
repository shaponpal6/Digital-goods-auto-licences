<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\Entity\Settings;

/**
 * Class ZoomSettings
 *
 * @package Soopno\Entity\Settings
 */
class ZoomSettings
{
    /** @var bool */
    private $enabled;

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'enabled'   => $this->getEnabled(),
        ];
    }
}
