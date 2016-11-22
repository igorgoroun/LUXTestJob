#!/usr/bin/env php
<?php
/* directory path */
$path = __DIR__."/datafiles";

array_walk(scandir($path),function($filename){
    if (preg_match("/^([A-Za-z0-9]+\.txt)/",$filename)) {
        print $filename."\n";
    }
});

?>