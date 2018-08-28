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
 * This page provides user to Download a sample csv file to import question questions in the question bank
 *
 * @package    qformat_csv
 * @copyright  2018 Gopal Sharma <gopalsharma66@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php'); // Specify path to moodle /config.php file.
require_login(); // require valid moodle login.  Will redirect to login page if not logged in.
require_once($CFG->libdir . '/adminlib.php');
$format = optional_param('format', 'csv', PARAM_ALPHA);

if ($format) {
    $header = array(
        'questiontext' => 'questiontext',
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'Answer 1' => 'Answer 1',
        'Answer 2' => 'Answer 2'
    );
    $rowvalue1 = array(
        'questiontext' => 'The command “mknod myfifo b 4 16”',
        'A' => 'Will create a block device if user is root',
        'B' => 'Will create a block device for all users',
        'C' => 'Will create a FIFO if user is not root',
        'D' => 'None of the mentioned',
        'Answer 1' => 'A',
        'Answer 2' => 'B'
    );
    $rowvalue2 = array(
        'questiontext' => 'Which command is used to display the operating system name?',
        'A' => 'os',
        'B' => 'unix',
        'C' => 'kernal',
        'D' => 'uname',
        'Answer 1' => 'D',
        'Answer 2' => ''
    );
    switch ($format) {
        case 'csv' : qformat_csv_download($header, $rowvalue1, $rowvalue2);
    }
    die;
}

function qformat_csv_download($header, $rowvalue1, $rowvalue2) {
    global $CFG;
    require_once($CFG->libdir . '/csvlib.class.php');
    $filename = clean_filename(get_string('samplefile', 'qformat_csv'));
    $csvexport = new csv_export_writer();
    $csvexport->set_filename($filename);
    $csvexport->add_data($header);
    $csvexport->add_data($rowvalue1);
    $csvexport->add_data($rowvalue2);
    $csvexport->download_file();
    die;
}
