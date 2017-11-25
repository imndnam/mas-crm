<?php
/*+***********************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 *************************************************************************************/

class Ncrm_JavaScript extends Ncrm_Viewer {

	/**
	 * Function to get the path of a given style sheet or default style sheet
	 * @param <String> $fileName
	 * @return <string / Boolean> - file path , false if not exists
	 */
	public static function getFilePath($fileName=''){
		if(empty($fileName)) {
			return false;
		}
		$filePath =  self::getBaseJavaScriptPath() . '/' . $fileName;
		$completeFilePath = Ncrm_Loader::resolveNameToPath('~'.$filePath);

		if(file_exists($completeFilePath)){
			return $filePath;
		}
		return false;
	}

	/**
	 * Function to get the Base Theme Path, until theme folder not selected theme folder
	 * @return <string> - theme folder
	 */
	public static function getBaseJavaScriptPath(){
		return 'layouts'. '/' . self::getLayoutName();
	}
}