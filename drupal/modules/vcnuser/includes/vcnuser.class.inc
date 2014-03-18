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
 * VCNUser class
 */
class VCNUser {
   private $url_zip = null;
   private $url_onetcode = null;
   private $url_distance = null;
   private $url_st = null;
   private $url_awlevel = null;
   
   private $session_zip = null;
   private $session_onetcode = null;
   private $session_distance = null;
   private $session_st = null;
   private $session_awlevel = null;
   
   private $cma_zip = null;
   private $cma_onetcode = null;
   private $cma_distance = null;
   private $cma_st = null;
   private $cma_awlevel = null;
   
   // store REST data for occupation and education levels to avoid
   // repeat REST calls for form display and edit
   
   private $occupation_list;
   private $education_levels_list;
   private $distance_options = array(5 => "5 miles", 
	                     15 => "15 miles", 
				    			     25 => "25 miles", 
							         50 => "50 miles", 
							         100 => "100 miles", 
							         250 => "250 miles", 
							         500 => "500 miles");
   
   // default values 
   private $default_distance;
   
   function __construct() {
   	 	
   		$industry_code = vcn_get_industry_code();
   	
   		// gather reference data needed for forms
	   	$params = array('industry' => $GLOBALS['vcn_config_default_industry_id']);
	   	$this->occupation_list = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'listcareers', $params);
	   	//don't want to show education level less than high school, hence using no_less_than_hs flag
	   	$this->education_levels_list = vcn_rest_wrapper('vcnlookupsvc', 'vcnlookup', 'get-education-category', array('industry' => $GLOBALS['vcn_config_default_industry_id'], 'no_less_than_hs' => TRUE));
	   
	   	$this->set_default_distance($GLOBALS['default_distance']); // default distance is 25 miles
	   	
	   	if (empty($GLOBALS['vcn_user_id']) || !isset($GLOBALS['vcn_user_id'])) {
	   		$is_user_id_set = false;
	   	} else {
	   		$is_user_id_set = true;
	   	}
	   	
	   	if (!isset($_SESSION['vcnuser_onetcode_'.$industry_code]) && ($is_user_id_set)) { //if no carrer is selected by user, pull the targeted career from CMA if exists, and later set it to session in function get_onetcode()
	   		$get_targeted_career_data = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-targeted-career', array('user_id' => $GLOBALS['vcn_user_id'], 'industry' => $GLOBALS['vcn_config_default_industry_id']), 'xml', 'post', false);
	   		$targeted_career = isset($get_targeted_career_data->item) ? (string)$get_targeted_career_data->item->itemid[0] : NULL;
	   		$this->cma_onetcode = $targeted_career;
	   	}
   
   }
   
   public function get_zip() {
     if (!empty($this->url_zip)) {
     	// sync session value to url value
	   vcnuser_set_zip_cookie($this->url_zip);
	   return $this->url_zip;
	 } else if (!empty($this->session_zip)) {
	 	if ($this->session_zip == 'cleared' || !is_numeric($this->session_zip)) {
	 		return null;
	 	}
	   return $this->session_zip;
	 } else if (!empty($this->cma_zip)) {
	   return $this->cma_zip;
	 } else {
	   return null;
	 }
   }
   
   public function get_onetcode() {
     if (!empty($this->url_onetcode)) {
	   // sync session value to url value
		   vcnuser_set_onetcode_session($this->url_onetcode);
		   return $this->url_onetcode;
		 } else if (!empty($this->session_onetcode)) {
		   return $this->session_onetcode;
		 } else if (!empty($this->cma_onetcode)) { //defined in the __construct
		 		return $this->cma_onetcode;
		 } else {
		   return null;
		 }
   }
   
   public function get_distance() {
     if (!empty($this->url_distance)) {
	   // sync session value to url value
	   vcnuser_set_distance_cookie($this->url_distance);
	   return $this->url_distance;
	 } else if (!empty($this->session_distance)) {
	   return $this->session_distance;
	 } else if (!empty($this->cma_distance)) {
	   return $this->cma_distance;
	 } else {
	   return $this->default_distance;
	 }
   }
   
	public function get_awlevel() {
   	if (!empty($this->url_awlevel)) { // sync session value to url value
   		vcnuser_set_awlevel_cookie($this->url_awlevel);
   		return $this->url_awlevel;
   	} else if (!empty($this->session_awlevel)) {
   		return $this->session_awlevel;
   	} else if (!empty($this->cma_awlevel)) {
   		return $this->cma_awlevel;
   	} else {
   		//return null;
   		$onetcode = $this->get_onetcode();
   		if (!empty($onetcode)) {
   			/*$max_education_array = vcn_calculate_max_edu_for_career($onetcode, FALSE);
   			vcnuser_set_awlevel_cookie($max_education_array['max_education_id']);
   			return $max_education_array['max_education_id'];*/
   			
   			$all_education_id = vcn_get_all_education_id();
   			vcnuser_set_awlevel_cookie($all_education_id);
   			return $all_education_id;
   			
   		} else {
   			return null;
   		}
   	}
  }
   
   public function get_zip_st() {
     $zip = $this->get_zip();
	 if (!empty($zip)) {
	   return vcnStateUtil::getStateAbbrevFromZip($zip);
     } else {
	   return null;
	 }
   }
   
   public function get_st() {
     //if (!empty($this->url_st)) {
	 //  return $this->url_st;
	 //} else if (!empty($this->session_st)) {
	 //  return $this->session_st;
	 //} else if (!empty($this->cma_st)) {
	 //  return $this->cma_st;
	 //} else {
	 return $this->get_zip_st();
	 //}
   }
    
   public function get_url_zip() {
     return $this->url_zip;
   }
   
   public function set_url_zip($zip) {
     $this->url_zip = $zip;
   }
   
   public function set_url_distance($distance) {
     $this->url_distance = $distance;
   }
   
//   public function set_url_st($st) {
//     $this->url_st = $st;
//   }
   
   public function set_url_onetcode($onetcode) {
     $this->url_onetcode = $onetcode;
   }
   
   public function set_url_awlevel($awlevel) {
     $this->url_awlevel = $awlevel;
   }
   
   public function set_session_zip($zip) {
     $this->session_zip = $zip;
   }
   
   public function set_session_distance($distance) {
     $this->session_distance = $distance;
   }
   
//   public function set_session_st($st) {
//     $this->session_st = $st;
//   }
   
   public function set_session_onetcode($onetcode) {
     $this->session_onetcode = $onetcode;
   }
   
   public function set_session_awlevel($awlevel) {
     $this->session_awlevel = $awlevel;
   }
   
   public function set_cma_zip($zip) {
     $this->cma_zip = $zip;
   }
   
   public function get_session_zip() {
     return $this->session_zip;
   }
   
   public function get_session_distance() {
     return $this->session_distance;
   }
   
   public function get_session_st() {
     return $this->session_st;
   }
   
   public function get_session_onetcode() {
     return $this->session_onetcode;
   }
   
   public function get_session_awlevel() {
     return $this->session_awlevel;
   }
   
   public function get_occupation_list() {
     return $this->occupation_list;
   }
   
   public function get_education_levels_list() {
     return $this->education_levels_list;
   }
   
   public function get_distance_options() {
     return $this->distance_options;
   }
   
   public function set_default_distance($default_distance) {
   		$this->default_distance = $default_distance;
   }
}