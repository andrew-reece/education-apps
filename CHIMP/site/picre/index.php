<html>

<head>
	<title>Chiara Haller Institute of Mind & Personality (CHIMP)</title>
</head>

<body>
<h2>Chiara Haller Institute of Mind & Personality (CHIMP)</h2>
<hr>
<h3>Survey #1</h3>
<br />
<strong>
Before going on to participate in this survey, please remember to make sure you have first filled out the <a href="consent_form.html">Informed Consent Form</a>.  
</strong>

<table cols="5">
	<tr>
		<td> # </td>
		<td> Statement </td>
		<td> Not at all </td>
		<td> Somewhat </td>
		<td> Mostly </td>
		<td> Completely </td>
	</tr>
	<?php 
		$handle = fopen("master_list.txt", "r");
		$count = 1;
		while (!feof($handle)) {
			$buffer = fgets($handle);
			echo "
			<tr>
			<td> $count </td>
			<td> $buffer </td>
			<td> <input name=\"1_$count\" type=\"radio\"> </td>
			<td> <input name=\"2_$count\" type=\"radio\"> </td>
			<td> <input name=\"3_$count\" type=\"radio\"> </td>
			<td> <input name=\"4_$count\" type=\"radio\"> </td>
			</tr>
			";
			
			$count++;
		}
	?>
</table>

</body>

</html>