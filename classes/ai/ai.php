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
/**
 * AI class
 *
 * @package    local_ai_connect
 * @copyright  2023 Enovation
 * @author Olgierd Dziminski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_ai_connector\ai;

use curl;
use moodle_exception;

class ai {
    const OPENAI_CHATGPT_CHAT_ENDPOINT = 'https://api.openai.com/v1/chat/completions';
    const OPENAI_CHATGPT_COMPLETION_ENDPOINT = 'https://api.openai.com/v1/completions';
    const DALLE_IMAGES_EDIT_ENDPOINT = 'https://api.openai.com/v1/images/edits';
    const DALLE_IMAGES_GENERATION_ENDPOINT = 'https://api.openai.com/v1/images/generations';
    const DALLE_IMAGES_VARIATIONS_ENDPOINT = 'https://api.openai.com/v1/images/variations';
    const STABLE_DIFFUSION_ENDPOINT = 'https://api.deepai.org/api/stable-diffusion';

    private string $openaiapikey;

    private string $deepaiapikey;

    private $model;
    private float $temperature;

    public function __construct() {
        $this->openaiapikey = get_config('local_ai_connector', 'openaiapikey');
        $this->deepaiapikey = get_config('local_ai_connector', 'deepaiapikey');
        $this->model = get_config('local_ai_connector', 'model');
        $this->temperature = get_config('local_ai_connector', 'temperature', 0.5);
    }


    /**
     * Makes a request to the specified URL with the given data and API key.
     *
     * @param string $url The URL to make the request to.
     * @param array $data The data to send with the request.
     * @param string $apikey The API key to authenticate the request.
     * @return array The response from the request.
     * @throws moodle_exception If the API key is empty.
     */
    private function make_request($url, $data, $apikey, $multipart = null) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        if (empty($apikey)) {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null,
                'Empty API Key.');
        }
        $headers = $multipart ? [
            "Content-Type: multipart/form-data"
        ] : [
            "Content-Type: application/json;charset=utf-8"
        ];

        $headers[] = "Authorization: Bearer $apikey";
        $curl = new curl();
        $options = [
            "CURLOPT_RETURNTRANSFER" => true,
            "CURLOPT_HTTPHEADER" => $headers,
        ];
        $start = microtime(true);

        $response = $curl->post($url, json_encode($data), $options);

        $end = microtime(true);
        $executiontime = round($end - $start, 2);

        if (json_decode($response) == null) {
            return ['curl_error' => $response, 'execution_time' => $executiontime];
        }
        return ['response' => json_decode($response, true), 'execution_time' => $executiontime];
    }

    /**
     * Generates a completion for the given prompt text.
     *
     * @param string $prompttext The prompt text.
     * @return string|array The generated completion or null if the model is empty.
     * @throws moodle_exception If the model is empty.
     */
    public function prompt_completion($prompttext) {
        if (empty($this->model)) {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null, 'Empty query model.');
        }
        $url = $this->get_prompt_url($this->model);
        $data = $this->get_prompt_data($url, $prompttext);
        $result = $this->make_request($url, $data, $this->openaiapikey);

        if (isset($result['choices'][0]['text'])) {
            return $result['choices'][0]['text'];
        } else if (isset($result['choices'][0]['message'])) {
            return $result['choices'][0]['message'];
        } else {
            return $result;
        }
    }

    /**
     * Retrieves the appropriate prompt URL based on the model.
     *
     * @param string $model The model name.
     * @return string The prompt URL.
     */
    private function get_prompt_url($model) : string {
        $chatcompletionmodels = ["gpt-4", "gpt-4-0314", "gpt-4-32k", "gpt-4-32k-0314", "gpt-3.5-turbo", "gpt-3.5-turbo-0301"];

        if (in_array($model, $chatcompletionmodels)) {
            return self::OPENAI_CHATGPT_CHAT_ENDPOINT;
        } else {
            return self::OPENAI_CHATGPT_COMPLETION_ENDPOINT;
        }
    }

    /**
     * Retrieves the data for the prompt based on the URL and prompt text.
     *
     * @param string $url The prompt URL.
     * @param string $prompttext The prompt text.
     * @return array The prompt data.
     */
    private function get_prompt_data($url, $prompttext) : array {
        if ($url == self::OPENAI_CHATGPT_CHAT_ENDPOINT) {
            $data = [
                'model' => $this->model,
                'temperature' => $this->temperature,
                'messages' => [
                    ['role' => 'system', 'content' => 'You: ' . $prompttext],
                ],
            ];
        } else {
            $data = [
                'model' => $this->model,
                'temperature' => $this->temperature,
                'prompt' => $prompttext,
            ];
        }
        return $data;
    }

    /**
     * Generates a response for the prompt text.
     *
     * @param string $prompttext The prompt text.
     * @param int $n The number of responses to generate. Default is 1.
     * @return string|array|null The generated response or null if the result is not available.
     */
    
    public function prompt_dalle_generation($prompttext, $n = 1) {
        $data = [
            'prompt' => $prompttext,
            'size' => "256x256",
            'n' => $n
        ];
        $url = self::DALLE_IMAGES_GENERATION_ENDPOINT;

        $result = $this->make_request($url, json_decode(json_encode($data)), $this->openaiapikey);
        if (isset($result)) {
            if (isset($result['data'])) {
                return $result['data'][0]['url'];
            } else {
                return $result['error'] ?? $result;
            }
        }
    }
    /**
     * Generates a response for the prompt text and optional image.
     *
     * @param string $prompttext The prompt text.
     * @param mixed|null $image The image.
     * @param mixed|null $mask The mask for editing the image. Default is null.
     * @param string $size The size of the generated image. Default is '256x256'.
     * @param int $n The number of responses to generate. Default is 1.
     * @return string|array|null The generated response or null if the result is not available.
     */

    public function prompt_dalle_edit($prompttext, $image, $mask = null, $size = '256x256', $n = 1) {
        $headers = [
            "Content-Type: multipart/form-data"
        ];
        $headers[] = "Authorization: Bearer $this->openaiapikey";
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => [
                'prompt' => $prompttext,
                'size' => $size,
                'image' => curl_file_create($image),
            ]
        ];
        $curl = curl_init(self::DALLE_IMAGES_EDIT_ENDPOINT);

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        return $response;
    }
    /**
     * Generates a variation of a given image.
     *
     * @param string $prompttext The prompt text.
     * @param mixed|null $image The image.
     * @param string $size The size of the generated image. Default is '256x256'.
     * @param int $n The number of responses to generate. Default is 1.
     * @return string|array|null The generated response or null if the result is not available.
     */

    public function prompt_dalle_variations($image, $size = '256x256', $n = 1) {
        $headers = [
            "Content-Type: multipart/form-data"
        ];
var_dump(43242);
        $headers[] = "Authorization: Bearer $this->openaiapikey";
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => [
                'size' => $size,
                'image' => curl_file_create($image),
            ]
        ];
        $curl = curl_init(self::DALLE_IMAGES_VARIATIONS_ENDPOINT);

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        return json_decode($response, true);
    }

    /**
     * Performs stable diffusion with the given prompt text.
     *
     * @param string $prompttext The prompt text.
     * @return string|array The stable diffusion result.
     * @throws moodle_exception If the deep AI key is empty.
     */
    public function prompt_stable_diffusion($prompttext) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        if (empty($this->deepaiapikey)) {
            return ['noapikey' => 'DeepAI\'s API key has not been set.', 'execution_time' => '-'];
        }
        $curl = new curl();

        $curl->setHeader([
            'api-key: ' . $this->deepaiapikey,
        ]);
        $curl->setOpt(CURLOPT_RETURNTRANSFER);
        $start = microtime(true);

        $result = $curl->post(self::STABLE_DIFFUSION_ENDPOINT, ['text' => $prompttext]);

        $end = microtime(true);
        $executiontime = round($end - $start, 2);

        if (json_decode($result) == null) {
            return ['curl_error' => $result, 'execution_time' => $executiontime];
        }

        return ['response' => json_decode($result, true), 'execution_time' => $executiontime];
    }

}

