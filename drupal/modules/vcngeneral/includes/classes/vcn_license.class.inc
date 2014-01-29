<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
class vcnLicense  {
	
	public $agency = '';
	public $description = '';
	public $id = '';
	public $title = '';
	public $agencyName = '';
	
	public function  __construct( $id=null, $title=null, $description=null, $agency=null ) {
		
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->agency = $agency;
		
	}
	
	// this method exists so we can sort off of the organization name
	public function setAgencyName() {
	
		if ( $this->agency ) {
				
			$this->agencyName = $this->agency->name;
	
		}
	
	}
	
	public function toXml( &$data ) {
	
		$data->addChild( 'licenseid', $this->id );
		$data->addChild( 'lictitle', htmlspecialchars($this->title) );
		$data->addChild( 'licdesc', htmlspecialchars($this->description) );
	
		$agency = $data->addChild( 'licauth' );
		$agency->addChild( 'name1', htmlspecialchars($this->agency->name) );
		$agency->addChild( 'address1', htmlspecialchars($this->agency->address->address1) );
		$agency->addChild( 'address2', htmlspecialchars($this->agency->address->address2) );
		$agency->addChild( 'city', $this->agency->address->city );
		$agency->addChild( 'st', $this->agency->address->state->getAbbrev() );
		$agency->addChild( 'zip', $this->agency->address->zip );
		$agency->addChild( 'zipext', $this->agency->address->zipExt );
		$agency->addChild( 'telephone', $this->agency->phone );
		$agency->addChild( 'teleext', $this->agency->phoneExt );
		$agency->addChild( 'email', $this->agency->email );
		$agency->addChild( 'url', htmlspecialchars($this->agency->url) );
	
	}
	
}

?>
