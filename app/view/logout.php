<?php
//Logout action
session_start();
session_destroy();

include_once '../core/config.php';
header('location:'.DOCUMENT_ROOT);