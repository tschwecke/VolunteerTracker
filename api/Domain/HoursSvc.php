<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';
require_once 'Util/Config.php';

class HoursSvc {

	protected static $schoolYearStartDate;

	public function getById($hourId) {
		$results = Dal::executeQuery("Hours_Select_ByPK", $hourId);
		if(count($results) == 0) {
			$hour = null;
		}
		else {
			$hour = new DomainObject('Hours', $results[0]);
		}

		return $hour;
	}

	public function getByVolunteerId($volunteerId) {
		$results = Dal::executeQuery("Hours_Select_ByVolunteer_PK", $volunteerId, $this->getSchoolYearStartDate());
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('Hours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function getByFamilyId($familyId) {
		$results = Dal::executeQuery("Hours_Select_ByFamilyId", $familyId, $this->getSchoolYearStartDate());
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('Hours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function getByStatus($status) {
		$results = Dal::executeQuery("Hours_Select_ByStatus", $status, $this->getSchoolYearStartDate());
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('Hours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function getApprovedTotals() {
		$results = Dal::executeQuery("Hours_Select_ApprovedTotals", $this->getSchoolYearStartDate());
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('TotalHours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function save($hours) {

		$hours->classroom = $hours->classroom . "";

		if($hours->id > 0) {
			//Update
			Dal::execute('Hours_Update_ByPK', $hours->id,
                                                    $hours->volunteerId,
                                                    $hours->interestAreaId,
                                                    $hours->date,
                                                    $hours->nbrOfHours,
                                                    $hours->description,
                                                    $hours->status,
                                                    $hours->classroom);
		}
		else {
			//Insert
		        $results = Dal::executeQuery('Hours_Insert', $hours->volunteerId,
                                                    $hours->interestAreaId,
                                                    $hours->date,
                                                    $hours->nbrOfHours,
                                                    $hours->description,
                                                    $hours->status,
                                                    $hours->classroom);

			$hours->id = $results[0]['NewId'];			
		}
	}

	private function getSchoolYearStartDate() {
		if(is_null($schoolYearStartDate)) {
			$schoolYearStartDate = Config::get('SCHOOL_YEAR_START_DATE');
		}

		return $schoolYearStartDate;
	}
}
