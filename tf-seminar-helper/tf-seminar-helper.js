//####################################################################################################
//####################################################################################################

/* 
   Scripting app for Psych2500: Proseminar in Social Psychology
   Created: Fall 2013
   Author: Andrew Reece (TF)
   Contact: reece@g.harvard.edu
*/

//####################################################################################################
//####################################################################################################




//                    ENTER COURSE-SPECIFIC VALUES IN THIS FUNCTION 

//                     [Function Name: initializeCourseVariables()]

//####################################################################################################
//####################################################################################################

// name: initializeCourseVariables()
// purpose: assigns course/user-specific variables for locating folders, spreadsheets, etc
//          since everything must be a function in Google Scripts, this is where a user hard codes their folder/file ids & paths
//          [we may be able to set these vars via Google Forms for a more generic, user-friendly interface]
// input: none
// output: Array(String a, String b, ..., String n)


function initializeCourseVariables() {

/////////////
//  FOLDERS
///////////// 
  
  //   
  // Enter folder name: root folder name for entire course on Google Drive
  //
  var root = "Psych 2500/"
  
  //   
  // Enter folder name: Administrative root directory (Admin; must be subfolder of root)
  //  
  var admin_root = root + "Admin/"

  //   
  // Enter folder name: students upload assignments here (Student)
  //  
  var paper_submission_folder = "Weekly Comments"

  //   
  // Enter folder name: students' completed assignments are kept here (Admin; if applicable)
  //  
  var admin_papers_storage_folder = "_Comments/"

  //   
  // Enter folder name: exams are kept here (Admin)
  //  
  
  var admin_exams = "_Exams/"
  
  //   
  // Enter folder name: master grading spreadsheets are kept here (Admin)
  //  
  var admin_grading = admin_root + "_Grading/"

  //
  // Enter folder PATH: containing uploaded assignments from students
  // eg. "CourseName/Students/Uploads"
  //
  var folder_student_uploads = root + paper_submission_folder
  
  //
  // Enter folder PATH: master/admin folder where student papers are stored
  // eg. "CourseName/Admin/Records"
  //
  var folder_admin_papers = admin_root + admin_papers_storage_folder
  
  // Nov 4 2013 - something has gone screwy with the folder paths, not sure why
  //              so for now, we are switching to hard-coding folder ID refs
  var ID_folder_admin_papers = "0By1RU2ey21bfbHJOVjRITHJzNXM"
  //
  // Enter folder PATH: exams live here
  // eg. "CourseName/Admin/Exams"
  //  
  var folder_admin_exams = admin_root + admin_exams
  
  
  
/////////////
//   FILES
/////////////  
  
  //   
  // Enter file ID: email template for 1-week-before reminders to faculty 
  // (Google Spreadsheet; contains email elements)
  //
  var sheet_reminder_email = "0Ai1RU2ey21bfdFFuZFhrRHJzTHd5dHBVTFhWa0cxWFE"
  
  //   
  // Enter file ID: email template for faculty when they receive student assignments 
  // (Google Spreadsheet; lists class date, faculty name per class, faculty email)
  //  
  var sheet_sendpapers_email = "0Ai1RU2ey21bfdGd5UUltcUtzc1FGSmFqWWFxYzV4aVE"
  
  //   
  // Enter file ID: course scheduling info 
  // (Google Spreadsheet; lists class date, faculty name per class, faculty email)
  //  
  var sheet_class_schedule = "0AkYlsx9j8KGqdHNLdXUtMGJaazMwOUQtMGRyQ0VSYmc"
  
  //   
  // Enter file ID: syllabus 
  // (PDF; entire course syllabus)
  //    
  var pdf_syllabus = "0B0Ylsx9j8KGqcnNuTG40YW9BZUk"
  
  //   
  // Enter file NAME: master grading sheet for weekly comments 
  // (Google Spreadsheet)
  //    
  var sheet_comment_grades = "_Comments Master"
  
  //   
  // Enter file NAME: master URL list for graded/feedbacked weekly comments 
  // (Google Spreadsheet)
  //     
  var sheet_doc_urls = "_Documents Master"
  
  //   
  // Enter file NAME: exam 1
  // (Google Spreadsheet)
  //     
  var doc_exam1 = "1YQWDc-1hDvW88aRDuPhZSM7FrTNKrgRSO15TN4ms7uc"
  
  
  //
  // for demo only - remove when done
  //
  //var sheet_class_schedule = "0Ai1RU2ey21bfdGtfX0NQMV9ZN2R2LWFudUF2SWFia3c"
  //var sheet_comment_grades = "_Comments Master DEMO"
  //var sheet_doc_urls = "_Documents Master DEMO"
  
   
  
/////////////
//   MISC
/////////////  
    
  //
  // Enter time (24:00 format) for when papers are due each week
  //       (this is so the daemon knows when to run)
  //
  var deadline = new Array()
  deadline[0] = 17   // enter hour
  deadline[1] = 15   // enter minute
  
  //
  // Enter the opening string of the document title format for submitted assignments
  //       eg. for format 'MyCourse.SurnameFirstname.Date.docx', enter 'MyCourse'
  //       (for regex)
  //
  var title_head = "Psych2500\."
  
  var student_name_list = "Bercovitz,Cao,Coombs,Dillon,Fernandes,Hammond,Insel,Jing,Lee,Levari,Ngnoumen,Sezer,Was"
  
  
// return dictionary of all variables set here
  
  return {'admin exams': folder_admin_exams, 
          'admin papers':folder_admin_papers,  
          'admin papers ID':ID_folder_admin_papers,
          'admin papers subfolder': admin_papers_storage_folder, 
          'admin root': admin_root, 
          'comment grade sheet': sheet_comment_grades, 
          'deadline': deadline, 
          'doc title head': title_head,
          'doc url sheet': sheet_doc_urls, 
          'email - send files to faculty': sheet_sendpapers_email, 
          'email - 1 week reminder': sheet_reminder_email, 
          'exam1':doc_exam1, 
          'grading': admin_grading, 
          'paper submission folder': paper_submission_folder, 
          'root': root, 
          'schedule': sheet_class_schedule, 
          'student list': student_name_list,
          'student papers': folder_student_uploads, 
          'syllabus': pdf_syllabus}

         
}

