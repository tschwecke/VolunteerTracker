<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class VolunteerSvc {

	public function getById($id) {
		$results = Dal::executeQuery("Volunteer_Select_ByPK", $id);
		$volunteer = new DomainObject('Volunteer', $results[0]);
		$this->assertValidity($volunteer);

		return $volunteer;
	}

	public function getByEmailAddress($emailAddress) {
		$results = Dal::executeQuery("Volunteer_Select_ByEmailAddress", $emailAddress);

		if(count($results) > 0) {
			$volunteer = new DomainObject('Volunteer', $results[0]);
			$this->assertValidity($volunteer);
		}
		else {
			$volunteer = null;
		}
		return $volunteer;
	}

	public function getByInterestAreaId($interestAreaId) {
		$results = Dal::executeQuery("Volunteer_Select_ByInterestArea_PK", $interestAreaId);

		$volunteers = array();
		for($i=0; $i<count($results); $i++) {
			$volunteer = new DomainObject('Volunteer', $results[$i]);
			$this->assertValidity($volunteer);
			array_push($volunteers, $volunteer);
		}

		return $volunteers;
	}

	public function getAll() {
		$results = Dal::executeQuery("Volunteer_Select_All");
		$volunteers = array();
		for($i=0; $i<count($results); $i++) {
			$volunteer = new DomainObject('Volunteer', $results[$i]);
			$this->assertValidity($volunteer);
			array_push($volunteers, $volunteer);
		}

		return $volunteers;
	}

	public function save($volunteer) {
		if($volunteer->id > 0) {
			Dal::execute("Volunteer_Update_ByPK", $volunteer->id,
								$volunteer->firstName,
								$volunteer->lastName,
								$volunteer->emailAddress,
								$volunteer->passwordHash,
								$volunteer->salt,
								$volunteer->familyId,
								$volunteer->primaryPhoneNbr,
								$volunteer->roleId);
		}
		else {
			$results = Dal::executeQuery("Volunteer_Insert", $volunteer->firstName,
				                            $volunteer->lastName,
				                            $volunteer->emailAddress,
				                            $volunteer->passwordHash,
				                            $volunteer->salt,
				                            $volunteer->familyId,
				                            $volunteer->primaryPhoneNbr,
				                            $volunteer->roleId);

			$volunteer->id = $results[0]['NewId'];
		}

        }


	protected function assertValidity($volunteer) {
		if(!property_exists($volunteer, 'id')) {
			echo 'Volunteer validation failed: property id missing';
			//exit();			
		}
		if(!property_exists($volunteer, 'firstName')) {
			echo 'Volunteer validation failed: property firstName missing';
			exit();			
		}
		if(!property_exists($volunteer, 'lastName')) {
			echo 'Volunteer validation failed: property lastName missing';
			exit();			
		}
		if(!property_exists($volunteer, 'familyId')) {
			echo 'Volunteer validation failed: property familyId missing';
			exit();			
		}
	}
}
