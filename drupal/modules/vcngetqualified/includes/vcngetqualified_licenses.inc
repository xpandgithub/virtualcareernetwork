<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_getqualified_licenses_callback() {
	return theme('vcn_getqualified_licenses');
}

function vcn_getqualified_get_licenses_data() {
	
	$onetcode = vcnuser_get_onetcode();
	$zipcode = vcnuser_get_zip();
	$state = vcnuser_get_st();
	
	if ($zipcode) {
		$location = $zipcode;
	} elseif (isset($state)) {
		$location = $state;
	} else {
		$location = NULL;
	}
	
	if ($onetcode && $location) {    
		$stfips = vcnStateUtil::getFipsByUnknown($location);
		$state_abbrev = vcnStateUtil::getAbbrevByFips($stfips);
    
		$licenses = vcnLicenseDataService::getLicensesByOnetcode($onetcode, $location);
		$licensesCount = $licenses->data->licenses ? count($licenses->data->licenses) : 0;

		$licenses_data = array(
											'licenses_count' => $licensesCount, 
											'licenses_data' => $licenses, 
											'params' => array('onetcode' => $onetcode, 'location' => array('zip' => $zipcode, 'state' => $state_abbrev, 'stfips' => $stfips)),
											'bad_data' => FALSE
										);
	} else {
		$is_onetcode_set = TRUE;
		$is_location_set = TRUE;
		if (!$onetcode) {
			$is_onetcode_set = FALSE;
		}
		if (!$location) {
			$is_location_set = FALSE;
		}
		$licenses_data = array(
											'bad_data' => TRUE, 
											'bad_data_additional_info' => array('is_onetcode_set' => $is_onetcode_set, 'is_location_set' => $is_location_set)
										);
	}
	
	return $licenses_data;

} 