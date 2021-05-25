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
class SettingsSections
{
  

    /**
     * @return array
     */
    public static function toArray()
    {
        return array(
                array(
                    'id' => 'mr_assistant_general_settings',
                    'title' => __('General', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_appearance_settings',
                    'title' => __('Appearance', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_admin_appearance',
                    'title' => __('Admin Style', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_response',
                    'title' => __('Response', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_template_settings',
                    'title' => __('Template', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_realtime_database',
                    'title' => __('Realtime Database', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_chatbot_settings',
                    'title' => __('Virtual Assistant', 'mr-assistant')
                ),
                array(
                    'id' => 'mr_assistant_advance_settings',
                    'title' => __('Advanced', 'mr-assistant')
                )
            );
    }
}