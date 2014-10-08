<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';
require_once 'Util/Config.php';

class ReportSvc {

	public function getFamilyHours() {
		$results = Dal::executeQuery("Report_Select_FamilyHours", $this->getSchoolYearStartDate());
		$families = array();
		for($i=0; $i<count($results); $i++) {
			$family = new DomainObject('FamilyHours', $results[$i]);
			array_push($families, $family);
		}

		return $families;
	}


  private function getSchoolYearStartDate() {
    if(is_null($schoolYearStartDate)) {
      $schoolYearStartDate = Config::get('SCHOOL_YEAR_START_DATE');
    }

    return $schoolYearStartDate;
  }

}
