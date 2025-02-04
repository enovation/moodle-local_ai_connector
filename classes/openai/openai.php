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

/**
 * Class openai
 *
 * This class provides methods to interact with different aspects of the OpenAI API.
 *
 * @package     local_ai_connector
 * @copyright   2024 Enovation Solutions
 * @author      Oliwer Banach <oliwer.banach@enovation.ie>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class openai {

    /**
     * Create a new instance of the openai_assistants class.
     *
     * @return openai_assistants An instance of the openai_assistants class.
     * @see openai_assistants
     */
    public function assistants(): openai_assistants {
        return new openai_assistants();
    }

    /**
     * Create a new instance of the openai_audio class.
     *
     * @return openai_audio An instance of the openai_audio class.
     * @see openai_audio
     */
    public function audio(): openai_audio {
        return new openai_audio();
    }

    /**
     * Create a new instance of the openai_chat class.
     *
     * @return openai_chat An instance of the openai_chat class.
     * @see openai_chat
     */
    public function chat(): openai_chat {
        return new openai_chat();
    }

    /**
     * Create a new instance of the openai_embeddings class.
     *
     * @return openai_embeddings An instance of the openai_embeddings class.
     * @see openai_embeddings
     */
    public function embeddings(): openai_embeddings {
        return new openai_embeddings();
    }

    /**
     * Create a new instance of the openai_files class.
     *
     * @return openai_files An instance of the openai_files class.
     * @see openai_files
     */
    public function files(): openai_files {
        return new openai_files();
    }

    /**
     * Create a new instance of the openai_fine_tuning class.
     *
     * @return openai_fine_tuning An instance of the openai_fine_tuning class.
     * @see openai_fine_tuning
     */
    public function fine_tuning(): openai_fine_tuning {
        return new openai_fine_tuning();
    }

    /**
     * Create a new instance of the openai_images class.
     *
     * @return openai_images An instance of the openai_images class.
     * @see openai_images
     */
    public function images(): openai_images {
        return new openai_images();
    }

    /**
     * Create a new instance of the openai_messages class.
     *
     * @param string $openaithreadid The ID of the thread for which to create a new openai_messages instance.
     * @return openai_messages An instance of the openai_messages class.
     * @see openai_messages
     */
    public function messages(string $openaithreadid): openai_messages {
        return new openai_messages($openaithreadid);
    }

    /**
     * Create a new instance of the openai_models class.
     *
     * @return openai_models An instance of the openai_models class.
     * @see openai_models
     */
    public function models(): openai_models {
        return new openai_models();
    }

    /**
     * Create a new instance of the openai_moderations class.
     *
     * @return openai_moderations An instance of the openai_moderations class.
     * @see openai_moderations
     */
    public function moderations(): openai_moderations {
        return new openai_moderations();
    }

    /**
     * Create a new instance of the openai_runs class.
     *
     * @param string $openaithreadid The ID of the thread for which to create a new openai_runs instance.
     * @return openai_runs An instance of the openai_runs class.
     * @see openai_runs
     */
    public function runs(string $openaithreadid): openai_runs {
        return new openai_runs($openaithreadid);
    }

    /**
     * Create a new instance of the openai_threads class.
     *
     * @return openai_threads An instance of the openai_threads class.
     * @see openai_threads
     */
    public function threads(): openai_threads {
        return new openai_threads();
    }

    /**
     * Create a new instance of the openai_prompt_calculator class.
     *
     * @return openai_prompt_calculator An instance of the openai_prompt_calculator class.
     * @see openai_prompt_calculator
     */
    public function prompt_calculator(): openai_prompt_calculator {
        return new openai_prompt_calculator();
    }

    /**
     * Returns a new instance of the `openai_vector` class.
     *
     * @return openai_vector The new instance of the `openai_vector` class.
     */
    public function vector(): openai_vector {
        return new openai_vector();
    }
}