//####################################################################################################
//####################################################################################################






//                  SPECIFY TIME-TRIGGERS IN THIS NEXT FUNCTION 

//                     [Function Name: createTimeTrigger()]

//####################################################################################################
//####################################################################################################

// name: createTimeTrigger()
// purpose: 
//          
// input: none
// output: none (but starts daemon)
//
// from: https://developers.google.com/apps-script/managing_triggers_programmatically

// NB: Google only guarantees that scripts will run within a 15 minute window of the time you specify.
//     In particular, if you definitely don't want a script to run before a certain time, set it to run at least 15 min after that time 

function createTimeTrigger() {
  
  // runs sendWeeklyComments - grabs student assignments and sends to faculty
  // activates at 17:20 every Sunday
   var everySunday = ScriptApp.newTrigger("sendWeeklyComments")
   .timeBased()
   .onWeekDay(ScriptApp.WeekDay.SUNDAY)
   .atHour(17)
   .nearMinute(20)
   .everyWeeks(1)
   .create();
    
  // runs returnComments - grabs graded papers from my inbox, scrapes grades, returns to students
  // activates at 11:45 every Monday
   var everyMonday = ScriptApp.newTrigger("returnComments")
   .timeBased()
   .onWeekDay(ScriptApp.WeekDay.MONDAY)
   .atHour(11)
   .nearMinute(45)
   .everyWeeks(1)
   .create();
  
  // runs sendReminderToFaculty - emails faculty member each week with a reminder about teaching
  //      (this function only really makes sense for a course where there are multiple instructors alternating throughout the semester
  // activates at 9:30 every Monday
    var remindFaculty = ScriptApp.newTrigger("sendReminderToFaculty")
   .timeBased()
   .onWeekDay(ScriptApp.WeekDay.MONDAY)
   .atHour(11)
   .nearMinute(30)
   .everyWeeks(1)
   .create();
}

//####################################################################################################
//####################################################################################################






//           DON'T MESS WITH THE REST OF THESE FUNCTIONS UNLESS YOU KNOW WHAT YOU'RE DOING

//                         [functions are listed alphabetically from here on]


//####################################################################################################
//####################################################################################################

// name: adjustCommentsMaster(surname, grade, data)
// purpose: 1) find student name, class date
//          2) enter the grade scraped from comment paper into the master spreadsheet for comment grades
//          3) grade then propagates from the master spreadsheet to students' private individual spreadsheets
//
// input:  String surname, String grade, String date
// output: none

function adjustCommentsMaster(data, date) {
  
    var vars = initializeCourseVariables()
    
    // get file directory paths
    var master_dir = vars["grading"]
    var comments_master_sheet_name = vars["comment grade sheet"]
    
    // load actual Apps file objects so we can manipulate them
    var comments_master_file = DocsList.getFolder(master_dir).find(comments_master_sheet_name)[0]
    var comments_master_sheet = SpreadsheetApp.open(comments_master_file)
    
    // transpose the spreadsheet matrix so it's in a format we can use
    var sheet_data = arrayTranspose(comments_master_sheet.getDataRange().getValues())
    
    // surname-grade pairs are passed in as a 2D array
    // we actually want them more like two columns side-by-side...so we transpose
    //    (probably an easier way to do this)
    var tdata = arrayTranspose(data)
    
    // initialize empty array 
    var students_list = new Array()
    
    // find the column we want to fill with grades
    // the header cell in each column has a mm-dd date - so we just match against the date that was passed in
    for (var j = 0; j < sheet_data.length; j++) {
      if (sheet_data[j][0] == date) {
        var col = j + 1
        break
      }
    }
    Logger.log('date: '+date+' and col: '+col)

  // i don't remember why we need to make tdata[1] initialized into a new array.
  var grades = new Array(tdata[1])
  Logger.log(grades)
  Logger.log('url col: '+col)
  // update the grades values in the 'col' column of our spreadsheet 
  comments_master_sheet.getSheets()[0].getRange(2, col, tdata[0].length).setValues(arrayTranspose(grades))
  
}

//####################################################################################################
//####################################################################################################

// name: adjustDocsMaster(url, surname, date)
// purpose: 1) find student name, class date
//          2) enter the url linking to their graded comment paper into the master spreadsheet for graded papers
//          3) url then propagates from the master spreadsheet to students' private individual spreadsheets
//          
// input:  String url, String surname, String date
// output: none


function adjustDocsMaster(data, date) {
    
    var vars = initializeCourseVariables()

    // get file directory paths    
    var master_dir = vars["grading"]
    var docs_master_sheet_name = vars["doc url sheet"]
    
    // load actual Apps file objects so we can manipulate them
    var docs_master_file = DocsList.getFolder(master_dir).find(docs_master_sheet_name)[0]
    var docs_master_sheet = SpreadsheetApp.open(docs_master_file)
    
    // transpose the spreadsheet matrix so it's in a format we can use
    var sheet_data = arrayTranspose(docs_master_sheet.getDataRange().getValues())
    
    // surname-grade pairs are passed in as a 2D array
    // we actually want them more like two columns side-by-side...so we transpose
    //    (probably an easier way to do this)    
    var tdata = arrayTranspose(data)
    
    var students_list = new Array()
    
    var urls2d = new Array(tdata[1])
    
    for (var j = 0; j < sheet_data.length; j++) {
      if (sheet_data[j][0] == date) {
        var this_date = sheet_data[j][0]
        var col = j+1 
      }
    }
    for (var i = 1; i < sheet_data[0].length; i++) {
      var student = sheet_data[0][i]
      
      students_list.push(student)
      
    }

  //Logger.log('tdata url: '+tdata)
  //Logger.log('url data0: '+tdata[0])
  //Logger.log('url data1: '+tdata[1])
  Logger.log('url col: '+col)
  docs_master_sheet.getSheets()[0].getRange(2, col, tdata[0].length).setValues(arrayTranspose(urls2d))
  
}

