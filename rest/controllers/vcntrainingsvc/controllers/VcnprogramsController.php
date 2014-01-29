<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCNTrainingSvc_VCNProgramsController extends VCN_WebServices {

	public function getProgramsByOnetcodeAction() {

		$model = new VCN_Model_VCNPrograms();
		$data = $model->getPrograms($this->params);
			
		$output = self::getOutput($this->format, $data, 'result');

		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);

	}

  public function getProgramsByUnitidAction() {

		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramsByUnitid($this->params);
			
		$output = self::getOutput($this->format, $data, 'result');

		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);

	}
	
	// Gives Program's Detail based on Programid AND Cipcode
	public function getProgramDetailAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramDetail($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Program's Required Courses based on Programid AND Cipcode
	public function getProgramRequiredCoursesAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramRequiredCourses($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Program's Curriculum Courses based on Programid AND Cipcode
	public function getProgramCurriculumCoursesAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramCurriculumCourses($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Program's Required Education based on Programid
	public function getProgramRequiredEducationAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramRequiredEducation($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Program's Entrance Tests based on Programid
	public function getProgramEntranceTestsAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramEntranceTests($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Gives Program's Accredited based on Programid
	public function getProgramAccreditedAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getProgramAccredited($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}	
	
	// Gives Cipcode list with title
	public function getCipcodeListAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->getCipcodeList($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Delete program
	public function deleteProgramAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->deleteProgram($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Insert/Update program
	public function updateProgramInfoAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->updateProgramInfo($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Insert/Update program's Entrance Tests 
	public function updateProgramEntranceTestsAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->updateProgramEntranceTests($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	// Insert/Update program's Required courses
	public function updateProgramRequiredCoursesAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->updateProgramRequiredCourses($this->params);
			
		$output = self::getOutput( $this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	public function updateProgramCurriculumCoursesAction() {
	
		$model = new VCN_Model_VCNPrograms();
		$data = $model->updateProgramCurriculumCourses($this->params);
			
		$output = self::getOutput($this->format, $data, 'result');
	
		$this->_response->setHeader('Content-Type', $this->format)->setBody($output);
	
	}
	
	
  
}