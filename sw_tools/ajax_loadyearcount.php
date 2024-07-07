<?php

    session_start();
    include '../core.php';
    ini_set('max_execution_time',180);

    if (!isset($_GET['y'])) {exit;}
    
    $year = mysqli_real_escape_string($GLOBALS["conn"],$_GET['y']);

    $stmt_term = $new_conn->prepare("select count(distinct(eg_item_id)) as totalSubYear from eg_item2 where 38publication_c like ?");
    $stmt_term->bind_param("s", $year);//s string
    $stmt_term->execute();
    $stmt_term->bind_result($count_SubYear);
    $stmt_term->fetch();
    $stmt_term->free_result();
    $stmt_term->close();
    
    if ($count_SubYear >= 1)
        {echo "$count_SubYear";}								
    else
        {echo "0";}   

    //delete old value
    $stmt_del = $new_conn->prepare("delete from eg_stat_year where id = ?");
    $stmt_del->bind_param("i", $year);
    $stmt_del->execute();
    $stmt_del->close();

    //new for counting
    $lastcount_timestamp_update = time();
    $stmt_insert = $new_conn->prepare("insert into eg_stat_year values(?,?,?)");
    $stmt_insert->bind_param("iis", $year, $count_SubYear, $lastcount_timestamp_update);
    $stmt_insert->execute();
    $stmt_insert->close();

?>