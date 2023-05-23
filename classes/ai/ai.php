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
namespace local_ai_connector\ai;

use curl;
use moodle_exception;

class ai {
    const OPENAI_CHATGPT_CHAT_ENDPOINT = 'https://api.openai.com/v1/chat/completions';
    const OPENAI_CHATGPT_COMPLETION_ENDPOINT = 'https://api.openai.com/v1/completions';

    private string $openaiapikey;
    
    private string $deepaiapikey;
    
    private $model;
    private float $temperature;

    /**
     * @var string Last query error.
     */
    private ?string $error;


    public function __construct() {
        $this->openaiapikey = get_config('local_ai_connector', 'openaiapikey');
        $this->deepaiapikey = get_config('local_ai_connector', 'deepaiapikey');
        $this->model = get_config('local_ai_connector', 'model');
        $this->temperature = get_config('local_ai_connector', 'temperature', 0.5);
    }

    private function make_request($url, $data, $apikey) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        $this->error = null;

        if (empty($apikey)) {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null,
                'Empty API Key.');
        }

        $headers = [
            "Authorization: Bearer $apikey",
            "Content-Type: application/json;charset=utf-8"
        ];

        $curl = new curl();
        $options = [
            "CURLOPT_RETURNTRANSFER" => true,
            "CURLOPT_HTTPHEADER" => $headers,
        ];

        $response = $curl->post($url, json_encode($data), $options);

        // @TODO basic validation and check that the response is valid JSON
        return json_decode($response, true);
    }

/*
 *   -d '{
     "model": "gpt-3.5-turbo",
     "messages": [{"role": "user", "content": "Say this is a test!"}],
     "temperature": 0.7
   }'
 */
    public function prompt_completion($prompttext) {
        if (empty($this->model)) {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null, 'Empty query model.');
        }

        $data = [
            'model' => $this->model,
            'temperature' => $this->temperature,
            'prompt' => $prompttext,
        ];

        $result = $this->make_request($this::OPENAI_CHATGPT_COMPLETION_ENDPOINT, $data, $this->openaiapikey);
var_dump($result); die();

        // Check if error is there.
        if (isset($result['error'])) {

            return false;
        }

        if (isset($result['choices'])) {
            return $result['choices'][0]['text'];
        } else {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null,
                '[ChatGPT] Prompt error occurred. Check the API key you provided.');
        }
    }

    public function prompt_dalle($prompttext, $image = null) {
        $data = [
            'prompt' => $prompttext,
            'size' => "256x256" // TODO: Let users choose desired dimensions: 256x256, 512x512, or 1024x1024.
        ];

        if (isset($image)) {
            $data['image'] = $image;
            $url = "https://api.openai.com/v1/images/edits";
        } else {
            $url = "https://api.openai.com/v1/images/generations";
        }

        $result = $this->make_request($url, json_decode(json_encode($data)), $this->openaiapikey);

        if (isset($result['data'])) {
            return $result['data'][0]['url'];
        } else {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null,
                '[DALL-E] Prompt error occurred. Check the API key you provided.');
        }
    }

    public function prompt_stable_diffusion($prompttext) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        if (empty($this->deepaiapikey)) {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null,
                'Empty Stable Diffusion API key.');
        }

        $curl = new curl();

        $curl->setHeader([
            'api-key: ' . $this->deepaiapikey,
        ]);
        $curl->setOpt(CURLOPT_RETURNTRANSFER);
        $result = $curl->post('https://api.deepai.org/api/stable-diffusion', ['text' => $prompttext]);
        $result = json_decode($result);

        if (isset($result->output_url)) {
            return $result->output_url;
        } else {
            throw new moodle_exception('prompterror', 'local_ai_connector', '', null,
                '[Stable Diffusion] Prompt error occurred. Check the API key you provided.');
        }
    }

}
