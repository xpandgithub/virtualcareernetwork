<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCNCmaSvc_VCNCmaJobscoutsController extends VCN_WebServices {
	
	public function getCmaJobscoutsAction() {
		
		$model = new VCN_Model_VCNCmaJobscouts();
		$data  = $model->getCmaJobscouts($this->params);
	
		$output = self::getOutput($this->format, $data, 'result');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}
	
	public function deleteFromJobscoutsAction() {
	
		$model = new VCN_Model_VCNCmaJobscouts();
		$data = $model->deleteFromJobscouts($this->params);
			
		$output = self::getOutput($this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function updateSubscriptionJobscoutsAction() {
	
		$model = new VCN_Model_VCNCmaJobscouts();
		$data = $model->updateSubscriptionJobscouts($this->params);
			
		$output = self::getOutput($this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function saveJobsearchAction() {
	
		$model = new VCN_Model_VCNCmaJobscouts();
		$data = $model->saveJobSearch($this->params);
			
		$output = self::getOutput($this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
  public function getAllCmaJobscoutsForTodayAction() {
		
		$model = new VCN_Model_VCNCmaJobscouts();
		$data  = $model->getAllCmaJobscoutsForToday($this->params);
	
		$output = self::getOutput($this->format, $data, 'result');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}
  
}	