<html>
<head>
<script src="zohoscript.js" type="text/javascript"></script>
<script src="http://creator.zoho.com/agamayoga/form/json/49/" type="text/javascript"></script>

<link href="feedback.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
// GETS USER INFO FROM EMAIL LINK QUERY STRING (NAME AND EMAIL)

var userName;

function getURLparameters(name) 
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "Agama Yogi";
  else
    return results[1];
}

userName = getURLparameters("username");
</script>

<title>
AGAMA YOGA STUDENT FEEDBACK FORM: 1st Month
</title>
</head>

<body bgcolor="#6699CC">
<img src="agama_webheader.jpg" width="100%"></img>
<br />
<br />
<span id="subheader">AGAMA YOGA STUDENT FEEDBACK FORM: 1st Month</span>


	<br /><br />
<span class="gold">	Welcome, <script type="text/javascript">document.write(userName);</script>!
	<br /><br />
	
	Agama Yoga is committed to continually growing and evolving in positive ways.  Your feedback will help greatly to maintain a high standard for the quality of the teachings, classes, and overall experience offered at our school.  Please fill out the form as thoroughly as possible; you may of course choose to remain anonymous, but be assured that your personal information will not be shared in any way with your teachers or classmates.
	</span>

	<br /><br />
	<i>Note: Required fields will be marked with an asterisk (<span style="color:red;">*</span>).</i>
	<br />
<form linkid="49" user="agamayoga" class="Zoho-form" nexturl="http://creator.zoho.com/agamayoga/agama-yoga-data-collection/view/4/" onsubmit='Zoho.submit(this); return false;'>

<!--

ALL THIS SHOULD BE RECORDED IN A SEPARATE DATABASE 
IF WE NEED TO WE CAN RUN IT HERE FOR A BIT

<script type="text/javascript">Zoho.writeLabel("Name");</script>
<script type="text/javascript">Zoho.writeInput("Name");</script><br />
<br />

<script type="text/javascript">Zoho.writeLabel("email");</script>
<script type="text/javascript">Zoho.writeInput("email");</script>

<script type="text/javascript">Zoho.writeLabel("starsign");</script>
<script type="text/javascript">Zoho.writeInput("starsign");</script>

<script type="text/javascript">Zoho.writeLabel("birthdate");</script>
<script type="text/javascript">Zoho.writeInput("birthdate");</script>
-->
<br />
***** <span id="subheader">GENERAL FEEDBACK </span> ***** <br /><br />

<script type="text/javascript">Zoho.writeLabel("how_did_you_find_us");</script><br />
<div id="whiteback"><script type="text/javascript">Zoho.writeInput("how_did_you_find_us");</script></div>
<br />
<br />
<script type="text/javascript">Zoho.writeLabel("overall_exp");</script><br />
<script type="text/javascript">Zoho.writeInput("overall_exp");</script>
<br />
<br />
<script type="text/javascript">Zoho.writeLabel("crit_comments");</script><br />
<script type="text/javascript">Zoho.writeInput("crit_comments");</script>

<br /><br />
<script type="text/javascript">Zoho.writeLabel("best_elements");</script><br />
<script type="text/javascript">Zoho.writeInput("best_elements");</script>
<br /><br />

<script type="text/javascript">Zoho.writeLabel("needs_improvement");</script><br />
<script type="text/javascript">Zoho.writeInput("needs_improvement");</script>
<br /><br />

<script type="text/javascript">Zoho.writeLabel("helping_new_students");</script><br />
<script type="text/javascript">Zoho.writeInput("helping_new_students");</script>
<br /><br />
<script type="text/javascript">Zoho.writeLabel("teaching_quality");</script><br />
<script type="text/javascript">Zoho.writeInput("teaching_quality");</script>
<br /><br />
<script type="text/javascript">Zoho.writeLabel("comments_quality_of_teachings");</script><br />
<script type="text/javascript">Zoho.writeInput("comments_quality_of_teachings");</script>
<br /><br />

***** <span id="subheader">SPECIFIC TEACHER FEEDBACK </span> ***** <br /><br />

Please help us grow individually as well by reflecting the performance of each teacher. For any teacher who you rate less than excellent, please briefly explain why, along with any suggestions you may have for how that teacher could improve.  (Please feel welcome to express your satisfaction with excellent teachers as well!)
<br /><br />
Also, please select your three favorite teachers by checking the boxes marked "Favorite" next to their names.

<br />

<br />
<?php
$handle = fopen("firstmonth_current_teachers.txt", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = trim(fgets($handle));
		$uc_buffer = ucwords($buffer);
        if ($buffer != "") {
		echo "
		<table width=\"100%\">
<tr>
<td>
	<span id=\"subheader\">$uc_buffer</span>
</td>
<td>
	<script type=\"text/javascript\">Zoho.writeInput(\"favorite_$buffer\");</script>
</td>
</tr>
<tr>
<td><script type=\"text/javascript\">Zoho.writeLabel(\"hatha_$buffer\");</script></td>

<td><script type=\"text/javascript\">Zoho.writeInput(\"hatha_$buffer\");</script></td>

</tr>
<tr>
<td><script type=\"text/javascript\">Zoho.writeLabel(\"lecture_$buffer\");</script>:</td>
<td><script type=\"text/javascript\">Zoho.writeInput(\"lecture_$buffer\");</script></td>
</tr>
</table>


<script type=\"text/javascript\">Zoho.writeLabel(\"comments_$buffer\");</script>:
<br />
<script type=\"text/javascript\">Zoho.writeInput(\"comments_$buffer\");</script>
<br />

<br />";
	}
    }
    fclose($handle);
}
?>
<br />
***** <span id="subheader">FUTURE PLANS AND FINAL COMMENTS</span> *****
<br /><br />
<script type="text/javascript">Zoho.writeLabel("future_plans_yesno");</script>  
<script type="text/javascript">Zoho.writeInput("future_plans_yesno");</script>

<br /><br />
<script type="text/javascript">Zoho.writeLabel("future_plans");</script>
<br />
<script type="text/javascript">Zoho.writeInput("future_plans");</script>

<br /><br />
<script type="text/javascript">Zoho.writeLabel("final_comments");</script>
<br />
<script type="text/javascript">Zoho.writeInput("final_comments");</script>
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
<span id="subheader">Thank You!!</span>
<br />
<script type="text/javascript">Zoho.writeSubmit("Submit");</script>
<br />
<script type="text/javascript">Zoho.writeReset("Reset");</script>
</form>
</body>
</html>
