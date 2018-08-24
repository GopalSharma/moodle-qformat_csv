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
 * csv format question importer.
 *
 * @package    qformat_csv
 * @copyright  2018 Gopal Sharma <gopalsharma66@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

/**
 * CSV format - a simple format for creating multiple and single choice questions.
 * The format looks like this:
 * questiontext, A,   B,   C,   D,   Answer 1,    Answer 2
 * "3, 4, 7, 8, 11, 12, ... What number should come next?",7,10,14,15,D
 * That is,
 *  + first line contains the headers separated with commas
 *  + Next line contains the details of question each line contain one question text, four option, and either one or two answers again all separated by commas.
 *  Each line contains all the details regarding the one question ie. question text, options and answer.
 *  You can also download the sample file for your reference.
 */
$header = true;
class qformat_csv extends qformat_default {
    public function provide_import() {
        return true;
    }

    public function provide_export() {
        return false;
    }

    /**
     * @return string the file extension (including .) that is normally used for
     * files handled by this plugin.
     */
    public function export_file_extension() {
        return '.csv';
    }

    public function readquestions($lines) {
        question_bank::get_qtype('multianswer'); // Ensure the multianswer code is loaded.
        $questions = array();
        $question = $this->defaultquestion();
        $headers = explode(',', $lines[0]);
         // Get All the Header Values
        for ($rownum = 1; $rownum < count($lines); $rownum++) {
            // $rowdata = explode(',', $lines[$rownum]);
            $rowdata = str_getcsv($lines[$rownum], ",", '"'); // ignore the commas(,) within the double quotes (")
            /* echo '<pre>',print_r($headers),'</pre>'; */
            $columncount = count($rowdata);
            $headerscount = count($headers);
            if ($columncount != $headerscount) {
                if ($columncount > $headerscount) { // There are more than 7 values or there will be extra comma making them more then 7 values.
                    echo get_string('commma_error', 'qformat_csv', $rownum);
                    return 0;
                } else if ($columncount < $headerscount) { // Entire question with options and answer is not in one line, new line found.
                    echo get_string('newline_error', 'qformat_csv', $rownum);
                    return 0;
                }
            }
            for ($linedata = 0; $linedata < count($rowdata); $linedata++) {
                // echo $headers[$linedata]." ".$linedata;
                if (empty(trim($rowdata[6]))) {
                    $fraction = 1;
                    $question->single = 1;
                } else {
                    $fraction = 0.5;
                    $question->single = 0;
                }
                // if($headers[$linedata] == 'name'){
                $question->qtype = 'multichoice';
                $question->name = $this->create_default_question_name($rownum, get_string('questionname', 'question'));
                // } else
                if ($headers[$linedata] == 'questiontext') {
                      $question->questiontext = htmlspecialchars(trim($rowdata[$linedata]), ENT_NOQUOTES);
                } else if ($headers[$linedata] == 'generalfeedback') { // if extra column is provide with header 'generalfeedback' then that feedback will get applied
                    $question->generalfeedback = $rowdata[$linedata];
                    // $question->generalfeedbackformat = FORMAT_HTML;
                } else if ($headers[$linedata] == 'defaultgrade') {
                    $question->defaultgrade = $this->text_field($rowdata[$linedata]);
                } else if ($headers[$linedata] == 'penalty') {
                    $question->penalty = $rowdata[$linedata];
                } else if ($headers[$linedata] == 'hidden') {
                    $question->hidden = $rowdata[$linedata];
                } /*elseif ($headers[$linedata] == 'singlechoice') {
                    $question->single = $rowdata[$linedata];
                }*/ else if ($headers[$linedata] == 'answernumbering') {
                    $question->answernumbering = $rowdata[$linedata];
                } else if ($headers[$linedata] == 'correctfeedback') {
                    $question->correctfeedback = $this->text_field($rowdata[$linedata]);
                } else if ($headers[$linedata] == 'partiallycorrectfeedback') {
                    $question->partiallycorrectfeedback = $this->text_field($rowdata[$linedata]);
                } else if ($headers[$linedata] == 'incorrectfeedback') {
                    $question->incorrectfeedback = $this->text_field($rowdata[$linedata]);
                } else if ($headers[$linedata] == 'A') {
                    $correctans1 = $linedata + 4;
                    $correctans2 = $linedata + 5;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'A' || trim($rowdata[$correctans2]) == 'A') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                                                    $question->feedback[] = $this->text_field('');
                } else if ($headers[$linedata] == 'B') {
                    $correctans1 = $linedata + 3;
                    $correctans2 = $linedata + 4;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'B' || trim($rowdata[$correctans2]) == 'B') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                    $question->feedback[] = $this->text_field('');
                } else if ($headers[$linedata] == 'C') {
                    $correctans1 = $linedata + 2;
                    $correctans2 = $linedata + 3;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'C' || trim($rowdata[$correctans2]) == 'C') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                    $question->feedback[] = $this->text_field('');
                } else if ($headers[$linedata] == 'D') {
                    $correctans1 = $linedata + 1;
                    $correctans2 = $linedata + 2;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'D' || trim($rowdata[$correctans2]) == 'D') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                    $question->feedback[] = $this->text_field('');
                }
            }
                    $questions[] = $question;
                    // Clear array for next question set.
                    $question = $this->defaultquestion();
        }
         return $questions;
    }
    
    protected function text_field($text) {
        return array(
            'text' => htmlspecialchars(trim($text), ENT_NOQUOTES),
            'format' => FORMAT_HTML,
            'files' => array(),
        );
    }

    public function readquestion($lines) {
        // This is no longer needed but might still be called by default.php.
        return;
    }
    
}
