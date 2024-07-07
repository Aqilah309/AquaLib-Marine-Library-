<?php
session_start();
include '../core.php';
include '../sw_includes/access_isset_depo.php';

if (isset($_GET['docid']) && is_numeric($_GET['docid']))
{
    $query_fdb = "select SQL_CALC_FOUND_ROWS * from eg_item_depo where inputby='".$_SESSION['useridentity']."'and id=".$_GET['docid'];
    $result_fdb = mysqli_query($GLOBALS["conn"],$query_fdb);
    $myrow_fdb = mysqli_fetch_array($result_fdb);
        $id = $myrow_fdb["id"] ?? null;
        $year = $myrow_fdb["year"] ?? null;
        $timestamp = $myrow_fdb["timestamp"] ?? null;

        //declaration
        if (isset($_GET['t']) && $_GET['t'] == 'd' && file_exists("../$system_dfile_directory/$year/$id"."_".$timestamp.".pdf")) {
            $file = "../$system_dfile_directory/$year/$id"."_".$timestamp.".pdf";
            $filename = $_SESSION['useridentity']."_Declaration_Form.pdf";
        }

        //full text
        else if (isset($_GET['t']) && $_GET['t'] == 'p' && file_exists("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf")) {
            $file = "../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf";
            $filename = $_SESSION['useridentity']."_Full_Text_Submission.pdf";
        }

        else
        {
            echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>File not found.</h2><em>sWADAH Response Code</em></div>";
            exit;
        }

    ob_start();
    header("Cache-Control: public, must-revalidate");
    header("Pragma: no-cache");
    header("Content-Type: application/pdf");
    header("Content-Length: " .(string)(filesize($file)) );
    header("Content-Disposition: inline; filename=$filename");//can be set to attachment or inline
    header("Content-Transfer-Encoding: binary\n"); 
    ob_end_clean();
    flush();
    readfile($file);		
}
else
{
    echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>File not found.</h2><em>sWADAH Response Code</em></div>";
}
exit;
?>