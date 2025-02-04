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
 * Class openai_images
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create images.
 *
 * @see         https://platform.openai.com/docs/api-reference/images
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_images extends openai_client {

    /**
     * Create a new image generation.
     *
     * @see https://platform.openai.com/docs/api-reference/images/create
     * @param array $data The data to be sent to the OpenAI API for image generation.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create(array $data): array {
        return $this->client('/images/generations', 'POST', $data);
    }

    /**
     * Create an edit from a Moodle resource.
     *
     * @see https://platform.openai.com/docs/api-reference/images/createEdit
     * @param int $moodleresourceid The ID of the Moodle resource to be used for the edit.
     * @param array $data The data to be sent to the OpenAI API for the edit.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_edit_from_moodle_resource(int $moodleresourceid, array $data): array {
        return $this->client_upload_moodle_resource('/images/edits', $moodleresourceid, $data);
    }

    /**
     * Create an edit from a CURLFile.
     *
     * @see https://platform.openai.com/docs/api-reference/images/createEdit
     * @param CURLFile $curlfile The CURLFile to be used for the edit.
     * @param array $data The data to be sent to the OpenAI API for the edit.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_edit(CURLFile $curlfile, array $data): array {
        return $this->client_upload_file('/images/edits', $curlfile, $data);
    }

    /**
     * Create a variation from a CURLFile.
     *
     * @see https://platform.openai.com/docs/api-reference/images/createVariation
     * @param CURLFile $curlfile The CURLFile to be used for the variation.
     * @param array $data The data to be sent to the OpenAI API for the variation.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_variation(CURLFile $curlfile, array $data): array {
        return $this->client_upload_file('/images/variations', $curlfile, $data);
    }

    /**
     * Create a variation from a Moodle resource.
     *
     * @see https://platform.openai.com/docs/api-reference/images/createVariation
     * @param int $moodleresourceid The ID of the Moodle resource to be used for the variation.
     * @param array $data The data to be sent to the OpenAI API for the variation.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_variation_from_moodle_resource(int $moodleresourceid, array $data): array {
        return $this->client_upload_moodle_resource('/images/variations', $moodleresourceid, $data);
    }
}