//####################################################################################################
//####################################################################################################

// name: arrayTranspose(data)
// purpose: given a 2d Array, this function returns the transposed table
//          from https://developers.google.com/apps-script/guides/sheets
// input:  Array data
// output: Array ret
//         eg. arrayTranspose([[1,2,3],[4,5,6]]) returns [[1,4],[2,5],[3,6]]

function arrayTranspose(data) {
  if (data.length == 0 || data[0].length == 0) {
    return null;
  }

  var ret = [];
  for (var i = 0; i < data[0].length; ++i) {
    ret.push([]);
  }

  for (var i = 0; i < data.length; ++i) {
    for (var j = 0; j < data[i].length; ++j) {
      ret[j][i] = data[i][j];
    }
  }

  return ret;
}

//####################################################################################################
//####################################################################################################

// name: escapeForRegex(s)
// purpose: formats strings with appropriate escape characters for regex use
//          from: http://stackoverflow.com/questions/3561493/is-there-a-regexp-escape-function-in-javascript
// input: String s
// output: String s
//         eg. Hello it\'s me. -> Hello it\'s me\.


function escapeForRegex(s) {
    return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')
};

//####################################################################################################
//####################################################################################################

// name: findTargetMessage(ms)
// purpose: 
//          
// input:  
// output: 
//         eg. 

function findTargetMessage(ms, regex) {

 var target = null
 
 for (var j = 0; j < ms.length; j++) {
   
  for (var k = 0; k < ms[j].length; k++) {
     
   var this_msg = ms[j][k]
     var from = this_msg.getFrom()
     Logger.log('looking for: '+regex+ ' and have: '+from)
     var match = from.match("<"+regex+">")
     if (match) {
        Logger.log('found match'+from)
        var attached = this_msg.getAttachments()  
     }
  }
   
   if (!!attached) { return attached; break; }
 }  

 return attached
 
}

//####################################################################################################
//####################################################################################################

// name: formatDateString(str)
// purpose: takes a standard Date object and chops off the time elements (mm:ss etc)
//          see Date specs at: http://docs.oracle.com/javase/6/docs/api/java/text/SimpleDateFormat.html
// input: Date object
// output: String date
//         eg. Thu Mar 17 1980

function formatDateString(str) {  
  var dateArray = String(str).split(" ")
  dateArray.length=4 
  date = dateArray.join(" ")
  return date;
}

//####################################################################################################
//####################################################################################################

// name: getAdminUploadFolder(path)
// purpose: returns Google Drive folder object for depositing files
// input: String id
// output: Folder object

function getAdminUploadFolder(path) {
  //Logger.log(path)
 return DocsList.getFolder(path); // TF folder ID tag 
}

//####################################################################################################
//####################################################################################################

// name: getDateInfo(addend)
// purpose: generates date data based on either today or tomorrow's date (addend=0 for today, =1 for tomorrow)
//          (this function runs on sundays but needs monday date strings to effectively search spreadsheet)
// input: Integer addend
// output: Array(String verbose, String terse, RegExp regex)
//         verbose   = EEE MMM DD YYYY  | eg. Thu Mar 17 1980
//         terse     = MM-DD            | eg. 03-17
//         regex     = ^MM-DD.*         | eg. ^03-17.*

function getDateInfo(addend) {
  
   var d = new Date()
   
  // this program will run on Sunday night, and the spreadsheet with class dates have Monday dates
  // so we need to tell it to search for a day one day ahead of the actual date when it runs
  // hence getDate() + 1
  d.setDate(d.getDate() + addend)
  
  // verbose form 
  var verbose = d.toDateString()
  
  // TEST ONLY
  
  //verbose = 'Mon Sep 30 2013'
  
  // terse form
  var day = d.getDate()
  var month = d.getMonth() + 1
  if (month < 10) { month = '0' + month } 
  if (day < 10) { day = '0' + day }
  var terse = month+"-"+day
  
  // TEST ONLY
  //terse = '09-30'
  
  // regex (using terse)
  var regex = new RegExp("^"+terse+".*")
  
  
  return Array(verbose, terse, regex)
}

//####################################################################################################
//####################################################################################################

// name: getEmailAttachments(msg)
// purpose: 
//          
// input:  
// output: 
//         eg. 

function getEmailAttachments(msg) {
  var attachments = msg
  //var attachments = msg.getAttachments();
  if (!!attachments) {
    var is_zip = (attachments[0].getContentType() == 'application/zip') ? true : false
  
    if (is_zip) {
      return Utilities.unzip(attachments[0])
    } else {
      return attachments 
    } 
  }
}

//####################################################################################################
//####################################################################################################

// name: getEmailSinceYesterday(cutoff_time)
// purpose: Access Gmail inbox of account running the script
//          Return all messages received after Sunday 5pm
//             
// input: Integer hr, Integer min (24:00 format)
// output: Message[]

function getEmailSinceYesterday(hr, min) {
 
 // we want a 'yesterday' date to mark a cutoff for searching our inbox
 var yesterday = new Date()
 yesterday.setDate(yesterday.getDate() - 1)
 yesterday.setHours(hr,min)
 
 // Grab all threads of Gmail inbox of the account running this script
 // Current account: dharmahound@gmail.com
 //         NB: g.harvard seems to have set restrictions on DriveApp methods
 //          ...the functions that use them here get bounced on my g.harvard acct.
 var total_threads = GmailApp.getInboxThreads();
  
 var targets = new Array()
 
 var subject = "Psych2500: Students' Comments [PLEASE READ AND GRADE BEFORE CLASS]"
 
 // loops through threads and excludes those dated before yesterday's paper submission cutoff
 // stores resulting messages in 'msgs' array
 for (var i = 0; i < total_threads.length; i++) {
   
   //test
   Logger.log(total_threads[i].getFirstMessageSubject())
   Logger.log(yesterday+ ' and message date: '+total_threads[i].getLastMessageDate())
   if ((total_threads[i].getFirstMessageSubject() == subject) && (total_threads[i].getLastMessageDate() >= yesterday)) {
     // if most recent message in thread is from later than 5:15pm yesterday, add to our targets array
     // 5:15 is when the 'sendWeeklyComments()' function runs on Sundays...this script runs on Monday morning
     targets.push(total_threads[i].getMessages())
     Logger.log('found a fitting email!')
   }
          
 } 
  
 return targets
}

