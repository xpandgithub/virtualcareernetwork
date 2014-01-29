<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCNProviderSvc_VCNProviderController extends VCN_WebServices {
	
	// Gives provider's basic info including name and contact based on LAT-LONG
	public function getProviderAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProvider($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives provider's basic info including name and contact based on unitid
	public function getProviderByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives provider's basic info based on Programid
	public function getProviderByProgramidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderByProgramid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives provider's detail info based on unitid
	public function getProviderDetailByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderDetailByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's Services based on unitid
	public function getProviderServicesByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderServicesByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's Entrance Tests based on unitid
	public function getProviderEntranceTestsByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderEntranceTestsByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's Entrance Tests based on Programid
	public function getProviderEntranceTestsByProgramidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderEntranceTestsByProgramid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's Required Courses based on unitid
	public function getProviderRequiredCoursesByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderRequiredCoursesByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's Required Courses based on Programid
	public function getProviderRequiredCoursesByProgramidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderRequiredCoursesByProgramid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's Degrees Offered based on unitid
	public function getProviderDegreesOfferedByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderDegreesOfferedByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Provider's FAID Offered based on unitid
	public function getProviderFaidOfferedByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderFaidOfferedByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Updates provider's logo based on unitid
	public function updateProviderLogoByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->updateProviderLogoByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Updates provider's info based on unitid
	public function updateProviderInfoByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->updateProviderInfoByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Updates provider's detail info based on unitid
	public function updateProviderDetailByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->updateProviderDetailByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Updates Provider's Entrance Tests based on unitid
	public function updateProviderEntranceTestsByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->updateProviderEntranceTestsByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Updates Provider's Required Courses based on unitid
	public function updateProviderRequiredCoursesByUnitidAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->updateProviderRequiredCoursesByUnitid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives provider's list
	public function getProviderListAction() {
	
		$model = new VCN_Model_VCNProvider();
		$data = $model->getProviderList($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}

}