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
 * Strings for component 'qformat_csv', language 'en'
 *
 * @package    qformat_csv
 * @copyright  2018 Gopal Sharma <gopalsharma66@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'CSV format';
$string['pluginname_help'] = 'This is a CSV format for importing multiple choice( 2 choices ) or single choice questions from a csv file.  <a href="'.$CFG->wwwroot.'/question/format/csv/sample.php" >Click here </a>to download sample file.';
$string['pluginname_link'] = 'qformat/csv';
$string['commma_error'] = '<font color="#990000"> Upload failed. Unnecessary Comma(,) found in <b> Question {$a} </b><br /> Please remove the comma(,) from the field. <br /></font>';
$string['newline_error'] = '<font color="#990000">Upload failed. New Line found in <b> Question {$a} . </b> Make sure that entire question with choices and answers are in one line itself.<br /> Please correct this question and try importing again. <br /> No Question has been improted.</font>';
$string['samplefile'] = 'SampleFile';