//####################################################################################################
//####################################################################################################

// name: getFacultyInfo(classdate, id)
// purpose: get name, email for faculty scheduled to teach on a given date
//          ie. accesses spreadsheet which holds columns: class date, faculty name, faculty email
// input:  String classdate [eg. Thu Mar 17 1980], String class-schedule spreadsheet id
// output: Array (name, email)

function getFacultyInfo(classdate, id) {
  
  var faculty_list = SpreadsheetApp.openById(id) 
  var data = arrayTranspose(faculty_list.getDataRange().getValues())
  var dates = data[0]
  var names = data[1]
  var emails = data[2]
  
 for(d in dates) {
    var d_str = formatDateString(dates[d])
    if (d_str == classdate) {
      var this_date = d_str
      var this_name = names[d]
      var this_email = emails[d]
    }
  }
  
  return Array(this_name, this_email)
}

//####################################################################################################
//####################################################################################################

// name: getTargetFiles(root, date)
// purpose: 
//          
// input:  String root, String date
// output: File[] 
//         eg. 

function getTargetFiles(root, date) {
  
  var student_upload_folder = DocsList.getFolder(root) // Weekly Comments folder ID tag
  
  // this gets the list of subfolders inside the parent uploads folder, ie. folders for each week 
  // naming convention: MM/DD [Faculty Surname] - [Listed Topic]
  var upload_subfolders = student_upload_folder.getFolders();
  
  // iterate through weekly folder names
 for (var i = 0; i < upload_subfolders.length; i++) {
    
    var fname = upload_subfolders[i].getName();
   
    var date_regex = new RegExp("^"+date+".*")
    
    // search folder names for one that has our target date
    if (date_regex.exec(fname)) {
      
      // when we have a match (there should be only one), get all of the files from that folder
      // these are the students' individual comment docs
      var target_dir = upload_subfolders[i]
      break;
    }
  }
  
  return target_dir.getFiles();
}

//####################################################################################################
//####################################################################################################

// name: navigateXmlDir(file, path)
// purpose: We want to scrape .docx files.  But those are actually zipped XML libraries!  
//          This function unzips and finds the root XML doc with all the document text
//          
// input:  File docx_file, String directory_path
// output: File xml_root_file
//         eg. navigateXMLDir(File reece.docx, String 'My2500/papers/')
//             -> reece.xml

function navigateXmlDir(file, path) {
  
  // specifically direct Apps to treat this file as .zip
  file.setContentType('application/zip')
  
  // 
  var this_file = path.createFile(file)
  var fid = this_file.getId()
      
  // unzips word doc [who knew word docs are zip files?]
  var this_doc = Utilities.unzip(DocsList.getFileById(fid))
  var target = null
      
  // goes through folder/file directory unzipped from .docx packet
  for (var x = 0; x < this_doc.length; x++) {
     if (!!this_doc[x]) {
        if (this_doc[x].getName() == 'word/document.xml') { target = this_doc[x]; break; }
     }      
  } 
  
  this_file.setTrashed(true)
  
  return target
}

//####################################################################################################
//####################################################################################################

// name: returnComments()
// purpose: 1) checks Gmail inbox for messages from this week's faculty (sent after submission date)
//          2) grabs attachments from most recent email
//          3) opens each individual document, scans for student name and grade
//          4) enters grade into master grade sheet (which then copies over into students' private sheets)
//          5) puts link to graded paper document in master sheet (copies into students' private sheets)
// input:  none
// output: none


