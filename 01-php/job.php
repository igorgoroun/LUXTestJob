#!/usr/bin/env php
<?php
/* get classes */
$class_path = __DIR__."/classes/";
require($class_path."Db.php");
require($class_path."Init.php");

/* mysql connection parameters */
$mysql_params = [
    'user'=>'',
    'pass'=>'',
    'host'=>'',
    'base'=>'luxtest'
];

/* possible arguments for this script */
$arguments = ['create','fill','get'];

/* check argument */
if (isset($argv[1])) {
    $a = $argv[1];
} else $a=false;
if (!$a || !in_array($a,$arguments)) {
    print "Please define one of ['create','fill','get'] arguments to start script\n";
    exit();
}

/* db object */
$db = new Db($mysql_params);

/* Testjob Init object */
$init = new Init($db);

/* run methods */
switch($a) {
    case 'create':
        $init->newtable();
        break;
    case 'fill':
        $init->filltable();
        break;
    default:
        $init->get();
        break;
}

/* bad idea */
$db->mysqlDisconnect();

?>