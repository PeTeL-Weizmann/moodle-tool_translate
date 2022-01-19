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
 * Other tests for AWS translate engine.
 *
 * @package   translateengine_aws
 * @copyright 2021 eWallah
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    info@iplusacademy.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace translateengine_aws;

/**
 * Other tests for AWS translate engine.
 *
 * @package   translateengine_aws
 * @copyright 2021 eWallah
 * @author    Renaat Debleu <info@eWallah.net>
 * @author    info@iplusacademy.org
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class other_test extends \advanced_testcase {

    /**
     * Setup testcase.
     */
    public function setUp(): void {
        $this->setAdminUser();
        $this->resetAfterTest();
        set_config('region', 'eu-west-3', 'translateengine_aws');
        set_config('access_key', 'key', 'translateengine_aws');
        set_config('secret_key', 'secret', 'translateengine_aws');
    }

    /**
     * Test the empty class.
     */
    public function test_notconfigured() {
        $course = $this->getDataGenerator()->create_course();
        set_config('access_key', '', 'translateengine_aws');
        $class = new engine($course);
        $this->assertInstanceOf('\translateengine_aws\engine', $class);
        $this->assertFalse($class->is_configured());
        $this->assertSame('Not configured', $class->translatetext('en', 'fr', 'boe'));
        $this->assertIsArray($class->supported_langs());
    }

    /**
     * Test the class.
     */
    public function test_class() {
        $course = $this->getDataGenerator()->create_course();
        $class = new engine($course);
        $this->assertInstanceOf('\translateengine_aws\engine', $class);
        $this->assertTrue($class->is_configured());
        $this->assertIsArray($class->supported_langs());
        $this->assertSame('AWS translate', $class->get_name());
        $langs = $class->supported_langs();
        $languages1 = get_string_manager()->get_list_of_languages('en', 'iso6391');
        $languages2 = get_string_manager()->get_list_of_languages('en', 'iso6392');
        foreach ($langs as $key => $value) {
            $this->assertTrue(array_key_exists($value, $languages1));
            $this->assertTrue(array_key_exists($key, $languages2));
        }
        $this->assertSame('BEHAT 1', $class->translatetext('en', 'fr', 'boe'));
    }
}
