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
$dalleresult = $ai->prompt_dalle('angry goose');
$stablediffusionresult = $ai->prompt_stable_diffusion('Happy chihuahas');

if ($gptresult && !isset($gptresult['curl_error'])) {
    if (isset($gptresult['error'])) {
        $gptinfo = "Inactive 🔴</br> Error message: " . $gptresult['error']['message'] . "</br>";
        $gptinfo .= "Error type: " . $gptresult['error']['type'] . "</br>";
        $gptinfo .= "Param: " . $gptresult['error']['param'] . "</br>";
        $gptinfo .= "Code: " . $gptresult['error']['code'] . "</br>";
    } else {
        $gptinfo = "Active 🟢";
    }
} else {
    $gptinfo = "Inactive 🔴, cURL error: " . $gptresult['curl_error'];
}

if ($dalleresult && !isset($dalleresult['curl_error'])) {
    if (isset($dalleresult['error'])) {
        $dalleinfo = "Inactive 🔴, error message: " . $dalleresult['error'];
    } else {
        $dalleinfo = "Active 🟢</br>";
    }
} else {
    $dalleinfo = "Inactive 🔴, cURL error: " . $dalleresult['curl_error'];
}

if ($stablediffusionresult && !isset($stablediffusionresult->curl_error)) {
    if (isset($stablediffusionresult->status)) {
        $stablediffusioninfo = "Inactive 🔴, error message: " . $stablediffusionresult->status;
    } else {
        $stablediffusioninfo = "Active 🟢</br>";
    }
} else {
    $stablediffusioninfo = "Inactive 🔴, cURL error: " . $stablediffusionresult['curl_error'];
}


$PAGE->set_url('/local/ai_connector/classes/ai/test.php');

echo $OUTPUT->header();
?>

    <style>
        table {
            border: 1px solid black;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>

    <table>
        <tr>
            <th>GPT status</th>
            <td><?php echo $gptinfo; ?></td>
        </tr>
        <tr>
            <th>DALL-E status</th>
            <td><?php echo $dalleinfo; ?></td>
        </tr>
        <tr>
            <th>Stable Diffusion status</th>
            <td><?php echo $stablediffusioninfo; ?></td>
        </tr>
    </table>


<?php
echo $OUTPUT->footer();

