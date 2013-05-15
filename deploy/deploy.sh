#!/bin/bash
ftp -n devsite1.aspenviewacademy.org << EOT
ascii
user schwecke pRuTe5T2
prompt
passive
cd devsite1
cd volunteer
lcd ..
mput *.*
put .htaccess

mkdir Controllers
cd Controllers
lcd Controllers
mput *.php
cd ..
lcd ..

mkdir Domain
cd Domain
lcd Domain
mput *.php
cd ..
lcd ..

mkdir Middleware
cd Middleware
lcd Middleware
mput *.php
cd ..
lcd ..

mkdir Models
cd Models
lcd Models
mput *.php
cd ..
lcd ..

mkdir scripts
cd scripts
lcd scripts
mput *.*
cd ..
lcd ..

mkdir styles
cd styles
lcd styles
mput *.*
cd ..
lcd ..

mkdir Util
cd Util
lcd Util
mput *.php
cd ..
lcd ..


