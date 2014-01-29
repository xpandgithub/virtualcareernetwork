<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 

//School/Provider detail page
function vcn_schooldetail_by_unitid($unitid) {
	return theme('vcn_schooldetail_by_unitid_template', array('unitid' => $unitid));
}


function vcn_school_detail(&$variables) {

	// Facebook Like button
	$variables['facebook_like'] = ''; //vcn_school_detail_facebook_like($variables);

	$variables['na'] = 'N/A';

	$providerdetail = $variables['providerdetail'];
	$providerdegreesoffered = $variables['providerdegreesoffered'];
	$providerentrancetests = $variables['providerentrancetests'];
	$providerfaidoffered = $variables['providerfaidoffered'];
	$providerrequiredcourses = $variables['providerrequiredcourses'];
	$providerservices = $variables['providerservices'];

	if ((strlen($providerdetail->logoimage)>1) ) {
		$prologo = $providerdetail->logoimage;
		$datatype='data:image/jpeg';
		$variables['school_logo'] =$datatype.";base64,".$prologo;
	} else {
		$variables['school_logo'] = vcn_image_path()."miscellaneous/school_default_logo.jpg";
	}

	if (strlen($providerdetail->missionstatement)) {
		$variables['missionstatement'] = $providerdetail->missionstatement;
	}

	if (strlen($providerdetail->missionstatementurl)>4 && $providerdetail->missionstatementurlflag) {
		$variables['missionstatementurl'] = vcn_build_link_window_opener($providerdetail->missionstatementurl, $providerdetail->name." Mission Statement", false, true);
	}

	if (strlen($providerdetail->applurl)>4 && $providerdetail->applurlflag) {
		$variables['applurl'] = vcn_build_link_window_opener($providerdetail->applurl, "Apply online", false, true);
	}

	if ($providerdetail->addr != 'null') {
		$variables['address'] = $providerdetail->addr;
	}
	$variables['address'] = $variables['address']."; ".$providerdetail->city.", ".$providerdetail->state." ".$providerdetail->zipcode;

	$variables['phone'] = vcn_format_phone($providerdetail->phone);

	if (strlen($providerdetail->webaddr)>4 && $providerdetail->webaddrflag) {
		$variables['webaddr'] = vcn_build_link_window_opener($providerdetail->webaddr, $providerdetail->webaddr, false, true);
	}

	if ($providerdetail->ipedsdesc != 'null') {
		$variables['ipedsdesc'] = $providerdetail->ipedsdesc;
	}else {
		$variables['ipedsdesc'] = 'N/A';
	}

	if (intval($providerdetail->percentadmittedtotal) < 1) {
		$variables['percentadmittedtotal'] = 'N/A';
	}else {
		$variables['percentadmittedtotal'] = number_format(intval($providerdetail->percentadmittedtotal), 0, '.', ',')."%";
	}

	$variables['degrees'] = "";
	foreach ($providerdegreesoffered->item as $degree) {
		$variables['degrees'] .= "<div class=\"school-list-item\">".$degree->codedesc."</div>";
	}

	$variables['services'] = "";
	if ($providerservices->item[0]->servicename != "") {
		foreach ($providerservices->item as $services) {
			$variables['services'] .= "<div class=\"school-list-item\">".$services->servicename."</div>";
		}
	}else {
		$variables['services'] = 'N/A';
	}

	$variables['entrancetests'] = "";
	if (count($providerentrancetests->item) > 0) {
		foreach ($providerentrancetests->item as $test) {
			if (strlen($test->minscore) && (strtoupper($test->minscore) == 'NONE' || strtoupper($test->minscore) == 'N/A' || strtoupper($test->minscore) == 'NA')) {
				$testminscore = '';
			} else {
				$testminscore = ': ' . $test->minscore;
			}
			$variables['entrancetests'] .= "<b>".$test->testname."</b>". $testminscore."<br/>";
		}
		$variables['entrancetests'] .= "<br/>";
	} else {
		$variables['entrancetests_na'] = 'N/A';
	}
	$variables['faidurl'] = "";
	if (strlen($providerdetail->faidurl)>4 && $providerdetail->faidurlflag) {
		$variables['faidurl'] = vcn_build_link_window_opener($providerdetail->faidurl, "Financial Aid (For this school)", false, true);
	}

	$variables['v2faidurl'] = "";
	if (count($providerfaidoffered->item) > 0) {
		foreach ($providerfaidoffered->item as $v2faidurl) {
			if (strlen($v2faidurl->v2loanid)) {
				$variables['v2faidurl'] .= "<div>".vcn_build_link_window_opener($v2faidurl->v3loanurl, $v2faidurl->v3loanname, false, true)."</div>";
			}
		}
	}

	$variables['requiredcourses'] = "";
	if (count($providerrequiredcourses->item) > 0) {
		foreach ($providerrequiredcourses->item as $requiredcourses) {
			if (($requiredcourses->courseid) > 0) {
				switch (strtoupper($requiredcourses->courselevel)) {
					case 'C':
						$level = 'College';
						break;
					case 'H':
						$level = 'High School';
						break;
					case 'H or C':
						$level = 'High School or College';
						break;
					case 'B':
						$level = 'Bachelors';
						break;
					default:
						$level = $requiredcourses->courselevel;
						break;
				}
				$divider = '';
				if(strlen($requiredcourses->description) > 0) {
					$divider = ' - ';
				}
				$variables['requiredcourses'] .= '<div class="school-required-courses allclear">';
				$variables['requiredcourses'] .= '<div><b>'.$requiredcourses->coursetitle.'</b>'.$divider.$requiredcourses->description.'&nbsp;</div>';
				$variables['requiredcourses'] .= '<div>'.$level.'&nbsp;</div>';
				$variables['requiredcourses'] .= '<div>'.$requiredcourses->mingpa.'&nbsp;</div>';
				$variables['requiredcourses'] .= '</div>';
			}
		}
	}

	return true;
}


function vcn_school_detail_tabs(&$variables) {
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("desc","Overview");
	$vcn_tabs_list[] = array("deg","Degrees Offered");
	$vcn_tabs_list[] = array("test","Entrance Tests");
	$vcn_tabs_list[] = array("cost","Cost + Fees");
	$vcn_tabs_list[] = array("ser","Services");
	$vcn_tabs_list[] = array("demo","Student<br/>Demographics");
	$vcn_tabs_list[] = array("req","Prerequisite Courses<br/>for Admission");

	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = array("desc","deg","test","cost","ser","demo","req");

	$variables['vcn_tabs_header'] = vcn_tabs_header($variables['vcn_tabs_list'], $variables['vcn_tabs_keys']);

	$variables['vcn_tabs_body_start'] = vcn_tabs_body_start();
	$variables['vcn_tabs_body_id_prefix'] = vcn_tabs_body_id_prefix();
	$variables['vcn_tabs_body_end'] = vcn_tabs_body_end();

	return true;
}