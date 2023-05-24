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
 * Test page
 *
 * @package    local_ai_connect
 * @copyright  2023 Enovation
 * @author Olgierd Dziminski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_ai_connector;

require_once(__DIR__ . '/../../config.php');

use context_system;

require_login();
if (!is_siteadmin($USER)) {
    throw new require_login_exception();
}
$PAGE->set_context(context_system::instance());

defined('MOODLE_INTERNAL') || die();
global $CFG, $PAGE;

$ai = new ai\ai();
$gptresult = $ai->prompt_completion('Explain me quantum physics like I am five.');

// Check $gptresult.
$dalletest = $ai->prompt_dalle('angry goose');
$stablediffusiontest = $ai->prompt_stable_diffusion('Happy chihuahas');

$services = [
    'GPT' => (!isset($gptresult['error'])) ? 'Active' : 'Inactive',
    'DALL-E' => isset($dalletest) ? 'Active' : 'Inactive',
    'Stable_Diffusion' => isset($stablediffusiontest) ? 'Active' : 'Inactive'
];


$PAGE->set_url('/local/ai_connector/classes/ai/test.php');

echo $OUTPUT->header();
echo "GPT status: " . $services['GPT'] . "</br>";
echo "DALL-E status: " . $services['DALL-E'] . "</br>";
echo "Stable Diffusion status: " . $services['Stable_Diffusion'] . "</br>";
echo $OUTPUT->footer();
