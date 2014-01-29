<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

//License detail page
function vcn_license_detail($licenseid, $location, $onetcode) {
	return theme('vcn_license_detail_template', array('licenseid' => $licenseid, 'location' => $location, 'onetcode' => $onetcode));
}

function vcn_license_detail_facebook_like(&$variables) {

	// Facebook Like button
	$current_url = "http://" . $_SERVER['SERVER_NAME'] . vcn_drupal7_base_path() . 'get-qualified/license/' . $variables['licenseid'].'/location/' . $variables['location'].'/onetcode/' . $variables['onetcode'];
	$facebookTitle = "VCN.org License: " . $variables['title'];
	// metatags for the Facebook Like button
	$facebookMetatags = new vcnFacebookMetatag($facebookTitle, $current_url, $variables['description']);
	$facebookMetatags->getTags();
	// Generate Facebook Like button
	$facebookLikeButton = new vcnFacebookLike($current_url);
	$facebookLikeButton->width = '80';
	$facebookLikeButton->shiftTop = '1';
	$facebookLikeButton->shiftLeft = '0';

	return $facebookLikeButton->getButton();
}

// Get the details of license by id from COS REST
function vcn_get_license_detail(&$variables) {

	// get the License detail info
	$detail = vcnLicenseDataService::getLicenseById($variables['onetcode'], $variables['licenseid'], $variables['location']);

	$variables['fips'] = '00';
	if (isset($variables['location']) && strlen($variables['location'])) {
		$variables['fips'] = vcnStateUtil::getFipsByUnknown($variables['location']);
	}
	$license = $detail->data->licenses;

	$variables['id'] = $license->licenseid;
	$variables['title'] = $license->lictitle;
	$variables['description'] = $license->licdesc;
	$variables['lic_auth_name'] = $license->licauth->name1;
	$variables['lic_auth_phone'] = $license->licauth->telephone;

	$address = '';
	if (isset($license->licauth->address1) && strlen($license->licauth->address1)) {
		$address .= $license->licauth->address1;

		if (isset($license->licauth->address2) && strlen($license->licauth->address2)) {
			$address .= ', ' . $license->licauth->address2;
		}
	}
		
	if (isset($license->licauth->city) && strlen($license->licauth->city) && isset($license->licauth->st) && strlen($license->licauth->st)) {
		$address .= '<br/>' . $license->licauth->city . ', ' . $license->licauth->st;
	}

	if (isset($license->licauth->zip) && strlen($license->licauth->zip)) {
		if (!strlen($license->licauth->address1) && !strlen($license->licauth->city) && !strlen($license->licauth->st)) {
			$address .= '<br/>';
		}
		$address .= ' ' . $license->licauth->zip;
	}

	if (isset($license->licauth->telephone) && strlen($license->licauth->telephone)) {
		$address .= '<br/>' . $license->licauth->telephone;
	}

	$variables['lic_auth_address'] = $address;

	if (isset($license->licauth->url) && strlen($license->licauth->url)) {
		$variables['lic_auth_name'] = vcn_build_link_window_opener($license->licauth->url, $variables['lic_auth_name'], false);
	} else {
		$variables['lic_auth_name'] = '<strong>' . $variables['lic_auth_name'] . '</strong>';
	}

	$variables['facebook_like'] = ''; //vcn_license_detail_facebook_like($variables);
}

function vcn_license_detail_tabs(&$variables) {

	//License Overview 
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("lover","Overview");	

	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = array("lover");

	$variables['vcn_tabs_header'] = vcn_tabs_header($variables['vcn_tabs_list'], $variables['vcn_tabs_keys']);

	$variables['vcn_tabs_body_start'] = vcn_tabs_body_start();
	$variables['vcn_tabs_body_id_prefix'] = vcn_tabs_body_id_prefix();
	$variables['vcn_tabs_body_end'] = vcn_tabs_body_end();

	return true;
}