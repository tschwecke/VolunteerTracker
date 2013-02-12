<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/AvailabilitySvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class AvailabilityController extends BaseController {

	public function getByVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Availability", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new AvailabilitySvc();
		$availabilities = $svc->getByVolunteerId($volunteerId);

		$this->sendResponse(200, $availabilities);
	}

	public function setForVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		$candidateAvailabilities = $this->deserializeRequestBody('Availability');
//		$candidateAvailabilities = array_unique($candidateAvailabilities);

		$svc = new AvailabilitySvc();
		$currentAvailabilities = $svc->getByVolunteerId($volunteerId);

		$addedAvailabilities = $this->inAButNotB($candidateAvailabilities, $currentAvailabilities);
		if(count($addedAvailabilities) > 0) {
			if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "Availability", $volunteerId)) {
				$this->sendResponse(403);				
				return;
			}
		}

		$removedAvailabilities = $this->inAButNotB($currentAvailabilities, $candidateAvailabilities);
		if(count($removedAvailabilities) > 0) {
			if (!$authorizationMgr->hasRight($authenticatedUserId, "Delete", "Availability", $volunteerId)) {
				$this->sendResponse(403);				
				return;
			}
		}

		$availabilitySvc = new AvailabilitySvc();
		foreach($addedAvailabilities as $addedAvailability) {
			$addedAvailability->volunteerId = $volunteerId;
			$availabilitySvc->save($addedAvailability);
		}

		foreach($removedAvailabilities as $removedAvailability) {
			$availabilitySvc->delete($removedAvailability);
		}

		$this->sendResponse(200);
	}

	private function inAButNotB($a, $b) {
		$result = array();

		foreach($a as $a_value) {
			$found = false;

			foreach($b as $b_value) {
				if($a_value->dayOfWeek == $b_value->dayOfWeek 
					&& $a_value->timeOfDay == $b_value->timeOfDay) {
					$found = true;
				}
			}

			if($found == false) {
				array_push($result, $a_value);
			}
		}

		return $result;
	}



}
/*
        [RequiresAuthentication]
        public virtual ActionResult GetByVolunteerId(int volunteerId)
        {
            if (!HasRight("Read", "Availability", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            IList<Availability> availabilities = getAvailabilitiesByVolunteerId(volunteerId);

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(availabilities);

            return new ServiceResult { StatusCode = 200, Content = json };
        }

        [RequiresAuthentication]
        public virtual ActionResult SetForVolunteerId(int volunteerId)
        {
            SerializationMgr serializationMgr = new SerializationMgr();
            IList<Availability> newAvailabilities = serializationMgr.Deserialize<IList<Availability>>(HttpContext.Request.InputStream);
            IList<Availability> currentAvailabilities = getAvailabilitiesByVolunteerId(volunteerId);

            newAvailabilities = removeDuplicates(newAvailabilities);
            IList<Availability> addedAvailabilities = inAButNotB(newAvailabilities, currentAvailabilities);
            if (addedAvailabilities.Count > 0)
            {
                if (!HasRight("Create", "Availability", volunteerId))
                    return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };
            }

            IList<Availability> removedAvailabilities = inAButNotB(currentAvailabilities, newAvailabilities);
            if (removedAvailabilities.Count > 0)
            {
                if (!HasRight("Delete", "Availability", volunteerId))
                    return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };
            }


            AvailabilitySvc availabilitySvc = new AvailabilitySvc();
            foreach (Availability availability in addedAvailabilities)
            {
                availability.VolunteerId = volunteerId;
                availabilitySvc.Save(availability);
            }
            foreach (Availability availability in removedAvailabilities)
            {
                availabilitySvc.Delete(availability);
            }

            return new ServiceResult { StatusCode = 200 };
        }


        private IList<Availability> getAvailabilitiesByVolunteerId(int volunteerId)
        {
            AvailabilitySvc availabilitySvc = new AvailabilitySvc();
            IList<Availability> availabilities = availabilitySvc.GetByVolunteerId(volunteerId);

            return availabilities;
        }

        protected virtual List<Availability> removeDuplicates(IList<Availability> availabilities)
        {
            IDictionary<string, Availability> uniqueAvailabilities = new Dictionary<string, Availability>();

            foreach (Availability availability in availabilities)
            {
                string key = availability.DayOfWeek + availability.TimeOfDay;
                if (!uniqueAvailabilities.ContainsKey(key))
                {
                    uniqueAvailabilities.Add(key, availability);
                }
            }

            return uniqueAvailabilities.Values.ToList<Availability>();
        }

        protected virtual IList<Availability> inAButNotB(IList<Availability> a, 
                                                         IList<Availability> b)
        {
            IList<Availability> result = new List<Availability>();

            foreach(Availability newAvailability in a)
            {
                bool match = false;
                foreach (Availability currentAvailability in b)
                {
                    if (newAvailability.DayOfWeek == currentAvailability.DayOfWeek
                        && newAvailability.TimeOfDay == currentAvailability.TimeOfDay)
                    {
                        match = true;
                        break;
                    }
                }

                if (!match)
                {
                    result.Add(newAvailability);
                }

            }

            return result;
        }
*/
