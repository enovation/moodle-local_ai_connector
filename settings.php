<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Settings page
 *
 * @package    local_ai_connect
 * @copyright  2023 Enovation
 * @author Olgierd Dziminski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_ai_connector', get_string('pluginname', 'local_ai_connector'));

    // OpenAI.
    $name = new lang_string('openaisettings', 'local_ai_connector');
    $description = new lang_string('openaisettings_help', 'local_ai_connector');
    $settings->add(new admin_setting_heading('openaisettings', $name, $description));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/openaiapikey',
        get_string('openaiapikey', 'local_ai_connector'),
        get_string('openaiapikey_desc', 'local_ai_connector'),
        ''
    ));

    $ADMIN->add('localplugins', $settings);
}
