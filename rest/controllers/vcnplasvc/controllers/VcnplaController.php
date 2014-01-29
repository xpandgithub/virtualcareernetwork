<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCNPLASvc_VCNPLAController extends VCN_WebServices {
	
	public function listMilitaryCoursesAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->getMilitaryCoursesList($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	
	//gets list of the organizations that provide training courses or placement exams
	public function listCoursesExamsOrganizationAction() {
		
		$model = new VCN_Model_VCNPLA();
		$data = $model->getCoursesExamsOrganizationList($this->params);
		
		$output = self::getOutput( $this->format, $data, 'result', $this->params['type'].'_data');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function listTrainingOrExamCoursesAction() {
		
		$model = new VCN_Model_VCNPLA();
		$data = $model->getPLATrainingOrExamCoursesList($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getCollegeCourseDetailsAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->getCollegeCourseDetails($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getMilitaryCourseDetailsAction() {
		
		$model = new VCN_Model_VCNPLA();
		$data = $model->getMilitaryCourseDetails($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}
	
	public function getProfessionalTrainingCourseDetailsAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->getProfessionalTrainingCourseDetails($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getNationalExamDetailsAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->getNationalExamDetails($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getCreditCoursesByUseridAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->getCreditCoursesByUserid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function deleteCreditCoursesByUserCourseidAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->deleteCreditCoursesByUserCourseid($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function savePlaCoursesToCmaAction() {
		
		$model = new VCN_Model_VCNPLA();
		$data = $model->savePlaCoursesToCma($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
		
	}
	
	//action to save the College Credit information to CMA
	public function savePlaCollegeCreditToCmaAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->savePlaCollegeCreditToCma($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
		
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function getUserCourseByCourseInfoAction() {
	
		$model = new VCN_Model_VCNPLA();
		$data = $model->getUserCourseByCourseInfo($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}

}