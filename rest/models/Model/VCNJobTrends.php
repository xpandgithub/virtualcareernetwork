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
 * Ability Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     waltonr
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNJobTrends extends VCN_Model_Base_VCNBase {
	
	public function getJobTrendsData($params) {
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "SELECT 'national_data' AS area_category, i.pchg AS percent_job_change,i.estemp AS estimated_employment, i.projemp AS projected_employment, i.aopent AS job_openings
              FROM iomatrix i 
              JOIN (SELECT m.matoccode AS matoccode, m.soccode FROM matxsoc m 
              JOIN (SELECT s1.soccode AS soccode, s1.socwage as socwage, s2.onetcode FROM socxsocwage s1 
              JOIN (SELECT soccode, onetcode FROM socxonet WHERE onetcode = :onetcode) s2 ON s1.soccode = s2.soccode) s ON m.soccode = s.soccode) m 
              ON m.matoccode = i.matoccode AND i.stfips = '00' AND i.matincode = '000001'";

      $binds = array(':onetcode' => $params['onetcode']);

      if (isset($params['zipcode'])) {
        $sql .= " UNION 
                  SELECT 'state_data' AS area_category, i.pchg AS percent_job_change, i.estemp AS estimated_employment, i.projemp AS projected_employment, i.aopent AS job_openings 
                  FROM iomatrix i 
                  JOIN (SELECT m.matoccode AS matoccode, m.soccode FROM matxsoc m 
                  JOIN (SELECT s1.soccode AS soccode, s1.socwage as socwage, s2.onetcode FROM socxsocwage s1 
                  JOIN (SELECT soccode, onetcode FROM socxonet WHERE onetcode = :onetcode) s2 ON s1.soccode = s2.soccode) s ON m.soccode = s.soccode) m ON m.matoccode = i.matoccode 
                  JOIN zipxarea z ON i.stfips = z.stfips WHERE z.zipcode = :zipcode AND i.matincode = '000001'";

        $binds = array(':onetcode' => $params['onetcode'], ':zipcode' => $params['zipcode']);
      }

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);
      $result = $stmt->fetchAll();

      $data = array();
      foreach ($result as $row) {
        $row['job_trends'] = $this->jobTrends($row['percent_job_change']);
        $data[$row['area_category']][] = $row;
      }

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
		
		return $this->result;
	}
	
	protected function jobTrends($percent_job_change) {
		
		$percent_job_change = number_format($percent_job_change, 0); //brought over from function jobgrowth from Occupation.php
		$percent_job_change = (float)$percent_job_change;
		
		if ($percent_job_change > 20) {
			$job_trends = "Much faster than average";
		} else if (($percent_job_change <= 20) && ($percent_job_change > 14)) {
			$job_trends = "Faster than average";
		} else if (($percent_job_change <= 14) && ($percent_job_change > 7)) {
			$job_trends = "Average";
		} else if (($percent_job_change <= 7) && ($percent_job_change > 3)) {
			$job_trends = "Slower than average";
		} else if (($percent_job_change <= 3) && ($percent_job_change > -2)) {
			$job_trends = "Little or no change";
		} else if (($percent_job_change <= -2) && ($percent_job_change > -9)) {
			$job_trends = "Decline slowly or moderately";
		} else {
			$job_trends = "Decline rapidly";
		}
		
		return $job_trends;
		
	}
	
	
}
 