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

<button type="submit" name="next"  class="btn btn-success"
		onclick="return ImportJs.uploadAndParse();"><strong>{'LBL_NEXT_BUTTON_LABEL'|@vtranslate:$MODULE}</strong></button>
&nbsp;&nbsp;
<a name="cancel" class="cursorPointer cancelLink" value="{'LBL_CANCEL'|@vtranslate:$MODULE}" onclick="location.href='index.php?module={$FOR_MODULE}&view=List'">
		{'LBL_CANCEL'|@vtranslate:$MODULE}
</a>