var LoginController = function(router, authSvc, notificationMgr) {

	this.render = function() {
		
		console.log('LoginController.render()');

		var credentials = {
			"emailAddress": "kioskadmin@aspenviewacademy.org",
			"password": "admin123"
		};

		var loginRactive = new Ractive({
		  el: 'viewContainer',
		  template: '#loginViewTemplate',
		  data: credentials
		});

		loginRactive.on("login", function() {
			console.log('LoginController.render.onLogin("' + credentials.emailAddress + '", "' + credentials.password + '");');
			authSvc.login(credentials.emailAddress, credentials.password, function(error) {
				if(error) {
					if(error.statusCode === 401) {
						notificationMgr.showError("The email and password you entered are not valid.  Please try again.");
					}
					else {
						notificationMgr.showError("An error occurred while trying to sign in.  Please try again.");
					}
				}
				else {
					notificationMgr.clearError();
					loginRactive.teardown(function() {
						router.setRoute("home");
					});
				}
			});
		});

	};
};