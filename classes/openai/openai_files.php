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

use CURLFile;
use moodle_exception;

/**
 * Class openai_files
 *
 * This class provides methods for handling file operations with the OpenAI API.
 *
 * @see         https://platform.openai.com/docs/api-reference/files
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_files extends openai_client {

    /**
     * Uploads a moodle resource to the OpenAI API.
     *
     * @see https://platform.openai.com/docs/api-reference/files/create
     * @param int $moodleresourceid The ID of the Moodle resource to be uploaded.
     * @param string $purpose The purpose of the file upload. Can be 'fine-tune' or 'assistants'.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the purpose is not 'fine-tune' or 'assistants', or if there is an error during the upload
     *         process.
     */
    public function upload_moodle_resource(int $moodleresourceid, string $purpose = 'assistants'): array {

        if (!in_array($purpose, ['fine-tune', 'assistants'])) {
            throw new moodle_exception('error:openai_client', 'local_ai_connector', '', '', 'Invalid file upload purpose');
        }

        return $this->client_upload_moodle_resource('/files', $moodleresourceid, ['purpose' => $purpose]);
    }

    /**
     * Uploads a file to the OpenAI API.
     *
     * @see https://platform.openai.com/docs/api-reference/files/create
     * @param CURLFile $curlfile The file to be uploaded.
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the upload process.
     */
    public function upload_file(CURLFile $curlfile, array $data): array {
        return $this->client_upload_file('/files', $curlfile, $data);
    }

    /**
     * Lists all files in the OpenAI API.
     *
     * @see https://platform.openai.com/docs/api-reference/files/list
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the retrieval process.
     */
    public function list(): array {
        return $this->client('/files');
    }

    /**
     * Retrieves a file from the OpenAI API.
     *
     * @see https://platform.openai.com/docs/api-reference/files/retrieve
     * @param string $openaifileid The ID of the file to be retrieved.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the retrieval process.
     */
    public function retrieve(string $openaifileid): array {
        return $this->client('/files/' . $openaifileid);
    }

    /**
     * Deletes a file from the OpenAI API.
     *
     * @see https://platform.openai.com/docs/api-reference/files/delete
     * @param string $openaifileid The ID of the file to be deleted.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the deletion process.
     */
    public function delete(string $openaifileid): array {
        return $this->client('/files/' . $openaifileid, 'delete');
    }

    /**
     * Retrieves the content of a file from the OpenAI API.
     *
     * @see https://platform.openai.com/docs/api-reference/files/retrieve-contents
     * @param string $openaifileid The ID of the file whose content is to be retrieved.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the retrieval process.
     */
    public function retrieve_content(string $openaifileid): array {
        return $this->client('/files/' . $openaifileid . '/content');
    }
}
