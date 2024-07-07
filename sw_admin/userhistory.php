<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_super.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Patron History";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>

	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>	

	<?php if (isset($_REQUEST["pid"]) && is_numeric($_REQUEST["pid"])) {?>
		<table class=whiteHeaderNoCenter>
			<tr class=yellowHeaderCenter>
				<td colspan=3><strong><?php echo namePatron($_REQUEST["pid"]);?></strong></td>
			</tr>
			
			<tr class=whiteHeaderCenterUnderline>
				<td style='width:5%;'></td>
				<td>Title</td>
				<td>Bookmarked on</td>
			</tr>
			
			<?php			
				$n = 1;

				$param_fdb = patronIdToUsername($_REQUEST["pid"]);
				$stmt_fdb = $new_conn->prepare("select * from eg_item_charge where 39patron=?");
				$stmt_fdb->bind_param("s", $param_fdb);
				$stmt_fdb->execute();
				$result_fdb = $stmt_fdb->get_result();
				while($myrow_fdb = $result_fdb->fetch_assoc())
					{
						$accessnum_fdb = $myrow_fdb["38accessnum"];
						$charged_on_fdb = $myrow_fdb["39charged_on"];
														
						echo "<tr class=yellowHover>";
							echo "<td style='text-align:center;'>$n</td>";
							echo "<td>".getTitle($accessnum_fdb)."<br/><div style='font-size:10px'><em>$accessnum_fdb</em></div></td>";
							echo "<td>".date('D, Y-m-d h:i:s a',$charged_on_fdb)."</td>";					
						echo "</tr>";
						$n = $n + 1;
					}
			?>
		</table><br/>
	<?php } else {echo "<h2 style='text-align:center;'>No history</h2>";}?>
		
	<div style='text-align:center;'><a class='sButton' href='chanuser.php'><span class='fas fa-arrow-circle-left'></span> Back to user account page</a></div>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>