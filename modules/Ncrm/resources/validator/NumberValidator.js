/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
Ncrm_BaseValidator_Js("Ncrm_NumberValidator_Js",{},{
	error: "",
	validate: function(){
		var field = this.fieldInfo;
		if(jQuery(field).attr('id') == "probability"){
			if (isNaN(field.val())) {
				// this allows the use of i18 for the error msgs
				this.getOnlyNumbersError;
			}else if(field.val() > 100){
				this.getProbabilityNumberError;
			}
		}
		if (isNaN(field.val())) {
			 // this allows the use of i18 for the error msgs
			this.getOnlyNumbersError;
		}
	},

	getOnlyNumbersError: function(){
		this.error = "please enter only numbers";
		return this.error;
	},

	getProbabilityNumberError: function(){
		this.error = "please enter only numbers less than 100 as field value is in percentage";
		return this.error;
	}
})