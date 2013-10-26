<?php
require_once 'Util/Config.php';
require_once 'Controllers/AccessTokenController.php';
require_once 'Controllers/HoursController.php';
require_once 'Controllers/InterestAreaController.php';
require_once 'Controllers/InterestController.php';
require_once 'Controllers/RightController.php';
require_once 'Controllers/SelectedInterestController.php';
require_once 'Controllers/VolunteerController.php';
require_once 'Middleware/AuthenticationFilter.php';

Config::load('config.ini');

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->add(new AuthenticationFilter());

$res = $app->response();
$res['Content-Type'] = 'application/json';

//Access Token
$app->post('/restservices/accessToken', function() {
	$controller = new AccessTokenController();
	$controller->getWithEmailAndPassword();
});

$app->get('/restservices/volunteers/:volunteerId/accessToken', function($volunteerId) {
	$controller = new AccessTokenController();
	$controller->getByVolunteerId($volunteerId);
});

//Volunteer
$app->get('/restservices/volunteers/:volunteerId', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->getById($volunteerId);
});

$app->get('/restservices/volunteers', function() {
	$controller = new VolunteerController();
	$controller->getAll();
});

$app->post('/restservices/volunteers', function() {
	$controller = new VolunteerController();
	$controller->create();
});

$app->get('/restservices/interestAreas/:interestAreaId/volunteers', function($interestAreaId) {
	$controller = new VolunteerController();
	$controller->getByInterestAreaId($interestAreaId);
});

$app->put('/restservices/volunteers/:volunteerId', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->update($volunteerId);
});

$app->put('/restservices/volunteers/:volunteerId/role', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->updateRole($volunteerId);
});

//Interest Areas

$app->get('/restservices/interestAreas', function() {
	$controller = new InterestAreaController();
	$controller->getAll();
});

$app->post('/restservices/interestAreas', function() {
	$controller = new InterestAreaController();
	$controller->create();
});

$app->put('/restservices/interestAreas/:id', function($id) {
	$controller = new InterestAreaController();
	$controller->update($id);
});


//Interests

$app->get('/restservices/volunteers/:volunteerId/interests', function($volunteerId) {
	$controller = new InterestController();
	$controller->getByVolunteerId($volunteerId);
});

$app->post('/restservices/volunteers/:volunteerId/interests', function($volunteerId) {
	$controller = new InterestController();
	$controller->create($volunteerId);
});

$app->put('/restservices/volunteers/:volunteerId/interests/:interestId', function($volunteerId, $interestId) {
	$controller = new InterestController();
	$controller->create($volunteerId);
});


//Selected Interests

$app->get('/restservices/volunteers/:volunteerId/selectedInterests', function($volunteerId) {
	$controller = new SelectedInterestController();
	$controller->getByVolunteerId($volunteerId);
});

$app->put('/restservices/volunteers/:volunteerId/selectedInterests', function($volunteerId) {
	$controller = new SelectedInterestController();
	$controller->setForVolunteerId($volunteerId);
});

//Hours

$app->get('/restservices/volunteers/:volunteerId/hours', function($volunteerId) {
	$controller = new HoursController();
	$controller->getByVolunteerId($volunteerId);
});

$app->get('/restservices/volunteers/:volunteerId/familyHours', function($volunteerId) {
	$controller = new HoursController();
	$controller->getFamilyHoursByVolunteerId($volunteerId);
});

$app->get('/restservices/hours/approvedTotals', function() {
	$controller = new HoursController();
	$controller->getApprovedTotals();
});

$app->get('/restservices/hours/:status', function($status) {
	$controller = new HoursController();
	$controller->getHoursByStatus($status);
});

$app->post('/restservices/volunteers/:volunteerId/hours', function($volunteerId) {
	$controller = new HoursController();
	$controller->create($volunteerId);
});

$app->put('/restservices/volunteers/:volunteerId/hours/:hoursId/status', function($volunteerId, $hoursId) {
	$controller = new HoursController();
	$controller->updateStatus($hoursId);
});


//Rights

$app->get('/restservices/roles/:roleId/rights', function($roleId) {
	$controller = new RightController();
	$controller->getByRoleId($roleId);
});


$app->run();

