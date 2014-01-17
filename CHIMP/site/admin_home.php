<?php	

$javascript="<script type=\"text/javascript\" src=\"ajax.js\"></script>";

require("HTML_headers.php");
require("registeruser/cookie_check.php");
require("functions_library.php");

$HTML_title = "AGAMA STUDENT REGISTRY: ADMIN HOME";
echo $HTML_header_A.$HTML_title.$HTML_header_B;	
?>


<div align="center">
	<img src="images/agama_logo_small.jpg"></img>
	<br /><br />
	Welcome to the Administrator's home page for Agama Yoga Student Registry.
	Please select from the options below:
</div>
	<table cols="3" border="1px">
		<tr class="bold">
			<td align="center" valign="top" width="25%">
			<span class="bluebold">MAIN MENU</span>
			<br /><br />
				<a style="text-decoration:none" href="register_student.php">Enter A Student Into The Registry</a>
			<br />
				<a style="text-decoration:none" href="search_registry.php">Search For A Specific Student</a>
			<br />
				<a style="text-decoration:none" href="search_registry_bygroup.php">Search By Category Or Group</a>
			<hr>
				<a style="text-decoration:none" href="mailto:sysadmin@agamayoga.com">Contact System Administrator</a>
			<br />
				<a style="text-decoration:none" href="registeruser/user_home.php">Return To User Home Page</a>
			</td>
		
			<td valign="top" align="center" width="45%">
			<form name="admin_view">					
			<span class="bluebold">QUICK MENU</span>
				<table cols="3" class="bold">
					<tr>
						<td>
							View: 
						</td>
						<td>
							<input type="text" name="fullname" size="25" value="Enter Student's Full Name" onClick="this.value='';">
						</td>
						<td>
							<input type="submit" value="View Record" onClick="ajaxModifier('quick_view'); return false;">
						</td>
					</tr>
			</form>
			<form name="admin_edit" method="POST" action="edit_record.php">
					<tr>
						<td>
							Edit:
						</td>
						<td>
							<input type="text" name="fullname" size="25" value="Enter Student's Full Name" onClick="this.value='';">
						</td>
						<td>
							<input type="submit" value="Edit Record">
						</td>
					</tr>
				</form>
				<form name="admin_grab_localinfo">
					<tr>
						<td>
							Local Info: 
						</td>
						<td>
							<input type="text" name="fullname" size="25" value="Enter Student's Full Name" onClick="this.value='';">
							<select name="branch">
								<option value="0">* Select Local Branch *</option>
								<?php echo populateSelectBox("branch"); ?>
							</select>
						</td>
						<td>
							<input type="submit" value="Get Local Info" onclick="ajaxModifier('quick_local'); return false;"></form>
						</td>
					</tr>
				</form>
				<form name="upload" enctype="multipart/form-data" method="POST" action="update_records.php">
					<tr>
						<td>
							Upload:
						</td>
						<td>							
							<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
							<input name="batch_register" type="file" /><br />
						</td>
						<td>							
							<input type="submit" value="Upload Records" />
						</td>
					</tr>
				</form>
				</table>
			</td>
			<td width="28%">
				<div id="quick_info"><br /><span style="color:#black">Welcome, <span style="color:blue"><? echo $user ?></span>!<br /><br />- Please Note - <br />Upload function is still under development.  Don't try to use it yet. - Andy</span></div>
			</td>
		</tr>
	</table>
</body>
</html>