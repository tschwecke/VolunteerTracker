#!/bin/bash
ftp -n -v devsite1.aspenviewacademy.org << EOT
ascii
user schwecke pRuTe5T2
prompt
passive
cd devsite1
cd volunteer
lcd ..
mput *.*

mkdir -p scripts
cd scripts
lcd scripts
mput *.*
cd ..
lcd ..
