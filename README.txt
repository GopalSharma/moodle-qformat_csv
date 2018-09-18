New in 3.5.04 (Build: 2018091801)
==============

- FIX: Solved the issue related to CSV file handling.
- NEW: Implemented functionality to export of multichoice question into a CSV file having four options and maximum of two right answers.


# Description
Question formats import plugin 

==============================

This plugin contains support for importing of only multichoice questions in CSV format file in question bank.
The CSV format is a very simple way of creating multiple choice questions using a CSV(Comma separated value) file.
The first line of CSV file must contain the headers separated with commas.
Following all the other (except Header) rows/lines contain details about the one question ie question text, four option, and answer.
Each line will contain the details about the one question.

The CSV file used for import should have the following structure :
-A CSV file with all questions in comma separated value form with a .csv extension
-The first line contains the headers separated with commas for example
  questiontext,A,B,C,D,Answer 1,Answer 2
-Next lines contain the details of the question,
  each of line contain one question text, four option, and either one or two answers again all separated by commas.
-Each line contains all the details regarding the one question ie.question text, options, and answer.
-You can also download the sample file for your reference.

IMPORTANT NOTES:

You have to save the file in a csv format. Don't save it as an Excel document or anything like that.
Non-ASCII characters like 'quotes' can cause import errors.
To avoid this always save your text file in UTF-8 format (most text editors, even libre office, will ask you).
The Header must be as it is shown in the example everything is case sensitive as shown below otherwise, the import will fail.
"Answer 2" is optional, as of now there can be maximum of two right answers of a question.
If you want have comm(,) between the text may be in question text or in options text then 
you must include that text between the double quotes(") like below in the 3rd question
where entire question is included between the double quotes like this "3, 4, 7, 8, 11, 12, ... What number should come next?"

 questiontext, A, B, C, D, Answer 1, Answer 2
 Which command is used to print a file, print, ptr, lpr, none of the mentioned, C
 Which command is used to display the operating system name?, os, unix, kernal, uname, D
 "3, 4, 7, 8, 11, 12, ... What number should come next?", 7, 10, 14, 15, D

You can also see the 'Answer 2' is optional as 1st and the 3rd question has only one answer whereas 2nd question has two answers.
Questions imported in question bank can also be imported when creating a quiz from the question bank.
