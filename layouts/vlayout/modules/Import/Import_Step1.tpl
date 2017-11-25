{*<!--
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *
 ********************************************************************************/
-->*}
<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
		<td><strong>{'LBL_IMPORT_STEP_1'|@vtranslate:$MODULE}:</strong></td>
		<td class="big">{'LBL_IMPORT_STEP_1_DESCRIPTION'|@vtranslate:$MODULE}</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td data-import-upload-size="{$IMPORT_UPLOAD_SIZE}" data-import-upload-size-mb="{$IMPORT_UPLOAD_SIZE_MB}">
			<input type="hidden" name="type" value="csv" />
			<input type="hidden" name="is_scheduled" value="1" />
			<input type="file" name="import_file" id="import_file" onchange="ImportJs.checkFileType()"/>
			<!-- input type="hidden" name="userfile_hidden" value=""/ -->
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>{'LBL_IMPORT_SUPPORTED_FILE_TYPES'|@vtranslate:$MODULE}</td>
	</tr>
</table>