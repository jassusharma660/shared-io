<?php
if(!defined("DOCUMENT_ROOT")) {
    include_once '../core/config.php';
    header('location:'.DOCUMENT_ROOT);
  }
?>
<footer>
  <span class="developer">Made with <img src="<?=ASSETS?>/images/icons/pixel-heart-50.png" alt="love"> by Jassu Sharma</span>
  <span id="copyright"></span>
</footer>
<script>
  document.getElementById("copyright").innerHTML = "&copy; "+new Date().getFullYear();
</script>
