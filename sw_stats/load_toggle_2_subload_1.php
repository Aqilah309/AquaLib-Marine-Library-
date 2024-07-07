<?php
session_start();
include '../sw_includes/access_isset.php';
include '../core.php';
ini_set('max_execution_time',180);

if (!isset($_GET['t'])) {exit;}
$t = mysqli_real_escape_string($GLOBALS["conn"],$_GET['t']); if (!is_numeric($t)) {$t = 0;}

function echoandputcontents_items($t,$system_statcache_directory)
{
    //count total number of item for a type
    $query_totalcount = "select count(id) as totalid from eg_item where 38typeid='$t'";
    $result_totalcount = mysqli_query($GLOBALS["conn"],$query_totalcount);
    $myrow_totalcount = mysqli_fetch_array($result_totalcount);
    echo $myrow_totalcount["totalid"];

    file_put_contents("../$system_statcache_directory/".$t."_itemscount.txt", $myrow_totalcount["totalid"]."\n".time());
}

if (file_exists("../$system_statcache_directory/".$t."_itemscount.txt") && $report_count_generator == 'daily')
{
    $lines = file("../$system_statcache_directory/".$t."_itemscount.txt");
    $diff = time() - $lines[1];
    if ($diff < 86400)
        {echo $lines[0];}
    else
    {
        echoandputcontents_items($t,$system_statcache_directory);
    }
}
else
{
    echoandputcontents_items($t,$system_statcache_directory);
}

?>