function returnComments() {
  
 // get course specific variables (folder/spreadsheet ids)
 var local_vars = initializeCourseVariables()
 var master_folder = local_vars['admin papers']
 var student_uploads = local_vars['student papers']
 var course_schedule = local_vars['schedule']
 var submission_deadline = local_vars['deadline']
 var doc_title_head = local_vars['doc title head']
 var student_list = local_vars['student list']
 
 // get date info in verbose, regex, and terse form (see getDateInfo() help for more)
 var date_info = getDateInfo(0)
 var date_verbose = date_info[0]
 var date_mm_dd  = date_info[1]
 var date_regex = date_info[2]
 
 var faculty_data = getFacultyInfo(date_verbose, course_schedule)
 var faculty_name = faculty_data[0]
 var faculty_email = faculty_data[1] 
 var regex_email = escapeForRegex(faculty_email)
 
 if (!(faculty_name == "NOCLASS")) {
   
   var submit_hr = submission_deadline[0]
   var submit_min = submission_deadline[1]
   
   var msgs = getEmailSinceYesterday(submit_hr, submit_min)
   
   var target_msg = findTargetMessage(msgs, regex_email)          
   
   var files = getEmailAttachments(target_msg)
   
   // admin_folder_path is where we want students' zipped comments for the week to be stored on our Admin directory
   // Directory naming convention: MM-DD-FacultySurname
   var admin_folder_path = master_folder+date_mm_dd+"-"+faculty_name
   
   // Folder object for master admin folder - where we upload a copy of graded papers
   var admin_upload_folder = getAdminUploadFolder(admin_folder_path); 
   
   
   var catch_errors = new Array()
   
   var grade
   var all_grades = new Array()
   
   var surname
   var all_surnames = new Array()
   
   var all_doc_urls = new Array()
   
   for (var k = 0; k < files.length; k++) {
     
     var fname = files[k].getName()
     var this_student = student_list[k]
     
     if (fname.match(/\.doc$/)) {
       
       var rawdata = files[k].getDataAsString()
       var is_xml = false
       
       var doc_info = uploadDocVersion(admin_upload_folder, files[k])
       var doc_id = doc_info[0]
       var doc_url = doc_info[1]
       all_doc_urls.push(doc_url)
       
       if (!!rawdata) {
         var scraped_data = scrapeXmlDoc(rawdata, is_xml)
         if (!!scraped_data[1]) { 
           surname = scraped_data[1] 
         } else {
           surname = this_student+"?" 
           catch_errors.push("Name couldn't be scraped from doc!")
         }
         all_surnames.push(surname)
         
         if (!!scraped_data[0]) { 
           grade = scraped_data[0][1] 
           
         } else { 
           grade = "?"  
           catch_errors.push("Grade not found for: "+surname)

         }
         all_grades.push(grade)
         
         // test
          Logger.log('Name: '+surname+' and grade: '+grade)
       }
       
     } else if (fname.match("^"+doc_title_head+".*\.docx")) {
       
       var doc_info = uploadDocVersion(admin_upload_folder, files[k])
       var doc_id = doc_info[0]
       var doc_url = doc_info[1]
       all_doc_urls.push(doc_url)
       var is_xml = true
       
       var target_dir = navigateXmlDir(files[k], admin_upload_folder)
       
       if (!!target_dir) {
         
         var scraped_data = scrapeXmlDoc(target_dir, is_xml)
         
         if (!!scraped_data[1]) { 
           surname = scraped_data[1] 
         } else {
           surname = "?" 
           catch_errors.push("\nName couldn't be scraped from doc!")
         }
         all_surnames.push(surname)
         
         if (!!scraped_data[0]) { 
           grade = scraped_data[0][1] 
           
         } else { 
           grade = "?"
           catch_errors.push("\nGrade not found for: "+surname)  
         }
         all_grades.push(grade)
         
         
         // test
          Logger.log('Name: '+surname+' and grade: '+grade)
       } 
     }
   }
   
   // if there were any exceptions caught in the doc scraping, send me an email alert listing them
   if (catch_errors.length > 0) { 
     
      var reason = 'ERRORS'
     
      sendAlert(reason, catch_errors)
     
   }
   
   // mimics Python's zip() - mushes two arrays into a 2D array
   // here we do this for surnames and grades/urls, respectively
   var grade_data = zip([all_surnames, all_grades])
   var url_data = zip([all_surnames, all_doc_urls])
   
   // 2D-array sort, from: http://stackoverflow.com/questions/6490343/sorting-2-dimensional-javascript-array
   grade_data.sort(function(a, b) { return (a[0] < b[0] ? -1 : (a[0] > b[0] ? 1 : 0)); });
   url_data.sort(function(a, b) { return (a[0] < b[0] ? -1 : (a[0] > b[0] ? 1 : 0)); });

   
   adjustCommentsMaster(grade_data, date_mm_dd)
   adjustDocsMaster(url_data, date_mm_dd)
   
 } else {    // for days with no class
   
    var reason = 'NOCLASS'
   
    // sends me an email stating that daemon ran and found no class this week
    sendAlert(reason);

 }
}

//####################################################################################################
//####################################################################################################

// name: scrapeXmlDoc(dir)
// purpose: Reads text of the first node in an XML document - which, for unzipped .docx files, contains the entire text
//          Searches for specific patterns that identify where grades and student names are entered
//          Returns grade and surname
//          
// input:   Document dir (an XML document) ... or Blob dir (in the case that it's .doc and not .docx)
// output:  String grade, String surname
//         eg. take document mypaper.docx (unzipped)
//             return Array('2.5', 'Reece')

function scrapeXmlDoc(dir, xml) {
  
  var grade
  var surname
  
  if (xml) {
   var nodes = XmlService.parse(dir.getDataAsString()).getDescendants()
        
   var grade = nodes[0].getValue().match(/grade in box below\: *(([0-3]{1}\.?[0-9]{0,2})|(<?[AB]{1}[\+\-]?))/)
   
  if (!grade) {   
     grade = nodes[0].getValue().match(/Grade\: *([0-3]{1}\.?[0-9]{0,2})/)
   }
    
   if (grade == 'A') {
     var grade = 3
   } else if (grade == 'A-') {
     var grade = 2
   } else if (grade == 'B+') {
     var grade = 1
   } else if (grade == '<B+') {
     var grade = 0 
   }
    
   var surname = nodes[0].getValue().match(/Name\: *[A-Z]?[a-z]*( [A-Z]{1}\.?)? ([A-Z]?[a-z]*)\t*/).pop()

   return Array(grade, surname)
   
  } else {
    
   var grade = dir.match(/(Feedback \(optional\)\:\t*([0-3]{1}\.?[0-9]{0,2}))|(grade in box below\: *([0-3]{1}\.?[0-9]{0,2}))/)
   if (!grade) {   
     grade = dir.match(/Grade\: *([0-3]{1}\.?[0-9]{0,2})/)
   }
   // test
    Logger.log('grade match: '+grade)   
   var surname = dir.match(/Name\: *[A-Z]?[a-z]*( [A-Z]{1}\.?)? ([A-Z]?[a-z]*)\t*/)
   if (!!surname) { surname = surname.pop() } else { surname = 'noname' }

   // test
   // Logger.log('surname: '+surname)
   return Array(grade, surname)
  }
}

//####################################################################################################
//####################################################################################################

// name: sendAlert(reason, data[optional])
// purpose: Sends email alerts to admins
//          
// input:   String reason, Array data [optional]
// output:  none (sends email)
//         eg. reason = 'ERRORS'
//             send email listing errors caught from a given function

// NB: better just to have 'reason' var activate different spreadsheets containing the template data...

