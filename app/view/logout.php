<?php
//Logout action
session_start();
session_destroy();
if(!defined("WEBSITE_NAME")) {
  include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';
  $url = $protocol.$_SERVER['HTTP_HOST'];
  header('location: '.$url);
}
