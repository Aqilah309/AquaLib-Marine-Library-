<?php

    session_start();
    include '../core.php';
    ini_set('max_execution_time',180);

    if (!isset($_GET['p'])) {exit;}       
    $pid= mysqli_real_escape_string($GLOBALS["conn"],$_GET['pid']);

    $param = "%".$_GET['p']."%";
    $stmt_term = $new_conn->prepare("select count(id) as totalSubPub from eg_item2 where 38publication_b like ?");
    $stmt_term->bind_param("s", $param);//s string
    $stmt_term->execute();
    $stmt_term->bind_result($count_SubPub);
    $stmt_term->fetch();
    $stmt_term->free_result();
    $stmt_term->close();

    if ($count_SubPub >= 1) {echo $count_SubPub;}
    else {echo 0;}		

    //new for counting
    $lastcount_timestamp_update = time();
    $stmt_update = $new_conn->prepare("update eg_publisher set 43count=?, 43lastcount_timestamp=? where 43pubid=?");
    $stmt_update->bind_param("isi", $count_SubPub, $lastcount_timestamp_update, $pid);
    $stmt_update->execute();
    $stmt_update->close();
    
?>