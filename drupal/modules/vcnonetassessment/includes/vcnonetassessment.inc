<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_get_onet_assessment_scores($save_results=false) {
  
  $scoreRealistic = 0;
	$scoreInvestigative = 0;
	$scoreArtistic = 0;
	$scoreSocial = 0;
	$scoreEnterprising = 0;
	$scoreConventional = 0;
	
	$userAnswersArr = array();
  $userAnswersTypesArr = array();
  
  if ($GLOBALS['is_user_logged_in'] && !isset($_SESSION['user_answers'][0])) {
    $params = array('userid' => $GLOBALS['vcn_user_id']);
    $content = vcn_rest_wrapper('vcncmasvc', 'vcncmauser', 'get-cma-user-assessment-values', $params);
    if (strlen($content->item->assessmentvalues)) {
      $assessmentArr = json_decode($content->item->assessmentvalues);
      $_SESSION['user_answers'] = $assessmentArr->user_answers;
      $_SESSION['user_answers_types'] = $assessmentArr->user_answers_types;   
    }
  }
  
  for ($i = 0; $i < 60; $i++) {
		$userAnswer = null;
		
		if (isset($_GET['q' . $i]) && strlen($_GET['q' . $i])) {
		  $userAnswer = $_GET['q' . $i];
		} else if (isset($_SESSION['user_answers']) && isset($_SESSION['user_answers'][$i])) {
		  $userAnswer = $_SESSION['user_answers'][$i];  
		}
		
		$userAnswersArr[$i] = $userAnswer;
		
    $userAnswerType = null;
    
		if (isset($_GET['t' . $i]) && strlen($_GET['t' . $i])) {
			$userAnswerType = $_GET['t' . $i];
		} else {
      if (isset($_SESSION['user_answers_types']) && isset($_SESSION['user_answers_types'][$i])) {
		    $userAnswerType = $_SESSION['user_answers_types'][$i];  
		  }
    }
    
    $userAnswersTypesArr[$i] = $userAnswerType;
    
    if (!is_null($userAnswer) && $userAnswerType) {
      switch ($userAnswerType) {
        case '1':
          $scoreRealistic += (int)$userAnswer;
          break;
        case '2':
          $scoreInvestigative += (int)$userAnswer;
          break;
        case '3':
          $scoreArtistic += (int)$userAnswer;
          break;
        case '4':
          $scoreSocial += (int)$userAnswer;
          break;
        case '5':
          $scoreEnterprising += (int)$userAnswer;
          break;
        case '6':
          $scoreConventional += (int)$userAnswer;
          break;
      }
    }
	}
  
  $resultsArr = array();
  $resultsArr['user_answers'] = $userAnswersArr;
  $resultsArr['user_answers_types'] = $userAnswersTypesArr;
  $resultsArr['score_realistic'] = $scoreRealistic;
  $resultsArr['score_investigative'] = $scoreInvestigative;
  $resultsArr['score_artistic'] = $scoreArtistic;
  $resultsArr['score_social'] = $scoreSocial;
  $resultsArr['score_enterprising'] = $scoreEnterprising;
  $resultsArr['score_conventional'] = $scoreConventional;
  
  if ($GLOBALS['is_user_logged_in'] && $save_results) {   
    $params = array('userid' => $GLOBALS['vcn_user_id'], 'assessmentvalues' => json_encode($resultsArr));
    $content = vcn_rest_wrapper('vcncmasvc', 'vcncmauser', 'update-cma-user-assessment-values', $params);
  }
  
  return $resultsArr;
}

function vcn_get_onet_assessment_career_matches_by_scores($industry, $scoreRealistic, $scoreInvestigative, $scoreArtistic, $scoreSocial, $scoreEnterprising, $scoreConventional, $limit=10) {
  
  // get the list of matching careers
	$params = array(
	  'industry' => $industry,
	  'realistic' => $scoreRealistic,
	  'investigative' => $scoreInvestigative,
	  'artistic' => $scoreArtistic,
	  'social' => $scoreSocial,
	  'enterprising' => $scoreEnterprising,
	  'conventional' => $scoreConventional,
	  'limit' => $limit,
	);
  
	$content = vcn_rest_wrapper('vcnonetassessmentsvc', 'vcnonetassessment', 'matching-careers', $params);
  
  $careers = array();
  
  if ($content) {
    for ($i = 0; $i < $limit; $i++) {
      if ($content->career[$i]) {
        $imageName = 'photo.' . vcn_derive_imagename_from_onetcode($content->career[$i]->onetcode) . '.jpg';

        // if career image doesn't exist then put in a stock image
        if (!isset($content->career[$i]->careerimageyn) || $content->career[$i]->careerimageyn == 'N') {
          $imageName = 'photo.none.png';
        }

        $imageUrl = vcn_image_path() . 'career_images/' . $imageName;

        $detailsUrl = vcn_drupal7_base_path() . 'careers/' . $content->career[$i]->onetcode;

        if (vcn_external_client_calling_interest_profiler()) {
          $detailsUrl = vcn_drupal7_base_path() . 'interest-profiler-details/' . $content->career[$i]->onetcode . '/industry/' . $variables['industry'];
        }

        $careers[] = array(
          'onetcode' => $content->career[$i]->onetcode,
          'title' => $content->career[$i]->title,
          'description' => $content->career[$i]->description,
          'image_url' => $imageUrl,
          'details_url' => $detailsUrl,
        );
      }
    }
  }
  
  return $careers;
}

function vcn_get_onet_assessment_career_matches($limit=10) {
  
  $industry = $GLOBALS['vcn_config_default_industry_id'];
  if (isset($_GET['industry']) && strlen($_GET['industry'])) {
		$industry = $_GET['industry'];
	}
  
  $userScoresArr = vcn_get_onet_assessment_scores();
 
  if ($userScoresArr && is_array($userScoresArr) && $userScoresArr['user_answers_types'][0]) {
    $careers = vcn_get_onet_assessment_career_matches_by_scores($industry, $userScoresArr['score_realistic'], $userScoresArr['score_investigative'], $userScoresArr['score_artistic'], $userScoresArr['score_social'], $userScoresArr['score_enterprising'], $userScoresArr['score_conventional'], $limit);
  } else {
    $careers = null;
  }
  
  return $careers;
}
