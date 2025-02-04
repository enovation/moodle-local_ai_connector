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
 * Class openai_fine_tuning
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create, list, retrieve, cancel and list events of fine-tuning jobs.
 *
 * @see         https://platform.openai.com/docs/api-reference/fine-tuning
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_fine_tuning extends openai_client {

    /**
     * Creates a fine-tuning job.
     *
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/create
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function create(array $data): array {
        return $this->client('/fine_tuning/jobs', 'POST', $data);
    }

    /**
     * Lists all fine-tuning jobs.
     *
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/list
     * @param array|null $data The data to be sent as the request body. Default is null.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list(?array $data = null): array {
        return $this->client('/fine_tuning/jobs', 'GET', $data);
    }

    /**
     * Lists all events of a specific fine-tuning job.
     *
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/list-events
     * @param string $openaifinetuningjobid The ID of the fine-tuning job.
     * @param array|null $data The data to be sent as the request body. Default is null.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list_events(string $openaifinetuningjobid, ?array $data = null): array {
        return $this->client('/fine_tuning/jobs/' . $openaifinetuningjobid . '/events', 'GET', $data);
    }

    /**
     * Retrieves a specific fine-tuning job.
     *
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/retrieve
     * @param string $openaifinetuningjobid The ID of the fine-tuning job.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $openaifinetuningjobid): array {
        return $this->client('/fine_tuning/jobs/' . $openaifinetuningjobid);
    }

    /**
     * Cancels a specific fine-tuning job.
     *
     * @see https://platform.openai.com/docs/api-reference/fine-tuning/cancel
     * @param string $openaifinetuningjobid The ID of the fine-tuning job.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function cancel(string $openaifinetuningjobid): array {
        return $this->client('/fine_tuning/jobs/' . $openaifinetuningjobid . '/cancel', 'POST');
    }
}
