<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\Entity\Settings;

/**
 * Class Settings
 *
 * @package Soopno\Entity\Settings
 */
class Settings
{
    /** @var GeneralSettings */
    private $generalSettings;

    /** @var PaymentSettings */
    private $paymentSettings;

    /** @var ZoomSettings */
    private $zoomSettings;

    /**
     * @return GeneralSettings
     */
    public function getGeneralSettings()
    {
        return $this->generalSettings;
    }

    /**
     * @param GeneralSettings $generalSettings
     */
    public function setGeneralSettings($generalSettings)
    {
        $this->generalSettings = $generalSettings;
    }

    /**
     * @return PaymentSettings
     */
    public function getPaymentSettings()
    {
        return $this->paymentSettings;
    }

    /**
     * @param PaymentSettings $paymentSettings
     */
    public function setPaymentSettings($paymentSettings)
    {
        $this->paymentSettings = $paymentSettings;
    }

    /**
     * @return ZoomSettings
     */
    public function getZoomSettings()
    {
        return $this->zoomSettings;
    }

    /**
     * @param ZoomSettings $zoomSettings
     */
    public function setZoomSettings($zoomSettings)
    {
        $this->zoomSettings = $zoomSettings;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'general'  => $this->getGeneralSettings() ? $this->getGeneralSettings()->toArray() : null,
            'payments' => $this->getPaymentSettings() ? $this->getPaymentSettings()->toArray() : null,
        ];
    }
}
