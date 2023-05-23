<?php
/**
 * Settings page
 *
 * @package    local_ai_connect
 * @copyright  2023 Enovation
 * @author Olgierd Dziminski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_ai_connector', get_string('pluginname', 'local_ai_connector'));

    // OpenAI.
    $name = new lang_string('openaisettings', 'local_ai_connector');
    $description = new lang_string('openaisettings_help', 'local_ai_connector');
    $settings->add(new admin_setting_heading('openaisettings', $name, $description));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/openaiapikey',
        get_string('openaiapikey', 'local_ai_connector'),
        get_string('openaiapikey_desc', 'local_ai_connector'),
        ''
    ));


    $settings->add(new admin_setting_configtextarea(
        'local_ai_connector/source_of_truth',
        get_string('sourceoftruth', 'local_ai_connector'),
        get_string('sourceoftruth_desc', 'local_ai_connector'),
        ''
    ));

    $settings->add(new admin_setting_configselect(
        'local_ai_connector/model',
        get_string('model', 'local_ai_connector'),
        get_string('model_desc', 'local_ai_connector'),
        'gpt-3.5-turbo',
        [
            'gpt-3.5-turbo' => 'gpt-3.5-turbo',
            'gpt-3.5-turbo-0301' => 'gpt-3.5-turbo-0301',
            'gpt-4' => 'gpt-4',
            'gpt-4-0314' => 'gpt-4-0314',
            'gpt-4-32k' => 'gpt-4-32k',
            'gpt-4-32k-0314' => 'gpt-4-32k-0314'
        ]
    ));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/temperature',
        get_string('temperature', 'local_ai_connector'),
        get_string('temperature_desc', 'local_ai_connector'),
        '0.5',
        PARAM_FLOAT
    ));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/top_p',
        get_string('top_p', 'local_ai_connector'),
        get_string('top_p_desc', 'local_ai_connector'),
        ''
    ));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/frequency_penalty',
        get_string('frequency_penalty', 'local_ai_connector'),
        get_string('frequency_penalty_desc', 'local_ai_connector'),
        ''
    ));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/presence_penalty',
        get_string('presence_penalty', 'local_ai_connector'),
        get_string('presence_penalty_desc', 'local_ai_connector'),
        ''
    ));

    // DeepAI.
    $name = new lang_string('deepaisettings', 'local_ai_connector');
    $description = new lang_string('deepaisettings_help', 'local_ai_connector');
    $settings->add(new admin_setting_heading('deepaisettings', $name, $description));

    $settings->add(new admin_setting_configtext(
        'local_ai_connector/deepaiapikey',
        get_string('deepaiapikey', 'local_ai_connector'),
        '',
        ''
    ));

    $ADMIN->add('localplugins', $settings);
}