<?php
require_once 'Util/Dal.php';
require_once 'Models/DomainObject.php';

class AvailabilitySvc {

	public function getByVolunteerId($volunteerId) {
		$results = Dal::executeQuery("Availability_Select_ByVolunteer_PK", $volunteerId);
		$availabilities = array();
		for($i=0; $i<count($results); $i++) {
			$availability = new DomainObject('Availability', $results[$i]);
			array_push($availabilities, $availability);
		}

		return $availabilities;
	}

	public function save($availability) {
		if($availability->id > 0) {
			//Update
			Dal::execute('Availability_Update_ByPK', $availability->id, 
				                                     $availability->volunteerId,
				                                     $availability->dayOfWeek,
				                                     $availability->timeOfDay);
		}
		else {
			//Insert
		        $results = Dal::executeQuery('Availability_Insert', $availability->volunteerId,
						                                $availability->dayOfWeek,
						                                $availability->timeOfDay);
			$availability->id = $results[0]['NewId'];			
		}
	}

	public function delete($availability) {
            Dal::execute('Availability_Delete_ByPK', $availability->id);
	}
}
/*
        public virtual IList<Availability> GetByVolunteerId(int volunteerId)
        {
            IList<Availability> availabilities = new List<Availability>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Availability_Select_ByVolunteer_PK", new OdbcParameter("volunteer_PK", volunteerId));

            while (reader.Read())
            {
                Availability availability = new Availability();

                availability.Id = reader.GetInt32(0);
                availability.VolunteerId = reader.GetInt32(1);
                availability.DayOfWeek = reader.GetString(2);
                availability.TimeOfDay = reader.GetString(3);

                availabilities.Add(availability);
            }

            return availabilities;
        }

        public virtual void Save(Availability availability)
        {
            Dal dal = new Dal();

            if (availability.Id > 0)
            {
                //Update
                dal.Execute("Availability_Update_ByPK", new OdbcParameter("availability_PK", availability.Id), 
                                                     new OdbcParameter("volunteer_PK", availability.VolunteerId),
                                                     new OdbcParameter("dayOfWeek", availability.DayOfWeek),
                                                     new OdbcParameter("timeOfDay", availability.TimeOfDay));
            }
            else
            {
                //Insert
                int id = dal.Execute("Availability_Insert", new OdbcParameter("volunteer_PK", availability.VolunteerId),
                                                        new OdbcParameter("dayOfWeek", availability.DayOfWeek),
                                                        new OdbcParameter("timeOfDay", availability.TimeOfDay));
                availability.Id = id;
            }
        }

        public virtual void Delete(Availability availability)
        {
            Dal dal = new Dal();

            dal.Execute("Availability_Delete_ByPK", new OdbcParameter("volunteer_PK", availability.Id));
        }

*/
