<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCNOccupationSvc_VCNCareerController extends VCN_WebServices {
	
	public function listcareersAction() {
		
		$model = new VCN_Model_VCNCareer();
		$data  = $model->listCareers($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'careerdata');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}

	public function searchCareersAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data  = $model->searchCareers($this->params);

		$output = self::getOutput( $this->format, $data, 'result', 'career');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data  = $model->getCareer($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
  public function getSimilarCareersAction() {
		
		$model = new VCN_Model_VCNCareer();
		$data  = $model->getSimilarCareers($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}
	
	public function getCareerRequirementsAction() {
		
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerRequirements($this->params);
		
		$output = self::getOutput( $this->format, $data, 'result', 'career_data');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerInterviewDataAction() {
		
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerInterviewData($this->params);
		
		$output = self::getOutput( $this->format, $data, 'result', 'interview_data');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerListByMinEducationAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerListByMinEducation($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerListByTypicalEducationAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerListByTypicalEducation($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerListByJobsAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerListByJobs($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerListByGrowthAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerListByGrowth($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareerListByPayAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data = $model->getCareerListByPay($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	
	public function getCareersDataCareergridAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data  = $model->getCareersDataCareergrid($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
	public function getCareersByMinEducationAndZipcodeAction() {
	
		$model = new VCN_Model_VCNCareer();
		$data  = $model->getCareersByMinEducationAndZipcode($this->params);
	
		$output = self::getOutput( $this->format, $data, 'result', 'career');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	}
	
}