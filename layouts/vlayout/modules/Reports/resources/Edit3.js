/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
Reports_Edit_Js("Reports_Edit3_Js",{},{
	
	step3Container : false,
	
	advanceFilterInstance : false,
	
	init : function() {
		this.initialize();
	},
	/**
	 * Function to get the container which holds all the report step3 elements
	 * @return jQuery object
	 */
	getContainer : function() {
		return this.step3Container;
	},

	/**
	 * Function to set the report step3 container
	 * @params : element - which represents the report step3 container
	 * @return : current instance
	 */
	setContainer : function(element) {
		this.step3Container = element;
		return this;
	},
	
	/**
	 * Function  to intialize the reports step3
	 */
	initialize : function(container) {
		if(typeof container == 'undefined') {
			container = jQuery('#report_step3');
		}
		
		if(container.is('#report_step3')) {
			this.setContainer(container);
		}else{
			this.setContainer(jQuery('#report_step3'));
		}
	},
	
	calculateValues : function(){
		//handled advanced filters saved values.
		var advfilterlist = this.advanceFilterInstance.getValues();
		jQuery('#advanced_filter').val(JSON.stringify(advfilterlist));
	},
	
	registerSubmitEvent : function(){
		var thisInstance = this;
		var form = this.getContainer();
		form.submit(function(e){
			thisInstance.calculateValues();
		});
	},
	
	registerEvents : function(){
		var container = this.getContainer();
		app.changeSelectElementView(container);
		this.advanceFilterInstance = Ncrm_AdvanceFilter_Js.getInstance(jQuery('.filterContainer',container));
		this.registerSubmitEvent();
		container.validationEngine();
	}
});
	



