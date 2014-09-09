<?php
require_once 'Util/Config.php';
require_once 'Controllers/AccessTokenController.php';
require_once 'Controllers/HoursController.php';
require_once 'Controllers/InterestAreaController.php';
require_once 'Controllers/InterestController.php';
require_once 'Controllers/RightController.php';
require_once 'Controllers/SelectedInterestController.php';
require_once 'Controllers/VolunteerController.php';
require_once 'Controllers/KioskCheckInController.php';
require_once 'Controllers/ReportController.php';
require_once 'Controllers/TestController.php';
require_once 'Middleware/AuthenticationFilter.php';

Config::load('config.ini');

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->config('debug', false);
$app->add(new AuthenticationFilter());

$res = $app->response();
$res['Content-Type'] = 'application/json';

$app->error(function (\Exception $e) use ($app) {
		$app = \Slim\Slim::getInstance();
		$res = $app->response();
		$res->status(500);
		$res->body($e);
});

//Access Token
$app->post('/accessToken', function() {
	$controller = new AccessTokenController();
	$controller->getWithEmailAndPassword();
});

$app->get('/volunteers/:volunteerId/accessToken', function($volunteerId) {
	$controller = new AccessTokenController();
	$controller->getByVolunteerId($volunteerId);
});

//Volunteer
$app->get('/volunteers/:volunteerId', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->getById($volunteerId);
});

$app->get('/volunteers', function() {
	$controller = new VolunteerController();
	$controller->getAll();
});

$app->post('/volunteers', function() {
	$controller = new VolunteerController();
	$controller->create();
});

$app->get('/interestAreas/:interestAreaId/volunteers', function($interestAreaId) {
	$controller = new VolunteerController();
	$controller->getByInterestAreaId($interestAreaId);
});

$app->put('/volunteers/:volunteerId', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->update($volunteerId);
});

$app->put('/volunteers/:volunteerId/role', function($volunteerId) {
	$controller = new VolunteerController();
	$controller->updateRole($volunteerId);
});

//Interest Areas

$app->get('/interestAreas', function() {
	$controller = new InterestAreaController();
	$controller->getAll();
});

$app->post('/interestAreas', function() {
	$controller = new InterestAreaController();
	$controller->create();
});

$app->put('/interestAreas/:id', function($id) {
	$controller = new InterestAreaController();
	$controller->update($id);
});


//Interests

$app->get('/volunteers/:volunteerId/interests', function($volunteerId) {
	$controller = new InterestController();
	$controller->getByVolunteerId($volunteerId);
});

$app->post('/volunteers/:volunteerId/interests', function($volunteerId) {
	$controller = new InterestController();
	$controller->create($volunteerId);
});

$app->put('/volunteers/:volunteerId/interests/:interestId', function($volunteerId, $interestId) {
	$controller = new InterestController();
	$controller->create($volunteerId);
});


//Selected Interests

$app->get('/volunteers/:volunteerId/selectedInterests', function($volunteerId) {
	$controller = new SelectedInterestController();
	$controller->getByVolunteerId($volunteerId);
});

$app->put('/volunteers/:volunteerId/selectedInterests', function($volunteerId) {
	$controller = new SelectedInterestController();
	$controller->setForVolunteerId($volunteerId);
});

//Hours

$app->get('/volunteers/:volunteerId/hours', function($volunteerId) {
	$controller = new HoursController();
	$controller->getByVolunteerId($volunteerId);
});

$app->get('/volunteers/:volunteerId/familyHours', function($volunteerId) {
	$controller = new HoursController();
	$controller->getFamilyHoursByVolunteerId($volunteerId);
});

$app->get('/hours/approvedTotals', function() {
	$controller = new HoursController();
	$controller->getApprovedTotals();
});

$app->get('/hours/:status', function($status) {
	$controller = new HoursController();
	$controller->getHoursByStatus($status);
});

$app->post('/volunteers/:volunteerId/hours', function($volunteerId) {
	$controller = new HoursController();
	$controller->create($volunteerId);
});

$app->put('/volunteers/:volunteerId/hours/:hoursId/status', function($volunteerId, $hoursId) {
	$controller = new HoursController();
	$controller->updateStatus($hoursId);
});


//KioskCheckIn
$app->get('/kioskCheckIns/active', function() {
	$controller = new KioskCheckInController();
	$controller->getActive();
});

$app->get('/kioskCheckIns/:kioskCheckInId', function($kioskCheckInId) {
	$controller = new KioskCheckInController();
	$controller->getById($kioskCheckInId);
});

$app->post('/kioskCheckIns', function() {
	$controller = new KioskCheckInController();
	$controller->create();
});

$app->post('/kioskCheckIns/:kioskCheckInId/checkOut', function($kioskCheckInId) {
	$controller = new KioskCheckInController();
	$controller->checkOut($kioskCheckInId);
});


//Report
$app->get('/reports/familyHours', function() {
	$controller = new ReportController();
	$controller->getFamilyHours();
});


//Rights
$app->get('/roles/:roleId/rights', function($roleId) {
	$controller = new RightController();
	$controller->getByRoleId($roleId);
});

//Routes specifically for supporting integration testing
$app->delete('/test/volunteers/:volunteerId', function($volunteerId) {
	$controller = new TestController();
	$controller->deleteTestVolunteer($volunteerId);
});


$app->run();

