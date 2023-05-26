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
 * AI class
 *
 * @package    local_ai_connect
 * @copyright  2023 Enovation
 * @author Olgierd Dziminski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_ai_connector\privacy;

use core_privacy\local\metadata\collection;

class provider {
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link('lti_client', [
            'prompttext' => 'privacy:metadata:ai_connector:prompttext',
            'image' => 'privacy:metadata:ai_connector:image',
        ], 'privacy:metadata:ai_connector');

        return $collection;
    }
}
