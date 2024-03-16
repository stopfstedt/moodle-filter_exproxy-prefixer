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
 * Unit test for the filter_ucsfezproxy
 *
 * @package    filter_ucsfezproxy
 * @copyright  The Regents of the University of California
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace filter_ucsfezproxy;

use advanced_testcase;
use context_system;
use filter_ucsfezproxy;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/filter/ucsfezproxy/filter.php');

/**
 * Unit tests for filter_ucsfezproxy.
 *
 * Test the EZProxy URL filter.
 *
 * @package    filter_ucsfezproxy
 * @copyright  The Regents of the University of California
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers \filter_ucsfezproxy
 */
final class filter_test extends advanced_testcase {

    /**
     * @var filter_ucsfezproxy The EZProxy filter object under test.
     */
    protected filter_ucsfezproxy $filter;

    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->filter = new filter_ucsfezproxy(context_system::instance(), []);
    }

    protected function tearDown(): void {
        unset($this->filter);
    }

    /**
     * Filter test.
     *
     * @dataProvider filter_provider
     * @param string $text The unfiltered input.
     * @param string $expected The expected filter output.
     */
    public function test_filter(string $text, string $expected): void {
        $this->assertEquals($expected, $this->filter->filter($text));
    }

    /**
     * Data provider function for the test filter function.
     *
     * @return array An array of arrays. Each item contains the unfiltered input, followed by the expected filter output.
     */
    public static function filter_provider(): array {
        return [
            ['', ''],
            ['lorem ipsum', 'lorem ipsum'],
            ['https://ucsf.edu', 'https://ucsf.edu'],
            ['http://doi.org/10.1109/5.771073', 'https://ucsf.idm.oclc.org/login?url=http://doi.org/10.1109/5.771073'],
            ['https://doi.org/10.1109/5.771073', 'https://ucsf.idm.oclc.org/login?url=https://doi.org/10.1109/5.771073'],
            ['http://doi.info/10.1109/5.771073', 'https://ucsf.idm.oclc.org/login?url=http://doi.info/10.1109/5.771073'],
            ['https://doi.info/10.1109/5.771073', 'https://ucsf.idm.oclc.org/login?url=https://doi.info/10.1109/5.771073'],
            ['https://www.doi.org/10.1109/5.771073', 'https://ucsf.idm.oclc.org/login?url=https://www.doi.org/10.1109/5.771073'],
            ['https://dx.doi.org/10.1109/5.771073', 'https://ucsf.idm.oclc.org/login?url=https://dx.doi.org/10.1109/5.771073'],
        ];
    }
}
