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
    <div class="modelContainer" style='min-width:350px;'>
        <div class="modal-header contentsBackground">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>{vtranslate('LBL_ADD_NEW_FOLDER', $MODULE)}</h3>
        </div>
        <form class="form-horizontal" id="addDocumentsFolder" method="post" action="index.php">
            <input type="hidden" name="module" value="{$MODULE}" />
            <input type="hidden" name="action" value="Folder" />
            <input type="hidden" name="mode" value="save" />
            <div class="modal-body">
                <div class="row-fluid">
                    <div class="control-group">
                        <label class="control-label">
                            <span class="redColor">*</span>
                            {vtranslate('LBL_FOLDER_NAME', $MODULE)}
                        </label>
                        <div class="controls">
                            <input class="span3" data-validator='{Zend_Json::encode([['name'=>'FolderName']])}' data-validation-engine="validate[required, funcCall[Ncrm_Base_Validator_Js.invokeValidation]]" id="documentsFolderName" name="foldername" class="span12" type="text" value=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            {vtranslate('LBL_FOLDER_DESCRIPTION', $MODULE)}
                        </label>
                        <div class="controls">
                            <textarea rows="1" class="input-xxlarge fieldValue span3" name="folderdesc" id="description"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            {include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
        </form>
    </div>
{/strip}