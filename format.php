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
 * CSV format question importer.
 *
 * @package    qformat_csv
 * @copyright  2018 Gopal Sharma <gopalsharma66@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

/*
 CSV format - a simple format for creating multiple and single choice questions.
 * The format looks like this for simple csv file with minimum columns:
 * questionname, questiontext, A,   B,   C,   D,   Answer 1,    Answer 2
 * Question1, "3, 4, 7, 8, 11, 12, ... What number should come next?",7,10,14,15,D
 *
 *
 * The format looks like this for Extended csv file with extra columns columns:
 * questionname, questiontext, A,   B,   C,   D,   Answer 1,    Answer 2,
   answernumbering, correctfeedback, partiallycorrectfeedback, incorrectfeedback, defaultmark
 * Question1, "3, 4, 7, 8, 11, 12, ... What number should come next?",7,10,14,15,D, ,
   123, Your answer is correct., Your answer is partially correct., Your answer is incorrect., 1
 *
 *
 *  That is,
 *  + first line contains the headers separated with commas
 *  + Next line contains the details of question, each line contain
 *  one question text, four option, and either one or two answers again all separated by commas.
 *  Each line contains all the details regarding the one question ie. question text, options and answer.
 *  You can also download the sample file for your reference.
 *
 * @copyright 2018 Gopal Sharma <gopalsharma66@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$globals['header'] = true;
class qformat_csv extends qformat_default {

    public function provide_import() {
        return true;
    }

    public function provide_export() {
        return true;
    }

    /**
     * @return string the file extension (including .) that is normally used for
     * files handled by this plugin.
     */
    public function export_file_extension() {
        return '.csv';
    }

    public function readquestions($lines) {
        global $CFG;
        require_once($CFG->libdir . '/csvlib.class.php');
        question_bank::get_qtype('multianswer'); // Ensure the multianswer code is loaded.
        $questions = array();
        $question = $this->defaultquestion();
        $headers = explode(',', $lines[0]);
        $answertwo = 0;
        foreach ($headers as $key => $value) {
            if (trim($value) == "Answer 2") {
                $answertwo = $key;
            }
        }
        // Get All the Header Values from the CSV file.
        for ($rownum = 1; $rownum < count($lines); $rownum++) {
            $rowdata = str_getcsv($lines[$rownum], ",", '"'); // Ignore the commas(,) within the double quotes (").
            $columncount = count($rowdata);
            $headerscount = count($headers);
            if ($columncount != $headerscount || $columncount != 8  || $headerscount != 8) {
                if ($columncount != $headerscount || $columncount != 13  || $headerscount != 13) {
                    if ($columncount > $headerscount ) {
                        // There are more than 7 values or there will be extra comma making them more then 7 values.
                            echo get_string('commma_error', 'qformat_csv', $rownum);
                        return 0;
                    } else if ($columncount < $headerscount) {
                        // Entire question with options and answer is not in one line, new line found.
                            echo get_string('newline_error', 'qformat_csv', $rownum);
                        return 0;
                    } else {
                        // There are more than 7 values or there will be extra comma making them more then 7 values.
                            echo get_string('csv_file_error', 'qformat_csv', $rownum);
                        return 0;
                    }
                }
            }
            for ($linedata = 0; $linedata < count($rowdata); $linedata++) {
                if ($answertwo != 0 && !empty(trim($rowdata[$answertwo]))) {
                    $fraction = 0.5;
                    $question->single = 0;
                } else {
                    $fraction = 1;
                    $question->single = 1;
                }

                $question->qtype = 'multichoice';

                if (trim($headers[$linedata]) == 'questionname') {
                    $question->name = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'questiontext') {
                      $question->questiontext = htmlspecialchars(trim($rowdata[$linedata]), ENT_NOQUOTES);
                } else if (trim($headers[$linedata]) == 'generalfeedback') {
                    // If extra column is provide with header 'generalfeedback' then that feedback will get applied.
                    $question->generalfeedback['text'] = $rowdata[$linedata];
                    $question->generalfeedback['format'] = FORMAT_HTML;
                } else if (trim($headers[$linedata]) == 'defaultmark') {
                    $question->defaultmark = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'penalty') {
                    $question->penalty = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'hidden') {
                    $question->hidden = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'answernumbering') {
                    // If extra column is provide with header 'answernumbering' then that answernumbering will get applied.
                    $question->answernumbering = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'correctfeedback') {
                    $question->correctfeedback['text'] = $rowdata[$linedata];
                    $question->correctfeedback['format'] = FORMAT_HTML;
                } else if (trim($headers[$linedata]) == 'partiallycorrectfeedback') {
                    $question->partiallycorrectfeedback['text'] = $rowdata[$linedata];
                    $question->partiallycorrectfeedback['format'] = FORMAT_HTML;
                } else if (trim($headers[$linedata]) == 'incorrectfeedback') {
                    $question->incorrectfeedback['format'] = FORMAT_HTML;
                    $question->incorrectfeedback['text'] = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'A') {
                    $correctans1 = $linedata + 4;
                    $correctans2 = $linedata + 5;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'A' || trim($rowdata[$correctans2]) == 'A') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                                                    $question->feedback[] = $this->text_field('');
                } else if (trim($headers[$linedata]) == 'B') {
                    $correctans1 = $linedata + 3;
                    $correctans2 = $linedata + 4;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'B' || trim($rowdata[$correctans2]) == 'B') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                    $question->feedback[] = $this->text_field('');
                } else if (trim($headers[$linedata]) == 'C') {
                    $correctans1 = $linedata + 2;
                    $correctans2 = $linedata + 3;
                    $question->answer[] = $this->text_field($rowdata[$linedata]);
                    if (trim($rowdata[$correctans1]) == 'C' || trim($rowdata[$correctans2]) == 'C') {
                        $question->fraction[]  = $fraction;
                    } else {
                        $question->fraction[] = 0;
                    }
                    $question->feedback[] = $this->text_field('');
                } else if (trim($headers[$linedata]) == 'D') {
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

    public function writequestion($question) {
        global $OUTPUT;
        $expout = "";
        $rightanswer = "";
        $answercount = 0;
        $rightanswercount = 0;
        // Output depends on question type.
        // CSV Header should be printed only once.
        if ($globals['header']) {
                $expout .= "questionname,questiontext,A,B,C,D,Answer 1,Answer 2,";
                $expout .= "answernumbering, correctfeedback, partiallycorrectfeedback, incorrectfeedback, defaultmark";
                $globals['header'] = false;
        }

        switch($question->qtype) {
            case 'multichoice':
                if (count($question->options->answers) != 4 ) {
                    break;
                }
                $expout .= '"'.$question->name.'"'.',';
                $expout .= '"'.$question->questiontext.'"'.',';
                foreach ($question->options->answers as $answer) {
                    $answercount++;
                    if ($answer->fraction == 1 && $question->options->single) {
                        switch ($answercount) {
                            case 1:
                                $rightanswer = 'A'.', ,';
                                break;
                            case 2:
                                $rightanswer = 'B'.', ,';
                                break;
                            case 3:
                                $rightanswer = 'C'.', ,';
                                break;
                            case 4:
                                $rightanswer = 'D'.', ,';
                                break;
                            default:
                                $rightanswer = '';
                                break;
                        }
                    } else if ($answer->fraction == 0.5 && !$question->options->single) {
                        $rightanswercount ++;
                        $comma = ",";
                        if ( $rightanswercount <= 1 ) {
                            $comma = ","; // Add comma  to first answer i.e. to 'Answer 1'.
                        }
                        switch ($answercount) {
                            case 1:
                                $rightanswer .= 'A'.$comma;
                                break;
                            case 2:
                                $rightanswer .= 'B'.$comma;
                                break;
                            case 3:
                                $rightanswer .= 'C'.$comma;
                                break;
                            case 4:
                                $rightanswer .= 'D'.$comma;
                                break;
                            default:
                                $rightanswer = '';
                                break;
                        }

                    }
                    $expout .= '"'.$answer->answer.'"'.',';
                }
                $expout .= $rightanswer;
                $expout .= '"'.$question->options->answernumbering.'"'.',';
                $expout .= '"'.$question->options->correctfeedback.'"'.',';
                $expout .= '"'.$question->options->partiallycorrectfeedback.'"'.',';
                $expout .= '"'.$question->options->incorrectfeedback.'"'.',';
                $expout .= '"'.$question->defaultmark.'"'.',';

            break;
        }
        return $expout;
    }
}
