<?php
session_start();
include '../sw_includes/access_isset.php';
include '../core.php';
ini_set('max_execution_time',180);

if (!isset($_GET['t'])) {exit;}
$t = mysqli_real_escape_string($GLOBALS["conn"],$_GET['t']); if (!is_numeric($t)) {$t = 0;}

function echoandputcontents_sessions($t,$system_statcache_directory)
{
    //count total session of all item for a type (group by similarly ippaddress and logdate to prevent multiple counting)
    $query_totalsession = "select count(eg_item_access.id) as totalid 
    from eg_item_access, eg_item 
    where eg_item.38typeid='$t' and eg_item_access.eg_item_id=eg_item.id  
    group by eg_item_access.39ipaddr, SUBSTRING_INDEX(eg_item_access.39logdate,' ', 2)";
    $result_totalsession = mysqli_query($GLOBALS["conn"],$query_totalsession);
    echo mysqli_num_rows($result_totalsession);

    file_put_contents("../$system_statcache_directory/".$t."_sessionscount.txt", mysqli_num_rows($result_totalsession)."\n".time());
}

if (file_exists("../$system_statcache_directory/".$t."_sessionscount.txt") && $report_count_generator == 'daily')
{
    $lines = file("../$system_statcache_directory/".$t."_sessionscount.txt");
    $diff = time() - $lines[1];
    if ($diff < 86400)
        {echo $lines[0];}
    else
    {
        echoandputcontents_sessions($t,$system_statcache_directory);
    }
}
else
{
    echoandputcontents_sessions($t,$system_statcache_directory);
}

?>