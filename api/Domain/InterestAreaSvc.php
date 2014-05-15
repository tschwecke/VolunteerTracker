<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class InterestAreaSvc {

	public function getAll() {
		$results = Dal::executeQuery("InterestArea_Select_All");
		$interestAreas = array();
		for($i=0; $i<count($results); $i++) {
			$interestArea = new DomainObject('InterestArea', $results[$i]);
			array_push($interestAreas, $interestArea);
		}

		return $interestAreas;
	}

	public function save($interestArea) {
		if($interestArea->id > 0) {
			//Update
			Dal::execute("InterestArea_Update_ByPK", $interestArea->id,
                                                        $interestArea->name,
                                                        $interestArea->sortOrder);
		}
		else {
			//Insert
                	$results = Dal::executeQuery("InterestArea_Insert", $interestArea->name,
                                                        		$interestArea->sortOrder);
			$interestArea->id = $results[0]['NewId'];
		}
	}
}