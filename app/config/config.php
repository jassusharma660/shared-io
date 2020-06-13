<?php

/**************************
  SET APP ENVIRONMENT
  development/production
**************************/

$_SESSION['APP_ENV'] = "development";

switch($_SESSION['APP_ENV']) {
    case "development": error_reporting(E_ALL);  break;
    case "production": error_reporting(0);    break;
    default: error_reporting(0);    break;
}


/*******************************
  THIS WILL SET DEFAULT VALUES
  for controller, method, params
********************************/
define('WEBSITE_NAME','shared-io');
//DEFAULT CONTROLLER TO user eg: website/home
define('PAGE',"home");
//DEFAULT CMD TO use eg: website/home/index
define('CMD',"");
//DEFAULT PARAMETERS TO use eg: website/home/index/name
define('PARAMS',"[]");
//File used as index.php in rowCount
define('ROOTINDEX',"index.php");

/**************************
  THIS WILL SET DATABASE
  credentials
**************************/
define('DB_HOST',"");
define('DB_NAME',"");
define('DB_USER',"");
define('DB_PASS',"");


/**************************
  THIS WILL SET PATHS
**************************/

define('APP',"app");
define('ASSETS',"assets");
define('CORE',APP."/core/");
define('VIEW',APP."/view/");
