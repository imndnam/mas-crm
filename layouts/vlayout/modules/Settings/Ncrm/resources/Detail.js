/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("Settings_Ncrm_Detail_Js",{},{
	detailViewForm : false,

	/**
	 * Function which will give the detail view form
	 * @return : jQuery element
	 */
	getForm : function() {
		if(this.detailViewForm == false) {
			this.detailViewForm = jQuery('#detailView');
		}
		return this.detailViewForm;
	},

	/**
	 * Function to register form for validation
	 */
	registerFormForValidation : function(){
		var detailViewForm = this.getForm();
		detailViewForm.validationEngine(app.validationEngineOptions);
	},

	/**
	 * Function which will handle the registrations for the elements
	 */
	registerEvents : function() {
		this.registerFormForValidation();
	}
});