function sendAlert(reason, data) {
  
  var to = 'reece@g.harvard.edu'
  
  var from = 'Psych2500 Daemon'
  
  var subject
  var body
  
  if (reason == 'NOCLASS') {
    
    subject = 'No 2500 class this week'
    body = 'That is all there is to discuss.  Thanks!'
    
  } else if (reason == 'ERRORS') {
    
    subject = 'Errors in returnComments() found'
    body = 'Hi, I just ran returnComments() for Psych2500.  Errors occurred.  This is what happened:\n\n'+data
  }
  
  
  MailApp.sendEmail({
    to: to,
    from: from,
    subject: subject,
    htmlBody: body,  
  });  
  
}

//####################################################################################################
//####################################################################################################

// name: sendFilesToFaculty(id, name, email, files)
// purpose: send email to faculty with zipped files
//          
// input: String name, String email, Blob files
// output: none (sends email)

function sendFilesToFaculty(id, name, email, files) {
 
  var parts = SpreadsheetApp.openById(id) 
  var data = arrayTranspose(parts.getDataRange().getValues())
  
  // email parameters:
  var title = data[0][1]
  var from = data[1][1]
  var cc = data[2][1]
  var bcc = data[3][1]
  var greeting = data[4][1] + ' ' + name // eg. "Dear Professor" + " " + "Feynman"
  var body = greeting + data[5][1]
  


 
 // email address should be dynamically assigned to different faculty based on the date
 // have a separate spreadsheet with date/faculty name/email that we pull this data from
 MailApp.sendEmail({
   to: email,
   cc: cc,
   bcc: bcc,
   name: from,
   subject: title,
   htmlBody: body,  
   attachments: files
 }); 
}

//####################################################################################################
//####################################################################################################

// name: sendReminderToFaculty(id, name, email)
// purpose: send email reminder to faculty one week ahead of their scheduled teaching date
//          
// input: String name, String email
// output: none (sends email)

function sendReminderToFaculty() {
 
  // NB: this function is set up only to send reminders 7 days in advance
  // ...to tweak this, we'd make 'days-in-advance' an argument
  
  // get date info in verbose, regex, and terse form (see getDateInfo() help for more)
  var date_info = getDateInfo(7)
  var date_verbose = date_info[0]
  
  // get course specific variables (folder/spreadsheet ids)
  var local_vars = initializeCourseVariables()
  var course_schedule = local_vars['schedule']
  var email_template = local_vars['email - 1 week reminder']
  var syllabus = local_vars['syllabus']

  var faculty_data = getFacultyInfo(date_verbose, course_schedule)
  var faculty_name = faculty_data[0]
  var faculty_email = faculty_data[1] 
    
  // string formatting of date (to make email prettier)
  var month_names = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
  var weekday_names = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]
  var d = new Date() 
  d.setDate(d.getDate() + 7)
  var day_of_the_month = d.getDate()
  var day_of_the_week = weekday_names[d.getDay()-1]
  var month = month_names[d.getMonth()]  

  // initialize email vars
  // (we do this in the case of "NOCLASS", for which these vars should be null)
  var cc
  var bcc
  var attachment
  var from
  
  if ((faculty_name == "NOCLASS") || (!faculty_name)) {

    var title = "No 2500 class next week"
    var body = "There's no class next week ("+ day_of_the_week + ", " + month + " " + day_of_the_month+")"
   
  } else {
        
    var email_content = SpreadsheetApp.openById(email_template) 
    var data = arrayTranspose(email_content.getDataRange().getValues())

    // email parameters:
    var title = data[0][1]
    var from = data[1][1]
    var cc = data[2][1]
    var bcc = data[3][1]
    var greeting = data[4][1] + ' ' + faculty_name + ",<br /><br />" // eg. "Dear Professor" + " " + "Feynman, <br /><br />"
    var opening_line = data[5][1] + day_of_the_week + ', ' + month + ' ' + day_of_the_month
    var main_body = data[6][1] 
    var body = greeting + opening_line + main_body
    var attachment = DriveApp.getFileById(syllabus) 
    
  }

  MailApp.sendEmail({
    to: faculty_email,
    cc: cc,  // this goes to Mahzarin, be careful when you're testing!
    bcc: bcc,
    name: from,
    subject: title,
    htmlBody: body,  
    attachments: attachment
  }); 
}

//####################################################################################################
//####################################################################################################

// name: sendWeeklyComments()
// purpose: 1) finds folder with target files
//          2) zips files
//          3) sends email with .zip attachment
// input:  String classdate [eg. Thu Mar 17 1980], String id [eg. Google file id string]
// output: Array (name, email)

function sendWeeklyComments() {
  
  // get date info in verbose, regex, and terse form (see getDateInfo() help for more)
  var date_info = getDateInfo(1)
  var date_verbose = date_info[0]
  var date_mm_dd  = date_info[1]
  var date_regex = date_info[2]
  
  // get course specific variables (folder/spreadsheet ids)
  var local_vars = initializeCourseVariables()
  var master_folder = local_vars["admin papers"]
  var student_uploads = local_vars["student papers"]
  var course_schedule = local_vars["schedule"]
  var template = local_vars["email - send files to faculty"]
  
  var faculty_data = getFacultyInfo(date_verbose, course_schedule)
  var faculty_name = faculty_data[0]
  var faculty_email = faculty_data[1]
  
  if (!(faculty_name == "NOCLASS")) {
    
    var target_docs = getTargetFiles(student_uploads, date_mm_dd)
    
    var admin_folder_path = master_folder+date_mm_dd+"-"+faculty_name
    
    // zip all files together and dump in Admin directory subfolder for that week's assignments
    var zipped_files = getAdminUploadFolder(admin_folder_path).createFile(Utilities.zip(target_docs, 'Psych2500_'+date_mm_dd+'_ungraded.zip'));

    // send zipped files to faculty, along with grading instructions
    sendFilesToFaculty(template, faculty_name, faculty_email, zipped_files)
    
    // we don't need to store the zip file, so trash it when done
    zipped_files.setTrashed(true)
    
  } else {    // for days with no class
    
    // send an email saying there's no class to TF 
    // (put this in a function)
     MailApp.sendEmail({
       to: 'reece@g.harvard.edu',
       subject: 'No class tomorrow ['+ date_verbose + ']. [ from sendComments() daemon ]',
       htmlBody: 'There is no 2500 class tomorrow.  So no files were sent to faculty.',  
     }); 
  }
}

