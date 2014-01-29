<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

/* 
* Online courses and related pages functions
*/

function vcn_take_online_bystate_process(&$variables) {
	$state = $variables['state'];
	$providers = $variables["providers"];
	
	// Defines the array of state characters as key and count of course as value.
	$alphabet = array('A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0, 'G' => 0, 'H' => 0, 'I' => 0,
			'J' => 0, 'K' => 0, 'L' => 0, 'M' => 0, 'N' => 0, 'O' => 0, 'P' => 0, 'Q' => 0, 'R' => 0,
			'S' => 0, 'T' => 0, 'U' => 0, 'V' => 0, 'W' => 0, 'X' => 0, 'Y' => 0, 'Z' => 0);
		
	$statealpha = 'A'; // Check to see if a user clicked on a state character, by default "A"
	if (isset($state) && strlen($state) && in_array(strtoupper($state), $alphabet)) {
		$statealpha = strtoupper($state);
	}
		
	$selected_provider_list = array();
	foreach ($providers->item as $provider) {
		if (!$provider->name) {
			break;
		}
		$letter = strtoupper(substr($provider->state, 0, 1));
		$alphabet[$letter] += 1;
	
		if($letter == $statealpha && strlen($provider->webaddrflag)){
			$statename = vcnStateUtil::getNameByAbbrev((string)$provider->state);
			$selected_provider_list[$statename][] = vcn_build_link_window_opener( $provider->webaddr, $provider->name, false);
		}
	}
	$variables["provider_list"] = $selected_provider_list;
		
	$alphabetListing = "";
	foreach ($alphabet as $key => $value) {
	
		if($alphabetListing != ""){
			$alphabetListing .= ' | ';
		}
		if ($value === 0) {
			$alphabetListing .= '<span class="courses-state-inactive">' . $key . '</span>';
		} elseif ($statealpha == $key) {
			$alphabetListing .= '<strong>' . $key . '</strong>';
		} else {
			$alphabetListing .= '<a href="?state=' . $key . '">' . $key .'</a>';
		}
	}
	$variables["alphabetListing"] = $alphabetListing;

	return true;	
}
?>