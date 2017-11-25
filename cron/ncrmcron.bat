@echo OFF
REM #*********************************************************************************
REM # The contents of this file are subject to the NCRM Public License Version 1.0
REM # ("License"); You may not use this file except in compliance with the License
REM # The Original Code is:  NCRM Open Source
REM # The Initial Developer of the Original Code is ncrm.
REM # Portions created by ncrm are Copyright (C) ncrm.
REM # All Rights Reserved.
REM #
REM # ********************************************************************************

set NCRMCRM_ROOTDIR="C:\Program Files\ncrmcrm5\apache\htdocs\ncrmCRM"
set PHP_EXE="C:\Program Files\ncrmcrm5\php\php.exe"

cd /D %NCRMCRM_ROOTDIR%

%PHP_EXE% -f ncrmcron.php
