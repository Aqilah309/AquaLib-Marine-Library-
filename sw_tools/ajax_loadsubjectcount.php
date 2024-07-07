<?php

    session_start();
    include '../core.php';
    ini_set('max_execution_time',180);

    if (!isset($_GET['a']) && !isset($_GET['s'])) {exit;}

    $acronym = mysqli_real_escape_string($GLOBALS["conn"],$_GET['a']);
    $subject = mysqli_real_escape_string($GLOBALS["conn"],$_GET['s']);
    $sid = mysqli_real_escape_string($GLOBALS["conn"],$_GET['sid']);

    if ($subject_heading_selectable == "multi")
        {
            $param1 = "%$acronym".$subject_heading_delimiter."%";
            $param2 = "$acronym".$subject_heading_delimiter."%";
            $param3 = $acronym;
            $stmt_term = $new_conn->prepare("select count(id) as totalSubHeading from eg_item where 41subjectheading like ? or  41subjectheading like ?  or  41subjectheading like ?");
            $stmt_term->bind_param("sss", $param1,$param2,$param3);//s string
        }
    else	
        {
            $param = $acronym;
            $stmt_term = $new_conn->prepare("select count(id) as totalSubHeading from eg_item where 41subjectheading like ?");
            $stmt_term->bind_param("s", $param);//s string
        }

    $stmt_term->execute();
    $stmt_term->bind_result($count_SubHeading);
    $stmt_term->fetch();
    $stmt_term->free_result();
    $stmt_term->close();

    if ($count_SubHeading >= 1)
        {echo "$count_SubHeading";}							
    else
        {echo "0";}   

    //new for counting
    $lastcount_timestamp_update = time();
    $stmt_update = $new_conn->prepare("update eg_subjectheading set 43count=?, 43lastcount_timestamp=? where 43subjectid=?");
    $stmt_update->bind_param("isi", $count_SubHeading, $lastcount_timestamp_update, $sid);
    $stmt_update->execute();
    $stmt_update->close();

?>