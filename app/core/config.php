<?php
$protocol = "";
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}
/**************************
  THIS WILL SET DATABASE
  credentials
**************************/
define('DB_HOST','localhost');
define('DB_NAME','shared-io');
define('DB_USER','sharedio_dba');
define('DB_PASS','sharedio_dba');


/**************************
  THIS WILL SET PATHS
**************************/
define('WEBSITE_NAME','shared-io');
$ROOT_PATH = $_SERVER['DOCUMENT_ROOT'];
$APP_PATH = $ROOT_PATH."/app/";
$CORE_PATH = $APP_PATH."core/";
$STORAGE_PATH = $APP_PATH."storage/";
$VIEW_PATH = $APP_PATH."view/";
