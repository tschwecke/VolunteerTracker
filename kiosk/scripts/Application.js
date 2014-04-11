var Kiosk = {};
Kiosk.run = function() {

	var notificationMgr = new NotificationMgr();
	var restMgr = new RestMgr();
	var authSvc = new AuthSvc(restMgr);
	var volunteerSvc = new VolunteerSvc(authSvc, restMgr);
	var volunteerAreaSvc = new VolunteerAreaSvc(authSvc, restMgr);
	var checkInSvc = new CheckInSvc(authSvc, volunteerSvc, volunteerAreaSvc, restMgr);

	var router = new Router().init();
	var loginController = new LoginController(router, authSvc, notificationMgr);
	var homeController = new HomeController(router);
	var checkInController = new CheckInController(router, volunteerSvc, volunteerAreaSvc, checkInSvc, notificationMgr);
	var checkOutController = new CheckOutController(router, checkInSvc);

	router.on("login", loginController.render);
	router.on("home", homeController.render);
	router.on("checkin", checkInController.render);
	router.on("checkout", checkOutController.render);

	router.setRoute("login");
};

