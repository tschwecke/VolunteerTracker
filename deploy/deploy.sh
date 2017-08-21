#!/bin/bash


ncftpput -f deploy/ftp.config $DEPLOYMENT_PATH Volunteer.html
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH admin
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH kiosk
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH scripts
ncftpput -R -f deploy/ftp.config $DEPLOYMENT_PATH styles

