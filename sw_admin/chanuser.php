<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_super.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Change User Account Type";
	
	//route tracing for future page
	$_SESSION['route2'] = '2';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>

	<script type="text/javascript">
		window.onclick = function(event) {
			if (event.target.matches('.dropbtn2')) {
				var dropdowns = document.getElementsByClassName('dropbtn2');
				for (var i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show') && !event.target.classList.contains('show')) {
						openDropdown.classList.remove('show');
						}
				}
				event.target.classList.toggle("show");
			}
		}
	</script>

	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>
	
	<?php 		
		
			if (isset($_GET["del"]) && $_GET["del"] <> NULL && is_numeric($_GET["del"]))// if delete
			{
				$get_id_del = mysqli_real_escape_string($GLOBALS["conn"], $_GET["del"]);
				
				$stmt_update = $new_conn->prepare("update eg_auth set usertype='FALSE' where id=?");
				$stmt_update->bind_param("i", $get_id_del);
				$stmt_update->execute();
				$stmt_update->close();
			}
				
			if (isset($_GET["res"]) && $_GET["res"] <> NULL && is_numeric($_GET["res"]))// if reset password
			{
				$get_id_res = mysqli_real_escape_string($GLOBALS["conn"], $_GET["res"]);
				
				$stmt_update = $new_conn->prepare("update eg_auth set syspassword=AES_ENCRYPT('$default_password_if_forgotten','$password_aes_key'), num_attempt=0 where id=?");
				$stmt_update->bind_param("i", $get_id_res);
				$stmt_update->execute();
				$stmt_update->close();
			}

			if (isset($_GET["reb"]) && $_GET["reb"] <> NULL && is_numeric($_GET["reb"]))// if unblock
			{
				$get_id_reb = mysqli_real_escape_string($GLOBALS["conn"], $_GET["reb"]);
				
				$stmt_update = $new_conn->prepare("update eg_auth set num_attempt=0, online='OFF' where id=?");
				$stmt_update->bind_param("i", $get_id_reb);
				$stmt_update->execute();
				$stmt_update->close();
			}
			
			if (isset($_GET["offuser"]) && $_GET["offuser"] <> NULL && is_numeric($_GET["offuser"]))// if set user offline
			{
				$get_id_offuser = mysqli_real_escape_string($GLOBALS["conn"], $_GET["offuser"]);
				
				$stmt_update = $new_conn->prepare("update eg_auth set online='OFF' where id=?");
				$stmt_update->bind_param("i", $get_id_offuser);
				$stmt_update->execute();
				$stmt_update->close();
			}
			
	?>

	<?php													
		
		$query_fdb = "select * from eg_auth order by usertype desc";																							
		$result_fdb = mysqli_query($GLOBALS["conn"],$query_fdb);
		$num_results_affected_fdb = mysqli_num_rows($result_fdb);												
		
		echo "<table class=yellowHeader>";
			echo "<tr><td><strong>Total user in the system</strong> : ";
				echo "$num_results_affected_fdb <em>record(s) found.</em>";
				echo "<div style='color:blue;'>To search use CTRL+F (Windows) or CMD+F (macOS).</div><br/>";
				echo "<a class='sButton' href='../sw_admin/chanelibility.php'><span class='fas fa-sticky-note'></span> Users Eligibility</a> ";
				echo "<a class='sButton' href='../sw_admin/adduser.php'><span class='fa fa-user-plus'></span> Add User</a> ";
			echo "</td></tr>";
		echo "</table>";	
														
		echo "<table class=whiteHeader>";										
			echo "<tr style='text-decoration:underline;'>";
				echo "<td>#</td>";
				echo "<td width=40% style='text-align:left;'>Full Name</td>";
				echo "<td class=specialTD><span style='text-decoration:underline;'>IC/ID</span> <span style='text-decoration:underline;'>(dbID)</span></td>";
				echo "<td>Account Type</td>";
				echo "<td>Option</td>";
				echo "<td>Status</td>";
			echo "</tr>";
												
			$n = 1;
			
			while ($myrow_fdb = mysqli_fetch_array($result_fdb))
			{
				echo "<tr class=yellowHover>";

					$id_fdb = $myrow_fdb["id"];	
					$username_fdb = $myrow_fdb["username"];
					$usertype_fdb = $myrow_fdb["usertype"];
					$name_fdb = $myrow_fdb["name"];
					$division_fdb = $myrow_fdb["division"];
					$online_fdb = $myrow_fdb["online"];					
					$lastlogin_fdb = $myrow_fdb["lastlogin"];
					$num_attempt_fdb = $myrow_fdb["num_attempt"];
					
					echo "<td>$n</td>";

					echo "<td class=specialTD>";
						echo "<span>$name_fdb ";
						if ($num_attempt_fdb >= $default_num_attempt_login) {echo "<img src='../sw_images/cu_locked.png' width=12>";}
						echo "</span>";
					echo "</td>";

					echo "<td class=specialTD><span>$username_fdb </span> <span>@$id_fdb</span></td>";	

					echo "<td>$usertype_fdb</td>";					
						
					echo "<td>";
						echo "<div class='dropdown'><button class='dropbtn2'>Options</button><div id='myDropdown".$n."' class='dropdown-content'>";
							echo "<a href='adduser.php?edt=$id_fdb' title='Edit User'><img src='../sw_images/cu_edituser.png' width=16>Edit User</a>";
							if ($username_fdb != 'admin')
							{
								echo " <a href='chanuser.php?del=$id_fdb' onclick=\"return confirm('Are you sure to set this user to inactive ? This application will be permanant.');\" title='Deactivate User'><img src='../sw_images/cu_deactivate.png' width=16> Deactivate User</a>";
								echo " <a href='chanuser.php?res=$id_fdb' onclick=\"return confirm('Are you sure to reset the user $username_fdb\'s password to $default_password_if_forgotten ?');\" title='Reset Password'><img src='../sw_images/cu_resetpass.png' width=16>Reset Password</a>";
								if ($num_attempt_fdb >= $default_num_attempt_login) {
									echo "<a href='chanuser.php?reb=$id_fdb' onclick=\"return confirm('Are you sure to unblock the user $username_fdb\'s ?');\" title='Unblock Account'><img src='../sw_images/cu_ublock.png' width=16>Unblock</a>";
								}									
							}
							echo " <a href='userhistory.php?pid=$id_fdb' title='User History'><img src='../sw_images/cu_history.png' width=16us>User History</a>";					
						echo "</div></div>";
					echo "</td>";
					
					echo "<td>";
						if ($online_fdb == 'ON')
						{				
							echo "<a class='sButtonRedSmall' href='chanuser.php?offuser=$id_fdb' onclick=\"return confirm('Are you sure to set offline to user $username_fdb ?');\"><span class=\"fas fa-sign-out-alt\"></span> Set Offline</a>";
							echo "<div style='font-size:6pt;'><strong>Logged:</strong> ".timetaken($lastlogin_fdb)."</div>";
						}
						else {
							echo "Offline";
						}
					echo "</td>";	

				echo "</tr>";
				$n = $n +1 ;
			}		
		echo "</table>";
	?>
		
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>