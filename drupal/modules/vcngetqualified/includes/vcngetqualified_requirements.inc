<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_getqualified_requirements_callback() {
	return theme('vcn_getqualified_requirements');
}

function vcn_getqualified_get_requirements_data() {
	
	module_load_include('inc', 'vcngetqualified', '/includes/classes/vcn_state_util.class');
	module_load_include('inc', 'vcngeneral', '/includes/vcngeneral');
	
	$onetcode = vcnuser_get_onetcode();
	$zipcode = vcnuser_get_zip();
	
	if ($onetcode) {
		$params = array('onetcode' => $onetcode, 'industry' => $GLOBALS['vcn_config_default_industry_id']);
		if ($zipcode) {
			$state = vcnStateUtil::getStateAbbrevFromZip($zipcode);
			if ($state) {
				$params['state'] = $state;
			}
		}
		
		$content = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'get-career-requirements', $params, 'json');
		
		$requirements_data = array();
		$requirements_data['onetcode_set'] = TRUE;
		$requirements_data['onetcode'] = $onetcode;
		$requirements_data['zipcode'] = $zipcode;
		$requirements_data['state'] = isset($state) ? $state: NULL;
		$requirements_data['career_title'] = isset($content[0]->title) ? $content[0]->title : NULL;    
		 
		
		
		$requirements_data['physical_requirement_url'] = isset($content[0]->physical_requirement_url) ? $content[0]->physical_requirement_url : NULL;
    	$requirements_data['physical_requirement_url_flag'] = isset($content[0]->physical_requirement_url_flag) ? $content[0]->physical_requirement_url_flag : NULL;
       	$requirements_data['physical_health_requirements'] = isset($content[0]->physical_health_requirement) ? $content[0]->physical_health_requirement : NULL;
    	    	
    	$requirements_data['legal_nationwide_requirements'] = isset($content[0]->nationwide_legal_requirement_desc) ? $content[0]->nationwide_legal_requirement_desc : NULL;
    	//$requirements_data['legal_state_specific_requirement'] = isset($content[0]->legal_requirement) ? $content[0]->legal_requirement : NULL;
		//$requirements_data['legal_requirement_url'] = isset($content[0]->legal_associated_url) ? $content[0]->legal_associated_url : NULL;
	  	//$requirements_data['legal_requirement_url_flag'] = isset($content[0]->legal_associated_url_flag) ? $content[0]->legal_associated_url_flag : NULL;
	  	    	
    	/* Legal requirements (state specific)*/
    	$requirements_data['legal_state_specific_requirements_regular_text'] = 'Please enter your <b>Preferred Location (ZIP Code)</b> to view the legal requirements for this occupation.';
    	$requirements_data['legal_state_specific_requirements_hidden_text'] = NULL;
    	if (isset($zipcode) && $zipcode > 0) {
    		$requirements_data['legal_state_specific_requirements_regular_text'] = "No specific Legal requirements have been identified in your state.";
    	
    		$legal_state_specific_all_requirements = "";
    	
    		$legal_state_specific_all_requirements.= (trim ((string)$content[0]->legal_absolute_prohibitions) != "" ? "<p>".trim ((string)$content[0]->legal_absolute_prohibitions)."</p>" : "");
    		$legal_state_specific_all_requirements.= (trim((string)$content[0]->legal_requirement) != "" ? "<p>".trim ((string)$content[0]->legal_requirement)."</p>" : "");
    		$legal_state_specific_all_requirements.= (trim ((string)$content[0]->legal_health_issues) != "" ? "<p>".trim ((string)$content[0]->legal_health_issues)."</p>" : "");
    		$legal_state_specific_all_requirements.= (trim ((string)$content[0]->legal_generic_requirements) != "" ? "<p>".trim ((string)$content[0]->legal_generic_requirements)."</p>" : "");
    	
    		if($legal_state_specific_all_requirements != ""){
    			$legal_state_specific_all_requirements_list = vcn_text_into_paragraphs((string)$legal_state_specific_all_requirements);
    				
    			$legal_state_specific_requirements_regular_text = $requirements_data['legal_state_specific_requirements_regular_text'];
    			if (!empty($legal_state_specific_all_requirements_list['regular_text_array'])) {
    				$legal_state_specific_requirements_regular_text = "";
    				foreach ($legal_state_specific_all_requirements_list['regular_text_array'] as $value) {
    					$legal_state_specific_requirements_regular_text .=  $value;
    				}
    			}
    			$requirements_data['legal_state_specific_requirements_regular_text'] = $legal_state_specific_requirements_regular_text;
    				
    			$legal_state_specific_requirements_hidden_text = $requirements_data['legal_state_specific_requirements_hidden_text'];
    			if (!empty($legal_state_specific_all_requirements_list['hidden_text_array'])) {
    				$legal_state_specific_requirements_hidden_text = "";
    				foreach ($legal_state_specific_all_requirements_list['hidden_text_array'] as $value) {
    					$legal_state_specific_requirements_hidden_text .=  $value;
    				}
    			}
    			$requirements_data['legal_state_specific_requirements_hidden_text'] = $legal_state_specific_requirements_hidden_text;
    				
    		}
    	
    		$requirements_data['legal_state_specific_requirements_associated_url'] = (string)$content[0]->legal_associated_url;
    		$requirements_data['legal_state_specific_requirements_associated_url_flag'] = (string)$content[0]->legal_associated_url_flag;
    			
    		if (!isset($requirements_data['legal_state_specific_requirements_associated_url_flag']) || $requirements_data['legal_state_specific_requirements_associated_url_flag'] !== '1') {
    			$requirements_data['legal_state_specific_requirements_associated_url'] = '';
    		}
    	
    	}
    	
	
    	$requirements_data['typical_education'] = isset($content[0]->typical_education) ? $content[0]->typical_education : NULL;
		$requirements_data['preceding_career_title'] = isset($content[0]->preceding_career_title) ? $content[0]->preceding_career_title : NULL;
		$requirements_data['preceding_career_onetcode'] = isset($content[0]->preceding_career_onetcode) ? $content[0]->preceding_career_onetcode : NULL; 
  } else {
		$requirements_data['onetcode_set'] = FALSE;
	}
	
	return $requirements_data;
}