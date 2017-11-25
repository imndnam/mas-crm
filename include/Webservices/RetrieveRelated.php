<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

include_once 'include/Webservices/QueryRelated.php';

function vtws_retrieve_related($id, $relatedType, $relatedLabel, $user) {
    $query = 'SELECT * FROM ' . $relatedType;
    return vtws_query_related($query, $id, $relatedLabel, $user);
}
