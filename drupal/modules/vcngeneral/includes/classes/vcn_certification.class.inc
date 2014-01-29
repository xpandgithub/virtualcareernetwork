<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
class vcnCertification  {

	const TYPE_ADVANCED = 'B';
	const TYPE_COMMON = 'A';  
	const TYPE_SPECIALTY = 'C';
	const TYPE_PRODUCT_EQUIP = 'D';
	const TYPE_SKILL = 'E';
	
	public $description = '';
	public $id = '';
	public $name = '';
	public $organization;
	public $orgName = '';
	public $typeId = '';
	public $typeName = '';
	public $url = '';
	
	public function  __construct( $id=null, $name=null, $description=null, $typeId=null, $organization=null, $url=null ) {
		
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->typeId = $typeId;
		$this->url = $url;
		$this->organization = $organization;
		
	}
	
	public function getTypeName() {
	
    $typeName = '';
    
		// Type=A => in VCN we call this COMMON but in COS the data comes to us as CORE
		if ( $this->typeId == $this::TYPE_ADVANCED ) {
			$typeName = 'Advanced';
		} else if ( $this->typeId == $this::TYPE_COMMON ) {
			$typeName = 'Common';
		} else if ( $this->typeId == $this::TYPE_SPECIALTY ) {
			$typeName = 'Specialty';
		}
	
		return $typeName;
	
	}
	
	// this method exists so that we can sort off of the type name
	public function setTypeName() {
	
    $typeName = '';
        
		// Type=A => in VCN we call this COMMON but in COS the data comes to us as CORE
		if ( $this->typeId == $this::TYPE_ADVANCED ) {
			$typeName = 'Advanced';
		} else if ( $this->typeId == $this::TYPE_COMMON ) {
			$typeName = 'Common';
		} else if ( $this->typeId == $this::TYPE_SPECIALTY ) {
			$typeName = 'Specialty';
		}
		
		$this->typeName = $typeName;
		
	}
	
	// this method exists so we can sort off of the organization name
	public function setOrgName() {

		if ( $this->organization ) {
			
			$this->orgName = $this->organization->name;
		
		}
		
	}
	
	public function toXml( &$data ) {

		$data->addChild( 'certid', $this->id );
		$data->addChild( 'certname', htmlspecialchars($this->name) );
		$data->addChild( 'url', htmlspecialchars($this->url) );
		$data->addChild( 'certdescription', htmlspecialchars($this->description) );
		$data->addChild( 'certtypename', $this->getTypeName() );
		
		$org = $data->addChild( 'certorg' );
		$org->addChild( 'orgid', $this->organization->id );
		$org->addChild( 'orgname', htmlspecialchars($this->organization->name) );
		$org->addChild( 'orgaddress', htmlspecialchars($this->organization->addressString) );
		$org->addChild( 'orgwebpag', htmlspecialchars($this->organization->url) );
		
	}
}

?>
