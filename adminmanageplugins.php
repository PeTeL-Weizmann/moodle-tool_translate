<?php
// This file is part of the tool_translate plugin for Moodle - http://moodle.org/
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
 * Allows the admin to manage translation engine plugins
 *
 * @package   tool_translate
 * @copyright 2021 eWallah
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    info@iplusacademy.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

$action = optional_param('action', null, PARAM_PLUGIN);
$plugin = optional_param('plugin', null, PARAM_PLUGIN);

if (!empty($plugin)) {
    require_sesskey();
}

require_once($CFG->dirroot . '/lib/adminlib.php');
$pluginmanager = new \tool_translate\plugin_manager();

\admin_externalpage_setup('translateengine');

$pluginmanager->execute($action, $plugin);
