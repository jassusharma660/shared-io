<?php
if(!defined("WEBSITE_NAME")) {
  include_once $_SERVER['DOCUMENT_ROOT'].'/app/core/config.php';
  $url = $protocol.$_SERVER['HTTP_HOST'];
  header('location: '.$url);
}
?>
<footer>
  <span class="developer">Made with <img src="/app/assets/images/icons/heart-24.png" alt="love"> by Jassu Sharma</span>
  <span id="copyright"></span>
</footer>
<script>
  $("#copyright").html("&copy; "+new Date().getFullYear());
</script>