//####################################################################################################
//####################################################################################################

// name: uploadDocVersion(dir, f)
// purpose: 
//          
// input:  
// output: 
//         eg. 

function uploadDocVersion(dir, f) {
  
    var this_doc = dir.createFile(f)
    var doc_id = this_doc.getId()
    //var doc_name = this_doc.getName()
    //test
    //Logger.log(DriveApp.Access)
    //var doc_loc = DriveApp.getFileById("0B0Ylsx9j8KGqTUFFLWRmMmFiZDY2LTI0ZDctNGJjMy05ZTE0LTBhOTZiMGM0OTRjYw")
    //var this_doc = DriveApp.getFileById(doc_id)
    //doc_loc.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);
    var doc_url = this_doc.getUrl()
    
    return Array(doc_id, doc_url) 
    
}

//####################################################################################################
//####################################################################################################

// name: apparateExam(url, surname, date)
// purpose: 1) find student name, class date
//          2) enter the url linking to their graded comment paper into the master spreadsheet for graded papers
//          3) url then propagates from the master spreadsheet to students' private individual spreadsheets
//          
// input:  String url, String surname, String date
// output: none


function apparateExam(url, surnames, date) {
    
    var master_dir = vars["admin exams"]
    var exam1_id = vars["exam1"]
    
    var exam1 = DriveApp.getFileById(exam1_id)
    var exam1_url = exam1.getUrl()
    var docs_master_file = DocsList.getFolder(master_dir).find(exam1)[0]
    var docs_master_sheet = SpreadsheetApp.open(exam1)
    
    var comments_master_sheet_name = vars["comment grade sheet"]
    
    var comments_master_file = DocsList.getFolder(master_dir).find(comments_master_sheet_name)[0]
    var comments_master_sheet = SpreadsheetApp.open(comments_master_file)
    var sheet_data = arrayTranspose(comments_master_sheet.getDataRange().getValues())
    var sheet_data = arrayTranspose(docs_master_sheet.getDataRange().getValues())
    
    var students_list = new Array()
    
    for (var j = 0; j < sheet_data.length; j++) {
      if (sheet_data[j][0] == date) {
        var this_date = sheet_data[j][0]
        var col = j+1 
      }
    }
    for (var i = 1; i < sheet_data[0].length; i++) {
      var student = sheet_data[0][i]
      
      students_list.push(student)
    }

  urls2d = new Array(urls)
  docs_master_sheet.getSheets()[0].getRange(2, col, surnames.length).setValues(arrayTranspose(urls2d))
  
}

//####################################################################################################
//####################################################################################################

// taken from: http://stackoverflow.com/questions/4856717/javascript-equivalent-of-pythons-zip-function

function zip(arrays) {
    return arrays[0].map(function(_,i){
        return arrays.map(function(array){return array[i]})
    });
}

//####################################################################################################
//####################################################################################################

function openExam3() {
  
    var exam = DriveApp.getFileById("1kcYF5uYIM1Y-XSSEjq7rJuG41kIoJYoWdUnJ6xR3BbY")
    exam.addViewer('psych2500@googlegroups.com')
    
    var d = new Date()
    
  MailApp.sendEmail({
    to: 'psych2500@googlegroups.com',
    from: 'Psych 2500 Mailer Daemon',
    subject: 'Exam 3 is open now.',
    htmlBody: 'Good morning 2500 folks, <br /><br />Exam 3 is open now.  <br />You should have received a separate email invitation to share the exam document.  <br />If for some reason you do not have an invite, please let Andrew know right away.  Otherwise, good luck!  <br /><br />Remember to follow submission guidelines, as per the document posted to the Exam-related folder in Useful Documents.<br /><br />Thanks,<br />Your friendly mailer daemon',  
  });  
  
  MailApp.sendEmail({
    to: 'reece@g.harvard.edu',
    from: 'Psych 2500 Mailer Daemon',
    subject: 'Exam 3 timestamp',
    htmlBody: 'The exam was opened at timestamp: '+d,  
  });  
}

//####################################################################################################
//####################################################################################################

