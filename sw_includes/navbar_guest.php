<?php
      defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
      
      if (isset($_SESSION['username_guest']))
      {
      ?>
        <script>
          function myShowMenuItemGuest() {
              var x = document.getElementById("myTopnav");
              if (x.className === "topnav") {x.className += " responsive";} 
              else {x.className = "topnav";}
          }
        </script>

        <div class="topnav" id="myTopnav">
          <a href="<?php echo $appendroot;?>usr.php" class="active"><img alt='Menu Icon' src='<?php echo $appendroot.$menu_icon;?>' width=20></a>
          <a href="<?php echo $appendroot;?>searcher.php?sc=cl"><span class="fa fa-search"></span> Searcher</a>
          <a href="<?php echo $appendroot;?>usr.php?u=g"><span class="fa fa-book"></span> My Bookmarks</a>
          <div class="dropdownNB">
            <button class="dropbtn"><span class="fa fa-info"></span> Help
              <span class="fa fa-caret-down"></span>
            </button>
            <div class="dropdownNB-content">
              <a href="<?php echo $appendroot;?>faq.php">FAQ</a>
              <a href="<?php echo $appendroot;?>about.php">About</a>
            </div>
          </div>
          <a href="<?php echo $appendroot;?>passchange.php?upd=.g"><span class="fa fa-key"></span> Change Password</a>
          <a href="<?php echo $appendroot;?>index.php?log=out" onclick="return confirm('Are you sure?')"><span class="fa fa-user"></span> Logout</a>
          <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myShowMenuItemGuest()">&#9776;</a>
        </div>

        <?php               
        if (basename($_SERVER["SCRIPT_FILENAME"], '.php') == 'usr' && !isset($_GET['u']))
        {
        ?>   
          <table class=whiteHeaderNoCenter>
            <tr>
              <td colspan=2>
                <?php echo getUserInfo($_SESSION["username_guest"],$system_helpdesk_contact);?> 
              </td>
            </tr>
          </table>
        <?php
        }
      }

      else
      {
        if (!isset($_SESSION['username']))
	      {
      ?>
        <table class="guest_headerbar">
          <tr><td colspan=2 style='height:29;text-align:right;'>	
              <strong><div style='color:black;font-size:16px;'>
              <?php 
                echo $system_title;
                if ($_SERVER["REMOTE_ADDR"] == $ezproxy_ip) {echo " [EZPROXY MODE]";}
              ?>
              </div></strong>
              <div style='color:white;font-size:14px;text-align:right;'>
              <?php
                echo "<a href='".$appendroot."searcher.php?sc=cl' class='nav'>Start</a> | ";
                if ($allow_user_to_login) {echo "<a href='".$appendroot."usrlogin.php' class='nav'>Login</a> | ";}
                echo "<a href='$appendroot"."faq.php' class='nav'>FAQ</a> | <a href='$appendroot"."about.php' class='nav'>About</a>";
              ?>		
              </div>
          </td></tr>
        </table>
      <?php
        }
      }
	?>