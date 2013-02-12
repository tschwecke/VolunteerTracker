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

/*
        public virtual IList<Right> GetByRoleId(int roleId)
        {
            IList<Right> rights = new List<Right>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Right_Select_ByRole_PK", new OdbcParameter("rolePk", roleId));

            while (reader.Read())
            {
                Right right = new Right();
                right.Id = reader.GetInt32(0);
                right.Code = reader.GetString(1);

                rights.Add(right);
            }

            return rights;
        }

        public virtual IList<Right> GetByVolunteerId(int volunteerId)
        {
            IList<Right> rights = new List<Right>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("Right_Select_ByVolunteer_PK", new OdbcParameter("volunteerPk", volunteerId));

            while (reader.Read())
            {
                Right right = new Right();
                right.Id = reader.GetInt32(0);
                right.Code = reader.GetString(1);

                rights.Add(right);
            }

            return rights;
        }
*/

