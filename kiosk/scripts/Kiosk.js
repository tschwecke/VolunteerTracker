var Kiosk = function(window) {
	this.run = function() {

		var tokenStore = new TokenStore(window.sessionStorage);
		var notificationMgr = new NotificationMgr();
		var restMgr = new RestMgr(tokenStore);
		var authSvc = new AuthSvc(restMgr, tokenStore);
		var volunteerSvc = new VolunteerSvc(authSvc, restMgr);
		var volunteerAreaSvc = new VolunteerAreaSvc(authSvc, restMgr);
		var checkInSvc = new CheckInSvc(authSvc, volunteerSvc, volunteerAreaSvc, restMgr);

		var router = new Router().init();
		var loginController = new LoginController(router, authSvc, notificationMgr);
		var homeController = new HomeController(router);
		var checkInController = new CheckInController(router, volunteerSvc, volunteerAreaSvc, checkInSvc, notificationMgr);
		var checkOutController = new CheckOutController(router, checkInSvc);

		router.on('login', loginController.render);
		router.on('home', homeController.render);
		router.on('checkin', checkInController.render);
		router.on('checkout', checkOutController.render);

		if(tokenStore.hasToken()) {
			router.setRoute('home');
		}
		else {
			router.setRoute('login');
		}
	};
};
