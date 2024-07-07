<?php
session_start();
include '../sw_includes/access_isset.php';
include '../core.php';
ini_set('max_execution_time',180);

    $query1 = "select id,username,name,lastlogin,online,input_count,timestamp_count from eg_auth where usertype='SUPER' or usertype='STAFF'";
    $result1 = mysqli_query($GLOBALS["conn"],$query1);	
                            
    echo "<table class=whiteHeader><tr class=yellowHeaderCenter><td>";
    echo "<strong>Data Managers Report :</strong><br/>System administrators and data managers";
    echo " with their input statistics.";
    echo "</td></tr></table>";			
                                        
    echo "<table class=whiteHeaderNoCenter>";										
    echo "<tr class=whiteHeaderCenterUnderline><td colspan=2>User</td><td width=20%>Total Input</td><td width=20%>Last logged-in</td></tr>";
                                                                                
    $n = 1;
                                        
    while ($myrow1 = mysqli_fetch_array($result1))
        {
            echo "<tr class=yellowHover>";
            
            $id1 = $myrow1["id"];
            $username1 = $myrow1["username"];
            $name1 = $myrow1["name"];
            $lastlogin1 = $myrow1["lastlogin"];
            $online1 = $myrow1["online"];
            $input_count1 = $myrow1["input_count"];
            $timestamp_count1 = $myrow1["timestamp_count"];
                                                                                                  
            echo "<td>$n</td>";
            echo "<td style='text-align:left;'><span style='color:green;'>$name1</span> ";
                if ($_SESSION['editmode'] == 'SUPER') {echo "[$username1]";}
            echo "</td>";
            echo "<td style='text-align:center;'><a href='adsreport_details.php?inf=$username1&infname=$name1'>";
                $diff = time() - $timestamp_count1;
                if ($diff <= 86400 && $report_count_generator == 'daily') {
                    echo $input_count1;
                }
                else {
                    $query_totalinput = "select count(id) as totalid from eg_item where 39inputby='$username1' ";										
                    $result_totalinput = mysqli_query($GLOBALS["conn"],$query_totalinput);
                    $myrow_totalinput = mysqli_fetch_array($result_totalinput);
                    $num_results_affected_totalinput = $myrow_totalinput["totalid"];
                    echo $num_results_affected_totalinput;                    

                    //new for counting
                    $lastcount_timestamp_update = time();
                    $stmt_update = $new_conn->prepare("update eg_auth set input_count=?, timestamp_count=? where id=?");
                    $stmt_update->bind_param("isi", $num_results_affected_totalinput, $lastcount_timestamp_update, $id1);
                    $stmt_update->execute();
                    $stmt_update->close();
                }                
            echo "</a></td>";
            echo "<td>";
            if ($online1 == 'ON')
                {echo "<span style='color:green;'>$lastlogin1</span><sup>ONLINE</sup>";}
            else
                {echo "$lastlogin1";}
            echo "</td></tr>";
                                                                                            
            $n = $n +1 ;
        }
    echo "</table>";
    if ($report_count_generator == 'daily') {
        echo "<br/><em>Statistic generated date and time: ".date('Y-m-d H:i:s',$timestamp_count1).".<br/>Statistic is valid for that date and time. New statistic will be generated 24 hours later.</em>";	
    }
?>