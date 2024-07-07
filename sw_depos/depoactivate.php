<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php'; 
	if (isset($_SESSION['useridentity'])) {unset($_SESSION['useridentity']);}
	$thisPageTitle  = "Depositor Login Page";
?>

<html lang='en'>

<head>
	<?php
		if (!$allow_depositor_function)
		{
			header("Location: ../index.php");
			die();
		}
	?>	
	<?php include '../sw_includes/header.php'; ?>
</head>

<body style='text-align:center;'>		
	
	<?php	    
				
		if (isset($_REQUEST['k']) && $_REQUEST["k"] != '')
		{
			$param_key = addslashes($_REQUEST['k']);
			
			$stmt_login = $new_conn->prepare("select registerkey from eg_auth_depo where registerkey=? and activation='NOTACTIVE'");
			$stmt_login->bind_param("s", $param_key);
			$stmt_login->execute();
			$stmt_login->store_result();
				$num_results_affected_login = $stmt_login->num_rows;
			$stmt_login->bind_result($registerkey);
			$stmt_login->fetch();
			$stmt_login->close();
			
			if ($num_results_affected_login <> 0)
			{
				$stmt_update = $new_conn->prepare("update eg_auth_depo set activation='ACTIVE' where registerkey=?");
				$stmt_update->bind_param("s", $param_key);
				$stmt_update->execute();
				$stmt_update->close();
				echo "<img src='../sw_images/tick.gif' width=64px><br/><br/><div style='text-align:center;color:blue;'>Your account has been activated.<br/><a href='$system_path"."sw_depos/depologin.php'>Click here</a> to login.<br/><br/></div>";
			}
			else {
				echo "<img src='../sw_images/caution.png'><br/><br/><div style='text-align:center;color:red;'>Invalid registration key or account has been activated. IP will be logged for security purposes.<br/><br/></div>";
			}
		}
			
		else {
			echo "<img src='../sw_images/caution.png'><br/><br/><div style='text-align:center;color:red;'>Invalid registration key. IP will be logged for security purposes.<br/><br/></div>";
		}
	?>

	<?php include '../sw_includes/footer.php';?>
	
</body>
	
</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>