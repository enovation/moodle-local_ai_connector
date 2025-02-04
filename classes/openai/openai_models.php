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
 * Class openai_models
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to list, retrieve, and delete fine-tuned models.
 *
 * @see         https://platform.openai.com/docs/api-reference/models
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_models extends openai_client {

    /**
     * List all models.
     *
     * @see https://platform.openai.com/docs/api-reference/models/list
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list(): array {
        return $this->client('/models');
    }

    /**
     * Retrieve a model.
     *
     * @see https://platform.openai.com/docs/api-reference/models/retrieve
     * @param string $openaimodelid The ID of the model.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $openaimodelid): array {
        return $this->client('/models/' . $openaimodelid);
    }

    /**
     * Delete fine-tuned model.
     *
     * @see https://platform.openai.com/docs/api-reference/models/delete
     * @param string $openaimodelid The ID of the model.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception
     */
    public function delete(string $openaimodelid): array {
        return $this->client('/models/' . $openaimodelid, 'DELETE');
    }
}
