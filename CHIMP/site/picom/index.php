<?php
require("../headers/header.php");

$study = $_GET['s'];
$uid = $_GET['u'];

//first thing: check to make sure user should be here (ie. is registered, also hasn't already completed)
?>

<h3>Unifying Characteristics of Creativity: Questionnaire</h3>
<br />

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