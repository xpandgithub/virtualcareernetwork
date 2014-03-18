<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_cma_jobscouts_execute_job_scouts() {
  
  // Load the findwork module that has the functions we need
  module_load_include('inc', 'vcnfindwork', 'includes/vcnfindwork'); // vcnuser class file must be included to create user object with vcnuser_get_user_values() function

  global $user;

  // Make REST call to get all job scouts for all users for just today
  $params = array('industry' => vcn_get_industry());
  $jobscouts = vcn_rest_wrapper('vcncmasvc', 'vcncmajobscouts', 'get-all-cma-jobscouts-for-today', $params, 'xml', 'post', false);

  // setup email header for HTML format
  $header  = 'MIME-Version: 1.0' . "\r\n";
  $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $header .= "From: support@vcn.org \r\n";
  $header .= "Reply-To: support@vcn.org \r\n";

  // Loop through all job scouts and for each one call DE web service via call to vcnfindwork.inc functions  
  // (vcn_findwork_results_get_data_freetext_search and vcn_findwork_results_get_data_by_onetcode) and send email with results
  foreach ($jobscouts->item as $jobscout) {

    $user = user_load((int)$jobscout->drupaluserid);

    $params = array('onetcode' => (string)$jobscout->onetcode, 
                    'zipcode' => (string)$jobscout->zip, 
                    'distance' => (string)$jobscout->distance, 
                    'search_term' => (string)$jobscout->keyword,
                    'career_title' => (string)$jobscout->careername);

    if (strlen($jobscout->onetcode)) {
      $jobs = vcn_findwork_results_get_data_by_onetcode($params, false, 10);
    } else {
      $jobs = vcn_findwork_results_get_data_freetext_search($params, false, 10);
    }

    $jobsCount = count($jobs);

    // send email using $user->mail ... is there a drupal mail function we can use?
    if ($jobsCount > 0) {
      $subject = '';
      if ($GLOBALS['vcn_config_current_environment'] != 'PRODUCTION') {
        $subject = $GLOBALS['vcn_config_current_environment'].': ';
      }
      $subject .= 'VCN.org Job Scout Results';
      
      $body = vcn_cma_jobscouts_format_email_body($jobs, $jobscout);
      $to = vcn_cma_jobscouts_get_email_addressee($user->mail);

      if ($to) {
        $mail_sent = mail($to, $subject, $body, $header);
      }
    }
    
  }
}

/**
* format_email_body()
* Builds the body of the email displaying the jobs
*
* @param array $jobs
* @param object $jobScout
* @return string
*/
function vcn_cma_jobscouts_format_email_body($jobs, $jobScout) {
	
	$baseUrl = vcn_base_url() . vcn_drupal7_base_path();
	$industryName = vcn_get_industry_name();

	$jobsCount = count($jobs);
	$location = (strlen($jobScout->zip)) ? ' within <strong>'.$jobScout->distance.' miles</strong> of ZIP Code <strong>'.$jobScout->zip.'</strong>.' : ' <strong>throughout the US</strong>.';
		
	$displayText = '';
	$url = '';
	if (!is_null($jobScout->onetcode) && strlen($jobScout->onetcode)) {
		$displayText = $industryName . ' Career: ' . $jobScout->careername;
		$url = $baseUrl . 'findwork-results/career/' . $jobScout->onetcode . '/zip/' . $jobScout->zip . '/distance/' . $jobScout->distance;
	} else {
		if (!is_null($jobScout->keyword) && strlen($jobScout->keyword)) {
			$displayText = 'Job Title: ' . $jobScout->keyword;
			$url = $baseUrl . 'findwork-results/search-term/' . urlencode($jobScout->keyword) . '/zip/' . $jobScout->zip . '/distance/' . $jobScout->distance;
		}
	}
	
	// $baseUrl findwork-results/search-term/food%20taster/zip/20191/distance/25
	// $baseUrl findwork-results/career/29-2051.00/zip/20191/distance/25

	$html = '<html><body style="font-family:Verdana, Tahoma, sans-serif; font-size:11px;">' .
	        '<h3>VCN.org Job Scout Results</h3><div style="font-size:11px;">There were jobs found matching <strong>' . $displayText . '</strong> ' . $location . '<br>' .
					'Below is a sample of the jobs.  To view the entire results <a href="' . $url . '">click here</a>.</div><br><br>' .
	        '<table cellpadding="5" style="width:700px; border-collapse:collapse; font-family:Verdana, Tahoma, sans-serif; font-size:11px;">' .
	        '    <tr style="color:black; background-color:#e2e2e2; border:1px solid #ffffff;">' .
	        '        <th style="border:1px solid #ffffff;">Title</th>' .
					'        <th style="border:1px solid #ffffff;">Company</th>' .
					'        <th style="border:1px solid #ffffff;">Location</th>' .
					'        <th style="border:1px solid #ffffff;">Date</th>' .
					'    </tr>';
	
	for ($i = 0;$i < count($jobs);$i++) {
		$jobArr = $jobs[$i];
		
		$date = date('m/d/Y', strtotime($jobArr['date_acquired']));
		
		$html .= '<tr style="background-color:#efefef; border:1px solid #ffffff;">' .
		         '    <td style="border:1px solid #ffffff;"><a href="' . $jobArr['job_url'] . '" style="color:#AB0534">' . $jobArr['job_title'] . '</a></td>' .
						 '    <td style="border:1px solid #ffffff;">' . $jobArr['company'] . '</td>' .
						 '    <td style="border:1px solid #ffffff;">' . $jobArr['location'] . '</td>' .
						 '    <td style="border:1px solid #ffffff;">' . $date . '</td>' .
						 '</td>';
	}
	
	$html .= '</table><br/><br/></body></html>';
	
	return $html;
}

function vcn_cma_jobscouts_get_email_addressee($toEmail) {
  
  if ($GLOBALS['vcn_config_current_environment'] == 'PRODUCTION') {
    return $toEmail;
  }
  
  $emails = explode(',', $GLOBALS['vcn_config_email_white_list']);
  
  foreach ($emails as $email) {
    if (stristr($toEmail, $email)) {
      return $toEmail;
    }
  }
  
  return false;
}