<?php
require_once 'Util/Dal.php';

class RightSvc {
	
	public function getByRoleId($roleId) {
		$results = Dal::executeQuery("Right_Select_ByRole_PK", $roleId);
		$rights = array();
		for($i=0; $i<count($results); $i++) {
			$right = new DomainObject('Right', $results[$i]);
			array_push($rights, $right);
		}
		
		return $rights;
	}

	public function getByVolunteerId($volunteerId) {
		$results = Dal::executeQuery("Right_Select_ByVolunteer_PK", $volunteerId);
		$rights = array();
		for($i=0; $i<count($results); $i++) {
			$right = new DomainObject('Right', $results[$i]);
			array_push($rights, $right);
		}
		
		return $rights;
		
	}
}
