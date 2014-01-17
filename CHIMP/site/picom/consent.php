<?php

	$consent_query = "SELECT * FROM study_consentforms WHERE id = '$study'";
	$result = mysql_query($consent_query);
	$data = mysql_fetch_assoc($result);
	$keys = array_keys($data);
	
	foreach ($keys as $k) {
		$$k = $data['$k'];
	}

?>

<h3>Informed Consent Form</h3>

<p>
<b>Please consider this information carefully before deciding whether to participate in this research.</b>
</p>

<p>
<b>Purpose of the research: <?php echo $purpose ?> xxx</b>
</p>

<p>	
<b>What you will do in this research:</b> This study will consist of two sessions. 
<div style="border:1px solid black;">
<b>First Session:</b>
You will complete two questionnaires on demographic variables, handedness, musical background, and personal characteristics. This will take approximately twenty minutes. After six months, you will be emailed again for the second session.
<b>Second Session:</b>
You will complete questions about personal characteristics. This will take approximately fifteen minutes.
</div>
</p>

<p>
<b>Time required:</b> Participation in the entire study will take approximately thirty five minutes to complete.  
</p>

<p>
<b>Risks:</b> There are no anticipated risks associated with participating in this study. 
</p>

<p>
<b>Benefits:</b> At the end of the study, we will provide a thorough explanation of the study and of our hypotheses.  We will describe the potential implications of the results of the study both if our hypotheses are supported and if they are disconfirmed.  If you wish, you can send an email message to Dr. Chiara Haller (haller@wjh.harvard.edu) and we will send you summaries of the overall study results.
</p>

<p>
<b>Confidentiality:</b> Your participation in this study will remain confidential, and your identity will not be stored with your data.  Your responses will be assigned a random numerical code number, which will be the only identifying mark on all of the data-gathering items in this study.  There will be no \"key\" or master-list linking participants\' name to their number or to any of the data-gathering materials. All data-gathering items will be stored in a locked file cabinet.  In keeping with the American Psychological Association\'s ethics committee rules and procedures, all data-gathering items will be securely destroyed five years after the end of the study. 
</p>

<p>
<b>Participation and withdrawal:</b> Your participation in this study is completely voluntary, and you may withdraw at any time by simply closing your web browser.  You may skip any questions you do not want to complete (no questions will be asked).
</p>

<p>
<div style="border:1px solid black;">
<b>To Contact the Researcher:</b> If you have questions about this research, please contact:
Chiara S. Haller, Ph.D
William James Hall #1350
33 Kirkland Street
Cambridge, MA 02138
Phone: 857-207-7783
haller@wjh.harvard.edu
Ellen Langer, Ph.D.
William James Hall #1330
33 Kirkland Street
Cambridge, MA 02138
Phone: 617-495-3860
langer@wjh.harvard.edu
</div>
</p>

<p>
<b>Whom to contact about your rights in this research, for questions, concerns, suggestions, or complaints that are not being addressed by the researcher, or research-related harm:</b> Jane Calhoun, Harvard University Committee on the Use of Human Subjects in Research, 1414 Massachusetts Avenue, Room 234, Cambridge, MA  02138.  Phone:  617-495-5459.  E-mail: jcalhoun@fas.harvard.edu
</p>

<p>
<b>Agreement:</b>
The nature and purpose of this research have been sufficiently explained and I agree to participate in this study.  I understand that I am free to withdraw at any time without incurring any penalty. Please click the button at the bottom left to state that you have read the consent information and agree to participate in this study. 
</p>

<form action='register_backend.php' method='POST'>
<input type='hidden' name='step' value='3'>
<input type='hidden' name='study' value='<?php echo $study; ?>'>
<input type='hidden' name='key' value='<?php echo $key; ?>'>
<input type='submit' value='Click to indicate your consent to participate'>
</form>