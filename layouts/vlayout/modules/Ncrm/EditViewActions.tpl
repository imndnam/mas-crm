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

{strip}
       <div class="row-fluid edit-bottom-toolbar">
            <div class="pull-right">
				<button class="btn btn-success" type="submit"><strong>{vtranslate('LBL_SAVE', $MODULE)}</strong></button>
				<a class="{*cancelLink*}" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			</div>
			<div class="clearfix"></div>
        </div>
		<br>
    </form>
</div>
{/strip}