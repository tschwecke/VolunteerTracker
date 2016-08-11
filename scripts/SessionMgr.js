
var SessionMgr = function() {
	var self = this;
	var accessToken = null;

	this.isAuthenticated = function() {
		if(self.getAccessToken()) {
			return true;
		}
		else {
			return false;
		}
	};

	this.getAccessToken = function() {
		if(!accessToken) {
			accessToken = sessionStorage["VolunteerAccessToken"];
		}

		return accessToken;
	};

	this.setAccessToken = function(newAccessToken) {
		accessToken = newAccessToken;

		if(sessionStorage) {
			sessionStorage["VolunteerAccessToken"] = accessToken;
		}
	};

	this.clearSession = function() {
		accessToken = null;

		if(sessionStorage) {
			sessionStorage.removeItem("VolunteerAccessToken");
		}
	};

	this.getVolunteerId = function() {
		var token = self.getAccessToken();
		if(token) {
			var tokenParts = token.split("|");
			if(tokenParts.length === 3) {
				return tokenParts[0];
			}
		}
	};

	//Attach an event handler so that the access token is always added to every request
	$(document).ajaxSend(function(evt, request, settings){
		var token = self.getAccessToken();
		if(token) {
			request.setRequestHeader("X-Authentication", token);
		}
	});

	//Attach an event handler so that we always grab a new access token if it is sent on a response
	$(document).ajaxSuccess(function(evt, response, settings) {
		var newAccessToken = response.getResponseHeader("X-Authentication");
		if(newAccessToken) {
			self.setAccessToken(newAccessToken);
		}
	});

	//Attach an event handler so that if we get a 401 we know the user's sessions has aexpiored and we send them back to the login screen
	$(document).ajaxError(function(evt, response, settings) {
		if(response.status == 401 && self.isAuthenticated()) {
			notificationMgr.notify("Your session has expired.", function() {
				loginSvc.logout();
			});
		}
	});
};
