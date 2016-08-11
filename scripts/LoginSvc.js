
var LoginSvc = function(loginDiv) {
	var self = this;

	this.getCredentialsFromForm = function () {
		var credentials = {
			emailAddress: loginDiv.find("#loginEmailAddress").val(),
			password: loginDiv.find("#loginPassword").val()
		}

		return credentials;
	};

	this.validate = function(credentials) {
		var errors = [];

		//Email
		if(hasValue(credentials.emailAddress)) {
			if(!isValidEmail(credentials.emailAddress)) {
				errors.push(ValidationErrorCodes.LOGIN_EMAIL_INVALID);
			}
		}
		else {
			errors.push(ValidationErrorCodes.LOGIN_EMAIL_REQUIRED);
		}

		//Password
		if(!hasValue(credentials.password)) errors.push(ValidationErrorCodes.LOGIN_PASSWORD_REQUIRED);

		return errors;
	};

	this.getAccessToken = function(credentials, callback) {
		var url = "api/accessToken";
		$.ajax(url, {
			type: "POST",
			data: JSON.stringify(credentials),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {

				callback(null, data.access_token);
			},
			error: function(jqXHR, textStatus, errorThrown) {

				callback({"route": "POST " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

	this.getAccessTokenById = function(volunteerId, callback) {
		var url = "api/volunteers/" + volunteerId + "/accessToken";
		$.ajax(url, {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data.access_token);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "GET " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

   this.logout = function() {
		sessionMgr.clearSession();
		document.location.reload();
	};

	this.switchUser = function(volunteerId) {
		self.getAccessTokenById(volunteerId, function(err, token) {

			setTimeout(function(){
				sessionMgr.setAccessToken(token);
				sessionMgr.getVolunteerId();
				document.location.reload();
			}, 1);
		})
	};
};
