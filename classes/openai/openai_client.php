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
namespace local_ai_connector\openai;

use context_module;
use curl;
use CURLFile;
use moodle_exception;
use stored_file;

/**
 * Class openai_client
 *
 * This class provides methods for interacting with the OpenAI API.
 *
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_client {

    /**
     * @var string The OpenAI API key.
     */
    private string $openaiapikey;

    /**
     * @var string The OpenAI API endpoint.
     */
    private string $endpoint;

    /**
     * Initializes the OpenAI API key and endpoint.
     */
    public function __construct() {
        $this->openaiapikey = get_config('local_ai_connector', 'openaiapikey');
        $this->endpoint = "https://api.openai.com/v1";
    }

    /**
     * Sends a request to the OpenAI API and returns the response as an array.
     *
     * @param string $path The path of the API endpoint.
     * @param string $method The HTTP method to use.
     * @param array|null $data The data to send with the request.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the HTTP method is not supported or if the response from the OpenAI API is invalid.
     */
    protected function client(string $path, string $method = 'GET', array $data = null): array {
        $response = $this->call($path, $method, $data);

        if (json_decode($response) == null) {
            throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'Invalid response from OpenAI');
        }

        return json_decode($response, true);
    }

    /**
     * Makes a call to the OpenAI API.
     *
     * @param string $path The path of the API endpoint.
     * @param string $method The HTTP method to use. Default is 'GET'.
     * @param array|null $data The data to send with the request. Default is null.
     * @return string The response from the OpenAI API.
     * @throws moodle_exception If the HTTP method is not supported, if there is a curl error, or if the response from the OpenAI
     *         API is empty.
     */
    private function call(string $path, string $method = 'GET', array $data = null): string {
        $curl = new curl();
        $curl->setHeader([
                "Authorization: Bearer $this->openaiapikey",
                "Content-Type: application/json;charset=utf-8",
                "OpenAI-Beta: assistants=v2",
        ]);

        if ($data !== null) {
            $data = json_encode($data);
        }

        switch (strtoupper($method)):
            case 'GET':
                $response = $curl->get($this->endpoint . $path, $data);
                break;
            case 'POST':
                $response = $curl->post($this->endpoint . $path, $data);
                break;
            case 'DELETE':
                $response = $curl->delete($this->endpoint . $path);
                break;
            case 'PUT':
                $response = $curl->put($this->endpoint . $path);
                break;
            default:
                throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'Method not supported');
        endswitch;

        if ($curl->error) {
            throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'cURL error: ' . $curl->error);
        }

        if (empty($response)) {
            throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'Empty response from OpenAI');
        }

        return $response;
    }

    /**
     * Sends a request to the OpenAI API and returns the response as a file content or array if error.
     *
     * @param string $path The path of the API endpoint.
     * @param string $method The HTTP method to use.
     * @param array|null $data The data to send with the request.
     * @return string | array The response from the OpenAI API.
     * @throws moodle_exception If the HTTP method is not supported or if the response from the OpenAI API is invalid.
     */
    protected function client_returns_file(string $path, string $method = 'GET', array $data = null) {
        $response = $this->call($path, $method, $data);

        if (json_decode($response)) {
            return json_decode($response, true);
        }

        return $response;
    }

    /**
     * Uploads a Moodle resource to the OpenAI API.
     *
     * @param string $path The path of the API endpoint.
     * @param int $moodleresourceid The ID of the Moodle resource.
     * @param array|null $data The data to send with the request.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception
     */
    protected function client_upload_moodle_resource(string $path, int $moodleresourceid, array $data = null): array {
        $file = $this->get_moodle_resource($moodleresourceid);

        $tempfile = tempnam(sys_get_temp_dir(), 'ai');
        $file->copy_content_to($tempfile);
        $curlfile = new CURLFile($tempfile, $file->get_mimetype(), $file->get_filename());

        $response = $this->client_upload_file($path, $curlfile, $data);

        unlink($tempfile);

        return $response;
    }

    /**
     * Retrieves a resource from Moodle.
     *
     * @param int $moodleresourceid
     * @return stored_file
     * @throws moodle_exception If the resource cannot be found.
     */
    private function get_moodle_resource(int $moodleresourceid): stored_file {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        if (!$cm = get_coursemodule_from_id('resource', $moodleresourceid)) {
            throw new moodle_exception('error:file_not_found', 'local_ai_connector', '', '', 'Invalid file ID');
        }

        $context = context_module::instance($cm->id);
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'mod_resource', 'content', 0, 'sortorder DESC, id ASC', false);
        if (count($files) < 1) {
            throw new moodle_exception('error:file_not_found', 'local_ai_connector', '', '', 'File not found in database');
        } else {
            $file = reset($files);
        }

        return $file;
    }

    /**
     * Uploads a file to the OpenAI API.
     *
     * @param string $path The path of the API endpoint.
     * @param CURLFile $file The file to upload.
     * @param array|null $data The data to send with the request.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is a cURL error or if the response from the OpenAI API is invalid.
     */
    protected function client_upload_file(string $path, CURLFile $file, ?array $data = null): array {
        $curl = new curl();

        $curl->setHeader([
                "Authorization: Bearer $this->openaiapikey",
                "Content-Type: multipart/form-data",
        ]);

        $postfields = ['file' => $file];
        if ($data !== null) {
            $postfields = array_merge($postfields, $data);
        }

        $response = $curl->post($this->endpoint . $path, $postfields);

        if ($curl->error) {
            throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'cURL error: ' . $curl->error);
        }

        if (json_decode($response) == null) {
            throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'Invalid response from OpenAI');
        }

        return json_decode($response, true);
    }
}
