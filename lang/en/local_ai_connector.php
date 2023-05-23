<?php

$string['pluginname'] = 'AI Connector';

$string['openaisettings'] = 'OpenAI settings';
$string['openaisettings_help'] = 'Settings for OpenAI services (ChatGPT, DALL-E)';
$string['openaiapikey'] = 'OpenAI API Key';
$string['openaiapikey_desc'] = 'The API Key for your OpenAI account, from https://platform.openai.com/account/api-keys . Sample key looks like this: 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';
$string['sourceoftruth'] = 'Source of truth';
$string['sourceoftruth_desc'] = 'Information that is specific for your organization. It will be passed to ChatGPT as facts that should be used when crafting the response.';
$string['model'] = 'Model';
$string['model_desc'] = 'The model used to generate the completion.';
$string['temperature'] = 'Temperature';
$string['temperature_desc'] = 'In other words this is "randomness" or "creativity".
Low temperature will generate more coherent but predictable text.
The range is from 0 to 1.';
$string['max_length'] = 'Maximum length';
$string['top_p'] = 'Top P';
$string['top_p_desc'] = 'It\'s used for similar purpose as temperature - the lower the setting, the more correct and deterministic output.
The range is also from 0 to 1.';
$string['frequency_penalty'] = 'Frequency penalty';
$string['frequency_penalty_desc'] = 'Reduces repetition of words that have already been generated. It counts how many times the word was already used.';
$string['presence_penalty'] = 'Presence penalty';
$string['presence_penalty_desc'] = 'Similar to frequency penalty, it reduces probability of using a that was already used.
The difference is that is does not matter how many times the word was used - just if it was or not.';
$string['deepaisettings'] = 'DeepAI settings';
$string['deepaisettings_help'] = 'Settings for DeepAI (deepai.org) services (Stable Diffusion)';
$string['deepaiapikey'] = 'DeepAI API Key';
$string['deepaiapikey_desc'] = 'DeepAI API Key';
