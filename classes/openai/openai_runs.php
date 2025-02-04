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
 * Class openai_runs
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to list, retrieve, modify, submit tool outputs, and cancel runs and their steps.
 *
 * @see         https://platform.openai.com/docs/api-reference/runs
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_runs extends openai_client {

    /**
     * @var string The ID of the thread.
     */
    private string $openaithreadid;

    /**
     * openai_runs constructor.
     *
     * @param string $openaithreadid The ID of the thread.
     */
    public function __construct(string $openaithreadid) {
        parent::__construct();
        $this->openaithreadid = $openaithreadid;
    }

    /**
     * Creates a run
     *
     * @see https://platform.openai.com/docs/api-reference/runs/createRun
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function create(array $data): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs', 'POST', $data);
    }

    /**
     * List all runs.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/listRuns
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list(?array $data = null): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs', 'GET', $data);
    }

    /**
     * List all steps of a run.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/listRunSteps
     * @param string $openairunid The ID of the run.
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function list_steps(string $openairunid, ?array $data = null): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs/' . $openairunid . '/steps', 'GET', $data);
    }

    /**
     * Retrieve a run.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/getRun
     * @param string $openairunid The ID of the run.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve(string $openairunid): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs/' . $openairunid);
    }

    /**
     * Retrieve a step of a run.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/getRunStep
     * @param string $openairunid The ID of the run.
     * @param string $openaistepid The ID of the step.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function retrieve_step(string $openairunid, string $openaistepid): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs/' . $openairunid . '/steps/' . $openaistepid);
    }

    /**
     * Modify a run.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/modifyRun
     * @param string $openairunid The ID of the run.
     * @param array|null $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function modify(string $openairunid, ?array $data = null): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs/' . $openairunid, 'POST', $data);
    }

    /**
     * Submit tool outputs for a run.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/submitToolOutputs
     * @param string $openairunid The ID of the run.
     * @param array $data The data to be sent as the request body.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function submit_tool_outputs(string $openairunid, array $data): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs/' . $openairunid . '/submit_tool_outputs', 'POST',
                $data);
    }

    /**
     * Cancel a run.
     *
     * @see https://platform.openai.com/docs/api-reference/runs/cancelRun
     * @param string $openairunid The ID of the run.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If the request fails.
     */
    public function cancel(string $openairunid): array {
        return $this->client('/threads/' . $this->openaithreadid . '/runs/' . $openairunid . '/cancel', 'POST');
    }
}
