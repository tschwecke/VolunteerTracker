<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/SelectedInterestSvc.php';
require_once 'Domain/InterestSvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class SelectedInterestController extends BaseController {

	public function getByVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Interests", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new SelectedInterestSvc();
		$interests = $svc->getByVolunteerId($volunteerId);

		$this->sendResponse(200, $interests);
	}

	public function setForVolunteerId($volunteerId) {
		$authenticatedUserId = $this->getAuthenticatedUserId();
		$authorizationMgr = new AuthorizationMgr();
		$candidateSelectedInterests = $this->deserializeRequestBody('SelectedInterest');

		$svc = new SelectedInterestSvc();
		$currentSelectedInterests = $svc->getByVolunteerId($volunteerId);		
		
		$selectedInterests = array_filter($candidateSelectedInterests, function($a) { return $a->selected;});
		$currentInterests = array_filter($currentSelectedInterests, function($a) { return $a->selected;});

		$newSelections = $this->inAButNotB($selectedInterests, $currentInterests);
		if(count($newSelections) > 0) {
			if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "Interests", $volunteerId)) {
				$this->sendResponse(403);				
				return;
			}
		}

		$removedSelections = $this->inAButNotB($currentInterests, $selectedInterests);
		if(count($removedSelections) > 0) {
			if (!$authorizationMgr->hasRight($authenticatedUserId, "Delete", "Interests", $volunteerId)) {
				$this->sendResponse(403);				
				return;
			}
		}

		$interestSvc = new InterestSvc();
		for($i=0; $i<count($newSelections); $i++) {
			$interestSvc->save($volunteerId, $newSelections[$i]);
		}

		for($i=0; $i<count($removedSelections); $i++) {
			$interestSvc->delete($removedSelections[$i]);
		}

		$this->sendResponse(200);
	} 

	protected function inAButNotB($a, $b) {
		$result = array();

		foreach($a as $a_value) {
			$found = false;

			foreach($b as $b_value) {
				if($a_value->interestAreaId === $b_value->interestAreaId) {
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
    public class SelectedInterestController : ServiceBaseController
    {
        [RequiresAuthentication]
        public ActionResult GetByVolunteerId(int volunteerId)
        {
            if (!HasRight("Read", "Interests", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            IList<SelectedInterest> interests = getCurrentSelectedInterests(volunteerId);

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(interests);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult SetForVolunteerId(int volunteerId)
        {
            SerializationMgr serializationMgr = new SerializationMgr();
            IList<SelectedInterest> candidateSelectedInterests = serializationMgr.Deserialize<IList<SelectedInterest>>(HttpContext.Request.InputStream);

            IList<SelectedInterest> candidateCurrentInterests = getCurrentSelectedInterests(volunteerId);

            IEnumerable<SelectedInterest> selectedInterests = candidateSelectedInterests.Where(selectedInterest => selectedInterest.Selected);
            IEnumerable<SelectedInterest> currentInterests = candidateCurrentInterests.Where(selectedInterest => selectedInterest.Selected);

            IEnumerable<SelectedInterest> newSelections = selectedInterests.Where(selectedInterest => !currentInterests.Any(currentInterest => currentInterest.Id == selectedInterest.Id));
            if (newSelections.Count<SelectedInterest>() > 0)
            {
                if (!HasRight("Create", "Interests", volunteerId))
                    return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };
            }
            
            IEnumerable<SelectedInterest> removedSelections = currentInterests.Where(currentInterest => !selectedInterests.Any(selectedInterest => currentInterest.Id == selectedInterest.Id));
            if (removedSelections.Count<SelectedInterest>() > 0)
            {
                if (!HasRight("Delete", "Interests", volunteerId))
                    return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };
            }

            InterestSvc interestSvc = new InterestSvc();
            foreach (SelectedInterest removedSelection in removedSelections)
            {
                Interest interest = new Interest { Id = removedSelection.InterestId};
                interestSvc.Delete(interest);
            }

            foreach (SelectedInterest newSelection in newSelections)
            {
                Interest interest = new Interest { VolunteerId=volunteerId, InterestAreaId=newSelection.Id };
                interestSvc.Save(interest);
            }

            return new ServiceResult { StatusCode = 200 };
        }


        protected virtual IList<SelectedInterest> getCurrentSelectedInterests(int volunteerId)
        {
            SelectedInterestSvc siSvc = new SelectedInterestSvc();
            IList<SelectedInterest> interests = siSvc.GetByVolunteerId(volunteerId);

            return interests;
        }
*/
