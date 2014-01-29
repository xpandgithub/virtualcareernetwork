<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
// Ajax callback functions 
// type = career/program/certification/license, notebookid
function vcn_remove_notebook_item_ajax_callback($item_type, $notebookid) { 
	
	$industry = vcn_get_industry();	
	$user = vcn_get_user_info();
	$vcnUserid = $user['vcn_user_id'];	
	
	if ($item_type == "" || $notebookid == "") {
		return "Missing " . ucfirst($item_type) . " Info, nothing removed";		
	}
	
	if($item_type == 'career') { // check if career is targeted and only 2 in saved list.
		$params = array('userid' => $vcnUserid, 'industry' => $industry);
		$careers = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-notebook-career-items', $params, 'xml', 'post', false);	
		
		$targeted_onet = 0;
		$career_list_array = array();
		$count = 0;
		foreach($careers->item as $key => $i) {	
			if($i->onetcode == "" ) {
				continue;
			}		
			if($i->itemrank == 1) {
				$targeted_onet = (string)$i->onetcode;		
				$targeted_onet_notebookid = (string)$i->notebookid;
			}
			$count++;
		}
		
		if($count > 2 && $targeted_onet_notebookid == $notebookid) {
			return "Please choose another target career before deleting this. You will need to go to choose a career (Choose Your Target Career) to do that.";			
		}
	}
			
	$params = array('industry' => $industry, 'userid' => $vcnUserid, 'itemtype' => $item_type, 'notebookid' => $notebookid);
	$result = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'remove-notebook-item', $params, 'xml', 'post', false);
	var_dump($result);
	//echo $result;	
}

function vcn_remove_employment_history_ajax_callback($useremploymentid) {
	
	$user = vcn_get_user_info();
	$vcnUserid = $user['vcn_user_id'];

	if ($useremploymentid == "") {
		return "Missing Employment history Info, nothing removed";
	}	
		
	$params = array('userid' => $vcnUserid, 'useremploymentid' => $useremploymentid);
	$result = vcn_rest_wrapper('vcncmasvc', 'vcncmaemployment', 'remove-employment-history', $params, 'xml', 'post', false);
	var_dump($result);
	//echo $result;
}

function vcn_cma_course_completed_ajax_callback($course) {
  
  $success = 'false';
  $user = vcn_get_user_info();
	$vcnUserid = $user['vcn_user_id'];

	if (!strlen($course)) {
		return "Missing moodle course id";
	}	
		
	if($vcnUserid > 0) {
		$params = array('userid' => $vcnUserid, 'courseid' => $course);
		$result = vcn_rest_wrapper('vcncmasvc', 'vcncmauser', 'update-user-course-completed', $params, 'xml', 'post', false);   
	
    if ($result->item && $result->item == '1') {
      $success = 'true';
    }
  }
  
  echo $success;
}
?>