function collectExam3() {
 
  /*
  problem:
  
        each student has a private folder.  in this folder is a subfolder Exams.  
        in this subfolder are subfolders Exam 1 and Exam 2 and Exam 3. in exam 3, there is a subfolder for each of the 8 questions students could choose to answer.
        
        students upload their completed exam responses separately for each question they complete, into the appropriate question folder.
        faculty need to receive zip files with all students' responses for a given question.
        
        so, we need to:
               1) collect responses from individual student folders
               2) round up responses for each question in folders on the admin side of the shared drive
               3) mail per-question sets of responses to the faculty members who wrote the questions (so they can grade them)
  
  what is hard coded:
  
        - a list of per-question folder names 
        - a list of faculty surnames
        - directory paths to admin and student folder regions
        
  tasks at hand:
  
        1) get into students' My2500 folder, then iterate through folders there (each subfolder is for an individual student).  
        2) go into each student folder, then go into subfolder Exams, then into subfolder Exam 3
        3) now go into each of the folders in the list of folder names here 
           - each is for a faculty member and question number, eg. 'Heatherton 2'
        4) check if there's a file.  there should only be one file in any one given exam response folder per student
        5) if there's a file, copy it, give it the same name as the original
        6) deposit the copy into the admin exam folder of the same designation (eg. 'Heatherton 2')
        7) this leaves us with all the student responses for each exam question listed together in their respective folders
        8) zip all the files in each folder, send to the appropriate faculty email address 
           - use a form email that gives grading instructions, insert faculty name as appropriate
  */
  
  // folder_dict will be an assoc. array for storing Folder objects, keyed by their names
  var folder_dict = new Object()
  // these are the folder names for the folder_dict array (we'll use these elsewhere too)
  var folders = ['Greene 1', 
                 'Greene 2', 
                 'Langer 1', 
                 'Langer 2', 
                 'Sidanius 1', 
                 'Sidanius 2', 
                 'Warneken 1', 
                 'Warneken 2']
  // array of faculty surnames for grabbing email addresses later on
  var faculty = ['Langer', 'Greene', 'Warneken', 'Sidanius']
  
  // path to exam2 master folder, admin side
  var PATH_admin_exams_folder = "_Exams/exam3/"
  // folder ID, in case path gets screwy
  var ID_admin_exams_folder = "0By1RU2ey21bfMVRCbFJ6TUtQMHM"
  // path to students' master folder
  var PATH_students_folder = 'TF/Psych 2500/My 2500'
  
  // test path to demo student folders (with dummy responses posted in exam2 area)
  //var PATH_students_folder = 'Admin/_Miscellaneous/_Demo Students/'

  // Folder object for admin exams
  var admin_folder = DocsList.getFolder(PATH_admin_exams_folder)

  // assign assoc array key/val pairs
  for (var i = 0; i < folders.length; i++) {
    var this_folder = folders[i]
    folder_dict[this_folder] = admin_folder.getFolders()[i]
  }
  
  // Folder object for students area
  var my2500 = DocsList.getFolder(PATH_students_folder)
  // Folder objects for each student
  var students = my2500.getFolders()
  
  // if there isn't a single exam response found, ct will be 0 and a warning gets logged
  var ct = 0
  
  // loop through each student
  for (var i = 0; i < students.length; i++) {
    
    // log
    //Logger.log('student: '+students[i].getName())
    
    // Folder object for exams within a given student folder
    var exams_folder = students[i].getFolders()[0]
    
    // log
    //Logger.log(exams_folder.getName())
    
    // error catching structures to ensure we're getting the folders we think we're getting
    // probably unnecessary, but can't hurt
    
    if ((!exams_folder) || (exams_folder.getName() != 'Exams')) { 
      
      Logger.log('Messed up main folder structure for: '+students[i].getName()); 
      
    } else {
      
      // Folder object for exam 3
      var exam3_folder = exams_folder.getFolders()[0]
      
      if ((!exam3_folder) || (exam3_folder.getName() != 'Exam 3')) { 
      
        Logger.log('Messed up Exams folder structure for: '+students[i].getName()); 
      
      } else {
        
        // Folder objects for each question in Exam 2 (eg. Heatherton 1, Heatherton 2)
        var choices = exam3_folder.getFolders()
        
        // loop through each question folder
        for (var j = 0; j < choices.length; j++) {
          
          // if there are files in a folder (there shouldn't ever be more than one), then copy and post to admin folder
          
          var responses = choices[j].getFiles()
          
          if (responses.length > 0) {
            
            // iterate '# responses found' counter
            ct++
              
            // log 
            Logger.log('Found a response! ...in: '+students[i].getName())
            
            // get first file in array (there should be only one)
            var response = responses[0]
            
            // add to admin folder - stored in folder_dict assoc array
            response.addToFolder(folder_dict[choices[j].getName()])

          }
        }
      }
    }
  }
  
  // log error if no responses are found at all. 
  // (at actual runtime this definitely should not happen.)
  if (ct == 0) { Logger.log('No one has turned in an exam response yet.') }
  
  
  // go through each pair of faculty folders
  // zip each folder, label appropriately (eg. 'Heatherton_Q1')
  // then zip folders together
  // then mail 'em out!
    
  var ID_email_template = "0Ai1RU2ey21bfdFRUMGRCS1FYLTBZWTBTLWVYb0xWUVE"
  var ID_faculty_list = "0AkYlsx9j8KGqdHNLdXUtMGJaazMwOUQtMGRyQ0VSYmc"
  
  var faculty_list = SpreadsheetApp.openById(ID_faculty_list) 
  var data = arrayTranspose(faculty_list.getDataRange().getValues())
  var dates = data[0]
  var names = data[1]
  var emails = data[2]
  
  var zipped = new Array()

  var ct = 0
  // loop over faculty list in class schedule spreadsheet
  for (var i = 0; i < names.length; i++) {
    
    if (ct == faculty.length) { break }
    // lop over list of faculty names for this exam
    for (var j = 0; j < faculty.length; j++) {
      
      //Logger.log('names: '+names[i])
      //Logger.log('faculty: '+ faculty[j])
      
      // if we find a match, zip files and send an email
      if (names[i] == faculty[j]) {
        
        //Logger.log('match! ...on '+faculty[j])
        
        zipped[j] = Utilities.zip(
          [
            Utilities.zip(folder_dict[faculty[j]+' 1'].getFiles(), faculty[j]+'_Q1.zip'), 
            Utilities.zip(folder_dict[faculty[j]+' 2'].getFiles(), faculty[j]+'_Q2.zip')
          ], faculty[j]+'.zip')
        
        var name = names[i]
        var email = emails[i]
        var attachments = zipped[j]
        
        // for testing
        //email = 'reece@g.harvard.edu'
        
        sendFilesToFaculty(ID_email_template, name, email, attachments)
        ct++
        continue
      }  
    }
  }  
  
}

//####################################################################################################
//####################################################################################################

/// FOR TESTING PURPOSES ONLY
///
/// this function gets a list of all the folders in the root directory and prints them to Logger.log
///

function getFolderTree() {
 
  var root = DocsList.getRootFolder()
  var first_level = root.getFolders()
  for (var i = 0; i < first_level.length; i++) {
    Logger.log(first_level[i].getName()) 
  }
}