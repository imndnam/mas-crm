REM  **************************************************************************************
REM  * The contents of this file are subject to the NCRM Public License Version 1.0 *
REM  * ("License"); You may not use this file except in compliance with the License       *
REM  * The Original Code is:  NCRM Open Source                                      *
REM  * The Initial Developer of the Original Code is ncrm.                              *
REM  * Portions created by ncrm are Copyright (C) ncrm.                               *
REM  * All Rights Reserved.                                                               *
REM  *                                                                                    *
REM  **************************************************************************************  
@echo off
set SCH_INSTALL=%1
FOR %%X in (%SCH_INSTALL%) DO SET SCH_INSTALL=%%~sX
schtasks /create /tn "ncrmCRM Notification Scheduler" /tr %SCH_INSTALL%\apache\htdocs\ncrmCRM\cron\intimateTaskStatus.bat /sc daily /st 11:00:00 /RU SYSTEM
schtasks /create /tn "ncrmCRM Email Reminder" /tr %SCH_INSTALL%\apache\htdocs\ncrmCRM\modules\Calendar\SendReminder.bat /sc minute /mo 1 /RU SYSTEM
