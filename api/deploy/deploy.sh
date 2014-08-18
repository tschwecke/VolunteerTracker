#!/bin/bash

if [ -z "$1" ];
then
  echo "Missing the environment.  You must pass either 'dev' or 'prod' as the first argument."
  exit -1
fi

if [ "$1" == "prod" ];
then
  DEPLOYMENT_PATH="/volunteer/api"
else
  DEPLOYMENT_PATH="/devsite1/volunteer/api"
fi

ncftpput -f deploy/ftp.config $DEPLOYMENT_PATH .htaccess
ncftpput -f deploy/ftp.config $DEPLOYMENT_PATH services.php
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH Controllers
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH Domain
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH Middleware
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH Models
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH Slim
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH Util