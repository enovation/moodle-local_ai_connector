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
 * Class openai_threads
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create, retrieve, modify, delete, and run threads.
 *
 * @see         https://platform.openai.com/docs/api-reference/threads
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_threads extends openai_client {

    /**
     * Create a new thread.
     *
     * @see https://platform.openai.com/docs/api-reference/threads/createThread
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function create(): array {
        return $this->client('/threads', 'POST');
    }

    /**
     * Retrieve a thread.
     *
     * @see https://platform.openai.com/docs/api-reference/threads/getThread
     * @param string $openaithreadid The ID of the thread.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $openaithreadid): array {
        return $this->client('/threads/' . $openaithreadid);
    }

    /**
     * Modify a thread.
     *
     * @see https://platform.openai.com/docs/api-reference/threads/modifyThread
     * @param string $openaithreadid The ID of the thread.
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function modify(string $openaithreadid, array $data): array {
        return $this->client('/threads/' . $openaithreadid, 'POST', $data);
    }

    /**
     * Delete a thread.
     *
     * @see https://platform.openai.com/docs/api-reference/threads/deleteThread
     * @param string $openaithreadid The ID of the thread.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function delete(string $openaithreadid): array {
        return $this->client('/threads/' . $openaithreadid, 'DELETE');
    }

    /**
     * Creates and runs a new thread.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/createThreadAndRun
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function runs(array $data): array {
        return $this->client('/threads/runs', 'POST', $data);
    }
}
