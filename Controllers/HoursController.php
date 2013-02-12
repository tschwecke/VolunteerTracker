<?php
require_once 'Controllers/BaseController.php';
require_once 'Domain/HoursSvc.php';
require_once 'Util/AuthenticationMgr.php';
require_once 'Util/AuthorizationMgr.php';

class HoursController extends BaseController {

	public function getByVolunteerId($volunteerId) {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "Hours", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new HoursSvc();
		$hours = $svc->getByVolunteerId($volunteerId);

		$this->sendResponse(200, $hours);
	}

	public function getPendingHours() {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllHours")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new HoursSvc();
		$hours = $svc->getByStatus('Pending');

		$this->sendResponse(200, $hours);
	}

	public function getApprovedTotals() {
		
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Read", "AllHours")) {
			$this->sendResponse(403);				
			return;
		}

		$svc = new HoursSvc();
		$totals = $svc->getApprovedTotals();

		$this->sendResponse(200, $totals);
	}

	public function create($volunteerId) {
echo time();
var_dump($volunteerId);
		$authenticatedUserId = $this->getAuthenticatedUserId();

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Create", "Hours", $volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$hours = $this->deserializeRequestBody('Hours');
		$hours->volunteerId = $volunteerId;
		$hours->status = 'Pending';
		
		//Convert the date from miliseconds since epoch to a string that mysql can recognize
		$millisecondsSinceEpoch = str_replace('/Date(', '', str_replace(')/', '', $hours->date));
		$hours->date = date("Y-m-d", $millisecondsSinceEpoch / 1000);

		$svc = new HoursSvc();
		$svc->save($hours);

		$this->sendResponse(200, $hours);
	}

	public function updateStatus($hoursId) {

		$authenticatedUserId = $this->getAuthenticatedUserId();

		$svc = new HoursSvc();
		$hours = $svc->getById($hoursId);

		$authorizationMgr = new AuthorizationMgr();
		if (!$authorizationMgr->hasRight($authenticatedUserId, "Update", "Hours", $hours->volunteerId)) {
			$this->sendResponse(403);				
			return;
		}

		$status = $this->getRequestBody();
		$hours->status = $status;

		$svc->save($hours);

		$this->sendResponse(200, $hours);
	}
}


/*
    public class HoursController : ServiceBaseController
    {
        [RequiresAuthentication]
        public virtual ActionResult GetByVolunteerId(int volunteerId)
        {
            if (!HasRight("Read", "Hours", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            HoursSvc hoursSvc = new HoursSvc();
            IList<Hours> hours = hoursSvc.GetByVolunteerId(volunteerId);

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(hours);

            return new ServiceResult { StatusCode = 200, Content = json };
        }

        public virtual ActionResult GetForFamilyByVolunteerId(int volunteerId)
        {
            //IList<Availability> availabilities = getAvailabilitiesByVolunteerId(volunteerId);

            //SerializationMgr serializationMgr = new SerializationMgr();
            //string json = serializationMgr.Serialize(availabilities);

            return new ServiceResult { StatusCode = 200, Content = "" };
        }

        [RequiresAuthentication]
        public virtual ActionResult GetPendingHours()
        {
            if (!HasRight("Read", "AllHours"))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            HoursSvc hoursSvc = new HoursSvc();
            IList<Hours> hours = hoursSvc.GetByStatus("Pending");

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(hours);

            return new ServiceResult { StatusCode = 200, Content = json };
        }

        [RequiresAuthentication]
        public virtual ActionResult GetApprovedTotals()
        {
            if (!HasRight("Read", "AllHours"))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            HoursSvc hoursSvc = new HoursSvc();
            IList<TotalHours> allTotalHours = hoursSvc.GetApprovedTotals();

            SerializationMgr serializationMgr = new SerializationMgr();
            string json = serializationMgr.Serialize(allTotalHours);

            return new ServiceResult { StatusCode = 200, Content = json };
        }

        [RequiresAuthentication]
        public ActionResult Create(int volunteerId)
        {
            if (!HasRight("Create", "Hours", volunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };

            SerializationMgr serializationMgr = new SerializationMgr();
            Hours hours = serializationMgr.Deserialize<Hours>(HttpContext.Request.InputStream);
            hours.VolunteerId = volunteerId;
            hours.Status = "Pending";

            HoursSvc hoursSvc = new HoursSvc();
            hoursSvc.Save(hours);

            string json = serializationMgr.Serialize(hours);
            return new ServiceResult { StatusCode = 200, Content = json };

        }

        [RequiresAuthentication]
        public ActionResult UpdateStatus(int hoursId)
        {
            var status = "";
            using (StreamReader reader = new StreamReader(HttpContext.Request.InputStream))
            {
                status = reader.ReadToEnd();
            }
            SerializationMgr serializationMgr = new SerializationMgr();
            HoursSvc hoursSvc = new HoursSvc();
            Hours hours = hoursSvc.GetById(hoursId);

            if (!HasRight("Update", "Hours", hours.VolunteerId))
                return new ServiceResult { StatusCode = 403, Content = "You are not authorized to perform that operation." };
            
            hours.Status = status;

            hoursSvc.Save(hours);

            string json = serializationMgr.Serialize(hours);
            return new ServiceResult { StatusCode = 200, Content = json };

        }
*/
