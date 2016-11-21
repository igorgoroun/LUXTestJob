#!/usr/bin/env php
<?php
$class_path = __DIR__."/classes/";
require($class_path."Db.php");
require($class_path."Init.php");

$mysql_params = [
    'user'=>'',
    'pass'=>'',
    'host'=>'',
    'base'=>'luxtest'
];

$arguments = ['create','fill','get'];

if (isset($argv[1])) {
    $a = $argv[1];
} else $a=false;

if (!$a || !in_array($a,$arguments)) {
    print "Please define one of ['create','fill','get'] arguments to start script\n";
    exit();
}

$db = new Db($mysql_params);
$init = new Init($db);

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


$db->mysqlDisconnect();

?>