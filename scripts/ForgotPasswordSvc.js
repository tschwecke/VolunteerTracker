
var VolunteerSvc = function(passwordResetDiv) {

	this.setFocusOnEmailAddress = function() {
		passwordResetDiv.find("#forgottenEmailAddress").focus();
	};

	this.getFromForm = function() {
		var volunteer = {
			emailAddress: passwordResetDiv.find("#email").val()
		};

		return volunteer;
	};

	this.validate = function(volunteer, currentVolunteer) {
		var errors = [];

		//Email
		if(hasValue(volunteer.emailAddress)) {
			if(!isValidEmail(volunteer.emailAddress)) {
				errors.push(ValidationErrorCodes.EMAIL_INVALID);
			}
		}
		else {
			errors.push(ValidationErrorCodes.EMAIL_REQUIRED);
		}

		return errors;
	};

	this.submit = function(emailAddress, callback) {

    var passwordResetRequest = {
      emailAddress: emailAddress
    };

		var url = "api/passwordReset";
		$.ajax(url, {
			type: "POST",
			data: JSON.stringify(passwordResetRequest),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "POST " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

};


