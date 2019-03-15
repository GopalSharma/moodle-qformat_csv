NEW in 3.6.02
==============
FIX: Solved the issue with Html tags in feedback.

NEW in 3.6.02
==============
- New: Added 'questionname' option in the standard CSV file, total fields/columns are 8 now.
- New: Extended CSV file with many more fields for questionname, answernumbering, correctfeedback, partiallycorrectfeedback,
incorrectfeedback and defaultmark, total fields/columns are 13 now.
- NEW: Now, while exporting questions into a CSV file, the CSV file will have 13 fields/columns by default.

NEW in 3.6.01
==============
- FIX: Solved the issue with answers and tested the plugin with Moodle version 3.6.

New in 3.5.04 (Build: 2018091801)
==============

- FIX: Issue related to multiple answers, even single choice answer is getting converted into a multi-choice answer.
- FIX: Solved the issue related to CSV file handling.
- NEW: Implemented functionality to export multichoice question into a CSV file having four options and maximum of
two right answers.


# Description
Question formats import plugin 

==============================

This plugin contains support for importing only multichoice questions in CSV format file in question bank and
exporting questions from a question bank in a CSV file.
The CSV format is a very simple way of creating multiple choice questions using a CSV(Comma separated value) file.
The first line of the CSV file must contain the headers separated with commas.

Now, There can be two types of CSV files that can be used
1. Simple CSV :
===============
This one will have same fields what we used to have(only addition is question name in the field)
All the other (except Header) rows/lines contain details about the one question
ie. question name, question text, four option, and answer1 , answer2.

Each line will contain the details about the one question.

The simple CSV file used for import should have the following structure :
-A simple CSV file with all questions in comma separated value form with a .csv extension
-The first line contains the headers separated with commas for example
  questionname,questiontext,A,B,C,D,Answer 1,Answer 2
-Next lines contain the details of the question,
  each of the line contain one question name, question text, four option, and
  either one or two answers again all separated by commas.
-Each line contains all the details regarding the one question ie. question name, question text, options, and answer.
-You can also download the sample CSV(sample.csv) file for your reference.


2. Extended CSV :
=================
This CSV file will have extra fields like answernumbering, correctfeedback, partiallycorrectfeedback,
incorrectfeedback and defaultmark.
All the other (except Header) rows/lines contain details about the one question
ie. question name, question text, four option, answer1, answer2, answernumbering, correctfeedback,
partiallycorrectfeedback, incorrectfeedback and defaultmark.
file with many other options

Each line will contain the details about the one question.

The CSV file used for import should have the following structure :
-A CSV file with all questions in comma separated value form with a .csv extension
-The first line contains the headers separated with commas for example
questionname,questiontext,A,B,C,D,Answer 1,Answer 2,answernumbering,correctfeedback,
partiallycorrectfeedback,incorrectfeedback,defaultmark
-Next lines contain the details of the question,
each of line contain one question name, question text, four option, answer1, answer2, answernumbering, correctfeedback,
partiallycorrectfeedback,incorrectfeedback and defaultmark, again all separated by commas.
-Each line contains all the details regarding the one only
ie. question name, question text, four option, answer1, answer2, answernumbering, correctfeedback,
partiallycorrectfeedback, incorrectfeedback and defaultmark.
-You can also download the Extended Sample CSV(extended_sample.csv) file for your reference.

=============================================================================================================================
IMPORTANT NOTES:

* You have to save the file strictly in csv format. Don't save it as an Excel document or anything like that.
* Non-ASCII characters like 'quotes' can cause import errors.
* Simple CSV file will have 8 Columns/fields.
* Extended CSV file will have 13 Columns/fields.
* To avoid this, always save your text file in UTF-8 format (most text editors, even libre office, will ask you).
* The Header must be as it is shown in the example everything is case sensitive as shown below otherwise, the import will fail.
* "Answer 2" is optional, as of now there can be maximum of two right answers of a question,
it should be added with empty value, in case question has only one answer.
* If you want to have comma(,) between the text may be in question text or in options text then
you must include that text between the double quotes(") like below in the 2nd question, where entire
question text is included between the double quotes like this "3, 4, 7, 8, 11, 12, ... What number should come next?"

 questionname, questiontext, A, B, C, D, Answer 1, Answer 2
 Question1,Which command is used to display the operating system name?, os, unix, kernal, uname, D,
 Question2,"3, 4, 7, 8, 11, 12, ... What number should come next?", 7, 10, 14, 15, D,

You can also see the 'Answer 2' is optional as 1st and 2nd question has only one answer but both questions
has 'Answer 2' as a blank value.

Similarly for Extended CSv file:-

 questionname,questiontext,A,B,C,D,Answer 1,Answer 2,answernumbering,correctfeedback,
 partiallycorrectfeedback,incorrectfeedback,defaultmark

 Question1,Which command is used to set terminal IO characteristic?,tty,ctty,ptty,stty,D,,iii,Your answer is correct.,
 Your answer is partially correct.,Your answer is incorrect.,1

* Please, see the sample csv files for more clarification.
* Questions imported in question bank can also be imported when creating a quiz from the question bank.
* You can also export questions from question bank into a CSV file.
