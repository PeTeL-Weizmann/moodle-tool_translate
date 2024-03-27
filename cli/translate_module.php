<?php
// This file is part of Moodle - https://moodle.org/
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
 * Moodle CLI script - translate_component.php
 *
 * @package   tool_translate
 * @copyright 2023 iplusacademy
 * @author    Renaat Debleu <info@eWallah.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir . '/clilib.php');
require_once($CFG->libdir . '/adminlib.php');

$usage = "This script translates a Moodle module.

Usage:
    # php translate_module.php --engine=aws --courseid=21 --modid=22 --from=en --to=fr
    # php translate_module.php [--help|-h]

Options:
    -h --help               Print this help.
    --engine=<value>        The engine to be used (default aws).
    --courseid=<value>      The course id.
    --modid=<value>         The course module that has to be translated.
    --from=<value>          The source language (default en).
    --to=<value>            The target language.
";

[$options, $unrecognised] = cli_get_params([
    'help' => false,
    'engine' => 'aws',
    'courseid' => null,
    'modid' => null,
    'from' => 'en',
    'to' => null,
], [
    'h' => 'help',
    'e' => 'engine',
    'c' => 'courseid',
    'm' => 'modid',
    'f' => 'from',
    't' => 'to',
]);

if ($unrecognised) {
    $unrecognised = implode(PHP_EOL . '  ', $unrecognised);
    cli_error(get_string('cliunknowoption', 'core_admin', $unrecognised));
}

if ($options['help']) {
    cli_writeln($usage);
    exit(2);
}

if (empty($options['courseid'])) {
    cli_error('Missing mandatory argument courseid.', 2);
}

if (empty($options['modid'])) {
    cli_error('Missing mandatory argument modid.', 2);
}

if (empty($options['to'])) {
    cli_error('Missing mandatory argument to.', 2);
}
$course = get_course($options['courseid']);
$engine = 'translateengine_' . $options['engine'] . '\engine';
require_once($CFG->dirroot . '/admin/tool/translate/engine/' . $options['engine'] . '/classes/engine.php');
require_once($CFG->dirroot . '/admin/tool/translate/classes/engine.php');
$translateengine = new $engine($course);
if ($translateengine->is_configured()) {
    cli_writeln("Are you sure to translate this module?");
    $prompt = get_string('cliyesnoprompt', 'admin');
    $input = cli_input($prompt, '', [get_string('clianswerno', 'admin'), get_string('cliansweryes', 'admin')]);
    if ($input == get_string('clianswerno', 'admin')) {
        exit(1);
    }
    if ($translateengine->lang_supported($options['to'])) {
        $translateengine->sourcelang = $options['from'];
        $translateengine->targetlang = $options['to'];

        $table = new \tool_translate\output\translation_table($course);
        echo $table->translate_module($options['modid']);
        echo '
';
    }
} else {
    cli_problem(get_string('noengine', 'tool_translate'));
}
