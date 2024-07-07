<?php
        include '../core.php';

        $get_id_det = mysqli_real_escape_string($GLOBALS["conn"],$_GET['d']);
        $get_type = mysqli_real_escape_string($GLOBALS["conn"],$_GET['t']);

        if (is_numeric($get_id_det) and ($get_type == 't' or $get_type == 'w' or is_numeric($get_type)))
        {
            $query_item = "select id,39inputdate,41instimestamp from eg_item where id='$get_id_det'";   
            $result_item = mysqli_query($GLOBALS["conn"],$query_item);
            $myrow_item = mysqli_fetch_array($result_item);
           
            $id = $myrow_item["id"];
            $inputdate = $myrow_item["39inputdate"];
            $instimestamp = $myrow_item["41instimestamp"];	
            $dir_year = substr($inputdate,0,4);

            if ($get_type == 't')
                {$im = imagecreatefromjpeg("../$system_albums_thumbnail_directory/$dir_year/$id"."_"."$instimestamp.jpg");}
            else if ($get_type == 'w')
                {$im = imagecreatefromjpeg("../$system_albums_watermark_directory/$dir_year/$id"."_"."$instimestamp.jpg");}
            else
               { $im = imagecreatefromjpeg("../$system_albums_directory/$dir_year/$id"."_"."$instimestamp"."/$get_type"."_wm.jpg");}
                
            header('Content-Type: image/jpg');
            imagejpeg($im);
            imagedestroy($im);
        }
        else
        {
            echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>Invalid Request.</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div>";
		    mysqli_close($GLOBALS["conn"]);exit;
        }
?>