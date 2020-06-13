<?php

/**************************
  THIS WILL SET DATABASE
  credentials
**************************/

$cleardb_url      = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server   = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db       = substr($cleardb_url["path"],1);

define('DB_SERVER', $cleardb_server);
define('DB_USERNAME', $cleardb_username);
define('DB_PASSWORD',$cleardb_password);
define('DB_NAME', $cleardb_db);



/**************************
  THIS WILL SET PATHS
**************************/
define('WEBSITE_NAME','shared-io');
define('DOCUMENT_ROOT','http://localhost/TEST_SERVER/shared-io/');
