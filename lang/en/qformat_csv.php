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
$string['pluginname_help'] = 'This is a CSV format for importing and exporting multiple choice( 4 choices ) questions with one or maximum of two answers from a CSV(Comma separated value) file. Please find the Sample csv(sample.csv) and Extended Sample csv(extended_sample.csv) file sent along with this plugin for your reference.';
$string['pluginname_link'] = 'qformat/csv';
$string['commma_error'] = '<font color="#990000"> Upload failed. Unnecessary Comma(,) found in <b> Question {$a} </b><br /> Please remove the comma(,) from the field or Put the entire text between the double quotes(" "), So the coomma(,) between them can be ignored. <br /></font>';
$string['newline_error'] = '<font color="#990000">Upload failed. New Line found in <b> Question {$a} . </b> Make sure that entire question with choices and answers are in one line itself.<br /> Please correct this question and try importing again. <br /> No Question has been imported.</font>';
$string['csv_file_error'] = '<font color="#990000">Upload failed. Something went wrong at line number <b/> {$a} . </b> Make sure you are uploading a valid CSV file.<br /> Please check that, <b> Simple CSV file must have 8 headers/fields and Extended CSV file must have 13 headers/fields in order work.</b><br /> Please correct the error and try importing again. <br /> No Question has been imported.</font>';
$string['samplefile'] = 'SampleFile';
$string['privacy:metadata'] = 'The CSV format plugin does not store any personal data.';
