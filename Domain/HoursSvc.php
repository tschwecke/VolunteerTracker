<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class HoursSvc {

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
		$results = Dal::executeQuery("Hours_Select_ByVolunteer_PK", $volunteerId);
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('Hours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function getByFamilyId($familyId) {
		$results = Dal::executeQuery("Hours_Select_ByFamilyId", $familyId);
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('Hours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function getByStatus($status) {
		$results = Dal::executeQuery("Hours_Select_ByStatus", $status);
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('Hours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function getApprovedTotals() {
		$results = Dal::executeQuery("Hours_Select_ApprovedTotals");
		$hours = array();
		for($i=0; $i<count($results); $i++) {
			$hour = new DomainObject('TotalHours', $results[$i]);
			array_push($hours, $hour);
		}

		return $hours;
	}

	public function save($hours) {

		if($hours->id > 0) {
			//Update
			Dal::execute('Hours_Update_ByPK', $hours->id,
                                                    $hours->volunteerId,
                                                    $hours->interestAreaId,
                                                    $hours->date,
                                                    $hours->nbrOfHours,
                                                    $hours->description,
                                                    $hours->status);
		}
		else {
			//Insert
		        $results = Dal::executeQuery('Hours_Insert', $hours->volunteerId,
                                                    $hours->interestAreaId,
                                                    $hours->date,
                                                    $hours->nbrOfHours,
                                                    $hours->description,
                                                    $hours->status);

			$hours->id = $results[0]['NewId'];			
		}
	}

}
/*

       public virtual Hours GetById(int hoursId)
        {
            Hours hour = new Hours();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Hours_Select_ByPK", new OdbcParameter("hours_PK", hoursId));

            while (reader.Read())
            {
                hour.Id = reader.GetInt32(0);
                hour.VolunteerId = reader.GetInt32(1);
                hour.InterestAreaId = reader.GetInt32(2);
                hour.Date = reader.GetDateTime(3);
                hour.NbrOfHours = reader.GetDecimal(4);
                hour.Description = reader.GetString(5);
                hour.Status = reader.GetString(6);
            }

            return hour;
        }
        
        public virtual IList<Hours> GetByVolunteerId(int volunteerId)
        {
            IList<Hours> hours = new List<Hours>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Hours_Select_ByVolunteer_PK", new OdbcParameter("volunteer_PK", volunteerId));

            while (reader.Read())
            {
                Hours hour = new Hours();

                hour.Id = reader.GetInt32(0);
                hour.VolunteerId = reader.GetInt32(1);
                hour.InterestAreaId = reader.GetInt32(2);
                hour.Date = reader.GetDateTime(3);
                hour.NbrOfHours = reader.GetDecimal(4);
                hour.Description = reader.GetString(5);
                hour.Status = reader.GetString(6);

                hours.Add(hour);
            }

            return hours;
        }


        public virtual IList<Hours> GetByFamilyId(int familyId)
        {
            IList<Hours> hours = new List<Hours>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Hours_Select_ByFamilyId", new OdbcParameter("familyId", familyId));

            while (reader.Read())
            {
                Hours hour = new Hours();

                hour.Id = reader.GetInt32(0);
                hour.VolunteerId = reader.GetInt32(1);
                hour.InterestAreaId = reader.GetInt32(2);
                hour.Date = reader.GetDateTime(3);
                hour.NbrOfHours = reader.GetDecimal(4);
                hour.Description = reader.GetString(5);
                hour.Status = reader.GetString(6);

                hours.Add(hour);
            }

            return hours;
        }

        public virtual IList<Hours> GetByStatus(string status)
        {
            IList<Hours> hours = new List<Hours>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Hours_Select_ByStatus", new OdbcParameter("status", status));

            while (reader.Read())
            {
                Hours hour = new Hours();

                hour.Id = reader.GetInt32(0);
                hour.VolunteerId = reader.GetInt32(1);
                hour.InterestAreaId = reader.GetInt32(2);
                hour.Date = reader.GetDateTime(3);
                hour.NbrOfHours = reader.GetDecimal(4);
                hour.Description = reader.GetString(5);
                hour.Status = reader.GetString(6);

                hours.Add(hour);
            }

            return hours;
        }
        
        public virtual IList<TotalHours> GetApprovedTotals()
        {
            IList<TotalHours> allTotalHorus = new List<TotalHours>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Hours_Select_ApprovedTotals");

            while (reader.Read())
            {
                TotalHours totalHours = new TotalHours();

                totalHours.Id = reader.GetInt32(0);
                totalHours.Hours = reader.GetInt32(1);

                allTotalHorus.Add(totalHours);
            }

            return allTotalHorus;
        }

        public virtual void Save(Hours hours)
        {
            Dal dal = new Dal();

            if (hours.Id > 0)
            {
                //Update
                dal.Execute("Hours_Update_ByPK", new OdbcParameter("hours_PK", hours.Id),
                                                    new OdbcParameter("volunteer_PK", hours.VolunteerId),
                                                    new OdbcParameter("interestArea_PK", hours.InterestAreaId),
                                                    new OdbcParameter("hoursDate", hours.Date),
                                                    new OdbcParameter("nbrHours", hours.NbrOfHours),
                                                    new OdbcParameter("description", hours.Description),
                                                    new OdbcParameter("status", hours.Status));
            }
            else
            {
                //Insert
                int id = dal.Execute("Hours_Insert", new OdbcParameter("volunteer_PK", hours.VolunteerId),
                                                    new OdbcParameter("interestArea_PK", hours.InterestAreaId),
                                                    new OdbcParameter("hoursDate", hours.Date),
                                                    new OdbcParameter("nbrHours", hours.NbrOfHours),
                                                    new OdbcParameter("description", hours.Description),
                                                    new OdbcParameter("status", hours.Status));
                hours.Id = id;
            }
        }
*/
