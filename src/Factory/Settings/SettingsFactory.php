<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace Soopno\Factory\Settings;

use Soopno\Entity\Settings\GeneralSettings;
use Soopno\Entity\Settings\Settings;
use Soopno\Entity\Settings\ZoomSettings;
use Soopno\ValueObjects\Json;

/**
 * Class SettingsFactory
 *
 * @package Soopno\Factory\Settings
 */
class SettingsFactory
{
    /**
     * @param Json  $entityJsonData
     * @param array $globalSettings
     *
     * @return Settings
     */
    public static function create($entityJsonData, $globalSettings)
    {
        $entitySettings = new Settings();
        $generalSettings = new GeneralSettings();
        $zoomSettings = new ZoomSettings();

        $data = $entityJsonData ? json_decode($entityJsonData->getValue(), true) : [];

        if (isset($data['general']['defaultAppointmentStatus'])) {
            $generalSettings->setDefaultAppointmentStatus($data['general']['defaultAppointmentStatus']);
        } else {
            $generalSettings->setDefaultAppointmentStatus($globalSettings['general']['defaultAppointmentStatus']);
        }

        if (isset($data['general']['minimumTimeRequirementPriorToBooking'])) {
            $generalSettings->setMinimumTimeRequirementPriorToBooking(
                $data['general']['minimumTimeRequirementPriorToBooking']
            );
        } else {
            $generalSettings->setMinimumTimeRequirementPriorToBooking(
                $globalSettings['general']['minimumTimeRequirementPriorToBooking']
            );
        }

        if (isset($data['general']['minimumTimeRequirementPriorToCanceling'])) {
            $generalSettings->setMinimumTimeRequirementPriorToCanceling(
                $data['general']['minimumTimeRequirementPriorToCanceling']
            );
        } else {
            $generalSettings->setMinimumTimeRequirementPriorToCanceling(
                $globalSettings['general']['minimumTimeRequirementPriorToCanceling']
            );
        }

        if (!empty($data['general']['numberOfDaysAvailableForBooking'])) {
            $generalSettings->setNumberOfDaysAvailableForBooking(
                $data['general']['numberOfDaysAvailableForBooking']
            );
        } else {
            $generalSettings->setNumberOfDaysAvailableForBooking(
                $globalSettings['general']['numberOfDaysAvailableForBooking']
            );
        }

        if (isset($data['zoom']['enabled'])) {
            $zoomSettings->setEnabled($data['zoom']['enabled']);
        } else {
            $zoomSettings->setEnabled($globalSettings['zoom']['enabled']);
        }

        $entitySettings->setGeneralSettings($generalSettings);
        $entitySettings->setZoomSettings($zoomSettings);

        return $entitySettings;
    }
}
