<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
class vcnUtilities {

	const ORDER_BY_DIRECTION_ASC = 'ASC';
	const ORDER_BY_DIRECTION_DESC = 'DESC';
	
	static $sortKey;
	
	static $orderByPropertyList = array( 'rank' => 'name', 
										 'cert_name' => 'name',
								 		 'cert_type_name' => 'typeName',
								 		 'org_name' => 'orgName',
										 'lictitle' => 'title',
										 'name1' => 'agencyName', );
	
	// this function will be used to transform the certification/license arrays
	// so that we limit the number of the results returned, perform
	// an order by and sort in a direction etc.
	public static function transformArray( $inputArr, $offset=null, $limit=200, $orderBy=null, $direction=null ) {

		$outputArr = $inputArr;
		
		if ( !$offset ) {
			
			$offset = 0;
			
		}
		
		if ( !$limit ) {
			
			$limit = 8;	
			
		}
		
		if ( !$direction ) {
			
			$direction = self::ORDER_BY_DIRECTION_ASC;
			
		}
		
		if ( is_array( $outputArr ) && count( $outputArr ) > 0 ) {
			
			// sort the array
			if ( strlen( $orderBy ) ) {
				
				if ( isset( self::$orderByPropertyList[ $orderBy ] ) ) {
					
					$property = self::$orderByPropertyList[ $orderBy ];
					
					self::sortByProperty( $outputArr, $property, $direction );
				
				}
				
			}
			
			// apply the offset and limit and just return whats needed
			$outputArr = array_slice( $outputArr, $offset, $limit );
		
		}
		
		return $outputArr;
	
	}

	public static function ascSorterByProperty( $a, $b ) {
		
		return strcasecmp( $a->{self::$sortKey}, $b->{self::$sortKey} );
		
	}
	
	public static function descSorterByProperty( $a, $b ) {
	
		return strcasecmp( $b->{self::$sortKey}, $a->{self::$sortKey} );
	
	}
	
	public static function sortByProperty( &$inputArr, $property, $direction ) {
		
		self::$sortKey = $property;
		
		if ( strtoupper( $direction ) == self::ORDER_BY_DIRECTION_ASC ) {
			usort( $inputArr, __CLASS__.'::ascSorterByProperty' );
		} else {
			usort( $inputArr, __CLASS__.'::descSorterByProperty' );
		}
	}
	
}
