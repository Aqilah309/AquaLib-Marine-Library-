<?php
session_start();
include '../sw_includes/access_isset.php';
include '../core.php';
ini_set('max_execution_time',180);

if (!isset($_GET['t'])) {exit;}
$t = mysqli_real_escape_string($GLOBALS["conn"],$_GET['t']); if (!is_numeric($t)) {$t = 0;}

function echoandputcontents_hits($t,$system_statcache_directory)
{
    //count total hits for a type
    $query_access = "select count(eg_item_access.id) as totalhits 
    from eg_item_access inner join eg_item 
    on eg_item_access.eg_item_id=eg_item.id 
    where eg_item.38typeid='$t'";
    $result_access = mysqli_query($GLOBALS["conn"],$query_access);
    $myrow_access = mysqli_fetch_array($result_access);
    echo $myrow_access["totalhits"];

    file_put_contents("../$system_statcache_directory/".$t."_hitscount.txt", $myrow_access["totalhits"]."\n".time());
}

if (file_exists("../$system_statcache_directory/".$t."_hitscount.txt") && $report_count_generator == 'daily')
{
    $lines = file("../$system_statcache_directory/".$t."_hitscount.txt");
    $diff = time() - $lines[1];
    if ($diff < 86400)
       { echo $lines[0];}
    else
    {
        echoandputcontents_hits($t,$system_statcache_directory);
    }
}
else
{
    echoandputcontents_hits($t,$system_statcache_directory);
}
?>