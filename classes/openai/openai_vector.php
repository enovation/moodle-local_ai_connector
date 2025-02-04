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
 * Class openai_vector
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create, retrieve, modify, and delete vector stores.
 *
 * @see         https://platform.openai.com/docs/api-reference/vector-stores
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_vector extends openai_client {

    /**
     * Create a new vector store.
     *
     * @see https://platform.openai.com/docs/api-reference/vector-stores/create
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function create(array $data): array {
        return $this->client('/vector_stores', 'POST', $data);
    }

    /**
     * List all vector stores.
     *
     * @see https://platform.openai.com/docs/api-reference/vector-stores/list
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list(?array $data = null): array {
        return $this->client('/vector_stores', 'GET', $data);
    }

    /**
     * Retrieve a vector store.
     *
     * @see https://platform.openai.com/docs/api-reference/vector-stores/retrieve
     * @param string $vectorstoreid The ID of the vector store.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $vectorstoreid): array {
        return $this->client('/vector_stores/' . $vectorstoreid);
    }

    /**
     * Modify a vector store.
     *
     * @see https://platform.openai.com/docs/api-reference/vector-stores/modify
     * @param string $vectorstoreid The ID of the vector store.
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function modify(string $vectorstoreid, ?array $data = null): array {
        return $this->client('/vector_stores/' . $vectorstoreid, 'POST', $data);
    }

    /**
     * Delete a vector store.
     *
     * @see https://platform.openai.com/docs/api-reference/vector-stores/delete
     * @param string $vectorstoreid The ID of the vector store.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function delete(string $vectorstoreid): array {
        return $this->client('/vector_stores/' . $vectorstoreid, 'DELETE');
    }

}
