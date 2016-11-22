#!/bin/sh

ls -1 datafiles/ | egrep "^([A-Za-z0-9]+\.txt)$" | sort

