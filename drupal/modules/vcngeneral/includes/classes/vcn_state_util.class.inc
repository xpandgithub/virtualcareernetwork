<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

abstract class vcnStateUtil {

	private static $_statesArray = array(
								array("fips" => "00", "abbrev" => "US", "name" => "United States"),
								array("fips" => "01", "abbrev" => "AL", "name" => "Alabama"),
								array("fips" => "02", "abbrev" => "AK", "name" => "Alaska"),
								array("fips" => "04", "abbrev" => "AZ", "name" => "Arizona"),
								array("fips" => "05", "abbrev" => "AR", "name" => "Arkansas"),
								array("fips" => "06", "abbrev" => "CA", "name" => "California"),
								array("fips" => "08", "abbrev" => "CO", "name" => "Colorado"),
								array("fips" => "09", "abbrev" => "CT", "name" => "Connecticut"),
								array("fips" => "10", "abbrev" => "DE", "name" => "Delaware"),
								array("fips" => "11", "abbrev" => "DC", "name" => "District of Columbia"),
								array("fips" => "12", "abbrev" => "FL", "name" => "Florida"),
								array("fips" => "13", "abbrev" => "GA", "name" => "Georgia"),
								array("fips" => "15", "abbrev" => "HI", "name" => "Hawaii"),
								array("fips" => "16", "abbrev" => "ID", "name" => "Idaho"),
								array("fips" => "17", "abbrev" => "IL", "name" => "Illinois"),
								array("fips" => "18", "abbrev" => "IN", "name" => "Indiana"),
								array("fips" => "19", "abbrev" => "IA", "name" => "Iowa"),
								array("fips" => "20", "abbrev" => "KS", "name" => "Kansas"),
								array("fips" => "21", "abbrev" => "KY", "name" => "Kentucky"),
								array("fips" => "22", "abbrev" => "LA", "name" => "Louisiana"),
								array("fips" => "23", "abbrev" => "ME", "name" => "Maine"),
								array("fips" => "24", "abbrev" => "MD", "name" => "Maryland"),
								array("fips" => "25", "abbrev" => "MA", "name" => "Massachusetts"),
								array("fips" => "26", "abbrev" => "MI", "name" => "Michigan"),
								array("fips" => "27", "abbrev" => "MN", "name" => "Minnesota"),
								array("fips" => "28", "abbrev" => "MS", "name" => "Mississippi"),
								array("fips" => "29", "abbrev" => "MO", "name" => "Missouri"),
								array("fips" => "30", "abbrev" => "MT", "name" => "Montana"),
								array("fips" => "31", "abbrev" => "NE", "name" => "Nebraska"),
								array("fips" => "32", "abbrev" => "NV", "name" => "Nevada"),
								array("fips" => "33", "abbrev" => "NH", "name" => "New Hampshire"),
								array("fips" => "34", "abbrev" => "NJ", "name" => "New Jersey"),
								array("fips" => "35", "abbrev" => "NM", "name" => "New Mexico"),
								array("fips" => "36", "abbrev" => "NY", "name" => "New York"),
								array("fips" => "37", "abbrev" => "NC", "name" => "North Carolina"),
								array("fips" => "38", "abbrev" => "ND", "name" => "North Dakota"),
								array("fips" => "39", "abbrev" => "OH", "name" => "Ohio"),
								array("fips" => "40", "abbrev" => "OK", "name" => "Oklahoma"),
								array("fips" => "41", "abbrev" => "OR", "name" => "Oregon"),
								array("fips" => "42", "abbrev" => "PA", "name" => "Pennsylvania"),
								array("fips" => "44", "abbrev" => "RI", "name" => "Rhode Island"),
								array("fips" => "45", "abbrev" => "SC", "name" => "South Carolina"),
								array("fips" => "46", "abbrev" => "SD", "name" => "South Dakota"),
								array("fips" => "47", "abbrev" => "TN", "name" => "Tennessee"),
								array("fips" => "48", "abbrev" => "TX", "name" => "Texas"),
								array("fips" => "49", "abbrev" => "UT", "name" => "Utah"),
								array("fips" => "50", "abbrev" => "VT", "name" => "Vermont"),
								array("fips" => "51", "abbrev" => "VA", "name" => "Virginia"),
								array("fips" => "53", "abbrev" => "WA", "name" => "Washington"),
								array("fips" => "54", "abbrev" => "WV", "name" => "West Virginia"),
								array("fips" => "55", "abbrev" => "WI", "name" => "Wisconsin"),
								array("fips" => "56", "abbrev" => "WY", "name" => "Wyoming"),
								array("fips" => "60", "abbrev" => "AS", "name" => "American Samoa"),
								array("fips" => "64", "abbrev" => "FM", "name" => "Federated States of Micronesia"),
								array("fips" => "66", "abbrev" => "GU", "name" => "Guam"),
								array("fips" => "68", "abbrev" => "MH", "name" => "Marshall Islands"),
								array("fips" => "69", "abbrev" => "MP", "name" => "Northern Mariana Islands"),
								array("fips" => "70", "abbrev" => "PW", "name" => "Palau"),
								array("fips" => "72", "abbrev" => "PR", "name" => "Puerto Rico"),
								array("fips" => "74", "abbrev" => "UM", "name" => "U.S. Minor Outlying Islands"),
								array("fips" => "78", "abbrev" => "VI", "name" => "U.S. Virgin Islands"),
								array("fips" => "99", "abbrev" => "99", "name" => "Unknown"),
								);
	
