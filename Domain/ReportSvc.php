<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class ReportSvc {

	public function getFamilyHours() {
		$results = Dal::executeQuery("Report_Select_FamilyHours");
		$families = array();
		for($i=0; $i<count($results); $i++) {
			$family = new DomainObject('FamilyHours', $results[$i]);
			array_push($families, $family);
		}

		return $families;
	}

}
