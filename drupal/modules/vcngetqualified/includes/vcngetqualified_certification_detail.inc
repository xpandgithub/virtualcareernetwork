<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

//Certification detail page
function vcn_certification_detail($certificationid, $onetcode) {
	return theme('vcn_certification_detail_template', array('certificationid' => $certificationid, 'onetcode' => $onetcode));
}


function vcn_certification_detail_facebook_like(&$variables) {

	// Facebook Like button
	$current_url = "http://" . $_SERVER['SERVER_NAME'] . vcn_drupal7_base_path() . 'get-qualified/certification/' . $variables['certificationid'].'/onetcode/' . $variables['onetcode'];
	$facebookTitle = "VCN.org Certification: " . $variables['name'];
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


// Get the details of certification by id from COS REST
function vcn_get_certification_detail(&$variables) {

	// get the Certification detail info
	$detail = vcnCertificationDataService::getCertificationById($variables['certificationid']);

	$certification = $detail->data->certifications;

	$variables['id'] = $certification->certid;
	$variables['name'] = $certification->certname;
	$variables['url'] = $certification->url;

	if (strlen($variables['url']) > 4) {
		$variables['url'] = vcn_build_link_window_opener($variables['url'], "Click here", false);
	}

	$variables['description'] = $certification->certdescription;
	$variables['type'] = $certification->certtypename;
	$variables['org_id'] = $certification->certorg->orgid;
	$variables['org_name'] = $certification->certorg->orgname;
	$variables['org_address'] = $certification->certorg->orgaddress;
	$variables['org_url'] = $certification->certorg->orgwebpag;

	if (strlen($variables['org_url'])) {
		$variables['org_name'] = vcn_build_link_window_opener($variables['org_url'], $variables['org_name'], false);
	} else {
		$variables['org_name'] = '<strong>' . $variables['org_name'] . '</strong>';
	}

	$variables['facebook_like'] = ''; //vcn_certification_detail_facebook_like($variables);
}

function vcn_certification_detail_tabs(&$variables) {

	//Certification Overview
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("cover","Overview");

	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = array("cover");

	$variables['vcn_tabs_header'] = vcn_tabs_header($variables['vcn_tabs_list'], $variables['vcn_tabs_keys']);

	$variables['vcn_tabs_body_start'] = vcn_tabs_body_start();
	$variables['vcn_tabs_body_id_prefix'] = vcn_tabs_body_id_prefix();
	$variables['vcn_tabs_body_end'] = vcn_tabs_body_end();

	return true;
}