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
 * Include tests for translate tool.
 *
 * @package   tool_translate
 * @copyright 2021 eWallah
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    info@iplusacademy.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Include tests for translate tool.
 *
 * @package   tool_translate
 * @copyright 2021 eWallah
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    info@iplusacademy.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_translate_include_testcase extends advanced_testcase {

    /**
     * Test the adminmanageplugins.
     */
    public function test_adminmanagepluginss() {
        global $CFG, $PAGE;
        require_once($CFG->dirroot . '/admin/tool/translate/db/access.php');
        $this->resetAfterTest();
        $this->setAdminUser();
        $PAGE->get_renderer('core');
        $_POST['plugin'] = 'aws';
        $_POST['sesskey'] = sesskey();
        ob_start();
        include($CFG->dirroot . '/admin/tool/translate/adminmanageplugins.php');
        $html = ob_get_clean();
        $this->assertStringContainsString('Manage translate engines', $html);
    }

    /**
     * Test the access.
     */
    public function test_access() {
        global $CFG;
        include($CFG->dirroot . '/admin/tool/translate/db/access.php');
    }

    /**
     * Test the version.
     */
    public function test_version() {
        global $CFG;
        require_once($CFG->dirroot . '/admin/tool/translate/version.php');
    }

}
