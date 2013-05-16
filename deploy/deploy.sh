#!/bin/bash

ncftpput -f deploy/ftp.config /devsite1/volunteer .htaccess
ncftpput -f deploy/ftp.config /devsite1/volunteer services.php
ncftpput -f deploy/ftp.config /devsite1/volunteer Volunteer.html
ncftpput -f deploy/ftp.config /devsite1/volunteer config.ini
ncftpput -R -f deploy/ftp.config /devsite1/volunteer Controllers
ncftpput -R -f deploy/ftp.config /devsite1/volunteer Domain
ncftpput -R -f deploy/ftp.config /devsite1/volunteer Middleware
ncftpput -R -f deploy/ftp.config /devsite1/volunteer Models
ncftpput -R -f deploy/ftp.config /devsite1/volunteer scripts
ncftpput -R -f deploy/ftp.config /devsite1/volunteer Slim
ncftpput -R -f deploy/ftp.config /devsite1/volunteer styles
ncftpput -R -f deploy/ftp.config /devsite1/volunteer Util

