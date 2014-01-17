<?php
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require("../db/db_setup.php");
require("../headers/header.php");
require("../lib.php");


$study = $_REQUEST['s'];
$survey = $_REQUEST['sv'];
$uid = $_REQUEST['u'];
$checkpoint = $_REQUEST['c'];
$continue = $_REQUEST['cont'];
$sname = getStudyNickname($study);
$status = getStudyStatus($sname, $uid);

if ($checkpoint) {		//this means we're coming in hot off of a survey completion and need to assess where to go next

	//plug everything into database first
	$success = logResults($_POST);
	
	//if $success = FALSE, that means there has already been a submission by this user for this survey
	//in that case, we return an error message and cancel the rest of the script
	if (!$success) {
	
		//first check to see if user still has surveys left to complete in this timepoint
		$still_going = compareTimepoints($sname, $uid, 'user-to-current');
		
		if ($still_going) {		//if so, then fetch # of surveys left to go and send error
		
			$total = getNumberOfSurveyElements($sname);
			$left = $total - $status;
			
			//here we check which study we're working with -- and pull up 'time remaining' and appropriate warnings
			if ($sname == 'picom') {
				//fetch minutes remaining in 10 min break
				$time_remaining = getTimeRemaining($uid, $study, $survey, 'segment');
				
				$time_warning = "Please be advised that you are still considered to be on your 10 minute break -- 
				the time remaining is listed below.  <br />
				Remember that you must continue to the next study within this 10 minute period; 
				otherwise, you will be disqualified as a study participant.";
				
				//get time remaining from DB timestamp, then plug it into countdown() to start with time remaining
				$break_button_HTML = "<br /><br />
				<div id =\"breakannounce\"></div><div id=\"breaktimer\"></div>
				<script src=\"/chimp/js/countdown.js\"></script>			
				<script language=\"javascript\" type=\"text/javascript\">countdown(0,$study, '$time_remaining');</script>";
				$continue_button_HTML = "<br /><br />
				<form name=\"continue\" method=\"POST\" action=\"/chimp/studies/\">
				<input type=\"hidden\" name=\"s\" value=\"$study\">
				<input type=\"hidden\" name=\"u\" value=\"$uid\">
				<input type=\"hidden\" name=\"cont\" value=\"1\">
				<input type=\"submit\" value=\"Continue to Next Questionnaire\">
				</form>";
				$time_warning .= $break_button_HTML.$continue_button_HTML;
				
			} elseif ($sname == 'pimindcre') {
				$time_remaining = getTimeRemaining($uid, $study, $survey, 'total');
				$time_warning = "Please remember that you are alloted a total of 72 hours to complete this study.  <br />
				You current have $time_remaining remaining. <br />
				If you would like to take a break, click the button below to log out from the CHIMP system.";
				$break_button_HTML = "<br /><br />
				<form name=\"break\" action=\"/chimp/register/logout.php\">
					<input type=\"submit\" value=\"Take A Break\">
				</form>";
				$time_warning .= $break_button_HTML;
			}
			
			$error_text = "You have already submitted your responses for this survey.  <br />
			Please continue onto the next survey by clicking the button below. <br />
			$time_warning";
		} else {	//if not, return error notifying that this timepoint is complete
			$error_text = "You have already submitted responses for this survey. <br />
			In fact, you have already completed all of the tasks for this phase of the study. <br />
			Thank you for your participation! We will contact you again via email when the next phase begins. <br />";
			
		}
		die($error_text);
	} else {
	
		//increment $status so we know to load the next survey, also checks to see if we're at the end of the line for this timepoint
		$what_we_just_completed = checkpoint($sname, $uid, $status, $survey);
		
		
		if ($what_we_just_completed == 'study') {
		
			//should have a whole page set up here
			die("You have completed the study! Thanks for participating.");
			
		} elseif ($what_we_just_completed == 'timepoint') {
		
			die("Congratulations, you have completed all the tasks for this phase of the study.  <br />
				We will contact you again via email when the next phase begins.  <br />
				Thank you!");
				
		} elseif ($what_we_just_completed == 'survey') {
		
			$status = getStudyStatus($sname, $uid);
			$total = getNumberOfSurveyElements($sname);
			$remaining = $total - $status + 1;	// +1 to offset the fact that $status was already incremented in logResults()
			$continue_button_HTML = "<br /><br />
			<form name=\"continue\" method=\"POST\" action=\"/chimp/studies/\">
				<input type=\"hidden\" name=\"s\" value=\"$study\">
				<input type=\"hidden\" name=\"u\" value=\"$uid\">
				<input type=\"hidden\" name=\"cont\" value=\"1\">
				<input type=\"submit\" value=\"Continue to Next Questionnaire\">
			</form>";
			$closing_HTML = "</body>
			</html>";
			if ($sname == 'picom') {
				$take_a_break = "Before continuing to the next questionnaire, you may take a 10 minute break if you choose.  
				You will be given this option at the end of every questionnaire.  
				<br /><br />
				
				If you decide to take a break, please make sure you have returned to your computer and clicked 
				'Continue to Next Questionnaire' before 10 minutes have elapsed; otherwise, you will not be allowed to continue with this study.  
				Please note that if you leave this page without clicking the 'Continue to Next Questionnaire' button, 
				it will be assumed that you have chosen to discontinue this study's tasks, and you will be removed as a participant from this study. 
				<br /><br />
				
				If you are ready to continue, please click 'Continue to Next Questionnaire'.";
				$break_button_HTML = "<br /><br />
				<div id =\"breakannounce\"></div><div id=\"breaktimer\"></div>
				<script src=\"/chimp/js/countdown.js\"></script>			
				<script language=\"javascript\" type=\"text/javascript\">countdown(0,$study);</script>";
			} elseif ($sname == 'pimindcre') {
				$take_a_break = "As this study is comprised of a large number of tasks, you are not required to complete everything in one sitting.
				You may take up to 3 days (72 hours from now) to complete all the required tasks.  
				If you would like to take a break now and come back to complete more tasks at a later time, 
				click 'Take A Break', and you will be logged out of CHIMP.  
				If you are ready to continue, please click 'Continue to Next Questionnaire'.";
				$break_button_HTML = "<br /><br />
				<form name=\"break\" action=\"/chimp/register/logout.php\">
					<input type=\"submit\" value=\"Take A Break\">
				</form>";
			}
			//check to see if participant wants to continue or break
			die("Thank you for completing this portion of the study.  
			There are still $remaining tests remaining. $take_a_break $break_button_HTML $continue_button_HTML $closing_HTML");
			//if not break, continue with next survey
			//if break, return to home page?
			
		}
	}
} elseif ($continue) {


	switch ($status) {
		case 0:	//unregistered
			echo "Sorry, you're not registered as a participant for this study.  <br />
			If you'd like to register, 
			please <a href=\"/chimp/\">return to the CHIMP home page</a> and click on the link for the study you wish to sign up for.<br />
			Thanks!";
			break;
		
		case 99:		//completed
			echo "You have already completed this study!  There's nothing more to do here.  
			<br />
			Please return to <a href=\"/chimp/\">the home page</a>.
			</body>
			</html>";
			break;
			
		default:
			$tp = compareTimepoints($sname, $uid, 'user-to-current');
			if ($tp) {
				printSurvey($study,$sname,$uid,$status);
			} else {
				echo "You have already completed all of the tasks for the current timepoint in this study. <br />
				When a new timepoint begins, you will be notified by email, and then you can continue on with new tasks. <br />
				Thank you.<br />
				</body>
				</html>";
			}
	}
} else {
	echo "Sorry, you shouldn't have gotten to this page the way you did.  Please go back.";
}
?>

