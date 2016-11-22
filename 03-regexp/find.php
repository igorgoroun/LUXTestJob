#!/usr/bin/env php
<?php

/* directory path */
$path = __DIR__."/datafiles";

/* define array for files */
$files = [];

/* read directory */
$dir = dir($path);
while (false !== ($file = $dir->read())) {
    if (preg_match("/^([A-Za-z0-9]+\.txt)/",$file)) {
        $files []= $file;
    }
}

/* default sort */
sort($files);

/* print out files list */
if (count($files)>0) {
    foreach ($files as $file) {
        printf("%s\n",$file);
    }
/* or no files if empty result */
} else {
    print "No files found.\n";
}

?>