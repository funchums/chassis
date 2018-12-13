<?php
/*Composer Autoload*/
require __DIR__ . '/vendor/autoload.php';

/*Define System Variables*/
define('FS','/');
define('BS','\\');
define('EXT', '.php');
define('LINKED',TRUE);

/*Define Main Application Variables*/
define('BASEAPP','main');
define('BASECLS','main');
define('BASEMET','index');

/*check request if set or not and sanitize*/
$request = isset($_REQUEST["request"]) ? trim(strip_tags($_REQUEST['request'])) : NULL;

/* construct all components in the bootstrap */
$overture = new Conductor\overture($request);

/* add your restful routes here */
$overture->_routes("GET", "main/main/index");
$overture->_routes("GET", "main/main/persons");
$overture->_routes("POST", "main/main/checkhash");
$overture->_routes("GET", "main/main/tstchain");
$overture->_routes("GET", "main/main/tstarr");
$overture->_routes("GET", "main/main/index2");
$overture->_routes("GET", "main/main/xert");

/* run the framework 
 * to disable REST remove "_isRest"
 */
$overture->play("_isRest");
//$overture->play();