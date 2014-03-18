<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 

function vcn_career_categories_tabs(&$variables) {
	
	$work_categories = $variables['work_categories'];	
	$vcn_tabs_list = array();	
	$vcn_tabs_keys = array();
	if (count($work_categories->item)>0) {
		for ($i = 0; $i < count($work_categories->item); $i++) {			
			$cat_code = (string) $work_categories->item[$i]->workcategorycode;
			$tab_icon = $variables['vcn_industry_image_path'] . "tab_images/tab_".strtolower($cat_code)."_icon.png";
      
			if (file_exists("..".$tab_icon)) {
				$cat_name = wordwrap((string) $work_categories->item[$i]->workcategorynameabbrev, 11, "<br />\n");
				$vcn_tabs_list[] = array($cat_code,$cat_name,"tab_".strtolower($cat_code)."_icon.png","",(string) $work_categories->item[$i]->workcategoryname);
			}else {
				$cat_name = wordwrap((string) $work_categories->item[$i]->workcategorynameabbrev, 25, "<br />\n");
				$vcn_tabs_list[] = array($cat_code,$cat_name,"","",(string) $work_categories->item[$i]->workcategoryname);
			}			
			$vcn_tabs_keys[] = $cat_code;			
		}
   	}   		
	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = $vcn_tabs_keys;
	$variables['vcn_tabs_header'] = vcn_tabs_header($variables['vcn_tabs_list'], $variables['vcn_tabs_keys']);

	$variables['vcn_tabs_body_start'] = vcn_tabs_body_start();
	$variables['vcn_tabs_body_id_prefix'] = vcn_tabs_body_id_prefix();
	$variables['vcn_tabs_body_end'] = vcn_tabs_body_end();

	return true;
}

function vcn_get_careers_byworktype_edu(&$variables) {
	/* REST Call */
	
	// get the careers with work type and education order by worktype, min required education, career title.
	$params = array('industry' => $variables['industry'], 'order' => array('worktype', 'typicaleduid', 'title'));
	$careers_byworktype_edu = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'get-career-list-by-typical-education', $params);

	return $careers_byworktype_edu;
	/* End REST Call */
}

function vcn_get_education_group_list(&$variables) {
	
	// get the education group list by industry id.
	$params = array('industry' => $variables['industry']);
	$education_group_list = vcn_rest_wrapper('vcnlookupsvc', 'vcnlookup', 'get-industry-education-group-list', $params);

	if(count($education_group_list->educationgroups->item) < 1) {
		drupal_set_message("Missing data of Education Group List by Industry","error");		
	}
		
	return $education_group_list->educationgroups;
		
}

function vcn_process_careers_byworktype_edu(&$variables) { 
	$careers_byworktype_edu = array();
	$careers_byworktype_edu_list = $variables['careers_byworktype_edu_list'];
	 
	if (count($careers_byworktype_edu_list->career)>0 && count($variables['education_group_list']->item)>0) {
		for ($i = 0; $i < count($careers_byworktype_edu_list->career); $i++) {
			$wtkey = "wt-".$careers_byworktype_edu_list->career[$i]->worktype;
			$edukey = "edu-".$careers_byworktype_edu_list->career[$i]->educatid;
			
			if(@!in_array($careers_byworktype_edu_list->career[$i]->worktype, $careers_byworktype_edu["wtkeys"])) {			
				$careers_byworktype_edu["wtkeys"][] = (string) $careers_byworktype_edu_list->career[$i]->worktype; 
			}
			
			if(@!in_array($careers_byworktype_edu_list->career[$i]->educatid, $careers_byworktype_edu[$wtkey]["educatids"])) {
				$educatidskey = (int) $careers_byworktype_edu_list->career[$i]->educatid;
				$careers_byworktype_edu[$wtkey]["educatids"][$educatidskey] = (int) $careers_byworktype_edu_list->career[$i]->educatid;
			}
			
			$careers_byworktype_edu[$wtkey][$edukey][] = array( (string) $careers_byworktype_edu_list->career[$i]->title, (string) $careers_byworktype_edu_list->career[$i]->onetcode);
		}
	}
	if(isset($careers_byworktype_edu["wtkeys"])) {
		foreach($careers_byworktype_edu["wtkeys"] as $wtk => $wtv) {
			$wtkey = "wt-".$wtv;
			
			$careers_byworktype_edu[$wtkey]["group_1"] = array();
			$careers_byworktype_edu[$wtkey]["group_2"] = array();
			$careers_byworktype_edu[$wtkey]["group_3"] = array();
				
			foreach ($careers_byworktype_edu[$wtkey]["educatids"] as $ekey => $eval) {
				$edukey = "edu-".$ekey;
			
				if($ekey <= $variables['education_group_list']->item[0]->maxeducatid) { // $variables['education_group_list']->item[0]->mineducatid $variables['education_group_list']->item[0]->maxeducatid
					$careers_byworktype_edu[$wtkey]["group_1"] = array_merge($careers_byworktype_edu[$wtkey]["group_1"],$careers_byworktype_edu[$wtkey][$edukey]);
				}else if($ekey >= $variables['education_group_list']->item[1]->mineducatid && $ekey <= $variables['education_group_list']->item[1]->maxeducatid) {
					$careers_byworktype_edu[$wtkey]["group_2"] = array_merge($careers_byworktype_edu[$wtkey]["group_2"],$careers_byworktype_edu[$wtkey][$edukey]);
				}else{
					$careers_byworktype_edu[$wtkey]["group_3"] = array_merge($careers_byworktype_edu[$wtkey]["group_3"],$careers_byworktype_edu[$wtkey][$edukey]);
				}
			}
		}
	
		foreach($careers_byworktype_edu["wtkeys"] as $wtk => $wtv) {
			$wtkey = "wt-".$wtv;
			sort($careers_byworktype_edu[$wtkey]["group_1"]);
			sort($careers_byworktype_edu[$wtkey]["group_2"]);
			sort($careers_byworktype_edu[$wtkey]["group_3"]);
		}
	}
	return $careers_byworktype_edu;
	/* End REST Call */
}

function vcn_process_work_type_desc($workcategorydesc, $shortdesc_limit = 500){
	$shortdesc_regular_text = $shortdesc = $workcategorydesc;
	$shortdesc_hidden_text = "";
	if (strlen($shortdesc) > $shortdesc_limit) {
		$spacepos = strpos($shortdesc, ' ', $shortdesc_limit);
		if ($spacepos >= $shortdesc_limit) {
			$shortdesc_regular_text = strip_tags(substr($shortdesc, 0, $spacepos));
			$shortdesc_hidden_text = strip_tags(substr($shortdesc, $spacepos));
		}
	}	
	return array('shortdesc_regular_text' => $shortdesc_regular_text, 'shortdesc_hidden_text' => $shortdesc_hidden_text);
}