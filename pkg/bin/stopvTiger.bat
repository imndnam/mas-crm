@echo off
cd ..
set mysql_dir=MYSQLINSTALLDIR
set mysql_username=MYSQLUSERNAME
set mysql_password=MYSQLPASSWORD
set mysql_port=MYSQLPORT
set mysql_bundled=MYSQLBUNDLEDSTATUS
set apache_dir=APACHEINSTALLDIR
set apache_bin=APACHEBIN
set apache_conf=APACHECONF
set apache_port=APACHEPORT
set apache_bundled=APACHEBUNDLED
set apache_service=APACHESERVICE

set NCRM_HOME=%cd%


if %apache_bundled% == true goto StopApacheCheck
goto StopMySQL

:StopApacheCheck
if %apache_service% == true goto StopApacheService
cd /d %apache_dir%
rem shut down apache
echo ""
echo "stopping ncrmcrm apache"
echo ""
bin\ShutdownApache logs\httpd.pid
goto StopMySQL

:StopApacheService
cd /d %apache_dir%
rem shut down apache
echo ""
echo "stopping ncrmcrm504 apache service"
echo ""
bin\apache -n ncrmcrm504 -k stop
echo ""
echo "uninstalling ncrmcrm504 apache service"
echo ""
bin\apache -k uninstall -n ncrmcrm504
rem .\bin\ShutdownApache.exe logs\httpd.pid
goto StopMySQL


:StopMySQL
if %mysql_bundled% == true (
	rem cd /d %NCRM_HOME%\mysql\bin
	rem  shutdown mysql 
	cd /d %mysql_dir%\bin
	mysqladmin --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% shutdown
	echo ""
	echo "NCRM  MySQL Sever is shut down"
	echo ""
	cd /d %NCRM_HOME%\bin
)
