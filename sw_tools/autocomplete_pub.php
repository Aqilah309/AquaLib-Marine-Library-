<?php 	

include '../core.php'; 

// Get search term
if (!isset($_GET['term'])) {
        die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
}

$searchTerm = mysqli_real_escape_string($GLOBALS["conn"],$_GET['term']);

//Generate skills data array
$itemData = array();

$param = "%$searchTerm%";
$stmtB = $new_conn->prepare("SELECT 43publisher FROM eg_publisher WHERE 43publisher LIKE ? order by 43publisher");
$stmtB->bind_param("s", $param);//s string
$stmtB->execute();
$resultB = $stmtB->get_result();
							
//while ($row = mysqli_fetch_array($resultB))
while($row = $resultB->fetch_assoc())	
{
        array_push($itemData, $row['43publisher']);
}

// Return results as json encoded array
echo json_encode($itemData);

?>