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
 * Class openai_audio
 *
 * This class extends the openai_client class and provides methods to interact with the OpenAI API.
 * It includes methods to create audio.
 *
 * @see         https://platform.openai.com/docs/api-reference/audio
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class openai_audio extends openai_client {

    /**
     * Creates a speech.
     *
     * @see https://platform.openai.com/docs/api-reference/audio/createSpeech
     * @param array $data The data to be sent as the request body.
     * @return string | array The response error array from the OpenAI API or string audio content.
     * @throws moodle_exception If the request fails.
     */
    public function create_speech(array $data) {
        return $this->client_returns_file('/audio/speech', 'POST', $data);
    }

    /**
     * Creates a transcription from a CURLFile.
     *
     * @see https://platform.openai.com/docs/api-reference/audio/createTranscription
     * @param CURLFile $curlfile The CURLFile to be used for the transcription.
     * @param array $data The data to be sent to the OpenAI API for the transcription.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_transcription(CURLFile $curlfile, array $data): array {
        return $this->client_upload_file('/audio/transcriptions', $curlfile, $data);
    }

    /**
     * Creates a transcription from a Moodle resource.
     *
     * @see https://platform.openai.com/docs/api-reference/audio/createTranscription
     * @param int $moodleresourceid The ID of the Moodle resource to be used for the transcription.
     * @param array $data The data to be sent to the OpenAI API for the transcription.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_transcription_from_moodle_resource(int $moodleresourceid, array $data): array {
        return $this->client_upload_moodle_resource('/audio/transcriptions', $moodleresourceid, $data);
    }

    /**
     * Create a translation from a CURLFile.
     *
     * @see https://platform.openai.com/docs/api-reference/audio/createTranslation
     * @param CURLFile $curlfile The CURLFile to be used for the translation.
     * @param array $data The data to be sent to the OpenAI API for the translation.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_translation(CURLFile $curlfile, array $data): array {
        return $this->client_upload_file('/audio/translations', $curlfile, $data);
    }

    /**
     * Create a translation from a Moodle resource.
     *
     * @see https://platform.openai.com/docs/api-reference/audio/createTranslation
     * @param int $moodleresourceid The ID of the Moodle resource to be used for the translation.
     * @param array $data The data to be sent to the OpenAI API for the translation.
     * @return array The response from the OpenAI API.
     * @throws moodle_exception If there is an error during the API call.
     */
    public function create_translation_from_moodle_resource(int $moodleresourceid, array $data): array {
        return $this->client_upload_moodle_resource('/audio/translations', $moodleresourceid, $data);
    }
}
