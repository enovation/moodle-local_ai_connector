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

$string['pluginname'] = 'AI Connector';

$string['openaisettings'] = 'OpenAI settings';
$string['openaisettings_help'] = 'Settings for OpenAI services (ChatGPT, DALL-E)';
$string['openaiapikey'] = 'OpenAI API Key';
$string['openaiapikey_desc'] = 'The API Key for your OpenAI account, from https://platform.openai.com/account/api-keys';

// Privacy API.

$string['privacy:metadata:ai_connector'] = 'In order to generate text or image, user needs to pass prompt text and/or image.';
$string['privacy:metadata:ai_connector:prompttext'] = 'User\'s prompt text is being sent to API services to generate response.';
$string['privacy:metadata:ai_connector:image'] = 'Image is an optional argument you can pass to make a base for generated image.';

// Error messages.
$string['error:openai_client'] = 'OpenAI client error';
$string['error:openai_files'] = 'OpenAI file error';
$string['error:file_not_found'] = 'File not found.';
