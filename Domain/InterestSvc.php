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
/*
 

        public virtual IList<Interest> GetByVolunteerId(int volunteerId)
        {
            IList<Interest> interests = new List<Interest>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Interest_Select_ByVolunteer_PK", new OdbcParameter("", volunteerId));

            while (reader.Read())
            {
                Interest interest = new Interest();

                interest.Id = reader.GetInt32(0);
                interest.VolunteerId = reader.GetInt32(1);
                interest.InterestAreaId = reader.GetInt32(2);

                interests.Add(interest);
            }

            return interests;
        }

        public virtual void Save(Interest interest)
        {
            Dal dal = new Dal();

            if (interest.Id > 0)
            {
                //Update
                dal.Execute("Interest_Update_ByPK", new OdbcParameter("interest_PK", interest.Id), 
                                                    new OdbcParameter("volunteer_PK", interest.VolunteerId),
                                                    new OdbcParameter("interestArea_PK", interest.InterestAreaId));
            }
            else
            {
                //Insert
                int id = dal.Execute("Interest_Insert", new OdbcParameter("volunteer_PK", interest.VolunteerId),
                                                    new OdbcParameter("interestArea_PK", interest.InterestAreaId));
                interest.Id = id;
            }
        }

        public virtual void Delete(Interest interest)
        {
            Dal dal = new Dal();

            dal.Execute("Interest_Delete_ByPK", new OdbcParameter("interest_PK", interest.Id));
        }
*/
