<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Ncrm Summary View Record Structure Model
 */
class Ncrm_SummaryRecordStructure_Model extends Ncrm_DetailRecordStructure_Model {

	/**
	 * Function to get the values in stuctured format
	 * @return <array> - values in structure array('block'=>array(fieldinfo));
	 */
	public function getStructure() {
		$summaryFieldsList = $this->getModule()->getSummaryViewFieldsList();
        $recordModel = $this->getRecord();
        $blockSeqSortSummaryFields = array();
			if ($summaryFieldsList) {
			foreach ($summaryFieldsList as $fieldName => $fieldModel) {
                if($fieldModel->isViewableInDetailView()) {
                    $fieldModel->set('fieldvalue', $recordModel->get($fieldName));
                    $blockSequence = $fieldModel->block->sequence;
                    $blockSeqSortSummaryFields[$blockSequence]['SUMMARY_FIELDS'][$fieldName] = $fieldModel;
						}
					}
				}
        $summaryFieldModelsList = array();
        ksort($blockSeqSortSummaryFields);
        foreach($blockSeqSortSummaryFields as $blockSequence => $summaryFields){
            $summaryFieldModelsList = array_merge_recursive($summaryFieldModelsList , $summaryFields);
        }
		return $summaryFieldModelsList;
	}
}