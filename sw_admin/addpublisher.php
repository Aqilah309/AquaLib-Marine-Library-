<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';
	include '../sw_includes/access_super.php';
	$thisPageTitle = "Publisher Listing";

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>	
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>
		
	<?php			

		if (isset($_GET["del"]))// if delete
		{
			$get_id_del = mysqli_real_escape_string($GLOBALS["conn"], $_GET["del"]);
			
			//delete statement with mysqli $new_conn
			$stmt_del = $new_conn->prepare("delete from eg_publisher where 43pubid = ?");
			$stmt_del->bind_param("i", $get_id_del);
			$stmt_del->execute();
			$stmt_del->close();
		}
				
		if (isset($_REQUEST["submitted"]) && $proceedAfterToken)// if submitted
		{
			$acronym1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"], $_POST["acronym1"]),'min');
			$publisher1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"], $_POST["publisher1"]),'min');
			
			if ($_REQUEST["submitted"] == "Insert")//if insert
			{
				//check for num of results affected by the query using mysqli
				$stmt_count = $new_conn->prepare("select count(*) from eg_publisher where 43acronym = ?");
				$stmt_count->bind_param("s", $acronym1);
				$stmt_count->execute();
				$stmt_count->bind_result($num_results_affected_publisher);//put it into $num_results_affected_publisher
				$stmt_count->fetch();
				$stmt_count->close();

				if ($num_results_affected_publisher == 0)
				{
					if (!empty($acronym1) && (!empty($publisher1)))
					{
						//insert query using mysqli prepared statement
						$stmt_insert = $new_conn->prepare("insert into eg_publisher values(DEFAULT,?,?,DEFAULT,DEFAULT)");
						$stmt_insert->bind_param("ss", $acronym1, $publisher1);
						$stmt_insert->execute();
						$stmt_insert->close();
						echo "<script>window.alert('The publisher $publisher1 ($acronym1) has been inputed into the database.');</script>";
					}
					
					else {
						echo "<script>window.alert('Your input has been cancelled.Check if any field(s) left emptied before posting.');</script>";	
					}
				}
				
				else if ($num_results_affected_publisher >= 1) {
					echo "<script>window.alert('Your input has been cancelled. Duplicate publisher detected.');</script>";		
				}
			}		

			else if ($_REQUEST["submitted"] == "Update")//if update
			{
				$id1 = $_POST["id1"];

				if (!empty($publisher1) && !empty($acronym1))
				{
					$stmt_update = $new_conn->prepare("update eg_publisher set 43publisher=?, 43acronym=? where 43pubid=?");
					$stmt_update->bind_param("ssi", $publisher1, $acronym1, $id1);
					$stmt_update->execute();
					$stmt_update->close();
					echo "<script>window.alert('The record has been updated.');</script>";					
				}
				else {
					echo "<script>window.alert('Error. Please make sure there were no empty field(s). The record has been restored to it original state.');</script>";
				}
			}
		}

		if (isset($_GET["edt"]))//get info if update to populate fields
		{
			$get_id_upd = mysqli_real_escape_string($GLOBALS["conn"], $_GET["edt"]);

			//find one result per prepared statement with mysqli $new_conn
			$stmt3 = $new_conn->prepare("select 43pubid, 43publisher, 43acronym from eg_publisher where 43pubid = ?");
			$stmt3->bind_param("i", $get_id_upd);//i integer, s string, d double, b blob
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($id3, $publisher3, $acronym3);//bind result from select statement
			$stmt3->fetch();
			$stmt3->close();
		}
	
	?>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td><strong>Publisher Addition</strong></td></tr>
		<tr class=greyHeaderCenter><td colspan=2><br/>
			<form action="addpublisher.php" method="post" enctype="multipart/form-data">
				<strong>Publisher: </strong>
				<br/><input type="text" name="publisher1" style="width:50%" maxlength="250" value="<?php if (isset($publisher3)) {echo $publisher3;} ?>"/>
				
				<br/><br/>
				<strong>Acronym: </strong>
				<br/><input type="text" name="acronym1" style="width:50%" maxlength="50" value="<?php if (isset($acronym3)) {echo $acronym3;} ?>"/>
				
				<input type="hidden" name="id1" value="<?php if (isset($id3)) {echo $id3;} ?>" />
				<input type="hidden" name="submitted" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" />	

				<br/><br/>
				<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
				<input type="submit" name="submit_button" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" /> 
				<input type="button" value="Cancel" onclick="location.href='addpublisher.php';">
			</form>			
		</td>
		</tr>			
	</table>
	
	<br/><br/>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter>
			<td colspan=4><strong>Publisher Controls :</strong></td>
		</tr>
		
		<tr class=whiteHeaderCenterUnderline>
			<td style='width:5%;'></td>
			<td style='text-align:left;'>Publisher</td>
			<td style='width:150;'>Acronym</td>
			<td style='width:150;'>Options</td>
		</tr>
		
		<?php			
			$n = 1;

			//find all result per prepared statement with mysqli $new_conn
			$stmt_fdb = $new_conn->prepare("select 43pubid, 43acronym, 43publisher from eg_publisher order by 43acronym");
			$stmt_fdb->execute();
			$result_fdb = $stmt_fdb->get_result();
			while($myrow_fdb = $result_fdb->fetch_assoc())	
				{
					$pubid_fdb = $myrow_fdb["43pubid"];
					$acronym_fdb = $myrow_fdb["43acronym"];
					$publisher_fdb = $myrow_fdb["43publisher"];
					
					echo "<tr class=yellowHover>";
						echo "<td>$n</td>";
						echo "<td style='text-align:left;'>$publisher_fdb</td>";
						echo "<td>$acronym_fdb</td>";
						echo "<td>";
							echo "<a title='Delete this record' href='addpublisher.php?del=$pubid_fdb' onclick=\"return confirm('Are you sure ? You are advisable to change all items based on this publisher to the another before proceeding.');\"><img src='../sw_images/delete.gif'></a> ";
							echo "<a title='Update this record' href='addpublisher.php?edt=$pubid_fdb'><img src='../sw_images/pencil.gif'></a>";
						echo "</td>";
					echo "</tr>";
					
					$n = $n + 1;
				}
		?>
	</table>

	<hr>

	<?php include '../sw_includes/footer.php'; ?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>