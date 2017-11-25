/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

var Ncrm_BaseList_Js = {
	/**
	 * Function to get the parameters for paging of records
	 * @return : string - module name
	 */
	getPageRecords : function(params){
		var aDeferred = jQuery.Deferred();
		
		if(typeof params == 'undefined') {
			params = {};
		}

		if(typeof params.module == 'undefined') {
			params.module = app.getModuleName();
		}

		if(typeof params.view == 'undefined') {
			//Default we will take list ajax
			params.view = 'ListAjax';
		}

		if(typeof params.page == 'undefined') {
			params.page = Ncrm_BaseList_Js.getCurrentPageNum();
		}

		AppConnector.request(params).then(
			function(data){
				aDeferred.resolve(data);
			},

			function(textStatus, errorThrown){
				aDeferred.reject(textStatus, errorThrown);
			}
		);
		return aDeferred.promise();
	},
	
	getCurrentPageNum : function() {
		return jQuery('#pageNumber').val();
	}
}
