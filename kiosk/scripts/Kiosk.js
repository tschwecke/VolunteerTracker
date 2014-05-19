var Kiosk = function(window) {
	this.run = function() {

		if(document.location.href.indexOf('?') == -1) {
			document.location = 'index.html?#/login';
		}

		var tokenStore = new TokenStore(window.sessionStorage);
		var notificationMgr = new NotificationMgr();
		var restMgr = new RestMgr(tokenStore);
		var authSvc = new AuthSvc(restMgr, tokenStore);
		var volunteerSvc = new VolunteerSvc(authSvc, restMgr);
		var volunteerAreaSvc = new VolunteerAreaSvc(authSvc, restMgr);
		var checkInSvc = new CheckInSvc(authSvc, volunteerSvc, volunteerAreaSvc, restMgr);

		var loginController = new LoginController(riot, authSvc, notificationMgr);
		var homeController = new HomeController(riot);
		var checkInController = new CheckInController(riot, volunteerSvc, volunteerAreaSvc, checkInSvc, notificationMgr);
		var checkOutController = new CheckOutController(riot, checkInSvc);

		riot.route(function(path) {
			//Find the right controller to handle the request, and default to the home controller
			var controller = homeController;
			switch(path) {
				case '#login':
					controller = loginController;
					break;
				case '#checkin':
					controller = checkInController;
					break;
				case '#checkout':
					controller = checkOutController;
					break;
			}

			controller.render();
		});


		if(tokenStore.hasToken()) {
			riot.route('#home');
		}
		else {
			riot.route('#login');
		}
	};
};
