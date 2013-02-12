<?php
require_once 'Controllers/AccessTokenController.php';
require_once 'Controllers/AvailabilityController.php';
require_once 'Controllers/HoursController.php';
require_once 'Controllers/InterestAreaController.php';
require_once 'Controllers/InterestController.php';
require_once 'Controllers/ProfileController.php';
require_once 'Controllers/RightController.php';
require_once 'Controllers/SelectedInterestController.php';
require_once 'Controllers/VolunteerController.php';
require_once 'Middleware/AuthenticationFilter.php';

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->add(new AuthenticationFilter());

$res = $app->response();
$res['Content-Type'] = 'application/json';

//Access Token
$app->post('/services/accessToken', function() {
	$controller = new AccessTokenController();
	$controller->getWithEmailAndPassword();
});

//Volunteer
$app->get('/services/volunteers/:volunteerId', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->getById($volunteerId);
});

$app->get('/services/volunteers', function() {
	$controller = new VolunteerController();
	$controller->getAll();
});

$app->post('/services/volunteers', function() {
	$controller = new VolunteerController();
	$controller->create();
});

$app->put('/services/volunteers/:volunteerId', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->update($volunteerId);
});

$app->put('/services/volunteers/:volunteerId/role', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->updateRole($volunteerId);
});

//Profile
$app->get('/services/volunteers/:volunteerId/profile', function($volunteerId) {
	$controller = new ProfileController();
	$controller->getByVolunteerId($volunteerId);
});

$app->post('/services/volunteers/:volunteerId/profile', function($volunteerId) {
	$controller = new ProfileController();
	$controller->create($volunteerId);
});

$app->put('/services/volunteers/:volunteerId/profile', function($volunteerId) {
	$controller = new ProfileController();
	$controller->update($volunteerId);
});

//Interest Areas

$app->get('/services/interestAreas', function() {
	$controller = new InterestAreaController();
	$controller->getAll();
});

$app->post('/services/interestAreas', function() {
	$controller = new InterestAreaController();
	$controller->create();
});

$app->put('/services/interestAreas/:id', function($id) {
	$controller = new InterestAreaController();
	$controller->update($id);
});


//Interests

$app->get('/services/volunteers/:volunteerId/interests', function($volunteerId) {
	$controller = new InterestController();
	$controller->getByVolunteerId($volunteerId);
});

$app->post('/services/volunteers/:volunteerId/interests', function($volunteerId) {
	$controller = new InterestController();
	$controller->create($volunteerId);
});

$app->put('/services/volunteers/:volunteerId/interests/:interestId', function($volunteerId, $interestId) {
	$controller = new InterestController();
	$controller->create($volunteerId);
});


//Selected Interests

$app->get('/services/volunteers/:volunteerId/selectedInterests', function($volunteerId) {
	$controller = new SelectedInterestController();
	$controller->getByVolunteerId($volunteerId);
});

$app->put('/services/volunteers/:volunteerId/selectedInterests', function($volunteerId) {
	$controller = new SelectedInterestController();
	$controller->setForVolunteerId($volunteerId);
});


//Availability

$app->get('/services/volunteers/:volunteerId/availability', function($volunteerId) {
	$controller = new AvailabilityController();
	$controller->getByVolunteerId($volunteerId);
});

$app->put('/services/volunteers/:volunteerId/availability', function($volunteerId) {
	$controller = new AvailabilityController();
	$controller->setForVolunteerId($volunteerId);
});


//Hours

$app->get('/services/volunteers/:volunteerId/hours', function($volunteerId) {
	$controller = new HoursController();
	$controller->getByVolunteerId($volunteerId);
});

$app->get('/services/hours/pending', function() {
	$controller = new HoursController();
	$controller->getPendingHours();
});

$app->get('/services/hours/approvedTotals', function() {
	$controller = new HoursController();
	$controller->getApprovedTotals();
});

$app->post('/services/volunteers/:volunteerId/hours', function($volunteerId) {
	$controller = new HoursController();
	$controller->create($volunteerId);
});

$app->put('/services/volunteers/:volunteerId/hours/:hoursId/status', function($volunteerId, $hoursId) {
	$controller = new HoursController();
	$controller->updateStatus($hoursId);
});


//Rights

$app->get('/services/roles/:roleId/rights', function($roleId) {
	$controller = new RightController();
	$controller->getByRoleId($roleId);
});


