<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class SelectedInterestSvc {

	public function getByVolunteerId($volunteerId) {
		$results = Dal::executeQuery("SelectedInterest_Get_ByVolunteer_PK", $volunteerId);
		$selectedInterests = array();
		for($i=0; $i<count($results); $i++) {
			$selectedInterest = new DomainObject('SelectedInterest', $results[$i]);

			//Convert selected to a boolean value.
			$selectedInterest->selected = ($selectedInterest->selected != null);

			//Rename interestId to id
			$selectedInterest->id = $selectedInterest->interestId;
			unset($selectedInterest->interestId);

			//Replace id with a zero if it is null
			if($selectedInterest->id == null) {
				$selectedInterest->id = 0;
			}

			array_push($selectedInterests, $selectedInterest);
		}

		return $selectedInterests;
	}
}