<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class KioskCheckInSvc {

	public function getById($id) {
		$results = Dal::executeQuery("KioskCheckIn_Select_ByPK", $id);
		$checkIn = new DomainObject('KioskCheckIn', $results[0]);
		$this->assertValidity($checkIn);

		return $checkIn;
	}

	public function getActive() {
		$results = Dal::executeQuery("KioskCheckIn_Select_Active");
		$checkIns = array();
		for($i=0; $i<count($results); $i++) {
			$checkIn = new DomainObject('KioskCheckIn', $results[$i]);
			$this->assertValidity($checkIn);
			array_push($checkIns, $checkIn);
		}

		return $checkIns;
	}

	public function save($checkIn) {

		$checkIn->classroom = $checkIn->classroom . "";

		if($checkIn->id > 0) {
			//Update
			Dal::execute('KioskCheckIn_Update_ByPK',
									$checkIn->id,
									$checkIn->volunteerId,
									$checkIn->interestAreaId,
									$checkIn->classroom,
									$checkIn->checkInTime,
									$checkIn->checkOutTime);
		}
		else {
			$results = Dal::executeQuery("KioskCheckIn_Insert", 
									$checkIn->volunteerId,
									$checkIn->interestAreaId,
									$checkIn->classroom,
									$checkIn->checkInTime);

			$checkIn->id = $results[0]['NewId'];
		}
	}

	protected function assertValidity($checkIn) {
		if(!property_exists($checkIn, 'id')) {
			echo 'Volunteer validation failed: property id missing';
			exit();			
		}
		if(!property_exists($checkIn, 'volunteerId')) {
			echo 'Volunteer validation failed: property volunteerId missing';
			exit();			
		}
		if(!property_exists($checkIn, 'interestAreaId')) {
			echo 'Volunteer validation failed: property interestAreaId missing';
			exit();			
		}
		if(!property_exists($checkIn, 'checkInTime')) {
			echo 'Volunteer validation failed: property checkInTime missing';
			exit();			
		}
	}
}
