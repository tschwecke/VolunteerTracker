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
/*
       public virtual IList<InterestArea> GetAll()
        {
            IList<InterestArea> interestAreas = new List<InterestArea>();
            Dal dal = new Dal();

            DbDataReader reader = dal.ExecuteQuery("InterestArea_Select_All");

            while (reader.Read())
            {
                InterestArea area = new InterestArea();
                area.Id = reader.GetInt32(0);
                area.Name = reader.GetString(1);
                area.SortOrder = reader.GetInt32(2);

                interestAreas.Add(area);
            }

            return interestAreas;
        }

        public virtual void Save(InterestArea interestArea)
        {
            Dal dal = new Dal();

            if (interestArea.Id > 0)
            {
                //Update
                dal.Execute("InterestArea_Update_ByPK", new OdbcParameter("interestArea_PK", interestArea.Id),
                                                        new OdbcParameter("name", interestArea.Name),
                                                        new OdbcParameter("sortOrder", interestArea.SortOrder));
            }
            else
            {
                //Insert
                int id = dal.Execute("InterestArea_Insert", new OdbcParameter("name", interestArea.Name),
                                                            new OdbcParameter("sortOrder", interestArea.SortOrder));
                interestArea.Id = id;
            }
        }
*/
