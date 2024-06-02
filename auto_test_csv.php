<?php

/**
* This file is part of Cria.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
*
* @package    local_cria
* @author     Patrick Thibaudeau
* @copyright  2024 onwards York University (https://yorku.ca)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


require_once('../../config.php');
use local_cria\base;

global $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

$context = context_system::instance();

require_login(1, false);
$bot_id = required_param('bot_id', PARAM_INT);
base::page(
    new moodle_url('/local/cria/auto_test_csv.php', ['bot_id' => $bot_id]),
    get_string('pluginname', 'local_cria'),
    '',
    $context
);

echo $OUTPUT->header();
$bot_id = required_param('bot_id', PARAM_INT);
$session_data = json_decode($_SESSION['data-' . $bot_id]);
$data = [];
foreach ($session_data as $row) {
    $answer = str_replace("\u2022", " ", $row->answer);
    $answer = str_replace("\r\n", " ", $answer);
    $answer = str_replace("\n", " ", $answer);

    $data[] = [
        'Question' => $row->question,
        'Response'=> strip_tags($row->response),
        'Answer' => $answer
    ];
}
echo 'WTYY?';
echo '<div class="alert alert-primary" role="alert">
    <h4 class="alert-heading">Convert JSON to Excel</h4>
    <p>The JSON is available below. Press the Copy to clipboard button. <br>Then click Convert to Excel.
    That will open a new tab. <br>Paste the JSON into the text area and click CONVERT (Big green button.)
    <br>Then click teh DOWNLOAD NOW button.
    </p>
    </div>';

echo '<div class="json-container">';
echo '<pre>';
echo json_encode($data, JSON_PRETTY_PRINT);
echo '</pre>';
echo '</div>';
// Add javscript to copy the json to the clipboard
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>';
echo '<script>
    var copyButton = document.createElement("button");
    copyButton.innerHTML = "Copy to clipboard";
    copyButton.classList.add("btn");
    copyButton.classList.add("btn-primary");
    copyButton.style = "margin-top: 10px;";
    copyButton.addEventListener("click", function() {
        var json = document.querySelector(".json-container pre").innerText;
        var temp = document.createElement("textarea");
        temp.value = json;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand("copy");
        document.body.removeChild(temp);
    });
    document.querySelector(".json-container").appendChild(copyButton);
</script>';

// URL to convert the data
echo '<a href="https://products.aspose.app/cells/conversion/json-to-xlsx" class="mt-2 btn btn-success" target="_blank">Convert to Excel</a>';

echo $OUTPUT->footer();