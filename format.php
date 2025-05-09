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
 * @copyright  2021 Gopal Sharma <gopalsharma66@gmail.com>
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

class qformat_csv extends qformat_default {

    // functions to indicate import/export functionality

    /** @return bool whether this plugin provides import functionality. */
    public function provide_import() {
        return true;
    }

    /** @return bool whether this plugin provides export functionality. */
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

    /**
     * Parses an array of lines into an array of questions,
     * where each item is a question object as defined by
     * readquestion().   Questions are defined as anything
     * between blank lines.
     *
     * NOTE this method used to take $context as a second argument. However, at
     * the point where this method was called, it was impossible to know what
     * context the quetsions were going to be saved into, so the value could be
     * wrong. Also, none of the standard question formats were using this argument,
     * so it was removed. See MDL-32220.
     *
     * If your format does not use blank lines as a delimiter
     * then you will need to override this method. Even then
     * try to use readquestion for each question
     * @param array lines array of lines from readdata
     * @return array array of question objects
     */
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
                      $question->questiontext = html_entity_decode(trim($rowdata[$linedata]));
                } else if (trim($headers[$linedata]) == 'generalfeedback') {
                    // If extra column is provide with header 'generalfeedback' then that feedback will get applied.
                    $question->generalfeedback['text'] = html_entity_decode(trim($rowdata[$linedata]));
                    $question->generalfeedback['format'] = FORMAT_HTML;
                } else if (trim($headers[$linedata]) == 'defaultmark') {
                    $question->defaultmark = html_entity_decode(trim($rowdata[$linedata]));
                } else if (trim($headers[$linedata]) == 'penalty') {
                    $question->penalty = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'hidden') {
                    $question->hidden = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'answernumbering') {
                    // If extra column is provide with header 'answernumbering' then that answernumbering will get applied.
                    $question->answernumbering = $rowdata[$linedata];
                } else if (trim($headers[$linedata]) == 'correctfeedback') {
                    $question->correctfeedback['text'] = html_entity_decode(trim($rowdata[$linedata]));
                    $question->correctfeedback['format'] = FORMAT_HTML;
                } else if (trim($headers[$linedata]) == 'partiallycorrectfeedback') {
                    $question->partiallycorrectfeedback['text'] = html_entity_decode(trim($rowdata[$linedata]));
                    $question->partiallycorrectfeedback['format'] = FORMAT_HTML;
                } else if (trim($headers[$linedata]) == 'incorrectfeedback') {
                    $question->incorrectfeedback['format'] = FORMAT_HTML;
                    $question->incorrectfeedback['text'] = html_entity_decode(trim($rowdata[$linedata]));
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

    /**
     * Return the array moodle is expecting
     * for an HTML text. No processing is done on $text.
     * qformat classes that want to process $text
     * for instance to import external images files
     * and recode urls in $text must overwrite this method.
     * @param array $text some HTML text string
     * @return array with keys text, format and files.
     */
    protected function text_field($text) {
        return array(
            'text' => html_entity_decode(trim($text)),
            'format' => FORMAT_HTML,
            'files' => array(),
        );
    }

    /**
     * Given the data known to define a question in
     * this format, this function converts it into a question
     * object suitable for processing and insertion into Moodle.
     *
     * If your format does not use blank lines to delimit questions
     * (e.g. an XML format) you must override 'readquestions' too
     * @param $lines mixed data that represents question
     * @return object question object
     */
    public function readquestion($lines) {
        // This is no longer needed but might still be called by default.php.
        return;
    }

    /**
     * Enable any processing to be done on the content
     * just prior to the file being saved
     * default is to do nothing
     * @param string output text
     * @param string processed output text
     */
    protected function presave_process($content) {
        // CSV Header should be printed only once.
        $expout = "questionname,questiontext,A,B,C,D,Answer 1,Answer 2,";
        $expout .= "answernumbering, correctfeedback, partiallycorrectfeedback, incorrectfeedback, defaultmark";
        return $expout.$content;
    }

    /**
     * convert a single question object into text output in the given
     * format.
     * This must be overriden
     * @param object question question object
     * @return mixed question export text or null if not implemented
     */
    public function writequestion($question) {
        global $OUTPUT;
        $expout = "";
        $rightanswer = "";
        $answercount = 0;
        $rightanswercount = 0;
        // Output depends on question type.
        // CSV Header should be printed only once.

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
                $expout .= '"'.$question->defaultmark.'"';

            break;
            default: return null;
        }
        return $expout;
    }
    
}
