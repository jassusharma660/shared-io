<?php

  if(!defined("WEBSITE_NAME")) {
    include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';
    $url = $protocol.$_SERVER['HTTP_HOST'];
    header('location: '.$url);
  }

  include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/colors.php';

  $showLogout = false;

  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true)
    $showLogout = true;
?>
<header>
  <a href="/">
    <span class="logo">
      <img src="/app/assets/images/logos/logo.svg"/><b>shared</b>-io
    </span>
  </a>
</header>
<?php
  if($showLogout) {
?>

<div id="menu">
  <!--Some options-->
</div>
<div id="profile">
  <span class="profile_image" style="background-color:<?=getColorForWord($_SESSION['fullname']);?>">
    <?=$_SESSION['fullname'][0]?>
  </span>
  <span class="name"><?=$_SESSION['fullname']?></span><br/>
  <span class="email"><?=$_SESSION['email']?></span>
</div>
<div onclick="window.location.href='logout.php';" id="logout">
  <img src="/app/assets/images/icons/lock-96.png"/>
  Logout
</div>
<?php
  }
?>