	private static function getStateValue($input, $inputType, $outputType) {
				
		$output = '';

		foreach (self::$_statesArray as $stateArr) {
			if (strtoupper($stateArr[$inputType]) == strtoupper($input)) {
				$output = $stateArr[$outputType];
				break;
			}
		}
		
		return $output;
		
	}
	
	public static function getNameByFips($fips) {
		
		$name = self::getStateValue($fips, vcnState::TYPE_FIPS, vcnState::TYPE_NAME);
		
		return $name;
		
	}
	
	public static function getAbbrevByFips($fips) {
		
		$abbrev = self::getStateValue($fips, vcnState::TYPE_FIPS, vcnState::TYPE_ABBREV);
		
		return $abbrev;
		
	}
	
	public static function getFipsByName($name) {
		
		$fips = self::getStateValue($name, vcnState::TYPE_NAME, vcnState::TYPE_FIPS);
		
		return $fips;
		
	}
	
	public static function getAbbrevByName($name) {
		
		$abbrev = self::getStateValue($name, vcnState::TYPE_NAME, vcnState::TYPE_ABBREV);
		
		return $abbrev;
		
	}
	
	public static function getFipsByAbbrev($abbrev) {
		
		$fips = self::getStateValue($abbrev, vcnState::TYPE_ABBREV, vcnState::TYPE_FIPS);
		
		return $fips;
		
	}
	
	public static function getNameByAbbrev($abbrev) {
		
		$name = self::getStateValue($abbrev, vcnState::TYPE_ABBREV, vcnState::TYPE_NAME);
		
		return $name;
		
	}
	
	public static function getFipsByUnknown($location) {
	
		$fips = '';
		
		if (strlen($location) > 2) {
			// zip
			$abbrev = self::getStateAbbrevFromZip($location);
			$fips = self::getFipsByAbbrev($abbrev);
		} else {
			if ( is_numeric($location) ) {
				// fips
				$fips = $location;
			} else {
				// state abbrev
				$fips = self::getFipsByAbbrev($location);
			}
		}
	
		return $fips;
	
	}
	
	public static function getStateAbbrevFromZip($zip) {
		
		$state_abbrev = '';		
		if ($zip) {			
			/* REST Call */
			// get the State Abbrev for zipcode
			$params = array('zipcode' => $zip);
			$content = vcn_rest_wrapper('vcnlocationsvc', 'vcnlocation', 'get-location', $params);
			$state_abbrev = $content->location->item->state;			
			/* End REST Call */  
		}		
		return $state_abbrev;
		
	}
	
	public static function getStatesList() {
	
		$states = self::$_statesArray;
	
		return $states;
	
	}
	
}


