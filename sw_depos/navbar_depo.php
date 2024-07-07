<?php
      defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
      
      if (isset($_SESSION['useridentity']))
      {
        $query_countuseridentity = "select count(id) as totaluserdeposit from eg_item_depo where inputby='".$_SESSION['useridentity']."'";
        $result_countuseridentity = mysqli_query($GLOBALS["conn"],$query_countuseridentity);
        $myrow_countuseridentity= mysqli_fetch_array($result_countuseridentity);
        $totaluserdeposit = $myrow_countuseridentity["totaluserdeposit"];
      ?>
        <script type="text/javascript">
            function myShowMenuItem() {
                var x = document.getElementById("myTopnav");
                if (x.className === "topnav") 
                    x.className += " responsive";
                else 
                    x.className = "topnav";
            }
        </script>
        <div class="topnav" id="myTopnav">
          <a href="depositor.php" class="active"><img alt='Menu Icon' src='../<?php echo $menu_icon;?>' width=20></a>
          <?php if ($totaluserdeposit < $limit_amount_userdeposit) {?><a href="deporeg.php"><span class="fa fa-plus"></span> Insert Deposit</a><?php }?>
          <a href="depopchange.php?upd=g"><span class="fa fa-key"></span> Change Password</a>
          <a href="depologin.php?log=out"><span class="fa fa-user"></span> Logout</a>
          <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myShowMenuItem()">&#9776;</a>
        </div>
        <?php 
      }
?>