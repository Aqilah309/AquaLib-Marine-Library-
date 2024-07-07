<?php
session_start();
include '../sw_includes/access_isset.php';
include '../core.php';
ini_set('max_execution_time',180);

    $query2 = "select 38type,38typeid from eg_item_type";
    $result2 = mysqli_query($GLOBALS["conn"],$query2);

    echo "<table class=whiteHeader>";
    echo "<tr class=yellowHeaderCenter><td><strong>Access Statistic</strong> : <br/><em>Detail statistic are shown when clicking any of the figures.</em>";
    echo "</td></tr></table>";	
                                            
    echo "<table class=whiteHeader>";										
        echo "<tr class=whiteHeaderCenterUnderline><td></td><td style='text-align:left;'>Type</td>";
            echo "<td width=20%>Total Item</td>";
            echo "<td width=20%>Total Session</td>";
            echo "<td width=20%>Total Access</td>";
        echo "</tr>";
                                                                                        
        $m = 1;
        $item_total = 0;
        $session_total = 0;
        $hits_total = 0;
                                                
        while ($myrow2 = mysqli_fetch_array($result2))
            {
                echo "<tr class=yellowHover>";						
                    $typestatement2 = $myrow2["38typeid"];  
                    $typedesc2 = $myrow2["38type"];                
                                                                                                            
                    echo "<td>$m</td><td style='text-align:left;'>$typedesc2</td>";	
                    echo "
							<script>
							$(document).ready(function(){		
								$.ajax({
									url: 'load_toggle_2_subload_1.php?t=$typestatement2',
									success: function(data){				
										$('#loaditemscount$m').html(data);
									}
								})
							});
							</script>
                            <script>
							$(document).ready(function(){		
								$.ajax({
									url: 'load_toggle_2_subload_2.php?t=$typestatement2',
									success: function(data){				
										$('#loadsessionscount$m').html(data);
									}
								})
							});
							</script>
                            <script>
							$(document).ready(function(){		
								$.ajax({
									url: 'load_toggle_2_subload_3.php?t=$typestatement2',
									success: function(data){				
										$('#loadhitscount$m').html(data);
									}
								})
							});
							</script>
							";
                    echo "<td><a href='adsreport_typedetails.php?type=$typestatement2&typetext=$typedesc2'><span id='loaditemscount$m'>Loading data.. </span></a></td>";
                    echo "<td><a href='adsreport_typeaccess.php?type=$typestatement2&typetext=$typedesc2'><span id='loadsessionscount$m'>Loading data.. </span></a></td>";
                    echo "<td><a href='adsreport_typeaccess.php?type=$typestatement2&typetext=$typedesc2'><span id='loadhitscount$m'>Loading data.. </span></a></td>";
                echo "</tr>";
                
                $m = $m +1 ;
            }

            function echoandputcontents_allsum($system_statcache_directory)
            {
                //count total number of item for all type
                $query_totalcount = "select count(id) as totalid from eg_item";
                $result_totalcount = mysqli_query($GLOBALS["conn"],$query_totalcount);
                $myrow_totalcount = mysqli_fetch_array($result_totalcount);
                $item_total = $myrow_totalcount["totalid"];

                //count total session of all item for all type (group by similarly ippaddress and logdate to prevent multiple counting)
                $query_totalsession = "select count(eg_item_access.id) as totalid 
                                            from eg_item_access, eg_item 
                                            where eg_item_access.eg_item_id=eg_item.id  
                                            group by eg_item.38typeid, eg_item_access.39ipaddr, SUBSTRING_INDEX(eg_item_access.39logdate,' ', 2)";
                $result_totalsession = mysqli_query($GLOBALS["conn"],$query_totalsession);
                $session_total = mysqli_num_rows($result_totalsession);
                
                //count total hits for all type
                $query_access = "select count(eg_item_access.id) as totalhits 
                                    from eg_item_access inner join eg_item 
                                    on eg_item_access.eg_item_id=eg_item.id";
                $result_access = mysqli_query($GLOBALS["conn"],$query_access);
                $myrow_access = mysqli_fetch_array($result_access);
                $hits_total = $myrow_access["totalhits"];

                file_put_contents("../$system_statcache_directory/allcount.txt", time()."\n$item_total\n$session_total\n$hits_total");
            }

            if (file_exists("../$system_statcache_directory/allcount.txt") && $report_count_generator == 'daily')
            {
                $lines = file("../$system_statcache_directory/allcount.txt");
                $diff = time() - $lines[0];
                if ($diff > 86400)
                   {echoandputcontents_allsum($system_statcache_directory);}
            }
            else
            {
                echoandputcontents_allsum($system_statcache_directory);
            }

            $lines = file("../$system_statcache_directory/allcount.txt");
            echo "<tr style='text-align:center;background-color:lightgrey'><td colspan=2 style='text-align:right;background-color:white'><em>Total :</em></td>";
                echo "<td><em>".$lines[1]."</em></td>";
                echo "<td><em>".$lines[2]."</em></td>";	
                echo "<td><em>".$lines[3]."</em></td>";
            echo "</tr>";
    echo "</table>";
    if ($report_count_generator == 'daily') {
        echo "<br/><em>Statistic generated date and time: ".date('Y-m-d H:i:s',$lines[0]).".<br/>Statistic is valid for that date and time. New statistic will be generated 24 hours later.</em>";	
    }
?>