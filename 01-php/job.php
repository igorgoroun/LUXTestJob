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
$arguments = ['create','create-force','fill','get'];

/* check argument */
if (isset($argv[1])) {
    $a = $argv[1];
} else $a=false;
if (!$a || !in_array($a,$arguments)) {
    print "Please define one of ['create','create-force','fill','get'] arguments to start script\n";
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
    case 'create-force':
        $init->newtable(true);
        break;
    case 'fill':
        if (isset($argv[2]) && is_numeric($argv[2])) {
            $lines=$argv[2];
            $init->filltable($lines);
        } else {
            $init->filltable();
        }
        break;
    default:
        $init->get(true);
        break;
}

/* bad idea */
$db->mysqlDisconnect();

?>