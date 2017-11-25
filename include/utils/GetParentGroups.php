<?php
/*********************************************************************************
** The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
*
 ********************************************************************************/

/** Class to retreive all the Parent Groups of the specified Group
 *
 */
class GetParentGroups { 

	var $parent_groups=Array();

	/** to get all the parent ncrm_groups of the specified group
	 * @params $groupId --> Group Id :: Type Integer
         * @returns updates the parent group in the varibale $parent_groups of the class
         */
	function getAllParentGroups($groupid)
	{
		global $adb,$log;
		$log->debug("Entering getAllParentGroups(".$groupid.") method...");
		$query="select groupid from ncrm_group2grouprel where containsgroupid=?";
		$result = $adb->pquery($query, array($groupid));
		$num_rows=$adb->num_rows($result);
		if($num_rows > 0)
		{
			for($i=0;$i<$num_rows;$i++)
			{
				$group_id=$adb->query_result($result,$i,'groupid');
				if(! in_array($group_id,$this->parent_groups))
				{
					$this->parent_groups[]=$group_id;
					$this->getAllParentGroups($group_id);
				}
			}
		}
		$log->debug("Exiting getAllParentGroups method...");
	}
}

?>
