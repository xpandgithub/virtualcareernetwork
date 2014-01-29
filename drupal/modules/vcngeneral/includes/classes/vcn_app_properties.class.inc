<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
/**
* vcnAppProperties()
* This class is used to get the VCN application properties
*/
class vcnAppProperties {
	
	private $resource;
	
	public function  __construct() {
	
	}
	
	public function getVCNSupportEmail() {
	
		return $this->getAppPropertyValue(vcnAppPropertyTypes::VcnSupportEmail);
	
	}
	
	public function getCurrentDrupalVersion() {
			
		return $this->getAppPropertyValue(vcnAppPropertyTypes::CurrentDrupalVersion);
	
	}
	
	public function getCurrentMoodleVersion() {
			
		return $this->getAppPropertyValue(vcnAppPropertyTypes::CurrentMoodleVersion);
	
	}
	
	public function getProviderPortalEmail() {
			
		return $this->getAppPropertyValue(vcnAppPropertyTypes::ProviderPortalEmail);
	
	}
	
	public function getProviderContactEmail() {
			
		return $this->getAppPropertyValue(vcnAppPropertyTypes::ProviderContactEmail);
	
	}
	
	public function getOneStopWebserviceKey() {
			
		return $this->getAppPropertyValue(vcnAppPropertyTypes::OneStopWebserviceKey);
	
	}
	
	public function getDEAJobSearchTermBlackList() {
			
		return $this->getAppPropertyValue(vcnAppPropertyTypes::DeaJobSearchTermBlackList);
	
	}
	
	public function getAppPropertyValue($appPropertyId) {
		
		$value = '';
	
		$connection = mysql_connect($GLOBALS['vcn_config_db_server_name'], $GLOBALS['vcn_config_db_username'], $GLOBALS['vcn_config_db_password']) or die("Error making database connection: ".mysql_error());
		$db = mysql_select_db($GLOBALS['vcn_config_db_name'], $connection) or die("Error selecting database: ".mysql_error());
		
		// if we already have a resource we've used before then use it else create it
		if (!$this->resource) {
			try {
				
				$query = " SELECT * FROM vcn_app_properties ";	
				$this->resource = mysql_query($query);
				
			} catch (Exception $e) {
				// dont do anything
			}
		}
		
		// make sure we definitely have a resource before trying to use it
		if ($this->resource) {
			
			mysql_data_seek($this->resource, 0);
			
			while ($row = mysql_fetch_assoc($this->resource)) {
				if ($row['ID'] == $appPropertyId) {
					$value = $row['VALUE'];
					break;
				}
			}			
		}
		
		return $value;
		
	}
	
}

/**
* vcnAppPropertyTypes()
* This class is used as a enumeration 
*/
class vcnAppPropertyTypes {

	const OneStopWebserviceKey = 1;
	const ProviderPortalEmail = 2;
	const ProviderContactEmail = 3;
	const CurrentDrupalVersion = 4;
	const CurrentMoodleVersion = 5;
	const VcnSupportEmail = 6;
	const DeaJobSearchTermBlackList = 7;
	
}
?>
