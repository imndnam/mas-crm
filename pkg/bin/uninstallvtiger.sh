#*********************************************************************************
# The contents of this file are subject to the NCRM Public License Version 1.0
# ("License"); You may not use this file except in compliance with the License
# The Original Code is:  NCRM Open Source
# The Initial Developer of the Original Code is ncrm.
# Portions created by ncrm are Copyright (C) ncrm.
# All Rights Reserved.
#
# ********************************************************************************

INS_DIR="../.."
WRKDIR=`pwd`
PREV_DIR=".."

APACHE_STATUS=`cat startnCrm.sh | grep ^apache_bundled | cut -d "=" -f2 | cut -d "'" -f2`
cd ${INS_DIR}
cd ${PREV_DIR}
if [ ${APACHE_STATUS} == "false" ]
then
	diff conf/httpd.conf conf/ncrm_conf/ncrmcrm-5.4.0/httpd.conf > /dev/null;
	if [ $? -eq 0 ]
	then
		cp conf/ncrmCRMBackup/ncrmcrm-5.4.0/httpd.ncrm.crm.conf conf/httpd.conf
		echo "The httpd.conf file successfully reverted"
	else
		echo "The httpd.conf file under apache/conf has been edited since installation. Hence the uninstallation will not revert the httpd.conf file. The original httpd.conf file is present in <apache home>/conf/ncrmCRMBackup/ncrmcrm-5.4.0/httpd.ncrm.crm.conf. Kindly revert the same manually"
	fi

	diff modules/libphp4.so modules/ncrm_modules/ncrmcrm-5.4.0/libphp4.so > /dev/null;
	if [ $? -eq 0 ]
        then
		cp modules/ncrmCRMBackup/ncrmcrm-5.4.0/libphp4.ncrm.crm.so modules/libphp4.so
		echo "The libphp4.so file successfully reverted"
	else
		echo "The libphp4.so file under apache/modules has been edited since installation. Hence the uninstallation will not revert the libphp4.so file. The original libphp4.so file is present in <apache home>/modules/ncrmCRMBackup/ncrmcrm-5.4.0/libphp4.ncrm.crm.so. Kindly revert the same manually"
	fi

	cd -

	if [ -d $PWD/ncrmcrm-5.4.0 ]; then
		echo "Uninstalling ncrmCRM from the system..."
		rm -rf ../conf/ncrm_conf/ncrmcrm-5.4.0
		rm -rf ../modules/ncrm_modules/ncrmcrm-5.4.0
		rm -rf ncrmcrm-5.4.0
		echo "Uninstallation of ncrmCRM completed"
		cd ${HOME}
	fi

else
	cd -
	if [ -d $PWD/ncrmcrm-5.4.0 ]; then
                echo "Uninstalling ncrmCRM from the system..."
		rm -rf ../conf/ncrm_conf/ncrmcrm-5.4.0
                rm -rf ../modules/ncrm_modules/ncrmcrm-5.4.0
                rm -rf ncrmcrm-5.4.0
                echo "Uninstallation of ncrmCRM completed"
                cd ${HOME}
        fi
fi
