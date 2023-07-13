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
$result = $ai->prompt_dalle_edit('baby otter','1.png');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (ob_get_contents()) ob_end_clean();
    $tmp_file = $_FILES['image']['tmp_name'];
    $file_name = basename($_FILES['image']['name']);
    $image = curl_file_create($tmp_file, $_FILES['image']['type'], $file_name);

    $tmp_file = $_FILES['mask']['tmp_name'];
    $file_name = basename($_FILES['mask']['name']);
    $mask = curl_file_create($tmp_file, $_FILES['mask']['type'], $file_name);
    var_dump($result); exit;
    echo $result;
}
?>
<h1>Edit Image</h1>
<form method="post" enctype="multipart/form-data">
    Select file to upload: <br>
    <label for="image">Image</label>
    <input type="file" name="image" id="image">
    <br>
    <label for="mask">Mask</label>
    <input type="file" name="mask" id="mask">
    <br>
    <input type="submit" value="Upload File" name="submit">
</form>
