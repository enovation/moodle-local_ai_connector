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
 * Class openai_messages
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create, retrieve, modify, and list messages and their files.
 *
 * @see         https://platform.openai.com/docs/api-reference/messages
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_messages extends openai_client {

    /**
     * @var string The ID of the thread.
     */
    private string $openaithreadid;

    /**
     * openai_messages constructor.
     *
     * @param string $openaithreadid The ID of the thread.
     */
    public function __construct(string $openaithreadid) {
        parent::__construct();
        $this->openaithreadid = $openaithreadid;
    }

    /**
     * Create a new message.
     *
     * @see https://platform.openai.com/docs/api-reference/messages/createMessage
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function create(array $data): array {
        return $this->client('/threads/' . $this->openaithreadid . '/messages', 'POST', $data);
    }

    /**
     * List all messages.
     *
     * @see https://platform.openai.com/docs/api-reference/messages/listMessages
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list(?array $data = null): array {
        return $this->client('/threads/' . $this->openaithreadid . '/messages', 'GET', $data);
    }

    /**
     * List all files of a message.
     *
     * @see https://platform.openai.com/docs/api-reference/messages/listMessageFiles
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list_files(?array $data = null): array {
        return $this->client('/threads/' . $this->openaithreadid . '/messages/files', 'GET', $data);
    }

    /**
     * Retrieve a message.
     *
     * @see https://platform.openai.com/docs/api-reference/messages/getMessage
     * @param string $openaimessageid The ID of the message.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $openaimessageid): array {
        return $this->client('/threads/' . $this->openaithreadid . '/messages/' . $openaimessageid);
    }

    /**
     * Retrieve a file of a message.
     *
     * @see https://platform.openai.com/docs/api-reference/messages/getMessageFile
     * @param string $openai_message_id The ID of the message.
     * @param string $openaifileid The ID of the file.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve_file(string $openaimessageid, string $openaifileid): array {
        return $this->client('/threads/' . $this->openaithreadid . '/messages/' . $openaimessageid . '/files/' .
                $openaifileid);
    }

    /**
     * Modify a message.
     *
     * @see https://platform.openai.com/docs/api-reference/messages/modifyMessage
     * @param string $openaimessageid The ID of the message.
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function modify(string $openaimessageid, ?array $data = null): array {
        return $this->client('/threads/' . $this->openaithreadid . '/messages/' . $openaimessageid, 'POST', $data);
    }
}
