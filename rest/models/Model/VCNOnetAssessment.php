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
 * VCN_Model_VCNOnetAssessment Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNOnetAssessment extends VCN_Model_Base_VCNBase {
	
	public function getQuestions() {

    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT question_id, question_text, type
               FROM vcn_onet_assessment_question
               WHERE category = 'A'
               ORDER BY question_id ";

      $stmt = $db->prepare($sql);
      $stmt->execute();

      $data = $stmt->fetchAll();

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
	
	public function getMatchingCareers($params) {

		$requiredParams = array('industry', 'realistic', 'investigative', 'artistic', 'social', 'enterprising', 'conventional');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		$lowestCoefficient = 0.05;
		if (isset($params['lowestcoeff'])) {
			$lowestCoefficient = $params['lowestcoeff'];
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT DISTINCT vooa.*, o.career_image_yn, o.display_title, o.detailed_description
               FROM vcn_occupation_onet_assessment vooa
               JOIN vcn_occupation o ON o.onetcode = vooa.onetcode 
               JOIN vcn_occupation_industry oxi ON oxi.onetcode = o.onetcode
               WHERE oxi.industry_id = :industry
               ORDER BY o.onetcode ";

      $binds = array(':industry' => $params['industry']);

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $oNetCareersArray = array();

      foreach ($result as $row) {	
        $detailedDescriptionArr = explode('.', $row['detailed_description']);

        $detailsArr = array(
            'careerimageyn' => $row['career_image_yn'],
            'title' => $row['display_title'],
            'description' => $detailedDescriptionArr[0],
            'scores' => array(
              'realistic' => $row['REALISTIC_SCORE'],
              'investigative' => $row['INVESTIGATIVE_SCORE'],
              'artistic' => $row['ARTISTIC_SCORE'],
              'social' => $row['SOCIAL_SCORE'],
              'enterprising' => $row['ENTERPRISING_SCORE'],
              'conventional' => $row['CONVENTIONAL_SCORE'],
          ),
        );

        $oNetCareersArray[$row['ONETCODE']] = $detailsArr;
      }

      //Set up an array to keep track of the correlation coefficients
      $correlationCoefficientsArr = array();

      // First, we determine the mean and standard deviation of our test value
      $userMean = ($params['realistic'] +
                   $params['investigative'] +
                   $params['artistic'] +
                   $params['social'] +
                   $params['enterprising'] +
                   $params['conventional']) / 6;

      $userStdDev = sqrt((pow(($params['realistic'] - $userMean), 2) +
                          pow(($params['investigative'] - $userMean), 2) +
                          pow(($params['artistic'] - $userMean), 2) +
                          pow(($params['social'] - $userMean), 2) +
                          pow(($params['enterprising'] - $userMean), 2) +
                          pow(($params['conventional'] - $userMean), 2)) / 5);

      foreach ($oNetCareersArray as $key => $valueArr) {
        // calculate out the mean and standard deviation of each O*Net career
        $oNetMean = ($valueArr['scores']['realistic'] +
                     $valueArr['scores']['investigative'] +
                     $valueArr['scores']['artistic'] +
                     $valueArr['scores']['social'] +
                     $valueArr['scores']['enterprising'] +
                     $valueArr['scores']['conventional']) / 6;

        $oNetStdDev = sqrt((pow(($valueArr['scores']['realistic'] - $oNetMean), 2) +
                            pow(($valueArr['scores']['investigative'] - $oNetMean), 2) +
                            pow(($valueArr['scores']['artistic'] - $oNetMean), 2) +
                            pow(($valueArr['scores']['social'] - $oNetMean), 2) +
                            pow(($valueArr['scores']['enterprising'] - $oNetMean), 2) +
                            pow(($valueArr['scores']['conventional'] - $oNetMean), 2)) / 5);

        // calculate the top half of the equation...
        $correlationCoefficientsSum = (($valueArr['scores']['realistic'] - $oNetMean) * ($params['realistic'] - $userMean)) +
                                      (($valueArr['scores']['investigative'] - $oNetMean) * ($params['investigative'] - $userMean)) +
                                      (($valueArr['scores']['artistic'] - $oNetMean) * ($params['artistic'] - $userMean)) +
                                      (($valueArr['scores']['social'] - $oNetMean) * ($params['social'] - $userMean)) +
                                      (($valueArr['scores']['enterprising'] - $oNetMean) * ($params['enterprising'] - $userMean)) +
                                      (($valueArr['scores']['conventional'] - $oNetMean) * ($params['conventional'] - $userMean));

        if ($userStdDev != 0.0 && $oNetStdDev != 0.0) {
          $correlationCoefficient = $correlationCoefficientsSum / (6 * $userStdDev * $oNetStdDev);

          // adding the coefficient to the array if its big enough
          if ($correlationCoefficient >= $lowestCoefficient) {
            $infoArr = array(
                      'onetcode' => $key,
                      'careerimageyn' => $oNetCareersArray[$key]['careerimageyn'],
                      'title' => $oNetCareersArray[$key]['title'],
                      'description' => $oNetCareersArray[$key]['description'],
                      'coefficient' => $correlationCoefficient,
            );

            $correlationCoefficientsArr[] = $infoArr;
          }
        }
      }

      // Sort the array from largest to smallest coefficient
      usort($correlationCoefficientsArr, array("VCN_Model_VCNOnetAssessment", "sortByCorrelationCoefficient"));

      $finalCorrelationCoefficientsArr = array();
      for ($i = 0; $i < count($correlationCoefficientsArr); $i++) {
        $finalCorrelationCoefficientsArr[] = $correlationCoefficientsArr[$i];
        if (isset($params['limit']) && $i > $params['limit'] - 2) {
          break;
        }
      }

      $data = $finalCorrelationCoefficientsArr;

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
		return $this->result;
		 
	}
	
	private function sortByCorrelationCoefficient($a, $b) {
		if ($a["coefficient"] == $b["coefficient"]) {
			return 0;
		}
		return ($a["coefficient"] > $b["coefficient"]) ? -1 : 1;
	}
	
}
 