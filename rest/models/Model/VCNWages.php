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
class VCN_Model_VCNWages extends VCN_Model_Base_VCNBase {
	
	public function getWageData($params) {
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "SELECT 'United States' AS areaname, o.onetcode AS onetcode, occcode, 'national_data' AS wage_category, periodyear, ratetype, pct10, pct25, median, pct75, pct90
              FROM vcn_occupation o
              JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
              JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
              JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND wo.stfips = '00'
              WHERE o.onetcode = :onetcode";

      $binds = array(':onetcode' => $params['onetcode']);

      if (isset($params['zipcode'])) {
        $sql .= " UNION
                SELECT vmz.state AS areaname, o.onetcode AS onetcode, occcode, 'state_data' AS wage_category, periodyear, ratetype, pct10, pct25, median, pct75, pct90
                FROM vcn_occupation o
                JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode
                JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode
                JOIN wage_occ AS wo ON sxsw.socwage = wo.occcode AND wo.areatype = '01'
                JOIN zipxarea AS zxa ON wo.stfips = zxa.stfips AND zxa.zipcode = :zipcode
                LEFT JOIN vcn_master_zipcode vmz ON zxa.zipcode = vmz.zip
                WHERE o.onetcode = :onetcode
                UNION
                SELECT geog_zipx.areaname AS areaname, zip_wages.onetcode AS onetcode, zip_wages.occcode AS occcode, 'metro_data' AS wage_category, 
                zip_wages.periodyear AS periodyear, zip_wages.ratetype AS ratetype, zip_wages.pct10 AS pct10, zip_wages.pct25 AS pct25, 
                zip_wages.median AS median, zip_wages.pct75 AS pct75, zip_wages.pct90 AS pct90 
                FROM
                (SELECT g.areaname AS areaname, zx.zipcode AS zipcode 
                FROM zipxarea zx JOIN geog g ON zx.stfips = g.stfips AND zx.area = g.area WHERE zx.zipcode = :zipcode) geog_zipx 
                LEFT JOIN (SELECT zx.stfips AS stfips, zx.area AS area, zipcode, o.onetcode AS onetcode, occcode, periodyear, ratetype, pct10, pct25, median, pct75, pct90 FROM zipxarea zx 
                LEFT JOIN wage_occ wo ON wo.stfips = zx.stfips AND wo.area = zx.area 
                LEFT JOIN socxsocwage sxsw ON sxsw.socwage = wo.occcode 
                LEFT JOIN socxonet sxo ON sxo.soccode = sxsw.soccode 
                LEFT JOIN vcn_occupation o ON o.onetcode = sxo.onetcode WHERE o.onetcode = :onetcode AND zx.zipcode = :zipcode) zip_wages ON
                geog_zipx.zipcode = zip_wages.zipcode ORDER BY onetcode, wage_category, ratetype";

        $binds = array(':onetcode' => $params['onetcode'], ':zipcode' => $params['zipcode']);
      }

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);
      $result = $stmt->fetchAll();

      $data = array();
      
      foreach ($result as $row) {
        $data[$row['wage_category']][] = $row;
      }

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
		
		return $this->result;
	}

	
}
 