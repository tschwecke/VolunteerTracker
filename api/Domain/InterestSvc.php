<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class InterestSvc {

	public function getByVolunteerId($volunteerId) {
		$results = Dal::executeQuery("Interest_Select_ByVolunteer_PK", $volunteerId);
		$interests = array();
		for($i=0; $i<count($results); $i++) {
			$interests = new DomainObject('Interest', $results[$i]);
			array_push($interests, $interest);
		}

		return $interests;
	}

	public function save($volunteerId, $interest) {

		if($interest->id > 0) {
			//Update
			Dal::execute('Interest_Update_ByPK', $interest->id, 
                                                    $volunteerId,
                                                    $interest->interestAreaId);
		}
		else {
			//Insert
		        $results = Dal::executeQuery('Interest_Insert', $volunteerId,
                                                    			$interest->interestAreaId);

			$interest->id = $results[0]['NewId'];			
		}
	}

	public function delete($interest) {
		Dal::execute('Interest_Delete_ByPK', $interest->id);
	}

}