$app->run();

/*
           routes.MapRoute(
                "GetAccessToken",
                "service.aspx/accessToken",
                new { controller = "AccessToken", action = "GetWithEmailAndPassword" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "CreateVolunteer",
                "services.aspx/volunteers",
                new { controller = "Volunteer", action = "Create" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "UpdateVolunteer",
                "services.aspx/volunteers/{volunteerId}",
                new { controller = "Volunteer", action = "Update" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "UpdateVolunteerRole",
                "services.aspx/volunteers/{volunteerId}/role",
                new { controller = "Volunteer", action = "UpdateRole" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "GetVolunteer",
                "services.aspx/volunteers/{volunteerId}",
                new { controller = "Volunteer", action = "GetById" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "GetVolunteers",
                "services.aspx/volunteers",
                new { controller = "Volunteer", action = "GetAll" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "GetProfileByVolunteerId",
                "services.aspx/volunteers/{volunteerId}/profile",
                new { controller = "Profile", action = "GetByVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("GET") });
            
            routes.MapRoute(
                "CreateProfile",
                "services.aspx/volunteers/{volunteerId}/profile",
                new { controller = "Profile", action = "Create" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "UpdateProfile",
                "services.aspx/volunteers/{volunteerId}/profile",
                new { controller = "Profile", action = "Update" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "GetAllInterestAreas",
                "services.aspx/interestAreas",
                new { controller = "InterestArea", action = "GetAll" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "CreateInterestArea",
                "services.aspx/interestAreas",
                new { controller = "InterestArea", action = "Create" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "UpdateInterestArea",
                "services.aspx/interestAreas/{id}",
                new { controller = "InterestArea", action = "Update" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "GetInterestsByVolulnteerId",
                "services.aspx/volunteers/{volunteerId}/interests",
                new { controller = "Interest", action = "GetByVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "CreateInterest",
                "service.aspx/volunteer/{volunteerId}/interests",
                new { controller = "Interest", action = "Create" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "UpdateInterest",
                "services.aspx/volunteers/{volunteerId}/interests/{interestId}",
                new { controller = "Interest", action = "Update" },
                new { httpMethod = new HttpMethodConstraint("PUT") });


            routes.MapRoute(
                "GetSelectedInterestsByVolunteerId",
                "services.aspx/volunteers/{volunteerId}/selectedInterests",
                new { controller = "SelectedInterest", action = "GetByVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "SetSelectedInterestsByVolunteerId",
                "services.aspx/volunteers/{volunteerId}/selectedInterests",
                new { controller = "SelectedInterest", action = "SetForVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "GetAllAvailabilitiesByVolunteerId",
                "services.aspx/volunteers/{volunteerId}/availability",
                new { controller = "Availability", action = "GetByVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "SetAvailabilitiesByVolunteerId",
                "services.aspx/volunteers/{volunteerId}/availability",
                new { controller = "Availability", action = "SetForVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "GetAllHoursByVolunteerId",
                "services.aspx/volunteers/{volunteerId}/hours",
                new { controller = "Hours", action = "GetByVolunteerId" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "GetAllPendingHours",
                "services.aspx/hours/pending",
                new { controller = "Hours", action = "GetPendingHours" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "GetApprovedHoursTotals",
                "services.aspx/hours/approvedTotals",
                new { controller = "Hours", action = "GetApprovedTotals" },
                new { httpMethod = new HttpMethodConstraint("GET") });

            routes.MapRoute(
                "CreateHours",
                "services.aspx/volunteers/{volunteerId}/hours",
                new { controller = "Hours", action = "Create" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "UpdateHoursStatus",
                "services.aspx/volunteers/{volunteerId}/hours/{hoursId}/status",
                new { controller = "Hours", action = "UpdateStatus" },
                new { httpMethod = new HttpMethodConstraint("PUT") });

            routes.MapRoute(
                "CreateSchema",
                "services.aspx/createSchema",
                new { controller = "DbSchema", action = "CreateSchema" },
                new { httpMethod = new HttpMethodConstraint("POST") });

            routes.MapRoute(
                "GetRightsByRoleId",
                "services.aspx/roles/{roleId}/rights",
                new { controller = "Right", action = "GetByRoleId" },
                new { httpMethod = new HttpMethodConstraint("GET") });

        }
*/
