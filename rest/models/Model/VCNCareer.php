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
 * VCN_Model_VCNCareer Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNCareer extends VCN_Model_Base_VCNBase {

	public function listCareers($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $additional = '';
      if (isset($params['additionalinfo'])) {
        $additional = ' , detailed_description, day_in_life, academic_requirement, physical_requirement, health_requirement ';
      }
      
      $distinct = 'o.onetcode';
      $worktype = '';
      if (!isset($params['ignoreworktype'])) {
        $worktype = ' , olwc.work_category_code AS worktype ';
      }else {
      	$distinct = ' DISTINCT(o.onetcode) ';
      }
      
      $sql = " SELECT $distinct AS onetcode, o.display_title AS title, o.minimum_education_category_id AS mineducatid
                      $worktype
                      $additional
               FROM vcn_occupation o 
               JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry ";
          
      if (!isset($params['ignoreworktype'])) {
        $sql .= " JOIN vcn_lookup_work_category olwc ON oxi.work_category_id = olwc.work_category_id ";
      }
      
      if (isset($params['order'])  && is_array($params['order']) === true ) {
        $cat = implode(" , ",$params['order']);
        $sql .= " ORDER BY ".$cat;
      } else if (isset($params['order']) && is_array($params['order']) === false ) {
        $sql .= " ORDER BY ".$params['order'];
      }else {
        $sql .= " ORDER BY title";
      }		

      $binds = array(
        ':industry' => $params['industry'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array();

      $i = 0;
      foreach ($result as $row) {
        $data[$i] = array(
            'title' => $row['title'],
            'onetcode' => $row['onetcode'],
            'mineducatid' => $row['mineducatid'],
        );
        
        if (!isset($params['ignoreworktype'])) {
          $data[$i]['worktype'] = $row['worktype'];
        }
        
        if (isset($params['additionalinfo'])) {
          $data[$i]['detaileddesc'] = $row['detailed_description'];
          $data[$i]['dayinlife'] = $row['day_in_life'];
          $data[$i]['academicreq'] = $row['academic_requirement'];
          $data[$i]['physicalreq'] = $row['physical_requirement'];
          $data[$i]['healthreq'] = $row['health_requirement'];
        }
        
        $i++;
      }

      $this->setResult($data);
      
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
		return $this->result;
	}
	
	public function searchCareers($params) {
		
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $binds = array();
      $binds[':industry'] = $params['industry'];

      $laytitleSubSql = '';
      $laytitleWhere = '';
      if (isset($params['keyword']) && strlen($params['keyword'])) {			
        // deal with spaces between words ... force AND condition
        $keywordsArr = explode(' ', $params['keyword']);

        $keywordsForLaytitles = '';
        $keywordsForTitle = '';

        for ($i = 0; $i < count($keywordsArr); $i++) {
          $binds[':keyword'.$i] = '%' . strtolower($keywordsArr[$i]) . '%';

          $keywordsForLaytitles .= 'LOWER( laytitle ) LIKE :keyword'.$i;
          $keywordsForTitle .=  'LOWER( display_title ) LIKE :keyword'.$i;

          if ($i < count($keywordsArr) - 1) {
            $keywordsForLaytitles .= ' AND ';
            $keywordsForTitle .= ' AND ';
          }
        }

        $laytitleSubSql = " AND $keywordsForLaytitles";
        $laytitleWhere = " WHERE ( $keywordsForTitle
                           OR o.onetcode in ( SELECT DISTINCT onetcode FROM onetsoc_laytitle WHERE $keywordsForLaytitles ) ) ";
      }

      $worktypeWhere = '';
      if (isset($params['worktype']) && strlen($params['worktype'])) {
        $binds[':worktype'] = strtolower($params['worktype']);
        $worktypeWhere = ' AND LOWER( lwc.work_category_code ) = :worktype ';
      }

      $typicaleduWhere = '';
      if (isset($params['typicaledu']) && strlen($params['typicaledu'])) {
        $binds[':typicaledu'] = strtolower($params['typicaledu']);
        $typicaleduWhere = ' AND voed.education_category_id <= :typicaledu ';
      }

      // when a zipcode is passed in there is a chance the career does not have any wage data in
      // that zipcode.  So we need to union to get all careers and then do some logic in the php
      // after the resultset is returned to make sure we pull the correct number of careers to send back 
      if (isset($params['zipcode']) && strlen($params['zipcode'])) {
        $sql = " SELECT DISTINCT o.display_title AS title, o.onetcode AS onetcode, o.detailed_description AS description, o.video_link AS video_link,
                        lwc.work_category_code AS worktype, voed.education_category_id AS typical_edu_id, vec.education_category_name AS typical_edu_text,
                        'metro_data' AS wage_category, g.areaname AS area_name, IF(ratetype = 1, 'Hourly', 'Annually') AS rate_type, pct10 AS wage_pct_10, pct25 AS wage_pct_25, 
                        median AS wage_median, pct75 AS wage_pct_75, pct90 AS wage_pct_90,
                        ( SELECT laytitle FROM onetsoc_laytitle ol WHERE ol.onetcode = o.onetcode $laytitleSubSql LIMIT 1 ) as laytitle    
                 FROM vcn_occupation o
                 JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode $typicaleduWhere
                 JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id
                 JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry 
                 JOIN vcn_lookup_work_category lwc ON oxi.work_category_id = lwc.work_category_id $worktypeWhere
                 JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
                 JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode 
                 JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode
                 JOIN zipxarea AS zxa ON wo.stfips = zxa.stfips AND wo.area = zxa.area AND zxa.zipcode = :zipcode
                 JOIN geog AS g ON zxa.stfips = g.stfips AND zxa.area = g.area
                 $laytitleWhere 
                 GROUP BY onetcode, rate_type 
                 UNION
                 SELECT o.display_title AS title, o.onetcode AS onetcode, o.detailed_description AS description, o.video_link AS video_link,
                        lwc.work_category_code AS worktype, voed.education_category_id AS typical_edu_id, vec.education_category_name AS typical_edu_text,
                        NULL AS wage_category, NULL AS area_name, NULL AS rate_type, NULL AS wage_pct_10, NULL AS wage_pct_25, 
                        NULL AS wage_median, NULL AS wage_pct_75, NULL AS wage_pct_90,
                        ( SELECT laytitle FROM onetsoc_laytitle ol WHERE ol.onetcode = o.onetcode $laytitleSubSql LIMIT 1 ) as laytitle    
                 FROM vcn_occupation o
                 JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode $typicaleduWhere
                 JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id
                 JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry
                 JOIN vcn_lookup_work_category lwc ON oxi.work_category_id = lwc.work_category_id $worktypeWhere
                 $laytitleWhere 
                 GROUP BY onetcode, rate_type 
        				 ORDER BY title, ISNULL(wage_category), rate_type";

        $binds[':zipcode'] = $params['zipcode'];

      } else {				
        $sql = " SELECT DISTINCT o.display_title AS title, o.onetcode AS onetcode, o.detailed_description AS description, o.video_link AS video_link,
                        lwc.work_category_code AS worktype, voed.education_category_id AS typical_edu_id, vec.education_category_name AS typical_edu_text,
                        'national_data' AS wage_category, 'United States' AS area_name, IF(ratetype = 1, 'Hourly', 'Annually') AS rate_type, pct10 AS wage_pct_10, pct25 AS wage_pct_25,
                        median AS wage_median, pct75 AS wage_pct_75, pct90 AS wage_pct_90,
                        ( SELECT laytitle FROM onetsoc_laytitle ol WHERE ol.onetcode = o.onetcode $laytitleSubSql LIMIT 1 ) as laytitle
                 FROM vcn_occupation o
                 JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode $typicaleduWhere 
                 JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id
                 JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry
                 JOIN vcn_lookup_work_category lwc ON oxi.work_category_id = lwc.work_category_id $worktypeWhere
                 JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
                 JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
                 JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND wo.stfips = '00'
                 $laytitleWhere 
                 GROUP BY onetcode, rate_type 
        				 ORDER BY title, wage_category, area_name, rate_type ";
      }
      
      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array();

      $i = -1;
      $prevOnetcode = '';

      // There are multiple records returned per onetcode due to the wages so we are wrapping
      // the wages in an array and making sure we attach that array to a single onetcode item
      foreach ($result as $row) {
        $wageDataExists = true;
        if ($prevOnetcode != $row['onetcode']) {
          $i++;

          $description = $row['description'];
          
          // return a shortened description of 100 words unless fulldesc param is passed
          if (!isset($params['fulldesc']) || $params['fulldesc'] != 'true') {
            $words = explode(" ", $row['description']);
            $description = implode(" ", array_splice($words, 0, 100));
          }
          
          $data[$i] = array(
            'title' => $row['title'], 
            'onetcode' => $row['onetcode'],
            'shortdesc' => $description,
            'worktype' => $row['worktype'],
            'typical_edu_id' => $row['typical_edu_id'],
            'typical_edu_text' => $row['typical_edu_text'],
            'laytitle' => $row['laytitle'],
            'videolink' => trim($row['video_link']),
          );

          // if the first record of the group of onetcodes has a NULL value for rate_type
          // then it means that onetcode doesn't have any rate information 
          if (!isset($row['rate_type'])) {
            $wageDataExists = false;
          }

          $prevOnetcode = $row['onetcode'];
        }

        $wageArr = null;

        if ($wageDataExists && isset($row['rate_type'])) {
          $wageArr = array(
              'wage_category' => $row['wage_category'],
              'rate_type' => $row['rate_type'],
              'wage_pct_10' => $row['wage_pct_10'],
              'wage_pct_25' => $row['wage_pct_25'],
              'wage_median' => $row['wage_median'],
              'wage_pct_75' => $row['wage_pct_75'],
              'wage_pct_90' => $row['wage_pct_90'],
          );
        }

        $data[$i]['wages'][] = $wageArr;
      }

      $this->setResult($data);
      
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
			
		return $this->result;
	}
	
	public function getCareer($params) {
	
		$requiredParams = array('industry', 'onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT o.display_title AS title, o.onetcode AS onetcode, olwc.work_category_code AS worktype,
                      voed.education_category_id AS eduid, vec.education_category_name AS edutext,
                      vec.education_level AS edulevel, o.detailed_description AS description,
                      o.preceding_career AS preceding_career_onetcode, o2.display_title AS preceding_career_title
               FROM vcn_occupation o
               JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry
               JOIN vcn_lookup_work_category olwc ON oxi.work_category_id = olwc.work_category_id
               JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode
               JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id
               LEFT JOIN vcn_occupation o2 ON o.preceding_career = o2.onetcode
               WHERE o.onetcode = :onetcode";

      $binds = array(
          ':industry' => $params['industry'],
          ':onetcode' => $params['onetcode'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array();

      foreach ($result as $row) {
        $data[] = array(
            'title' => $row['title'],
            'onetcode' => $row['onetcode'],
            'worktype' => $row['worktype'],
            'eduid' => $row['eduid'],
            'edutext' => $row['edutext'],
            'edulevel' => $row['edulevel'],
        	'description' => $row['description'],
          'preceding_career_onetcode' => $row['preceding_career_onetcode'],
          'preceding_career_title' => $row['preceding_career_title'],
        );
      }

      $this->setResult($data);
      
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
	}
	
  public function getSimilarCareers($params) {
		
		$requiredParams = array('industry', 'onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $binds = array();
      $binds[':industry'] = $params['industry'];
      $binds[':onetcode'] = $params['onetcode'];

      $limit = 3;
      if (isset($params['limit'])) {
        $limit = $params['limit'];
      }
      
      // when a zipcode is passed in there is a chance the career does not have any wage data in
      // that zipcode.  So we need to union to get all careers and then do some logic in the php
      // after the resultset is returned to make sure we pull the correct number of careers to send back 
      if (isset($params['zipcode']) && strlen($params['zipcode'])) {
        $sql = " SELECT o.display_title AS title, o.onetcode AS onetcode, 
                        voed.education_category_id AS typical_edu_id, vec.education_category_name AS typical_edu_text,
                        pct10 AS wage_pct_10, pct25 AS wage_pct_25, median AS wage_median, pct75 AS wage_pct_75, pct90 AS wage_pct_90
                 FROM related_occupation_data AS rod
                 JOIN vcn_occupation AS o ON rod.relatedonet = o.onetcode
                 JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode
                 JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id
                 JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry
                 JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
                 JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
                 JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND wo.ratetype = 4
                 JOIN zipxarea AS zxa ON wo.stfips = zxa.stfips AND wo.area = zxa.area AND zxa.zipcode = :zipcode
                 JOIN geog AS g ON zxa.stfips = g.stfips AND zxa.area = g.area
                 WHERE rod.onetcode = :onetcode
                 ORDER BY 1
                 LIMIT $limit ";

        $binds[':zipcode'] = $params['zipcode'];

      } else {				
        $sql = " SELECT o.display_title AS title, o.onetcode AS onetcode, 
                        voed.education_category_id AS typical_edu_id, vec.education_category_name AS typical_edu_text,
                        pct10 AS wage_pct_10, pct25 AS wage_pct_25, median AS wage_median, pct75 AS wage_pct_75, pct90 AS wage_pct_90
                 FROM related_occupation_data AS rod
                 JOIN vcn_occupation AS o ON rod.relatedonet = o.onetcode
                 JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode
                 JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id
                 JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry
                 JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
                 JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
                 JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND wo.stfips = '00' AND wo.ratetype = 4
                 WHERE rod.onetcode = :onetcode
                 ORDER BY 1
                 LIMIT $limit ";
      }

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array();

      foreach ($result as $row) {
        $data[] = array(
          'title' => $row['title'], 
          'onetcode' => $row['onetcode'],
          'typical_edu_id' => $row['typical_edu_id'],
          'typical_edu_text' => $row['typical_edu_text'],
          'wage_pct_10' => $row['wage_pct_10'],
          'wage_pct_25' => $row['wage_pct_25'],
          'wage_median' => $row['wage_median'],
          'wage_pct_75' => $row['wage_pct_75'],
          'wage_pct_90' => $row['wage_pct_90'],
        );
      }

      $this->setResult($data);
      
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
			
		return $this->result;
	}
	
	
	public function getCareerRequirements($params) {
		
		$requiredParams = array('onetcode', 'industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "SELECT ";
			
			$columns = "vcn_occupation.onetcode AS onetcode,
									vcn_occupation.display_title AS title, 
					                vcn_occupation.career_image_yn AS career_image_yn,
									vcn_occupation.career_ladder_yn AS career_ladder_yn,
									vcn_occupation.physical_requirement AS physical_requirement,
									vcn_occupation.physical_requirement_url AS physical_requirement_url,
                  					vcn_occupation.physical_requirement_url_flag AS physical_requirement_url_flag,
									vcn_occupation.health_requirement AS health_requirement,
									vcn_occupation.physical_health_requirements AS physical_health_requirement,
									vcn_occupation.nationwide_legal_requirement_desc AS nationwide_legal_requirement_desc,
									vcn_occupation.nationwide_legal_requirement_url AS nationwide_legal_requirement_url,
                  					vcn_occupation.nationwide_legal_requirement_url_flag AS nationwide_legal_requirement_url_flag,
									vcn_occupation.academic_requirement AS academic_requirement,
                  					vcn_occupation.video_link AS video_link,
									vcn_occupation.day_in_life AS day_in_life,
									vcn_occupation.day_in_life_url AS day_in_life_url,
                  					vcn_occupation.day_in_life_url_flag AS day_in_life_url_flag,
									vcn_occupation.detailed_description AS detailed_description,
									vcn_occupation_2.display_title AS preceding_career_title,
									vcn_occupation.preceding_career AS preceding_career_onetcode,
                  					vcn_occupation.edu_graph_src_desc AS edu_graph_src_desc,
									vcn_occupation.minimum_education_category_id AS minimum_education_category_id,
									vcn_edu_category.education_category_name AS typical_education,
									vcn_edu_category1.education_category_name AS min_education_name ";
			
			if (isset($params['state'])) {
				$columns .= ",vcn_occupation_legal_requirement.legal_requirement AS legal_requirement,
											vcn_occupation_legal_requirement.absolute_prohibitions AS legal_absolute_prohibitions, 
											vcn_occupation_legal_requirement.generic_requirements AS legal_generic_requirements, 
											vcn_occupation_legal_requirement.associated_url AS legal_associated_url, 
                      						vcn_occupation_legal_requirement.associated_url_flag AS legal_associated_url_flag,
											vcn_occupation_legal_requirement.health_issues AS legal_health_issues ";
			}
			
			$from = " FROM vcn_occupation
							LEFT JOIN vw_vcn_onet_education_distribution ON vcn_occupation.onetcode = vw_vcn_onet_education_distribution.onetcode 
							LEFT JOIN vcn_occupation vcn_occupation_2 ON vcn_occupation.preceding_career = vcn_occupation_2.onetcode 
							LEFT JOIN vcn_edu_category ON vw_vcn_onet_education_distribution.education_category_id = vcn_edu_category.education_category_id 
							LEFT JOIN vcn_edu_category AS vcn_edu_category1 ON vcn_occupation.minimum_education_category_id = vcn_edu_category1.education_category_id ";
			
      if (!isset($params['ignore_industry'])) {
        $from .= " JOIN vcn_occupation_industry ON vcn_occupation.onetcode = vcn_occupation_industry.onetcode AND vcn_occupation_industry.industry_id = :industry ";
      }
      
			if (isset($params['state'])) {
				$from .= " LEFT JOIN vcn_occupation_legal_requirement ON vcn_occupation.onetcode = vcn_occupation_legal_requirement.onetcode AND vcn_occupation_legal_requirement.state = :state ";
			}
			
			$where = " WHERE vcn_occupation.onetcode = :onetcode ";
			
			$sql = $sql.$columns.$from.$where;

			$stmt = $db->prepare($sql);
			$stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
      
      if (!isset($params['ignore_industry'])) {
        $stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
      }
      
			if (isset($params['state'])) {
				$stmt->bindParam(':state', $params['state'], PDO::PARAM_STR);
			}

			$stmt->execute();
			$result = $stmt->fetchAll();
			
			$data = array();
			
			foreach ($result as $row) {
				$cols_array = array(
					'title' => $row['title'],
					'onetcode' => $row['onetcode'],
         			'career_image_yn' => $row['career_image_yn'],
					'career_ladder_yn' => $row['career_ladder_yn'],
					'physical_requirement' => $row['physical_requirement'],
					'physical_requirement_url' => $row['physical_requirement_url'],
          'physical_requirement_url_flag' => $row['physical_requirement_url_flag'],
					'health_requirement' => $row['health_requirement'],
						'physical_health_requirement' => $row['physical_health_requirement'],
						'nationwide_legal_requirement_desc' => $row['nationwide_legal_requirement_desc'],
						'nationwide_legal_requirement_url' => $row['nationwide_legal_requirement_url'],
						'nationwide_legal_requirement_url_flag' => $row['nationwide_legal_requirement_url_flag'],
					'academic_requirement' => $row['academic_requirement'],
          'videolink' => $row['video_link'],
					'typical_education' => $row['typical_education'],
					'day_in_life' => $row['day_in_life'],
					'day_in_life_url' => $row['day_in_life_url'],
          'day_in_life_url_flag' => $row['day_in_life_url_flag'],
					'detailed_description' => $row['detailed_description'],
					'preceding_career_title' => $row['preceding_career_title'],
					'preceding_career_onetcode' => $row['preceding_career_onetcode'],
          'edu_graph_src_desc' => $row['edu_graph_src_desc'],
					'min_education_id' => $row['minimum_education_category_id'],
					'min_education_name' => $row['min_education_name'],
				);
				if (isset($params['state'])) {
					$cols_array['legal_requirement'] = $row['legal_requirement'];
					$cols_array['legal_absolute_prohibitions'] = $row['legal_absolute_prohibitions'];
					$cols_array['legal_generic_requirements'] = $row['legal_generic_requirements'];
					$cols_array['legal_associated_url'] = $row['legal_associated_url'];
          $cols_array['legal_associated_url_flag'] = $row['legal_associated_url_flag'];
					$cols_array['legal_health_issues'] = $row['legal_health_issues'];
				}
				$data[] = $cols_array;
			}
			
			$this->setResult($data);
			
		} catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
			
		return $this->result;
		
	}
	
	public function getCareerInterviewData($params) {
		
		$requiredParams = array('onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
				
			$db = Resources_PdoMysql::getConnection();
				
			$sql = " SELECT onetcode, interview_url_description, interview_url, interview_url_flag, interview_type 
               FROM vcn_occupation_interview 
               WHERE onetcode = :onetcode";
				
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			$data = array('career_interview' => $result);
				
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
			
		return $this->result;
		
		
	}
	
	public function getCareerListByMinEducation($params) {
	
		$requiredParams = array('industry', 'mineducation');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
				
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT DISTINCT v.onetcode AS onetcode, v.display_title AS displaytitle, v.minimum_education_category_id as minedu 
					    FROM vcn_occupation v 
					    LEFT JOIN vcn_occupation_industry v2 ON v.onetcode = v2.onetcode 
					    WHERE v.active_yn = 'Y' AND v2.industry_id = :industry AND v.minimum_education_category_id <= :mineducation ";	

			if (isset($params['order'])) {
				$sql .= " ORDER BY " . $params['order'];
			
				if (isset($params['direction'])) {
					$sql .= " " . $params['direction'];
				}
			}
				
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':mineducation', $params['mineducation'], PDO::PARAM_INT);
			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);					
				
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			$data = array();
				
			foreach ($result as $row) {
				$cols_array = array(
						'displaytitle' => $row['displaytitle'],
						'onetcode' => $row['onetcode'],
						'minedu' => $row['minedu'],						
				);				
				$data[] = $cols_array;
			}
				
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
			
		return $this->result;
	
	}
  
	public function getCareerListByJobs($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT distinct(o.onetcode) AS onetcode, o.display_title AS displaytitle, i.aopent as rate
					FROM vcn_occupation o 
					LEFT JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode
					LEFT JOIN socxonet s ON o.onetcode = s.onetcode 
					LEFT JOIN matxsoc m ON s.soccode = m.soccode 
					LEFT JOIN iomatrix i ON m.matoccode = i.matoccode AND ((i.stfips = '00' AND matincode = '000001')) 
					WHERE o.active_yn = 'Y' AND oxi.industry_id = :industry ";
	
			if (isset($params['order'])) {
				$sql .= " ORDER BY " . $params['order'];
					
				if (isset($params['direction'])) {
					$sql .= " " . $params['direction'];
				}
			}
			
			if (isset($params['limit'])) {
				$sql .= " limit " . $params['limit'];
			}
	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
	
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			$data = array();
	
			foreach ($result as $row) {
				$cols_array = array(
						'displaytitle' => $row['displaytitle'],
						'onetcode' => $row['onetcode'],
						'rate' => $row['rate'],
				);
				$data[] = $cols_array;
			}
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
			
		return $this->result;
	
	}
	
	public function getCareerListByGrowth($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT distinct(o.onetcode) AS onetcode, o.display_title AS displaytitle, i.pchg as growth, ROUND(i.pchg, 0) as growthdisplay
					FROM vcn_occupation o 
					LEFT JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode 
					LEFT JOIN socxonet s ON o.onetcode = s.onetcode 
					LEFT JOIN matxsoc m ON s.soccode = m.soccode 
					LEFT JOIN iomatrix i ON m.matoccode = i.matoccode AND ((i.stfips = '00' AND matincode = '000001'))
					WHERE o.active_yn = 'Y' AND oxi.industry_id = :industry ";
	
			if (isset($params['order'])) {
				$sql .= " ORDER BY " . $params['order'];
					
				if (isset($params['direction'])) {
					$sql .= " " . $params['direction'];
				}
			}
				
			if (isset($params['limit'])) {
				$sql .= " limit " . $params['limit'];
			}
	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
	
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			$data = array();
	
			foreach ($result as $row) {
				$cols_array = array(
						'displaytitle' => $row['displaytitle'],
						'onetcode' => $row['onetcode'],
						'growth' => $row['growth'],
						'growthdisplay' => $row['growthdisplay'],
				);
				$data[] = $cols_array;
			}
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
			
		return $this->result;
	
	}
	
	public function getCareerListByPay($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT o.onetcode AS onetcode, o.display_title AS displaytitle, wo.median AS median , ROUND(wo.median, 0) AS mediandisplay
              FROM vcn_occupation o
              LEFT JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode
              JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
              JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
              JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND wo.stfips = '00' AND ratetype = 1
              WHERE o.active_yn = 'Y' AND oxi.industry_id = :industry ";

			if (isset($params['order'])) {
				$sql .= " ORDER BY " . $params['order'];
					
				if (isset($params['direction'])) {
					$sql .= " " . $params['direction'];
				}
			}
				
			if (isset($params['limit'])) {
				$sql .= " limit " . $params['limit'];
			}
	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
	
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			$data = array();
	
			foreach ($result as $row) {
				$cols_array = array(
						'displaytitle' => $row['displaytitle'],
						'onetcode' => $row['onetcode'],
						'median' => $row['median'],
						'mediandisplay' => $row['mediandisplay'],
				);
				$data[] = $cols_array;
			}
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
			
		return $this->result;
	
	}
	
	// New REST call for career grid
	
	public function getCareersDataCareergrid($params) {
		
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "SELECT ";
			
			//this variable should be used in the count query instead of $columns
			$count_column = "COUNT(o.onetcode) AS count_careers ";
			
			$filter_by_work_type = FALSE;
			$filter_by_education_level = FALSE;
			$filter_by_search_term = FALSE;
			
			if ((isset($params['work_type'])) && ($params['work_type'] != '0')) {
				$filter_by_work_type = TRUE;
			}
				
			if ((isset($params['education_level'])) && ($params['education_level'] != '0')) {
				$filter_by_education_level = TRUE;
			}
			
			if (isset($params['search_term'])) {
				$filter_by_search_term = TRUE;
			}
			
			$work_type_filter_clause = '';
			$education_level_filter = '';
			if (($filter_by_work_type) && ($filter_by_education_level)) {
				$work_type_filter_clause = ' AND lwc.work_category_code = :work_type ';
				$education_level_filter = ' AND vec.education_category_id <= :education_level ';
			} else if (($filter_by_education_level) && !($filter_by_work_type)) {
				$education_level_filter = ' AND vec.education_category_id <= :education_level ';
			} else if (($filter_by_work_type) && !($filter_by_education_level)) {
				$work_type_filter_clause = ' AND lwc.work_category_code = :work_type ';
			}
			
			$search_term_filter_clause = '';
			$laytitle_where_clause  = ' WHERE ol.onetcode = o.onetcode ';
			
			if ($filter_by_search_term) {
				$laytitle_where_clause .= ' AND ';
				
				$search_terms = explode(' ', $params['search_term']);
				$i = 0;
				$search_term_bind_array = array();
				foreach($search_terms as $search_term) {
					$search_term_bind_array[] = array('bind_key' => ':search_keyword_'.$i, 'bind_value' => '%'.$search_term.'%');
					$i++;
				}
				
				$search_term_filter_clause = ' AND ('; // enclosing bracket 1
				
				$i = 0; $num_records = count($search_term_bind_array);
				foreach($search_term_bind_array as $and_clause) {
					if ($i < ($num_records-1)) {
						$search_term_filter_clause .= ' LOWER(o.display_title) LIKE '.$and_clause['bind_key'].' AND ';
						$laytitle_where_clause .= ' LOWER(laytitle) LIKE '.$and_clause['bind_key'].' AND ';
					} else {
						$search_term_filter_clause .= ' LOWER(o.display_title) LIKE '.$and_clause['bind_key'].' ';
						$laytitle_where_clause .= ' LOWER(laytitle) LIKE '.$and_clause['bind_key'].' ';
					}
					$i++;
				}
				
				$search_term_filter_clause .= ' OR o.onetcode IN ( SELECT DISTINCT onetcode FROM onetsoc_laytitle WHERE '; // enclosing bracket 2
				$i = 0;
				foreach($search_term_bind_array as $and_clause) {
				if ($i < ($num_records-1)) {
						$search_term_filter_clause .= ' LOWER(laytitle) LIKE '.$and_clause['bind_key'].' AND ';
					} else {
						$search_term_filter_clause .= ' LOWER(laytitle) LIKE '.$and_clause['bind_key'].' ';
					}
					$i++;
				}
				$search_term_filter_clause .= ')'; // closing enclosing bracket 2
				
				$search_term_filter_clause .= ')'; // closing enclosing bracket 1
				
			}
			
			$columns = " o.display_title AS title,
						      CAST(GROUP_CONCAT(IF(ratetype = 4, IF(pct25 = 999999, NULL, pct25), NULL)) AS UNSIGNED INTEGER) AS pct25_annual,
									CAST(GROUP_CONCAT(IF(ratetype = 1, IF(pct25 = 9999.99, NULL, pct25), NULL)) AS DECIMAL(4,2)) AS pct25_hourly,
						      CAST(GROUP_CONCAT(IF(ratetype = 4, IF(pct75 = 999999, NULL, pct75), NULL)) AS UNSIGNED INTEGER) AS pct75_annual,
						      CAST(GROUP_CONCAT(IF(ratetype = 1, IF(pct75 = 9999.99, NULL, pct75), NULL)) AS DECIMAL(4,2)) AS pct75_hourly,
									voed.education_category_id AS education_category_id, o.onetcode AS onetcode,
									SUBSTRING_INDEX(o.detailed_description, ' ', 20) AS detailed_description,vec.education_category_name AS education_category_name,
						      o.video_link AS video_link,( SELECT laytitle FROM onetsoc_laytitle ol ".$laytitle_where_clause." LIMIT 1 ) as laytitle, 
						      o.career_ladder_yn AS career_ladder_yn ";
			
			
			
			if (isset($params['zipcode'])) {
				$from = " FROM vcn_occupation o
                JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry $search_term_filter_clause
								JOIN vcn_lookup_work_category lwc ON oxi.work_category_id = lwc.work_category_id $work_type_filter_clause  
                JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode
								JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id".$education_level_filter."
								LEFT JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
								LEFT JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
								LEFT JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND (wo.stfips, wo.area) = (SELECT stfips, area FROM zipxarea WHERE zipcode = :zipcode)";
			} else {
				$from = " FROM vcn_occupation o
                JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry $search_term_filter_clause
                JOIN vcn_lookup_work_category lwc ON oxi.work_category_id = lwc.work_category_id $work_type_filter_clause
                JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode
                JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id".$education_level_filter."
                LEFT JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode 
                LEFT JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode 
                LEFT JOIN wage_occ wo ON wo.occcode = sxsw.socwage AND wo.stfips = '00' ";			
			}
			
			$group_by = " GROUP BY onetcode ";
			
			//query to get the total number of records without any filters for pagination
			$sql_count = "SELECT COUNT(a.onetcode) AS count_careers FROM (SELECT o.onetcode AS onetcode ".$from.$group_by.") a ";
			$stmt_count = $db->prepare($sql_count);
			$stmt_count->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
			if ($filter_by_work_type) {
				$stmt_count->bindParam(':work_type', $params['work_type'], PDO::PARAM_STR);
			}
			if ($filter_by_education_level) {
				$stmt_count->bindParam(':education_level', $params['education_level'], PDO::PARAM_INT);
			}
			if ($filter_by_search_term) {
				foreach($search_term_bind_array as $bind_params) {
					$stmt_count->bindParam($bind_params['bind_key'], $bind_params['bind_value'], PDO::PARAM_STR);
				}
			}
			if (isset($params['zipcode'])) {
				$stmt_count->bindParam(':zipcode', $params['zipcode'], PDO::PARAM_INT);
			}
			$stmt_count->execute();
			$number_of_rows = $stmt_count->fetchColumn();
			
			$data = array();
			if($number_of_rows > 0) {
				
				$sql .= $columns.$from.$group_by;
					
				if (isset($params['is_dataTable']) && $params['is_dataTable']) {
					$datatableUtil = Resources_DatatableUtilities::getDatatableCommon($params);
					$sortDir = $datatableUtil['sSortDir'];
					$sortDir_1 = $datatableUtil['sSortDir_1'];
					$orderByCol = $datatableUtil['sOrderColumn'];
					$orderByCol_1 = $datatableUtil['sOrderColumn_1'];
					$limitIndex = $datatableUtil['iDisplayStart'];
					$displayLength = $datatableUtil['iDisplayLength'];
					$column_display_order_datatable = $datatableUtil['column_display_order_datatable'];
					if ($orderByCol == $orderByCol_1) {
						$sql .= " ORDER BY $column_display_order_datatable[$orderByCol] $sortDir";
					} else {
						$sql .= " ORDER BY $column_display_order_datatable[$orderByCol] $sortDir,$column_display_order_datatable[$orderByCol_1] $sortDir_1";
					}
					$sql .= " LIMIT $limitIndex, $displayLength";
				}
				
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
				if ($filter_by_work_type) {
					$stmt->bindParam(':work_type', $params['work_type'], PDO::PARAM_STR);
				}
				if ($filter_by_education_level) {
					$stmt->bindParam(':education_level', $params['education_level'], PDO::PARAM_INT);
				}
				if ($filter_by_search_term) {
					foreach($search_term_bind_array as $bind_params) {
						$stmt->bindParam($bind_params['bind_key'], $bind_params['bind_value'], PDO::PARAM_STR);
					}
				}
				if (isset($params['zipcode'])) {
					$stmt->bindParam(':zipcode', $params['zipcode'], PDO::PARAM_INT);
				}
				$stmt->execute();
				$result = $stmt->fetchAll();
				
				foreach($result as $row) {
					$row['detailed_description'] = strip_tags($row['detailed_description']).'...';
					if (isset($params['zipcode'])) {
						$row['zipcode'] = $params['zipcode'];
					} else {
						$row['zipcode'] = NULL;
					}
					$data['result'][] = $row;
				}
			}
      
			$data['num_rows'] = $number_of_rows;
			$this->setResult($data);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function getCareersByMinEducationAndZipcode($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
				
			$db = Resources_PdoMysql::getConnection();

			$limit = 5;
			if (isset($params['limit'])) {
				$limit = $params['limit'];
			}
			
			$sql = "SELECT o.display_title AS title, 
							CAST(GROUP_CONCAT(IF(ratetype = 4, IF(pct25 = 999999, NULL, pct25), NULL)) AS UNSIGNED INTEGER) AS pct25_annual,
							CAST(GROUP_CONCAT(IF(ratetype = 4, IF(pct75 = 999999, NULL, pct75), NULL)) AS UNSIGNED INTEGER) AS pct75_annual, 					
							o.onetcode AS onetcode
					FROM vcn_occupation o 
					LEFT JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode  
					LEFT JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode 
					LEFT JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode 
					LEFT JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND (wo.stfips, wo.area) = (SELECT stfips, area FROM zipxarea WHERE zipcode = :zipcode) 
					WHERE o.active_yn = 'Y' AND o.minimum_education_category_id <= :education_level AND oxi.industry_id = :industry
					GROUP BY onetcode ORDER BY pct25_annual DESC,title ASC LIMIT 0, $limit ";
							 
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
			$stmt->bindParam(':education_level', $params['education_level'], PDO::PARAM_INT);
			$stmt->bindParam(':zipcode', $params['zipcode'], PDO::PARAM_INT);
								
			$stmt->execute();
			$result = $stmt->fetchAll();
			$this->setResult($result);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;	
	}
	
	
}
 