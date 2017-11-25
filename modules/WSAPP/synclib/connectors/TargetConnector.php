<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'modules/WSAPP/synclib/connectors/BaseConnector.php';

abstract class WSAPP_TargetConnector extends WSAPP_BaseConnector{

	public function transformToTargetRecord($sourceRecords){
		$destinationRecordList = array();
		foreach($sourceRecords as $record){
			$destinationRecord = clone $record;

			$destinationRecord->setId($record->getOtherAppId());
			$destinationRecord->setOtherAppId($record->getId());

			$destinationRecord->setModifiedTime($record->getOtherAppModifiedTime());
			$destinationRecord->setOtherAppModifiedTIme($record->getModifiedTime());
			$destinationRecordList[] = $destinationRecord;
		}
		return $destinationRecordList;
	}
	public function transformToSourceRecord($targetRecords){
		$sourceRcordList = array();
		foreach($targetRecords as $record){
			$sourceRecord = clone $record;

			$sourceRecord->setId($record->getOtherAppId())
						 ->setOtherAppId($record->getId())
						 ->setModifiedTime($record->getOtherAppModifiedTime())
						 ->setOtherAppModifiedTIme($record->getModifiedTime());

			$sourceRcordList[] = $sourceRecord;
		}
		return $sourceRcordList;
	}
}
?>
