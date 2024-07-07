<?php 
  defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
?>

<script>
function myShowMenuItemReg() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {x.className += " responsive";} 
    else {x.className = "topnav";}
}
</script>

<div class="topnav" id="myTopnav">
  <a href="<?php echo $appendroot;?>index2.php?sc=cl" class="active"><img alt='Menu Icon' src='<?php echo $appendroot;?><?php echo $menu_icon;?>' width=20></a>
  
  <?php if (!$_SESSION['needtochangepwd']) {?>
    <div class="dropdownNB">
      <button class="dropbtn"><span class="fa fa-keyboard"></span> Deposit Handling 
        <span class="fa fa-caret-down"></span>
      </button>
      <div class="dropdownNB-content">
        <?php 
          if ($system_function == 'full' || $system_function == 'repo' || $system_function == 'photo')				
          {
        ?>
            <a href="<?php echo $appendroot;?>sw_admin/reg.php"><span class="fas fa-folder-plus"></span> Add new item</a>
            <?php 
              if ($_SESSION['editmode'] == 'SUPER')				
              {
            ?>
                <a href="<?php echo $appendroot;?>sw_admin/delreq.php"><span class="fas fa-folder-minus"></span> Delete Request</a>          
            <?php
              }
            ?>
            <a href="<?php echo $appendroot;?>sw_admin/dupfinder.php"><span class="fas fa-copy"></span> Duplicate Finder</a>
            <a href="<?php echo $appendroot;?>sw_admin/embargoed.php"><span class="fas fa-folder"></span> Embargoed List</a>
        <?php
          }
        ?>
        <?php 
          if ($system_function == 'full' || $system_function == 'depo' || $system_function == 'photo')				
          {
        ?>
          <?php if ($allow_depositor_function) { ?>
            <a href="<?php echo $appendroot;?>sw_depos/depoadmin.php?v=entry"><span class="fas fa-university"></span> Manage User Deposit</a>
          <?php }?>
        <?php
          }
        ?>
      </div>
    </div> 
  <?php }?>
  
  <?php if (!$_SESSION['needtochangepwd'] && $_SESSION['editmode'] == 'SUPER') {?>
        <div class="dropdownNB">
          <button class="dropbtn"><span class="fas fa-database"></span> Foundation
            <span class="fa fa-caret-down"></span>
          </button>              
                <div class="dropdownNB-content">
                <?php 
                  if ($system_function == 'full' || $system_function == 'repo' || $system_function == 'photo')				
                  {
                ?>
                  <a href="<?php echo $appendroot;?>sw_admin/addtype.php"><span class="fas fa-clipboard-list"></span> Item Types</a>
                  <a href="<?php echo $appendroot;?>sw_admin/addsubject.php"><span class="fas fa-clipboard-list"></span> <?php echo $subject_heading_as;?></a>
                <?php
                  }
                ?> 
                  <a href="<?php echo $appendroot;?>sw_admin/addpublisher.php"><span class="fas fa-clipboard-list"></span> Publisher</a>
                </div>              
        </div>
  <?php }?>
  
  <?php if (!$_SESSION['needtochangepwd'] && $_SESSION['editmode'] == 'SUPER') {?>
        <div class="dropdownNB">
          <button class="dropbtn"><span class="fa fa-desktop"></span> Administration
            <span class="fa fa-caret-down"></span>
          </button>              
                <div class="dropdownNB-content">
                  <a href="<?php echo $appendroot;?>sw_stats/adsreport.php"><span class="fas fa-chart-bar"></span> Report Generator</a>
                <?php if ($system_mode != 'demo' && (!$aes_key_warning || $password_aes_key != "45C799DB3EBC65DFBC69A0F36F605E6CA2447CD519C50B7DA0D0D45D2B0F2431")) {?>
                  <a href="<?php echo $appendroot;?>sw_admin/chanuser.php"><span class="fas fa-user-circle"></span> User Account Management</a>
                <?php }?>
                <?php 
                  if ($system_function == 'full' || $system_function == 'depo' || $system_function == 'photo')				
                  {
                ?>
                  <?php if ($allow_depositor_function && (!$aes_key_warning || $password_aes_key != "45C799DB3EBC65DFBC69A0F36F605E6CA2447CD519C50B7DA0D0D45D2B0F2431")) { ?>
                    <a href="<?php echo $appendroot;?>sw_depos/depouser.php?show=NOTACTIVE"><span class="fas fa-users"></span> Depositor Management</a>
                  <?php }?>
                <?php 
                  }
                ?>
                  <?php if ($ip_restriction_enabled) { ?>
                    <a href="<?php echo $appendroot;?>sw_admin/ipcontrol.php"><span class="fas fa-shield-alt"></span> Allowed IP</a> 
                  <?php }?>
                  <a href="<?php echo $appendroot;?>sw_admin/checkpdfindexer.php"><span class="fas fa-file-pdf"></span> Check PDF Indexer</a>
                </div>              
        </div>
  <?php }?>
  
  <?php if (!$_SESSION['needtochangepwd']) {?>
    <div class="dropdownNB">
      <button class="dropbtn"><span class="fa fa-info"></span> Help
        <span class="fa fa-caret-down"></span>
      </button>
      <div class="dropdownNB-content">
        <a href="<?php echo $appendroot;?>faq.php"><span class="fas fa-question-circle"></span> FAQ</a>
        <a href="<?php echo $appendroot;?>oai2.php?verb=Identify" target='blank'><span class="fas fa-file-code"></span> OAI-PMH</a>
        <a href="<?php echo $appendroot;?>about.php"><span class="fas fa-server"></span> About</a>
      </div>
    </div>
  <?php }?>

  <?php if ($system_mode != 'demo') {?>
    <a href="<?php echo $appendroot;?>sw_admin/passchange.php?upd=.g"><span class="fa fa-key"></span> Change Password</a>
  <?php }?>
  <a href="<?php echo $appendroot;?>index.php?log=out" onclick="return confirm('Are you sure?')"><span class="fa fa-user"></span> Logout</a>
  <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myShowMenuItemReg()">&#9776;</a>
  
</div>