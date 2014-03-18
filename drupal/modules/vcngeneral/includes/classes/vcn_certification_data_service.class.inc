<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
abstract class vcnCertificationDataService  {

	public static function getCertificationsCount( $onetcode ) {
	
		$certificationsArr = self::getCertificationsByOnetcode( $onetcode, vcnCosWebService::OUTPUT_COUNT );
			
		$count = 0;
		
		if ( is_array( $certificationsArr ) ) {
			
			$count = count( $certificationsArr );
			
		}
		
		return $count;
	
	}
	
	public static function getCertificationsByOnetcode( $onetcode, $outputMode=vcnCosWebService::OUTPUT_XML, $offset=null, $limit=200, $orderBy=null, $direction=null ) {
		
		$cosWS = new vcnCosWebService();
		$cosWS->getCertifications( $onetcode );
		
		$output = '';
		
		if (!isset($cosWS->status->message)) {
			
			$certificationItems = $cosWS->data;
	
			$certificationSuppressedArr = self::getSuppressCertificationIds($onetcode);
			
			$certificationsArr = array();
			
			foreach ( $certificationItems AS $items ) {
			
				$id = trim((string)$items->CERT_ID);

				if (!in_array($id, $certificationSuppressedArr)) {
					if ($outputMode != vcnCosWebService::OUTPUT_COUNT) {
						$certification = self::populate($items);
						$certificationsArr[] = $certification;
					} else {
						$certificationsArr[] = $id;
					}
				}
			}
			
			if ( $outputMode != vcnCosWebService::OUTPUT_COUNT ) {
				$certificationsArr = vcnUtilities::transformArray( $certificationsArr, $offset, $limit, $orderBy, $direction );
			}
			
			$output = $certificationsArr;
			
			if ( $outputMode == vcnCosWebService::OUTPUT_XML ) {
				
				$xml = new SimpleXMLElement( '<root/>' );
				
				$cosWS->addStatusToXml( $xml );
				
				$data = $xml->addChild( 'data' );
				
				foreach ( $certificationsArr AS $certification ) {
				
					if ( $certification ) {
						
						$certifications = $data->addChild( 'certifications' );
							
						$certification->toXml( $certifications );
						
					}
				
				}
				
				$output = $xml;
			}
			
		} else {
		
			// need to log the error
		
		}
				
		return $output;
		
	}
	
	public static function getCertificationById( $certId, $outputMode=vcnCosWebService::OUTPUT_XML ) {
	
		$cosWS = new vcnCosWebService();
		$cosWS->getCertification( $certId );
		
		$output = '';
		
		if (!isset($cosWS->status->message)) {
			
			$certification = self::populate( $cosWS->data );
		
			$output = $certification;
			
			if ( $outputMode == vcnCosWebService::OUTPUT_XML ) {
	
				$xml = new SimpleXMLElement( '<root/>' );
				
				$cosWS->addStatusToXml( $xml );
				
				$data = $xml->addChild( 'data' );
				
				$certifications = $data->addChild( 'certifications' );
				
				$certification->toXml( $certifications );
				
				$output = $xml;
			}
		
		} else {
		
			// need to log the error
		
		}
				
		return $output;
	
	}
	
	static private function getSuppressCertificationIds($onetcode) {
	
		// this function will be used to suppress certifications that we've 
		// defined in VCN db to suppress
		
		$outputArr = array();
	
		$onetcode = mysql_real_escape_string($onetcode);
		
		try {
			
			$params = array('onetcode' => $onetcode);
      $content = vcn_rest_wrapper('vcntrainingsvc', 'vcncertification', 'get-blacklist', $params);

      if ($content && $content->certifications->item) {
        foreach ($content->certifications->item as $certification) {
          $outputArr[] = (string)$certification->certid;
        }
      }
      
		} catch (Exception $e) {
			
		}
		
		return $outputArr;
	
	}
	
	public static function populate( $data ) {
		
		$certification = new vcnCertification();
		
		$certification->id = trim( (string)$data->CERT_ID );
		$certification->name = trim( (string)$data->CERT_NAME );
		$certification->description = trim( (string)$data->CERT_DESCRIPTION );
		$certification->typeId = trim( (string)$data->CERT_TYPE );
		$certification->url = trim( (string)$data->URL );
				
		$organization = new vcnCertificationOrganization();
		$organization->id = trim( (string)$data->ORG_ID );
		$organization->name = trim( (string)$data->ORG_NAME );
		$organization->url = trim( (string)$data->ORG_WEBPAG );
		$organization->addressString = trim( (string)$data->ORG_ADDRES );
		
		$certification->organization = $organization;
		
		// need to manually set these properties because they are dynamic
		$certification->setTypeName();
		$certification->setOrgName();
		
		return $certification;
		
	}
}

?>
