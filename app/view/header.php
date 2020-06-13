<?php

  if(!defined("WEBSITE_NAME")) {
    include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';
    $url = $protocol.$_SERVER['HTTP_HOST'];
    header('location: '.$url);
  }
  $showLogout = false;

  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true)
    $showLogout = true;
?>
<header>
  <span class="logo">
    <img src="/app/assets/images/logos/logo.svg"/><b>shared</b>-io
  </span>
</header>
<?php
  if($showLogout) {
?>

<div id="menu">
  <!--Some options-->
</div>
<div id="profile">
  <span class="profile_image">
    <?=$_SESSION['fullname'][0]?>
  </span>
  <span class="name"><?=$_SESSION['fullname']?></span>
  <span class="email"><?=$_SESSION['email']?></span>
</div>
<div id="logout">
  <img src="/app/assets/images/icons/lock-96.png"/>
  <a onclick="window.location.href='logout.php';">
    Logout
  </a>
</div>
<?php
  }
?>
