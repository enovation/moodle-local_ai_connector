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

use moodle_exception;

/**
 * Class openai_assistants
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create, retrieve, modify, and delete assistants and their files.
 *
 * @see         https://platform.openai.com/docs/api-reference/assistants
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_assistants extends openai_client {

    /**
     * Create a new assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/createAssistant
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function create(array $data): array {
        return $this->client('/assistants', 'POST', $data);
    }

    /**
     * Add a file to an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/createAssistantFile
     * @param string $openaiassistantid The ID of the assistant.
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function add_file(string $openaiassistantid, array $data): array {
        return $this->client('/assistants/' . $openaiassistantid . '/files', 'POST', $data);
    }

    /**
     * List all assistants.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/listAssistants
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list(?array $data = null): array {
        return $this->client('/assistants', 'GET', $data);
    }

    /**
     * List all files of an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/listAssistantFiles
     * @param string $openaiassistantid The ID of the assistant.
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list_files(string $openaiassistantid, ?array $data = null): array {
        return $this->client('/assistants/' . $openaiassistantid . '/files', 'GET', $data);
    }

    /**
     * Retrieve an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/getAssistant
     * @param string $openaiassistantid The ID of the assistant.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $openaiassistantid): array {
        return $this->client('/assistants/' . $openaiassistantid);
    }

    /**
     * Retrieve a file of an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/getAssistantFile
     * @param string $openaiassistantid The ID of the assistant.
     * @param string $openaifileid The ID of the file.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve_file(string $openaiassistantid, string $openaifileid): array {
        return $this->client('/assistants/' . $openaiassistantid . '/files/' . $openaifileid);
    }

    /**
     * Modify an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/modifyAssistant
     * @param string $openaiassistantid The ID of the assistant.
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function modify(string $openaiassistantid, ?array $data = null): array {
        return $this->client('/assistants/' . $openaiassistantid, 'POST', $data);
    }

    /**
     * Delete an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/deleteAssistant
     * @param string $openaiassistantid The ID of the assistant.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function delete(string $openaiassistantid): array {
        return $this->client('/assistants/' . $openaiassistantid, 'DELETE');
    }

    /**
     * Delete a file of an assistant.
     *
     * @see https://platform.openai.com/docs/api-reference/assistants/deleteAssistantFile
     * @param string $openaiassistantid The ID of the assistant.
     * @param string $openaifileid The ID of the file.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function delete_file(string $openaiassistantid, string $openaifileid): array {
        return $this->client('/assistants/' . $openaiassistantid . '/files/' . $openaifileid, 'DELETE');
    }
}
