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
//Get Heroku ClearDB connection information
$cleardb_url      = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server   = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db       = substr($cleardb_url["path"],1);


define('DB_HOST',$cleardb_server);
define('DB_NAME',$cleardb_db);
define('DB_USER',$cleardb_username);
define('DB_PASS',$cleardb_password);


/**************************
  THIS WILL SET PATHS
**************************/
define('WEBSITE_NAME','shared-io');
$ROOT_PATH = $_SERVER['DOCUMENT_ROOT'];
$APP_PATH = $ROOT_PATH."/app/";
$CORE_PATH = $APP_PATH."core/";
$STORAGE_PATH = $APP_PATH."storage/";
$VIEW_PATH = $APP_PATH."view/";
