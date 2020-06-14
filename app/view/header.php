<?php

  if(!defined("DOCUMENT_ROOT")) {
    include_once '../core/config.php';
    header('location:'.DOCUMENT_ROOT);
  }
  $showLogout = false;

  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true) 
    $showLogout = true;
?>
<header>
  <?=WEBSITE_NAME?>
  <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true) 
      $_SESSION['email']
    ?>  
  <?php
    if($showLogout) 
      echo "<button onclick=\"window.location.href='logout.php';\">logout</button>";
  ?>
</header>
