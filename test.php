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
 * Test page
 *
 * @package    local_ai_connect
 * @copyright  2023 Enovation
 * @author      Olgierd Dziminski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_ai_connector;
require_once(__DIR__ . '/../../config.php');

use context_system;
use local_ai_connector\openai\openai;
use moodle_exception;
defined('MOODLE_INTERNAL') || die();
global $USER, $PAGE;
require_login();
if (!is_siteadmin($USER)) {
    throw new require_login_exception();
}
$PAGE->set_context(context_system::instance());
defined('MOODLE_INTERNAL') || die();
$PAGE->set_url('/local/ai_connector/classes/ai/test.php');
echo $OUTPUT->header();

$openai = new openai();
$test = new LocalAIConnector($openai);

$results['data'][] = $test->assistants();
$results['data'][] = $test->audio();
$results['data'][] = $test->chat();
$results['data'][] = $test->embeddings();
$results['data'][] = $test->files();
$results['data'][] = $test->fine_tuning();
$results['data'][] = $test->images();
$results['data'][] = $test->messages();
$results['data'][] = $test->models();
$results['data'][] = $test->moderations();
$results['data'][] = $test->runs();
$results['data'][] = $test->threads();

echo $OUTPUT->render_from_template('local_ai_connector/test/openai', $results);
echo $OUTPUT->footer();

class LocalAIConnector {
    private $openai;

    public function __construct($openai) {
        $this->openai = $openai;
    }

    public function assistants(): array {
        return $this->perform_api_test('Assistants', function() {
            return $this->openai->assistants()->list();
        });
    }

    private function perform_api_test($endpoint, $callback): array {
        try {
            $response = call_user_func($callback);

            if (!$response) {
                throw new moodle_exception('No response');
            }

            if (isset($response['error'])) {
                throw new moodle_exception($response['error']['message']);
            }

            return [
                    'endpoint' => $endpoint,
                    'status' => '🟢',
                    'response' => json_encode($response, JSON_PRETTY_PRINT),
            ];
        } catch (moodle_exception $e) {
            return [
                    'endpoint' => $endpoint,
                    'status' => '🔴',
                    'response' => $e->getMessage(),
            ];
        }
    }

    public function audio(): array {
        return $this->perform_api_test('Audio', function() {
            return $this->openai->audio()->create_speech([
                    'model' => 'tts-1',
                    'input' => 'Hello, this is test message.',
                    'voice' => 'echo',
            ]);
        });
    }

    public function chat(): array {
        return $this->perform_api_test('Chat', function() {
            return $this->openai->chat()->create_completion([
                    'model' => 'gpt-4',
                    'messages' => [
                            [
                                    'role' => 'user',
                                    'content' => 'Hello, this is test message.',
                            ],
                    ],
            ]);
        });
    }

    public function embeddings(): array {
        return $this->perform_api_test('Embeddings', function() {
            return $this->openai->embeddings()->create([
                    'model' => 'text-embedding-ada-002',
                    'input' => 'Hello, this is test message.',
            ]);
        });
    }

    public function files(): array {
        return $this->perform_api_test('Files', function() {
            return $this->openai->files()->list();
        });
    }

    public function fine_tuning(): array {
        return $this->perform_api_test('Fine Tuning', function() {
            return $this->openai->fine_tuning()->list();
        });
    }

    public function images(): array {
        return $this->perform_api_test('Images', function() {
            return $this->openai->images()->create([
                    'model' => 'dall-e-3',
                    'prompt' => 'Small cat on the grass',
            ]);
        });
    }

    public function models(): array {
        return $this->perform_api_test('Models', function() {
            return $this->openai->models()->list();
        });
    }

    public function moderations(): array {
        return $this->perform_api_test('Moderations', function() {
            return $this->openai->moderations()->create([
                    'input' => 'I want to kill you.',
            ]);
        });
    }

    public function runs(): array {
        return $this->perform_api_test('Runs', function() {
            $threadid = $this->openai->threads()->create()['id'];
            return $this->openai->runs($threadid)->list();
        });
    }

    public function threads(): array {
        return $this->perform_api_test('Threads', function() {
            return $this->openai->threads()->create();
        });
    }

    public function messages(): array {
        return $this->perform_api_test('Messages', function() {
            $threadid = $this->openai->threads()->create()['id'];
            return $this->openai->messages($threadid)->list();
        });
    }

}
