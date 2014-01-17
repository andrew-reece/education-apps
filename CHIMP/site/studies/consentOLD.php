<?php
require("../db/db_setup.php");
	
	$consent_query = "SELECT * FROM study_consentforms WHERE id = '$study' AND control = '$pimindcre_group'";
	$consent_result = mysql_query($consent_query);
	$data = mysql_fetch_assoc($consent_result) or die(mysql_error()." $consent_query!");
	$keys = array_keys($data);
	
	foreach ($keys as $k) {
		// this creates variables that hold field info from the consentforms DB, with the names that correspond to the field names
		// this is done by using each $keys value (here, $k) as the name of the variable we create, with the $$var method
		$$k = $data[$k];
	}
?>

<h3>Informed Consent Form</h3>

<p>
<b>Please consider this information carefully before deciding whether to participate in this research.</b>
</p>

<p>
<b>Purpose of the research: </b> <?php echo $purpose ?>
</p>

<p>	
<b>What you will do in this research:</b> 
<div class="consent_whatyouwilldo">
<?php echo $what_you_do ?>
<br />
	<div class="consent_session">
	
		<?php 
		for ($i=1; $i<=$sessions; $i++) {
			$s = "session".$i;
			$this_session = $$s;
			echo "<div class=\"consent_session_name\">
					<b>Session $i:</b> 
				</div>
				<div class=\"consent_session_info\">
					$this_session
				</div>";
			}
		?>
	</div>
</div>
</p>

<p>
<b>Time required:</b> <?php echo $time_required ?>  
</p>

<p>
<b>Risks:</b> <?php echo $risks ?>
</p>

<p>
<b>Benefits:</b> <?php echo $benefits ?>
</p>

<p>
<b>Confidentiality:</b> <?php echo $confidentiality ?>
</p>

<p>
<b>Participation and withdrawal:</b> <?php echo $participation ?>
</p>

<p>
<div class="consent_contact_researcher">
<b>To Contact the Researcher:</b> 
<br />
<?php echo $contact_researcher ?>
</div>
</p>

<p>
<b>Whom to contact about your rights in this research, for questions, concerns, suggestions, or complaints that are not being addressed by the researcher, or research-related harm:</b> 
<br />
<?php echo $contact_concerns ?>
</p>

<p>
<b>Agreement:</b> <?php echo $agreement ?>
</p>

<form action='register_backend.php' method='POST'>
<input type='hidden' name='step' value='3'>
<input type='hidden' name='study' value='<?php echo $study; ?>'>
<input type='hidden' name='key' value='<?php echo $key; ?>'>
<input type='submit' value='Click to indicate your consent to participate'>
</form>