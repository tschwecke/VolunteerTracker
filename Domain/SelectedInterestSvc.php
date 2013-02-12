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
/*
       public virtual IList<SelectedInterest> GetByVolunteerId(int volunteerId)
        {
            IList<SelectedInterest> selectedInterests = new List<SelectedInterest>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("SelectedInterest_Get_ByVolunteer_PK", new OdbcParameter("volunteer_PK", volunteerId));

            while (reader.Read())
            {
                SelectedInterest si = new SelectedInterest();
                si.Id = reader.GetInt32(0);
                si.Name = reader.GetString(1);
                si.SortOrder = reader.GetInt32(2);
                
                //Don't know why, but for some reason odbc and mysql aren't playing nicely with null values.  It would throw an error when
                //I tried to check the column for null, so I'm just going to wrap it in a try catch.
                try
                {
                    si.Selected = (reader.GetInt32(3) > 0);
                    si.InterestId = reader.GetInt32(4);
                }
                catch (Exception)
                {
                    si.Selected = false;
                    si.InterestId = 0;
                }

                selectedInterests.Add(si);
            }

            return selectedInterests;
        }
*/
