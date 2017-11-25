<?php
/*+*******************************************************************************
 * The contents of this file are subject to the NCRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  NCRM Open Source
 * The Initial Developer of the Original Code is ncrm.
 * Portions created by ncrm are Copyright (C) ncrm.
 * All Rights Reserved.
 ********************************************************************************/
if(isset($_REQUEST['service']))
{
	if($_REQUEST['service'] == "customerportal")
	{
		include("soap/customerportal.php");
	}
	elseif($_REQUEST['service'] == "firefox")
	{
		include("soap/firefoxtoolbar.php");
	}
	elseif($_REQUEST['service'] == "wordplugin")
	{
		include("soap/wordplugin.php");
	}
	elseif($_REQUEST['service'] == "thunderbird")
	{
		include("soap/thunderbirdplugin.php");
	}
	else
	{
		echo "No Service Configured for ". strip_tags($_REQUEST[service]);
	}
}
else
{
	echo "<h1>ncrmCRM Soap Services</h1>";
	echo "<li>ncrmCRM Outlook Plugin EndPoint URL -- Click <a href='ncrmservice.php?service=outlook'>here</a></li>";
	echo "<li>ncrmCRM Word Plugin EndPoint URL -- Click <a href='ncrmservice.php?service=wordplugin'>here</a></li>";
	echo "<li>ncrmCRM ThunderBird Extenstion EndPoint URL -- Click <a href='ncrmservice.php?service=thunderbird'>here</a></li>";
	echo "<li>ncrmCRM Customer Portal EndPoint URL -- Click <a href='ncrmservice.php?service=customerportal'>here</a></li>";
	echo "<li>ncrmCRM WebForm EndPoint URL -- Click <a href='ncrmservice.php?service=webforms'>here</a></li>";
	echo "<li>ncrmCRM FireFox Extension EndPoint URL -- Click <a href='ncrmservice.php?service=firefox'>here</a></li>";
}


?>
