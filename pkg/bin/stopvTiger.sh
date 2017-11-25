#*********************************************************************************
# The contents of this file are subject to the NCRM Public License Version 1.0
# ("License"); You may not use this file except in compliance with the License
# The Original Code is:  NCRM Open Source
# The Initial Developer of the Original Code is ncrm.
# Portions created by ncrm are Copyright (C) ncrm.
# All Rights Reserved.
#
# ********************************************************************************

cd ..
export NCRM_HOME=`pwd`

mysql_dir='MYSQLINSTALLDIR'
mysql_username='MYSQLUSERNAME'
mysql_password='MYSQLPASSWORD'
mysql_port=MYSQLPORT
mysql_socket='MYSQLSOCKET'
mysql_bundled='MYSQLBUNDLEDSTATUS'
apache_dir='APACHEINSTALLDIR'
apache_bin='APACHEBIN'
apache_conf='APACHECONF'
apache_port='APACHEPORT'
apache_bundled='APACHEBUNDLED'

if [ $apache_bundled == 'true' ];then
	cd $apache_bin
	echo "Shutting down apache !"
	./httpd -k stop
fi


if [ $mysql_bundled == 'true' ]; then
	MYSQL_HOME=$mysql_dir
	export MYSQL_HOME
	cd $MYSQL_HOME
	echo `pwd`
	echo "Shutting down the mysql server"
	./bin/mysqladmin --user=$mysql_username --password=$mysql_password --port=$mysql_port --socket=$mysql_socket shutdown
	echo "MySQL shutdown"
fi

cd $NCRM_HOME
