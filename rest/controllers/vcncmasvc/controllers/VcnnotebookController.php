<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCNCmaSvc_VCNNotebookController extends VCN_WebServices {	
	
	public function getNotebookItemByItemidAction() {	
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getNotebookItemByItemid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getNotebookItemsAction() {
	
		$this->params['type'] = (isset($this->params['type']) && $this->params['type'] != "") ? strtoupper($this->params['type']) : 'ALL';
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getNotebookItems($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getNotebookCareerItemsAction() {
    
    $this->params['type'] = 'OCCUPATION';
    
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getNotebookItems($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getNotebookProgramItemsAction() {
	
		$this->params['type'] = 'PROGRAM';
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getNotebookItems($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
  public function getNotebookLicenseItemsAction() {
	
    $this->params['type'] = 'LICENSE';
    
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getNotebookItems($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
  
  public function getNotebookCertificationItemsAction() {
	
    $this->params['type'] = 'CERTIFICATE';
    
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getNotebookItems($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
  
	public function getTargetedCareerAction() {
		
		/*for some reason, on certain occassions the "user_id" key is not present in the $params array. so if that is the case, we need to add it to the $params array with a NULL value as it is a required entity, 
		 otherwise the REST call fails and we get an error*/
		
		$params = $this->params;
		if (!array_key_exists('user_id', $params)) {
			$params['user_id'] = NULL;
		}
		
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getTargetedCareer($params);
		
		$output = self::getOutput( $this->format, $data, 'result');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}
	
	public function getNotebookCareersInDetailAction() {
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data  = $model->getNotebookCareersInDetail($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getNotebookProgramsInDetailAction() {
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data  = $model->getNotebookProgramsInDetail($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function saveTargetNotebookItemAction() {
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->saveTargetNotebookItem($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function removeNotebookItemAction() {
				
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->removeNotebookItem($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getTargetedNotebookItemsDetailByUseridAction() { // Learning inventory pdf header
	
		$model = new VCN_Model_VCNCmaNotebook();
		$data = $model->getTargetedNotebookItemsDetailByUserid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
}