#!/bin/bash

if [ -z "$1" ];
then
  echo "Missing the environment.  You must pass either 'dev' or 'prod' as the first argument."
  exit -1
fi

if [ "$1" == "prod" ];
then
  DEPLOYMENT_PATH="/"
else
  DEPLOYMENT_PATH="/devsite1/volunteer"
fi


ncftpput -f deploy/ftp.config $DEPLOYMENT_PATH Volunteer.html
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH admin
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH kiosk
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH scripts
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH styles

