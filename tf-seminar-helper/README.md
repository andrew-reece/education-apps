<h2>Workflow App for Harvard faculty/teaching fellows (2013)</h2>

Git repository for the Teaching Workflow App for teaching staff in seminar-style courses.

There are a huge amount of tasks in weekly student-faculty workflow that can be automated,
especially now that Harvard has integrated its mail/web services with Google Apps.  

For Fall semester 2013 I created this application suite to make my job easier as a teaching fellow
for Psych 2500 (Proseminar in Social Psychology).  Every week we had a different professor teach
class, and students submitted weekly papers to each respective prof.  Student-faculty workflow
then consisted of the following steps:

Students write papers
Papers are uploaded to course shared drive
TF collects papers, emails to faculty
Faculty read & grade papers, then send back to TF
TF records grades in student spreadsheets
TF emails papers back to students

The majority of these steps requires repetitive tasks on the part of the TF (eg. emailing,
entering grades in spreadsheets, etc).  There's no reason these can't be automated!  So I convinced
the course head to let me port the entire web presence for the course over to Google Apps (Drive,
Gmail, Docs, Sheets, GCal).  That allowed me to use Google Apps' scripting functionality to automate much 
of this workflow.  Once the script was in place, it took care of the following steps on its own,
at a specified time each week:

- collects student papers from shared drive
- zips papers, email zip file to each week's faculty member
- scans my email inbox for returned papers from faculty
- grabs returned papers, parses them to extract grade
- stores grade in master grading spreadsheet 
- master sheet propagates grade info to students' individual grading records
- uploads graded papers to secure location on shared drive
- sends students links to their graded papers

The script also contains functions for similar workflow models for exams, which occurred three 
times over the course of the semester.

I estimate that even including development time (30-40 hours), this script saved me at least
30 hours of work over the semester, not to mention freeing up my Sunday evenings!  Furthermore,
it can be re-used (with minor modifications) by anyone TF'ing a seminar-style class.  I tried to 
write the code so that course-specific adaptations can be made all in one function, and the
rest of the code remains modular.  (If it ever really becomes popular, I suppose I could write
a web interface that allows for modification of basic course parameters so that other TFs never
have to go into the source code.  But for now, it's still pretty easy to modify.)


The registry is written entirely in Javascript, using Google Apps Scripting/Google APIs

Contact info: andrew.garrett.reece@gmail.com