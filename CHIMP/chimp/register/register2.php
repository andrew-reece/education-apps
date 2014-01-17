

<h3>REGISTRATION: STEP 3 of 3</h3>
<br /><br />
<form method="POST" action="register_backend.php">
<input type="hidden" name="step" value="4">
<input type="hidden" name="key" value="<?php echo $key; ?>">
Please enter the following demographic information:
<br /><br />
Date of Birth (month & year only): 
<select name="birth_month">
	<option value="0" selected>Select a month:</option>
	
	
<?php
	$query = "SELECT month_id, month_name FROM month_index";
	$result = mysql_query($query) or die(mysql_error());
	while($months = mysql_fetch_assoc($result)) {
		echo "<option value='$months[month_id]'>$months[month_name]</option>";
	}
	
	echo "</select>
	<select name='birth_year'>
	<option value='0' selected>Select a year:</option>";
	for ($i=1900; $i<2010; $i++) {
		echo "<option value='$i'>$i</option>";
	}
	echo "</select>";
?>


<br /><br />
Sex (Check one):
<input name="gender" type="radio" value="0"> Female		<input name="gender" type="radio" value="1"> Male
<br /><br />
Race (Check one):<br />
<input name="race" type="radio" value="am-indian"> American Indian or Alaska Native<br />
<input name="race" type="radio" value="asian"> Asian<br />
<input name="race" type="radio" value="am-asian"> Asian American<br />
<input name="race" type="radio" value="black"> Black or African American<br />
<input name="race" type="radio" value="pac-isl"> Native Hawaiian or Other Pacific Islander<br />
<input name="race" type="radio" value="white"> Caucasian <br />
<input name="race" type="radio" value="other"> Other: <input id="other_race" type="text" size="15"> <br />
<input name="race" type="radio" value="noid"> I prefer not to self-identify <br />
<br /><br />
Hand preference (Check one):
<br />
<input name="hand" type="radio" value="1"> Left	<input name="hand" type="radio" value="2"> Right <input name="hand" type="radio" value="3"> Ambidextrous
<br /><br />
To what degree do you consider yourself to be an artist?
<br />
<input name="artist" type="radio" value="1"> Not at all<br />
<input name="artist" type="radio" value="2"> A little bit<br />
<input name="artist" type="radio" value="3"> Average <br />
<input name="artist" type="radio" value="4"> Fairly artistic<br />
<input name="artist" type="radio" value="5"> Very artistic<br />
<br /><br />
Do you play any musical instruments? If yes, please state which ones, separating multiple instruments with commas (ex. "Saxophone, Guitar, Piano"):
<br />
<textarea name="instruments" rows="3" cols="50">
</textarea>
<br /><br />
I am primarily: 
<select name="educational_position" onchange="revealEdInfo(this);">
	<option value="" selected>Please choose one: </option>
	<option value="student">a student </option>
	<option value="educator">an educator</option>
	<option value="neither">neither a student nor an educator</option>
</select>
<br /><br />
<div id="edinfo" style="visibility:hidden;display:none;">

	<div id="degree_text"></div> 
	
<select name="edinfo_degrees">
	<option value="" selected>Please choose one: </option>
	<option value="1">High school</option>
	<option value="2">Associate</option>
	<option value="3">Bachelor</option>
	<option value="4">Master</option>
	<option value="5">Doctoral</option>
</select>

<br /><br />

	<div id="major_text"></div>
	
<select name="edinfo_major">
         <option value="" selected>Select an area of study</option>
		 
        <?php
		$query = "SELECT discipline_id, discipline_name FROM discipline_index";
		$result = mysql_query($query) or die(mysql_error());
		while($discs = mysql_fetch_assoc($result)) {
			echo "<option value='$discs[discipline_id]'>$discs[discipline_name]</option>";
		}
		echo "</select>";
		?>
         
      </select>
</div>

<div id="professional_info" style="visibility:hidden;display:block">
	What is your profession?
	
	<select id="profession">
		
    <option value="0" selected>Please choose one:</option>
	<?php 
		$query = "SELECT profession_id, profession_name FROM profession_index";
		$result = mysql_query($query) or die(mysql_error());
		while($profs = mysql_fetch_assoc($result)) {
			echo "<option value='$profs[profession_id]'>$profs[profession_name]</option>";
		}
		echo "</select>";
	?>
	
	</select>
</div>
<br />
What is the highest level of education you have completed?
<br />

<select name="highest_degree">">
	<option value="" selected>Please choose one: </option>
	<option value="1">High school diploma </option>
	<option value="2">Associate's Degree</option>
	<option value="3">Bachelor's Degree </option>
	<option value="4">Master's Degree </option>
	<option value="5">Doctoral Degree </option>
	<option value="0">None of the above</option>
</select>

<br /><br />

<input type="submit" value="Click to Register">
</form>
