
var VolunteerSvc = function(profileDiv) {
	this.setFocusOnFirstName = function() {
		profileDiv.find("#firstName").focus();
	};

	this.getFromForm = function() {
		var volunteer = {
			emailAddress: profileDiv.find("#email").val(),
			confirmEmailAddress: profileDiv.find("#confirmEmail").val(),
			firstName: profileDiv.find("#firstName").val(),
			lastName: profileDiv.find("#lastName").val(),
			familyId: profileDiv.find("#familyId").val(),
			primaryPhoneNbr: profileDiv.find("#primaryPhoneNbr").val(),
			password: profileDiv.find("#password").val(),
			confirmPassword:  profileDiv.find("#confirmPassword").val()
		};

		return volunteer;
	};

	this.populateForm = function(volunteer) {
		var UNCHANGED_PASSWORD = "UNCHANGED";
		if(volunteer) {
			profileDiv.find("#email").val(volunteer.emailAddress);
			profileDiv.find("#confirmEmail").val(volunteer.emailAddress);
			profileDiv.find("#firstName").val(volunteer.firstName);
			profileDiv.find("#lastName").val(volunteer.lastName);
			profileDiv.find("#familyId").val(volunteer.familyId);
			profileDiv.find("#primaryPhoneNbr").val(volunteer.primaryPhoneNbr);
			profileDiv.find("#password").val(UNCHANGED_PASSWORD);
			profileDiv.find("#confirmPassword").val(UNCHANGED_PASSWORD);
		}
	};

	this.validate = function(volunteer, currentVolunteer) {
		var errors = [];

		//First Name
		if(!hasValue(volunteer.firstName)) errors.push(ValidationErrorCodes.FIRST_NAME_REQUIRED);

		//Last Name
		if(!hasValue(volunteer.lastName)) errors.push(ValidationErrorCodes.LAST_NAME_REQUIRED);

		//Email
		if(hasValue(volunteer.emailAddress)) {
			if(isValidEmail(volunteer.emailAddress)) {
				if(hasValue(volunteer.confirmEmailAddress)) {
					if(volunteer.emailAddress != volunteer.confirmEmailAddress) {
						errors.push(ValidationErrorCodes.CONFIRM_EMAIL_DOESNT_MATCH);
					}
				}
				else {
					errors.push(ValidationErrorCodes.CONFIRM_EMAIL_REQUIRED);
				}
			}
			else {
				errors.push(ValidationErrorCodes.EMAIL_INVALID);
			}
		}
		else {
			errors.push(ValidationErrorCodes.EMAIL_REQUIRED);
		}

		//Password
		if(hasValue(volunteer.password)) {
			if(isValidPassword(volunteer.password)) {
				if(hasValue(volunteer.confirmPassword)) {
					if(volunteer.password != volunteer.confirmPassword) {
						errors.push(ValidationErrorCodes.CONFIRM_PASSWORD_DOESNT_MATCH);
					}
				}
				else {
					errors.push(ValidationErrorCodes.CONFIRM_PASSWORD_REQUIRED);
				}
			}
			else {
				errors.push(ValidationErrorCodes.PASSWORD_INVALID);
			}
		}
		else {
			errors.push(ValidationErrorCodes.PASSWORD_REQUIRED);
		}

		//Family ID
		if(!hasValue(volunteer.familyId)) errors.push(ValidationErrorCodes.FAMILY_ID_REQUIRED);

		//If the volunteer is an existing volunteer, make sure they aren't changing too many fields at the same time
		if(currentVolunteer) {
			if(volunteer.firstName != currentVolunteer.firstName
				&& volunteer.lastName != currentVolunteer.lastName
				&& volunteer.emailAddress != currentVolunteer.emailAddress) {

				errors.push(ValidationErrorCodes.CANT_CHANGE_AT_SAME_TIME);
			}
		}

		return errors;
	};

	this.get = function(id, callback) {
		var url = "api/volunteers/" + id;
		$.ajax(url, {
			type: "GET",
			success: function(data, textStatus, jqXHR) {
				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "GET " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

	this.save = function(volunteer, callback) {
		var url = "api/volunteers";
		$.ajax(url, {
			type: "POST",
			data: JSON.stringify(volunteer),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {

				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(jqXHR.status == '409') {
					callback(new Error('Email taken'));
				}
				else {
					callback({"route": "POST " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
				}
			}
		});
	};

	this.update = function(volunteer, callback) {
		var url = "api/volunteers/" + volunteer.id;
		$.ajax(url, {
			type: "PUT",
			data: JSON.stringify(volunteer),
			contentType: "application/json",
			success: function(data, textStatus, jqXHR) {

				callback(null, data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				callback({"route": "PUT " + url, "jqXHR": jqXHR, "textStatus": textStatus, "errorThrown": errorThrown});
			}
		});
	};

};


