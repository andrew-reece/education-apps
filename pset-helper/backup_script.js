//####################################################################################################
//####################################################################################################

// purpose: returns calendar id string
function getCalendarId() {
  
  var id = "qhcavu6jim95ime2tovcjmje4s@group.calendar.google.com" // id for 'psets MWF' cal on dharmahound 
  
  return id
}

//####################################################################################################
//####################################################################################################

// purpose: returns spreadsheet id string
function getSpreadsheetId() {
  
  var id = "0Ai1RU2ey21bfdHZHWFc0MUFrVUcxbmY0WFRqeTVvblE"
  
  return id
}

//####################################################################################################
//####################################################################################################

// purpose: returns column index of time variable from pset-helper spreadsheet
function getTimeIndex(id) {
  
  var sheet = SpreadsheetApp.openById(id).getActiveSheet()
  var numcol = sheet.getLastColumn()
  var first_row = sheet.getRange(1,1,1,numcol).getValues()[0]
  
  for (var col in first_row) { if (first_row[col] == "time") { var time_idx = parseInt(col)+1 } }
  
  return time_idx
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

// purpose: rounds minutes to nearest half hour
function roundMinutes(id) {

  var sheet = SpreadsheetApp.openById(id).getActiveSheet()
  var numrow = sheet.getLastRow()

  var time_idx = getTimeIndex(id)
  
  var time_col = sheet.getRange( 2, time_idx, numrow-1 )
  var time_col_array = time_col.getValues()
  
  //Logger.log("TIME COL: "+time_col)

  for (var t in time_col_array) {
     
    // we subtract 2 from time_idx here to account for:
    //                                                  a) spreadsheet is 1-indexed but this is 0-indexed
    //                                                  b) we started at i = 1 to skip the timestamp column
    var time_string = time_col_array[t].toString()
    var time_chunks = time_string.split(" ")
    var time = time_chunks[4].slice(0,5)
    var hour = time.slice(0,2)
    var min = time.slice(3,5)
    var min_int = parseInt(min)
    
    if ((min_int != 0) && (min_int != 30)) {
      
      // if we have to round up to the next hour, we must change 'hour' var also
      if ((min_int >= 45) && (min_int < 60)) { min = '00'; hour = parseInt(hour)+1 }
      else if (min_int < 15) { min = '00' }
      else { min = '30' }
      
    }
  
    time = hour + ":" + min
    time_col_array[t] = time
  
  }
  
  var new_times = arrayTranspose([time_col_array])
  time_col.setValues(new_times)
  
}

//####################################################################################################
//####################################################################################################

// purpose: gets array of all cell values from spreadsheet
function getAllValues(id) {
  
  var sheet = SpreadsheetApp.openById(id)
  var range = sheet.getDataRange()
  var vals = range.getValues()
  
  return vals
}

//####################################################################################################
//####################################################################################################

// purpose: subroutine for checkMatches(), makes arrays for each column in spreadsheet
function makeCols(headers) {
  
  cols = new Array()
  
  for (h in headers) { cols[headers[h]] = new Array() }
    
  return cols
}

//####################################################################################################
//####################################################################################################

// fills column arrays with cell values
function getUserRows(vals, headers, id) {
  
  var user_rows = new Array()
  
  for (var i = 1; i < vals.length; i++) {
   
    var user_row = vals[i].slice(1)
    
    user_rows.push(user_row)
    
    //Logger.log('user_row:'+user_row)

  }
  
  return user_rows
}

//####################################################################################################
//####################################################################################################

// purpose: gets unique values in array
// from: http://stackoverflow.com/questions/1960473/unique-values-in-an-array
function onlyUnique(value, index, self) { 
  
    return self.indexOf(value) === index;
}

//####################################################################################################
//####################################################################################################

// purpose: get array of each unique combo of time/date/pset/subpset
function getUniqueSets(rows) {
    
  var noemail_rows = new Array()
  
  for (var user in rows) {
    
    var temp = rows[user].slice(1)
    
    noemail_rows.push(temp.join("_"))
    
  }
  
  var unique_sets = noemail_rows.filter(onlyUnique)  
  
  return unique_sets
}

//####################################################################################################
//####################################################################################################

// purpose: gets user rows that match each unique combo of time/date/pset/subpset
function getMatches(sets, rows) {
  
  var matches = new Array()
  
  for (var i = 0; i < sets.length; i++) {
    
    var this_set = sets[i]
    var targets = this_set.split("_")
    
    matches[i] = new Array()
    
    for (var j = 0; j < rows.length; j++) {
      
      var user_row = rows[j]
      
      var hopefuls = user_row.slice(1) // excludes first cell (email address)

      var pset_match = (hopefuls[0] == targets[0])
      var sub_pset_match = (hopefuls[1] == targets[1])
      var date_match = (hopefuls[2] == targets[2])
      var exact_time_match = (hopefuls[3] == targets[3])
      
      var hopeful_d = new Date(hopefuls[3])
      var target_d = new Date(targets[3])
      var milisec_30min = 30*60000
      var user_too_early = (hopeful_d.getTime() + milisec_30min == target_d.getTime())
      var user_too_late = (hopeful_d.getTime() - milisec_30min == target_d.getTime())
      
      if (exact_time_match)    { hopefuls[4] = "exact" }
      else if (user_too_early) { hopefuls[4] = "30_too_early" } 
      else if (user_too_late)  { hopefuls[4] = "30_too_late" } 
      else                     { hopefuls[4] = "way_off" } 
      
      var target_time = targets[3].toString().split(" ")[4]
      
      var hopeful_time = hopefuls[3].toString().split(" ")[4]
      var hour = parseInt(hopeful_time.slice(0,2))
      var min = hopeful_time.slice(3,5)
      
      if (min == 0) {
        
        var new_min = "30"
        var new_hour = hour - 1
        
        var time_forward = hour + ":" + new_min + ":00"
        var time_back = new_hour + ":" + new_min + ":00"
        
      } else if (min == 30) {
        
        var new_min = "00"
        var new_hour = hour + 1
        
        var time_forward = new_hour + ":" + new_min + ":00"
        var time_back = hour + ":" + new_min + ":00"
      }
      
      hopefuls[5] = time_forward  
      hopefuls[6] = time_back 
      
      if ((pset_match) && (sub_pset_match) && (date_match)) {
        
        if (matches[i].length == 0) {
          
          var full_info = [hopefuls[0], hopefuls[1], hopefuls[2], hopeful_time]
          var match_data = [full_info, user_row[0], hopefuls[4], hopefuls[5], hopefuls[6]]
          
        } else { var match_data = [user_row[0], hopefuls[4], hopefuls[5], hopefuls[6]] }
            
        //Logger.log('MATCH DATA: '+match_data)
        
        matches[i].push(match_data)
      }
    } 
  } 
  
  return matches
}

//####################################################################################################
//####################################################################################################

// purpose: cross refs matches on timeslot with available rooms to study
// these rooms are posted to gcal by TFs (or maybe students too?  2 calendars in that case?)
function getLocationsByTime(date, hr, min) {
 
  var cal_id = getCalendarId()
  var cal = CalendarApp.getCalendarById(cal_id)
  
  var requested_time = hr + '' + min
  
  var yr = new Date().getFullYear()
  var tmp = date.split(" ")
  var day = tmp[1]
  var month = tmp[0]
  
  // changes str to num for month (eg. 'Dec' to '11')
  var month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  for (var idx in month_names) { if (month == month_names[idx]) { month = idx } }
  
  var day_of_events = new Date(yr, month, day)
  var day_start = day_of_events.getTime()
  var events = cal.getEventsForDay(day_of_events)
  
  var loc_and_endtime = new Array()
  
  for (var ev in events) {
    
    var location = events[ev].getTitle()
    var location_good_until = events[ev].getEndTime().toString().split(" ")[4].slice(0,5)
    
    var event_start = events[ev].getStartTime()
    var tmp = event_start.toString().split(" ")
    var event_start_time = tmp[4].slice(0,2)+tmp[4].slice(3,5)
    
    var event_end = events[ev].getEndTime()
    var tmp = event_end.toString().split(" ")
    var event_end_time = tmp[4].slice(0,2)+tmp[4].slice(3,5)
    
    if ((event_start_time == requested_time) || ((event_start_time < requested_time) && (event_end_time > requested_time))) {
      
      var info = location + "_" + location_good_until
      
      loc_and_endtime.push([location, location_good_until, [yr, month, day, hr, min]]) 
    }
  }
  
  return loc_and_endtime
}

//####################################################################################################
//####################################################################################################

// purpose: changes 24-hr time to 12-hr time with AM/PM
function timeToString(hr, min) {
  
  if (hr > 12) { var time = hr-12 + ':' + min  + 'PM' } 
  else { var time = hr + ':' + min + 'AM' } 
  
  return time
}

//####################################################################################################
//####################################################################################################

// purpose: formats location and end time for email body text
function formatLocationText(tmp) {

  var text = new Array()
  
  for (idx in tmp) {
    
    var h = tmp[idx][1].slice(0,2)
    var m = tmp[idx][1].slice(3,5)
        
    text.push("       " + tmp[idx][0]+ " is available until " + timeToString(h,m) + "\n\n")
  }  
  
  return text.join("")
}

//####################################################################################################
//####################################################################################################

// purpose: format Date/Location info into email text
function getLocationText(date, hour, min) {
  
  var location_data = getLocationsByTime(date, hour, min)
  var location_info_text = formatLocationText(location_data)
  
  //Logger.log('location info: '+location_info_text)
  
  var text = (location_info_text == "") ? "" : "Alternately, you can meet up at an official study location.\n\n"
  + location_info_text 
  
  return text
}

//####################################################################################################
//####################################################################################################

// purpose: gets text of email for exact date/time/pset matches
function getExactEmailText(users, pset, date, hour, min) {
  
  var location_html = getLocationText(date, hour, min)
  var time = hour + ":" + min
  
  var emails = new Array()
  for (var i = 0; i < users.length; i++) { emails.push(users[i][0]) }
  
  var to = emails.toString()
  var from = 'Mathbook Helper'
  var subject = 'Good news! Someone wants to help you with Pset '+pset+'!'
  var body = "So it turns out you're not the only one who wants to work on pset "+pset
              +" on "+date+" at "+time
              +".\n\n"
              +"Just \"reply all\" to this email and y'all can figure out where to meet!\n\n" 
              + location_html 
  
  return [to, from, subject, body]
}

//####################################################################################################
//####################################################################################################

// purpose: gets text of email for too early matches
function getTooLateEmailText(users, f_users, pset, date, hour, min) {
  
  //Logger.log("USERS: "+users)
  //Logger.log("F_USERS: "+f_users)
  
  var orig_time = users[0][1].toString().slice(0,5)
  var new_time = users[0][2].toString().slice(0,5)
  
  var emails = new Array()
  for (var i = 0; i < users.length; i++) { emails.push(users[i][0]) }
  
  var forward_emails = ""
  for (var j = 0; j < f_users.length; j++) { forward_emails += "          "+f_users[j][0]+ "\n" }
  
  var to = emails.toString()
  var from = 'Mathbook Helper'
  var subject = 'Can you come 30 min later to work on Pset '+pset+'?'
  var body = "We haven't found other students who want to work on Pset "+pset
              +" on "+date+" at exactly "+ orig_time
              +" yet, but at least one person wants to start working 30 min after that, at "+new_time
              +".\n\n"
              +"Can you make that work?  If so, please get in touch with your classmates!\n\n"
              +"Here are the email addresses of people who want to work at "+new_time+":\n\n" 
              + forward_emails + "\n\n"
  
  return [to, from, subject, body]
}

//####################################################################################################
//####################################################################################################

// purpose: gets text of email for too late matches
function getTooEarlyEmailText(users, b_users, pset, date, hour, min) {

  var orig_time = users[0][1].toString().slice(0,5)
  var new_time = users[0][3].toString().slice(0,5)
  
  var emails = new Array()
  for (var i = 0; i < users.length; i++) { emails.push(users[i][0]) }
  
  var backward_emails = ""
  for (var j = 0; j < b_users.length; j++) { backward_emails += "          " + b_users[j][0] + "\n" }
  
  var to = emails.toString()
  var from = 'Mathbook Helper'
  var subject = 'Can you come 30 min earlier to work on Pset '+pset+'?'
  var body = "We haven't found other students who want to work on Pset "+pset
              +" on "+date+" at exactly "+ orig_time
              +" yet, but at least one person wants to start working 30 min before that, at "+new_time
              +".\n\n"
              +"Can you make that work?  If so, please get in touch with your classmates!\n\n"
              +"Here are the email addresses of people who want to work at "+new_time+":\n\n"
              + backward_emails + "\n\n"
  
  return [to, from, subject, body]
}

//####################################################################################################
//####################################################################################################

// purpose: sends email out to users
function sendMail(data) {
  
  var to = data[0]; var from = data[1]; var subject = data[2]; var body = data[3]  
  
  MailApp.sendEmail({
    to: to,
    from: from,
    subject: subject,
    body: body,  
  });
}

//####################################################################################################
//####################################################################################################

// purpose: sends out emails to people who matched on timeslots, letting them know
function sendMatchEmails(matches) {
  
  var emails_sent = new Array()
  
  for (var m in matches) {
    
    Logger.log("MATCHES[M]: "+matches[m])
    var pset = matches[m][0][0][0]
    
    var sub_pset = (matches[m][0][0][1] == "Not sure/All of them") ? "" : matches[m][0][0][1]
    
    var full_pset = pset + sub_pset
    
    var date = matches[m][0][0][2]
    var time = matches[m][0][0][3]
    var hour = time.slice(0,2)
    var min = time.slice(3,5)
    
    var set = full_pset + " " + date + " " + time
    //Logger.log('MATCH ITEMS: '+ set)
    
    if (matches[m].length > 1) {
      
      var match_set = matches[m]
      
      var exact_users = new Array()
      var too_early_users = new Array()
      var too_late_users = new Array()
      
      for (var user in match_set) {
        
        //Logger.log("USER: "+match_set[user])
        
        // we use negative indices here because the first user in each array also has pset info at the array front
        // these indices work assuming: backward = last, forward = 2nd-to-late, email = 3rd-to-last
        
        var len = match_set[user].length
        
        var forward = match_set[user][len-2]
        var backward = match_set[user][len-1]
        var match_status = match_set[user][len-3]
        var email = match_set[user][len-4]
        
        // we need to keep track of who's been emailed already
        // there is probably a more efficient way to do this - maybe not cycling through by matches, for example
        // but for now, we use 0/1 indexes to log whether we already sent an exact, too early, or too late email
        // if exact is sent, then no more get sent at all
        // if no exact but too early or too late are sent, then the complement can also be sent
        // that means we need to figure out how to look first for exacts?
        
        // [email, exact_email_sent, too_early_email_sent, too_late_email_sent]
        var exact_ct = 0
        var early_ct = 0
        var late_ct  = 0
        var full_ct = 0
        var found = false
        
        var email_list = (emails_sent.length > 0) ? emails_sent.keys() : null 
        
        if (email_list) {
          
          for (var i = 0; i < email_list.length; i++) {
            
            if (email == email_list[i]) {
              
              found = true
              if (emails_sent[email][1] == 1) { exact_ct = 1 }
              else if ((emails_sent[email][2] == 1) && (emails_sent[email][3] == 1)) { full_ct = 1 }
              else if ((emails_sent[email][2] == 1) && (emails_sent[email][3] == 0)) { early_ct = 1 }
              else if ((emails_sent[email][2] == 0) && (emails_sent[email][3] == 1)) { late_ct = 1 }
              
            }
          }
        }
        if (exact_ct == 1) { continue; } // skip everything if exact email match is already sent
        else if (!found) { emails_sent[email] = [0,0,0] } // if no record at all, start one
        
        Logger.log("MATCH STATUS: "+match_status)
        if (match_status == 'exact') { exact_users.push([email, time, forward, backward]) } // means exact match found
        
        else if (match_status == 'too_late') { too_late_users.push([email, time, forward, backward]) } // means backward time was found
        
        else if (match_status == 'too_early') { too_early_users.push([email, time, forward, backward]) } // means forward time was found
        
      }
      
      var match_found = (exact_users.length > 1)
      
      if (match_found) {
        
        var exact_text = getExactEmailText(exact_users, pset, date, hour, min)
        Logger.log("EXACT: "+exact_text)
        //sendMail(exact_text)
   
        if (too_early_users.length > 0) {
        
          var early_text = getTooLateEmailText(too_early_users, exact_users, pset, date, hour, min)
          
          Logger.log("REG F: "+early_text)
        //sendMail(exact_text)
          
        }  
        if (too_late_users.length > 0) {
          
          var late_text = getTooEarlyEmailText(too_late_users, exact_users, pset, date, hour, min)
          Logger.log("REG B : "+late_text)
        //sendMail(exact_text)
          
        }
      } else {
        
        if (too_early_users.length > 0) {
         
          var exact_text = getTooLateEmailText(exact_users, too_early_users, pset, date, hour, min)
          
          Logger.log("EXACT F: "+exact_text)
        //sendMail(exact_text)
        }
        
        if (too_late_users.length > 0) {
          
          var exact_text = getTooEarlyEmailText(exact_users, too_late_users, pset, date, hour, min)
          Logger.log("EXACT B: "+exact_text)
        //sendMail(exact_text)
          
        }
      }
      
    }  
  }  
}

//####################################################################################################
//####################################################################################################

// purpose: capitalizes first letter of a string
// from: http://bit.ly/18N7Nsp
function capitalizeFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//####################################################################################################
//####################################################################################################

//////////////////////////////////////
/////                          ///////
/////  MAIN MATCHING FUNCTION  ///////
/////      checkMatches()      ///////
//////////////////////////////////////

//####################################################################################################
//####################################################################################################

// purpose: identify groups looking to work together on same pset at same date/time
function checkMatches() {

  var spreadsheet_id = getSpreadsheetId()
  
  roundMinutes(spreadsheet_id)
  
  var vals = getAllValues(spreadsheet_id)
  
  var headers = vals[0].slice(1)
  
  var cols = makeCols(headers)
  
  var user_rows = getUserRows(vals, headers, spreadsheet_id)
  
  var unique_sets = getUniqueSets(user_rows)

  var email_matches = getMatches(unique_sets, user_rows)

  sendMatchEmails(email_matches, unique_sets)
}






//####################################################################################################
//####################################################################################################
//
//     START EMAIL CHECKING FUNCTION BLOCK
//
//####################################################################################################
//####################################################################################################


// purpose: gets timestamp from last row in spreadsheet
//          this is so the Gmail reader function only considers messages sent after the last checking
function getMostRecentTimestamp(id) {
 
  var sheet = SpreadsheetApp.openById(id)
  var last_row = sheet.getLastRow()
  var timestamp_cell = 'A'+last_row
  
  var timestamp = sheet.getRange(timestamp_cell).getValue()
  var date = new Date(timestamp)

  return date
  
}

//####################################################################################################
//####################################################################################################

// purpose: finds email threads relevant to our search, via subject
function matchThreadSubject(thread) {
  
  var subject = thread.getFirstMessageSubject()
  //Logger.log(subject)
  var match1 = subject.match(/(SMS\s{1}from)/)  
  var match2 = subject.match(/(pset[ ]?helper)/)
  
  
  return (match1 || match2)
}

//####################################################################################################
//####################################################################################################

// purpose: gets 'from' email address
function getFrom(msg) {
  
  var from = msg.getFrom()
  
  // gmail returns a full address string, eg. "Reece, Andrew" <reece@g.harvard.edu>
  // but we only want the email address itself. so use regex to pattern match what's inside the < >
  
  // learned how to access matched groups in javascript here: http://bit.ly/1cU09IB
  
  var email_pattern = /\<(.*)\>/
  var match = email_pattern.exec(from)
  
  // not all email addresses will have the extra text.  
  // but if they do, replace with simple email address.
  
  if (match) { from = match[1] }  
  
  return from
}

//####################################################################################################
//####################################################################################################

// purpose: gets pset info from email body
function getPset(body) {
  
  var pset_pattern = "pset\\s*(\\d{1,2})\\s?([ABC]{0,3})"
  var pset_regex = new RegExp(pset_pattern, "i")
  var pset_match = pset_regex.exec(body)
  
  var sub_pset_regex = /^[ABC]{1}$/i
  
  if (pset_match[1]) { var pset = pset_match[1] }
  
  //Logger.log('subpset: '+sub_pset_regex.exec(pset_match[2]))
  
  if (sub_pset_regex.exec(pset_match[2])) { var sub_pset = capitalizeFirstLetter(pset_match[2]) }  
  else { var sub_pset = "" }
  
  return [pset, sub_pset, pset_pattern]
}

//####################################################################################################
//####################################################################################################

// purpose: uses regex to scrape email body for date/time info
function getDateTimeInfo(pset, body) {
  
  var day_descrip = "(today|tomorrow|tom)"
  var day_short = "mon|tue|wed|thu|fri|sat|sun"
  var day_long = "monday|tuesday|wednesday|thursday|friday|saturday|sunday"
  var month_short = "jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec"
  var month_long = "january|february|march|april|may|june|july|august|september|october|november|december"
  var month_num = "[0-9]{1,2}(?=[\\/|-|\\s])"
  var date = "[0-9]{1,2}(?=[\\s|at|@])"
  var hour = "[0-9]{1,2}"
  var min = "[0-9]{1,2}"
  var datetime_pattern = new RegExp("(" + pset + ")\\n*\\W*" + day_descrip + "?\\W*" +
                                    "("+ day_long + "|" + day_short + ")?\\W*" +
                                    "("+ month_long + "|" + month_short + "|" + month_num + ")?\\W*" +
                                    "(" + date + ")?\\W*" +
                                    "(at|@)?\\s*" +
                                    "(" + hour + ")?\\:?(" + min + ")?(pm|am)?\\s*" +
                                    "(or|and)?\\s*" +
                                    "(" + hour + ")?\:?(" + min + ")?(pm|am)?\\s*", "gim")
  
  var match = datetime_pattern.exec(body)  
  for (m in match) {
    //Logger.log(match[m])
  }
  
  return cleanDateTimeInfo(match)
}

//####################################################################################################
//####################################################################################################

// purpose: converts 12-hr times to 24-hr format
function convertTo24(hr, am_pm) {
  
  // first check if cell for 'am/pm' values has data
  var am_pm_set = (am_pm)
  var pm = false
  
  // if so, see if it's pm (or PM)
  // we only care about PM times for converting to 24-hr format...in AM it's all the same
  if (am_pm_set) {
    var pm_pattern = /pm/i
    var pm = am_pm.match(pm_pattern)
  }
  
  // boolean variable: TRUE = time is in 12hr format 
  //                   NB:    technically this doesn't tell us that it's in 12 hr format
  //                          it could just be 24-hr format before noon
  //                          but we test for am/pm concurrently with this on the if statement below
  var format_12hr = (hr < 12)
  
  // if 'pm' is entered and the hour is <12, then we add 12 to convert to 24-hr format
  
  if (pm && format_12hr) { hr = parseInt(hr) + 12 } 
  
  return hr
}

//####################################################################################################
//####################################################################################################

// purpose: fix potential regex errors in parsing time backmatching
function fixTimeRegex(hr, min) {
  
  // here we have to check if regex messed with our hours/minutes
  // in the case where it's a 1-digit hour (eg. 6) and user provides no ":" between hr and min,
  // then in the example (630pm), regex matching {1,2} digits for hour will grab "63", and min then = "0"
  // this block of code checks for those cases and fixes them.
  
  //Logger.log("VAL: "+hr)
  //Logger.log("VAL[0]: "+hr[0])
  //Logger.log("VAL[1]: "+hr[1])
  //Logger.log("DTM[10]: "+min)
  
  // this is only a problem if minutes.length = 1
  // that means regex ate the minutes '10s' column into the hours backref
  
  if (min.length == 1) {
    
    // val[1] is the first digit (ie. 10s column) of minutes. 
    // reassign val[1] here because val is written over on next line with val = val[0]
    var min_tens_column = hr[1] 
    
    // val[0] is the hour.   
    hr = hr[0]
    
    // now change the minutes value in datetime_match[10] ... append val[1] in front of current value
    // concatenation with "" in the middle to ensure it's typed as a string
    min = min_tens_column + "" + min
    
  }  
  
  return [hr, min]
}

//####################################################################################################
//####################################################################################################

// purpose: cleans up date and time info for entry into spreadsheet
function cleanDateTimeInfo(datetime_match) {
  
  // now we have regex pattern-matched groups in an array
  // we have to sort through them and assign to appropriate variables
  // we can hard code indices, as long as the regex pattern itself isn't changed
  // eg. idx 9 is always 'hour' of availablilty
  
  
  // if no minutes are entered, this is default value
  var min = '00'
  //Logger.log(datetime_match)
  
  for (var i = 3; i < datetime_match.length; i++) {
    
    var val = datetime_match[i]
    
    if (val) {
      
      if (i == 4) {
      
        var tom_pattern = new RegExp("(tomorrow|tommorow|tomorow|tommorow|tom|tmrw)","i")
        var tod_pattern = new RegExp("(today|tod|tdy)","i")
        
        var tomorrow = tom_pattern.exec(val)
        var today = tod_pattern.exec(val)
        
        var d = new Date()
        
        if (tomorrow) { d.setDate(d.getDate() + 1) }
        
        var date_array = d.toString().split(" ")
        datetime_match[6] = date_array[1] // month value
        datetime_match[7] = date_array[2] // day value
        
        //Logger.log("TODTOM: "+val)
        //Logger.log("DATE: "+datetime_match[6]+" "+datetime_match[7])
        
      } else if (i == 5) {
        
        var weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
        
        // get day of week into 3-character, first-letter-caps format
        if (val.length > 3) { var weekday = capitalizeFirstLetter(val.slice(0,3)) }
        else { var weekday = capitalizeFirstLetter(val) }
        
        //Logger.log("WEEKDAY: "+weekday)
        
        for (var day in weekdays) { if (weekday == weekdays[day]) { var day_idx = day } }
        
        //Logger.log("DAY IDX: "+day_idx)
        
        var d = new Date()
        var today = d.getDay()
        
        // get Date object for the nearest upcoming given weekday 
        // eg. given 'Thursday' on a Friday, get next Thu Date()
        // slightly modified from: http://stackoverflow.com/questions/11789647/setting-day-of-week-in-javascript 
        
        var distance = (day_idx - today) 
        if (distance < 0) { distance = 7 - Math.abs(distance) }
        //Logger.log("DISTANCE FROM TODAY: "+distance)
        
        d.setDate(d.getDate() + distance);
        
        var target_date = d.toString().split(" ")
        //Logger.log("TARGET DATE: "+target_date)
        
        datetime_match[6] = target_date[1]
        datetime_match[7] = target_date[2]
          
        
      } else if (i == 6) { // idx 6 for month
        
        var month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        
        if (parseInt(val)) { var month = month_names[parseInt(val)-1] } 
        
        else if (val.length > 3) {
            
            var tmp = capitalizeFirstLetter(val).slice(0,3)
            
            for (var m in month_names) { if (tmp == month_names[m]) { var month = month_names[m] } } } 
        
        else if (val.length == 3) { var month = capitalizeFirstLetter(val) }
        
      
      } else if (i == 7) { var date = val   // idx 7 for day number
      
      } else if (i == 9) {                  // idx 9 for hour

        // check for any regex errors parsing time values
        var tmp = fixTimeRegex(val, datetime_match[10])
        var val = tmp[0]
        datetime_match[10] = tmp[1]
        
        // convert 12-hr time to 24-hr time
        var hour = convertTo24(val, datetime_match[11])
        
      } else if (i == 10) { var min = val } // idx 10 for min
    }
  }  
  
  Logger.log("Request: "+month + " " + date + " " + hour+":"+min)
  return [month, date, hour, min]
}

//####################################################################################################
//####################################################################################################

// purpose: enter request into master request spreadsheet
function enterRequestInSpreadsheet(from, pset, subpset, info, sheet_id) {

  var timestamp = new Date()
  
  var month_val = info[0]
  var date_val = info[1]
  var full_date = "'" + month_val + ' ' + date_val
  
  var hour_val = info[2]
  var min_val = info[3]  
  var full_time = hour_val + ':' + min_val + ":00"
  
  if (subpset == "") { subpset = "Not sure/All of them" }
  
  var sheet = SpreadsheetApp.openById(sheet_id)
  
  sheet.appendRow([timestamp, from, pset, subpset, full_date, full_time])
  
}

//####################################################################################################
//####################################################################################################

/////////////////////////////////////////
/////                             ///////
/////  MAIN EMAIL CHECK FUNCTION  ///////
/////                             ///////
/////////////////////////////////////////

//####################################################################################################
//####################################################################################################

// purpose: scan inbox for messages with pset requests, add to spreadsheet
function getEmailRequests() {
   
  var sheet_id = getSpreadsheetId()
  var prev_timestamp = getMostRecentTimestamp(sheet_id)
  
  var inbox = GmailApp.getInboxThreads()
  
  for (var idx in inbox) {
    
    var thread = inbox[idx]
    
    if (matchThreadSubject(thread)) {
      
      var msgs = thread.getMessages()
      
      for (var idx2 in msgs) {
        
        var this_timestamp = msgs[idx2].getDate()
        
        Logger.log('THIS TIMESTAMP: '+this_timestamp)
        //Logger.log('BOOL: '+(prev_timestamp < this_timestamp))  //CHANGE THIS TO prev < this !!!!
        if (prev_timestamp < this_timestamp) {
          
          var msg = msgs[idx2]
          
          var from = getFrom(msg)
          
          // parse the body of the email for relevant details to enter in spreadsheet
          var body = msg.getPlainBody()
          
          // first scrape pset info
          var tmp = getPset(body)
          var pset = tmp[0]
          var sub_pset = tmp[1]
          var pset_pattern = tmp[2]
          
          // now scrape date/time info
          var datetime_info = getDateTimeInfo(pset_pattern, body) 
          
          //enterRequestInSpreadsheet(from, pset, sub_pset, datetime_info, sheet_id)
          
          Logger.log('From: '+from)
          Logger.log('Pset: '+ pset + sub_pset)
          Logger.log(' ')
        } 
      }
    }
  }
}
