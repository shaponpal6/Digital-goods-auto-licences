<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\Services\Location;

/**
 * Class CurrentLocation
 *
 * @package Soopno\Services\Location
 */
class CurrentLocation
{
    /**
     * Get country ISO code by public IP address
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getCurrentLocationCountryIso()
    {
        try {
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, 'https://www.iplocate.io/api/lookup/' . $_SERVER['REMOTE_ADDR']);
            curl_setopt($curlHandle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_USERAGENT, 'Amelia');
            $result = json_decode(curl_exec($curlHandle));
            curl_close($curlHandle);

            return !isset($result->country_code) ? '' : strtolower($result->country_code);
        } catch (\Exception $e) {
            return '';
        }
    }
}
